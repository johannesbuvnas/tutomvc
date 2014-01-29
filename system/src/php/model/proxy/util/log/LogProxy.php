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
		$parts = explode( '/', $file );
		$fileName = array_pop( $parts );
		$dir = '';

		foreach($parts as $part)
		{
			$dir .= strlen($dir) ? "/$part" : "$part";
			if(!is_dir( $dir ))
			{
				var_dump($dir);
				mkdir( $dir );
			}
		}

		return file_put_contents( "$dir/$fileName", $content );
	}

	/* SET AND GET */
	public function get()
	{
		return NULL;
	}

	public function getLogFile()
	{
		$year = date( "Y" );
		$month = date( "m" );
		$day = date( "d" );

		return ( $this->getFacade()->getVO()->getRoot()."/logs/".$year."/".$month."/".$day.".php" );
	}

}