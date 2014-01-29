<?php
namespace tutomvc;

class FacadeVO extends ValueObject
{
	/* VARS */
	protected $_facadeClassReference;
	protected $_facadeKey;
	protected $_root;
	protected $_url;
	protected $_wpURL;
	protected $_wpRoot;
	
	function __construct( $facadeClassReference, $facadeKey, $root )
	{
		$this->_facadeClassReference = $facadeClassReference;
		$this->_facadeKey = $facadeKey;
		$this->_root = FileUtil::filterFileReference( $root );
		$this->_wpURL = get_bloginfo( 'wpurl' );
		$this->_wpRoot = substr( $this->_wpURL, strpos( $this->_wpURL, $_SERVER['SERVER_NAME'] ) + strlen( $_SERVER['SERVER_NAME'] ) );
		$documentRoot = FileUtil::filterFileReference( getenv( "DOCUMENT_ROOT" ) );
		$this->_url = get_bloginfo( 'wpurl' ) . FileUtil::filterFileReference( substr( $this->_root,  strpos( $documentRoot, $this->_root ) + strlen( $this->_wpRoot ) + strlen( $documentRoot ) ) );
	}

	/* SET AND GET */
	public function getURL( $relativePath = NULL )
	{
		return is_null( $relativePath ) ? $this->_url : $this->_url . FileUtil::filterFileReference( "/" . $relativePath );
	}

	public function getTemplateFileReference( $relativePath = NULL )
	{
		return is_null( $relativePath ) ? FileUtil::filterFileReference( $this->_root . "/templates" ) : FileUtil::filterFileReference( $this->_root . "/templates/{$relativePath}" );
	}

	public function getFacadeClassReference()
	{
		return $this->_facadeClassReference;
	}

	public function getRoot()
	{
		return $this->_root;
	}

	public function getWPRoot()
	{
		return $this->_wpRoot;
	}

}