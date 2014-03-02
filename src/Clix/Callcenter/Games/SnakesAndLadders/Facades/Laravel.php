<?php 

namespace Clix\Callcenter\Games\SnakesAndLadders\Facades;

use Clix\Callcenter\Games\SnakesAndLadders\GameController;

class Laravel extends GameController
{

	protected $_forcedMovementTableName   = "clix_callcenter_games_snakesandladders_forced_movement";
	
	protected $_playerLocationsTableName  = "clix_callcenter_games_snakesandladders_player_locations";
	
	protected $_hiddenSquareTableName     = "clix_callcenter_games_snakesandladders_hidden_squares";
	protected $_revealedSquareTableName   = "clix_callcenter_games_snakesandladders_revealed_squares";



	public function __construct($width, $height, $boardType)
	{
		$this->createTables();
		parent::__construct($width, $height, $boardType);

		return $this;
	}

	/**
	 * Performs a complete reset of the board
	 */
	public function resetBoard()
	{
		$this->destroyTables();
		$this->createTables();

		return $this;
	}


	protected function getForcedMovement()
	{
		$boardProperty = $this->getProperty('boardContent');

		$forcedMovement = \DB::table($this->_forcedMovementTableName)
			->select('type', 'start_square', 'end_square')
			->get();

		$newProperties = array();

		foreach ($forcedMovement as $forced)
		{
			$newProperties[$forced->start_square] = array('type' => $forced->type, 'end_square' => $forced->end_square);
		}

		$boardProperty['forcedMovement'] = $newProperties;

		$this->setProperty('boardContent', $boardProperty);

	}

	protected function savePlayerPositions()
	{
		\DB::table($this->_playerLocationsTableName)->delete();

		$players = $this->getProperty('players');

		foreach ($players as $id => $square)
		{
			\DB::table($this->_playerLocationsTableName)->insert(
				array('player_id' =>$id, 'current_square' => $square )
			);
		}

		return $this;
	}

	protected function getPlayerPositions()
	{
		$playerLocations = \DB::table($this->_playerLocationsTableName)
			->orderBy('current_square', 'desc')
			->select('player_id', 'current_square')
			->get();

		$players = array();

		if (count($playerLocations) > 0)
		{
			foreach ($playerLocations as $singlePlayer)
			{
				$players[$singlePlayer->player_id] = $singlePlayer->current_square;
			}

			$this->setProperty('players', $players);
		}

		return $this;
	}


	/**
	 * Get details of all the used squares on the board
	 */
	protected function getSquares()
	{
		// Create a variable for the board
		$board = array(
			'hidden'   => array(),
			'revealed' => array(),
		);

		// Get a list of all hidden squares from the database
		$hiddenSquares    = \DB::table($this->_hiddenSquareTableName)
			->select('square', 'content')
			->get();

		foreach ($hiddenSquares as $singleSquare)
		{
			$board['hidden'][$singleSquare->square] = $singleSquare->content;
		}

		// Get a list of all revealed squares from the database
		$revealedSquares  = \DB::table($this->_revealedSquareTableName)
			->select('square', 'content')
			->get();

		foreach ($revealedSquares as $singleSquare)
		{
			$board['revealed'][$singleSquare->square] = $singleSquare->content;
		}

		$this->setProperty('boardContent', $board);

		$this->getPlayerPositions();
		$this->getForcedMovement();

		return $this;
	}


	/**
	 * Create all tables relating to this game
	 */
	protected function createTables()
	{
		if ( !\Schema::hasTable($this->_playerLocationsTableName) )
		{
			\Schema::create($this->_playerLocationsTableName, function($table)
			{
			    $table->increments('id');
			    $table->string('player_id');
			    $table->string('current_square');
			});
		}

		if ( !\Schema::hasTable($this->_forcedMovementTableName) )
		{
			\Schema::create($this->_forcedMovementTableName, function($table)
			{
			    $table->increments('id');
			    $table->string('type');
			    $table->string('start_square');
			    $table->string('end_square');
			});
		}

		if ( !\Schema::hasTable($this->_hiddenSquareTableName) )
		{
			\Schema::create($this->_hiddenSquareTableName, function($table)
			{
			    $table->increments('id');
			    $table->string('square');
			    $table->string('content');
			});
		}

		if ( !\Schema::hasTable($this->_revealedSquareTableName) )
		{
			\Schema::create($this->_revealedSquareTableName, function($table)
			{
			    $table->increments('id');
			    $table->string('square');
			    $table->string('content');
			});
		}

		return $this;
	}

	/**
	 * Drop all tables relating to this game
	 */
	protected function destroyTables()
	{
		if ( \Schema::hasTable($this->_hiddenSquareTableName) )
		{
			\Schema::drop($this->_hiddenSquareTableName);
		}

		if ( \Schema::hasTable($this->_revealedSquareTableName) )
		{
			\Schema::drop($this->_revealedSquareTableName);
		}

		if ( \Schema::hasTable($this->_revealedSquareTableName) )
		{
			\Schema::drop($this->_revealedSquareTableName);
		}

		if ( \Schema::hasTable($this->_playerLocationsTableName) )
		{
			\Schema::drop($this->_playerLocationsTableName);
		}

		return $this;
	}

}