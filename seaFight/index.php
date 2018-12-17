<?php 	

function __autoload($className) {
	include_once($className . ".php");
}

$controller = new controller();

$controller->setBattlefield();
$controller->setStartedGame();