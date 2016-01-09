<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 09/01/16
	 * Time: 11:50
	 */

	namespace tutomvc\wp;

	use tutomvc\SelectFormInput;

	class WPAttachmentFormInput extends SelectFormInput
	{
		function __construct( $name, $title, $description, $min = 0, $max = - 1 )
		{
			parent::__construct( $name, $title, $description );
			$this->setMin( $min );
			$this->setMax( $max );
		}

		/* SET AND GET */
		public function getFormElement()
		{
			return SystemApp::getInstance()->render( "src/templates/forminput", "wpattachment", array(
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