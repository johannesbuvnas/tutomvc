<?php
namespace tutomvc\modules\analytics;
?>
<?php
	$gtmAccount = get_option( AnalyticsSettings::GTM_ACCOUNT_ID );
	if(!empty($gtmAccount))
	{
		$user = wp_get_current_user();
?>
		<script>
			dataLayer = [{
				'WP_USER' : {
					'username' : '<?php echo $user->user_login; ?>',
					'roles' : <?php echo json_encode( $user->roles ); ?>
				}
			}];
		</script>
		<!-- Google Tag Manager -->
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $gtmAccount; ?>"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo $gtmAccount; ?>');
		</script>
		<!-- End Google Tag Manager -->
<?php
	}
?>