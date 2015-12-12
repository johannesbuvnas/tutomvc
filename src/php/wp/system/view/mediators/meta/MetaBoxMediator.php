<?php
namespace tutomvc\wp;

class MetaBoxMediator extends Mediator
{
	const NAME = "meta/meta-box.php";

	private $_metaBox;
	private $_metaFieldMediator;

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	function onRegister()
	{
		$this->_metaFieldMediator = $this->getFacade()->view->hasMediator( MetaFieldMediator::NAME ) ? $this->getFacade()->view->getMediator( MetaFieldMediator::NAME ) : $this->getFacade()->view->registerMediator( new MetaFieldMediator() );
	}

	public function render()
	{
		$this->parse( "metaBox", $this->_metaBox );
		$this->parse( "metaFieldMediator", $this->_metaFieldMediator );

		parent::render();
	}

	/* SET AND GET */
	public function setMetaBox( MetaBox $metaBox )
	{
		$this->_metaBox = $metaBox;
	}
	public function getMetaBox()
	{
		return $this->_metaBox;
	}
}