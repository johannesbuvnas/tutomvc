/**
 * Created by johannesbuvnas on 17/01/15.
 */
define( [
        "backbone",
        "underscore",
        "text!view/form/ReproducibleFormGroup.html",
        "view/form/ReproducibleFormGroupItem"
    ],
    function ( Backbone, _, ReproducibleFormGroupHTML, ReproducibleFormGroupItem )
    {
        var ReproducibleFormGroup = Backbone.View.extend( {
            tagName: "ul",
            attributes: {
                "class": "list-group reproducible-form-group"
            },
            model: undefined,
            collection: undefined,
            _dummy: undefined,
            template: _.template( ReproducibleFormGroupHTML ),
            initialize: function ()
            {
                //Model
                this.collection = new ReproducibleFormGroup.Collection( Backbone.$.parseJSON( this.$( "textarea.collection" ).val() ) );
                this.model = new ReproducibleFormGroup.Model( Backbone.$.parseJSON( this.$( "textarea.model" ).val() ) );
                this.model.set( { addOption: !this._hasReachedMax( this.collection.length ) }, { silent: true } );
                this._dummy = Backbone.$.parseJSON( this.$( "textarea.collection-dummy-model" ).val() );
                //View
                this.$el.html( this.template( this.model.toJSON() ) );
                //Controller
                this.listenTo( this.collection, "change:index", this.onChangeIndex );
            },
            render: function ()
            {
                var height = this.$el.outerHeight();
                this.$el.css( "height", height );
                var _this = this;
                var index = 0;
                this.collection.each( function ( model )
                {
                    if ( !model.get( "view" ) )
                    {
                        model.set( "view", _this._getInstanceOfReproducibleFormGroupItem( model ) );
                        _this.$( ".reproducible-form-group-footer" ).before( model.get( "view" ).$el );
                    }
                    //model.get( "view" ).$el.detach();
                    model.set( {
                        index: index,
                        total: _this.collection.length,
                        deleteOption: _this._isDeletable( _this.collection.length - 1 )
                    }, { silent: true } );
                    index++;
                } );

                //this.model.set( { addOption: !this._hasReachedMax( this.collection.length ) }, { silent: true } );
                //this.$el.html( this.template( this.model.toJSON() ) );

                this.collection.each( function ( model )
                {
                    //model.get( "view" ).$formEl.trigger( "reattach" );

                    if ( !model.get( "hasTriggered" ) )
                    {
                        model.get( "view" ).render();
                        model.get( "view" ).$el.trigger( "added.reproducible-form-group-item" );
                        model.set( {
                            hasTriggered: true
                        } );
                    }
                } );

                this.$el.css( "height", "auto" );

                if ( !this.model.get( "addOption" ) || this.collection.length == 0 )
                {
                    this.$( ".reproducible-form-group-top-nav" ).addClass( "hidden" );
                }

                return this;
            },
            _getInstanceOfReproducibleFormGroupItem: function ( model )
            {
                var item = new ReproducibleFormGroupItem( {
                    model: model
                } );
                this.listenTo( item, "remove", this.onRemoveItem );

                return item;
            },
            _hasReachedMax: function ( length )
            {
                return length >= this.model.get( "max" ) && this.model.get( "max" ) >= 0;
            },
            _isDeletable: function ( index )
            {
                if ( index < this.model.get( "min" ) ) return false;

                return true;
            },
            add: function ( before )
            {
                var model = new ReproducibleFormGroupItem.Model( this._dummy );
                model.set( {
                    index: this.collection.length,
                    total: this.collection.length + 1
                } );

                if ( before )
                {
                    this.collection.add( model, { at: 0 } );
                }
                else
                {
                    this.collection.add( model );
                }

                this.render();
            },
            events: {
                "click .btn-add": "onAdd"
            },
            onAdd: function ( e )
            {
                e.preventDefault();
                var $el = Backbone.$( e.currentTarget );
                this.add( $el.parents( ".reproducible-form-group-top-nav" ).length );
            },
            onChangeIndex: function ( model, newIndex )
            {
                this.collection.remove( model );
                this.collection.add( model, { at: newIndex } );
                this.render();
            },
            onRemoveItem: function ( item )
            {
                if ( this._isDeletable( this.collection.length - 1 ) )
                {
                    item.$el.remove();
                    this.collection.remove( item.model );
                    this.render();
                }
            }
        }, {
            Model: Backbone.Model.extend( {
                defaults: {
                    //addOption: true,
                    //deleteOption: true,
                    min: 1,
                    max: 1,
                    label: "",
                    description: ""
                }
            } ),
            Collection: Backbone.Collection.extend( {
                model: ReproducibleFormGroupItem.Model
            } ),
            autoInstance: function ( $el )
            {
                $el.find( ".reproducible-form-group" ).each( function ()
                {
                    $ReproducibleFormGroup = Backbone.$( this );
                    var reproducibleFormGroup = new ReproducibleFormGroup( {
                        el: $ReproducibleFormGroup
                    } );
                    reproducibleFormGroup.render();
                } );
            }
        } );

        return ReproducibleFormGroup;
    } );