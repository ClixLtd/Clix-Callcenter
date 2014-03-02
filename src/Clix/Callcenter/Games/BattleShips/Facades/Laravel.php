<?php 

namespace Clix\Callcenter\Games\BattleShips\Facades;

use Clix\Callcenter\Games\BattleShips\GameController;

class Laravel extends GameController
{

	protected $_hiddenSquareTableName    = "clix_callcenter_games_battleships_hidden_squares";
	protected $_revealedSquareTableName  = "clix_callcenter_games_battleships_revealed_squares";


	public function __construct()
	{
		$this->createTables();
		parent::__construct();

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

		$this->setProperty('board', $board);

		return $this;
	}

	/**
	 * Create all tables relating to this game
	 */
	protected function createTables()
	{
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

		return $this;
	}

}