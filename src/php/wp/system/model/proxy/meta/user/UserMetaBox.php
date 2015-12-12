<?php
namespace tutomvc\wp;

class UserMetaBox extends MetaBox
{

	function __construct( $name, $title, $maxCardinality, $minCardinality )
	{
		$this->setName( $name );
		$this->setTitle( $title );
		$this->setMaxCardinality( $maxCardinality );
		$this->setMinCardinality( $minCardinality );
	}

	public function addPostMeta( $postID, $key, $value )
	{
		return update_user_meta( $postID, $key, $value ) || add_user_meta( $postID, $key, $value, TRUE );
	}
	public function deletePostMeta( $postID, $key )
	{
		return delete_user_meta( $postID, $key );
	}
	public function getInstanceOfMetaVO( $metaName, $postID, $metaField )
	{
		return new UserMetaVO( $metaName, $postID, $metaField );
	}

	public function getCardinality( $postID )
	{
		$cardinality = GetUserMetaDataFilter::getDBMetaValue( $postID, $this->getName() );
		$cardinality = count($cardinality) ? intval( $cardinality[0] ) : 0;
		return $cardinality >= $this->getMinCardinality() ? $cardinality : $this->getMinCardinality();
	}
}