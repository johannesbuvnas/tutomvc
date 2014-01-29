<?php
namespace tutomvc;

class Mediator extends CoreClass implements IMediator
{
	/* VARS */
	protected $_viewComponent;
	protected $_dataProvider = array();
	protected $_content = "";


	function __construct( $viewComponent )
	{
		$this->setViewComponent( $viewComponent );
	}

	/* ACTIONS */
	/**
	*	Render content.
	*/
	public function render()
	{
		$output = $this->getContent();
		
		if(empty( $output ))
		{
			var_dump( __CLASS__ . ":: Nothing to render." );
		}
		else
		{
			echo $output;
		}
	}
	
	/* METHODS */
	/**
	*	Called when registered, before render is called.
	*/
	public function onRegister()
	{
	}

	/* SET AND GET */
	/**
	*	Parse a variable to template.
	*/
	public function parse( $variableName, $value )
	{
		$this->_dataProvider[ $variableName ] = $value;
	}

	public function setViewComponent( $viewComponent )
	{
		$this->_viewComponent = $viewComponent;
	}
	public function getViewComponent()
	{
		return $this->getFacade()->getTemplateFileReference( $this->_viewComponent );
	}

	final public function getName()
	{
		return $this->_viewComponent;
	}

	/**
	*	Set content manually.
	*/
	public function setContent($content)
	{
		$this->_content = $content;
	}

	/**
	*	@return string Content.
	*/
	public function getContent()
	{
		if(!isset($this->_viewComponent) || empty($this->_viewComponent))
		{	
			if(!isset($this->_content) || !strlen($this->_content)) return "";
			else return $this->_content;
		}
		
		if($this->_dataProvider) extract( $this->_dataProvider, EXTR_SKIP);
		
		ob_start();

		if( is_file( $this->getViewComponent() ) )
		{
			include $this->getViewComponent();
		}
		else 
		{
			throw new \ErrorException( "CUSTOM ERROR: "." No such file - " . $this->getViewComponent(), 0, E_ERROR );
		}

		$output = ob_get_clean();
		
		if($output == null) return "\n";
		else return $output;
	}
}

interface IMediator
{
	/* ACTIONS */
	public function render();

	/* METHODS */
	public function parse( $variableName, $value );

	/* SET AND GET */
	public function setViewComponent( $name );
	public function getViewComponent();
}