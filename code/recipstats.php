#!/usr/bin/php
<?php
require_once "RecipientsDB.php";

$stats = RecipientsDB::getStats();
echo 'Статистика посещения следующая:';
print_r($stats);
?>
