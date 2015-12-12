<?php
namespace tutomvc\wp;

class MetaVO extends ValueObject implements IMetaVO
{
	/* VARS */
	protected $_postID;
	protected $_type;
	protected $_metaField;

	function __construct( $name, $postID, $metaField )
	{
		$this->setName( $name );
		$this->setPostID( $postID );
		$this->setMetaField( $metaField );
	}

	/* METHODS */

	/* SET AND GET */
	public function setPostID( $id )
	{
		$this->_postID = $id;
	}
	public function getPostID()
	{
		return $this->_postID;
	}

	public function setValue( $value )
	{
		if(is_array($value))
		{
			foreach($value as $rawValue)
			{
				$this->setValue( $rawValue );
			}

			return TRUE;
		}

		if( is_null( $value ) ) return delete_post_meta( $this->getPostID(), $this->getName() );

		return add_post_meta( $this->getPostID(), $this->getName(), $value, false );
	}
	public function getValue()
	{
		return intval($this->getPostID()) ? get_post_meta( $this->getPostID(), $this->getName(), false ) : apply_filters( FilterCommand::META_VALUE, NULL, NULL, $this->getMetaField() );
	}
	public function getDBValue()
	{
		return GetMetaDatFilter::getDBMetaValue( $this->getPostID(), $this->getName() );
	}

	public function __toString()
	{
		return $this->getValue();
	}

	public function setMetaField( MetaField $value )
	{
		$this->_metaField = $value;
	}
	public function getMetaField()
	{
		return $this->_metaField;
	}

	public function getType()
	{
		return $this->getMetaField()->getType();
	}

	public function getSettings()
	{
		return $this->getMetaField()->getSettings();
	}
}

interface IMetaVO
{
	/* CONSTANTS */
	const CARDINALITY_SINGLE = 1;
	const CARDINALITY_UNLIMITED = -1;

	/* SET AND GET */
	public function setPostID( $id );
	public function getPostID();
}