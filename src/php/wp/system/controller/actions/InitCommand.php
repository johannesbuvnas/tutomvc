<?php
	namespace tutomvc\wp;

	class InitCommand extends ActionCommand
	{
		function __construct()
		{
			parent::__construct( "init" );
		}

		function execute()
		{
			$this->prepModel();
			$this->prepView();
			$this->prepController();
		}

		function prepView()
		{
			wp_register_script( TutoMVC::NAME, NULL );

			wp_register_script( "tutomvc-backbone", $this->getFacade()->getURL( "dist/js/script.min.js" ), NULL, TutoMVC::VERSION, TRUE );

			if ( SystemFacade::DEVELOPMENT_MODE )
			{
				wp_register_style( SystemFacade::STYLE_CSS, $this->getFacade()->getURL( "dist/css/style.css" ), NULL, TutoMVC::VERSION );
				wp_register_script( SystemFacade::SCRIPT_JS_REQUIRE, $this->getFacade()->getURL( "src/js/libs/requirejs/require.js" ), NULL, TutoMVC::VERSION, TRUE );
				wp_register_script( SystemFacade::SCRIPT_JS, $this->getFacade()->getURL( "src/js/Main.config.js" ), array(
					SystemFacade::SCRIPT_JS_REQUIRE,
					TutoMVC::NAME
				), TutoMVC::VERSION, TRUE );
			}
			else
			{
				wp_register_style( SystemFacade::STYLE_CSS, $this->getFacade()->getURL( "dist/css/style.min.css" ), NULL, TutoMVC::VERSION );
				wp_register_script( SystemFacade::SCRIPT_JS, $this->getFacade()->getURL( "deploy/Main.pkgd.js" ), NULL, TutoMVC::VERSION, TRUE );
			}

		}

		function prepModel()
		{
		}

		function prepController()
		{
			$this->getFacade()->controller->registerCommand( new PrintScriptsCommand() );
			$this->getFacade()->controller->registerCommand( new AdminMenuCommand() );
		}
	}