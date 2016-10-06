<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 09/01/16
	 * Time: 11:50
	 */

	namespace tutomvc\wp\form\inputs;

	use tutomvc\core\form\inputs\SelectFormInput;
	use tutomvc\wp\system\SystemApp;

	class WPAttachmentFormInput extends SelectFormInput
	{
		const TYPE_WPMEDIA_ALL   = "";
		const TYPE_WPMEDIA_IMAGE = "image";

		function __construct( $name, $title, $description, $max = - 1, $type = self::TYPE_WPMEDIA_ALL )
		{
			parent::__construct( $name, $title, $description );
			$this->setMax( $max );
			$this->setType( $type );
		}

		/* SET AND GET */
		public function getHeaderElement()
		{
			$el = parent::getHeaderElement();
			$el .= '<span class="help-block">' . $this->getDescription() . '</span>';

			return $el;
		}

		public function getFooterElement()
		{
			return "";
		}

		public function getFormElement()
		{
			return SystemApp::getInstance()->render( "src/templates/wp/forminput", "wpattachment", array(
				"formInput" => $this
			), TRUE );
		}

		public function setMax( $max )
		{
			if ( $max <= 1 && $max >= 0 ) $this->setSingle( TRUE );
			else $this->setSingle( FALSE );

			return parent::setMax( $max );
		}
	}