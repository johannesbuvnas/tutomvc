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
                // Controller
                Backbone.$( window ).on( "load", function ()
                {
                    ReproducibleFormGroup.autoInstance( _this.$el );
                    //FormInput.autoInstance( _this.$el );
                } );
            },
            events: {
                "added.reproducible-form-group-item": "onRenderedForm"
            },
            onRenderedForm: function ( e )
            {
                console.log( "Main::onRenderedForm" );

                console.log( e.target );

                FormInput.autoInstance( Backbone.$( e.target ) );
            }
        } );

        return new Main;
    }
);