<?php
namespace tutomvc\wp;

class GitRepositoryVO extends ValueObject
{
	protected $_localPath;
	protected $_remotePath;
	protected $_branch;

	function __construct( $localPath, $remotePath, $branch )
	{
		$this->_localPath  = $localPath;
		$this->_remotePath = $remotePath;
		$this->_branch     = $branch;
	}

	/* METHODS */
	public function isInit()
	{
		return is_dir( FileUtil::filterFileReference( $this->_localPath . "/.git" ) );
	}

	public function init()
	{
		if ( $this->isInit() )
		{
			return TRUE;
		}

		$result = "";
		$result .= shell_exec( "cd " . $this->_localPath . " && git init" );
		$result .= shell_exec( "cd " . $this->_localPath . " && git remote add origin " . $this->_remotePath . " -f" );

		return $result;
	}

	public function hasUnpulledCommits()
	{
		return $this->getLocalVersion() != $this->getRemoteVersion();
	}

	public function pull()
	{
		$result = "";
		$result .= shell_exec( "cd " . $this->_localPath . " && git reset --hard" );
		$result .= shell_exec( "cd " . $this->_localPath . " && git pull origin {$this->_branch}" );

		return $result;
	}

	/* SET AND GET */
	public function getChangedFiles( $revision = NULL )
	{
		if ( is_null( $revision ) )
		{
			$revision = $this->getLocalVersion();
		}

		$result = shell_exec( "cd " . $this->_localPath . " && git diff-tree --no-commit-id --name-only -r {$revision}" );

		return explode( "\n", $result );
	}

	public function getLocalRevision( $number = 1 )
	{
		return shell_exec( "cd " . $this->_localPath . " && git log --pretty=format:'%H' -n {$number}" );
	}

	public function getRemoteRevision( $number = 1 )
	{
		return shell_exec( "cd " . $this->_localPath . " && git log origin/{$this->_branch} --pretty=format:'%H' -n {$number}" );
	}

	public function getLocalVersion()
	{
		return $this->getLocalRevision();
	}

	public function getRemoteVersion()
	{
		return $this->getRemoteRevision();
	}

	public function getStatus()
	{
		$status = "<pre>STATUS\n\n" . shell_exec( "cd " . $this->_localPath . " && git status" ) . "\n</pre>";
		$status .= "<pre>LOCAL REVISION\n\n" . $this->getLocalVersion() . "\n</pre>";
		$status .= "<pre>REMOTE REVISION\n\n" . $this->getRemoteVersion() . "</pre>";

		return $status;
	}

	public function revisionExists( $revision )
	{
		$result = shell_exec( "cd " . $this->_localPath . " && git log {$revision} --pretty=format:'%H' -n 1" );

		return $result == $revision ? TRUE : FALSE;
	}

	public function howManyCommitsBehindIs( $revision )
	{
		if ( ! $this->revisionExists( $revision ) )
		{
			return FALSE;
		}

		$i     = 0;
		$found = FALSE;
		while( ! $found )
		{
			$i ++;
			$result = shell_exec( "cd " . $this->_localPath . " && git log --pretty=format:'%H' -n {$i} --skip=" . ( $i - 1 ) . " --max-count=1" );
			if ( strpos( $result, $revision ) !== FALSE )
			{
				$found = TRUE;
			}
		}

		return $i - 1;
	}
}