<?php 

class CreateBattlefield 
{

	public $playerLogin = [
		'1' => '',
		'2' => '',
	];
	public $field = [
		'1' => ['0' => '', '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array(), '7' => array(), '8' => array(), '9' => array(), '10' => array()],
		'2' => ['0' => '', '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array(), '7' => array(), '8' => array(), '9' => array(), '10' => array()],
	];

	function writeToFile($player) 
	{
		$filename = $player;
		$data = json_encode($this->field[$player]);  
		file_put_contents($filename, $data);
	}

	function readToFile($player) 
	{
		$data = file_get_contents($player);
		$bookshelf = json_decode($data, TRUE);
		return $bookshelf;
	}

	function getErrors($field) 
	{
		$ships = [
			'singleDeck' => 0,
			'doubleDeck' => 0,
			'threeDeck' => 0,
			'fourDeck' => 0,
		];

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
					for ($i = $symbolKey; $i <= count($row); $i++) { //Пробегаемс по массиву по горизонтали
						if (!empty($field[$rowKey][$i])) {
							$counter++;
							$field[$rowKey][$i] = null;
						} else break;
					}
				} elseif (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey + 1])) { return "Неправильное расположение кораблей!";
				} elseif (!empty($field[$rowKey][$symbolKey]) && !empty($field[$rowKey + 1][$symbolKey - 1])) { return "Неправильное расположение кораблей!";
				} elseif (!empty($field[$rowKey][$symbolKey])) { //Проверяем текущую ячейку
					$counter++;
				}

				switch ($counter) {
					case '0':
						break;
					case '1':
						$ships['singleDeck']++;
						break;
					case '2':
						$ships['doubleDeck']++;
						break;
					case '3':
						$ships['threeDeck']++;
						break;
					case '4':
						$ships['fourDeck']++;
						break;
					default:
						return "Один из кораблей слишком большой!";
				}
			}
		}

		if ($ships['singleDeck'] !== 4 || $ships['doubleDeck'] !== 3 || $ships['threeDeck'] !== 2 || $ships['fourDeck'] !== 1) return "Проверьте количество кораблей и их расстановку!";
		return false;
	}

	function getFieldPlayer() 
	{
		$playerForm;
		$coordinates = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];

		$playerForm .= '<p><lable>Логин первого игрока: </lable ><input type = "text" name = "loginFirst"></p>';
		$playerForm .= '<p><lable>Логин второго игрока: </lable ><input type = "text" name = "loginSecond"></p>';

		if (!empty($_POST['loginFirst'])) {
			$player = '1';
		} elseif (!empty($_POST['loginSecond'])) {
			$player = '2';
		}

		for ($i = 0; $i < 11; $i++) {
			$playerForm .= "<tr>";
			$playerForm .= "<th>{$i}</th>";
			foreach ($coordinates as $key => $value) {
				if ($i === 0) {
					$playerForm .= "<th>{$value}</th>";
					$this->field[$player][$i] = $this->playerLogin[$player];
				} else {
					$playerForm .= '<td><input type="checkbox" name="' . $i . $value . '" value="' . $i . $value . '"></td>';
					array_push($this->field[$player][$i], $_POST[$i . $value]);
				}
			}
			$playerForm .= "</tr>";
		}

		if (!$this->getErrors($this->field[$player])) {
			$this->writeToFile($player);
		} else {
			$playerForm .= $this->getErrors($this->field[$player]);
		}

		$playerForm .= '
			<p><input type="submit" name="savePlayer" value="Сохранить поле игроков"></p>';

		return $playerForm;
	}
}