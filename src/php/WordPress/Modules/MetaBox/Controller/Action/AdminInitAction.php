<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 11/12/15
	 * Time: 11:38
	 */

	namespace TutoMVC\WordPress\Modules\MetaBox\Controller\Action;

	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use TutoMVC\WordPress\Modules\MetaBox\Controller\Action\EditUserProfileAction;
	use TutoMVC\WordPress\Modules\MetaBox\Controller\Action\SavePostAction;

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
