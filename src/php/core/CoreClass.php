<?php
namespace tutomvc;

class CoreClass
{
	/* PROTECTED */
	protected $_facadeKey;

	public function initializeFacadeKey( $key )
	{
		$this->_facadeKey = $key;
	}

	/**
	 * @return Facade
	 */
	public function getFacade()
	{
		if( is_null( $this->_facadeKey ) ) die( "No facade key has yet been initialized." );

		return Facade::getInstance( $this->_facadeKey );
	}

	/**
	 * @return Controller
	 */
	public function getController()
	{
		return $this->getFacade()->controller;
	}

	/**
	 * @return Model
	 */
	public function getModel()
	{
		return $this->getFacade()->model;
	}

	/**
	 * @return View
	 */
	public function getView()
	{
		return $this->getFacade()->view;
	}

	/**
	 * @return SystemFacade
	 */
	public function getSystem()
	{
		return Facade::getInstance( Facade::KEY_SYSTEM );
	}

	/**
	*	Called by the framework when registered and in the facade scope.
	*/
	public function onRegister()
	{
		
	}
}