<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 28/11/14
	 * Time: 21:47
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Proxy;

	class GitDeployProxy extends Proxy
	{

		const NAME       = __CLASS__;
		const ACTION_ADD = "model/proxy/GitDeployProxy/add";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function onRegister()
		{
			add_action( self::ACTION_ADD, array($this, "add"), 0, 1 );
		}

		function add( $item, $key = NULL, $override = FALSE )
		{
			var_dump( "Deploy", $item );
			// CHECK
			// Is this item deploying? If not: continue. If yes: Return conflict message.
			// Is other items with same webhook deploying? If not: continue. If yes: Return conflict message.
			// Is revision submitted? If not: fetch latest revision. If yes: Is the revision submitted valid?
			// Is comment submitted? If not: fetch latest commit message

			// PROCESS
			// Step 0: Change status to DEPLOYING / PROCESSING
			// Step 1: Clone git
			// Step 2: Fetch file list (all files if deploy from scratch, else list changes) (Is repository path submitted?)
			// Step 3: Connect to FTP and deploy file list
			// Step 4: Post to URL that deployment is done

			// END PROCESS
			// Step 1: Notify people
			// Step 2: Delete files
			// Step 3: Change status to DEPLOYED

			return FALSE;
		}

	}