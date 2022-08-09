<?php

	namespace TutoMVC\WordPress\Core\View;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\TutoMVC;

	class View
	{
		/* PROTECTED VARS */
		protected $_facadeKey;

		/* STATIC VARS */
		protected static $_instanceMap = array();

		public function __construct( $key )
		{
			if ( array_key_exists( $key, $this::$_instanceMap ) ) die( "ERROR! A View with that particular namespace already exists." );

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

			$viewComponentFilePath = $this->getViewComponentRealpath( $viewComponent, $name );

			if ( $viewComponentFilePath )
			{
				include $viewComponentFilePath;
			}
			else
			{
				$requested = $viewComponent;
				if ( !empty( $name ) ) $requested = $viewComponent . "-" . $name;
				throw new \ErrorException( "View component not found - " . $requested, 0, E_ERROR );
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
			// Maybe the absolute file position is passed?
			$absoluteFilePathPosition = strpos( $relativeFilePath, TutoMVC::getDocumentRoot() );

			if ( $absoluteFilePathPosition === FALSE )
			{
				$name = (string)$name;
				if ( '' !== $name ) $relativeFilePath = "{$relativeFilePath}-{$name}";

				$filePath = Facade::getInstance( $this->_facadeKey )->getRoot( $relativeFilePath );
			}
			else
			{
				$filePath = $relativeFilePath;
			}

			$pathinfo = pathinfo( $filePath );
			if ( !array_key_exists( "extension", $pathinfo ) )
			{
				$filePath .= ".php";
			}

			return is_file( $filePath ) ? $filePath : NULL;
		}
	}
