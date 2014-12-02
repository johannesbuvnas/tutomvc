<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 25/11/14
	 * Time: 12:52
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Proxy;
	use tutomvc\TutoMVC;

	class GitRepositoryProxy extends Proxy
	{
		const NAME                     = __CLASS__;
		const FORMAT_CLONE_PATH        = 'bin/git/repositories/object_id_%1$s/clone';
		const FILTER_TEST              = "gitmodule/model/GitRepositoryProxy/test";
		const FILTER_LOCATE_REPOSITORY = "gitmodule/model/GitRepositoryProxy/locateRepository";
		const FILTER_KEY_ID            = "gitmodule/model/GitRepositoryProxy/getKeyID";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function onRegister()
		{
			add_filter( self::FILTER_TEST, array($this, "test"), 0, 5 );
			add_filter( self::FILTER_LOCATE_REPOSITORY, array($this, "locateRepository"), 0, 1 );
			add_filter( self::FILTER_KEY_ID, array($this, "getKeyID"), 0, 1 );
		}

		public function test( $repositoryPath, $gitSSH, $branch, $sshPrivateKeyPath, $sshPrivateKeyPassphrase )
		{
//			var_dump( $repositoryPath, $gitSSH, $branch, $sshPrivateKeyPath, $sshPrivateKeyPassphrase );

			exec( "chmod +x " . $this->getSystem()->getVO()->getRoot( "src/shell/git-test.sh" ) );
			exec( $this->getSystem()->getVO()->getRoot( "src/shell/git-test.sh" ) . " $repositoryPath $gitSSH $branch $sshPrivateKeyPath $sshPrivateKeyPassphrase", $output, $returnVar );

//			var_dump( $output, $returnVar );

			return $returnVar > 0 ? FALSE : TRUE;
		}

		public function locateRepository( $objectID )
		{
			return $this->getSystem()->getVO()->getRoot( sprintf( self::FORMAT_CLONE_PATH, $objectID ) );
		}

		public function getKeyID( $repositoryID )
		{
			return get_post_meta( $repositoryID, GitRepositoryMetaBox::constructMetaKey( GitRepositoryMetaBox::NAME, GitKeyPostType::NAME ), TRUE );
		}
	}