/**
 * Created by johannesbuvnas on 17/01/15.
 */
define( [
    "backbone",
    "view/form/inputs/WPEditor"
], function ( Backbone, WPEditor )
{
    var FormInput = Backbone.View.extend( {
        initialize: function ()
        {
            console.log( "FormInput::initialize" );
        }
    }, {
        autoInstance: function ( $el )
        {
            console.log( "FormInput::autoInstace" );
            // Selectpicker
            $el.find( "select.selectpicker" ).each( function ()
            {
                Backbone.$( this ).selectpicker();
            } );
            // WP Editor
            WPEditor.autoInstance( $el );
        }
    } );

    return FormInput;
} );