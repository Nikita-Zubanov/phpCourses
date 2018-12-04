<?php

class View
{
	public $htmlFormFirst;
	public $htmlFormSecond;
	public function getHtmlRegistrationForm($playerRegistrationForm)
	{
		$this->htmlFormFirst = '
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

		$this->htmlFormSecond = '
					</form>
				</table>
			</body>
		</html>
		';

		return $this->htmlFormFirst . $playerRegistrationForm . $this->htmlFormSecond;
	}

	private $htmlStartedGame;
	public function getHtmlStartedGame()
	{
		$this->htmlStartedGame = '<p><a href="game.php" id="first">Начать игру</a></p>';
		return $this->htmlStartedGame;
	}
}