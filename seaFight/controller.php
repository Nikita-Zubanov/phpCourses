<?php 	

include 'createHtml.php';

class Controller
{
	public $createHtml;
	function __construct()
	{
		$this->createHtml = new CreateHtml();
	}

	function getBattlefield()
	{
		echo $this->createHtml->getHtmlRegistrationForm();
	}

	function getHtmlStartedGame()
	{
		echo $this->createHtml->getHtmlStartedGame();
	}
}