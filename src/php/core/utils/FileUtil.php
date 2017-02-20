<?php
	namespace tutomvc\core\utils;

	class FileUtil
	{
		/**
		 * @param $directory
		 *
		 * @return bool
		 */
		public static function removeDirectoryRecursively( $directory )
		{
			$files = array_diff( scandir( $directory ), array('.', '..') );
			foreach ( $files as $file )
			{
				(is_dir( "$directory/$file" )) ? self::removeDirectoryRecursively( "$directory/$file" ) : unlink( "$directory/$file" );
			}

			return rmdir( $directory );
		}
	}