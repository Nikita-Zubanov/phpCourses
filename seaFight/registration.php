<?php 

class Registration
{
	public function getRegistrationFormGame()
	{
		$game = new game();
		$field = new field();

		$idPlayer = $field->getRegistrationIdPlayer();
		$fieldPlayer = $field->getRegistrationFieldPlayer($idPlayer);
		$error = $field->getError($fieldPlayer);

		if(!empty($_POST['loginFirst']) || !empty($_POST['loginSecond'])) {
			if (empty($error)) {
				$game->writeToFile($fieldPlayer, $idPlayer);
			} else {
				$field->setRegistrationFormPlayer($error);
			}
		} else {
			$field->setRegistrationFormPlayer("Расставьте корабли!");
		}

		return $field->getRegistrationFormPlayer();
	}
}