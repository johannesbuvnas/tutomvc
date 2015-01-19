/**
 * Created by johannesbuvnas on 17/01/15.
 */
define( [
        "backbone",
        "underscore",
        "text!view/form/ReproducibleFormGroupItem.html",
        "view/form/inputs/FormInput"
    ],
    function ( Backbone, _, ReproducibleFormGroupItemHTML, FormInput )
    {
        var ReproducibleFormGroupItem = Backbone.View.extend( {
            model: undefined,
            tagName: "li",
            attributes: {
                "class": "list-group-item reproducible-form-group-item"
            },
            template: _.template( ReproducibleFormGroupItemHTML ),
            $formEl: undefined,
            _hasInitiatedFormInput: false,
            initialize: function ()
            {
                var _this = this;

                //Model
                this.model.set( "view", this );
                //View
                this.$formEl = Backbone.$( this.model.get( "formElementHTML" ) );
                this.$formEl.addClass( "form-group-detachable" );
                this.$formEl.find( '[name^="' + this.model.get( "name" ) + '"]' ).each( function ()
                {
                    var name = _this.model.fetchFormFormInputName( Backbone.$( this ).attr( "name" ) );
                    var afterName = _this.model.fetchFormFormInputAfterName( Backbone.$( this ).attr( "name" ) );
                    Backbone.$( this ).data( "name", name );
                    Backbone.$( this ).data( "after-name", afterName );
                } );
                //Controller
            },
            render: function ()
            {
                var _this = this;
                var $input;

                this.$formEl.trigger( "predetach" );
                this.$formEl.detach();

                // TODO: This is fucking up WPEditor
                this.$formEl.find( '[name^="' + this.model.get( "name" ) + '"]' ).each( function ()
                {
                    $input = Backbone.$( this );
                    var name = _this.model.constructFormInputName( $input.data( "name" ), $input.data( "after-name" ) );
                    var id = _this.model.constructFormInputID( name );

                    $input.attr( "name", name )
                        .attr( "id", id );

                    $input.parent( ".form-group-input" ).find( "label" ).attr( "for", id );
                } );

                this.$el.html( this.template( this.model.toJSON() ) );
                FormInput.autoInstance( this.$( ".reproducible-form-group-item-header" ) );
                this.$( ".reproducible-form-group-item-body" ).append( this.$formEl );

                if ( !this._hasInitiatedFormInput )
                {
                    this._hasInitiatedFormInput = true;
                    FormInput.autoInstance( this.$formEl );
                }
                return this;
            },
            remove: function ()
            {
                this.trigger( "remove", this );
            },
            events: {
                "click .btn-remove": "onRemove",
                "change select.reproducible-form-group-item-select-index": "onChangeIndex"
            },
            onRemove: function ( e )
            {
                e.preventDefault();
                this.remove();
            },
            onChangeIndex: function ( e )
            {
                this.model.set( { index: this.$( "select.reproducible-form-group-item-select-index" ).val() } );
            }
        }, {
            Model: Backbone.Model.extend( {
                defaults: {
                    name: "",
                    index: 0,
                    total: 1,
                    formElementHTML: "",
                    //view: undefined,
                    deleteOption: true,
                    navEnabled: true
                },
                constructFormInputID: function ( name )
                {
                    name = name.replace( /\[\]/gi, "" );
                    name = name.replace( /\]/gi, "" );
                    name = name.replace( /\[/gi, "_" );
                    return name;
                },
                constructFormInputName: function ( originalName, afterName )
                {
                    return this.get( "name" ) + "[" + this.get( "index" ) + "][" + originalName + "]" + afterName;
                },
                fetchFormFormInputName: function ( str )
                {
                    var re = new RegExp( this.get( "name" ) + "\\[(.*?)\\]" );
                    var matches = re.exec( str );
                    var match = matches[ matches.length - 1 ];

                    return match;
                },
                fetchFormFormInputAfterName: function ( str )
                {
                    var match = this.fetchFormFormInputName( str );
                    var afterMatch = str.substr( str.indexOf( "[" + match + "]" ) + match.length + 2 );

                    return afterMatch;
                }
            } )
        } );

        return ReproducibleFormGroupItem;
    } );