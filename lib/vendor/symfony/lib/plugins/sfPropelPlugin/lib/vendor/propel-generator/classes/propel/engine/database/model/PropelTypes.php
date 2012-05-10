<?php
/*
 *  $Id: PropelTypes.php 1262 2009-10-26 20:54:39Z francois $
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
 * A class that maps PropelTypes to PHP native types, PDO types (and Creole types).
 *
 * @author     Hans Lellelid <hans@xmpl.org> (Propel)
 * @version    $Revision: 1262 $
 * @package    propel.engine.database.model
 */
class PropelTypes {

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

	private static $TEXT_TYPES = array (
						self::CHAR, self::VARCHAR, self::LONGVARCHAR, self::CLOB, self::DATE, self::TIME, self::TIMESTAMP, self::BU_DATE, self::BU_TIMESTAMP
					);

	private static $LOB_TYPES = array (
						self::VARBINARY, self::LONGVARBINARY, self::BLOB
					);

	private static $TEMPORAL_TYPES = array (
						self::DATE, self::TIME, self::TIMESTAMP, self::BU_DATE, self::BU_TIMESTAMP
					);

	private static $NUMERIC_TYPES = array (
						self::SMALLINT, self::TINYINT, self::INTEGER, self::BIGINT, self::FLOAT, self::DOUBLE, self::NUMERIC, self::DECIMAL, self::REAL
					);

	const CHAR_NATIVE_TYPE = "string";
	const VARCHAR_NATIVE_TYPE = "string";
	const LONGVARCHAR_NATIVE_TYPE = "string";
	const CLOB_NATIVE_TYPE = "string"; // Clob
	const NUMERIC_NATIVE_TYPE = "string";
	const DECIMAL_NATIVE_TYPE = "string";
	const BOOLEAN_NATIVE_TYPE = "boolean";
	const TINYINT_NATIVE_TYPE = "int";
	const SMALLINT_NATIVE_TYPE = "int";
	const INTEGER_NATIVE_TYPE = "int";
	const BIGINT_NATIVE_TYPE = "string";
	const REAL_NATIVE_TYPE = "double";
	const FLOAT_NATIVE_TYPE = "double";
	const DOUBLE_NATIVE_TYPE = "double";
	const BINARY_NATIVE_TYPE = "string";
	const VARBINARY_NATIVE_TYPE = "string";
	const LONGVARBINARY_NATIVE_TYPE = "string";
	const BLOB_NATIVE_TYPE = "resource";
	const BU_DATE_NATIVE_TYPE = "string";
	const DATE_NATIVE_TYPE = "string";
	const TIME_NATIVE_TYPE = "string";
	const TIMESTAMP_NATIVE_TYPE = "string";
	const BU_TIMESTAMP_NATIVE_TYPE = "string";

	/**
	 * Mapping between Propel types and PHP native types.
	 *
	 * @var        array
	 */
	private static $propelToPHPNativeMap = array(
			self::CHAR => self::CHAR_NATIVE_TYPE,
			self::VARCHAR => self::VARCHAR_NATIVE_TYPE,
			self::LONGVARCHAR => self::LONGVARCHAR_NATIVE_TYPE,
			self::CLOB => self::CLOB_NATIVE_TYPE,
			self::NUMERIC => self::NUMERIC_NATIVE_TYPE,
			self::DECIMAL => self::DECIMAL_NATIVE_TYPE,
			self::TINYINT => self::TINYINT_NATIVE_TYPE,
			self::SMALLINT => self::SMALLINT_NATIVE_TYPE,
			self::INTEGER => self::INTEGER_NATIVE_TYPE,
			self::BIGINT => self::BIGINT_NATIVE_TYPE,
			self::REAL => self::REAL_NATIVE_TYPE,
			self::FLOAT => self::FLOAT_NATIVE_TYPE,
			self::DOUBLE => self::DOUBLE_NATIVE_TYPE,
			self::BINARY => self::BINARY_NATIVE_TYPE,
			self::VARBINARY => self::VARBINARY_NATIVE_TYPE,
			self::LONGVARBINARY => self::LONGVARBINARY_NATIVE_TYPE,
			self::BLOB => self::BLOB_NATIVE_TYPE,
			self::DATE => self::DATE_NATIVE_TYPE,
			self::BU_DATE => self::BU_DATE_NATIVE_TYPE,
			self::TIME => self::TIME_NATIVE_TYPE,
			self::TIMESTAMP => self::TIMESTAMP_NATIVE_TYPE,
			self::BU_TIMESTAMP => self::BU_TIMESTAMP_NATIVE_TYPE,
			self::BOOLEAN => self::BOOLEAN_NATIVE_TYPE,
	);

	/**
	 * Mapping between Propel types and Creole types (for rev-eng task)
	 *
	 * @var        array
	 */
	private static $propelTypeToCreoleTypeMap = array(

			self::CHAR => self::CHAR,
			self::VARCHAR => self::VARCHAR,
			self::LONGVARCHAR => self::LONGVARCHAR,
			self::CLOB => self::CLOB,
			self::NUMERIC => self::NUMERIC,
			self::DECIMAL => self::DECIMAL,
			self::TINYINT => self::TINYINT,
			self::SMALLINT => self::SMALLINT,
			self::INTEGER => self::INTEGER,
			self::BIGINT => self::BIGINT,
			self::REAL => self::REAL,
			self::FLOAT => self::FLOAT,
			self::DOUBLE => self::DOUBLE,
			self::BINARY => self::BINARY,
			self::VARBINARY => self::VARBINARY,
			self::LONGVARBINARY => self::LONGVARBINARY,
			self::BLOB => self::BLOB,
			self::DATE => self::DATE,
			self::TIME => self::TIME,
			self::TIMESTAMP => self::TIMESTAMP,
			self::BOOLEAN => self::BOOLEAN,

			// These are pre-epoch dates, which we need to map to String type
			// since they cannot be properly handled using strtotime() -- or even numeric
			// timestamps on Windows.
			self::BU_DATE => self::VARCHAR,
			self::BU_TIMESTAMP => self::VARCHAR,

	);

	/**
	 * Mapping between Propel types and PDO type contants (for prepared statement setting).
	 *
	 * @var        array
	 */
	private static $propelTypeToPDOTypeMap = array(
			self::CHAR => PDO::PARAM_STR,
			self::VARCHAR => PDO::PARAM_STR,
			self::LONGVARCHAR => PDO::PARAM_STR,
			self::CLOB => PDO::PARAM_STR,
			self::NUMERIC => PDO::PARAM_INT,
			self::DECIMAL => PDO::PARAM_STR,
			self::TINYINT => PDO::PARAM_INT,
			self::SMALLINT => PDO::PARAM_INT,
			self::INTEGER => PDO::PARAM_INT,
			self::BIGINT => PDO::PARAM_INT,
			self::REAL => PDO::PARAM_STR,
			self::FLOAT => PDO::PARAM_STR,
			self::DOUBLE => PDO::PARAM_STR,
			self::BINARY => PDO::PARAM_STR,
			self::VARBINARY => PDO::PARAM_LOB,
			self::LONGVARBINARY => PDO::PARAM_LOB,
			self::BLOB => PDO::PARAM_LOB,
			self::DATE => PDO::PARAM_STR,
			self::TIME => PDO::PARAM_STR,
			self::TIMESTAMP => PDO::PARAM_STR,
			self::BOOLEAN => PDO::PARAM_BOOL,

			// These are pre-epoch dates, which we need to map to String type
			// since they cannot be properly handled using strtotime() -- or even numeric
			// timestamps on Windows.
			self::BU_DATE => PDO::PARAM_STR,
			self::BU_TIMESTAMP => PDO::PARAM_STR,
	);

	/**
	 * Return native PHP type which corresponds to the
	 * Creole type provided. Use in the base object class generation.
	 *
	 * @param      $propelType The Propel type name.
	 * @return     string Name of the native PHP type
	 */
	public static function getPhpNative($propelType)
	{
		return self::$propelToPHPNativeMap[$propelType];
	}

	/**
	 * Returns the correct Creole type _name_ for propel added types
	 *
	 * @param      $type the propel added type.
	 * @return     string Name of the the correct Creole type (e.g. "VARCHAR").
	 */
	public static function getCreoleType($type)
	{
		return  self::$propelTypeToCreoleTypeMap[$type];
	}

	/**
	 * Resturns the PDO type (PDO::PARAM_* constant) value.
	 * @return     int
	 */
	public static function getPDOType($type)
	{
		return self::$propelTypeToPDOTypeMap[$type];
	}

	/**
	 * Returns Propel type constant corresponding to Creole type code.
	 * Used but Propel Creole task.
	 *
	 * @param      int $sqlType The Creole SQL type constant.
	 * @return     string The Propel type to use or NULL if none found.
	 */
	public static function getPropelType($sqlType)
	{
		if (isset(self::$creoleToPropelTypeMap[$sqlType])) {
			return self::$creoleToPropelTypeMap[$sqlType];
		}
	}

	/**
	 * Get array of Propel types.
	 *
	 * @return     array string[]
	 */
	public static function getPropelTypes()
	{
		return array_keys(self::$propelTypeToCreoleTypeMap);
	}

	/**
	 * Whether passed type is a temporal (date/time/timestamp) type.
	 *
	 * @param      string $type Propel type
	 * @return     boolean
	 */
	public static function isTemporalType($type)
	{
		return in_array($type, self::$TEMPORAL_TYPES);
	}

	/**
	 * Returns true if values for the type need to be quoted.
	 *
	 * @param      string $type The Propel type to check.
	 * @return     boolean True if values for the type need to be quoted.
	 */
	public static function isTextType($type)
	{
		return in_array($type, self::$TEXT_TYPES);
	}

	/**
	 * Returns true if values for the type are numeric.
	 *
	 * @param      string $type The Propel type to check.
	 * @return     boolean True if values for the type need to be quoted.
	 */
	public static function isNumericType($type)
	{
		return in_array($type, self::$NUMERIC_TYPES);
	}

	/**
	 * Returns true if type is a LOB type (i.e. would be handled by Blob/Clob class).
	 * @param      string $type Propel type to check.
	 * @return     boolean
	 */
	public static function isLobType($type)
	{
		return in_array($type, self::$LOB_TYPES);
	}

	/**
	 * Convenience method to indicate whether a passed-in PHP type is a primitive.
	 *
	 * @param      string $phpType The PHP type to check
	 * @return     boolean Whether the PHP type is a primitive (string, int, boolean, float)
	 */
	public static function isPhpPrimitiveType($phpType)
	{
		return in_array($phpType, array("boolean", "int", "double", "float", "string"));
	}

	/**
	 * Convenience method to indicate whether a passed-in PHP type is a numeric primitive.
	 *
	 * @param      string $phpType The PHP type to check
	 * @return     boolean Whether the PHP type is a primitive (string, int, boolean, float)
	 */
	public static function isPhpPrimitiveNumericType($phpType)
	{
		return in_array($phpType, array("boolean", "int", "double", "float"));
	}

	/**
	 * Convenience method to indicate whether a passed-in PHP type is an object.
	 *
	 * @param      string $phpType The PHP type to check
	 * @return     boolean Whether the PHP type is a primitive (string, int, boolean, float)
	 */
	public static function isPhpObjectType($phpType)
	{
		return (!self::isPhpPrimitiveType($phpType) && !in_array($phpType, array("resource", "array")));
	}
}
