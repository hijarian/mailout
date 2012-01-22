<?php
require_once "code/RecipientsDB_WebDriver.php";
if (isset($_GET['recipient_id']))
{
	RecipientsDB_WebDriver::recipientVisitedCatalog($_GET['recipient_id']);
}
?>