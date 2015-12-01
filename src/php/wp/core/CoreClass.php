<?php
	namespace tutomvc;

	class CoreClass
	{
		/* PROTECTED */
		protected $_facadeKey;

		public function initializeFacadeKey( $key )
		{
			$this->_facadeKey = $key;
		}

		/**
		 * @return Facade
		 */
		public function getFacade()
		{
			if ( is_null( $this->_facadeKey ) ) die("No facade key has yet been initialized.");

			return Facade::getInstance( $this->getFacadeKey() );
		}

		/**
		 *    Called by the framework when registered and in the facade scope.
		 */
		public function onRegister()
		{

		}

		/**
		 * @return mixed
		 */
		public function getFacadeKey()
		{
			return $this->_facadeKey;
		}
	}