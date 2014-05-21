<?php
namespace tutomvc;

class UserMetaVO extends MetaVO
{
	/* SET AND GET */
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

		if( is_null( $value ) ) return delete_user_meta( $this->getPostID(), $this->getName() );

		return add_user_meta( $this->getPostID(), $this->getName(), $value, false );
	}
	public function getValue()
	{
		return intval($this->getPostID()) ? get_user_meta( $this->getPostID(), $this->getName(), false ) : apply_filters( FilterCommand::META_VALUE, NULL, NULL, $this->getMetaField() );
	}
}