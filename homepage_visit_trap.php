<?php
require_once "code/RecipientsDB_WebDriver.php";
if (isset($_GET['recipient_id']))
{
	RecipientsDB_WebDriver::recipientVisitedHomepage($_GET['recipient_id']);
}
?>