import "../less/tutomvc.less";
import "script!select2/dist/js/select2.js";
import "./plugins/jQueryWPAttachmentFormInput";


(function( $ )
{
	$( document ).ready( function()
	{
		// Select2FormInput
		$( ".tutomvc-form-element .select2" ).each( function()
		{
			$( this ).select2( $( this ).data() );
		} );
	} );
})( jQuery );