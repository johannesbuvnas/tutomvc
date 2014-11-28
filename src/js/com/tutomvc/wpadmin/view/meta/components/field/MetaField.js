define( [
        "base64",
        "backbone",
        "underscore",
        "text!com/tutomvc/wpadmin/view/meta/components/field/MetaField.tpl.html",
        "com/tutomvc/component/form/Input",
        "com/tutomvc/component/form/Selector",
        "com/tutomvc/component/form/MultiSelector",
        "com/tutomvc/wpadmin/view/meta/components/field/input/googlemaps/GoogleMapsSelector",
        "com/tutomvc/component/model/proxy/Proxy",
        "com/tutomvc/wpadmin/view/meta/components/field/input/text/TextareaWYSIWYGInput",
        "com/tutomvc/wpadmin/view/meta/components/field/input/attachment/AttachmentList",
        "com/tutomvc/component/form/TextArea",
        "com/tutomvc/wpadmin/view/meta/components/field/input/text/LinkInput"
    ],
    function ( Base64, Backbone, _, Template, Input, Selector, MultiSelector, GoogleMapsSelector, Proxy, TextareaWYSIWYGInput, AttachmentList, TextArea, LinkInput )
    {
        "use strict";

        var MetaField = Backbone.View.extend( {
                template: _.template( Template ),
                constructor: function ( metaBoxID, element )
                {
                    var attr = JSON.parse( decodeURIComponent( element.find( ".JSON" ).html() ) );
                    if ( typeof attr.value == 'string' || attr.value instanceof String ) attr.value = Base64.decode( attr.value );
                    attr.elementID = attr.metaFieldName + "_" + metaBoxID;
                    attr.metaBoxID = metaBoxID;

                    Backbone.View.call( this, {
                        el: element,
                        model: new MetaField.Model( attr )
                    } );
                },
                initialize: function ()
                {
                    // Clear the default PHP fallback solution and hand it over to the component
                    this.model.set( {
                        fallbackValues: this.$( ".Input textarea" ).map( function ()
                        {
                            return Backbone.$( this ).val();
                        } ).get()
                    } );
                    this.input = MetaField.getInputInstance( this.model, this.$( ".Input" ).find( "input" ) );
                    this.$( ".Input" ).html( "" );
                    this.$( ".Input" ).append( this.input.$el );

                    if ( this.model.get( "dividerAfter" ) ) this.$el.append( "<hr/>" );
                    // if(this.model.get("type") == "selector_single") this.input.addEventListener( "change", _.bind(this.change, this) );

                    this.listenTo( this.model, "change:elementID", this.render );
                    this.listenTo( this.model, "change:title", this.render );
                    this.listenTo( this.model, "change:description", this.render );
                    this.listenTo( this.model, "change:name", this.onKeyChange );
                    this.listenTo( this.model, "change:value", this.change );
                    this.render();
                },
                render: function ()
                {
                    this.$( ".MetaFieldHeader" ).remove();
                    this.$el.prepend( this.template( this.model.toJSON() ) );
                    return this;
                },
                show: function ()
                {
                    this.$el.removeClass( "HiddenElement" );
                },
                hide: function ()
                {
                    this.$el.addClass( "HiddenElement" );
                },
                change: function ()
                {
                    // if(this.model.get("type") == "selector_single") this.model.set({value:this.input.getValue()})

                    // var event = new Event( "change", { metaFieldName : this.model.get("metaFieldName"), value : this.model.get("value") } );
                    this.trigger( "change", this.model );
                },

                // EVENTS
                events: {
                    "click label": "onClick"
                },
                // TODO: Remove this later
                onKeyChange: function ()
                {
                    // if(this.model.get("type") == "selector_single") this.input.setName( this.model.get("name") );
                },
                metaBoxChange: function ( metaBoxName, metaFieldName, value )
                {
                    // console.log("Appearently", metaBoxName + "_" + metaFieldName, "has changed to", value);

                    for ( var key in this.model.get( "conditions" ) )
                    {
                        var condition = this.model.get( "conditions" )[ key ];

                        var test;
                        eval( "test = " + condition.jsValidation );
                        var tested = test( metaBoxName, metaFieldName, value );
                        if ( typeof tested !== "undefined" )
                        {
                            if ( tested )
                            {
                                if ( condition.onMatch ) this[ condition.onMatch ]();
                            }
                            else
                            {
                                if ( condition.onElse ) this[ condition.onElse ]();
                            }
                        }
                    }
                },
                onClick: function ( e )
                {
                    this.input.trigger( "focus" );
                }
            },
            {
                Model: Input.Model.extend( {
                    defaults: {
                        fallbackValues: [],
                        metaBoxID: "",
                        title: "",
                        description: "",
                        metaFieldName: "",
                        conditions: [],
                        type: "text",
                        dividerBefore: false,
                        dividerAfter: false
                    },
                    setMetaKey: function ( metaBoxName, metaBoxCardinalityID )
                    {
                        return this.set( {
                            name: metaBoxName + "_" + metaBoxCardinalityID + "_" + this.get( "metaFieldName" )
                        } );
                    }
                } ),
                getInputInstance: function ( model )
                {
                    var component;

                    switch ( model.get( "type" ) )
                    {
                        case "link":

                            component = new LinkInput( {
                                model: model
                            } );

                            break;
                        case "textarea_wysiwyg":

                            component = new TextareaWYSIWYGInput( {
                                model: model
                            } );

                            break;
                        case "attachment":

                            component = new AttachmentList( {
                                model: model
                            } );

                            break;
                        case "selector_single":

                            // console.log( model.toJSON() );
                            var options = new Input.Collection();
                            var optionsObject = model.get( "options" );
                            for ( var key in optionsObject )
                            {
                                options.add( {
                                    value: key,
                                    name: optionsObject[ key ]
                                } );
                                if ( key == model.get( "value" ) ) model.set( { label: optionsObject[ key ] } );
                            }
                            model.set( {
                                options: options
                            } );
                            component = new Selector( {
                                model: model
                            } );

                            break;
                        case "selector_multiple":

                            var options = new Input.Collection();
                            var optionsObject = model.get( "options" );
                            for ( var key in optionsObject )
                            {
                                options.add( {
                                    value: key,
                                    name: optionsObject[ key ]
                                } );
                            }

                            component = new MultiSelector( {
                                model: new MultiSelector.Model( _.extend( model.toJSON(), { options: options } ) )
                            } );

                            break;
                        case "selector_google_maps":

                            var options = new Input.Collection();
                            var markerModel;
                            switch ( typeof model.get( "value" ) )
                            {
                                case "string":
                                    if ( model.get( "value" ) && model.get( "value" ).length )
                                    {
                                        markerModel = new GoogleMapsSelector.MarkerModel( Backbone.$.parseJSON( model.get( "value" ) ) );
                                        options.add( {
                                            value: model.get( "value" ),
                                            name: markerModel.get( "title" ),
                                            selected: true
                                        } );
                                    }


                                    break;
                                default:
                                    if ( model.get( "value" ) && model.get( "value" ).length )
                                    {
                                        for ( var i = 0, place; place = model.get( "value" )[ i ]; i++ )
                                        {
                                            markerModel = new GoogleMapsSelector.MarkerModel( Backbone.$.parseJSON( place ) );
                                            options.add( {
                                                value: JSON.stringify( markerModel ),
                                                name: markerModel.get( "title" ),
                                                selected: true
                                            } );
                                        }
                                    }
                                    break;

                            }

                            component = new GoogleMapsSelector( {
                                model: new GoogleMapsSelector.Model( _.extend( model.toJSON(), { options: options } ) )
                            } );

                            break;
                        case "textarea":

                            component = new TextArea( {
                                model: model,
                                attributes: {
                                    readonly: model.get( "readOnly" ) ? model.get( "readOnly" ) : false
                                }
                            } );

                            break;
                        case "selector_datetime":

                            component = new Input( {
                                model: model,
                                attributes: {
                                    type: "text"
                                }
                            } );
                            var val = model.get( "value" );
                            if ( !val || !val.length ) val = "";
                            var attrs = {
                                value: val,
                                format: 'Y-m-d H:i'
                            };
                            attrs = _.extend( attrs, model.get( "custom_attr" ) );
                            component.$el.datetimepicker( attrs );

                            break;
                        default:

                            var type = "text";
                            if ( model.get( "isPassword" ) ) type = "password";

                            component = new Input( {
                                model: model,
                                attributes: {
                                    type: type,
                                    readonly: model.get( "readOnly" ) ? model.get( "readOnly" ) : false
                                }
                            } );

                            break;
                    }

                    if ( component && component.setName ) component.setName( model.get( "name" ) );

                    return component;
                }
            } );

        return MetaField;
    } );