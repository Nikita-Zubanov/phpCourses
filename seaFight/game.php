<?php

define("FIRST_PLAYER", 1);
define("SECOND_PLAYER", 2);
define("VERTICAL_FIELD_SIZE", 10);

define("STATUS_GAME_OVER", "game_over");
define("STATUS_GAME_BEGUN", "game_begun");

define("GAME_STATUS_NAME_FILE", "gameStatus");
define("PLAYER_MOVE_NAME_FILE", "playerMove");
define("WINNER_NAME_FILE", "winner");

class Game
{
	public function readToFile($fileName)
	{
		$file = file_get_contents((string ) $fileName);
		return json_decode($file);
	}

	public function writeToFile($data, $fileName) 
	{
		$dataFile = json_encode($data);  
		file_put_contents($fileName, $dataFile);
	}

	private $playerMove;
	private $fieldPlayers = [
		FIRST_PLAYER => ['login' => '', '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array(), '7' => array(), '8' => array(), '9' => array(), '10' => array()],
		SECOND_PLAYER => ['login' => '', '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array(), '7' => array(), '8' => array(), '9' => array(), '10' => array()],
	];
	private function setFieldPlayer($idPlayer, $stepPlayer)
	{
		if ($idPlayer === FIRST_PLAYER) {
			$idEnemyPlayer = SECOND_PLAYER;
		} else {
			$idEnemyPlayer = FIRST_PLAYER;
		}

		$wasItHit = false;
		$coordinates = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];
		for ($y = 1; $y < VERTICAL_FIELD_SIZE + 1; $y++) {
			foreach ($coordinates as $key => $value) {
				if ($this->fieldPlayers[$idEnemyPlayer][$y][$key] === $stepPlayer) {
					$this->fieldPlayers[$idEnemyPlayer][$y][$key] = "1";
					$wasItHit = true;
				}
			}
		}

		if (!$wasItHit) {
			$this->playerMove = $idEnemyPlayer;
			$this->writeToFile($this->playerMove, PLAYER_MOVE_NAME_FILE);

			$x = (int) array_search(substr($stepPlayer, -2), $coordinates);
			$y = (int) substr($stepPlayer, 0, 1);
			$this->fieldPlayers[$idEnemyPlayer][$y][$x] = "0";
		}

		$this->writeToFile($this->fieldPlayers[$idEnemyPlayer], $idEnemyPlayer);
	}

	private function getFieldPlayer($idPlayer)
	{
		$html = new view();

		$this->fieldPlayers[$idPlayer] = (array) $this->readToFile($idPlayer);

		$playerForm .= $html->getHtmlFormFirst();
		$playerForm .= "<caption>" . $this->fieldPlayers[$idPlayer]['login'] . "</caption>";
		
		$coordinates = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];
		for ($y = 0; $y < VERTICAL_FIELD_SIZE + 1; $y++) {
			$playerForm .= "<tr>";
			$playerForm .= "<th>{$y}</th>";
			foreach ($coordinates as $key => $value) {
				if ($y === 0) {
					$playerForm .= "<th>{$value}</th>";
				} else {
					if ($this->fieldPlayers[$idPlayer][$y][$key] === "1") {
						$playerForm .= '<td class="wreckedship"></td>';
					} elseif ($this->fieldPlayers[$idPlayer][$y][$key] === "0") {
						$playerForm .= '<td class="miss"></td>';
					} elseif (!empty($this->fieldPlayers[$idPlayer][$y][$key])) {
						$playerForm .= '<td class="wholeShip"></td>';
					} else {
						$playerForm .= '<td></td>';
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

	private function setStepPlayer($idPlayer)
	{
		if (!empty($_POST[$idPlayer])) {
			$this->setFieldPlayer($idPlayer, mb_strtoupper($_POST[$idPlayer], 'UTF-8')); 
		}
	}

	public function setGameStatus($gameStatus)
	{
		$this->writeToFile($gameStatus, GAME_STATUS_NAME_FILE);

		echo '<META HTTP-EQUIV=Refresh Content="0;">';
	}

	private $winner;
	private $ships = [
			'singleDeck' => ['shipCount' => 0, 'location' => array()],
			'doubleDeck' => ['shipCount' => 0, 'location' => array()],
			'threeDeck' => ['shipCount' => 0, 'location' => array()],
			'fourDeck' => ['shipCount' => 0, 'location' => array()],
	];
	private function getWinner($ships, $idPlayer)
	{
		$shipCoordinate = new registration();

		if ($idPlayer === FIRST_PLAYER) {
			$idEnemyPlayer = SECOND_PLAYER;
		} else {
			$idEnemyPlayer = FIRST_PLAYER;
		}

		$ships = $shipCoordinate->getShipsLocationAndCount($this->fieldPlayers[$idEnemyPlayer]);

		$numberSurvivingShips;
		foreach ($ships as $deckKey => $deck) {
			$numberSurvivingShips += $ships[$deckKey]['shipCount'];
		}

		if ($numberSurvivingShips === 0) {
			$this->winner = $this->playerMove;

			$this->setGameStatus(STATUS_GAME_OVER);
		}

		return $this->winner;
	}

	public function setStartedGame()
	{
		$this->playerMove = (int) $this->readToFile(PLAYER_MOVE_NAME_FILE);

		echo $this->getFieldPlayer(FIRST_PLAYER);
		echo $this->getFieldPlayer(SECOND_PLAYER);

		if (empty($this->getWinner($this->ships, $this->playerMove))) {
			if(isset($_POST['attack'])) {
				$this->setStepPlayer($this->playerMove);

				$this->setGameStatus(STATUS_GAME_BEGUN);
			}
		} else {
			$nameWinner = $this->fieldPlayers[$this->winner]['login'];

			$this->writeToFile(null, WINNER_NAME_FILE);				//Задаем изначальные значения файлам, где хранится информация об игре
			$this->writeToFile(FIRST_PLAYER, PLAYER_MOVE_NAME_FILE);
			$this->writeToFile(null, FIRST_PLAYER);
			$this->writeToFile(null, SECOND_PLAYER);

			echo "<script>alert(\"Победил игрок $nameWinner\");</script>"; 
		}
	}
}