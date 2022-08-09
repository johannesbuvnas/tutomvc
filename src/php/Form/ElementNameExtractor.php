<?php

	namespace TutoMVC\Form;

	use TutoMVC\Form\FormElement;

	class ElementNameExtractor
	{
		protected $_elementName;
		protected $_ancestor;
		protected $_index;
		protected $_children;

		function __construct( $elementName )
		{
			$this->_elementName = FormElement::sanitizeName( $elementName );
			$matches            = FormElement::matchElementName( $elementName );
			if ( count( $matches ) == 4 )
			{
				$this->_ancestor = $matches[ 1 ];
				$this->_index    = intval( $matches[ 2 ] );
				$rest            = $matches[ 3 ];
				$this->_children = FormElement::extractGroupNames( $rest );
			}
		}

		/**
		 * @return mixed
		 */
		public function getElementName()
		{
			return $this->_elementName;
		}

		/**
		 * @return mixed
		 */
		public function getAncestor()
		{
			return $this->_ancestor;
		}

		/**
		 * @return mixed
		 */
		public function getIndex()
		{
			return $this->_index;
		}

		/**
		 * @return mixed
		 */
		public function getChildren()
		{
			return $this->_children;
		}
	}
