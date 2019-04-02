#!/usr/bin/php
<?php
$db = new PDO('sqlite:'.dirname(__FILE__).'/recipients.db');
$results = $db->query('select * from recipients');
while ($row = $results->fetch(PDO::FETCH_ASSOC))
{
	$values = array(
		$row['id'],
		$row['gid'],
		$row['email'],
		$row['opened'],
		$row['homepage'],
		$row['catalog'],
		$row['unsubscribed']
	);
	echo implode(' ', $values)."\n";
}
?>
