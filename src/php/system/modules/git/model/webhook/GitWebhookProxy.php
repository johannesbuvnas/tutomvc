<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 28/11/14
	 * Time: 21:29
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Proxy;
	use tutomvc\TutoMVC;

	class GitWebhookProxy extends Proxy
	{
		const NAME            = __CLASS__;
		const ACTION_DEPLOY   = "gitmodule/model/GitWebhookProxy/deploy";
		const FILTER_REVISION = "gitmodule/model/GitWebhookProxy/getRevision";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function onRegister()
		{
			add_action( self::ACTION_DEPLOY, array($this, "deploy"), 0, 1 );
			add_filter( self::FILTER_REVISION, array($this, "getRevision"), 0, 1 );
		}

		function deploy( $objectID )
		{
			// CHECK
			// Is webhook already deploying?
			/////////////////////////////////////////////////////////////////////////////////////////////////////////
			if ( $this->isDeploying( $objectID ) )
			{
				die(__( "This webhook has already been triggered and is currently processing.", TutoMVC::NAME ));

				return new WP_Error( 'error', __( "This webhook has already been triggered and is currently processing.", TutoMVC::NAME ) );
			}
			/////////////////////////////////////////////////////////////////////////////////////////////////////////

			$deployPostID = wp_insert_post( array(
				"post_type"   => GitDeployPostType::NAME,
				"post_status" => "publish",
				"post_title"  => "Deployed via webhook"
			) );
			update_post_meta( $deployPostID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::GIT_WEBHOOK ), $objectID );
			update_post_meta( $deployPostID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::REVISION ), $this->getRevision( $objectID ) );

			do_action( GitDeployProxy::ACTION_ADD, $deployPostID );
		}

		public function getRevision( $objectID )
		{
			return get_post_meta( $objectID, GitWebhookMetaBox::constructMetaKey( GitWebhookMetaBox::NAME, GitWebhookMetaBox::REVISION ), TRUE );
		}

		public function setRevision( $objectID, $revision )
		{
			return update_post_meta( $objectID, GitWebhookMetaBox::constructMetaKey( GitWebhookMetaBox::NAME, GitWebhookMetaBox::REVISION ), $revision );
		}

		public function isDeploying( $objectID )
		{
			$searchQuery = get_posts( array(
				"post_type"   => GitDeployPostType::NAME,
				"post_status" => "any",
				"nopaging"    => TRUE,
				"meta_query"  => array(
					"relation" => "AND",
					array(
						"key"     => StatusMetaField::NAME,
						"value"   => StatusMetaField::PROCESSING,
						"compare" => "LIKE"
					),
					array(
						"key"     => GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::GIT_WEBHOOK ),
						"value"   => $objectID,
						"compare" => "LIKE"
					)
				)
			) );

			return count( $searchQuery ) ? TRUE : FALSE;
		}

	}