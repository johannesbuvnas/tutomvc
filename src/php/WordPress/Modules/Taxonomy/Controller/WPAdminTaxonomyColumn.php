<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/08/15
	 * Time: 16:18
	 */

	namespace TutoMVC\WordPress\Modules\Taxonomy\Controller;


	use TutoMVC\Model\Simple\NameObject;

	class WPAdminTaxonomyColumn extends NameObject
	{
		protected $_title;

		function __construct( $name, $title )
		{
			parent::__construct( $name );
			$this->setTitle( $title );
		}

		public function render( $taxonomy, $termID )
		{
		}

		/**
		 * @return mixed
		 */
		public function getTitle()
		{
			return $this->_title;
		}

		/**
		 * @param mixed $title
		 */
		public function setTitle( $title )
		{
			$this->_title = $title;
		}
	}
