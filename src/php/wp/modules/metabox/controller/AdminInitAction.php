<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 11/12/15
	 * Time: 11:38
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\wp\core\controller\command\ActionCommand;

	class AdminInitAction extends ActionCommand
	{
		function execute()
		{
			$this->getFacade()->registerCommand( "save_post", new SavePostAction() );
			$this->getFacade()->registerCommand( "profile_update", new EditUserProfileAction() );
			$this->getFacade()->registerCommand( "user_register", new EditUserProfileAction() );
			$this->getFacade()->registerCommand( "edit_attachment", $this->getFacade()->getCommand( "save_post" ) );
		}
	}