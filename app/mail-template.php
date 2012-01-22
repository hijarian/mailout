<?php
/**
 * Шаблон для генерации текста письма
 *
 */

// Начинаем выдавать текст.
$mail_text = <<<ENDL
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>demo-template</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body bgcolor="#ffffff">
<h1>Hello, recipient number ######! :)</h1>
// <img src="http://myaddress.dom/mailout/app/images/demo.gif" alt="Demo image in template. Check twice that it's source attribute points at URL, not a relative path." />
</body>
</html>
ENDL;

// На этом месте мы получаем переменную $mail_text, которую вызывающий скрипт может вставлять целиком в письмо.