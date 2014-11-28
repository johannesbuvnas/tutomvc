<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 25/11/14
	 * Time: 12:52
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Proxy;

	class GitRepositoryProxy extends Proxy
	{
		const NAME              = __CLASS__;
		const FORMAT_CLONE_PATH = 'bin/git/repositories/object_id_%1$s';
		const FILTER_TEST       = "model/proxy/GitRepositoryProxy/test";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function onRegister()
		{
			add_filter( self::FILTER_TEST, array($this, "test"), 0, 5 );
		}

		public function test( $repositoryPath, $gitSSH, $branch, $sshPrivateKeyPath, $sshPrivateKeyPassphrase )
		{
//			var_dump( $repositoryPath, $gitSSH, $branch, $sshPrivateKeyPath, $sshPrivateKeyPassphrase );

			exec( "chmod +x " . $this->getSystem()->getVO()->getRoot( "src/shell/git-test.sh" ) );
			exec( $this->getSystem()->getVO()->getRoot( "src/shell/git-test.sh" ) . " $repositoryPath $gitSSH $branch $sshPrivateKeyPath $sshPrivateKeyPassphrase", $output, $returnVar );

//			var_dump( $output, $returnVar );

			return $returnVar > 0 ? FALSE : TRUE;
		}

		public static function locateRepository( $objectID )
		{
			return sprintf( self::FORMAT_CLONE_PATH, $objectID );
		}
	}