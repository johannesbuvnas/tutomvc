jQuery( function() {
	jQuery( "body" ).on( "click", ".wpeditor-placeholder-overlay", ( e ) => {
		let $currentTarget = e.currentTarget;
		let $el            = jQuery( $currentTarget.closest( ".wpeditor-placeholder" ) );
		let content        = $el.find( "textarea" ).val();
		console.log( content );
		jQuery.ajax( $el.data( "ajax-url" ), {
			data:   {
				content:content,
				"id":   $el.data( "id" ),
			},
			success:( result ) => {
				console.log( result );
				$el.html( result.html );
				tinymce.init( result.settings );
				quicktags( {id:$el.data( "id" )} );
				window.wpActiveEditor = $el.data( "id" );
			},
			error:  error => console.error( error )
		} );
	} );
} );