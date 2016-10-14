/**
 * Created by johannesbuvnas on 18/12/15.
 */
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