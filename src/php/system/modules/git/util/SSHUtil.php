<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/11/14
	 * Time: 09:19
	 */

	namespace tutomvc\modules\git;

	use tutomvc\TutoMVC;

	class SSHUtil
	{

		const FORMAT_KEY_FILE_PATH         = 'bin/git/repository/%1$s/.ssh/%2$s';
		const FORMAT_REPOSITORY_CLONE_PATH = 'bin/git/repository/%1$s/clone';

		public static function generateKey( $label, $filePath, $passPhrase = "nosecret" )
		{
			global $systemFacade;
			exec( $systemFacade->getVO()->getRoot( "src/shell/ssh-keygen.sh" ) . " $label $filePath $passPhrase", $output, $returnVar );

			var_dump( $output );
			var_dump( $returnVar );

			// If shell script returns int(2), the key already exists and return TRUE.
			return $returnVar > 0 && $returnVar != 2 ? FALSE : TRUE;
		}

		public static function constructKeyFilePath( $categoryName, $keyFileName = "id_rsa" )
		{
			global $systemFacade;

			return $systemFacade->getVO()->getRoot( sprintf( self::FORMAT_KEY_FILE_PATH, $categoryName, $keyFileName ) );
		}

		public static function cloneGitRepository( $path, $sshURL, $branchName, $sshKey, $sshKeyPassPhrase = "nosecret" )
		{
			global $systemFacade;
			exec( "chmod +x " . $systemFacade->getVO()->getRoot( "src/shell/git-clone.sh" ) );
			exec( $systemFacade->getVO()->getRoot( "src/shell/git-clone.sh" ) . " $path $sshURL $branchName $sshKey $sshKeyPassPhrase", $output, $returnVar );

			var_dump( $output );
			var_dump( $returnVar );

			return $returnVar > 0 ? FALSE : TRUE;
		}

		public static function constructRepositoryClonePath( $categoryName )
		{
			global $systemFacade;

			return $systemFacade->getVO()->getRoot( sprintf( self::FORMAT_REPOSITORY_CLONE_PATH, $categoryName ) );
		}
	}