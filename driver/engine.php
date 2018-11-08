<?php

class Engine 
{
	function startTheEngine($theEngineRunning) {
		include 'driver.php';
		$driver = new Driver;

		function run($driver) {
			ob_start();		//Я попытался воспользоваться буфером вывода, но так и не смог разобраться, как именно он работает в функциях с разной областью видимости
			for ($cycle = 1; $cycle < 5; $cycle++) {		//Цикл, имитирующий 4 такта двигателя
				$firstGroupOfPistons = function($driver, $cycle) {		//Я использую анонимную функцию, т.к. иначе нельзя использовать рекурсивную функцию 
					switch ($cycle) {							//Каждому такту присуще свои положения клапанов, поршней и т.п.
						case 1:
							$engineData['letPetrol'] = 'on';
							$engineData['letOutPetrol'] = 'off';
							$engineData['sparkingPlug'] = 'off';
							$engineData['movePiston'] = 'down';
							break;
						case 2:
							$engineData['letPetrol'] = 'off';
							$engineData['letOutPetrol'] = 'off';
							$engineData['sparkingPlug'] = 'on';
							$engineData['movePiston'] = 'up';
							break;
						case 3:
							$engineData['letPetrol'] = 'off';
							$engineData['letOutPetrol'] = 'off';
							$engineData['sparkingPlug'] = 'off';
							$engineData['movePiston'] = 'down';
							break;
						case 4:
							$engineData['letPetrol'] = 'off';
							$engineData['letOutPetrol'] = 'on';
							$engineData['sparkingPlug'] = 'off';
							$engineData['movePiston'] = 'up';
						break;
					}
					$driver -> letPetrolOne($engineData['letPetrol']);
					$driver -> letOutPetrolOne($engineData['letOutPetrol']);
					$driver -> sparkingPlugOne($engineData['sparkingPlug']);
					$driver -> movePistonOne($engineData['movePiston']);
			
					$driver -> letPetrolFour($engineData['letPetrol']);
					$driver -> letOutPetrolFour($engineData['letOutPetrol']);
					$driver -> sparkingPlugFour($engineData['sparkingPlug']);
					$driver -> movePistonFour($engineData['movePiston']);
				};

				$secondGroupOfPistons = function($driver, $cycle) {
					switch ($cycle) {
						case 1:
							$engineData['letPetrol'] = 'off';
							$engineData['letOutPetrol'] = 'off';
							$engineData['sparkingPlug'] = 'off';
							$engineData['movePiston'] = 'down';
							break;
						case 2:
							$engineData['letPetrol'] = 'off';
							$engineData['letOutPetrol'] = 'on';
							$engineData['sparkingPlug'] = 'off';
							$engineData['movePiston'] = 'up';
							break;
						case 3:
							$engineData['letPetrol'] = 'on';
							$engineData['letOutPetrol'] = 'off';
							$engineData['sparkingPlug'] = 'off';
							$engineData['movePiston'] = 'down';
							break;
						case 4:
							$engineData['letPetrol'] = 'off';
							$engineData['letOutPetrol'] = 'off';
							$engineData['sparkingPlug'] = 'on';
							$engineData['movePiston'] = 'up';
							break;
					}
					$driver -> letPetrolTwo($engineData['letPetrol']);
					$driver -> letOutPetrolTwo($engineData['letOutPetrol']);
					$driver -> sparkingPlugTwo($engineData['sparkingPlug']);
					$driver -> movePistonTwo($engineData['movePiston']);		

					$driver -> letPetrolThree($engineData['letPetrol']);
					$driver -> letOutPetrolThree($engineData['letOutPetrol']);
					$driver -> sparkingPlugThree($engineData['sparkingPlug']);
					$driver -> movePistonThree($engineData['movePiston']);
				};
				$firstGroupOfPistons($driver, $cycle);
				$secondGroupOfPistons($driver, $cycle);
			}
			ob_end_flush();
			run($driver);		//Использование рекурсивной функции для зацикливания
		}
		if ((bool) $theEngineRunning) run($driver);
	}
}