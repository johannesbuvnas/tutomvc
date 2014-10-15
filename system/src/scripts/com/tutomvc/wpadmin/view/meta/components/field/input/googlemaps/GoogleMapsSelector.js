/**
 * Created by johannesbuvnas on 13/10/14.
 */
define( [
        "jquery",
        "underscore",
        "backbone",
        "com/tutomvc/component/form/MultiSelector",
        "async!https://maps.googleapis.com/maps/api/js?key=" + TutoMVC.metaFieldGoogleMapsAPIKey + "&libraries=places",
        "text!com/tutomvc/wpadmin/view/meta/components/field/input/googlemaps/GoogleMapsSelector.html"
    ],
    function ( $,
               _,
               Backbone,
               MultiSelector,
               GoogleMaps,
               HTML )
    {
        var GoogleMapsSelector = Backbone.View.extend( {
                    template: _.template( HTML ),
                    multiselector: undefined,
                    model: undefined,
                    map: undefined,
                    marker: undefined,
                    searchInput: undefined,
                    _keyUpTimer: undefined,
                    initialize: function ()
                    {
                        // Model
                        // View
                        this.multiselector = new MultiSelector( {
                            model: this.model
                        } );
                        this.multiselector.model.get( "options" ).on( "change", _.bind( this.adjustCardinality, this ) );
                        this.multiselector.model.get( "options" ).on( "add", _.bind( this.adjustCardinality, this ) );
                        this.multiselector.model.get( "options" ).on( "remove", _.bind( this.adjustCardinality, this ) );
                        // Controller

                        this.render();
                    },
                    render: function ()
                    {
                        this.$el.html( this.template() );

                        this.map = new google.maps.Map( this.$( ".GoogleMaps > .Inner" ).get( 0 ), this.model.get( "mapOptions" ) );

                        this.map.controls[ google.maps.ControlPosition.BOTTOM_CENTER ].push( this.$( ".SearchBox" ).get( 0 ) );
                        this.searchInput = new google.maps.places.SearchBox( this.$( "input.Search" ).get( 0 ) );
                        google.maps.event.addListener( this.searchInput, "places_changed", _.bind( this.onSearchInput, this ) );

                        this.$( ".GoogleMapsExtendedContainer" ).append( this.multiselector.$el );

                        this.adjustCardinality();

                        return this;
                    },
                    adjustCardinality: function ()
                    {
                        if ( this.model.get( "maxCardinality" ) < 0 || this.multiselector.model.get( "options" ).where( { selected: true } ).length < this.model.get( "maxCardinality" ) )
                        {
                            this.$( ".SearchBox" ).css( "display", "block" );
                        }
                        else
                        {
                            this.$( ".SearchBox" ).css( "display", "none" );
                        }
                    },
                    setMarkerToPlace: function ( place )
                    {
                        if ( this.marker )
                        {
                            this.marker.setMap( null );
                            this.$( ".AddButton" ).css( "display", "none" );
                        }

                        if ( !place ) return this;

                        var image = {
                            url: place.icon,
                            size: new google.maps.Size( 71, 71 ),
                            origin: new google.maps.Point( 0, 0 ),
                            anchor: new google.maps.Point( 17, 34 ),
                            scaledSize: new google.maps.Size( 25, 25 )
                        };

                        this.marker = new google.maps.Marker( {
                            map: this.map,
                            icon: image,
                            title: place.formatted_address,
                            position: place.geometry.location,
                            draggable: true,
                            cursor: "grab"
                        } );

                        var bounds = new google.maps.LatLngBounds();
                        bounds.extend( place.geometry.location );
                        this.map.fitBounds( bounds );

                        this.$( ".AddButton" ).css( "display", "block" );

                        return this;
                    },
                    events: {
                        "keypress input.Search": "onSearchKeyPress",
                        "keyup input.Search": "onSearchKeyUp",
                        "click .AddButton": "onAddButtonClick"
                    },
                    onAddButtonClick: function ( e )
                    {
                        e.preventDefault();

                        if ( this.model.get( "maxCardinality" ) < 0 || this.multiselector.model.get( "options" ).where( { selected: true } ).length < this.model.get( "maxCardinality" ) )
                        {
                            var value = new GoogleMapsSelector.MarkerModel( {
                                lat: this.marker.getPosition().lat(),
                                lng: this.marker.getPosition().lng(),
                                title: this.marker.getTitle()
                            } );
                            this.multiselector.model.get( "options" ).add( {
                                name: this.marker.getTitle(),
                                value: JSON.stringify( value ),
                                selected: true
                            } );
                            this.multiselector.render();
                        }

                        this.setMarkerToPlace( null );
                        this.$( "input.Search" ).val( "" );
                    },
                    onSearchInput: function ( e )
                    {
                        var places = this.searchInput.getPlaces();

                        if ( places.length == 0 )
                        {
                            return;
                        }

                        for ( var i = 0, place; place = places[ i ]; i++ )
                        {
                            return this.setMarkerToPlace( place );
                        }
                    },
                    onSearchKeyPress: function ( e )
                    {
                        c = e.which ? e.which : e.keyCode;

                        if ( c == 13 )
                        {
                            e.preventDefault();
                        }
                    },
                    onSearchKeyUp: function ( e )
                    {
                        var val = this.$( "input.Search" ).val();
                        if ( !val || !val.length )
                        {
                            this.setMarkerToPlace( null );
                        }
                    }
                },
                {
                    Model: MultiSelector.Model.extend( {
                        defaults: {
                            mapOptions: {
                                center: { lat: -34.397, lng: 150.644 },
                                zoom: 1
                            }
                        }
                    } ),
                    MarkerModel: Backbone.Model.extend( {
                        defaults: {
                            lat: null,
                            lng: null,
                            title: null
                        }
                    } )
                }
            )
            ;

        return GoogleMapsSelector;
    } )
;