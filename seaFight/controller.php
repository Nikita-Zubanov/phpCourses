<?php 	

class Controller
{
	private function getGameStatus()
	{
		$game = new game();

		return $game->readToFile(game::GAME_STATUS_NAME_FILE);
	}

	public function setBattlefield()
	{
		$html = new view();
		$registration = new registration();

		if ($this->getGameStatus() === game::STATUS_GAME_OVER) {	
			$playerRegistrationForm = $registration->getRegistrationGame();
			echo $html->getHtmlRegistrationForm($playerRegistrationForm);
		}
	}

	public function setStartedGame()
	{
		$game = new game();

		if ($this->getGameStatus() === game::STATUS_GAME_BEGUN) {
			$game->setStartedGame();
		}
	}
}