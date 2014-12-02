<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 01/12/14
	 * Time: 11:44
	 */

	namespace tutomvc\modules\git;

	use tutomvc\TutoMVC;
	use tutomvc\WPAdminPostTypeColumn;

	class GitDeployStageColumn extends WPAdminPostTypeColumn
	{
		const NAME = "deployment_stage";

		function __construct()
		{
			parent::__construct( self::NAME, __( "Stage", TutoMVC::NAME ), FALSE );
		}

		public function render( $postID )
		{
			$status = get_post_meta( $postID, DeploymentStageMetaField::NAME, TRUE );
			switch ( $status )
			{
				default:
					echo "<strong>" . strtoupper( $status ) . "</strong>";
					break;
			}
		}

		public function sort( $wpQuery )
		{

		}
	}