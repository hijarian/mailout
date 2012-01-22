<?php
require_once "RecipientsDB.php";

class RecipientsDB_WebDriver
{
	private static $admin_email = 'msafronov@akalita.com';

	public static function recipientUnsubscribed($id)
	{
		self::silentlyCall('unsubscribed', $id);
	}

	public static function recipientVisitedCatalog($id)
	{
		self::silentlyCall('visitedCatalog', $id);
	}

	public static function recipientVisitedHomepage($id)
	{
		self::silentlyCall('visitedHomepage', $id);
	}

	public static function recipientOpenedEmail($id)
	{
		self::silentlyCall('openedEmail', $id);
	}

	public static function renderUnsubscriptionReport()
	{
		include "forms/unsubscription_report.php";
	}

	public static function renderUnsubscriptionForm()
	{
		include "forms/unsubscription_form.php";
	}

	public static function returnStubImage()
	{
		header("Content-Type: image/png");
		$im = @imagecreate(1, 1)
			or die("Cannot Initialize new GD image stream");
		imagepng($im);
		imagedestroy($im);
	}

	private static function silentlyCall($method, $param)
	{
		try
		{
			call_user_func(array('RecipientsDB', $method), $param);
		}
		catch (Exception $e)
		{
			self::mailErrorToAdmin($method, $e->getMessage());
		}
	}

	private static function mailErrorToAdmin($method, $error_message)
	{
		mail(
			self::$admin_email,
			'Error raised on method '.$method.' in mailout!',
			$error_message,
			'From: noreply@mgtd.ru'
		);
	}
}
?>
