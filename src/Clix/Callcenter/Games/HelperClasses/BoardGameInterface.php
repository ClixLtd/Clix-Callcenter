<?php

namespace Clix\Callcenter\Games\HelperClasses;

interface BoardGameInterface
{

	/**
	 * Generate the board including player positions
	 * @return array
	 */
	public function generateBoard();

	/**
	 * Rolls dice and returns a total number of results
	 * @param  int    $sides
	 * @param  int    $numberOfDice
	 * @return array
	 */
	public function rollDice($sides, $numberOfDice);

	/**
	 * Performs a complete reset of the board
	 */
	public function resetBoard();

	/**
	 * Sets the board to be specific dimensions
	 * @param int $boardHeight
	 * @param int $boardWidth
	 */
	public function setDimensions($boardHeight, $boardWidth);

	/**
	 * Move a player to a whole new position on the board
	 * @param  int    $playerID
	 * @param  mixed  $newPosition
	 */
	public function movePlayer($playerID, $newPosition);

	/**
	 * Add a player to the current board
	 * @param int   $playerID
	 * @param mixed $startPosition
	 */
	public function addPlayer($playerID, $startPosition);

	/**
	 * Gets a property for the class
	 * @param  string $property
	 * @return mixed
	 */
	public function getProperty($property);

	/**
	 * Sets a property for the class
	 * @param string $property
	 * @param mixed $value
	 */
	public function setProperty($property, $value);

}