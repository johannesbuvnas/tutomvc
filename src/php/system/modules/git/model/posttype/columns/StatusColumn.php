<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 26/11/14
	 * Time: 15:55
	 */

	namespace tutomvc\modules\git;

	use tutomvc\TutoMVC;
	use tutomvc\WPAdminPostTypeColumn;

	class StatusColumn extends WPAdminPostTypeColumn
	{
		const NAME = "custom_status";

		function __construct()
		{
			parent::__construct( self::NAME, __( "Status", TutoMVC::NAME ), FALSE );
		}

		public function render( $postID )
		{
			$status = get_post_meta( $postID, GitModule::POST_META_STATUS, TRUE );
			switch ( $status )
			{
				case GitModule::POST_META_STATUS_VALUE_ERROR:

					echo "<strong style='color:red;'>$status</strong>";

					break;
				case GitModule::POST_META_STATUS_VALUE_OK:

					echo "<strong style='color:green;'>$status</strong>";

					break;
			}
		}

		public function sort( $wpQuery )
		{

		}
	}