define(
    [
        "backbone",
        "bootstrap",
        "view/form/ReproducibleFormGroup"
    ],
    function ( Backbone, bootstrap, ReproducibleFormGroup )
    {
        var Main = Backbone.View.extend( {
            el: "body",
            initialize: function ()
            {
                console.log( "Hello new Tuto MVC" );
                //View
                ReproducibleFormGroup.autoInstance( this.$el );
            }
        } );

        return new Main;
    }
);