<?php 

class Battlefield 
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

	function fieldCheck($field) 
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

	function createFieldPlayer() 
	{
		$form;
		$coordinates = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];

		$form .= '<p><lable>Логин первого игрока: </lable ><input type = "text" name = "loginFirst"></p>';
		//$this->playerLogin[$player] = $_POST["login"];

		$form .= '<p><lable>Логин второго игрока: </lable ><input type = "text" name = "loginSecond"></p>';
		//$this->playerLogin[$player] = $_POST["login"];

		if (!empty($_POST['loginFirst'])) {
			$player = '1';
		} elseif (!empty($_POST['loginSecond'])) {
			$player = '2';
		}

		for ($i = 0; $i < 11; $i++) {
			$form .= "<tr>";
			$form .= "<th>{$i}</th>";
			foreach ($coordinates as $key => $value) {
				if ($i === 0) {
					$form .= "<th>{$value}</th>";
					$this->field[$player][$i] = $this->playerLogin[$player];
				} else {
					$form .= '<td><input type="checkbox" name="' . $i . $value . '" value="' . $i . $value . '"></td>';
					array_push($this->field[$player][$i], $_POST[$i . $value]);
				}
			}
			$form .= "</tr>";
		}

		if (!$this->fieldCheck($this->field[$player])) {
			$this->writeToFile($player);
			//var_dump($this->readToFile($player));
		} else {
			$form .= $this->fieldCheck($this->field[$player]);
		}
		return $form;
	}
}
?>