<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 10/05/15
	 * Time: 08:39
	 */

	namespace tutomvc\wp\setting;

	use tutomvc\FissileFormGroup;

	class Setting extends FissileFormGroup
	{
		protected $_menuSlug = "";
		protected $_autoload = TRUE;

		function __construct( $name, $menuSlug, $title = NULL, $description = NULL, $min = 1, $max = - 1 )
		{
			$this->setName( $name );
			parent::__construct( $name, $this, $description, $min, $max );
			$this->setMenuSlug( $menuSlug );
		}

		public function renderHeaderElement()
		{
			echo $this->getHeaderElement();
		}

		/**
		 * @return string
		 */
		public function getMenuSlug()
		{
			return $this->_menuSlug;
		}

		/**
		 * @param string $menuSlug
		 *
		 * @return $this
		 */
		public function setMenuSlug( $menuSlug )
		{
			$this->_menuSlug = $menuSlug;

			return $this;
		}

		/**
		 * @return boolean
		 */
		public function isAutoload()
		{
			return $this->_autoload;
		}

		/**
		 * @param boolean $autoload
		 *
		 * @return $this
		 */
		public function setAutoload( $autoload )
		{
			$this->_autoload = $autoload;

			return $this;
		}
	}