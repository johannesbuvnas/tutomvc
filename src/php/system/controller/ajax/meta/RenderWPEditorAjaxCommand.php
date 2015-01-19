<?php
	namespace tutomvc;

	class RenderWPEditorAjaxCommand extends AjaxCommand
	{
		function __construct()
		{
			parent::__construct( AjaxCommands::RENDER_WP_EDITOR );
		}

		public function execute()
		{
			$content = array_key_exists( "content", $_REQUEST ) ? stripslashes( $_REQUEST[ 'content' ] ) : "";

			if ( array_key_exists( "userID", $_REQUEST ) && intval( $_REQUEST[ 'userID' ] ) )
			{
				$content = GetUserMetaDataFilter::getDBMetaValue( $_REQUEST[ 'userID' ], $_REQUEST[ 'metaKey' ] );
				$content = is_array( $content ) && count( $content ) ? $content[ 0 ] : $content;
			}
			else if ( array_key_exists( "postID", $_REQUEST ) && intval( $_REQUEST[ 'postID' ] ) )
			{
				$content = GetMetaDatFilter::getDBMetaValue( $_REQUEST[ 'postID' ], $_REQUEST[ 'metaKey' ] );
				$content = is_array( $content ) && count( $content ) ? $content[ 0 ] : $content;
			}
			else if ( array_key_exists( "metaKey", $_REQUEST ) )
			{
				$this->getFacade()->controller->removeCommand( "option_" . $_REQUEST[ 'metaKey' ] );
				$content = get_option( $_REQUEST[ 'metaKey' ] );
			}

			$settings = array(
				"quicktags" => FALSE
			);

			if ( array_key_exists( "textarea_name", $_REQUEST ) )
			{
				$settings[ 'textarea_name' ] = $_REQUEST[ 'textarea_name' ];
			}

			if ( is_array( $content ) && count( $content ) ) $content = array_pop( $content );
			if ( is_array( $content ) && !count( $content ) ) $content = "";

			wp_editor( $content, $_REQUEST[ 'elementID' ], $settings );
			exit;
		}
	}
