<?php
require_once "RecipientsDB.php";
require_once "Recipient.php";
/// Generic abstract class extending PHPUnit_Extensions_Database_TestCase and providing definition for getConnection() method. See http://www.phpunit.de/manual/current/en/database.html#tip:-use-your-own-abstract-database-testcase
require_once "DatabaseTestCase.php";

class RecipientsStatsTest extends DatabaseTestCase
{
	protected function getDataSet()
	{
		return $this->getYAMLDataSet('fixtures/SampleStatisticsData.yml');
	}

	public function setUp()
	{
		parent::setUp();
		RecipientsDB::useSQLiteFile(dirname(__FILE__).'/recipients.test.db');
	}

	public function testGetStats()
	{
		$stats = RecipientsDB::getStats();
		$this->assertNotEmpty($stats);
		$this->assertInternalType('array', $stats);
		$this->assertEquals(3, $stats['opened_count']);
		$this->assertEquals(2, $stats['homepage_count']);
		$this->assertEquals(3, $stats['catalog_count']);
		$this->assertEquals(2, $stats['unsubscribed_count']);
		$this->assertEquals(4, $stats['address_count']);
	}
}
?>