<?php
	use tutomvc\wp\pagecache\PageCacheModule;

	$component = str_ireplace( PageCacheModule::getInstance()->getRoot(), "", PageCacheModule::getOriginalTemplateInclude() );

	if ( !PageCacheModule::isIgnoreCacheMode() && !is_404() )
	{
		$output  = PageCacheModule::getInstance()->render( $component, NULL, array(), TRUE );
		$expires = PageCacheModule::getExpireTimeInSeconds() > 0 ? date( "Y-m-d H:i:s", time() + PageCacheModule::getExpireTimeInSeconds() ) : "Never";
		$created = date( "Y-m-d H:i:s e", time() );
		$output .= "\n<!-- TutoMVC Page Cache -->";
		$output .= "\n<!-- Created: $created -->";
		$output .= "\n<!-- Expires: $expires -->";
		PageCacheModule::getInstance()->getPageCacheProxy()->add( $output );
		echo $output;
	}
	else
	{
		PageCacheModule::getInstance()->render( $component );
	}