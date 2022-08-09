<?php

	namespace TutoMVC\Utils;

	class URLUtil
	{
		public static function formatCurrentURL()
		{
			$pageURL = 'http';
			if ( array_key_exists( "HTTPS", $_SERVER ) && $_SERVER[ "HTTPS" ] == "on" )
			{
				$pageURL .= "s";
			}
			$pageURL .= "://";
			if ( array_key_exists( "SERVER_PORT", $_SERVER ) && $_SERVER[ "SERVER_PORT" ] != "80" )
			{
				$pageURL .= $_SERVER[ "SERVER_NAME" ] . ":" . $_SERVER[ "SERVER_PORT" ] . $_SERVER[ "REQUEST_URI" ];
			}
			else
			{
				$pageURL .= $_SERVER[ "SERVER_NAME" ] . $_SERVER[ "REQUEST_URI" ];
			}

			return $pageURL;
		}
	}
