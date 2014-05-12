<?php
namespace tutomvc;

class GitRepositoryVO extends ValueObject
{
	protected $_localPath;
	protected $_remotePath;

	function __construct( $localPath, $remotePath )
	{
		$this->_localPath = $localPath;
		$this->_remotePath = $remotePath;
	}

	/* METHODS */
	public function init()
	{
		if(is_dir( FileUtil::filterFileReference( $this->_localPath."/.git" ) )) return TRUE;
		
		$result = "";
		$result .= shell_exec( "cd ".$this->_localPath." && git init" );
    	$result .= shell_exec( "cd ".$this->_localPath." && git remote add origin ".$this->_remotePath." -f" );

    	return $result;
	}

	public function hasUnpulledCommits()
	{
		return $this->getLocalVersion() != $this->getRemoteVersion();
	}

	public function pull()
	{
		$result = "";
		$result .= shell_exec( "cd ".$this->_localPath." && git reset --hard" );
    	$result .= shell_exec( "cd ".$this->_localPath." && git pull origin master" );

    	return $result;
	}

	/* SET AND GET */
	public function getLocalVersion()
	{
		return shell_exec( "cd ".$this->_localPath." && git log -n 1" );
	}
	public function getRemoteVersion()
	{
		shell_exec( "cd ".$this->_localPath." && git fetch" );
		return shell_exec( "cd ".$this->_localPath." && git log origin/master -n 1" );
	}
	public function getStatus()
	{
		$status = "<pre>STATUS\n\n".shell_exec( "cd ".$this->_localPath." && git status" )."\n</pre>";
		$status .= "<pre>LOCAL REVISION\n\n".$this->getLocalVersion()."\n</pre>";
		$status .= "<pre>REMOTE REVISION\n\n".$this->getRemoteVersion()."</pre>";

		return $status;
	}
}