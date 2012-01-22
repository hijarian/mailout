<?php
/**
 * Generic class for database testing with PHPUnit
 * Taken from example at http://www.phpunit.de/manual/current/en/database.html#tip:-use-your-own-abstract-database-testcase
 *
 * When using this class you should:
 * 1. Extend DatabaseTestCase, providing definition for method named getDataSet()
 * 2. Write a phpunit.xml configuration file providing values for variables $DB_DSN, $DB_DBNAME, $DB_USER and $DB_PASSWD
 */
abstract class DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{
    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

	protected function getYAMLDataSet($dataset_filename)
	{
		return new PHPUnit_Extensions_Database_DataSet_YamlDataSet(
			$dataset_filename
		);
	}

}