<?php

$givenNumbers = array (
	'number' => '46D6A45',
	'numberSystem' => '16', 
	'convertIn' => '10'
);

function convertationNumberber($givenNumbers) {
	function convertInTen($givenNumbers) {						//Сначала переводим в десятичную систему счисления
		$convertNumber;
		$lengthNumber = strlen($givenNumbers['number']);
		switch ($givenNumbers['numberSystem']) {
			case '10':
				return $givenNumbers;
				break;
			case '8':
				for ($i = 0; $i < $lengthNumber; $i++) {
					$convertNumber += (int) $givenNumbers['number'][$lengthNumber - $i - 1] * 8**$i;
				}
				$givenNumbers['number'] = $convertNumber;
				$givenNumbers['numberSystem'] = '10';
				return $givenNumbers;
				break;
			case '2':
				for ($i = 0; $i < $lengthNumber; $i++) {
					$convertNumber += (int) $givenNumbers['number'][$lengthNumber - $i - 1] * 2**$i;
				}
				$givenNumbers['numberSystem'] = '10';
				$givenNumbers['number'] = $convertNumber;
				return $givenNumbers;
				break;
			case '16':
				$convertation = array (					//Массив для преобразования из букв в 16-тиричной системе счисления в числа 10-тичную систему счисления
					10 => 'A',
					11 => 'B',
					12 => 'C',
					13 => 'D',
					14 => 'E',
					15 => 'F'
				);
				for ($i = 0; $i < $lengthNumber; $i++) {
					if (ctype_digit($givenNumbers['number'][$lengthNumber - $i - 1])) {
						$convertNumber += (int) $givenNumbers['number'][$lengthNumber - $i - 1] * 16**$i;
					} else {
						$convertNumber += array_search($givenNumbers['number'][$lengthNumber - $i - 1], $convertation) * 16**$i;
					}
				}
				$givenNumbers['number'] = (string) $convertNumber;
				$givenNumbers['numberSystem'] = '10';
				return $givenNumbers;
				break;
		}
	}
	function convertInWantnumberber($givenNumbers) {					//Функция конвертирования в заданную систему счисления
		$saveNumber = (float) $givenNumbers['number'];
		switch ($givenNumbers['convertIn']) {
			case '2':
				$givenNumbers['number'] = "";
				while ($saveNumber != 0) {
					$givenNumbers['number'] .= (string) $saveNumber % 2;
					$saveNumber = floor($saveNumber / 2);
				}
				$givenNumbers['number'] = strrev($givenNumbers['number']);
				$givenNumbers['numberSystem'] = '2';
				return $givenNumbers;
				break;
			case '8':
				$givenNumbers['number'] = "";
				while ($saveNumber != 0) {
					$givenNumbers['number'] .= (string) $saveNumber % 8;
					$saveNumber = floor($saveNumber / 8);
				}
				$givenNumbers['number'] = strrev($givenNumbers['number']);
				$givenNumbers['numberSystem'] = '8';
				return $givenNumbers;
				break;
			case '16':
				$convertation = array (
					'A' => 10,
					'B' => 11,
					'C' => 12,
					'D' => 13,
					'E' => 14,
					'F' => 15
				);
				$givenNumbers['number'] = "";
				while ($saveNumber != 0) {
					if($saveNumber % 16 < 10) {
						$givenNumbers['number'] .= (string) $saveNumber % 16;
						$saveNumber = floor($saveNumber / 16);
					}
					else {
						$givenNumbers['number'] .= array_search((string)($saveNumber % 16), $convertation);
						$saveNumber = floor($saveNumber / 16);
					}
				}
				$givenNumbers['number'] = strrev($givenNumbers['number']);
				$givenNumbers['numberSystem'] = '16';
				return $givenNumbers;
				break;
			default:
				return $givenNumbers;
		}
	}
	$givenNumbers = convertInTen($givenNumbers);
	return convertInWantnumberber($givenNumbers);
}
var_dump($givenNumbers);
echo "<br>";
var_dump(convertationNumberber($givenNumbers));
