<?php

	namespace tutomvc\wp\restapi;

	use pushndeploy\plugin\DeployMetaBox;
	use pushndeploy\plugin\DeployPostType;
	use pushndeploy\plugin\StatusMeta;
	use tutomvc\wp\metabox\MetaBoxModule;

	class MetaBoxRestField extends RestField
	{
		public function callback_get( $object, $fieldName, $request, $objectType )
		{
			return MetaBoxModule::getWPPostMetaByName( $object[ 'id' ], $fieldName );
		}

		public function callback_update( $value, $object, $fieldName, $request, $objectType )
		{
			if ( MetaBoxModule::get( $fieldName ) )
			{
				MetaBoxModule::get( $fieldName )->setFissions( $value );
				MetaBoxModule::get( $fieldName )->update( $object->ID );
			}
		}
	}