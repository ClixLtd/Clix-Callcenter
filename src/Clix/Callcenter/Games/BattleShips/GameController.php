<?php

namespace Clix\Callcenter\Games\BattleShips;

use Clix\Callcenter\Games\HelperClasses\StandardBoardGame;
use Clix\Callcenter\Games\HelperClasses\Dice;

class GameController extends StandardBoardGame
{

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