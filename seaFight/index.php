<html>
	<head>
		<meta charset="utf-8">
		<title>Морской бой</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
	<body>
		<table>
			<caption>Морской бой</caption>
			<form method="POST">
				<p><input type="radio" name="player" value="1" checked>Первый игрок</p>
    			<p><input type="radio" name="player" value="2">Второй игрок</p>
				<?php 

				include 'battlefield.php';
				$player = new Battlefield;

				$player->createFieldPlayer($_POST["player"]);

				?>
				<p><input type="submit" name="savePlayer" value="Сохранить поле игрока"></p>
				<input type="submit" href='game.php?hello=true' name="button" value="Отправить" />
				<a href='game.php?hello=true'>Run PHP Function</a>
			</form>
		</table>
	</body>
</html>