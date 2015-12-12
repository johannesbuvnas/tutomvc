<?php namespace tutomvc\wp;
/**
*	LinkUtility
*/

class LinkUtil
{
	const PATTERN_YOUTUBE_ID = "#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#";
	const PATTERN_VIMEO_LINK = "/https?:\/\/(?:www\.)?vimeo\.com\/\d{6}/";
	const PATTERN_VIMEO_ID = "/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/";

	public static function isVideoLink( $url )
	{
		return self::getYouTubeID( $url ) || self::isVimeo( $url );
	}
	public static function getYouTubeID( $url )
	{
		return preg_match_all( self::PATTERN_YOUTUBE_ID, $url, $matches );
	}
	public static function isVimeo( $url )
	{
		return preg_match( self::PATTERN_VIMEO_LINK, $url, $matches );
	}
	public static function getVimeoID( $url )
	{
		preg_match_all( self::PATTERN_VIMEO_ID, $url, $matches );
		if(count($matches))
		{
			$matches = array_pop($matches);
			return $matches[0];
		}

		return NULL;
	}
}