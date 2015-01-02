define( [
        "backbone"
    ],
    function ( Backbone )
    {
        "use strict";

        var Model = Backbone.Model.extend( {
            constructor: function ( options )
            {
                var defaults = {
                    name: "",
                    value: "",
                    elementID: ""
                };
                this.defaults = _.extend( defaults, this.defaults );

                Backbone.Model.call( this, options );
            }
        } );

        var Collection = Backbone.Collection.extend( {
            model: Model,
            initialize: function ()
            {

            }
        } );

        var Input = Backbone.View.extend( {
                tagName: "input",
                attributes: {
                    type: "hidden"
                },
                constructor: function ( options )
                {
                    Backbone.View.call( this, options );

                    // Model
                    if ( !this.model ) this.model = new Input.Model();

                    // View
                    this.$el.val( this.model.get( "value" ) );
                    this.render();

                    // Controller
                    this.listenTo( this.model, 'change', this.render );
                    this.listenTo( this.model, 'destroy', this.remove );
                },
                render: function ()
                {
                    this.$el.attr( "name", this.model.get( "name" ) );
                    this.$el.attr( "id", this.model.get( "elementID" ) );

                    return this;
                }
            },
            {
                Model: Model,
                Collection: Collection
            } );
        return Input;
    } );