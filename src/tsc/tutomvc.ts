import "../scss/tutomvc.scss";
import "script-loader!bootstrap/dist/js/bootstrap.js";
import "script-loader!select2/dist/js/select2.js";
import "script-loader!bootstrap-select/dist/js/bootstrap-select.js";
import "./plugins/jQueryWPAttachmentFormInput";
import "./plugins/jQueryWPEditorFormInput";
import "./plugins/jQueryWPMetaBox";

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

console.log("HELLO WORLD");
