<?php
namespace tutomvc;

class MetaBoxProxy extends Proxy
{
	const NAME = __CLASS__;
	const WP_HOOK_REGISTER = "add_meta_boxes";
	const WP_HOOK_SAVE = "save_post";

	protected $_registeredSaveHooks = array();

	public function onRegister()
	{
		/* ACTIONS */
		$this->getFacade()->controller->registerCommand( new RenderMetaBoxProxyCommand() );

		/* AJAX */
		$this->getFacade()->controller->registerCommand( new RenderMetaBoxAjaxCommand() );
		$this->getFacade()->controller->registerCommand( new RenderWPEditorAjaxCommand() );
		
		/* FILTERS */
		$this->getFacade()->controller->registerCommand( new GetMetaValueFilterCommand() );
		$this->getFacade()->controller->registerCommand( new GetMetaDatFilter() );
	}

	public function add( MetaBox $item, $key = NULL )
	{
		add_action( self::WP_HOOK_REGISTER, array( $this, "registerMetaBox" ) );

		if( $this->getFacade()->model->hasProxy( PostTypeProxy::NAME ) )
		{
			foreach( $this->getFacade()->model->getProxy( PostTypeProxy::NAME )->getMap() as $postTypeVO )
			{
				// if($item->hasPostType( $postTypeVO->getName() )) $postTypeVO->addMetaBox( $item );
			}
		}

		foreach($item->getSupportedPostTypes() as $postType)
		{
			$hook = $this->getSaveHook($postType);
			if(!in_array($hook, $this->_registeredSaveHooks))
			{
				array_push($this->_registeredSaveHooks, $hook);
				add_action( $this->getSaveHook($postType), array( $this, "onSavePost") );
			}
		}

		parent::add( $item, $item->getName() );
	}

	/* ACTIONS */
	public function registerMetaBox( $postType )
	{
		foreach($this->getMap() as $metaBox)
		{
			if($metaBox->hasPostType( $postType ))
			{
				add_meta_box( 
					$metaBox->getName(),
					$metaBox->getTitle(),
					array( $this, "onRenderMetaBox" ),
					$postType,
					$metaBox->getContext(),
					$metaBox->getPriority(),
					array
					(
						"name" => $metaBox->getName()
					)
				);
			}
		}
	}

	public function renderMetaBox( $name, $postID )
	{
		do_action( ActionCommand::RENDER_META_BOX, $name, $postID );
	}

	private function postSave( $metaBox, $postID )
	{
		$metaBox->delete( $postID );
		update_post_meta( $postID, $metaBox->getName(), $_POST[ $metaBox->getName() ] ) || add_post_meta( $postID, $metaBox->getName(), $_POST[ $metaBox->getName() ], true );
		
		$map = $metaBox->getMetaBoxMap( $postID );

		foreach( $map as $metaBoxMap )
		{
			foreach( $metaBoxMap as $metaVO )
			{
				if( array_key_exists( $metaVO->getName(), $_POST ) ) $metaVO->setValue( $_POST[ $metaVO->getName() ] );
			}
		}

		return TRUE;
	}

	/* SET AND GET */
	private function getSaveHook( $postType )
	{
		$hook = self::WP_HOOK_SAVE;
		if($postType != "post" && $postType != "page" && $postType != "attachment") $hook = $hook . "_" . $postType;
		else if($postType == "attachment") $hook = "edit_attachment";

		return $hook;
	}

	/* EVENT HANDLERS */
	public function onRenderMetaBox( $post, $args )
	{
		do_action( ActionCommand::PREPARE_META_FIELD );
		
		$this->renderMetaBox( $args['args']['name'], $post->ID );
	}

	public function onSavePost( $postID )
	{
		$postType = get_post_type( $postID );

		foreach($this->getMap() as $metaBox)
		{
			if($metaBox->hasPostType( $postType ))
			{
				if( WordPressUtil::verifyNonce( $metaBox->getName() . "_nonce", $metaBox->getName() ) )
				{
					$this->postSave( $metaBox, $postID );
				}
			}
		}
	}
}

interface IMetaBoxProxy
{
	const HOOK_REGISTER = "add_meta_boxes";
	const HOOK_RENDER = "";

	function registerMetaBox();
	function renderMetaBox();
}