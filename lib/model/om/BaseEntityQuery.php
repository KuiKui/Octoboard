<?php


/**
 * Base class that represents a query for the 'entity' table.
 *
 * 
 *
 * @method     EntityQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     EntityQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     EntityQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method     EntityQuery orderByAverageValue($order = Criteria::ASC) Order by the average_value column
 * @method     EntityQuery orderByAverageCount($order = Criteria::ASC) Order by the average_count column
 * @method     EntityQuery orderByHistory($order = Criteria::ASC) Order by the history column
 * @method     EntityQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     EntityQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     EntityQuery groupById() Group by the id column
 * @method     EntityQuery groupByName() Group by the name column
 * @method     EntityQuery groupByValue() Group by the value column
 * @method     EntityQuery groupByAverageValue() Group by the average_value column
 * @method     EntityQuery groupByAverageCount() Group by the average_count column
 * @method     EntityQuery groupByHistory() Group by the history column
 * @method     EntityQuery groupByCreatedAt() Group by the created_at column
 * @method     EntityQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     EntityQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     EntityQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     EntityQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     Entity findOne(PropelPDO $con = null) Return the first Entity matching the query
 * @method     Entity findOneOrCreate(PropelPDO $con = null) Return the first Entity matching the query, or a new Entity object populated from the query conditions when no match is found
 *
 * @method     Entity findOneById(int $id) Return the first Entity filtered by the id column
 * @method     Entity findOneByName(string $name) Return the first Entity filtered by the name column
 * @method     Entity findOneByValue(int $value) Return the first Entity filtered by the value column
 * @method     Entity findOneByAverageValue(string $average_value) Return the first Entity filtered by the average_value column
 * @method     Entity findOneByAverageCount(int $average_count) Return the first Entity filtered by the average_count column
 * @method     Entity findOneByHistory(string $history) Return the first Entity filtered by the history column
 * @method     Entity findOneByCreatedAt(string $created_at) Return the first Entity filtered by the created_at column
 * @method     Entity findOneByUpdatedAt(string $updated_at) Return the first Entity filtered by the updated_at column
 *
 * @method     array findById(int $id) Return Entity objects filtered by the id column
 * @method     array findByName(string $name) Return Entity objects filtered by the name column
 * @method     array findByValue(int $value) Return Entity objects filtered by the value column
 * @method     array findByAverageValue(string $average_value) Return Entity objects filtered by the average_value column
 * @method     array findByAverageCount(int $average_count) Return Entity objects filtered by the average_count column
 * @method     array findByHistory(string $history) Return Entity objects filtered by the history column
 * @method     array findByCreatedAt(string $created_at) Return Entity objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return Entity objects filtered by the updated_at column
 *
 * @package    propel.generator.lib.model.om
 */
abstract class BaseEntityQuery extends ModelCriteria
{
	
	/**
	 * Initializes internal state of BaseEntityQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'Entity', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new EntityQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    EntityQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof EntityQuery) {
			return $criteria;
		}
		$query = new EntityQuery();
		if (null !== $modelAlias) {
			$query->setModelAlias($modelAlias);
		}
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

	/**
	 * Find object by primary key.
	 * Propel uses the instance pool to skip the database if the object exists.
	 * Go fast if the query is untouched.
	 *
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    Entity|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ($key === null) {
			return null;
		}
		if ((null !== ($obj = EntityPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
			// the object is alredy in the instance pool
			return $obj;
		}
		if ($con === null) {
			$con = Propel::getConnection(EntityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		$this->basePreSelect($con);
		if ($this->formatter || $this->modelAlias || $this->with || $this->select
		 || $this->selectColumns || $this->asColumns || $this->selectModifiers
		 || $this->map || $this->having || $this->joins) {
			return $this->findPkComplex($key, $con);
		} else {
			return $this->findPkSimple($key, $con);
		}
	}

	/**
	 * Find object by primary key using raw SQL to go fast.
	 * Bypass doSelect() and the object formatter by using generated code.
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con A connection object
	 *
	 * @return    Entity A model object, or null if the key is not found
	 */
	protected function findPkSimple($key, $con)
	{
		$sql = 'SELECT `ID`, `NAME`, `VALUE`, `AVERAGE_VALUE`, `AVERAGE_COUNT`, `HISTORY`, `CREATED_AT`, `UPDATED_AT` FROM `entity` WHERE `ID` = :p0';
		try {
			$stmt = $con->prepare($sql);
			$stmt->bindValue(':p0', $key, PDO::PARAM_INT);
			$stmt->execute();
		} catch (Exception $e) {
			Propel::log($e->getMessage(), Propel::LOG_ERR);
			throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
		}
		$obj = null;
		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$obj = new Entity();
			$obj->hydrate($row);
			EntityPeer::addInstanceToPool($obj, (string) $row[0]);
		}
		$stmt->closeCursor();

		return $obj;
	}

	/**
	 * Find object by primary key.
	 *
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con A connection object
	 *
	 * @return    Entity|array|mixed the result, formatted by the current formatter
	 */
	protected function findPkComplex($key, $con)
	{
		// As the query uses a PK condition, no limit(1) is necessary.
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		$stmt = $criteria
			->filterByPrimaryKey($key)
			->doSelect($con);
		return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(12, 56, 832), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
		}
		$this->basePreSelect($con);
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		$stmt = $criteria
			->filterByPrimaryKeys($keys)
			->doSelect($con);
		return $criteria->getFormatter()->init($criteria)->format($stmt);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(EntityPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(EntityPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterById(1234); // WHERE id = 1234
	 * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
	 * $query->filterById(array('min' => 12)); // WHERE id > 12
	 * </code>
	 *
	 * @param     mixed $id The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(EntityPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the name column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
	 * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $name The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function filterByName($name = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($name)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $name)) {
				$name = str_replace('*', '%', $name);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(EntityPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the value column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByValue(1234); // WHERE value = 1234
	 * $query->filterByValue(array(12, 34)); // WHERE value IN (12, 34)
	 * $query->filterByValue(array('min' => 12)); // WHERE value > 12
	 * </code>
	 *
	 * @param     mixed $value The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function filterByValue($value = null, $comparison = null)
	{
		if (is_array($value)) {
			$useMinMax = false;
			if (isset($value['min'])) {
				$this->addUsingAlias(EntityPeer::VALUE, $value['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($value['max'])) {
				$this->addUsingAlias(EntityPeer::VALUE, $value['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(EntityPeer::VALUE, $value, $comparison);
	}

	/**
	 * Filter the query on the average_value column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByAverageValue(1234); // WHERE average_value = 1234
	 * $query->filterByAverageValue(array(12, 34)); // WHERE average_value IN (12, 34)
	 * $query->filterByAverageValue(array('min' => 12)); // WHERE average_value > 12
	 * </code>
	 *
	 * @param     mixed $averageValue The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function filterByAverageValue($averageValue = null, $comparison = null)
	{
		if (is_array($averageValue)) {
			$useMinMax = false;
			if (isset($averageValue['min'])) {
				$this->addUsingAlias(EntityPeer::AVERAGE_VALUE, $averageValue['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($averageValue['max'])) {
				$this->addUsingAlias(EntityPeer::AVERAGE_VALUE, $averageValue['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(EntityPeer::AVERAGE_VALUE, $averageValue, $comparison);
	}

	/**
	 * Filter the query on the average_count column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByAverageCount(1234); // WHERE average_count = 1234
	 * $query->filterByAverageCount(array(12, 34)); // WHERE average_count IN (12, 34)
	 * $query->filterByAverageCount(array('min' => 12)); // WHERE average_count > 12
	 * </code>
	 *
	 * @param     mixed $averageCount The value to use as filter.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function filterByAverageCount($averageCount = null, $comparison = null)
	{
		if (is_array($averageCount)) {
			$useMinMax = false;
			if (isset($averageCount['min'])) {
				$this->addUsingAlias(EntityPeer::AVERAGE_COUNT, $averageCount['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($averageCount['max'])) {
				$this->addUsingAlias(EntityPeer::AVERAGE_COUNT, $averageCount['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(EntityPeer::AVERAGE_COUNT, $averageCount, $comparison);
	}

	/**
	 * Filter the query on the history column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByHistory('fooValue');   // WHERE history = 'fooValue'
	 * $query->filterByHistory('%fooValue%'); // WHERE history LIKE '%fooValue%'
	 * </code>
	 *
	 * @param     string $history The value to use as filter.
	 *              Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function filterByHistory($history = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($history)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $history)) {
				$history = str_replace('*', '%', $history);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(EntityPeer::HISTORY, $history, $comparison);
	}

	/**
	 * Filter the query on the created_at column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
	 * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
	 * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $createdAt The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = null)
	{
		if (is_array($createdAt)) {
			$useMinMax = false;
			if (isset($createdAt['min'])) {
				$this->addUsingAlias(EntityPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($createdAt['max'])) {
				$this->addUsingAlias(EntityPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(EntityPeer::CREATED_AT, $createdAt, $comparison);
	}

	/**
	 * Filter the query on the updated_at column
	 *
	 * Example usage:
	 * <code>
	 * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
	 * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
	 * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
	 * </code>
	 *
	 * @param     mixed $updatedAt The value to use as filter.
	 *              Values can be integers (unix timestamps), DateTime objects, or strings.
	 *              Empty strings are treated as NULL.
	 *              Use scalar values for equality.
	 *              Use array values for in_array() equivalent.
	 *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = null)
	{
		if (is_array($updatedAt)) {
			$useMinMax = false;
			if (isset($updatedAt['min'])) {
				$this->addUsingAlias(EntityPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($updatedAt['max'])) {
				$this->addUsingAlias(EntityPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(EntityPeer::UPDATED_AT, $updatedAt, $comparison);
	}

	/**
	 * Exclude object from result
	 *
	 * @param     Entity $entity Object to remove from the list of results
	 *
	 * @return    EntityQuery The current query, for fluid interface
	 */
	public function prune($entity = null)
	{
		if ($entity) {
			$this->addUsingAlias(EntityPeer::ID, $entity->getId(), Criteria::NOT_EQUAL);
		}

		return $this;
	}

} // BaseEntityQuery