<?php

namespace Clix\Callcenter\Games\SnakesAndLadders;

use Clix\Callcenter\Games\HelperClasses\StandardBoardGame;
use Clix\Callcenter\Games\HelperClasses\Dice;

class GameController extends StandardBoardGame
{


	/**
	 * Any special actions that we need for this square go here
	 * @param  int $playerID
	 * @param  mixed $newPosition
	 */
	protected function squareAction($playerID, $newPosition)
	{
		$board = $this->getProperty('boardContent');

		if ( isset($board['forcedMovement'][$newPosition]) )
		{
			$newPositionDetails = $board['forcedMovement'][$newPosition];

			$this->setProperty('lastResult', array(
				'type' 	     => $newPositionDetails['type'],
				'end_square' => $newPositionDetails['end_square'],
			));

			$this->movePlayer($playerID, $newPositionDetails['end_square']);
		}
		else if ( isset($board['hidden'][$newPosition]) )
		{
			
		}
		else
		{

		}

		

		return $this;

	}


	/**
	 * Rolls dice and returns a total number of results
	 * @param  int    $sides
	 * @param  int    $numberOfDice
	 * @return array
	 */
	public function rollDice($sides=6, $numberOfDice=2)
	{
		$dice = new Dice($numberOfDice, $sides);
		$diceResults = $dice->performAllRolls();
		return $diceResults;
	}

}