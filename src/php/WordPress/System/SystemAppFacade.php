<?php

	namespace TutoMVC\WordPress\System;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\Log\LogModuleFacade;
	use TutoMVC\WordPress\System\Controller\Action\AdminEnqueueScriptsAction;
	use TutoMVC\WordPress\System\Controller\Action\AdminHeadAction;
	use TutoMVC\WordPress\Core\Controller\Ajax\ParseFormGroupAjaxCommand;
	use TutoMVC\WordPress\Core\Controller\Ajax\WPEditorAjaxCommand;
	use TutoMVC\WordPress\TutoMVC;

	/**
	 * Class SystemAppFacade
	 * @package tutomvc
	 */
	final class SystemAppFacade extends Facade
	{
		function __construct()
		{
			parent::__construct( TutoMVC::NAME );
		}

		public function onRegister()
		{
//			if ( !session_id() )
//			{
//				session_name( TutoMVC::NAME );
//				session_start();
//			}

			$this->setRoot( TutoMVC::getRoot() );
			$this->setURL( TutoMVC::getURL() );

			parent::onRegister();
		}

		protected function prepModel()
		{
			$this->registerModule( new LogModuleFacade() );
		}

		protected function prepController()
		{
			$this->registerCommand( "admin_enqueue_scripts", new AdminEnqueueScriptsAction() );
			$this->registerCommand( "admin_head", new AdminHeadAction() );
			$this->registerCommand( WPEditorAjaxCommand::NAME, new WPEditorAjaxCommand() );
			$this->registerCommand( ParseFormGroupAjaxCommand::NAME, new ParseFormGroupAjaxCommand() );
		}
	}
