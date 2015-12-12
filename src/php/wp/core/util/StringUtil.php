<?php
namespace tutomvc\wp;

class StringUtil
{
	
	public static function stripLinks($text)
	{
		return preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $text);
	}

	public static function stripBrackets($text)
	{
		$text = preg_replace("/(\[(.*?)\](.*?)\[(.*?)\])/", "", $text);
		$text = preg_replace("/(\[(.*?)\])/", "", $text);

		return $text;
	}

	public static function text2link($text) 
	{
		$reg_exUrl = "/((((http|https|ftp|ftps):\/\/)|www.)[a-zA-Z0-9-.]+.[a-zA-Z]{2,4}(\/?\S*))/";

		return preg_replace( $reg_exUrl, "<a href=\"$1\" target='_blank'>$1</a> ", $text );
	}

	public static function generateSlug($phrase, $maxLength = -1)
	{
	    $result = strtolower($phrase);

	    $result = preg_replace("/[^a-z0-9\s-]/", "", $result);
	    $result = trim(preg_replace("/[\s-]+/", " ", $result));
	    $result = trim(substr($result, 0, $maxLength));
	    $result = preg_replace("/\s/", "-", $result);

	    return $result;
	}
	
}