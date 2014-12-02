<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 01/12/14
	 * Time: 10:25
	 */

	namespace tutomvc\modules\git;

	class DeploymentStageMetaField
	{
		const NAME      = "deploy_status";
		const DONE      = "done";
		const CLONING   = "cloning";
		const UPLOADING = "uploading";
	}