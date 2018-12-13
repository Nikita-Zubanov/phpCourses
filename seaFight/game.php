<?php

define("FIRST_PLAYER", 1);
define("SECOND_PLAYER", 2);
define("FIELD_SIZE", 10);

include 'view.php';
include 'registration.php';

$game = new Game();
$game->startGame();

class Game
{
	function readToFile($fileName)
	{
		$file = file_get_contents((string ) $fileName);
		return json_decode($file);
	}

	function writeToFile($data, $fileName) 
	{
		$dataFile = json_encode($data);  
		file_put_contents($fileName, $dataFile);
	}	

	public $playerMove;
	private $fieldPlayers = [
		FIRST_PLAYER => ['login' => '', '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array(), '7' => array(), '8' => array(), '9' => array(), '10' => array()],
		SECOND_PLAYER => ['login' => '', '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array(), '7' => array(), '8' => array(), '9' => array(), '10' => array()],
	];
	function setFieldPlayer($idPlayer, $stepPlayer)
	{
		if ($idPlayer === FIRST_PLAYER) {
			$idPlayer = SECOND_PLAYER;
		} else {
			$idPlayer = FIRST_PLAYER;
		}

		$coordinates = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];
		$wasItHit = false;

		for ($i = 1; $i < FIELD_SIZE + 1; $i++) {
			foreach ($coordinates as $key => $value) {
				if ($this->fieldPlayers[$idPlayer][$i][$key] === $stepPlayer) {
					$this->fieldPlayers[$idPlayer][$i][$key] = "1";
					$wasItHit = true;
				}
			}
		}

		if (!$wasItHit) {
			$this->playerMove = $idPlayer;
			$this->writeToFile($this->playerMove, "playerMove");

			$x = (int) array_search(substr($stepPlayer, -2), $coordinates);
			$y = (int) substr($stepPlayer, 0, 1);
			$this->fieldPlayers[$idPlayer][$y][$x] = "0";
		}

		$this->writeToFile($this->fieldPlayers[$idPlayer], $idPlayer);
	}

	function getFieldPlayer($idPlayer)
	{
		$html = new View();

		$this->fieldPlayers[$idPlayer] = (array) $this->readToFile($idPlayer);

		$coordinates = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];

		$playerForm = $html->getHtmlFormFirst();
		$playerForm .= "<caption>" . $this->fieldPlayers[$idPlayer]['login'] . "</caption>";
		

		for ($i = 0; $i < FIELD_SIZE + 1; $i++) {
			$playerForm .= "<tr>";
			$playerForm .= "<th>{$i}</th>";
			foreach ($coordinates as $key => $value) {
				if ($i === 0) {
					$playerForm .= "<th>{$value}</th>";
				} else {
					if ($this->fieldPlayers[$idPlayer][$i][$key] === "1") {
						$playerForm .= '<td class="wreckedship">'.$this->fieldPlayers[$idPlayer][$i][$key].'</td>';
					} elseif ($this->fieldPlayers[$idPlayer][$i][$key] === "0") {
						$playerForm .= '<td class="miss">'.$this->fieldPlayers[$idPlayer][$i][$key].'</td>';
					} elseif (!empty($this->fieldPlayers[$idPlayer][$i][$key])) {
						$playerForm .= '<td class="wholeShip">'.$this->fieldPlayers[$idPlayer][$i][$key].'</td>';
					} else {
						$playerForm .= '<td>'.$this->fieldPlayers[$idPlayer][$i][$key].'</td>';
					}
				}
			}
			$playerForm .= "</tr>";
		}

		$playerForm .= '<p><lable>Ход '.$this->playerMove.' игрока: </lable ><input type = "text" name = "' . $idPlayer . '"></p>';
		$playerForm .= '<p><input type="submit" name="attack" value="Атаковать" class="submit"></p>';

		$playerForm .= $html->getHtmlFormSecond();
		return $playerForm;
	}

	function getStepPlayer($idPlayer)
	{
		if (!empty($_POST[$idPlayer])) {
			$this->setFieldPlayer($idPlayer, mb_strtoupper($_POST[$idPlayer], 'UTF-8')); 
		}
	}

	public $winner;
	private $ships = [
			'singleDeck' => ['counter' => 0, 'location' => array()],
			'doubleDeck' => ['counter' => 0, 'location' => array()],
			'threeDeck' => ['counter' => 0, 'location' => array()],
			'fourDeck' => ['counter' => 0, 'location' => array()],
	];
	function getWinner($ships, $idPlayer)
	{
		$shipCoordinate = new Registration();

		if ($idPlayer === FIRST_PLAYER) {
			$idPlayer = SECOND_PLAYER;
		} else {
			$idPlayer = FIRST_PLAYER;
		}

		$ships = $shipCoordinate->getShipsLocationAndCount($this->fieldPlayers[$idPlayer]);

		$numberSurvivingShips;
		foreach ($ships as $deckKey => $deck) {
			$numberSurvivingShips += $ships[$deckKey]['counter'];
		}

		if ($numberSurvivingShips === 0) {
			$this->winner = $this->playerMove;
		}

		return $this->winner;
	}

	function startGame()
	{
		$this->playerMove =  (int) $this->readToFile("playerMove");

		echo $this->getFieldPlayer(FIRST_PLAYER);
		echo $this->getFieldPlayer(SECOND_PLAYER);

		if (empty($this->getWinner($this->ships, $this->playerMove))) {
			if(isset($_POST['attack'])) {
				$this->getStepPlayer($this->playerMove);
				echo'<META HTTP-EQUIV=Refresh Content="0;URL=game.php">';
			}
		} else {
			$nameWinner = $this->fieldPlayers[$this->winner]['login'];
			echo "<script>alert(\"Победил игрок $nameWinner\");</script>"; 
		}
	}
}