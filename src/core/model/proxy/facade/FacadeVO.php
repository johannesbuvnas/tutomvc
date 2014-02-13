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
	protected $_domain;
	protected $_wpRoot;
	
	function __construct( $facadeClassReference, $facadeKey, $root )
	{
		$this->_facadeClassReference = $facadeClassReference;
		$this->_facadeKey = $facadeKey;
		$this->_root = FileUtil::filterFileReference( $root );
		$this->_wpURL = get_bloginfo( 'wpurl' );
		$this->_domain = parse_url( $this->_wpURL );
		$this->_domain = array_key_exists( "port", $this->_domain ) ? $this->_domain['host'] . ":" . $this->_domain['port'] : $this->_domain['host'];
		$this->_wpRoot = substr( $this->_wpURL, strpos( $this->_wpURL, $this->_domain ) + strlen( $this->_domain ) );
		$documentRoot = FileUtil::filterFileReference( getenv( "DOCUMENT_ROOT" ) );
		$this->_url = get_bloginfo( 'wpurl' ) . FileUtil::filterFileReference( substr( $this->_root,  strripos( $this->_root, $documentRoot ) + strlen( $this->_wpRoot ) + strlen( $documentRoot ) ) );
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

	public function getRoot( $relativePath = NULL )
	{
		return is_null( $relativePath ) ? FileUtil::filterFileReference( $this->_root ) : FileUtil::filterFileReference( $this->_root . "/{$relativePath}" );
	}

	public function getWPRoot()
	{
		return $this->_wpRoot;
	}

}