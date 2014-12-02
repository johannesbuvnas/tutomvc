<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/11/14
	 * Time: 16:00
	 */

	namespace tutomvc\modules\git;

	use tutomvc\MetaField;
	use tutomvc\TutoMVC;

	class ServerMetaField extends MetaField
	{
		function __construct( $description = "" )
		{
			parent::__construct( ServerPostType::NAME, __( "Server", TutoMVC::NAME ), $description, MetaField::TYPE_SELECTOR_MULTIPLE, array(
				MetaField::SETTING_MAX_CARDINALITY => 1,
				MetaField::SETTING_INPUT_ON_ENTER  => FALSE
			) );
		}

		public function getSettings()
		{
			if ( !$this->hasSetting( self::SETTING_OPTIONS ) )
			{
				$options = array();
				$wpQuery = get_posts( array(
					"post_type"   => ServerPostType::NAME,
					"post_status" => "publish",
					"nopaging"    => TRUE,
					//					"meta_key"     => GitModule::POST_META_STATUS,
					//					"meta_value"   => array(GitModule::POST_META_STATUS_VALUE_OK, GitModule::POST_META_STATUS_VALUE_ERROR),
					//					"meta_compare" => "IN"
				) );

				foreach ( $wpQuery as $post )
				{
					$html         = "<div class='" . ServerPostType::NAME . "'>";
					$customStatus = get_post_meta( $post->ID, StatusMetaField::NAME, TRUE );
					$html .= $post->post_title;
					switch ( $customStatus )
					{
						case StatusMetaField::OK:

							$html .= " [ <strong style='color:green;'>" . strtoupper( $customStatus ) . "</strong> ] ";

							break;
						case StatusMetaField::ERROR:

							$html .= " [ <strong style='color:red;'>" . strtoupper( $customStatus ) . "</strong> ] ";

							break;
						default:

							$html .= " [ <strong style='color:red;'>" . __( "Unknown", TutoMVC::NAME ) . "</strong> ] ";

							break;
					}
					$html .= "</div>";
					$options[ $post->ID ] = $html;
				}

				$this->setSetting( self::SETTING_OPTIONS, $options );
			}

			return parent::getSettings();
		}
	}