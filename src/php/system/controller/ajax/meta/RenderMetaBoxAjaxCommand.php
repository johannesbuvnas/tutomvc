<?php
namespace tutomvc;

class RenderMetaBoxAjaxCommand extends AjaxCommand
{
	function __construct()
	{
		parent::__construct( AjaxCommands::RENDER_META_BOX );
	}

	function register()
	{
		parent::register();
	}

	public function execute()
	{
		$metaBox = $this->getFacade()->model->getProxy( MetaBoxProxy::NAME )->get( $_REQUEST[ 'metaBoxName' ] );
		$key = $_REQUEST['key'];
		$metaFieldMap = $metaBox->getMetaFieldMap( $_REQUEST['postID'], $_REQUEST['key'] );

		$metaBoxMediator = $this->getFacade()->view->hasMediator( MetaBoxMediator::NAME ) ? $this->getFacade()->view->getMediator( MetaBoxMediator::NAME ) : $this->getFacade()->view->registerMediator( new MetaBoxMediator() );
		$metaBoxMediator->setMetaBox( $metaBox );
		$metaBoxMediator->parse( "cardinalityID", $key );
		$metaBoxMediator->parse( "metaFieldMap", $metaFieldMap );
		$metaBoxMediator->render();
		exit;
	}
}