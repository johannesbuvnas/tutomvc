<?php
namespace tutomvc;

class LogProxy extends Proxy
{
	
	/* METHODS */
	public function add( $message )
	{
		$file = $this->getLogFile();

		if(!is_file( $file ))
		{
			$newFileContent = "<?php defined('ABSPATH') or die('No direct script access.'); ?>";
			$this->createFile( $file, $newFileContent );
		}

		$fileContent = file_get_contents( $file );
		$fileContent .= "\n".date( "Y-m-d H:i:s e" )." ::: ".$message;

		return file_put_contents( $file, $fileContent );
	}

	protected function createFile( $file, $content )
	{
		$dir = explode( '/', $file );
		$fileName = array_pop( $dir );
		$dir = implode( '/', $dir );
		if(!is_dir( $dir )) mkdir( $dir, 0777, TRUE );
		return file_put_contents( "$dir/$fileName", $content );
	}

	/* SET AND GET */
	public function get()
	{
		return NULL;
	}

	public function getMap()
	{
		if(!count($this->_map))
		{
			$di = new \RecursiveDirectoryIterator( $this->getFacade()->getLogsPath(), \RecursiveDirectoryIterator::SKIP_DOTS );
			$it = new \RecursiveIteratorIterator( $di );

			foreach($it as $file)
			{
			    $this->_map[ date("Y-m-d", filemtime($file->getPathname())) ] = $file->getPathname();
			}

			ksort( $this->_map, SORT_NUMERIC );
		}

		return $this->_map;
	}

	public function getLogFile( $time = 0 )
	{
		if(!$time) $time = time();

		$year = date( "Y", $time );
		$month = date( "m", $time );
		$day = date( "d", $time );

		return FileUtil::filterFileReference( $this->getFacade()->getLogsPath()."/".$year."/".$month."/".$day.".php" );
	}

}