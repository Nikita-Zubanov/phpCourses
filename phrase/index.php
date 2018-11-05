<?php

$phrase = "Complete the right sentence with random words in English, Spanish! Do you have an extra English dictionary by any chance? He suddenly talks English.";

function getString($phrase) {			//Из строки делаем массив
	$arrayString;

	$phrase = str_replace($array = ['. ', '! ', '... ', '? ', '?! '], '. ', $phrase);
	$arrayString = explode('. ', $phrase);

	return $arrayString;
}

$arrayString = getString($phrase);
function getWords($arrayString) {
	$arrayWords;
	$arrayString = str_replace($array = ['.', '!', '...', '?', '?!', ',', ':', ';', '-', '(', ')', '"'], '', $arrayString);								//Удаляем из строки все знаки препинания

	foreach ($arrayString as $key => $value) {			//Из массива строк делаем массив слов	
		$arrayWords[$key] = explode(' ', $value);
	}

	$offerNumber = 1;
	foreach ($arrayWords as $keyLines => $valueLines) {				//Цикл, проходящий по двумерному массиву и сравнивающий каждое слово с остальными для посчета одинаковых слов
		$arrayString[$keyLines] = str_pad($arrayString[$keyLines], strlen($arrayString[$keyLines]) + 3, "$offerNumber. ", STR_PAD_LEFT);
		$arrayString[$keyLines] .= " (";

		foreach ($valueLines as $keyWords => $valueWords) {			//Не знаю законно ли, но в индекс строки массива я записал длину слова, а в индекс элемента строки массива - кол-во этого слова в предложении
			$keyWords = 0;

			foreach ($valueLines as $word) {
				if ($valueWords === $word) $keyWords++;
			}
			$arrayString[$keyLines] .= strlen($valueWords) . "-" . $keyWords . " ";
		}
		$arrayString[$keyLines] .= ")";
		$offerNumber++;
	}
	foreach ($arrayString as $keyLines => $valueLines) {		//Вывод массива строк
		echo $arrayString[$keyLines] . "<br>";
	}
}

echo $phrase . "<br>";
getWords($arrayString);