<?php
namespace tutomvc\modules\analytics;
?>
<?php
	$gaAccount = get_option( AnalyticsSettings::ACCOUNT_ID );
	if(!empty($gaAccount))
	{
		$user = wp_get_current_user();
?>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];
			a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			<?php
			if( is_user_logged_in() )
			{
				$user = wp_get_current_user();
				$gacode = "ga('create', '{$gaAccount}', { 'userId': '%s' });";
				echo sprintf( $gacode, $user->user_login );
			}
			else
			{
				$gacode = "ga('create', '{$gaAccount}', 'auto');";
				echo sprintf( $gacode );
			}
			?>
			ga('send', 'pageview');
		</script>
<?php
	}
?>