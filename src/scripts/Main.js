define(
    [
        "backbone",
        "bootstrap"
    ],
    function ( Backbone, bootstrap )
    {
        var Main = Backbone.View.extend( {
            el: "body",
            initialize: function ()
            {
                console.log( "Hello new Tuto MVC" );
                //View
                this.$( "select.multiselect" ).each( function ()
                {
                    var $el = Backbone.$( this );
                } );
            }
        } );

        return new Main;
    }
);