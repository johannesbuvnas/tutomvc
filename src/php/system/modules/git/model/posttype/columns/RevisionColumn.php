<?php
/**
 * Created by PhpStorm.
 * User: johannesbuvnas
 * Date: 17/11/14
 * Time: 11:02
 */

namespace tutomvc\modules\git;


use tutomvc\MetaBox;
use tutomvc\TutoMVC;
use tutomvc\WPAdminPostTypeColumn;

class RevisionColumn extends WPAdminPostTypeColumn
{
	const NAME = "revision";

	function __construct()
	{
		parent::__construct( self::NAME, __( "Current revision", TutoMVC::NAME ) );
	}

	public function render( $postID )
	{
		$currentRevision = get_post_meta( $postID, MetaBox::constructMetaKey( GitWebhookMetaBox::NAME, GitWebhookMetaBox::REVISION ), TRUE );

		echo "<code>$currentRevision</code>";
	}
} 