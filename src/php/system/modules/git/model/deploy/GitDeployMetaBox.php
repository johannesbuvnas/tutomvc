<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/11/14
	 * Time: 15:57
	 */

	namespace tutomvc\modules\git;

	use tutomvc\MetaBox;
	use tutomvc\MetaCondition;
	use tutomvc\MetaField;
	use tutomvc\SingleSelectorMetaField;
	use tutomvc\TextAreaMetaField;
	use tutomvc\TutoMVC;

	class GitDeployMetaBox extends MetaBox
	{
		const NAME                = "git_deploy_settings";
		const GIT_WEBHOOK         = "git_webhook";
		const REVISION            = "revision";
		const COMMENT             = "comment";
		const NOTIFY              = "notify";
		const DEPLOY_FROM_SCRATCH = "deploy_from_scratch";
		const FILE_LIST           = "file_list";

		function __construct()
		{
			parent::__construct( self::NAME, __( "Settings" ), array(GitDeployPostType::NAME), 1, MetaBox::CONTEXT_NORMAL, MetaBox::PRIORITY_CORE );
			$this->setMinCardinality( 1 );

			$this->addField( new WebHookMetaField( self::GIT_WEBHOOK, __( "Git Webhook", TutoMVC::NAME ) ) );
			$this->addField( new SingleSelectorMetaField( self::DEPLOY_FROM_SCRATCH, __( "Deploy from scratch", TutoMVC::NAME ), "", array(
				"true"  => __( "Yes" ),
				"false" => __( "No" )
			), "false" ) );
			$this->addField( new MetaField( self::REVISION, __( "Revision", TutoMVC::NAME ), __( "Leave empty for default: Latest commit / revision", TutoMVC::NAME ), MetaField::TYPE_TEXT, array(), array(
				new MetaCondition( self::NAME, self::DEPLOY_FROM_SCRATCH, "false", MetaCondition::CALLBACK_SHOW, MetaCondition::CALLBACK_HIDE )
			) ) );
			$this->addField( new MetaField( self::COMMENT, __( "Comment" ), __( "Leave empty for default: Commit message", TutoMVC::NAME ), MetaField::TYPE_TEXTAREA ) );
			$this->addField( new SingleSelectorMetaField( self::NOTIFY, __( "Notify", TutoMVC::NAME ), __( "Send notification to the creator of the webhook.", TutoMVC::NAME ), array(
				"true"  => __( "Yes" ),
				"false" => __( "No" )
			), "true" ) );
			$this->addField( new TextAreaMetaField( self::FILE_LIST, __( "List of files to upload", TutoMVC::NAME ), "", 15, TRUE ) );
		}
	}