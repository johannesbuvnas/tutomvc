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
        this.wpMedia = wp.media( {
            title: this.options.title,
            multiple: this.options.max < 0 || this.options.max > 1 ? true : false,
            library: this.options.type ? { type: this.options.type } : undefined,
            button: { text: this.options.buttonText },
            frame: 'select'
        } );
        this.template = _.template( this.$el.find( "textarea.underscore-template" ).val() );
        this.wpMedia.on( "select", function ()
        {
            _this.onWPMediaSelect();
        } );
        this.render();
    };

    PluginConstructor.prototype.render = function ()
    {
        if ( this.count() >= this.options.max && this.options.max >= 0 ) this.$el.find( ".btn-add" ).prop( "disabled", "disabled" );
        else this.$el.find( ".btn-add" ).prop( "disabled", null );

    };

    PluginConstructor.prototype.open = function ()
    {
        this.wpMedia.open();
    };

    PluginConstructor.prototype.count = function ()
    {
        return this.$el.find( ".list-group-item" ).length;
    };

    PluginConstructor.prototype.add = function ( attachmentModel )
    {
        var _this = this;
        var $el = $( this.template( attachmentModel.toJSON() ) );
        $el.find( ".btn-remove" ).click( function ( e )
        {
            e.preventDefault();
            var $el = $( this );
            $el.closest( ".list-group-item" ).remove();

            _this.render();
        } );
        this.$el.find( ".list-group" ).append( $el );
    };

    PluginConstructor.prototype.onWPMediaSelect = function ()
    {
        var selection = this.wpMedia.state().get( 'selection' );
        var _this = this;
        selection.each( function ( attachment )
        {
            if ( _this.options.max < 0 || _this.count() < _this.options.max )
            {
                _this.add( attachment );
            }
        } );

        this.render();
    };

    PluginConstructor.VERSION = '1.0.0';

    PluginConstructor.DEFAULTS = {
        max: -1,
        title: "",
        buttonText: "",
        type: null,
        frame: "select"
    };

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
        $( ".wpattachmentforminput" ).each( function ()
        {
            Plugin.call( $( this ), $( this ).data() );
        } );
        $( ".wpattachmentforminput .btn-remove" ).click( function ( e )
        {
            e.preventDefault();
            var $el = $( this );
            $el.closest( ".list-group-item" ).remove();

            Plugin.call( $( $( this ).data( "target" ) ), "render" );
        } );
        $( ".wpattachmentforminput .btn-add" ).click( function ( e )
        {
            Plugin.call( $( $( this ).data( "target" ) ), "open" );
        } );
    } );
}( jQuery );