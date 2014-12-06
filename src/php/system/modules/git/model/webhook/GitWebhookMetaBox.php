<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 15/11/14
	 * Time: 08:03
	 */
	namespace tutomvc\modules\git;

	use tutomvc\MetaField;
	use tutomvc\TutoMVC;

	class GitWebhookMetaBox extends \tutomvc\MetaBox
	{
		const NAME                   = "git_webhook_settings";
		const GIT_REPOSITORY_PATH    = "git_repository_path";
		const SERVER_PATH            = "server_path";
		const REVISION               = "revision";
		const REVISION_DEFAULT_VALUE = "-";

		function __construct()
		{
			parent::__construct( self::NAME, __( "Settings" ), array(GitWebhookPostType::NAME) );

			$this->setMinCardinality( 1 );

			$this->addField( new GitRepositoryMetaField() );
			$this->addField( new MetaField( self::GIT_REPOSITORY_PATH, __( "Repository path", TutoMVC::NAME ), __( "Deploy only files from this path on the repository.", TutoMVC::NAME ) ) );
			$this->addField( new ServerMetaField() );
			$this->addField( new MetaField( self::SERVER_PATH, __( "Server path", TutoMVC::NAME ) ) );
			$this->addField( new MetaField( self::REVISION, __( "Current revision on server", TutoMVC::NAME ), __( "If blank, the first deployment will deploy from scratch." ) ) )
			     ->setSetting( MetaField::SETTING_DEFAULT_VALUE, self::REVISION_DEFAULT_VALUE );
		}
	}