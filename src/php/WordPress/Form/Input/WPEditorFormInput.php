<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 07/12/15
	 * Time: 09:18
	 */

	namespace TutoMVC\WordPress\Form\Input;

	use TutoMVC\Form\Input\TextAreaFormInput;
	use TutoMVC\WordPress\System\SystemApp;
	use function ob_get_clean;
	use function ob_start;
	use function wp_editor;

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

			// TODO: Ugly fix
			ob_start();
			wp_editor( $this->getValue(), "dummy", $this->getArgs() );
			$editor = ob_get_clean();

			return SystemApp::getInstance()->render( "src/templates/wp/forminput", "wpeditor", array(
				"formInput" => $this
			), TRUE );
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
