<?php
namespace tutons;

class Mediator extends CoreClass implements IMediator
{
	/* VARS */
	protected $_name;
	protected $_template;
	protected $_dataProvider = array();
	protected $_content = "";
	
	
	public function __construct( $name = NULL )
	{
		$this->setName( is_null( $name ) ? get_class( $this ) : $name );
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
			var_dump( $this->getName() . ":: Nothing to render." );
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

	/**
	*	Set view template (reference string) for this mediator.
	*/
	public function setTemplate( $template )
	{
		$this->_template = $template;
	}
	public function getTemplate()
	{
		return $this->getFacade()->getTemplateFileReference( $this->_template );
	}

	public function setName( $name )
	{
		$this->_name = $name;
	}
	/**
	*	@return string Mediator name
	*/
	public function getName()
	{
		return $this->_name;
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
		if(!isset($this->_template) || !strlen($this->_template))
		{	
			if(!isset($this->_content) || !strlen($this->_content)) return "";
			else return $this->_content;
		}
		
		if($this->_dataProvider) extract( $this->_dataProvider, EXTR_SKIP);
		
		ob_start();

		if( is_file( $this->getTemplate() ) )
		{
			include $this->getTemplate();
		}
		else 
		{
			// TODO: Error message. Should error messages be handled within facade or framework?
			var_dump( $this->getName() . ":: No such file - " . $this->getTemplate() );
			
			return "";
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
	public function setName( $name );
	public function getName();
	public function setTemplate( $templateReference );
	public function getTemplate();
}