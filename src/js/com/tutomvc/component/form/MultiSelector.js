define( [
        "underscore",
        "backbone",
        "com/tutomvc/component/form/Selector",
        "text!com/tutomvc/component/form/MultiSelector.tpl.html"
    ],
    function ( _,
               Backbone,
               Selector,
               HTML )
    {
        "use strict";
        var MultiSelector = Selector.extend( {
                template: _.template( HTML ),
                initialize: function ()
                {
                    // Prep model
                    var filteredValue = this.model.get( "value" );

                    console.log( filteredValue );

                    switch ( typeof filteredValue )
                    {
                        case "string":

                            var selectedOption = this.model.get( "options" ).findWhere( { value: this.model.get( "value" ) } );
                            if ( selectedOption )
                            {
                                selectedOption.set( { selected: true } );
                            }
                            else if ( this.model.get( "inputOnEnter" ) )
                            {
                                this.model.get( "options" ).add( {
                                    name: filteredValue,
                                    value: filteredValue,
                                    selected: true
                                } );
                            }

                            break;
                        default:

                            for ( var i in filteredValue )
                            {
                                var selectedOption = this.model.get( "options" ).findWhere( { value: filteredValue[ i ] } );
                                if ( selectedOption ) selectedOption.set( { selected: true } );
                            }

                            break;
                    }

                    // Prep view
                    this.$el.addClass( "MultiSelector" );

                    // Prep controller
                    // this.events["click .SelectedOptions > .Model"] = "onSelect";
                    // this.events["keyup .SelectedOptions > input.Filter"] = "onFilterKeyUp";
                    // this.events["keydown .SelectedOptions > input.Filter"] = "onFilterKeyDown";
                    Backbone.$( "body" ).on( "click", _.bind( this.hide, this ) );

                    this.render();
                },
                render: function ()
                {
                    this.$el.html( this.template( this ) );

                    return this;
                },
                show: function ()
                {
                    if ( !this.model.hasReachedMaxCardinality() ) this.$el.addClass( "Expanded" );
                    else this.hide();
                },
                toggle: function ()
                {
                    if ( !this.model.hasReachedMaxCardinality() ) this.$el.toggleClass( "Expanded" );
                    else this.hide();
                },
                filter: function ( value )
                {
                    if ( !value || !value.length )
                    {
                        this.$( ".SelectedOptions > input.Filter" ).val( value );
                        this.render();
                        this.$el.addClass( "Expanded" );
                    }
                    else
                    {
                        this.model.get( "options" ).forEach( function ( model )
                        {
                            var innerHTML = model.get( "name" ).replace( /(<([^>]+)>)/ig, "" );
                            var index = innerHTML.toLowerCase().indexOf( value.toLowerCase() );
                            if ( index >= 0 )
                            {
                                innerHTML = innerHTML.substring( 0, index ) + "<span class='Highlight'>" + innerHTML.substring( index, index + value.length ) + "</span>" + innerHTML.substring( index + value.length );
                                Backbone.$( ".Options > .Model[data-cid=" + model.cid + "]" ).removeClass( "HiddenElement" );
                                Backbone.$( ".Options > .Model[data-cid=" + model.cid + "] > .Label" ).html( innerHTML );
                            }
                            else
                            {
                                Backbone.$( ".Options > .Model[data-cid=" + model.cid + "]" ).addClass( "HiddenElement" );
                            }
                        } );
                    }

                    return this;
                },
                // Events
                events: {
                    "click .Options > .Model > .AddButton": "onSelect",
                    "click .SelectedOptions > .Model > .RemoveButton": "onSelect",
                    "click .SelectedOptions > .Model > .Label a": "onLinkClick",
                    "keyup .SelectedOptions > input.Filter": "onFilterKeyUp",
                    "keydown .SelectedOptions > input.Filter": "onFilterKeyDown",
                    "focus .SelectedOptions > input.Filter": "onFilterFocus",
                    "click": "onClick"
                },
                onClick: function ( e )
                {
                    if ( e && !e.linkClick )
                    {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    if ( Backbone.$( e.target ).is( "input[type=text]" ) ) this.show();
                    else this.toggle();
                },
                onFilterKeyDown: function ( e )
                {
                    if ( e.keyCode == 13 )
                    {
                        e.preventDefault();

                        if ( this.model.get( "readOnly" ) ) return;

                        if ( this.model.get( "inputOnEnter" ) )
                        {
                            this.model.get( "options" ).add( {
                                name: this.$( ".SelectedOptions > input.Filter" ).val(),
                                value: this.$( ".SelectedOptions > input.Filter" ).val(),
                                selected: true
                            } );

                            this.$( ".SelectedOptions > input.Filter" ).val( "" );
                            this.filter( null );

                            this.hide();
                        }
                    }
                },
                onFilterKeyUp: function ( e )
                {
                    this.filter( this.$( ".SelectedOptions > input.Filter" ).val() );
                },
                onSelect: function ( e )
                {
                    if ( this.model.get( "readOnly" ) ) return;

                    var selectedModel = this.model.get( "options" ).get( Backbone.$( e.currentTarget ).attr( "data-cid" ) );

                    if ( selectedModel.get( "selected" ) )
                    {
                        selectedModel.set( { selected: false } );
                    }
                    else
                    {
                        if ( !this.model.hasReachedMaxCardinality() ) selectedModel.set( { selected: true } );
                    }

                    if ( selectedModel.get( "selected" ) || this.model.hasReachedMaxCardinality() ) this.$el.removeClass( "Expanded" );
                    else this.$el.addClass( "Expanded" );

                    var newValue = [];
                    var selectedModels = this.model.get( "options" ).where( { selected: true } );
                    Backbone.$( selectedModels ).each( function ()
                    {
                        newValue.push( this.get( "value" ) );
                    } );

                    this.model.set( { value: newValue } );
                }
            },
            {
                Model: Selector.Model.extend( {
                    defaults: {
                        inputOnEnter: false,
                        maxCardinality: -1
                    },
                    hasReachedMaxCardinality: function ()
                    {
                        if ( this.get( "maxCardinality" ) < 0 || this.get( "options" ).where( { selected: true } ).length < this.get( "maxCardinality" ) ) return false;
                        else return true;
                    }
                } )
            } );

        return MultiSelector;
    } );