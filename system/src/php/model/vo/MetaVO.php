<?php
namespace tutomvc;

class MetaVO extends ValueObject implements IMetaVO
{
	/* PRIVATE VARS */
	private $_postID;
	private $_type;

	function __construct( $name, $postID, $type = MetaType::FIELD_TEXT, $settings = array() )
	{
		$this->setName( $name );
		$this->setPostID( $postID );
		$this->setType( $type );
		$this->setSettings( $settings );
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
				$this->setValue( $rawValue, $this->getPostID() );
			}

			return TRUE;
		}

		if( is_null( $value ) ) return delete_post_meta( $this->getPostID(), $this->getName() );

		return add_post_meta( $this->getPostID(), $this->getName(), $value, false );
	}
	public function getValue()
	{
		$value = get_post_meta( $this->getPostID(), $this->getName(), false );
		$value = apply_filters( FilterCommand::META_VALUE, $value, $this->getType() );

		if( is_array($value) && count($value) == 1 && is_string( $value[0] ) ) return $value[0];

		return $value;
	}

	public function setType( $type )
	{
		$this->_type = $type;
	}
	public function getType()
	{
		return $this->_type;
	}

	public function setSettings( $settings )
	{
		$this->_settings = $settings;
	}
	public function getSettings()
	{
		return $this->_settings;
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
	public function setType( $type );
	public function getType();
	public function setSettings( $settings );
	public function getSettings();
}