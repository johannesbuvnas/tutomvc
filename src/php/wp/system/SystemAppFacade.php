<?php

	namespace tutomvc\wp;

	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\log\LogModuleFacade;
	use tutomvc\wp\system\controller\actions\AdminEnqueueScriptsAction;
	use tutomvc\wp\system\controller\actions\AdminHeadAction;
	use tutomvc\wp\system\controller\actions\ParseFormGroupAjaxCommand;
	use tutomvc\wp\system\controller\actions\WPEditorAjaxCommand;

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
			if ( !session_id() )
			{
				// TODO: Why do we need this? Does it have something to do with view rendering?
				session_name( TutoMVC::NAME );
				session_start();
			}

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
