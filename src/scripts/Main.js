define(
    [
        "backbone",
        "bootstrap",
        "view/form/ReproducibleFormGroup",
        "view/form/inputs/FormInput"
    ],
    function ( Backbone, bootstrap, ReproducibleFormGroup, FormInput )
    {
        var Main = Backbone.View.extend( {
            el: "body",
            initialize: function ()
            {
                console.log( "Hello new Tuto MVC" );
                //View
                var _this = this;
                Backbone.$( document ).ready( function ()
                {
                    FormInput.autoInstance( _this.$el );
                    ReproducibleFormGroup.autoInstance( _this.$el );
                } );
            }
        } );

        return new Main;
    }
);