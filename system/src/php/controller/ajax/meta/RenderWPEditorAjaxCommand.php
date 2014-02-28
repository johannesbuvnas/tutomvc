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
		$content = GetMetaDatFilter::getDBMetaValue( $_REQUEST['postID'], $_REQUEST['metaKey'] );
		$content = is_array($content) && count($content) ? $content[0] : $content;
		
		$id = $_REQUEST[ 'id' ];
		$settings = array(
			"quicktags" => FALSE
		);		

		wp_editor( $content, $id, $settings );
		exit;
	}
}