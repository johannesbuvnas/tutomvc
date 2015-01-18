/**
 * Created by johannesbuvnas on 17/01/15.
 */
define( [
    "backbone"
], function ( Backbone )
{
    var Input = Backbone.View.extend( {
        initialize: function ()
        {
            console.log( "FormInput::initialize" );
        }
    }, {
        autoInstance: function ( $el )
        {
            // Selectpicker
            $el.find( "select.selectpicker" ).each( function ()
            {
                Backbone.$( this ).selectpicker();
            } );
        }
    } );

    return Input;
} );