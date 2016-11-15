<?php

	namespace tutomvc\wp\adminmenu\model;

	/**
	 * @see https://developer.wordpress.org/reference/functions/add_menu_page/
	 * @package tutomvc\wp\adminmenu\model
	 */
	class AdminMenuPage
	{
		protected $_pageTitle;
		protected $_menuTitle;
		protected $_capability;
		protected $_menuSlug;
		protected $_parentSlug;
		protected $_iconURL;
		protected $_position;

		function __construct( $pageTitle, $menuTitle, $capability, $menuSlug, $parentSlug = NULL, $iconURL = NULL, $position = NULL )
		{
			$this->setPageTitle( $pageTitle );
			$this->setMenuTitle( $menuTitle );
			$this->setCapability( $capability );
			$this->setMenuSlug( $menuSlug );
			$this->setParentSlug( $parentSlug );
			$this->setIconURL( $iconURL );
			$this->setPosition( $position );
		}

		function render()
		{
			$output = '
			<div class="wrap">
				<h1>' . $this->getPageTitle() . '</h1>
			</div>
			';

			echo $output;
		}

		function isSubmenuPage()
		{
			return !empty($this->_parentSlug);
		}

		/* SET AND GET */
		/**
		 * @return mixed
		 */
		public function getPageTitle()
		{
			return $this->_pageTitle;
		}

		/**
		 * @param mixed $pageTitle
		 */
		public function setPageTitle( $pageTitle )
		{
			$this->_pageTitle = $pageTitle;
		}

		/**
		 * @return mixed
		 */
		public function getMenuTitle()
		{
			return $this->_menuTitle;
		}

		/**
		 * @param mixed $menuTitle
		 */
		public function setMenuTitle( $menuTitle )
		{
			$this->_menuTitle = $menuTitle;
		}

		/**
		 * @return mixed
		 */
		public function getCapability()
		{
			return $this->_capability;
		}

		/**
		 * @param mixed $capability
		 */
		public function setCapability( $capability )
		{
			$this->_capability = $capability;
		}

		/**
		 * @return mixed
		 */
		public function getMenuSlug()
		{
			return $this->_menuSlug;
		}

		/**
		 * @param mixed $menuSlug
		 */
		public function setMenuSlug( $menuSlug )
		{
			$this->_menuSlug = $menuSlug;
		}

		/**
		 * @return mixed
		 */
		public function getIconURL()
		{
			return $this->_iconURL;
		}

		/**
		 * @param mixed $iconURL
		 */
		public function setIconURL( $iconURL )
		{
			$this->_iconURL = $iconURL;
		}

		/**
		 * @return mixed
		 */
		public function getPosition()
		{
			return $this->_position;
		}

		/**
		 * @param mixed $position
		 */
		public function setPosition( $position )
		{
			$this->_position = $position;
		}

		/**
		 * @return mixed
		 */
		public function getParentSlug()
		{
			return $this->_parentSlug;
		}

		/**
		 * @param mixed $parentSlug
		 */
		public function setParentSlug( $parentSlug )
		{
			$this->_parentSlug = $parentSlug;
		}
	}