<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/11/14
	 * Time: 14:11
	 */

	namespace tutomvc\modules\git;

	use tutomvc\FileUtil;

	class GitUtil
	{
		public static function getChangedFileListAfterRevision( $repositoryPath, $revision )
		{
			global $systemFacade;
			exec( "chmod +x " . $systemFacade->getVO()->getRoot( "src/shell/git-l-changes.sh" ) );
			exec( $systemFacade->getVO()->getRoot( "src/shell/git-l-changes.sh" ) . " $repositoryPath $revision", $output, $returnVar );

			if ( $returnVar == 0 )
			{
				$dp = array();
				foreach ( $output as $key => $relativeFilePath )
				{
					if ( !array_key_exists( $relativeFilePath, $dp ) )
					{
						$dp[ $relativeFilePath ] = FileUtil::filterFileReference( "$repositoryPath/$relativeFilePath" );
					}
				}

				return $dp;
			}

			return isset($dp) ? $dp : NULL;
		}

		public static function test( $repositoryPath, $repositorySSHAddress, $branch, $sshKey, $sshKeyPassphrase )
		{
			global $systemFacade;
			exec( "chmod +x " . $systemFacade->getVO()->getRoot( "src/shell/git-test.sh" ) );
			exec( $systemFacade->getVO()->getRoot( "src/shell/git-test.sh" ) . " $repositoryPath $repositorySSHAddress $branch $sshKey $sshKeyPassphrase", $output, $returnVar );

			var_dump( $output, $returnVar );
			exit;
		}
	}