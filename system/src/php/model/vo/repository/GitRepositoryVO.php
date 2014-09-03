<?php
namespace tutomvc;

class GitRepositoryVO extends ValueObject
{
	protected $_localPath;
	protected $_remotePath;
	protected $_branch;

	function __construct( $localPath, $remotePath, $branch )
	{
		$this->_localPath = $localPath;
		$this->_remotePath = $remotePath;
		$this->_branch = $branch;
	}

	/* METHODS */
	public function isInit()
	{
		return is_dir( FileUtil::filterFileReference( $this->_localPath."/.git" ) );
	}
	public function init()
	{
		if($this->isInit()) return TRUE;
		
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
    	$result .= shell_exec( "cd ".$this->_localPath." && git pull origin {$this->_branch}" );

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
		return shell_exec( "cd ".$this->_localPath." && git log origin/{$this->_branch} -n 1" );
	}
	public function getStatus()
	{
		$status = "<pre>STATUS\n\n".shell_exec( "cd ".$this->_localPath." && git status" )."\n</pre>";
		$status .= "<pre>LOCAL REVISION\n\n".$this->getLocalVersion()."\n</pre>";
		$status .= "<pre>REMOTE REVISION\n\n".$this->getRemoteVersion()."</pre>";

		return $status;
	}
}