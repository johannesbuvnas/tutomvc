<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 10/05/15
	 * Time: 08:39
	 */

	namespace tutomvc\wp\setting;

	use tutomvc\FissileFormGroup;
	use tutomvc\FormElement;
	use tutomvc\TutoMVC;

	class Setting extends FissileFormGroup
	{
		protected $_pageName     = "";
		protected $_sectionName  = "";
		protected $_autoload     = TRUE;
		protected $_isRegistered = FALSE;

		function __construct( $name, $pageName, $title = NULL, $description = NULL, $min = 1, $max = - 1 )
		{
			parent::__construct( $name, $title, $description, $min, $max );
			$this->setPageName( $pageName );
			$this->_sectionName = FormElement::sanitizeID( $name . "_section" );
		}

		public function register()
		{
			if ( !$this->_isRegistered )
			{
				add_settings_section( $this->getSectionName(), "", array(
					$this,
					"render"
				), $this->getPageName() );

				add_settings_field( $this->getName(), "", array(
					$this,
					"renderField"
				), $this->getPageName(), $this->getSectionName() );

				register_setting( $this->getPageName(), $this->getName(), array($this, "sanitize") );

				$this->_isRegistered = TRUE;
			}

			return $this;
		}

		/**
		 *
		 * Core function. Runs before data is saved to the database.
		 *
		 * @param $value
		 * @param $option
		 *
		 * @see http://wpseek.com/function/sanitize_option/
		 *
		 * @return array|mixed
		 */
		public function sanitize( $value )
		{
			$data = array(
				$this->getName() => $value
			);
			$this->parse( $data );

			$errors = $this->getErrors();

			if ( count( $errors ) )
			{
				if ( function_exists( 'add_settings_error' ) )
				{
					add_settings_error( $this->getName(), FormElement::sanitizeID( $this->getName() . "-error-msg" ), sprintf( __( "<a href='#%1s'>@%2s</a>: Settings saved but it contain errors.", TutoMVC::NAME ), $this->getID(), $this->getLabel() ), "error" );
				}
			}

			return $this->getValue();
		}

		public function render( $args )
		{
			$this->setValue( get_option( $this->getName(), NULL ) );
			$this->validate();

			echo $this->getElement();
		}

		public function renderField()
		{
		}

		/* SET AND GET */

		/**
		 * @return string
		 */
		public function getPageName()
		{
			return $this->_pageName;
		}

		/**
		 * @param string $menuSlug
		 *
		 * @return $this
		 */
		public function setPageName( $menuSlug )
		{
			$this->_pageName = $menuSlug;

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

		/**
		 * @return string
		 */
		public function getSectionName()
		{
			return $this->_sectionName;
		}
	}