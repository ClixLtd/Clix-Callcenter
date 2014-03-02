<?php

namespace Clix\Callcenter\Games\HelperClasses;

class Dice
{
	protected $diceSides;
	protected $numberOfDice;

	/**
	 * Constructor for the class, sets all properties
	 * @param integer $numberOfDice
	 * @param integer $sides
	 */
	public function __construct($numberOfDice=1, $sides=6)
	{
		$this->diceSides = $sides;
		$this->numberOfDice = $numberOfDice;
		return $this;
	}

	/**
	 * Performs one roll of the dice
	 * @return int
	 */
	protected function roll()
	{
		return mt_rand(1, $this->diceSides);
	}

	/**
	 * Performs rolls based on specified number of dice
	 * @return array
	 */
	public function performAllRolls()
	{
		$totalResults = array(
			'total'	=> 0,
			'rolls' => array(),
		);

		for ($i=1; $i <= $this->numberOfDice; $i++)
		{
			$result = $this->roll();
			$totalResults['total'] = $totalResults['total'] + $result;
			$totalResults['rolls'][$i] = $result;
		}

		return $totalResults;

	}


}