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

	public function init()
	{
		$result = "";
		$result .= shell_exec( "cd ".$this->_localPath." && git init" );
    	$result .= shell_exec( "cd ".$this->_localPath." && git remote add origin ".$this->_remotePath." -f" );

    	return $result;
	}

	public function pull()
	{
		$result = "";
		$result .= shell_exec( "cd ".$this->_localPath." && git reset --hard" );
    	$result .= shell_exec( "cd ".$this->_localPath." && git pull origin master" );

    	return $result;
	}
}