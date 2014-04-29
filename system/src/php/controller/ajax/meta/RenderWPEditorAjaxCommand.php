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
		if(intval($_REQUEST['postID']))
		{
			$content = GetMetaDatFilter::getDBMetaValue( $_REQUEST['postID'], $_REQUEST['metaKey'] );
			$content = is_array($content) && count($content) ? $content[0] : $content;
		}
		else
		{
			$this->getFacade()->controller->removeCommand( "option_" . $_REQUEST['metaKey'] );
			$content = get_option( $_REQUEST['metaKey'] );
		}

		$settings = array(
			"quicktags" => FALSE
		);

		if(is_array($content) && count($content)) $content = array_pop($content);
		if(is_array($content) && !count($content)) $content = "";

		wp_editor( $content, $_REQUEST[ 'elementID' ], $settings );
		exit;
	}
}
