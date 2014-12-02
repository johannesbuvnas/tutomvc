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
			$status = get_post_meta( $postID, StatusMetaField::NAME, TRUE );
			switch ( $status )
			{
				case StatusMetaField::ERROR:

					echo "<strong style='color:red;'>" . strtoupper( $status ) . "</strong>";

					break;
				case StatusMetaField::OK:

					echo "<strong style='color:green;'>" . strtoupper( $status ) . "</strong>";

					break;
				default:
					echo "<strong>" . strtoupper( $status ) . "</strong>";
					break;
			}
		}

		public function sort( $wpQuery )
		{

		}
	}