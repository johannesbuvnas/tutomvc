<?php
	namespace tutomvc\wp\core\view;

	use tutomvc\wp\core\facade\Facade;

	class View
	{
		/* PROTECTED VARS */
		protected $_facadeKey;

		/* STATIC VARS */
		protected static $_instanceMap = array();

		public function __construct( $key )
		{
			if ( array_key_exists( $key, $this::$_instanceMap ) ) die("ERROR! A View with that particular namespace already exists.");

			$this::$_instanceMap[ $key ] = $this;

			$this->_facadeKey = $key;
		}

		/* PUBLIC STATIC METHODS */
		public static function getInstance( $key )
		{
			if ( !array_key_exists( $key, self::$_instanceMap ) ) self::$_instanceMap[ $key ] = new View( $key );

			return self::$_instanceMap[ $key ];
		}

		/* PUBLIC METHODS */
		/**
		 * @param string $viewComponent
		 * @param null|string $name
		 * @param array $dataProvider
		 * @param bool $returnOutput
		 *
		 * @return string|bool
		 * @throws \ErrorException
		 */
		public function render( $viewComponent, $name = NULL, $dataProvider = array(), $returnOutput = FALSE )
		{
			if ( $returnOutput ) ob_start();

			if ( is_array( $dataProvider ) )
			{
				extract( $dataProvider, EXTR_SKIP );
			}

			$viewComponent = $this->getViewComponentRealpath( $viewComponent, $name );

			if ( $viewComponent )
			{
				include $viewComponent;
			}
			else
			{
				throw new \ErrorException( "\\tutomvc\\View: " . " View component not found - " . $viewComponent, 0, E_ERROR );
			}

			if ( $returnOutput ) return ob_get_clean();

			return TRUE;
		}

		/**
		 * @param $relativeFilePath
		 * @param null|string $name
		 *
		 * @return null|string
		 */
		public function getViewComponentRealpath( $relativeFilePath, $name = NULL )
		{
			$name = (string)$name;
			if ( '' !== $name ) $relativeFilePath = "{$relativeFilePath}-{$name}";

			$filePath = Facade::getInstance( $this->_facadeKey )->getRoot( $relativeFilePath );
			$pathinfo = pathinfo( $filePath );
			if ( !array_key_exists( "extension", $pathinfo ) )
			{
				$filePath .= ".php";
			}

			return is_file( $filePath ) ? $filePath : NULL;
		}
	}