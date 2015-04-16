<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/04/15
	 * Time: 09:42
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\ActionCommand;

	class SavePostAction extends ActionCommand
	{
		const NAME = "save_post";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function execute()
		{
			echo "<pre>";
			print_r( $_POST );
			echo "</pre>";
		}
	}