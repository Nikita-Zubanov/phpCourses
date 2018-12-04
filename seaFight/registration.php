<?php 

define("FIRST_PLAYER", 1);
define("SECOND_PLAYER", 2);
class Registration
{
	private function writeToFile($field, $idPlayer) 
	{
		$filename = $idPlayer;
		$data = json_encode($field);  
		file_put_contents($filename, $data);
	}

	private $ships = [
			'singleDeck' => ['counter' => 0, 'location' => array()],
			'doubleDeck' => ['counter' => 0, 'location' => array()],
			'threeDeck' => ['counter' => 0, 'location' => array()],
			'fourDeck' => ['counter' => 0, 'location' => array()],
	];
	private function setLocation($locationShip)
	{
		switch (count($locationShip)) {
			case 0:
				break;
			case 1:
				$this->ships['singleDeck']['location'] = array_merge($this->ships['singleDeck']['location'], $locationShip);
				break;
			case 2:
				$this->ships['doubleDeck']['location'] = array_merge($this->ships['doubleDeck']['location'], $locationShip);
				break;
			case 3:
				$this->ships['threeDeck']['location'] = array_merge($this->ships['threeDeck']['location'], $locationShip);
				break;
			case 4:
				$this->ships['fourDeck']['location'] = array_merge($this->ships['fourDeck']['location'], $locationShip);
				break;
		}
	}
	public function getShipsLocations($field)
	{
		foreach ($field as $rowKey => $row) {
			foreach ($row as $symbolKey => $symbol) {
				$locationShip = array();

				if (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey])) {
					for ($i = $rowKey; $i <= count($field); $i++) { //Пробегаемся по массиву по вертикали
						if (!empty($field[$i][$symbolKey])) {
							$locationShip[$i] = $i . substr($symbol, -2);
							$field[$i][$symbolKey] = null;
						} else break;
					}

					$this->setLocation($locationShip);

				} elseif (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey][$symbolKey + 1])) {
					for ($i = $symbolKey; $i <= count($row); $i++) { //Пробегаемся по массиву по горизонтали
						if (!empty($field[$rowKey][$i])) {
							$locationShip[$i] = $i . substr($symbol, -2);
							$field[$rowKey][$i] = null;
						} else break;
					}

					$this->setLocation($locationShip);

				} elseif (!empty($field[$rowKey][$symbolKey])) { //Проверяем текущую ячейку
					for ($i = $rowKey; $i <= count($field); $i++) { //Пробегаемся по массиву по вертикали для 1
						if (!empty($field[$i][$symbolKey])) {
							$locationShip[$i] = $i . substr($symbol, -2);
							$field[$i][$symbolKey] = null;
						} else break;

						if (!empty($field[$rowKey][$i])) {
							$locationShip[$i] = $i . substr($symbol, -2);
							$field[$rowKey][$i] = null;
						} else break;
					}

					$this->setLocation($locationShip);
				}
			}
		}

		return $this->ships;
	}

	private function getErrorShipPositioning ($field)
	{
		foreach ($field as $rowKey => $row) {
			foreach ($row as $symbolKey => $symbol) {
				if (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey + 1])) { return "Неправильное расположение кораблей! ";
				} elseif (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey - 1])) { return "Неправильное расположение кораблей! ";
				}
			}
		}
		return null;
	}

	private function getErrorShipsSize($field)
	{		
		foreach ($field as $rowKey => $row) {
			foreach ($row as $symbolKey => $symbol) {
				$counter = 0;
				
				if (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey])) {
					for ($i = $rowKey; $i <= count($field); $i++) { //Пробегаемся по массиву по вертикали
						if (!empty($field[$i][$symbolKey])) {
							$counter++;
							$field[$i][$symbolKey] = null;
						} else break;
					}
				} elseif (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey][$symbolKey + 1])) {
					for ($i = $symbolKey; $i <= count($row); $i++) { //Пробегаемся по массиву по горизонтали
						if (!empty($field[$rowKey][$i])) {
							$counter++;
							$field[$rowKey][$i] = null;
						} else break;
					}
				} elseif (!empty($field[$rowKey][$symbolKey])) { //Проверяем текущую ячейку
					$counter++;
				}

				switch ($counter) {
					case '0':
						break;
					case '1':
						$this->ships['singleDeck']['counter']++;
						break;
					case '2':
						$this->ships['doubleDeck']['counter']++;
						break;
					case '3':
						$this->ships['threeDeck']['counter']++;
						break;
					case '4':
						$this->ships['fourDeck']['counter']++;
						break;
					default:
						return "Один из кораблей слишком большой! ";
				}
			}
		}
		if ($this->ships['singleDeck']['counter'] !== 4 || $this->ships['doubleDeck']['counter'] !== 3 || $this->ships['threeDeck']['counter'] !== 2 || $this->ships['fourDeck']['counter'] !== 1) return "Проверьте количество кораблей и их расстановку! ";
		return null;
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
		if (!empty($_POST['loginFirst'])) {
			$idPlayer = FIRST_PLAYER;
		} elseif (!empty($_POST['loginSecond'])) {
			$idPlayer = SECOND_PLAYER;
		}

		return $idPlayer;
	}

	public function getFieldPlayer() 
	{
		$coordinates = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];

		$playerForm .= '<p><lable>Логин первого игрока: </lable ><input type = "text" name = "loginFirst"></p>';
		$playerForm .= '<p><lable>Логин второго игрока: </lable ><input type = "text" name = "loginSecond"></p>';

		$idPlayer = $this->getIdPlayer();

		for ($i = 0; $i < 11; $i++) {
			$playerForm .= "<tr>";
			$playerForm .= "<th>{$i}</th>";
			foreach ($coordinates as $key => $value) {
				if ($i === 0) {
					$playerForm .= "<th>{$value}</th>";

					$this->setLoginPlayer($idPlayer);
				} else {
					$playerForm .= '<td><input type="checkbox" name="' . $i . $value . '" value="' . $i . $value . '"></td>';
					array_push($this->fieldPlayers[$idPlayer][$i], $_POST[$i . $value]);
				}
			}
			$playerForm .= "</tr>";
		}

		if (empty($this->getErrorShipPositioning($this->fieldPlayers[$idPlayer])) && 
			empty($this->getErrorShipsSize($this->fieldPlayers[$idPlayer]))) {
			$this->writeToFile($this->fieldPlayers[$idPlayer], $idPlayer);
		} else {
			$playerForm .= $this->getErrorShipPositioning($this->fieldPlayers[$idPlayer])
						 . $this->getErrorShipsSize($this->fieldPlayers[$idPlayer]);
		}
		
		$playerForm .= '<p><input type="submit" name="savePlayer" value="Сохранить поле игроков"></p>';

		$this->getShipsLocations($this->fieldPlayers[$idPlayer]);

		return $playerForm;
	}
}