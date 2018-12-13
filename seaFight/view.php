<?php

class View
{
	public $htmlFormFirst;
	public function getHtmlFormFirst()
	{
		$this->htmlFormFirst = '
			<br>
			<!DOCTYPE html>
			<html>
			<head>
				<meta charset="utf-8">
				<title>Морской бой</title>
				<link rel="stylesheet" type="text/css" href="style.css">
				<script type=\"text/javascript\">$(".submit").click(function(){setTimeout(function(){window.location.reload();}, 1000);});</script>
			</head>
			
			<body>
				<table>
					<form method="POST">
		';
		return $this->htmlFormFirst;
	}

	public $htmlFormSecond;
	public function getHtmlFormSecond()
	{
		$this->htmlFormSecond = '
					</form>
				</table>
			</body>
		</html>
		';
		return $this->htmlFormSecond;
	}

	public function getHtmlRegistrationForm($playerRegistrationForm)
	{
		return $this->getHtmlFormFirst() . $playerRegistrationForm . $this->getHtmlFormSecond();
	}

	private $htmlStartedGame;
	public function getHtmlStartedGame()
	{
		$this->htmlStartedGame = '<p><a href="game.php">Начать игру</a></p>';
		return $this->htmlStartedGame;
	}
}