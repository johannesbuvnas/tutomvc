<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 28/11/14
	 * Time: 21:29
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Proxy;

	class GitWebhookProxy extends Proxy
	{
		const NAME          = __CLASS__;
		const ACTION_DEPLOY = "model/proxy/GitWebhookProxy/deploy";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function onRegister()
		{
			add_action( self::ACTION_DEPLOY, array($this, "deploy"), 0, 1 );
		}

		function deploy( $objectID )
		{
			$deployPostID = wp_insert_post( array(
				"post_type"   => GitDeployPostType::NAME,
				"post_status" => "publish",
				"post_title"  => "Deployed via webhook"
			) );
			update_post_meta( $deployPostID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::GIT_WEBHOOK ), $objectID );
			var_dump( $deployPostID );

			do_action( GitDeployProxy::ACTION_ADD, $deployPostID );
		}

	}