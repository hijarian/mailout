<?php
require_once "code/RecipientsDB_WebDriver.php";
if (isset($_GET['recipient_id']))
{
	if (isset($_GET['accepted']))
	{
		RecipientsDB_WebDriver::recipientUnsubscribed($_GET['recipient_id']);
		RecipientsDB_WebDriver::renderUnsubscriptionReport();
	}
	else
	{
		RecipientsDB_WebDriver::renderUnsubscriptionForm();
	}
}
?>