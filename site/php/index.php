<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../../../style.css">
	</head>
	<body>
		<header>
			<h1>Информация</h1>
		</header>
		<div id="content">
			<?php

			$data = array(
				'name' => $_POST["name"],
				'surname' => $_POST['surname'],
				'gender' => $_POST["gender"],
				'age' => (int)$_POST["age"],
				'isPregnant' => (bool)$_POST["isPregnant"],
				'isStudent' => (bool)$_POST['isStudent'],
				'postCode' => $_POST['postCode']
			);

			output($data);

			function output($data) {
				echo 'Здравствуйте, ' . $data['name'] . ' ' . $data['surname'] . '! ' 
				. 'Вы ' . $data['gender'] . ' ' . $data['age'] . ' лет и у вас ';
				if ($data['isPregnant'] === false) {
					echo 'нет детей. ';
				} else {
					echo 'есть дети. ';
				}
				if ($data['isStudent'] === false) {
					echo 'Вы не учитесь, ';
				} else {
					echo 'Вы учитесь, ';
				}
				echo 'а ваш почтовый индекс ' . $data['postCode'] . '.';
			}
			?>
		</div>
	</body>
</html>