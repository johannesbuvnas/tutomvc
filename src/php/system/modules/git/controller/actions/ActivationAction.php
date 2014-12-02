<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 01/12/14
	 * Time: 10:13
	 */

	namespace tutomvc\modules\git;

	use tutomvc\ActionCommand;

	class ActivationAction extends ActionCommand
	{
		const NAME = "gitmodule/controller/action/ActivationAction";

		function __construct()
		{
			parent::__construct();
		}

		/**
		 * TODO: exec() and change chmod on .sh-scripts.
		 * TODO: Create a .sh-scripts with variables.
		 */
		function execute()
		{

		}
	}