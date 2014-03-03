<?php

namespace Clix\Callcenter\Games\HelperClasses;
use Clix\Callcenter\Games\HelperClasses\BoardNumbering as BoardType;

class StandardBoardGame implements BoardGameInterface
{

	protected $properties = array();



	/**
	 * Actions we need to perform when first loading the board
	 */
	public function __construct($width, $height, $boardType)
	{
		$this->setDimensions($width, $height);

		$this->setProperty('board', BoardType::create()->style($boardType)->generate($width, $height));

		// Gets all the details of the used squares on the board
		$this->getSquares();
		return $this;
	}

	/**
	 * Any special actions that we need for this square go here
	 * @param  int $playerID
	 * @param  mixed $newPosition
	 */
	protected function squareAction($playerID, $newPosition)
	{

		return $this;
	}

	/**
	 * Generate the board including player positions
	 * @return array
	 */
	public function generateBoard()
	{
		$board = $this->getProperty('board');
		return $board;
	}

	/**
	 * Rolls dice and returns a total number of results
	 * @param  int    $sides
	 * @param  int    $numberOfDice
	 * @return array
	 */
	public function rollDice($sides=6, $numberOfDice=1)
	{
		$dice = new Dice($numberOfDice, $sides);
		$diceResults = $dice->performAllRolls();
		return $diceResults;
	}

	/**
	 * Sets the board to be specific dimensions
	 * @param int $boardHeight
	 * @param int $boardWidth
	 */
	public function setDimensions($boardWidth, $boardHeight)
	{
		$this->setProperty('dimensions', array(
			'width'  => $boardWidth,
			'height' => $boardHeight,
		));

		return $this;
	}

	/**
	 * Move a player to a whole new position on the board
	 * @param  int    $playerID
	 * @param  array  $newPosition
	 */
	public function movePlayer($playerID, $newPosition)
	{
		$dimensions = $this->getProperty('dimensions');
		$totalSquares = $dimensions['width'] * $dimensions['height'];

		$newPosition = ($newPosition > $totalSquares) ? $newPosition - $totalSquares : $newPosition;

		if ( isset($this->getProperty('players')[$playerID]) )
		{
			// Lets move the player to the new position
			$allPlayers = $this->getProperty('players');
			$allPlayers[$playerID] = $newPosition;

			// Store the players into the array
			$this->setProperty('players', $allPlayers);

			// Run any actions that are required for this position
			$this->squareAction($playerID, $newPosition);
		}
		else
		{
			return false;
		}

		// Save the players
		$this->savePlayerPositions();

		return $newPosition;
	}

	/**
	 * Add a player to the current board
	 * @param int   $playerID
	 * @param array $startPosition
	 */
	public function addPlayer($playerID, $startPosition)
	{
		// Check if this player is already on the board
		if ( isset($this->getProperty('players')[$playerID]) )
		{
			// If the player is on the board then we don't add them
			// instead we return false for the user to handle the error
			return false;
		}
		else
		{
			// Lets add the player to the board in the required position
			$allPlayers = $this->getProperty('players');
			$allPlayers[$playerID] = $startPosition;

			// Store the players into the array
			$this->setProperty('players', $allPlayers);

			// Run any actions that are required for this position
			$this->squareAction($playerID, $startPosition);
		}

		// Save the players
		$this->savePlayerPositions();

		return $this->getProperty('lastResult');
	}

	public function getPlayerPosition($playerID=null)
	{
		$players = $this->getProperty('players');
		return $players[$playerID];
	}

	public function getAllPlayerPosition()
	{
		$players = $this->getProperty('players');
		return $players;
	}

	public function getLastAction()
	{
		return $this->getProperty('lastResult');
	}


	/**
	 * Gets a property for the class
	 * @param  string $property
	 * @return mixed
	 */
	public function getProperty($property)
	{
		return (isset($this->properties[$property])) 
			? $this->properties[$property] 
			: false;

	}

	/**
	 * Sets a property for the class
	 * @param mixed $property
	 */
	public function setProperty($property, $value)
	{
		$this->properties[$property] = $value;

		return $this;
	}

	/**
	 * EXTENDED: This function is extended to a facade
	 * Save player positions somewhere
	 */
	protected function savePlayerPositions() { return $this; }

	/** 
	 * EXTENDED: This function is extended to a facade
	 * Get details of all available squares
	 */
	protected function getPlayerPositions() { return $this; }

	/** 
	 * EXTENDED: This function is extended to a facade
	 * Get details of all available squares
	 */
	protected function getSquares() { return $this; }

	/**
	 * EXTENDED: This function is extended to a facade
	 * Performs a complete reset of the board
	 */
	public function resetBoard() { return $this; }

}