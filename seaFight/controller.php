<?php 	

include 'view.php';
include 'registration.php';

class Controller
{
	private $html;
	private $registration;
	public function __construct()
	{
		$this->html = new View();
		$this->registration = new Registration();
	}

	public function getBattlefield()
	{
		return $this->html->getHtmlRegistrationForm($this->registration->getFieldPlayer());
	}

	public function getHtmlStartedGame()
	{
		return $this->html->getHtmlStartedGame();
	}
}