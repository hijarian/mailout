#!/usr/bin/php
<?php
/** 
 * Скрипт для отправки почтовой рассылки по списку адресов
 * Список адресов хранится в отдельном файле.
 * Письмо генерируется в HTML-виде.
 * Для генерации тела письма выделена отдельная функция.
 *
 * Автор: Сафронов Марк a. k. a. hijarian
 * Сопровождающий: тот же.
 * Октябрь 2011
 * Не для распространения.
 */
include "../code/RecipientsDB.php";

$test_mode = (isset($argv[1]) && ($argv[1] == 'test')) ? true : false;

/// Определяем некоторые строковые константы для текста

// Сегодняшнее число
$now = date('d.m.Y');

// Обратный адрес
$from = "no-reply.mgtd@mail.ru";

// Тема письма по умолчанию
$default_subject = 'КОМПАНИЯ "МЕГАТРЕЙД" ПОЗДРАВЛЯЕТ ВСЕХ С НАСТУПАЮЩИМ НОВЫМ ГОДОМ!';

// Генерация текста письма лежит в отдельном скрипте.
include "mail-template-new-year.php";

// Заголовки письма
$headers  = "From: $from\r\n";
$headers .= "Content-Type: text/html; charset=\"windows-1251\"\r\n";
$headers .= "Content-Transfer-Encoding: 8bit";

// Генерирует тему письма на основе полученной пары (ПолноеИмя, EmailАдрес)
function get_subject($address) {
// На 17.10.2011 просто возвращает тему письма по умолчанию
	global $default_subject;
	return "=?CP1251?B?" . base64_encode($default_subject) . "?=";
}

// Генерирует адрес получателя на основе полученной пары (ПолноеИмя, EmailАдрес)
function get_address($address) {
	return trim($address);
}

// Индекс нам позволяет сгенерировать ссылку для разлогинивания
function get_text($idx) {
	// $mail_text генерируется в скрипте-шаблоне письма
	global $mail_text;
	return preg_replace('/######/', $idx, $mail_text);
}

// Отправка письма с возвратом текста проверки, отправлено оно или нет.
function try_send_mail($idx, $to, $subject, $text, $headers) {
	$result = mail($to, $subject, $text, $headers) ? 'sent' : 'not sent';
	return sprintf("Letter %3d: %s (%s)\n", $idx, $result, $to);
}

// Печать лога событий на экран
function print_report($log) {
	foreach ($log as $event) {
		echo $event;
	}
}

function get_addresses($gid = NULL)
{
	if ($gid === NULL)
	{
		return RecipientsDB::getAllActiveEmails();
	}
	else
	{
		return RecipientsDB::getActiveEmailsByGID($gid);
	}
}

//==============================================================================
// Основной алгоритм BEGIN
//==============================================================================
// 1. Начинаем запись событий в лог
$log = array();

// 2. Получаем список адресов в виде массива записей (ПолноеИмя, EmailАдрес)
$addresses_list = get_addresses($test_mode ? 666 : NULL);

// 3. Для каждого адреса делаем следующее:
foreach($addresses_list as $idx => $address) {

// 3.1. Генерируем тему письма
	$subject = get_subject($address);

// 3.2. Генерируем тело письма
	$text = get_text($idx);

// 3.3. Генерируем адрес получателя
	$to = get_address($address); 
	if ($to == NULL) {
		continue;
	};

// 3.4. Отправляем письмо по адресу получателя.
	$log[] = try_send_mail($idx, $to, $subject, $text, $headers);
}

// 4. Выводим отчёт о выполнении
print_report($log);

?>
