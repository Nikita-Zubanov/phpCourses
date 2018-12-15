<?php

class View
{
	public function getHtmlFormFirst()
	{
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
					<form method="POST">
		';
		return $htmlFormFirst;
	}

	public function getHtmlFormSecond()
	{
		$htmlFormSecond = '
					</form>
				</table>
			</body>
		</html>
		';
		return $htmlFormSecond;
	}

	public function getHtmlRegistrationForm($playerRegistrationForm)
	{
		return $this->getHtmlFormFirst() . $playerRegistrationForm . $this->getHtmlFormSecond();
	}

	private $htmlStartedGame;
	public function getHtmlStartedGame()
	{
		$this->htmlStartedGame = '<p><a href="game.php">Начать игру</a></p>';
		//$this->htmlStartedGame = '<META HTTP-EQUIV=Refresh Content="0;URL=game.php">';
		return $this->htmlStartedGame;
	}
}