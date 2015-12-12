<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 11/12/15
	 * Time: 11:38
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\ActionCommand;

	class AdminInitAction extends ActionCommand
	{
		function execute()
		{
			$this->getFacade()->registerCommand( "save_post", new SavePostAction() );
		}
	}