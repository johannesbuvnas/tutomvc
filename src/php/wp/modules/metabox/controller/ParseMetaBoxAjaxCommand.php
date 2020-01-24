<?php

	namespace tutomvc\wp\metabox;

	use tutomvc\core\form\groups\FissileFormGroup;
	use tutomvc\wp\core\controller\command\AjaxCommand;
	use tutomvc\wp\settings\Settings;
	use tutomvc\wp\settings\SettingsModule;
	use function wp_verify_nonce;

	class ParseMetaBoxAjaxCommand extends AjaxCommand
	{
		const NAME = "tutomvc_parse_metabox";

		public function execute()
		{
			if ( wp_verify_nonce( $_GET[ 'nonce' ], self::NAME ) )
			{
				$metaBoxID = $_GET[ 'mid' ];
				$type      = $_GET[ 'type' ];
				if ( strlen( $metaBoxID ) && strlen( $type ) )
				{
					/** @var FissileFormGroup $fissileFormGroup */
					$fissileFormGroup = NULL;
					switch ( $type )
					{
						case "metabox";

							/** @var MetaBox $metaBox */
							foreach ( MetaBoxModule::getProxy()->getMap() as $metaBox )
							{
								if ( $metaBox->getID() == $metaBoxID )
								{
									$fissileFormGroup = $metaBox;
									break;
								}
							}

							break;
					}

					if ( $fissileFormGroup )
					{
						$fissileFormGroup->parse( $_POST );
						$fissileFormGroup->output();
						exit;
					}
				}
			}

			die( 0 );
		}
	}