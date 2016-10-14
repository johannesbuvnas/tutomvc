// CSS / LESS
import "../less/tutomvc.less";
// Dependencies
import "script!select2/dist/js/select2.js";

(function ( $ )
{
	$( document ).ready( function ()
	{
		// Select2FormInput
		$( ".tutomvc-form-element .select2" ).each( function ()
		{
			$( this ).select2( $( this ).data() );
		} );
	} );
})( jQuery );