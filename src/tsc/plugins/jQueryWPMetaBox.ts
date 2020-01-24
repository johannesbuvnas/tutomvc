import {MetaBox} from "../wp/form/groups/MetaBox";

jQuery( function() {
	jQuery( ".tutomvc-metabox" ).each( function( i, el ) {
		new MetaBox( jQuery( el ) );
	} );
} );