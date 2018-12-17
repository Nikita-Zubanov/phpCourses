<?php 

class Registration
{
	public function getRegistrationGame()
	{
		$game = new game();
		$field = new field();

		$idPlayer = $field->getRegistrationIdPlayer();
		$field->setRegistrationFieldPlayer($idPlayer);

		if(!empty($_POST['loginFirst']) || !empty($_POST['loginSecond'])) {
			$field->getShipsLocationAndCount($field->fieldPlayers[$idPlayer]);
			$field->setErrorShipPositioning($field->fieldPlayers[$idPlayer]);
			$field->setErrorShipsSize($field->fieldPlayers[$idPlayer], $field->ships);
			
			if (empty($field->error)) {
				$game->writeToFile($field->fieldPlayers[$idPlayer], $idPlayer);
			} else {
				$field->playerRegistrationForm .= $field->error;
			}
		} else {
			$field->playerRegistrationForm .= "Расставьте корабли!";
		}

		return $field->playerRegistrationForm;
	}
}