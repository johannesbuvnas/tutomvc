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

	class ProgressionColumn extends WPAdminPostTypeColumn
	{
		const NAME = "progression";

		function __construct()
		{
			parent::__construct( self::NAME, __( "Progression", TutoMVC::NAME ), FALSE );
		}

		public function render( $postID )
		{
			$progression = get_post_meta( $postID, GitDeployMetaBox::constructMetaKey( GitDeployMetaBox::NAME, GitDeployMetaBox::PROGRESSION ), TRUE );
			echo "<strong>" . ($progression  * 100) . "%</strong>";
		}

		public function sort( $wpQuery )
		{

		}
	}