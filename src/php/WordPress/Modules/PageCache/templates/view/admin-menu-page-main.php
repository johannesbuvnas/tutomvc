<?php
	use TutoMVC\Utils\ArrayUtil;
	use TutoMVC\WordPress\Modules\PageCache\PageCacheModule;

	if ( is_network_admin() )
	{
		$indexedFiles = PageCacheModule::getInstance()->getPageCacheProxy()->listIndexes();
		usort( $indexedFiles, array(ArrayUtil::class, "usortByDirectoryDepth") );
		/** @var WP_Site $site */
//		foreach ( get_sites() as $site )
//		{
//			if ( !empty( $indexes = PageCacheModule::getInstance()->getPageCacheProxy()->listIndexes( $site->domain ) ) ) $indexedFiles = array_merge( $indexedFiles, $indexes );
//		}
	}
	else
	{
		$siteURL      = get_bloginfo( "siteurl" );
		$siteURL      = parse_url( $siteURL );
		$indexedFiles = PageCacheModule::getInstance()->getPageCacheProxy()->listIndexes( $siteURL[ 'host' ] );
	}
?>
<script type="application/javascript">
	jQuery( "doucument" ).ready( jQuery( function () {
		jQuery( '[data-toggle="popover"]' ).popover()
	} ) );
</script>
<div class="wrap">
    <div class="container-fluid">
        <h1>Page Cache</h1>
        <!-- -->
        <form method="post" class="card">
			<?php
				wp_nonce_field( \TutoMVC\WordPress\Modules\PageCache\Controller\Action\ClearPageCacheAction::NAME, "_wpnonce" );
			?>
            <div class="card-block">
				<?php
				?>
                <p class="lead">...generates HTML index files and serves them directly.</p>
                <p>
					<?php
						if ( !empty( $indexedFiles ) && !is_network_admin() )
						{
							?>
                            <button name="what" value="site">Clear all</button>
							<?php
						}
						if ( is_network_admin() )
						{
							?>
                            <button name="what" value="everygadamthing">Clear everything</button>
							<?php
						}
					?>
                </p>
				<?php
					if ( !empty( $indexedFiles ) )
					{
						?>
                        <ul class="list-group">
							<?php
								foreach ( $indexedFiles as $key => $uri )
								{
									?>
                                    <li class="list-group-item">
                                        <a href="<?php echo "http://$uri"; ?>" target="_blank">
                                            <small><samp><?php echo $uri; ?></samp></small>
                                        </a>
                                        <span style="float: right">
                                                <button name="what" value="<?php echo $uri; ?>">Clear</button>
                                            </span>
                                    </li>
									<?php
								}
							?>
                        </ul>
						<?php
					}
				?>
            </div>
        </form>
        <!-- LEVERAGE BROWSER CACHE -->
        <div class="card">
            <div class="card-block">
                <h2>Leverage Browser Cache</h2>
                <p class="lead">The code below tells browsers what to cache and how long to "remember" it.</p>
                <p class="lead">It should be added to the top of your <samp>.htaccess</samp> file.</p>
                <pre><code>
&lt;IfModule mod_expires.c&gt;
    ExpiresActive On
    ExpiresByType image/jpg "access 1 year"
    ExpiresByType image/jpeg "access 1 year"
    ExpiresByType image/gif "access 1 year"
    ExpiresByType image/png "access 1 year"
    ExpiresByType text/css "access 1 month"
    ExpiresByType text/html "access 1 month"
    ExpiresByType application/pdf "access 1 month"
    ExpiresByType text/x-javascript "access 1 month"
    ExpiresByType application/x-shockwave-flash "access 1 month"
    ExpiresByType image/x-icon "access 1 year"
    ExpiresDefault "access 1 month"
&lt;/IfModule&gt;
&#x3C;IfModule mod_deflate.c&#x3E;
    &#x3C;IfModule mod_setenvif.c&#x3E;
        &#x3C;IfModule mod_headers.c&#x3E;
            SetEnvIfNoCase ^(Accept-EncodXng|X-cept-Encoding|X{15}|~{15}|-{15})$ ^((gzip|deflate)\s*,?\s*)+|[X~-]{4,13}$ HAVE_Accept-Encoding
            RequestHeader append Accept-Encoding &#x22;gzip,deflate&#x22; env=HAVE_Accept-Encoding
        &#x3C;/IfModule&#x3E;
    &#x3C;/IfModule&#x3E;
    &#x3C;IfModule mod_filter.c&#x3E;
        AddOutputFilterByType DEFLATE &#x22;application/atom+xml&#x22; \
                                      &#x22;application/javascript&#x22; \
                                      &#x22;application/json&#x22; \
                                      &#x22;application/ld+json&#x22; \
                                      &#x22;application/manifest+json&#x22; \
                                      &#x22;application/rdf+xml&#x22; \
                                      &#x22;application/rss+xml&#x22; \
                                      &#x22;application/schema+json&#x22; \
                                      &#x22;application/vnd.geo+json&#x22; \
                                      &#x22;application/vnd.ms-fontobject&#x22; \
                                      &#x22;application/x-font-ttf&#x22; \
                                      &#x22;application/x-javascript&#x22; \
                                      &#x22;application/x-web-app-manifest+json&#x22; \
                                      &#x22;application/xhtml+xml&#x22; \
                                      &#x22;application/xml&#x22; \
                                      &#x22;font/eot&#x22; \
                                      &#x22;font/opentype&#x22; \
                                      &#x22;image/bmp&#x22; \
                                      &#x22;image/svg+xml&#x22; \
                                      &#x22;image/vnd.microsoft.icon&#x22; \
                                      &#x22;image/x-icon&#x22; \
                                      &#x22;text/cache-manifest&#x22; \
                                      &#x22;text/css&#x22; \
                                      &#x22;text/html&#x22; \
                                      &#x22;text/javascript&#x22; \
                                      &#x22;text/plain&#x22; \
                                      &#x22;text/vcard&#x22; \
                                      &#x22;text/vnd.rim.location.xloc&#x22; \
                                      &#x22;text/vtt&#x22; \
                                      &#x22;text/x-component&#x22; \
                                      &#x22;text/x-cross-domain-policy&#x22; \
                                      &#x22;text/xml&#x22;

    &#x3C;/IfModule&#x3E;
    &#x3C;IfModule mod_mime.c&#x3E;
        AddEncoding gzip              svgz
    &#x3C;/IfModule&#x3E;
&#x3C;/IfModule&#x3E;
                    </code></pre>
                <p class="">
                    <a href="https://varvy.com/pagespeed/leverage-browser-caching.html" target="_blank" class="">Read more</a>
                </p>
            </div>
        </div>
        <!-- -->
		<?php
			if ( !\TutoMVC\WordPress\Modules\PageCache\PageCacheModule::isWPCacheEnabled() )
			{
				?>
                <!-- #wp-cache -->
                <div class="card" id="wp-cache">
                    <div class="card-block">
                        <h2>WP_CACHE</h2>

                        <div class="error">
                            <p><a href="#wp-cache">WP_CACHE is not setup!</a></p>
                        </div>
                        <p class="lead">... is a variable defined in wp-config.php. It tells WordPress to use
                            <a href="#advanced-cache"><samp>advanced-cache.php</samp></a>.</p>
                        <p class="lead">Put this in the top of your wp-config.php<br/><code>define('WP_CACHE', TRUE);</code>
                        </p>
                    </div>
                </div>
                <!-- -->
				<?php
			}
		?>
		<?php
			if ( (!defined( "MULTISITE" ) || !MULTISITE) || is_network_admin() ):
				?>
                <!-- #advanced-cache -->
                <div class="card" id="advanced-cache">
                    <div class="card-block">
						<?php
							if ( !\TutoMVC\WordPress\Modules\PageCache\PageCacheModule::doesWPAdvancedCacheFileExists() )
							{
								?>
                                <div class="error">
                                    <p><a href="#advanced-cache"><samp>advanced-cache.php</samp> is not setup!</a></p>
                                </div>
								<?php
							}
						?>
                        <p class="lead">
                            <samp>advanced-cache.php</samp> is the first file that WordPress runs if cache has been enabled.
                        </p>
                        <p class="lead">It needs to be setup in order for page cache to be working properly.</p>
                        <p class="lead">Have you setup <samp>advanced-cache.php</samp>?</p>
                        <p class="lead">
                        <form action="#" method="post">
							<?php
								wp_nonce_field( \TutoMVC\WordPress\Modules\PageCache\Controller\Action\SetupAdvancedCacheAction::NAME, "_wpnonce" );
							?>
                            <button class="btn btn-primary">Press here to be sure</button>
                        </form>
                        </p>
                    </div>
                </div>
                <!-- -->
			<?php endif; ?>
    </div>
</div>
