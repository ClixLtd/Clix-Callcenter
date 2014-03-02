<?php

namespace Clix\Callcenter\Games\HelperClasses;

class BoardNumbering
{

	protected static $_instance = null;
	protected $_boardStyle = null;
	protected $_boardDimensions = array();

	public function generate($width, $height)
	{
		$this->_boardDimensions = array(
			'w' => $width,
			'h' => $height,
		);

		return $this->generateReverseSnake();
	}


	public function style($style=null)
	{
		$this->_boardStyle = $style;
		return $this;
	}


	public function generateReverseSnake()
	{

		$thisBoard = array();
		$currentSquare = 1;
		$up = true;

		for ($i=$this->_boardDimensions['h']; $i > 0; $i--) { 
			
			$rowConstant = $currentSquare+$this->_boardDimensions['w'];
			for ($j=1; $j < $this->_boardDimensions['w']+1; $j++) { 

				$thisSquare = ($i*$this->_boardDimensions['w']) - ($this->_boardDimensions['w']-$j);

				$thisBoard[$thisSquare] = ($up) 
					? $currentSquare 
					: $rowConstant - $j;

				$currentSquare++;
			}

			$up = ($up) ? false : true;

		}

		ksort($thisBoard, SORT_NUMERIC);

		return $thisBoard;
	}


	/**
	 * This method returns the instance to create a singleton
	 * @return class The instance of this class
	 */
	public static function create()
	{
		if ( is_null(self::$_instance) )
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	protected function __clone() 	  { /* Protected for singleton reasons */ }
	protected function __construct()  { /* Protected for singleton reasons */ }


}