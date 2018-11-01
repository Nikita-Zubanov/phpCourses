<?php

$forecast = array(
	'temperatureYesterday' => 14,
	'temperatureToday' => 13,
	'temperatureTomorrow' => 7,
	'rainOutside' => true,
	'predictionAnya' => "Сегодня шла по улице и так замерзла. Было очень холодно: заморозки..."
);

function willWalk($forecast){
	$momWords;
	$coldLevel;	//Переменная, показывающая насколько холодно на улице
	$forecast['predictionAnya'] = str_replace($array = ['!', '.', ',', '?', ':', '...'], '', $forecast['predictionAnya']); 	//Удаление знаков препинания							
	$AnyaWords = explode(" ", $forecast['predictionAnya']);	//Массив слов Ани												
	foreach ($AnyaWords as $word) {
		switch ($word) {
			case 'холодно':
				$coldLevel++;
				break;
			case 'заморозки':
				$coldLevel++;
				break;
			case 'замерзла':
				$coldLevel++;
				break;
		}
		if ($coldLevel === 2) break;	//Узнаем, насколько со слов Ани на улице холодно. Больше двух ключевых слов нас не интересует	
	}
	if ($forecast['temperatureYesterday'] > $forecast['temperatureToday'] && $forecast['temperatureToday'] > $forecast['temperatureTomorrow']) {	//Если на улице холодает или если Аня произнесла одно из ключевых слов
		$coldLevel++;
		$momWords .= "Ты хорошо оделся? ";
	} elseif ($coldLevel > 0) {
		$momWords .= "Ты хорошо оделся? ";
	}
	if ($forecast['temperatureYesterday'] < 13 && ($forecast['temperatureToday'] > 11 && $forecast['temperatureTomorrow'] > 11)){
		$momWords .= "Надень шапку. ";
	} else {
		$momWords .= "Надень теплую шапку. ";
	}
	if ($forecast['rainOutside']) {
		$momWords .= "Возьми с собой зонтик! ";
	}
	if ($forecast['temperatureTomorrow'] < $forecast['temperatureToday'] - 5) {
		$coldLevel++;
	} elseif ($forecast['temperatureTomorrow'] < $forecast['temperatureToday'] - 3) {
		$momWords .= "И шарф!";
	}
	if ($coldLevel === 4) $momWords = "Ты не пройдешь!";	//Если на улице слишком холодно(степень холода: 4), то не пускаем
	return $momWords;
}
echo willWalk($forecast);