<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 25/11/14
	 * Time: 09:31
	 */

	namespace tutomvc\modules\git;

	use tutomvc\MetaBox;
	use tutomvc\MetaField;
	use tutomvc\Notification;
	use tutomvc\TutoMVC;

	class GitRepositoryMetaBox extends MetaBox
	{
		const NAME    = "git_repository_settings";
		const ADDRESS = "address";
		const BRANCH  = "branch";

		function __construct()
		{
			parent::__construct( self::NAME, __( "Repository", TutoMVC::NAME ), array(GitRepositoryPostType::NAME) );
			$this->setMinCardinality( 1 );

			$this->addField( new MetaField( self::ADDRESS, __( "SSH address", TutoMVC::NAME ) ) );
			$this->addField( new MetaField( self::BRANCH, __( "Branch name", TutoMVC::NAME ) ) )
			     ->setSetting( MetaField::SETTING_DEFAULT_VALUE, "master" );
			$this->addField( new GitKeyMetaField() );
		}
	}