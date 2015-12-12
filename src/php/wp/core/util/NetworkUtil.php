<?php
namespace tutomvc\wp;

class NetworkUtil
{
	/**
	*	@return string Client IP.
	*/
	public static function getClientIP()
	{
		$ip = $_SERVER['REMOTE_ADDR'];

        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $ip;
	}

	/**
	*	Uses a public service to retrieve country code by IP.
	*/
	public static function getCountryCode( $ip )
	{
		return file_get_contents( "http://geoip.wtanaka.com/cc/".$ip );
	}

}