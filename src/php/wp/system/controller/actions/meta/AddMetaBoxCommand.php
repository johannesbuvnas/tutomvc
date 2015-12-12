<?php
namespace tutomvc\wp;

class AddMetaBoxCommand extends ActionCommand
{
	const NAME = "add_meta_boxes";

	function __construct()
	{
		parent::__construct( self::NAME );

		$this->setExecutionLimit( 1 );
	}

	function execute()
	{
		$postType = $this->getArg(0);

		foreach($this->getFacade()->metaCenter->getMap() as $metaBox)
		{
			if($metaBox->hasPostType( $postType ))
			{
				add_meta_box( 
					$metaBox->getName(),
					$metaBox->getTitle(),
					array( $this, "render" ),
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

	function render( $post, $args )
	{
		do_action( ActionCommand::PREPARE_META_FIELD );
		do_action( ActionCommand::RENDER_META_BOX, $args['args']['name'], $post->ID );
	}
}