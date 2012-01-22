<?php
require_once "Recipient.php";

class RecipientTest extends PHPUnit_Framework_TestCase
{
	public function SampleRecipients()
	{
		return array(
			array(1, 1, 'example@address.dom', 0, 0, 0, false),
			array(1, 15, 'unsubscribed@address.dom', 4, 2, 3, true),
		);
	}

	/**
	 * @dataProvider SampleRecipients
	 */
	public function testPropertiesAvailable($id, $gid, $email, $opened, $homepage, $catalog, $unsubscribed)
	{
		$recipient = new Recipient($id, $gid, $email, $opened, $homepage, $catalog, $unsubscribed);

		$this->assertEquals($id, $recipient->id);
		$this->assertEquals($gid, $recipient->gid);
		$this->assertEquals($email, $recipient->email);
		$this->assertEquals($opened, $recipient->mailout_visits);
		$this->assertEquals($homepage, $recipient->homepage_visits);
		$this->assertEquals($catalog, $recipient->catalog_visits);
		$this->assertSame($unsubscribed, $recipient->is_unsubscribed);
	}

	public function Statistics()
	{
		return array(
			array(1, 'mailout_visits', 'openedEmail'),
			array(1, 'homepage_visits', 'visitedHomepage'),
			array(1, 'catalog_visits', 'visitedCatalog')
		);
	}

	/**
	 * @dataProvider Statistics
	 * @depends testPropertiesAvailable
	 */
	public function testStatistic($init_value, $statistic_name, $changer_function)
	{
		$recipient = new Recipient(1, 1, 'example@address.dom');
		$recipient->$statistic_name = $init_value;

		$old_value = $recipient->$statistic_name;
		$this->assertEquals($init_value, $old_value);
		
		$recipient->$changer_function();
		$new_value = $recipient->$statistic_name;

		$this->assertEquals($init_value + 1, $new_value);
	}
	
	public function testUnsubscribe()
	{
		$recipient = new Recipient(1, 1, 'example@address.dom');

		$recipient->unsubscribed();

		$this->assertTrue($recipient->is_unsubscribed);
	}
}
?>
