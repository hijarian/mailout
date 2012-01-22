<?php
require_once "code/RecipientsDB.php";
$stats = RecipientsDB::getStats();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Статистика последней проведённой по рассылке на <?php echo date('d-m-Y');?></title>
</head>
<body>
	<h1>Статистика по последней проведённой рассылке на <?php echo date('d-m-Y');?></h1>
	<table border="1" cellspacing="0" cellpadding="3">
		<thead>
			<tr>
				<th>Адресов в базе</th>
				<th>Открыто писем</th>
				<th>Посещений главной страницы</th>
				<th>Посещений страницы каталога</th>
				<th>Отказов от рассылки</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $stats['address_count'];?></td>
				<td><?php echo $stats['opened_count'];?></td>
				<td><?php echo $stats['homepage_count'];?></td>
				<td><?php echo $stats['catalog_count'];?></td>
				<td><?php echo $stats['unsubscribed_count'];?></td>
			</tr>
		</tbody>
	</table>
</body>
</html>