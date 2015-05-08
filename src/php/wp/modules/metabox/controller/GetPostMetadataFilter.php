<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 07/05/15
	 * Time: 16:40
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\FilterCommand;
	use tutomvc\FormGroup;

	class GetPostMetadataFilter extends FilterCommand
	{
		const NAME = "get_post_metadata";

		static $doNotExecute;

		function __construct()
		{
			parent::__construct( self::NAME, 99, 4 );
		}

		function execute()
		{
			if ( self::$doNotExecute ) return;

			$value    = $this->getArg( 0 );
			$postID   = $this->getArg( 1 );
			$metaKey  = $this->getArg( 2 );
			$isSingle = $this->getArg( 3 );
			$postType = get_post_type( $postID );

			/** @var MetaBox $metaBox */
			foreach ( $this->getFacade()->model->getProxy( MetaBoxProxy::NAME )->getMap() as $metaBox )
			{
				if ( in_array( $postType, $metaBox->getPostTypes() ) )
				{
					if ( $metaKey == $metaBox->getName() )
					{
						$int = $metaBox->countClones( $postID );
						$metaBox->setValue( $int );
						$valueMap = $metaBox->getValueMapAt();
					}
					else if ( $formElement = $metaBox->findFormElementByElementName( $metaKey ) )
					{
						if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
						{
							/** @var FormGroup $formElement */
							$valueMap = $formElement->getValueMapAt();
						}
						else
						{
							$valueMap = array($formElement->getElementName());
						}
					}

					if ( isset($valueMap) )
					{
						$value = $this->mapValues( $postID, $valueMap );
					}
				}
			}

			return $value;
		}

		protected function mapValues( $postID, $map )
		{
			self::$doNotExecute = TRUE;
			foreach ( $map as &$value )
			{
				if ( is_array( $value ) ) $value = $this->mapValues( $postID, $value );
				else if ( is_string( $value ) ) $value = get_post_meta( $postID, $value, TRUE );
			}
			self::$doNotExecute = FALSE;

			return $map;
		}
	}