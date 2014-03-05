<?php
namespace tutomvc;

class Command extends CoreClass implements ICommand
{
	const NAME = "";

	/* VARS */
	protected $_name;
	protected $_executionLimit = -1;
	protected $_executions = 0;
	protected $_args;


	function __construct( $name = NULL )
	{
		$this->setName( $name );
	}


	/* PUBLIC METHODS */
	/**
	* Override.
	*/
	public function register()
	{

	}

	/**
	* Override.
	*/
	public function execute()
	{
	}

	public function remove()
	{
		
	}

	public function setName( $name )
	{
		$this->_name = is_null( $name ) ? $this::NAME : $name;
	}
	public function getName()
	{
		return $this->_name;
	}

	public function setExecutionLimit( $limit )
	{
		$this->_executionLimit = $limit;
	}

	public function getExecutionLimit()
	{
		return $this->_executionLimit;
	}

	public function getExecutionCount()
	{
		return $this->_executions;
	}

	public function hasReachedExecutionLimit()
	{
		return $this->_executionLimit > -1 && $this->_executions >= $this->_executionLimit;
	}

	/**
	* Get arguments parsed when executed.
	*/
	protected function getArgs()
	{
		return $this->_args;
	}
	protected function getArg( $index )
	{
		return array_key_exists($index, $this->_args) ? $this->_args[$index] : NULL;
	}

	/**
	*	Do not override.
	*/
	public function preExecution()
	{
		if( $this->hasReachedExecutionLimit() ) return;

		$this->_executions++;
		$this->_args = func_get_args();

		return call_user_func_array( array( $this, "execute" ), func_get_args() );
	}
}

interface ICommand
{
	public function setName( $name );
	public function getName();
	public function register();
	public function setExecutionLimit( $limit );
	public function getExecutionLimit();
	public function getExecutionCount();
	public function hasReachedExecutionLimit();
	public function preExecution();
}