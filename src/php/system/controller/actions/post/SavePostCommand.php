<?php
	namespace tutomvc;

	class SavePostCommand extends ActionCommand
	{
		const NAME = "save_post";

		public function __construct( $postType = NULL )
		{
			parent::__construct( self::constructHookName( $postType ) );
		}

		public static function constructHookName( $postType = NULL )
		{
			$hook = self::NAME;
			if ( $postType != "post" && $postType != "page" && $postType != "attachment" && !is_null( $postType ) ) $hook = $hook . "_" . $postType;
			else if ( $postType == "attachment" ) $hook = "edit_attachment";

			return $hook;
		}

		function execute()
		{
			$postID   = $this->getArg( 0 );
			$postType = get_post_type( $postID );

			foreach ( $this->getFacade()->metaCenter->getMap() as $metaBox )
			{
				if ( $metaBox->hasPostType( $postType ) )
				{
					if ( WordPressUtil::verifyNonce( $metaBox->getName() . "_nonce", $metaBox->getName() ) )
					{
						$metaBox->clearMetaFields( $postID );
						/** @var MetaBox $metaBox */
						$metaBox->addPostMeta( $postID, $metaBox->getName(), $_POST[ $metaBox->getName() ] );

						$map = $metaBox->getMetaBoxMap( $postID );

						foreach ( $map as $metaBoxMap )
						{
							foreach ( $metaBoxMap as $metaVO )
							{
								if ( array_key_exists( $metaVO->getName(), $_POST ) )
								{
									/** @var MetaVO $metaVO */
									$metaVO->setValue( $_POST[ $metaVO->getName() ] );
								}
							}
						}
					}
				}
			}

			foreach ( $this->getFacade()->postTypeCenter->getMap() as $customPostType )
			{
				if ( $customPostType->getName() == $postType )
				{
					$customPostType->save_post( $postID );
				}
			}
		}
	}