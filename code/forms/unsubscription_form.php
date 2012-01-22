<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Форма отказа от рассылки</title>
</head>
<body>
	<h1>Отказаться от рассылки?</h1>
	<form action="" method="get">
		<input type="hidden" name="recipient_id" value="<?php echo $_GET['recipient_id'];?>" />
		<input type="hidden" name="accepted" value="yes" />
		<input type="submit" name="submit" value="Да, отказаться от рассылки" />
	</form>
	<p>Если вы не хотите отказываться от нашей рассылки, просто закройте эту страницу и не возвращайтесь на неё.</p>
</body>
</html>