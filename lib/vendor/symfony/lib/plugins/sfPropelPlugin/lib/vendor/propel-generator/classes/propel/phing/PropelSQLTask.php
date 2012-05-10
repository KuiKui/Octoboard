<?php

/*
 *  $Id: PropelSQLTask.php 1262 2009-10-26 20:54:39Z francois $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://propel.phpdb.org>.
 */

include_once 'propel/engine/database/model/AppData.php';

/**
 * The task for building SQL DDL based on the XML datamodel.
 *
 * This class uses the new DDLBuilder classes instead of the Capsule PHP templates.
 *
 * @author     Hans Lellelid <hans@xmpl.org>
 * @package    propel.phing
 */
class PropelSQLTask extends AbstractPropelDataModelTask {

	/**
	 * The properties file that maps an SQL file to a particular database.
	 * @var        PhingFile
	 */
	private $sqldbmap;

	/**
	 * Name of the database.
	 */
	private $database;

	/**
	 * Set the sqldbmap.
	 * @param      PhingFile $sqldbmap The db map.
	 */
	public function setSqlDbMap(PhingFile $sqldbmap)
	{
		$this->sqldbmap = $sqldbmap;
	}

	/**
	 * Get the sqldbmap.
	 * @return     PhingFile $sqldbmap.
	 */
	public function getSqlDbMap()
	{
		return $this->sqldbmap;
	}

	/**
	 * Set the database name.
	 * @param      string $database
	 */
	public function setDatabase($database)
	{
		$this->database = $database;
	}

	/**
	 * Get the database name.
	 * @return     string
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 * Create the sql -> database map.
	 *
	 * @throws     IOException - if unable to store properties
	 */
	protected function createSqlDbMap()
	{
		if ($this->getSqlDbMap() === null) {
			return;
		}

		// Produce the sql -> database map
		$sqldbmap = new Properties();

		// Check to see if the sqldbmap has already been created.
		if ($this->getSqlDbMap()->exists()) {
			$sqldbmap->load($this->getSqlDbMap());
		}

		if ($this->packageObjectModel) {
			// in this case we'll get the sql file name from the package attribute
			$dataModels = $this->packageDataModels();
			foreach ($dataModels as $package => $dataModel) {
				foreach ($dataModel->getDatabases() as $database) {
					$name = ($package ? $package . '.' : '') . 'schema.xml';
					$sqlFile = $this->getMappedFile($name);
					$sqldbmap->setProperty($sqlFile->getName(), $database->getName());
				}
			}
		} else {
			// the traditional way is to map the schema.xml filenames
			$dmMap = $this->getDataModelDbMap();
			foreach (array_keys($dmMap) as $dataModelName) {
				$sqlFile = $this->getMappedFile($dataModelName);
				if ($this->getDatabase() === null) {
					$databaseName = $dmMap[$dataModelName];
				} else {
					$databaseName = $this->getDatabase();
				}
				$sqldbmap->setProperty($sqlFile->getName(), $databaseName);
			}
		}

		try {
			$sqldbmap->store($this->getSqlDbMap(), "Sqlfile -> Database map");
		} catch (IOException $e) {
			throw new IOException("Unable to store properties: ". $e->getMessage());
		}
	}

	public function main() {

		$this->validate();

		if (!$this->mapperElement) {
			throw new BuildException("You must use a <mapper/> element to describe how names should be transformed.");
		}

		if ($this->packageObjectModel) {
			$dataModels = $this->packageDataModels();
		} else {
			$dataModels = $this->getDataModels();
		}

		// 1) first create a map of filenames to databases; this is used by other tasks like
		// the SQLExec task.
		$this->createSqlDbMap();

		// 2) Now actually create the DDL based on the datamodel(s) from XML schema file.
		$targetDatabase = $this->getTargetDatabase();

		$generatorConfig = $this->getGeneratorConfig();

		$builderClazz = $generatorConfig->getBuilderClassname('ddl');

		foreach ($dataModels as $package => $dataModel) {

			foreach ($dataModel->getDatabases() as $database) {

				// Clear any start/end DLL
				call_user_func(array($builderClazz, 'reset'));

				// file we are going to create
				if (!$this->packageObjectModel) {
					$name = $dataModel->getName();
				} else {
					$name = ($package ? $package . '.' : '') . 'schema.xml';
				}

				$outFile = $this->getMappedFile($name);

				$this->log("Writing to SQL file: " . $outFile->getPath());

				// First add any "header" SQL
				$ddl = call_user_func(array($builderClazz, 'getDatabaseStartDDL'));

				foreach ($database->getTables() as $table) {

					if (!$table->isSkipSql()) {
						$builder = $generatorConfig->getConfiguredBuilder($table, 'ddl');
						$this->log("\t+ " . $table->getName() . " [builder: " . get_class($builder) . "]");
						$ddl .= $builder->build();
						foreach ($builder->getWarnings() as $warning) {
							$this->log($warning, Project::MSG_WARN);
						}
					} else {
						$this->log("\t + (skipping) " . $table->getName());
					}

				} // foreach database->getTables()

				// Finally check to see if there is any "footer" SQL
				$ddl .= call_user_func(array($builderClazz, 'getDatabaseEndDDL'));

				#var_dump($outFile->getAbsolutePath());
				// Now we're done.  Write the file!
				file_put_contents($outFile->getAbsolutePath(), $ddl);

			} // foreach database
		} //foreach datamodels

	} // main()

	/**
	 * Packages the datamodels to one datamodel per package
	 *
	 * This applies only when the the packageObjectModel option is set. We need to
	 * re-package the datamodels to allow the database package attribute to control
	 * which tables go into which SQL file.
	 *
	 * @return     array The packaged datamodels
	 */
	protected function packageDataModels() {

		static $packagedDataModels;

		if (is_null($packagedDataModels)) {

			$dataModels = $this->getDataModels();
			$dataModel = array_shift($dataModels);
			$packagedDataModels = array();

			$platform = $this->getGeneratorConfig()->getConfiguredPlatform();

			foreach ($dataModel->getDatabases() as $db) {
				foreach ($db->getTables() as $table) {
					$package = $table->getPackage();
					if (!isset($packagedDataModels[$package])) {
						$dbClone = $this->cloneDatabase($db);
						$dbClone->setPackage($package);
						$ad = new AppData($platform);
						$ad->setName($dataModel->getName());
						$ad->addDatabase($dbClone);
						$packagedDataModels[$package] = $ad;
					}
					$packagedDataModels[$package]->getDatabase($db->getName())->addTable($table);
				}
			}
		}

		return $packagedDataModels;
	}

	protected function cloneDatabase($db) {

		$attributes = array (
			'name' => $db->getName(),
			'baseClass' => $db->getBaseClass(),
			'basePeer' => $db->getBasePeer(),
			'defaultIdMethod' => $db->getDefaultIdMethod(),
			'defaultPhpNamingMethod' => $db->getDefaultPhpNamingMethod(),
			'defaultTranslateMethod' => $db->getDefaultTranslateMethod(),
			'heavyIndexing' => $db->getHeavyIndexing(),
		);

		$clone = new Database();
		$clone->loadFromXML($attributes);
		return $clone;
	}
}
