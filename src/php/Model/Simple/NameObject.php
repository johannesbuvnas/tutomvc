<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:57
	 */

	namespace TutoMVC\Model\Simple;

	class NameObject
	{
		protected $_name;

		function __construct( $name )
		{
			$this->setName( $name );
		}

		/**
		 * @return string
		 */
		public function getName()
		{
			return $this->_name;
		}

		/**
		 * @param string $name
		 */
		public function setName( $name )
		{
			$this->_name = $name;
		}
	}
