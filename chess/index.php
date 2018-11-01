<?php
                            
$chessman = array (
	'8:H' => 'black:king',
	'8:G' => 'black:rook',
	'7:F' => 'black:knight',
	'2:G' => 'white:king',
	'6:F' => 'white:rook',
	'5:F' => 'white:rook'
);

$board = array (
	'8' => array ('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0),
	'7' => array ('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0),
	'6' => array ('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0),
	'5' => array ('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0),
	'4' => array ('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0),
	'3' => array ('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0),
	'2' => array ('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0),
	'1' => array ('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0),
);
$letterConverter = array (
	'A' => 1,
	'B' => 2,
	'C' => 3,
	'D' => 4,
	'E' => 5,
	'F' => 6,
	'G' => 7,
	'H' => 8
);
$keysChessman = array_keys($chessman);

foreach ($chessman as $key => $letter) {
	switch ($letter) {
		case 'black:king':
			$positionBlackKing = $key;
			break;
		case 'white:king':
			$positionWhiteKing = $key;
			break;
	}
}
foreach ($board as $key1 => $x) {							//Присваиваем элементам массива board значения шахматных фигур 
	foreach ($x as $key2 => $y) {
		$cell = $key1 . ":" . $key2;
		var_dump($key1);
		for ($i = 0; $i < count($keysChessman); $i++) {
			if ($cell === $keysChessman[$i]) {
				switch ($chessman[$keysChessman[$i]]) {
					case 'black:king':
						$board[$key1][$key2] = 'black:king';
						break;
					case 'black:rook':
						$board[$key1][$key2] = 'black:rook';
						break;
					case 'black:knight':
						$board[$key1][$key2] = 'black:knight';
						break;
					case 'white:king':
						$board[$key1][$key2] = 'white:king';
						break;
					case 'white:rook':
						$board[$key1][$key2] = 'white:rook';
						break;
					case 'white:rook':
						$board[$key1][$key2] = 'white:rook';
						break;
				}
			}
		}
	}
}

function moveWhite($board, $chessman, $positionBlackKing, $letterConverter) {	//Функция, если ход белых
	$yKingBlack = substr($positionBlackKing, 0, 1);	//Узнаем позицию черного короля по x и по y(по y конвертируем из буквенной позиции в числовую с помощью массива letterConverter) 
	$xKingBlack = substr($positionBlackKing, -1);
	$xKingBlack = $letterConverter[$xKingBlack];
	$result;

	foreach ($board as $key1 => $a) {	//Проверяем, может ли какая-нибудь фигура белых убить за один ход черного короля
		foreach ($a as $key2 => $b) {
			$cell = $board[$key1][$key2];
			if ($cell != '0') {
				switch ($cell) {
					case 'white:king':
						$key2 = $letterConverter[$key2];
						$x = $key2;
						if (++$key1 == $yKingBlack && ++$x == $xKingBlack) $result = "Победа!";
						if (--$key1 == $yKingBlack && --$x == $xKingBlack) $result = "Победа!";
						if (++$key1 == $yKingBlack && --$x == $xKingBlack) $result = "Победа!";
						if (--$key1 == $yKingBlack && ++$x == $xKingBlack) $result = "Победа!";
						if (++$key1 == $yKingBlack && $x == $xKingBlack) $result = "Победа!";
						if (--$key1 == $yKingBlack && $x == $xKingBlack) $result = "Победа!";
						if ($key1 == $yKingBlack && ++$x == $xKingBlack) $result = "Победа!";
						if ($key1 == $yKingBlack && --$x == $xKingBlack) $result = "Победа!";
						break;
					case 'white:rook': 
						$key2 = $letterConverter[$key2];
						$x = $key2;
						for ($y = $key1; $y < 9; $y++) {
							if ($y === $key1) $x = $key2;
							if ($y == $yKingBlack && $x == $xKingBlack) $result = "Победа!";
							$x++;
						}
						for ($y = $key1; $y < 9; $y++) {
							if ($y === $key1) $x = $key2;
							if ($x >= 0 && $y == $yKingBlack && $x == $xKingBlack) $result = "Победа!";
							$x++;
						}
						for ($y = $key1; $y > 0; $y--) {
							if ($y === $key1) $x = $key2;
							if ($y == $yKingBlack && $x == $xKingBlack) $result = "Победа!";
							$x++;
						}
						for ($y = $key1; $y > 0; $y--) {
							if ($y === $key1) $x = $key2;
							if ($x >= 0 && $y == $yKingBlack && $x == $xKingBlack) $result = "Победа!";
							$x++;
						}
						break;
				}
			}	
		}
	}
	if (empty($result)) {
		$result = "Ничья";
	}
	return $result;
}
function moveBlack($board, $chessman, $positionWhiteKing, $letterConverter) {	//Функция, аналогичная moveWhite
	$yKingWhite = substr($positionWhiteKing, 0, 1);
	$xKingWhite = substr($positionWhiteKing, -1);
	$xKingWhite = $letterConverter[$xKingWhite];
	$result;

	foreach ($board as $key1 => $a) {
		foreach ($a as $key2 => $b) {
			$cell = $board[$key1][$key2];
			if ($cell != '0') {
				switch ($cell) {
					case 'black:king':
						$key2 = $letterConverter[$key2];
						$x = $key2;
						if (++$key1 == $yKingWhite && ++$x == $xKingWhite) $result = "Победа!";
						if (--$key1 == $yKingWhite && --$x == $xKingWhite) $result = "Победа!";
						if (++$key1 == $yKingWhite && --$x == $xKingWhite) $result = "Победа!";
						if (--$key1 == $yKingWhite && ++$x == $xKingWhite) $result = "Победа!";
						if (++$key1 == $yKingWhite && $x == $xKingWhite) $result = "Победа!";
						if (--$key1 == $yKingWhite && $x == $xKingWhite) $result = "Победа!";
						if ($key1 == $yKingWhite && ++$x == $xKingWhite) $result = "Победа!";
						if ($key1 == $yKingWhite && --$x == $xKingWhite) $result = "Победа!";
						break;
					case 'black:rook': 
						$key2 = $letterConverter[$key2];
						$x = $key2;
						for ($y = $key1; $y < 9; $y++) {
							if ($y === $key1) $x = $key2;
							if ($y == $yKingWhite && $x == $xKingWhite) $result = "Победа!";
							$x++;
						}
						for ($y = $key1; $y < 9; $y++) {
							if ($y === $key1) $x = $key2;
							if ($x >= 0 && $y == $yKingWhite && $x == $xKingWhite) $result = "Победа!";
							$x++;
						}
						for ($y = $key1; $y > 0; $y--) {
							if ($y === $key1) $x = $key2;
							if ($y == $yKingWhite && $x == $xKingWhite) $result = "Победа!";
							$x++;
						}
						for ($y = $key1; $y > 0; $y--) {
							if ($y === $key1) $x = $key2;
							if ($x >= 0 && $y == $yKingWhite && $x == $xKingWhite) $result = "Победа!";
							$x++;
						}
						break;
					case 'black:knight': 
						$key2 = $letterConverter[$key2];
						$x = $key2;
						$y = $key1;
						$y;
						if ($y + 2 == $yKingWhite && $x + 1 == $xKingWhite) $result = "Победа!";
						if ($y + 2 == $yKingWhite && $x - 1 == $xKingWhite) $result = "Победа!";
						if ($y - 2 == $yKingWhite && $x + 1 == $xKingWhite) $result = "Победа!";
						if ($y - 2 == $yKingWhite && $x - 1 == $xKingWhite) $result = "Победа!";
						if ($y + 1 == $yKingWhite && $x + 2 == $xKingWhite) $result = "Победа!";
						if ($y + 1 == $yKingWhite && $x - 2 == $xKingWhite) $result = "Победа!";
						if ($y - 1 == $yKingWhite && $x + 2 == $xKingWhite) $result = "Победа!";
						if ($y - 1 == $yKingWhite && $x - 2 == $xKingWhite) $result = "Победа!";
						break;
				}
			}	
		}
	}
	if (empty($result)) {
		$result = "Ничья";
	}
	return $result;
}
$resultWhite = moveWhite($board, $chessman, $positionBlackKing, $letterConverter);
$resultBlack = moveBlack($board, $chessman, $positionWhiteKing, $letterConverter);
if ($resultWhite === $resultBlack) {
	echo "Ничья.";
} elseif ($resultWhite === "Победа!") {
	echo "Победа белых!";
} elseif ($resultBlack === "Победа!") {
	echo "Победа черных!";
}
//П.с. Только после написания кода понял, что его можно реструктуризировать и написать более компактно. Лучше бы я сделал одну функцию, возвращающую исход, в которой были бы описаны: функция с ходами каждой из фигур(не важно какого цвета — я не расист все таки), две функции на исход белых и черных, — а в конце основной функции возвращался бы результат