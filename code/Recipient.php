<?php
class Recipient
{

	private $id;
	private $gid;
	private $email;
	private $mailout_visits;
	private $homepage_visits;
	private $catalog_visits;
	private $is_unsubscribed;

	public function __construct($id, $gid, $email, $opened = 0, $homepage = 0, $catalog = 0, $unsubscribed = 0)
	{
		$this->id = $id;
		$this->gid = $gid;
		$this->email = $email;
		$this->mailout_visits = $opened;
		$this->homepage_visits = $homepage;
		$this->catalog_visits = $catalog;
		$this->is_unsubscribed = ($unsubscribed > 0);
	}

	public function __get($param)
	{
		return $this->$param;
	}

	public function __set($param, $value)
	{
		$this->$param = $value;
	}

	public function visitedHomepage()
	{
		$this->homepage_visits += 1;
	}
	
	public function openedEmail()
	{
		$this->mailout_visits += 1;
	}
	
	public function visitedCatalog()
	{
		$this->catalog_visits += 1;
	}
	
	public function unsubscribed()
	{
		$this->is_unsubscribed = true;
	}
}
?>
