/**
 * Created by johannesbuvnas on 17/01/15.
 */
define( [
    "backbone",
    "view/form/inputs/WPEditor"
], function ( Backbone, WPEditor )
{
    var FormFormInput = Backbone.View.extend( {
        initialize: function ()
        {
            console.log( "FormFormInput::initialize" );
        }
    }, {
        autoInstance: function ( $el )
        {
            // Selectpicker
            $el.find( "select.selectpicker" ).each( function ()
            {
                Backbone.$( this ).selectpicker();
            } );
            // WP Editor
            WPEditor.autoInstance( $el );
        }
    } );

    return FormFormInput;
} );