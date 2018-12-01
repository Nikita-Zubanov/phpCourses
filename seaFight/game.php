<html>
	<head>
		<meta charset="utf-8">
		<title>Морской бой</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
	<body>
		<table>
				<?php 
				$coordinates = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К'];
				for ($i = 0; $i < 11; $i++) {
				echo "<tr>";
				echo "<th>{$i}</th>";
				foreach ($coordinates as $key => $value) {
				if ($i === 0) {
					echo "<th>{$value}</th>";
					//$this->field[$player][$i] = $this->playerLogin[$player];
				} else {
					echo '<div id="first"><td>1</td></div>';
					//var_dump($i);
					//array_push($this->field[$player][$i], $_POST[$i . $value]);
				}
			}
			echo "</tr>";
		}
		//'.$i.'
				?>


		</table>
	</body>
</html>