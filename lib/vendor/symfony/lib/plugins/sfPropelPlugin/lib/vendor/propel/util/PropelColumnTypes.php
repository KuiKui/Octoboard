<?php
/*
 *  $Id: PropelColumnTypes.php 1262 2009-10-26 20:54:39Z francois $
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

/**
 * Enumeration of Propel types.
 *
 * THIS CLASS MUST BE KEPT UP-TO-DATE WITH THE MORE EXTENSIVE GENERATOR VERSION OF THIS CLASS.
 *
 * @author     Hans Lellelid <hans@xmpl.org> (Propel)
 * @version    $Revision: 1262 $
 * @package    propel.util
 */
class PropelColumnTypes {

	const CHAR = "CHAR";
	const VARCHAR = "VARCHAR";
	const LONGVARCHAR = "LONGVARCHAR";
	const CLOB = "CLOB";
	const NUMERIC = "NUMERIC";
	const DECIMAL = "DECIMAL";
	const TINYINT = "TINYINT";
	const SMALLINT = "SMALLINT";
	const INTEGER = "INTEGER";
	const BIGINT = "BIGINT";
	const REAL = "REAL";
	const FLOAT = "FLOAT";
	const DOUBLE = "DOUBLE";
	const BINARY = "BINARY";
	const VARBINARY = "VARBINARY";
	const LONGVARBINARY = "LONGVARBINARY";
	const BLOB = "BLOB";
	const DATE = "DATE";
	const TIME = "TIME";
	const TIMESTAMP = "TIMESTAMP";

	const BU_DATE = "BU_DATE";
	const BU_TIMESTAMP = "BU_TIMESTAMP";

	const BOOLEAN = "BOOLEAN";

	private static $propelToPdoMap = array(
		self::CHAR 			=> PDO::PARAM_STR,
		self::VARCHAR 		=> PDO::PARAM_STR,
		self::LONGVARCHAR 	=> PDO::PARAM_STR,
		self::CLOB 			=> PDO::PARAM_LOB,
		self::NUMERIC 		=> PDO::PARAM_STR,
		self::DECIMAL 		=> PDO::PARAM_STR,
		self::TINYINT 		=> PDO::PARAM_INT,
		self::SMALLINT 		=> PDO::PARAM_INT,
		self::INTEGER 		=> PDO::PARAM_INT,
		self::BIGINT 		=> PDO::PARAM_STR,
		self::REAL 			=> PDO::PARAM_STR,
		self::FLOAT 		=> PDO::PARAM_STR,
		self::DOUBLE 		=> PDO::PARAM_STR,
		self::BINARY 		=> PDO::PARAM_STR,
		self::VARBINARY 	=> PDO::PARAM_STR,
		self::LONGVARBINARY => PDO::PARAM_STR,
		self::BLOB 			=> PDO::PARAM_LOB,
		self::DATE 			=> PDO::PARAM_STR,
		self::TIME 			=> PDO::PARAM_STR,
		self::TIMESTAMP 	=> PDO::PARAM_STR,
		self::BU_DATE 		=> PDO::PARAM_STR,
		self::BU_TIMESTAMP 	=> PDO::PARAM_STR,
		self::BOOLEAN 		=> PDO::PARAM_BOOL,
	);

	/**
	 * Resturns the PDO type (PDO::PARAM_* constant) value for the Propel type provided.
	 * @param      string $propelType
	 * @return     int
	 */
	public static function getPdoType($propelType)
	{
		return self::$propelToPdoMap[$propelType];
	}

}
