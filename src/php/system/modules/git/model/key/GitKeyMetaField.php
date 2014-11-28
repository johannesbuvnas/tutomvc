<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/11/14
	 * Time: 16:00
	 */

	namespace tutomvc\modules\git;

	use tutomvc\MetaField;

	class GitKeyMetaField extends MetaField
	{
		function __construct( $description = "" )
		{
			parent::__construct( GitKeyPostType::NAME, __( "SSH Key" ), $description, MetaField::TYPE_SELECTOR_MULTIPLE, array(
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
					"post_type"   => GitKeyPostType::NAME,
					"post_status" => "publish",
					"nopaging"    => TRUE
				) );

				foreach ( $wpQuery as $post )
				{
					$html = "<div class='" . GitKeyPostType::NAME . "'>";
					if ( $post->post_status != "publish" ) $html .= "[" . __( $post->post_status ) . "] ";
					$html .= $post->post_title;
					$html .= "</div>";
					$options[ $post->ID ] = $html;
				}

				$this->setSetting( self::SETTING_OPTIONS, $options );
			}

			return parent::getSettings();
		}
	}