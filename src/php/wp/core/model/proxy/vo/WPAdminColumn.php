<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 04/12/15
	 * Time: 15:49
	 */

	namespace tutomvc\wp\core\model\vo;

	use tutomvc\core\model\NameObject;

	class WPAdminColumn extends NameObject
	{
		protected $_title;
		protected $_isSortable = FALSE;

		function __construct( $name, $title )
		{
			parent::__construct( $name );
			$this->setTitle( $title );
		}

		/**
		 * @param $postID
		 */
		public function render( $postID )
		{
		}

		/**
		 * @return string
		 */
		public function getTitle()
		{
			return $this->_title;
		}

		/**
		 * @param string $title
		 */
		public function setTitle( $title )
		{
			$this->_title = $title;
		}

		/**
		 * @return boolean
		 */
		public function isSortable()
		{
			return $this->_isSortable;
		}

		/**
		 * @param boolean $isSortable
		 */
		public function setIsSortable( $isSortable )
		{
			$this->_isSortable = $isSortable;
		}
	}