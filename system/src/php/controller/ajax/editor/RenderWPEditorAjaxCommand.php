<?php
namespace tutomvc;

class RenderWPEditorAjaxCommand extends AjaxCommand
{
	function __construct()
	{
		parent::__construct( AjaxCommands::RENDER_WP_EDITOR );
	}

	public function execute(  )
	{
		$content = array_key_exists( "content", $_REQUEST ) ? stripslashes( $_REQUEST[ 'content' ] ) : "";
		$id = $_REQUEST[ 'id' ];
		$settings = array(
			"quicktags" => FALSE,
			// 'textarea_rows' => 30,
		);		

		wp_editor( $content, $id, $settings );
		exit;
	}
}