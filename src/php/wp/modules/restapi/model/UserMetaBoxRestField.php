<?php

	namespace tutomvc\wp\restapi;

	use tutomvc\wp\metabox\MetaBoxModule;
	use tutomvc\wp\metabox\UserMetaBox;

	class UserMetaBoxRestField extends RestField
	{
		public function callback_get( $object, $fieldName, $request, $objectType )
		{
			return MetaBoxModule::getWPUserMetaByName( $object[ 'id' ], $fieldName );
		}

		public function callback_update( $value, $object, $fieldName, $request, $objectType )
		{
			/** @var UserMetaBox|null $userMetaBox */
			if ( $userMetaBox = MetaBoxModule::getUserProxy()->get( $fieldName ) )
			{
				$userMetaBox->setFissions( $value );
				$userMetaBox->update( $object->ID );
			}
		}
	}