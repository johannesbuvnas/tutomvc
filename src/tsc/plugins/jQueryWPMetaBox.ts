import {MetaBox} from "../wp/form/groups/MetaBox";

jQuery( function() {
	jQuery( ".tutomvc-settings,.tutomvc-metabox,.tutomvc-user_metabox" ).each( function( i, el ) {
		new MetaBox( jQuery( el ) );
	} );
} );