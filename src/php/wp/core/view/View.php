<?php
	namespace tutomvc;

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
		public function render( $viewComponent, $dataProvider = array(), $returnOutput = FALSE )
		{
			if ( $returnOutput ) ob_start();

			if ( is_array( $dataProvider ) )
			{
				extract( $dataProvider, EXTR_SKIP );
			}

			$filePath = Facade::getInstance( $this->_facadeKey )->getRoot( $viewComponent );
			$pathinfo = pathinfo( $filePath );
			if ( !array_key_exists( "extension", $pathinfo ) )
			{
				$filePath .= ".php";
			}

			if ( is_file( $filePath ) )
			{
				include $filePath;
			}
			else
			{
				throw new \ErrorException( "\\tutomvc\\View: " . " View component not found - " . $viewComponent, 0, E_ERROR );
			}

			if ( $returnOutput ) return ob_get_clean();
		}
	}