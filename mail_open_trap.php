<?php
require_once "code/RecipientsDB_WebDriver.php";
if (isset($_GET['recipient_id']))
{
	RecipientsDB_WebDriver::recipientOpenedEmail($_GET['recipient_id']);
}
RecipientsDB_WebDriver::returnStubImage();
?>