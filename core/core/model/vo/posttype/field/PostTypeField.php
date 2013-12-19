<?php
namespace tutons;

class PostTypeField extends ValueObject
{
	/* SET AND GET */
	public function setValue( $value, $postID )
	{
		return wp_update_post( array(
			"ID" => $postID,
			$this->getName() => $value
		) );
	}

	public function getValue( $postID )
	{
		return get_post_field( $this->getName(), $postID );
	}
}