<?php

class Field
{
	const WRECKED_SHIP = "hit";
	const MISS = "slip";
	const COORDINATES = array("А", "Б", "В", "Г", "Д", "Е", "Ж", "З", "И", "К");
	const FIRST_PLAYER = 1;
	const SECOND_PLAYER = 2;
	const VERTICAL_FIELD_SIZE = 10;

	private $fieldPlayers = [
		self::FIRST_PLAYER => ['login' => '', '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array(), '7' => array(), '8' => array(), '9' => array(), '10' => array()],
		self::SECOND_PLAYER => ['login' => '', '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array(), '7' => array(), '8' => array(), '9' => array(), '10' => array()],
	];

	public function getRegistrationIdPlayer()
	{
		$game = new game();

		if (!empty($_POST['loginFirst'])) {
			$idPlayer = self::FIRST_PLAYER;
		} elseif (!empty($_POST['loginSecond'])) {
			$idPlayer = self::SECOND_PLAYER;
			
			$game->setGameStatus(game::STATUS_GAME_BEGUN);
		}
		return $idPlayer;
	}

	private function setRegistrationLoginPlayer($idPlayer)
	{
		if ($idPlayer === self::FIRST_PLAYER) {
			$this->fieldPlayers[$idPlayer]['login'] = $_POST['loginFirst'];
		} elseif ($idPlayer === self::SECOND_PLAYER) {
			$this->fieldPlayers[$idPlayer]['login'] = $_POST['loginSecond'];
		}
	}

	private $playerRegistrationForm;
	public function getRegistrationFieldPlayer($idPlayer) 
	{
		for ($y = 0; $y < self::VERTICAL_FIELD_SIZE + 1; $y++) {
			foreach (self::COORDINATES as $key => $value) {
				if ($y === 0) {
					$this->setRegistrationLoginPlayer($idPlayer);
				} else {
					array_push($this->fieldPlayers[$idPlayer][$y], $_POST[$y . $value]);
				}
			}
		}

		return $this->fieldPlayers[$idPlayer];
	}

	public function setRegistrationFormPlayer($data) 
	{
		$this->playerRegistrationForm .= $data;
	}

	public function getRegistrationFormPlayer() 
	{
		$this->playerRegistrationForm .= '<p><lable>Логин первого игрока: </lable ><input type = "text" name = "loginFirst"></p>';
		$this->playerRegistrationForm .= '<p><lable>Логин второго игрока: </lable ><input type = "text" name = "loginSecond"></p>';

		for ($y = 0; $y < self::VERTICAL_FIELD_SIZE + 1; $y++) {
			$this->playerRegistrationForm .= "<tr>";
			$this->playerRegistrationForm .= "<th>{$y}</th>";
			foreach (self::COORDINATES as $key => $value) {
				if ($y === 0) {
					$this->playerRegistrationForm .= "<th>{$value}</th>";
					$this->setRegistrationLoginPlayer($idPlayer);
				} else {
					$this->playerRegistrationForm .= '<td><input type="checkbox" name="' . $y . $value . '" value="' . $y . $value . '"></td>';
					array_push($this->fieldPlayers[$idPlayer][$y], $_POST[$y . $value]);
				}
			}
			$this->playerRegistrationForm .= "</tr>";
		}

		$this->playerRegistrationForm .= '<p><input type="submit" name="savePlayer" value="Сохранить поле игрока"></p>';

		return $this->playerRegistrationForm;
	}

	private $error;
	private $ships = [
			'singleDeck' => ['shipCount' => 0, 'location' => array()],
			'doubleDeck' => ['shipCount' => 0, 'location' => array()],
			'threeDeck' => ['shipCount' => 0, 'location' => array()],
			'fourDeck' => ['shipCount' => 0, 'location' => array()],
	];
	private function setShipsLocationAndCount($shipsLocation, $shipsCount)	//Идут две функции, работающие с массивом кораблей
	{
		if (!empty($shipsLocation)) {
			switch (count($shipsLocation)) {
				case 0:
					break;
				case 1:
					$this->ships['singleDeck']['location'] = array_merge($this->ships['singleDeck']['location'], $shipsLocation);
					break;
				case 2:
					$this->ships['doubleDeck']['location'] = array_merge($this->ships['doubleDeck']['location'], $shipsLocation);
					break;
				case 3:
					$this->ships['threeDeck']['location'] = array_merge($this->ships['threeDeck']['location'], $shipsLocation);
					break;
				case 4:
					$this->ships['fourDeck']['location'] = array_merge($this->ships['fourDeck']['location'], $shipsLocation);
					break;
			}
		} elseif (!empty($shipsCount)) {
			switch ($shipsCount) {
				case '0':
					break;
				case '1':
					$this->ships['singleDeck']['shipCount']++;
					break;
				case '2':
					$this->ships['doubleDeck']['shipCount']++;
					break;
				case '3':
					$this->ships['threeDeck']['shipCount']++;
					break;
				case '4':
					$this->ships['fourDeck']['shipCount']++;
					break;
				default:
					$this->error .= "Один из кораблей слишком большой! ";
			}
		}
	}

	public function getShipsLocationAndCount($field)
	{
		foreach ($field as $rowKey => $row) {
			foreach ($row as $symbolKey => $symbol) {
				$locationShip = array();
				$shipCount = 0;
				
				if (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey]) && 
					$field[$rowKey][$symbolKey] != self::MISS && $field[$rowKey][$symbolKey] != self::WRECKED_SHIP) {
					for ($i = $rowKey; $i <= count($field); $i++) { //Пробегаемся по массиву поля по вертикали
						if (!empty($field[$i][$symbolKey])) {
							$locationShip[$i] = $i . substr($symbol, -2);
							$shipCount++;
							$field[$i][$symbolKey] = null;
						} else break;
					}
					$this->setShipsLocationAndCount($locationShip, null);
				} elseif (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey][$symbolKey + 1]) && 
							$field[$rowKey][$symbolKey] != self::MISS && $field[$rowKey][$symbolKey] != 
							self::WRECKED_SHIP) {
					for ($i = $symbolKey; $i <= count($row); $i++) { //Пробегаемся по массиву поля по горизонтали
						if (!empty($field[$rowKey][$i])) {
							$locationShip[$i] = $i . substr($symbol, -2);
							$shipCount++;
							$field[$rowKey][$i] = null;
						} else break;
					}
					$this->setShipsLocationAndCount($locationShip, null);
				} elseif (!empty($field[$rowKey][$symbolKey]) && $field[$rowKey][$symbolKey] != self::MISS 
					&& $field[$rowKey][$symbolKey] != self::WRECKED_SHIP) { //Проверяем текущую ячейку поля
					$shipCount++;
					for ($i = $rowKey; $i <= count($field); $i++) { //Пробегаемся по массиву поля по вертикали и горизонтали для однопалубного корабля
						if (!empty($field[$i][$symbolKey])) {
							$locationShip[$i] = $i . substr($symbol, -2);
							$field[$i][$symbolKey] = null;
						} else break;
						if (!empty($field[$rowKey][$i])) {
							$locationShip[$i] = $i . substr($symbol, -2);
							$field[$rowKey][$i] = null;
						} else break;
					}
					$this->setShipsLocationAndCount($locationShip, null);
				}
				
				$this->setShipsLocationAndCount(null, $shipCount);
			}
		}
		return $this->ships;
	}

	private function setErrorPositioningShips($field)	//Идут две функции проверки поля игрока
	{
		foreach ($field as $rowKey => $row) {
			foreach ($row as $symbolKey => $symbol) {
				if (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey + 1])) { $this->error .= "Неправильное расположение кораблей! ";
				} elseif (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey - 1])) { $this->error .= "Неправильное расположение кораблей! ";
				}
			}
		}
	}

	private function setErrorNumberShips($ships)
	{
		if ($ships['singleDeck']['shipCount'] !== 4 || $ships['doubleDeck']['shipCount'] !== 3 || $ships['threeDeck']['shipCount'] !== 2 || $ships['fourDeck']['shipCount'] !== 1) {
			$this->error .= "Проверьте количество кораблей и их расстановку! ";
		}
	}

	public function getError($fieldPlayer)
	{
		$shipsPlayer = $this->getShipsLocationAndCount($fieldPlayer);

		$this->setErrorPositioningShips($fieldPlayer);
		$this->setErrorNumberShips($shipsPlayer);

		return $this->error;
	}

	public function getPlayingFormPlayer($idPlayer, $playerMove) 
	{
		$game = new game();

		$this->fieldPlayers[$idPlayer] = (array) $game->readToFile($idPlayer);

		$playerGameplayForm .= "<caption>" . $this->fieldPlayers[$idPlayer]['login'] . "</caption>";

		for ($y = 0; $y < self::VERTICAL_FIELD_SIZE + 1; $y++) {
			$playerGameplayForm .= "<tr>";
			$playerGameplayForm .= "<th>{$y}</th>";
			foreach (self::COORDINATES as $key => $value) {
				if ($y === 0) {
					$playerGameplayForm .= "<th>{$value}</th>";
				} else {
					if ($this->fieldPlayers[$idPlayer][$y][$key] === self::WRECKED_SHIP) {
						$playerGameplayForm .= '<td class="wreckedship"></td>';
					} elseif ($this->fieldPlayers[$idPlayer][$y][$key] === self::MISS) {
						$playerGameplayForm .= '<td class="miss"></td>';
					} elseif (!empty($this->fieldPlayers[$idPlayer][$y][$key])) {
						$playerGameplayForm .= '<td class="wholeShip"></td>';
					} else {
						$playerGameplayForm .= '<td></td>';
					}
				}
			}
			$playerGameplayForm .= "</tr>";
		}

		$playerGameplayForm .= '<p><lable>Ход '.$playerMove.' игрока: </lable ><input type = "text" name = "' . $idPlayer . '"></p>';
		$playerGameplayForm .= '<p><input type="submit" name="attack" value="Атаковать" class="submit"></p>';

		return $playerGameplayForm;
	}

	public function setPlayingFieldPlayer($idPlayer, $stepPlayer)
	{
		$game = new game();

		if ($idPlayer === self::FIRST_PLAYER) {
			$idEnemyPlayer = self::SECOND_PLAYER;
		} else {
			$idEnemyPlayer = self::FIRST_PLAYER;
		}

		$this->fieldPlayers[$idEnemyPlayer] = (array) $game->readToFile($idEnemyPlayer);

		$wasItHit = false;
		for ($y = 1; $y < self::VERTICAL_FIELD_SIZE + 1; $y++) {
			foreach (self::COORDINATES as $key => $value) {
				if ($this->fieldPlayers[$idEnemyPlayer][$y][$key] === $stepPlayer) {
					$this->fieldPlayers[$idEnemyPlayer][$y][$key] = self::WRECKED_SHIP;
					$wasItHit = true;
				} elseif ($y.$value === $stepPlayer && (
						$this->fieldPlayers[$idEnemyPlayer][$y][$key] === self::WRECKED_SHIP ||
						$this->fieldPlayers[$idEnemyPlayer][$y][$key] === self::MISS)) {
					echo "<script>alert(\"Вы уже атаковали по данным координатам.\");</script>";
					$wasItHit = true;
				}
			}
		}

		if (!$wasItHit) {
			$game->writeToFile($idEnemyPlayer, game::PLAYER_MOVE_NAME_FILE);

			$x = (int) array_search(substr($stepPlayer, -2), self::COORDINATES);
			$y = (int) substr($stepPlayer, 0, 1);

			$this->fieldPlayers[$idEnemyPlayer][$y][$x] = self::MISS;
		}

		$game->writeToFile($this->fieldPlayers[$idEnemyPlayer], $idEnemyPlayer);
	}

	private function getPlayingFieldPlayer($idPlayer) 
	{
		$game = new game();

		$this->fieldPlayers[$idPlayer] = (array) $game->readToFile($idPlayer);

		return $this->fieldPlayers[$idPlayer];
	}

	public function getNameWinner($playerMove)
	{
		$game = new game();

		if ($playerMove === self::FIRST_PLAYER) {
			$idEnemyPlayer = self::SECOND_PLAYER;
		} else {
			$idEnemyPlayer = self::FIRST_PLAYER;
		}
		$fieldEnemyPlayer = $this->getPlayingFieldPlayer($idEnemyPlayer);
		$ships = $this->getShipsLocationAndCount($fieldEnemyPlayer);

		$numberSurvivingShips;
		foreach ($ships as $deckKey => $deck) {
			$numberSurvivingShips += $ships[$deckKey]['shipCount'];
		}

		if ($numberSurvivingShips === 0) {
			$fieldPlayer = $this->getPlayingFieldPlayer($playerMove);
			$winnerName = $fieldPlayer['login'];

			$game->setGameStatus(game::STATUS_GAME_OVER);
		}
		return $winnerName;
	}
}