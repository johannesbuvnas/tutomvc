<?php
namespace tutomvc;

class ProfileUpdateActionCommand extends ActionCommand
{
	const NAME = "profile_update";

	public function __construct()
	{
		parent::__construct( self::NAME );
	}

	function execute()
	{
		$userID = $this->getArg( 0 );

		foreach($this->getFacade()->userMetaCenter->getMap() as $metaBox)
		{
			if( WordPressUtil::verifyNonce( $metaBox->getName() . "_nonce", $metaBox->getName() ) )
			{
				$metaBox->clearMetaFields( $userID );
				$metaBox->addPostMeta( $userID, $metaBox->getName(), $_POST[ $metaBox->getName() ] );
				
				$map = $metaBox->getMetaBoxMap( $userID );

				foreach( $map as $metaBoxMap )
				{
					foreach( $metaBoxMap as $metaVO )
					{
						if( array_key_exists( $metaVO->getName(), $_POST ) ) $metaVO->setValue( $_POST[ $metaVO->getName() ] );
					}
				}
			}
		}
	}
}