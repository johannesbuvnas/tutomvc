define(
    [
        "backbone",
        "bootstrap",
        "view/form/MetaBoxListGroup"
    ],
    function ( Backbone, bootstrap, MetaBoxListGroup )
    {
        var Main = Backbone.View.extend( {
            el: "body",
            initialize: function ()
            {
                console.log( "Hello new Tuto MVC" );
                //View
                MetaBoxListGroup.autoInstance( this.$el );
            }
        } );

        return new Main;
    }
);