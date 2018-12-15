<?php 	

define("STATUS_GAME_OVER", "game_over");
define("STATUS_GAME_BEGUN", "game_begun");

define("GAME_STATUS_NAME_FILE", "gameStatus");

function __autoload($className) {
	include_once($className . ".php");
}

$controller = new controller();

$controller->setBattlefield();
$controller->setStartedGame();