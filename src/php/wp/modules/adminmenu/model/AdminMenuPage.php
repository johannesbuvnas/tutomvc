<?php

	namespace tutomvc\wp\adminmenu\model;

	/**
	 * @see https://developer.wordpress.org/reference/functions/add_menu_page/
	 * @package tutomvc\wp\adminmenu\model
	 */
	class AdminMenuPage
	{
		const PARENT_SLUG_DASHBOARD        = "index.php";
		const PARENT_SLUG_POSTS            = "edit.php";
		const PARENT_SLUG_MEDIA            = "upload.php";
		const PARENT_SLUG_PAGES            = "edit.php?post_type=page";
		const PARENT_SLUG_COMMENTS         = "edit-comments.php";
		const PARENT_SLUG_APPEARANCE       = "themes.php";
		const PARENT_SLUG_PLUGINS          = "plugins.php";
		const PARENT_SLUG_USERS            = "users.php";
		const PARENT_SLUG_TOOLS            = "tools.php";
		const PARENT_SLUG_SETTINGS         = "options-general.php";
		const PARENT_SLUG_NETWORK_SETTINGS = "settings.php";

		protected $_pageTitle;
		protected $_menuTitle;
		protected $_capability;
		protected $_menuSlug;
		protected $_parentSlug;
		protected $_iconURL;
		protected $_position;

		function __construct( $pageTitle, $menuTitle, $capability, $menuSlug, $parentSlug = NULL )
		{
			$this->setPageTitle( $pageTitle );
			$this->setMenuTitle( $menuTitle );
			$this->setCapability( $capability );
			$this->setMenuSlug( $menuSlug );
			$this->setParentSlug( $parentSlug );
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

		/**
		 * Triggered when load.
		 */
		function load()
		{

		}

		function isSubmenuPage()
		{
			return !empty($this->_parentSlug);
		}

		/* SET AND GET */
		/**
		 * @return string
		 */
		public function getPageTitle()
		{
			return $this->_pageTitle;
		}

		/**
		 * @param string $pageTitle
		 */
		public function setPageTitle( $pageTitle )
		{
			$this->_pageTitle = $pageTitle;
		}

		/**
		 * @return string
		 */
		public function getMenuTitle()
		{
			return $this->_menuTitle;
		}

		/**
		 * @param string $menuTitle
		 */
		public function setMenuTitle( $menuTitle )
		{
			$this->_menuTitle = $menuTitle;
		}

		/**
		 * @return string
		 */
		public function getCapability()
		{
			return $this->_capability;
		}

		/**
		 * @param string $capability
		 */
		public function setCapability( $capability )
		{
			$this->_capability = $capability;
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
		 */
		public function setMenuSlug( $menuSlug )
		{
			$this->_menuSlug = $menuSlug;
		}

		/**
		 * @return string
		 */
		public function getIconURL()
		{
			return $this->_iconURL;
		}

		/**
		 * Only for root menu pages without parents.
		 *
		 * @param string $iconURL
		 */
		public function setIconURL( $iconURL )
		{
			$this->_iconURL = $iconURL;
		}

		/**
		 * @return string
		 */
		public function getPosition()
		{
			return $this->_position;
		}

		/**
		 * * Only for root menu pages without parents.
		 *
		 * @param string $position
		 */
		public function setPosition( $position )
		{
			$this->_position = $position;
		}

		/**
		 * @return string
		 */
		public function getParentSlug()
		{
			return $this->_parentSlug;
		}

		/**
		 * @param string $parentSlug
		 */
		public function setParentSlug( $parentSlug )
		{
			$this->_parentSlug = $parentSlug;
		}
	}