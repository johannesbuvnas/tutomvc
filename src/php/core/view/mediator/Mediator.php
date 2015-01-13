<?php
	namespace tutomvc;

	class Mediator extends CoreClass implements IMediator
	{
		/* VARS */
		public    $hookRender;
		protected $_viewComponent;
		protected $_dataProvider = array();
		protected $_content      = "";

		function __construct( $viewComponent )
		{
			$this->setViewComponent( $viewComponent );
		}

		/* ACTIONS */
		/**
		 * @param array $dataProvider
		 *
		 * @return $this
		 * @throws \ErrorException
		 */
		public function render( $dataProvider = NULL )
		{
			if ( is_array( $dataProvider ) ) $this->_dataProvider = $dataProvider;
			if ( is_array( $this->_dataProvider ) ) extract( $this->_dataProvider, EXTR_SKIP );

			if ( strlen( $this->_content ) )
			{
				echo $this->_content;

				return $this;
			}

			$file = $this->getViewComponentFilePath();
			if ( is_file( $file ) )
			{
				include $file;
			}
			else
			{
				throw new \ErrorException( "MEDIATOR: " . " View component not found - " . $file, 0, E_ERROR );
			}

			return $this;
		}

		public function flush()
		{
			unset($this->_dataProvider);
			$this->_dataProvider = array();
		}

		/* METHODS */
		/**
		 *    Called when registered, before render is called.
		 */
		public function onRegister()
		{
		}

		/* SET AND GET */
		/**
		 *    Parse a variable to template.
		 */
		public function parse( $variableName, $value = NULL )
		{
			if ( is_array( $variableName ) )
			{
				foreach ( $variableName as $key => $value )
				{
					$this->parse( $key, $value );
				}
			}
			else
			{
				$this->_dataProvider[ $variableName ] = $value;
			}

			return $this;
		}

		public function retrieve( $variableName )
		{
			return array_key_exists( $variableName, $this->_dataProvider ) ? $this->_dataProvider[ $variableName ] : NULL;
		}

		public function setViewComponent( $viewComponent )
		{
			$this->_viewComponent = $viewComponent;
		}

		public function getViewComponent()
		{
			return $this->_viewComponent;
		}

		public function getViewComponentFilePath()
		{
			$filePath = $this->getFacade()->getTemplateFileReference( $this->getViewComponent() );
			$pathinfo = pathinfo( $filePath );
			if ( !array_key_exists( "extension", $pathinfo ) )
			{
				$filePath .= ".php";
			}

			return $filePath;
		}

		final public function getName()
		{
			return $this->_viewComponent;
		}

		/**
		 * @return string Content.
		 */
		public function getContent()
		{
			ob_start();
			$this->render();

			return ob_get_clean();
		}
	}

	interface IMediator
	{
		/* METHODS */
		public function parse( $variableName, $value );
	}