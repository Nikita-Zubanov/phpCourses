<?php 

define("FIRST_PLAYER", 1);
define("SECOND_PLAYER", 2);
define("VERTICAL_FIELD_SIZE", 10);

class Registration
{
	private $error;
	private $ships = [
			'singleDeck' => ['shipCount' => 0, 'location' => array()],
			'doubleDeck' => ['shipCount' => 0, 'location' => array()],
			'threeDeck' => ['shipCount' => 0, 'location' => array()],
			'fourDeck' => ['shipCount' => 0, 'location' => array()],
	];
	private function setShipsLocationAndCount($shipsLocation, $shipsCount)
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
					$field[$rowKey][$symbolKey] != "0" && $field[$rowKey][$symbolKey] != "1") {
					for ($i = $rowKey; $i <= count($field); $i++) { //Пробегаемся по массиву поля по вертикали
						if (!empty($field[$i][$symbolKey])) {
							$locationShip[$i] = $i . substr($symbol, -2);
							$shipCount++;

							$field[$i][$symbolKey] = null;
						} else break;
					}

					$this->setShipsLocationAndCount($locationShip, null);

				} elseif (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey][$symbolKey + 1]) && 
							$field[$rowKey][$symbolKey] != "0" && $field[$rowKey][$symbolKey] != "1") {
					for ($i = $symbolKey; $i <= count($row); $i++) { //Пробегаемся по массиву поля по горизонтали
						if (!empty($field[$rowKey][$i])) {
							$locationShip[$i] = $i . substr($symbol, -2);
							$shipCount++;

							$field[$rowKey][$i] = null;
						} else break;
					}

					$this->setShipsLocationAndCount($locationShip, null);

				} elseif (!empty($field[$rowKey][$symbolKey]) && $field[$rowKey][$symbolKey] != "0" && 
							$field[$rowKey][$symbolKey] != "1") { //Проверяем текущую ячейку поля
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

	private function setErrorShipPositioning($field)
	{
		foreach ($field as $rowKey => $row) {
			foreach ($row as $symbolKey => $symbol) {
				if (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey + 1])) { $this->error .= "Неправильное расположение кораблей! ";
				} elseif (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey - 1])) { $this->error .= "Неправильное расположение кораблей! ";
				}
			}
		}
	}

	private function setErrorShipsSize($field)
	{
		if ($this->ships['singleDeck']['shipCount'] !== 4 || $this->ships['doubleDeck']['shipCount'] !== 3 || $this->ships['threeDeck']['shipCount'] !== 2 || $this->ships['fourDeck']['shipCount'] !== 1) {
			$this->error .= "Проверьте количество кораблей и их расстановку! ";
		}
	}

	private $fieldPlayers = [
		FIRST_PLAYER => ['login' => '', '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array(), '7' => array(), '8' => array(), '9' => array(), '10' => array()],
		SECOND_PLAYER => ['login' => '', '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array(), '7' => array(), '8' => array(), '9' => array(), '10' => array()],
	];

	private function setLoginPlayer($idPlayer)
	{
		if ($idPlayer === FIRST_PLAYER) {
			$this->fieldPlayers[$idPlayer]['login'] = $_POST['loginFirst'];
		} elseif ($idPlayer === SECOND_PLAYER) {
			$this->fieldPlayers[$idPlayer]['login'] = $_POST['loginSecond'];
		}
	}

	private function getIdPlayer()
	{
		$game = new game();

		if (!empty($_POST['loginFirst'])) {
			$idPlayer = FIRST_PLAYER;
		} elseif (!empty($_POST['loginSecond'])) {
			$idPlayer = SECOND_PLAYER;
			
			$game->setGameStatus(STATUS_GAME_BEGUN);
		}

		return $idPlayer;
	}

	private $playerForm;
	private function setFieldPlayer($idPlayer) 
	{
		$this->playerForm .= '<p><lable>Логин первого игрока: </lable ><input type = "text" name = "loginFirst"></p>';
		$this->playerForm .= '<p><lable>Логин второго игрока: </lable ><input type = "text" name = "loginSecond"></p>';

		$coordinates = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];
		for ($y = 0; $y < VERTICAL_FIELD_SIZE + 1; $y++) {
			$this->playerForm .= "<tr>";
			$this->playerForm .= "<th>{$y}</th>";
			foreach ($coordinates as $key => $value) {
				if ($y === 0) {
					$this->playerForm .= "<th>{$value}</th>";

					$this->setLoginPlayer($idPlayer);
				} else {
					$this->playerForm .= '<td><input type="checkbox" name="' . $y . $value . '" value="' . $y . $value . '"></td>';
					array_push($this->fieldPlayers[$idPlayer][$y], $_POST[$y . $value]);
				}
			}
			$this->playerForm .= "</tr>";
		}

		$this->playerForm .= '<p><input type="submit" name="savePlayer" value="Сохранить поле игрока"></p>';
	}

	public function getRegistrationGame()
	{
		$game = new game();

		$idPlayer = $this->getIdPlayer();
		$this->setFieldPlayer($idPlayer);

		if(!empty($_POST['loginFirst']) || !empty($_POST['loginSecond'])) {
			$this->getShipsLocationAndCount($this->fieldPlayers[$idPlayer]);
			$this->setErrorShipPositioning($this->fieldPlayers[$idPlayer]);
			$this->setErrorShipsSize($this->fieldPlayers[$idPlayer]);

			if (empty($this->error)) {
				$game->writeToFile($this->fieldPlayers[$idPlayer], $idPlayer);
			} else {
				$this->playerForm .= $this->error;
			}
		} else {
			$this->playerForm .= "Расставьте корабли!";
		}

		return $this->playerForm;
	}
}