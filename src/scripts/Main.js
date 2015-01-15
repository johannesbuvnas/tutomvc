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
            }
        } );

        return new Main;
    }
);