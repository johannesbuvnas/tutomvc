<?php
namespace tutomvc;

class CodeMediator extends Mediator
{
	const NAME = "debug/code.php";

	protected $_title = "";
	protected $_lines = array();
	protected $_highlightedLine = -1;

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	/* ACTIONS */
	public function prepareFile( $file, $highlightedLine = -1 )
	{
		if(is_file($file))
		{
			$this->setTitle( $file );
			$this->setLines( file( $file ) );
			$this->setHighlightedLine( $highlightedLine );
		}

		return $this;
	}

	/* SET AND GET */
	function getContent()
	{
		$this->parse( "title", $this->getTitle() );
		$this->parse( "lines", $this->getLines() );
		$this->parse( "highlightedLine", $this->getHighlightedLine() );

		return parent::getContent();
	}

	function setTitle( $value )
	{
		$this->_title = $value;

		return $this;
	}
	function getTitle()
	{
		return $this->_title;
	}

	function setLines( $value )
	{
		$this->_lines = $value;

		return $this;
	}
	function getLines()
	{
		return $this->_lines;
	}

	function setHighlightedLine( $value )
	{
		$this->_highlightedLine = $value;

		return $this;
	}
	function getHighlightedLine()
	{
		return $this->_highlightedLine;
	}
}