<?php
namespace tutomvc;

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