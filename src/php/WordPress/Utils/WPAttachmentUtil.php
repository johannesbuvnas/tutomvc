<?php

	namespace TutoMVC\WordPress\Utils;

	class WPAttachmentUtil
	{
		/**
		 *    Uploads attachment from URL, returns WP attachment URL or WP Error object.
		 */
		public static function uploadAttachment( $url, $postID, $description = NULL )
		{
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');

			$tmp = download_url( $url );

			preg_match( '/[^\?]+/', $url, $matches );
			$file_array[ 'name' ]     = basename( $matches[ 0 ] );
			$file_array[ 'tmp_name' ] = $tmp;

			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp ) )
			{
				@unlink( $file_array[ 'tmp_name' ] );
				$file_array[ 'tmp_name' ] = '';
			}

			// do the validation and storage stuff
			$id = media_handle_sideload( $file_array, $postID, $description );

			// If error storing permanently, unlink
			if ( is_wp_error( $id ) )
			{
				@unlink( $file_array[ 'tmp_name' ] );

				return $id;
			}

			return $id;
		}

		/**
		 *    Uploads attachment from data, returns WP attachment URL or WP Error object.
		 */
		public static function uploadAttachmentFromByteArray( $fileName, $byteArray, $postID, $description = NULL )
		{
			return self::uploadAttachmentFromData( $fileName, $byteArray->data, $postID, $description );
		}

		/**
		 *    Uploads attachment from data, returns WP attachment URL or WP Error object.
		 */
		public static function uploadAttachmentFromData( $fileName, $data, $postID, $description = NULL )
		{
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');

			$localFileName = wp_tempnam();
			file_put_contents( $localFileName, $data );

			$file_array[ 'name' ]     = $fileName;
			$file_array[ 'tmp_name' ] = $localFileName;

			if ( !is_file( $localFileName ) )
			{
				$file_array[ 'tmp_name' ] = '';
			}

			$id = media_handle_sideload( $file_array, $postID, $description );

			if ( is_wp_error( $id ) )
			{
				@unlink( $file_array[ 'tmp_name' ] );

				return $id;
			}

			return $id;
		}
	}
