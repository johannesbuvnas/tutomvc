/**
 * Created by johannesbuvnas on 17/01/15.
 */
define( [
        "backbone",
        "underscore",
        "text!view/form/MetaBoxListGroup.html",
        "view/form/MetaBoxListGroupItem",
        "view/form/inputs/Input"
    ],
    function ( Backbone, _, MetaBoxListGroupHTML, MetaBoxListGroupItem, Input )
    {
        var MetaBoxListGroup = Backbone.View.extend( {
            tagName: "ul",
            attributes: {
                "class": "list-group metabox-list-group"
            },
            model: undefined,
            collection: undefined,
            _dummy: undefined,
            template: _.template( MetaBoxListGroupHTML ),
            initialize: function ()
            {
                console.log( "MetaBoxListGroup::initialize" );
                //Model
                this.model = new MetaBoxListGroup.Model( Backbone.$.parseJSON( this.$( "textarea.model" ).val() ) );
                this.collection = new MetaBoxListGroup.Collection( Backbone.$.parseJSON( this.$( "textarea.collection" ).val() ) );
                this._dummy = Backbone.$.parseJSON( this.$( "textarea.collection-dummy-model" ).val() );
                //View
                this.render();
                //Controller
                this.listenTo( this.collection, "change:index", this.onChangeIndex );
            },
            render: function ()
            {
                console.log( "MetaBoxListGroup::render" );
                var height = this.$el.outerHeight();
                this.$el.css( "height", height );
                var _this = this;
                var index = 0;
                this.collection.each( function ( model )
                {
                    if ( !model.get( "view" ) )
                    {
                        model.set( "view", _this._getInstanceOfMetaBoxListGroupItem( model ) );
                    }
                    model.get( "view" ).$el.detach();
                    model.set( {
                        index: index,
                        total: _this.collection.length,
                        deleteOption: _this._isDeletable( _this.collection.length - 1 )
                    }, { silent: true } );
                    index++;
                } );

                this.model.set( { addOption: !this._hasReachedMax( this.collection.length ) }, { silent: true } );
                this.$el.html( this.template( this.model.toJSON() ) );

                this.collection.each( function ( model )
                {
                    model.get( "view" ).render();
                    _this.$( ".metabox-list-group-footer" ).before( model.get( "view" ).$el );
                } );

                this.$el.css( "height", "auto" );

                console.log( "MetaBoxListGroup::addOption", this.model.get( "addOption" ) );
                if ( !this.model.get( "addOption" ) )
                {
                    this.$( ".metabox-list-group-top-nav" ).addClass( "hidden" );
                }

                return this;
            },
            _getInstanceOfMetaBoxListGroupItem: function ( model )
            {
                var item = new MetaBoxListGroupItem( {
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
                console.log( "MetaBoxListGroup::add", before );
                var model = new MetaBoxListGroupItem.Model( this._dummy );
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
                this.add( $el.parents( ".metabox-list-group-top-nav" ).length );
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
                model: MetaBoxListGroupItem.Model
            } ),
            autoInstance: function ( $el )
            {
                $el.find( ".metabox-list-group" ).each( function ()
                {
                    $metaboxListGroup = Backbone.$( this );
                    new MetaBoxListGroup( {
                        el: $metaboxListGroup
                    } );
                } );
            }
        } );

        return MetaBoxListGroup;
    } );