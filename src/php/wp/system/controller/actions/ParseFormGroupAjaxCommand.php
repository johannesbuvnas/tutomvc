<?php

	namespace tutomvc\wp\system\controller\actions;

	use tutomvc\core\form\groups\FissileFormGroup;
	use tutomvc\wp\core\controller\command\AjaxCommand;
	use tutomvc\wp\metabox\MetaBox;
	use tutomvc\wp\metabox\MetaBoxModule;
	use tutomvc\wp\metabox\UserMetaBox;
	use tutomvc\wp\settings\Settings;
	use tutomvc\wp\settings\SettingsModule;
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