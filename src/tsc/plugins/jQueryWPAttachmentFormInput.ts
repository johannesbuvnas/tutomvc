import {WPAttachmentFormInput} from "../wp/form/inputs/WPAttachmentFormInput";
jQuery( document ).ready( function ()
{
	jQuery( ".wpattachmentforminput" ).each( function ()
	{
		new WPAttachmentFormInput( jQuery( this ), jQuery( this ).data() );
	} );
//	jQuery( ".wpattachmentforminput .btn-remove" ).click( function ( e )
//	{
//		e.preventDefault();
//		var $el = $( this );
//		$el.closest( ".list-group-item" ).remove();
//
//		new WPAttachmentFormInput( $( $( this ).data( "target" ) ), "render" );
//	} );
//	jQuery( ".wpattachmentforminput .btn-add" ).click( function ( e )
//	{
//		new WPAttachmentFormInput( $( $( this ).data( "target" ) ), "open" );
//	} );
} );