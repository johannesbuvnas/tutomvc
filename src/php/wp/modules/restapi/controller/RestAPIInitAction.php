<?php

	namespace tutomvc\wp\restapi;

	use tutomvc\wp\core\controller\command\ActionCommand;
	use tutomvc\wp\metabox\MetaBox;
	use tutomvc\wp\metabox\MetaBoxModule;
	use tutomvc\wp\metabox\UserMetaBox;

	class RestAPIInitAction extends ActionCommand
	{
		public function execute()
		{
			/** @var MetaBox $metaBox */
			foreach ( MetaBoxModule::getProxy()->getMap() as $metaBox )
			{
				if ( $metaBox->isRestEnabled() )
				{
					foreach ( $metaBox->getPostTypes() as $postType )
					{
						RestAPIModule::registerField( new MetaBoxRestField( $postType, $metaBox->getName() ) );
					}
				}
			}

			/** @var UserMetaBox $userMetaBox */
			foreach ( MetaBoxModule::getUserProxy()->getMap() as $userMetaBox )
			{
				if ( $userMetaBox->isRestEnabled() )
				{
					RestAPIModule::registerField( new UserMetaBoxRestField( "user", $userMetaBox->getName() ) );
				}
			}

			/** @var RestField $restField */
			foreach ( RestAPIModule::getRestFieldProxy()->getMap() as $key => $restField )
			{
				register_rest_field( $restField->getObjectType(), $restField->getName(), array(
					"get_callback"    => array($restField, "callback_get"),
					"update_callback" => array($restField, "callback_update"),
					"schema"          => array($restField, "callback_schema"),
				) );
			}

			/** @var RestRoute $restRoute */
			foreach ( RestAPIModule::getRestRouteProxy()->getMap() as $key => $restRoute )
			{
				register_rest_route( $restRoute->getNamespace(), $restRoute->getRoute(), array(
					"methods"             => $restRoute->getMethods(),
					"callback"            => array($restRoute, "callback"),
					"permission_callback" => array($restRoute, "callback_permission"),
					"args"                => $restRoute->getArgs()
				), $restRoute->isOverride() );
			}
		}
	}