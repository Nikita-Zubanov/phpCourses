<html>
	<head>
		<meta charset="utf-8">
		<title>Двигатель</title>
	</head>
	
	<body>
		<form method="POST">
			<p><b>Работа двигателя: </b></p>
			<p>
				<input type="radio" name="position" value="1">Включить<Br>
				<input type="radio" name="position" value="0">Выключить
			</p>
			<p><input type="submit" value="Применить"></p>
		</form>
		<div>
			<?php
			include 'engine.php';	
			$engine = new Engine;
			
			$engine->startTheEngine($_POST['position']);
			?>
		</div>
	</body>
</html>