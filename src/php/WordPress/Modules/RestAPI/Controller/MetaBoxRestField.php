<?php

	namespace TutoMVC\WordPress\Modules\RestAPI\Controller;

	use TutoMVC\WordPress\Modules\MetaBox\MetaBoxModule;
	use TutoMVC\WordPress\Modules\RestAPI\Controller\RestField;

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
