<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 12/01/16
	 * Time: 17:20
	 */

	namespace TutoMVC\WordPress\Form\Input;

	use TutoMVC\Form\Input\FormInput;

	class WPColorPickerFormInput extends FormInput
	{
		function __construct( $name, $title, $description = "", $readonly = FALSE )
		{
			parent::__construct( $name, $title, $description, self::TYPE_TEXT, $readonly );

			if ( is_admin() )
			{
				if ( !wp_script_is( "iris", "enqueued" ) )
				{
					wp_enqueue_script( "iris" );
				}
			}
		}

		function getFormElementAttributes()
		{
			$attr            = parent::getFormElementAttributes();
			$attr[ 'class' ] = $attr[ 'class' ] . " form-input-color-picker";

			return $attr;
		}

		function formatFooterOutput()
		{
			$el = parent::formatFooterOutput();
			$el .= '
			<script type="text/javascript">
			jQuery(document).ready(function()
			{
				jQuery("input.form-input-color-picker").iris();
			});
			</script>
			';

			return $el;
		}
	}
