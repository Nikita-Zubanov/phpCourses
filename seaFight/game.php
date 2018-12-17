<?php

class Game
{
	const STATUS_GAME_OVER = "game_over";
	const STATUS_GAME_BEGUN = "game_begun";
	const GAME_STATUS_NAME_FILE = "gameStatus";
	const PLAYER_MOVE_NAME_FILE = "playerMove";
	const WINNER_NAME_FILE = "winner";

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

	private function setStepPlayer($idPlayer)
	{
		$field = new field();

		if (!empty($_POST[$idPlayer])) {
			$field->setPlayingFieldPlayer($idPlayer, mb_strtoupper($_POST[$idPlayer], 'UTF-8')); 
		}
	}

	public function setGameStatus($gameStatus)
	{
		$this->writeToFile($gameStatus, self::GAME_STATUS_NAME_FILE);
		echo '<META HTTP-EQUIV=Refresh Content="0;">';
	}

	private $winner;
	private $playerMove;
	private function getWinner($fieldPlayer, $ships)
	{
		$field = new field();

		if ($this->playerMove === field::FIRST_PLAYER) {
			$idEnemyPlayer = field::SECOND_PLAYER;
		} else {
			$idEnemyPlayer = field::FIRST_PLAYER;
		}

		$ships = $field->getShipsLocationAndCount($fieldPlayer[$idEnemyPlayer]);

		$numberSurvivingShips;
		foreach ($ships as $deckKey => $deck) {
			$numberSurvivingShips += $ships[$deckKey]['shipCount'];
		}
		if ($numberSurvivingShips === 0) {
			$this->winner = $this->playerMove;
			$this->setGameStatus(self::STATUS_GAME_OVER);
		}

		return $this->winner;
	}

	public function setStartedGame()
	{
		$field = new field();

		$this->playerMove = (int) $this->readToFile(self::PLAYER_MOVE_NAME_FILE);

		echo $field->getPlayingFieldPlayer(field::FIRST_PLAYER, $this->playerMove);
		echo $field->getPlayingFieldPlayer(field::SECOND_PLAYER, $this->playerMove);

		if (empty($this->getWinner($field->fieldPlayers, $field->ships))) {
			if(isset($_POST['attack'])) {
				$this->setStepPlayer($this->playerMove);
				$this->setGameStatus(self::STATUS_GAME_BEGUN);
			}
		} else {
			$nameWinner = $field->fieldPlayers[$this->winner]['login'];
			$this->writeToFile(null, self::WINNER_NAME_FILE);				//Задаем изначальные значения файлам, где хранится информация об игре
			$this->writeToFile(field::FIRST_PLAYER, self::PLAYER_MOVE_NAME_FILE);
			$this->writeToFile(null, field::FIRST_PLAYER);
			$this->writeToFile(null, field::SECOND_PLAYER);

			echo "<script>alert(\"Победил игрок $nameWinner\");</script>";
		}
	}
}