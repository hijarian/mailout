<?php
require_once "Recipient.php";

class RecipientsDB
{
	private static $connection_string = NULL;

	public static function useSQLiteFile($filename)
	{
		self::$connection_string = 'sqlite:'.$filename;
	}

	private static function getConnection()
	{
		$connection_string = self::getConnectionString(); 
		$connection = new PDO($connection_string);
		if (empty($connection))
		{
			throw new Exception('Не удалось создать PDO подключение к '.$connection_string);
		}
		else
		{
			return $connection;
		}
	}

	private static function getConnectionString()
	{
		return self::$connection_string
			? self::$connection_string
			: 'sqlite:'.dirname(__FILE__).'/recipients.db';
	}

	/**
	 * Получить объект получателя по его ID
	 * @param int $id Идентификтор получателя в БД
	 * @return Recipient Объект получателя
	 */
	public static function getRecipientByID($id)
	{
		$db = self::getConnection();
		$results = $db->query('select * from recipients where id='.$id);
		if (empty($results))
		{
			self::raise('Не удалось получить получателя с ID '.$id, $db);
		}
		else
		{
			return self::constructRecipientFromDBResult($results);
		}
	}


	private static function constructRecipientFromDBResult($results)
	{
		if (empty($results))
		{
			throw new Exception('RecipientsDB::constructRecipientFromDBResult() : нет данных для построения объекта получателя!');
		}
		$first_match = $results->fetch(PDO::FETCH_ASSOC);
		return new Recipient(
			$first_match['id'],
			$first_match['gid'],
			$first_match['email'],
			$first_match['opened'],
			$first_match['homepage'],
			$first_match['catalog'],
			$first_match['unsubscribed']
		);
	}

	public static function visitedHomepage($visitor_id)
	{
		$visitor = self::getRecipientByID($visitor_id);
		$visitor->visitedHomepage();
		self::saveRecipient($visitor);
	}

	public static function visitedCatalog($visitor_id)
	{
		$visitor = self::getRecipientByID($visitor_id);
		$visitor->visitedCatalog();
		self::saveRecipient($visitor);
	}

	public static function openedEmail($visitor_id)
	{
		$visitor = self::getRecipientByID($visitor_id);
		$visitor->openedEmail();
		self::saveRecipient($visitor);
	}

	public static function unsubscribed($visitor_id)
	{
		$visitor = self::getRecipientByID($visitor_id);
		$visitor->unsubscribed();
		self::saveRecipient($visitor);
	}


	private static function saveRecipient($recipient)
	{
		$db = self::getConnection();
		$query = $db->prepare('update recipients set gid=:gid, email=:email, opened=:opened, homepage=:homepage, catalog=:catalog, unsubscribed=:unsubscribed where id=:id');
		if (empty($query))
		{
			self::raise('Невозможно сохранить получателя!', $db);
		}
		else
		{
			$query->execute(
				array(
					'id' => $recipient->id,
					'gid' => $recipient->gid,
					'email' => $recipient->email,
					'opened' => $recipient->mailout_visits,
					'homepage' => $recipient->homepage_visits,
					'catalog' => $recipient->catalog_visits,
					'unsubscribed' => $recipient->is_unsubscribed ? 1 : 0
				)
			);
		}
	}

	public static function getStats()
	{
		$db = self::getConnection();
		$query = $db->prepare(self::getSQLForGettingStats());
		if (empty($query))
		{
			self::raise('Не удалось подготовить запрос статистики от базы данных!', $db);
		}
		else
		{
			$result = $query->execute();
			if ($result)
			{
				return $query->fetch(PDO::FETCH_ASSOC);
			}
			else
			{
				self::raise('Не удалось выполнить запрос статистики от базы данных!', $query);
			}
		}
	}

	private static function getSQLForGettingStats()
	{
		return '
select *
from 
	(select count(*) as address_count from recipients where ignore = 0)
		join
	(select count(*) as opened_count from recipients where opened > 0 and ignore = 0)
		join
	(select count(*) as homepage_count from recipients where homepage > 0 and ignore = 0)
		join
	(select count(*) as catalog_count from recipients where catalog > 0 and ignore = 0)
		join
	(select count(*) as unsubscribed_count from recipients where unsubscribed > 0 and ignore = 0)
;';
	}

	private static function raise($msg, $pdo_handle)
	{
		throw new Exception(
			$msg. "\n" . self::makeErrorString($pdo_handle->errorInfo())
		);
	}

	private static function makeErrorString($error_info)
	{
		return "SQLSTATE " . $error_info[0] . " [" . $error_info[1] . "] " . $error_info[2];
	}


	public static function getAllActiveEmails()
	{
		$db = self::getConnection();
		$results = $db->query('select id,email from recipients where ignore=0');
		if ($results)
		{
			return self::DBResultToEmailsArray($results);
		}
		else
		{
			return array();
		}
	}

	public static function getActiveEmailsByGID($gid)
	{
		$db = self::getConnection();
		$results = $db->query('select id,email from recipients where (ignore=0) and gid='.$gid);
		if ($results)
		{
			return self::DBResultToEmailsArray($results);
		}
		else
		{
			return array();
		}
	}

	private static function DBResultToEmailsArray($db_results)
	{
		$emails = array();
		while ($row = $db_results->fetch(PDO::FETCH_ASSOC))
		{
			$emails[$row['id']] = $row['email'];
		}
		return $emails;
	}


}
?>
