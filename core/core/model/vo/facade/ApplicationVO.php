<?php
namespace tutomvc;

class ApplicationVO extends ValueObject
{
	/* VARS */
	protected $_facadeClassReference;
	protected $_facadeKey;
	protected $_root;
	protected $_url;
	protected $_relativeURL;
	protected $_wpURL;
	protected $_wpRoot;
	
	function __construct( $facadeClassReference, $facadeKey, $root )
	{
		$this->_facadeClassReference = $facadeClassReference;
		$this->_facadeKey = $facadeKey;
		$this->_root = $root;
		$this->_wpURL = get_bloginfo( 'wpurl' );
		$this->_wpRoot = substr( $this->_wpURL, strpos( $this->_wpURL, $_SERVER['SERVER_NAME'] ) + strlen( $_SERVER['SERVER_NAME'] ) );
		$this->_url = get_bloginfo( 'wpurl' ) . FileUtil::filterFileReference( substr( $this->_root,  strpos( $this->_root, $this->_wpRoot ) + strlen( $this->_wpRoot ) ) );
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

}