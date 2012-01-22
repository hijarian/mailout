<?php
require_once "RecipientsDB.php";
require_once "Recipient.php";
/// Generic abstract class extending PHPUnit_Extensions_Database_TestCase and providing definition for getConnection() method. See http://www.phpunit.de/manual/current/en/database.html#tip:-use-your-own-abstract-database-testcase
require_once "DatabaseTestCase.php";

class RecipientsDBTest extends DatabaseTestCase
{
	protected function getDataSet()
	{
		return $this->getYAMLDataSet('fixtures/SampleRecipients.yml');
	}

	public function setUp()
	{
		parent::setUp();
		RecipientsDB::useSQLiteFile(dirname(__FILE__).'/recipients.test.db');
	}

	public function testGetRecipientByID()
	{
		$recipient = RecipientsDB::getRecipientByID(1);
		$this->assertEquals('normal@visitor.dom', $recipient->email);
	}

	public function Visitors()
	{
		return array(
			array(1, 'normal', 1, 2, 3, false, 'Visited.yml'),
			array(2, 'angry', 1, 0, 0, true, 'UnsubscribedInstantly.yml'),
			array(3, 'uninterested', 1, 2, 3, true, 'VisitedAndUnsubscribed.yml'),
			array(4, 'cunning', 0, 0, 1, false, 'DidNotLoadImages.yml')
		);
	}

	/**
	 * @depends testGetRecipientByID
	 * @dataProvider Visitors
	 */
	public function testVisitor($visitor_id, $codename, $opened, $homepage, $catalog, $unsubscribed, $dataset_filename)
	{
		call_user_func(array($this, $codename.'VisitorActions'), $visitor_id);

		$this->checkStatistics($visitor_id, $opened, $homepage, $catalog, $unsubscribed);
		
		$this->compareWithDataset('fixtures/'.$dataset_filename);
	}

	private function checkStatistics($id, $opened, $homepage, $catalog, $unsubscribed)
	{
		$recipient = RecipientsDB::getRecipientByID($id);
		$this->assertEquals($opened, $recipient->mailout_visits);
		$this->assertEquals($homepage, $recipient->homepage_visits);
		$this->assertEquals($catalog, $recipient->catalog_visits);
		$this->assertSame($unsubscribed, $recipient->is_unsubscribed);
	}

	private function compareWithDataset($dataset_filename)
	{
		$init_dataset = $this->getConnection()->createDataSet()->getTable('recipients');
		$expected_dataset = $this->getYAMLDataSet($dataset_filename)->getTable('recipients');
		$this->assertTablesEqual($expected_dataset, $init_dataset);
	}

	private function normalVisitorActions($visitor_id)
	{
		RecipientsDB::openedEmail($visitor_id);
		RecipientsDB::visitedHomepage($visitor_id);
		RecipientsDB::visitedHomepage($visitor_id);
		RecipientsDB::visitedCatalog($visitor_id);
		RecipientsDB::visitedCatalog($visitor_id);
		RecipientsDB::visitedCatalog($visitor_id);
	}

	private function uninterestedVisitorActions($visitor_id)
	{
		$this->normalVisitorActions($visitor_id);
		RecipientsDB::unsubscribed($visitor_id);
	}

	private function angryVisitorActions($visitor_id)
	{
		RecipientsDB::openedEmail($visitor_id);
		RecipientsDB::unsubscribed($visitor_id);
	}

	private function cunningVisitorActions($visitor_id)
	{
		RecipientsDB::visitedCatalog($visitor_id);
	}

	public function RecipientsList()
	{
		return array(
			array(
				1, 
				array(
					'1' => 'normal@visitor.dom',
					'2' => 'angry@visitor.dom'
				)
			),
			array(
				22,
				array(
					'3' => 'not.interested@visitor.dom',
					'4' => 'cunning@visitor.dom'
				)
			)
		);
	}
	/**
	 * @dataProvider RecipientsList
	 */
	public function testGetEmailsByGID($gid, $expected_list)
	{
		$list = RecipientsDB::getActiveEmailsByGID($gid);
		$this->assertEquals($expected_list, $list);
	}
	
	public function testGetAllEmails()
	{
		$expected_list = array(
			'1' => 'normal@visitor.dom',
			'2' => 'angry@visitor.dom',
			'3' => 'not.interested@visitor.dom',
			'4' => 'cunning@visitor.dom'
		);
		$list = RecipientsDB::getAllActiveEmails();
		$this->assertEquals($expected_list, $list);
	}
	
}
?>