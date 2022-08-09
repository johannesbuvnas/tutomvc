<?php

	namespace TutoMVC\WordPress\Modules\RestAPI\Controller;

	use TutoMVC\WordPress\Modules\MetaBox\Controller\UserMetaBox;
	use TutoMVC\WordPress\Modules\MetaBox\MetaBoxModule;

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
