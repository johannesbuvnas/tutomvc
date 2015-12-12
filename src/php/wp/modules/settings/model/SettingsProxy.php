<?php
	namespace tutomvc\wp\settings;

	use tutomvc\wp\Proxy;

	class SettingsProxy extends Proxy
	{
		const NAME = __CLASS__;

		protected $_isRegistered = FALSE;

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		/**
		 * @param Settings $item
		 * @param null $key
		 * @param bool $override
		 *
		 * @return mixed
		 * @throws \ErrorException
		 */
		public function add( $item, $key = NULL, $override = FALSE )
		{
			if ( $this->_isRegistered )
			{
				throw new \ErrorException( "Settings already registered! Settings are registered on action hook 'admin_init'" );
			}

			return parent::add( $item, $item->getName(), $override );
		}

		public function registerAll()
		{
			if ( !$this->_isRegistered )
			{
				$this->_isRegistered = TRUE;

				/** @var Settings $setting */
				foreach ( $this->getMap() as $setting )
				{
					$setting->register();
				}
			}

			return $this;
		}

		/* SET AND GET */
	}