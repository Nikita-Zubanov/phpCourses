<?php

class CreateHtml 
{
	public $htmlRegistrationForm;
	function getHtmlRegistrationForm()
	{
		include 'createBattlefield.php';
		$player = new CreateBattlefield;
		
		$htmlFormFirst = '
			<!DOCTYPE html>
			<html>
			<head>
				<meta charset="utf-8">
				<title>Морской бой</title>
				<link rel="stylesheet" type="text/css" href="style.css">
			</head>
			
			<body>
				<table>
					<caption>Морской бой</caption>
					<form method="POST">
		';

		$htmlFormSecond = '
					</form>
				</table>
			</body>
		</html>
		';

		$playerRegistrationForm = $player->getFieldPlayer();
		$this->htmlRegistrationForm = $htmlFormFirst . $playerRegistrationForm . $htmlFormSecond;
		return $this->htmlRegistrationForm;
	}

	public $htmlStartedGame;
	function getHtmlStartedGame()
	{
		$this->htmlStartedGame = '<p><a href="game.php" id="first">Начать игру</a></p>';
		return $this->htmlStartedGame;
	}
}