/**
 * Created by johannesbuvnas on 17/01/15.
 */
define( [
        "backbone",
        "underscore",
        "text!view/form/MetaBoxListGroupItem.html",
        "view/form/inputs/Input"
    ],
    function ( Backbone, _, MetaBoxListGroupItemHTML, Input )
    {
        var MetaBoxListGroupItem = Backbone.View.extend( {
            model: undefined,
            tagName: "li",
            attributes: {
                "class": "list-group-item metabox-list-group-item"
            },
            template: _.template( MetaBoxListGroupItemHTML ),
            $formEl: undefined,
            initialize: function ()
            {
                var _this = this;

                //Model
                this.model.set( "view", this );
                //View
                this.$formEl = Backbone.$( this.model.get( "formElementHTML" ) );
                this.$formEl.find( '[name^="' + this.model.get( "name" ) + '"]' ).each( function ()
                {
                    var name = _this.model.fetchFormInputName( Backbone.$( this ).attr( "name" ) );
                    var afterName = _this.model.fetchFormInputAfterName( Backbone.$( this ).attr( "name" ) );
                    Backbone.$( this ).data( "name", name );
                    Backbone.$( this ).data( "after-name", afterName );
                } );
                Input.autoInstance( this.$formEl );
                //Controller
            },
            render: function ()
            {
                var _this = this;

                this.$formEl.detach();
                this.$el.html( this.template( this.model.toJSON() ) );
                Input.autoInstance( this.$( ".metabox-list-group-item-header" ) );
                this.$( ".metabox-list-group-item-body" ).append( this.$formEl );
                this.$formEl.find( '[name^="' + this.model.get( "name" ) + '"]' ).each( function ()
                {
                    var name = _this.model.constructInputName( Backbone.$( this ).data( "name" ), Backbone.$( this ).data( "after-name" ) );
                    var id = _this.model.constructInputID( name );

                    Backbone.$( this ).attr( "name", name )
                        .attr( "id", id );

                    Backbone.$( this ).parent( ".form-group-input" ).find( "label" ).each( function ()
                    {
                        Backbone.$( this ).attr( "for", id );
                    } );
                } );
                return this;
            },
            remove: function ()
            {
                console.log( "MetaBoxListGroupItem::remove" );
                this.trigger( "remove", this );
            },
            events: {
                "click .btn-remove": "onRemove",
                "change select.metabox-list-group-item-select-index": "onChangeIndex"
            },
            onRemove: function ( e )
            {
                e.preventDefault();
                this.remove();
            },
            onChangeIndex: function ( e )
            {
                this.model.set( { index: this.$( "select.metabox-list-group-item-select-index" ).val() } );
            }
        }, {
            Model: Backbone.Model.extend( {
                defaults: {
                    name: "",
                    index: 0,
                    total: 1,
                    formElementHTML: "",
                    view: undefined,
                    deleteOption: true,
                    navEnabled: true
                },
                constructInputID: function ( name )
                {
                    name = name.replace( /\[\]/gi, "" );
                    name = name.replace( /\]/gi, "" );
                    name = name.replace( /\[/gi, "_" );
                    return name;
                },
                constructInputName: function ( originalName, afterName )
                {
                    return this.get( "name" ) + "[" + this.get( "index" ) + "][" + originalName + "]" + afterName;
                },
                fetchFormInputName: function ( str )
                {
                    var re = new RegExp( this.get( "name" ) + "\\[(.*?)\\]" );
                    var matches = re.exec( str );
                    var match = matches[ matches.length - 1 ];

                    return match;
                },
                fetchFormInputAfterName: function ( str )
                {
                    var match = this.fetchFormInputName( str );
                    var afterMatch = str.substr( str.indexOf( "[" + match + "]" ) + match.length + 2 );

                    return afterMatch;
                }
            } )
        } );

        return MetaBoxListGroupItem;
    } );