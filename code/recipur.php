#!/usr/bin/php
<?php
$db = new PDO('sqlite:'.dirname(__FILE__).'/recipients.db');
if ($db->query('update recipients set homepage=0, opened=0, catalog=0'))
{
	echo "Counters cleared successfully!\n";
}
else
{
	echo "Error while clearing counters!\n";
	print_r($db->errorInfo());
}
if ($db->query('update recipients set ignore=1 where unsubscribed=1'))
{
	echo "Unsubscribed recipients added to permanently ignored!\n";
}
else
{
	echo "Error while remembering unsubscribed recipients!\n";
	print_r($db->errorInfo());
}
if ($db->query('update recipients set unsubscribed=0 where unsubscribed=1'))
{
	echo "Information about subscription cancellations cleared!\n";
}
else
{
	echo "Error while crearing unsubscription information!\n";
	print_r($db->errorInfo());
}


