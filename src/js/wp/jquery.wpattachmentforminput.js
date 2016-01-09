/**
 * Created by johannesbuvnas on 09/01/16.
 */
+function ( $ )
{
    'use strict';

    // PUBLIC CLASS DEFINITION
    // ==============================

    var NAME = "wpattachmentforminput";
    var DATA_KEY = "tutomvc.wpattachmentforminput";

    var PluginConstructor = function ( element, options )
    {
        var _this = this;
        this.$el = $( element );
        this.options = $.extend( {}, PluginConstructor.DEFAULTS, options );
        this.render();
    };

    PluginConstructor.prototype.render = function ()
    {
    };

    PluginConstructor.VERSION = '1.0.0';

    PluginConstructor.DEFAULTS = {};

    // PLUGIN DEFINITION
    // ========================
    function Plugin( option )
    {
        return this.each( function ()
        {
            var $this = $( this );
            var data = $this.data( DATA_KEY );
            var options = typeof option == 'object' && option;

            if ( !data ) $this.data( DATA_KEY, (data = new PluginConstructor( this, options )) );

            if ( typeof option == "string" ) data[ option ]();
        } )
    }

    var old = $.fn[ NAME ];

    $.fn[ NAME ] = Plugin;
    $.fn[ NAME ].Constructor = PluginConstructor;


    // NO CONFLICT
    // ==================

    $.fn[ NAME ].noConflict = function ()
    {
        $.fn[ NAME ] = old;
        return this;
    };


    // DATA-API
    // ===============
    $( document ).ready( function ()
    {
        //$( "select.stateselect" ).each( function ()
        //{
        //    Plugin.call( $( this ), $( this ).data() );
        //} );
    } );
}( jQuery );