import "../less/tutomvc.less";
import "script!select2/dist/js/select2.js";
import "script!bootstrap/dist/js/bootstrap.js";
import "script!bootstrap-select/dist/js/bootstrap-select.js";
import "./plugins/jQueryWPAttachmentFormInput";


(function( $ )
{
	$( document ).ready( function()
	{
		// Select2FormInput
		$( ".form-input-element.select2" ).each( function()
		{
			$( this ).select2( $( this ).data() );
		} );
	} );
})( jQuery );