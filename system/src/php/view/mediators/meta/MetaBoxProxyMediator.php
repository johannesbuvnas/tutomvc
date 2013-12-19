<?php
namespace tutomvc;

class MetaBoxProxyMediator extends SystemMediator
{
	const NAME = "MetaBoxProxyMediator";

	private $_metaBox;
	private $_metaBoxMediator;
	private $_postID;

	function __construct()
	{
		$this->setName( self::NAME );
		$this->setTemplate( "meta/meta-box-proxy.php" );
	}

	function onRegister()
	{
		$this->_metaBoxMediator = $this->getFacade()->view->hasMediator( MetaBoxMediator::NAME ) ? $this->getFacade()->view->getMediator( MetaBoxMediator::NAME ) : $this->getFacade()->view->registerMediator( new MetaBoxMediator() );
	}

	public function render()
	{
		$this->parse( "metaBox", $this->_metaBox );
		$this->parse( "postID", $this->_postID );
		$this->_metaBoxMediator->setMetaBox( $this->getMetaBox() );
		$this->parse( "metaBoxMediator", $this->_metaBoxMediator );

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

	public function setPostID( $postID )
	{
		$this->_postID = $postID;
	}
	public function getPostID()
	{
		return $this->_postID;
	}
}