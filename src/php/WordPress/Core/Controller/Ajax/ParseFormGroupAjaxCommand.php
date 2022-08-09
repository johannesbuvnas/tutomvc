<?php

	namespace TutoMVC\WordPress\Core\Controller\Ajax;

	use TutoMVC\Form\Group\FissileFormGroup;
	use TutoMVC\WordPress\Core\Controller\Command\AjaxCommand;
	use TutoMVC\WordPress\Modules\MetaBox\Controller\MetaBox;
	use TutoMVC\WordPress\Modules\MetaBox\MetaBoxModule;
	use TutoMVC\WordPress\Modules\MetaBox\Controller\UserMetaBox;
	use TutoMVC\WordPress\Modules\Settings\Controller\Settings;
	use TutoMVC\WordPress\Modules\Settings\SettingsModule;
	use function array_map;
	use function strlen;

	class ParseFormGroupAjaxCommand extends AjaxCommand
	{
		const NAME = "tutomvc_parse_form_group";

		public function execute()
		{
			if ( wp_verify_nonce( $_GET[ 'nonce' ], self::NAME ) )
			{
				$formGroupID = $_GET[ 'id' ];
				$type        = $_GET[ 'type' ];
				if ( strlen( $formGroupID ) && strlen( $type ) )
				{
					/** @var FissileFormGroup $fissileFormGroup */
					$fissileFormGroup = NULL;
					switch ( $type )
					{
						case "settings";

							/** @var Settings $settings */
							foreach ( SettingsModule::getProxy()->getMap() as $settings )
							{
								if ( $settings->getID() == $formGroupID )
								{
									$fissileFormGroup = $settings;
									break;
								}
							}

							break;
						case "metabox":

							/** @var MetaBox $metabox */
							foreach ( MetaBoxModule::getProxy()->getMap() as $metabox )
							{
								if ( $metabox->getID() == $formGroupID )
								{
									$fissileFormGroup = $metabox;
									break;
								}
							}

							break;
						case "user_metabox":

							/** @var UserMetaBox $userMetabox */
							foreach ( MetaBoxModule::getUserProxy()->getMap() as $userMetabox )
							{
								if ( $userMetabox->getID() == $formGroupID )
								{
									$fissileFormGroup = $userMetabox;
									break;
								}
							}

							break;
					}

					if ( $fissileFormGroup )
					{
						$_POST = array_map( 'stripslashes_deep', $_POST );
						$fissileFormGroup->parse( $_POST );
						$fissileFormGroup->output();
						exit;
					}
				}
			}
		}
	}
