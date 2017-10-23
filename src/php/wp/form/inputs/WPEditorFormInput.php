<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 07/12/15
	 * Time: 09:18
	 */
	namespace tutomvc\wp\form\inputs;

	use tutomvc\core\form\inputs\TextAreaFormInput;

	class WPEditorFormInput extends TextAreaFormInput
	{
		protected $_args;

		function __construct( $name, $title, $description = NULL, $args = array() )
		{
			parent::__construct( $name, $title, $description, FALSE );
			$this->setArgs( $args );
		}

		function formatFormElementOutput()
		{
			$this->_args[ 'textarea_name' ] = $this->getElementName();
			$this->_args                    = wp_parse_args( $this->_args, array(
				'wpautop'             => TRUE,
				'media_buttons'       => TRUE,
				'default_editor'      => '',
				'drag_drop_upload'    => FALSE,
				'textarea_rows'       => 20,
				'tabindex'            => '',
				'tabfocus_elements'   => ':prev,:next',
				'editor_css'          => '',
				'editor_class'        => '',
				'teeny'               => FALSE,
				'dfw'                 => FALSE,
				'_content_editor_dfw' => FALSE,
				'tinymce'             => TRUE,
				'quicktags'           => TRUE
			) );

			ob_start();
			wp_editor( $this->getValue(), $this->getID(), $this->getArgs() );
			return ob_get_clean();
		}

		/**
		 * @return array
		 */
		public function getArgs()
		{
			return $this->_args;
		}

		/**
		 * @param array $args
		 */
		public function setArgs( $args )
		{
			$this->_args = $args;
		}
	}