<?php

	namespace TutoMVC\Utils;

	class FileUtil
	{
		/**
		 * @param $directory
		 *
		 * @return array
		 */
		public static function listFiles( $directory )
		{
			if ( !is_dir( $directory ) ) return array();

			$content = array_diff( scandir( $directory ), array('.', '..') );
			$files   = array();
			foreach ( $content as $file )
			{
				$path = self::sanitizePath( "$directory/$file" );
				if ( is_file( $path ) ) $files[] = $path;
			}

			return $files;
		}

		/**
		 * @param $directory
		 *
		 * @return bool
		 */
		public static function removeDirectoryRecursively( $directory )
		{
			if ( !is_dir( $directory ) ) return FALSE;

			$files = array_diff( scandir( $directory ), array('.', '..') );
			foreach ( $files as $file )
			{
				(is_dir( "$directory/$file" )) ? self::removeDirectoryRecursively( "$directory/$file" ) : unlink( "$directory/$file" );
			}

			return rmdir( $directory );
		}

		/**
		 * Removes double slashes.
		 * Trims last slash.
		 *
		 * @param $path
		 *
		 * @return mixed|string
		 */
		public static function sanitizePath( $path )
		{
			$path = str_replace( "\\", DIRECTORY_SEPARATOR, $path );

			$path = str_replace( "//", DIRECTORY_SEPARATOR, $path );

			if ( substr( $path, strlen( $path ) - 1, 1 ) == DIRECTORY_SEPARATOR ) $path = substr( $path, 0, strlen( $path ) - 1 );

			return $path;
		}
	}
