<?php
namespace tutomvc;

class FacadeVO extends ValueObject
{
	/* VARS */
	protected $_root;
	protected $_url;
	protected $_wpRoot;
	/**
	*	Relative to the root.
	*/
	public $templatesDir = "/templates/";
	
	function __construct( $root )
	{
		$this->_root = FileUtil::filterFileReference( $root );
		$this->_url = get_bloginfo( 'wpurl' ) . FileUtil::filterFileReference( substr( $this->_root,  strripos( $this->_root, TutoMVC::getDocumentRoot() ) + strlen( TutoMVC::getWPRelativeRoot() ) + strlen( TutoMVC::getDocumentRoot() ) ) );
	}

	/* SET AND GET */
	public function getURL( $relativePath = NULL )
	{
		return is_null( $relativePath ) ? $this->_url : $this->_url . FileUtil::filterFileReference( "/" . $relativePath );
	}

	public function getTemplateFileReference( $relativePath = NULL )
	{
		return is_null( $relativePath ) ? FileUtil::filterFileReference( $this->_root . "/" . $this->templatesDir . "/" ) : FileUtil::filterFileReference( $this->_root . "/{$this->templatesDir}/{$relativePath}" );
	}

	public function getRoot( $relativePath = NULL )
	{
		return is_null( $relativePath ) ? FileUtil::filterFileReference( $this->_root ) : FileUtil::filterFileReference( $this->_root . "/{$relativePath}" );
	}

	public function getWPRoot()
	{
		return TutoMVC::getWPRoot();
	}
}