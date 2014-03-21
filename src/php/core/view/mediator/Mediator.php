<?php
namespace tutomvc;

class Mediator extends CoreClass implements IMediator
{
	/* VARS */
	public $hookRender;
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
		$params = func_num_args() > 0 ? func_get_arg( 0 ) : NULL;
		if(is_array($params)) $this->_dataProvider = is_array( $this->_dataProvider ) ? array_merge( $params, $this->_dataProvider ) : $params;

		$output = $this->getContent();
		
		if(empty( $output ))
		{
			throw new \ErrorException( "CUSTOM ERROR: Nothing to render", 0, E_ERROR );
		}
		else
		{
			echo $output;
		}
	}

	public function flush()
	{
		unset($this->_dataProvider);
		$this->_dataProvider = array();
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
	public function parse( $variableName, $value = NULL )
	{
		if(is_array($variableName))
		{
			foreach( $variableName as $key => $value ) $this->parse( $key, $value );
		}
		else
		{
			$this->_dataProvider[ $variableName ] = $value;
		}

		return $this;
	}
	public function retrieve( $variableName )
	{
		return array_key_exists($variableName, $this->_dataProvider) ? $this->_dataProvider[ $variableName ] : NULL;
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
		$params = func_num_args() > 0 ? func_get_arg( 0 ) : NULL;
		if(is_array($params)) $this->_dataProvider = is_array( $this->_dataProvider ) ? array_merge( $params, $this->_dataProvider ) : $params;

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