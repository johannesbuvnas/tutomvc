define([
	"jquery"
],
function( $ )
{
	return new function()
	{
	// begin tuto

		/* PRIVATE REFERENCES */
		var _ns = this;
		var _classExtensions = [];

		/* PRIVATE METHODS */
		// var extend = function( childName, parentName )
		// {
		// 	_classExtensions.push( {
		// 		"childName" : childName,
		// 		"parentName" : parentName
		// 	} );
		// };

		// var init = function()
		// {
		// 	for(var i = 0; i < _classExtensions.length; i++)
		// 	{
		// 		var extension = _classExtensions[i];
		// 		var childClass = eval( extension.childName );
		// 		var childPrototype = childClass.prototype;
		// 		var parentClass = eval( extension.parentName );

		// 		childClass.prototype = new parentClass();
		// 		childClass.prototype.constructor = childClass;

		// 		for (var key in childPrototype)
		// 		{
		// 		   if( !childClass.prototype[ key ] ) childClass.prototype[ key ] = childPrototype[ key ];
		// 		}
		// 	}
		// };

		this.core = new function()
		{
			/* PUBLIC CLASSES */
			this.CoreClass = function()
			{
				/* PRIVATE REFERENCES */
				var _this = this;
				var _facadeKey;

				/* ACTIONS */
				this.initializeFacadeKey = function( facadeKey )
				{
					_facadeKey = facadeKey;

					return _facadeKey;
				};

				/* SET AND GET */
				this.getFacadeKey = function()
				{
					return _facadeKey;
				};

				this.getFacade = function()
				{
					return _ns.core.Facade.getInstance( _facadeKey );
				};

				/* EVENTS */
				this.onRegister = function()
				{
				};
			};

			this.Facade = new function()
			{
				function Facade( key )
				{
					/* PRIVATE REFERENCES */
					var _this = this;
					var _key;

					/* PUBLIC VARS */
					this.model;
					this.view;
					this.controller;


					/* CONSTUCTOR */
					var construct = function( key )
					{
						if( !key ) return console.log( "Facade::error(" + "need key" + ")" );

						_key = key;

						Facade.instanceMap[ key ] = _this;

						initializeModel();
						initializeController();
						initializeView();
					};
					var initializeModel = function()
					{
						_this.model = _ns.core.model.Model.getInstance( _this.getKey() );
					};
					var initializeController = function()
					{
						_this.controller = _ns.core.controller.Controller.getInstance( _this.getKey() );
					};
					var initializeView = function()
					{
						_this.view = _ns.core.view.View.getInstance( _this.getKey() );
					};

					/* ACTIONS */
					this.dispatch = function( name, body )
					{
						_this.view.dispatchEvent( new _ns.core.controller.event.Event( name, body ) );
					};

					
					/* SET AND GET */
					this.getKey = function()
					{
						return _key;
					};

					/* EVENTS */
					this.onRegister = function()
					{
						console.log("tt");
					};

					construct( key );
				}

				/* STATIC REFERENCES */
				Facade.instanceMap = [];
				Facade.getInstance = function( key )
				{
					if( !key ) return console.log( "Facade::getInstance - no key" );

					if( Facade.instanceMap[ key ] ) 
					{
						return Facade.instanceMap[ key ];
					}
					else
					{
						return new Facade( key );
					}

					return null;
				};

				return Facade;
			};

			this.model = new function()
			{
			// begin tuto.model

				this.proxy = new function()
				{
				// begin tuto.model.proxy

					this.Proxy = function( name )
					{
						function Proxy( name )
						{
							/* VARS */
							var _this = this;
							var _name;
							var _map = [];

							var construct = function( name )
							{
								if( !name ) return console.log("Proxy::construct - no name");
								_name = name;
							};

							/* METHODS */
							this.add = function( item, key )
							{
								if(key) _map[ key ] = item;
								else _map.push( item );
							};

							this.has = function( key )
							{
								return _map[ key ] != null;
							};

							this.get = function( key )
							{
								return _map[ key ];
							};

							/* SET AND GET */
							this.getMap = function()
							{
								return _map;
							};
							this.getName = function()
							{
								return _name;
							};

							construct( name );
						}

						Proxy.prototype = new _ns.core.CoreClass();
						Proxy.prototype.constructor = Proxy;

						return new Proxy( name );
					};

				// end tuto.model.proxy
				};

				this.Model = new function()
				{
					function Model( key )
					{
						/* VARS */
						var _this = this;
						var _facadeKey;
						var _proxyMap = [];

						var construct = function( key )
						{
							_facadeKey = key;

							Model.instanceMap[ _facadeKey ] = _this;
						};

						/* METHODS */
						this.registerProxy = function( proxy )
						{
							// if( !(proxy instanceof _ns.core.model.proxy.Proxy) ) return console.log( "Model::registerProxy - proxy isnt instance of Proxy" );

							proxy.initializeFacadeKey( _this.getFacadeKey() );
							_proxyMap[ proxy.getName() ] = proxy;
							proxy.onRegister();

							return proxy;
						};

						this.retrieveProxy = function( proxyName )
						{
							return _proxyMap[ proxyName ];
						};

						/* SET AND GET */
						this.getFacadeKey = function()
						{
							return _facadeKey;
						};

						construct( key );
					}

					/* STATIC REFERENCES */
					Model.instanceMap = [];
					Model.getInstance = function( key )
					{
						if( !key ) return console.log( "Model::getInstance - no key" );

						if( Model.instanceMap[ key ] ) 
						{
							return Model.instanceMap[ key ];
						}
						else
						{
							return new Model( key );
						}

						return null;
					};

					return Model;
				};

			// end tuto.model
			};

			this.view = new function()
			{
			// begin tuto.view

				this.mediator = new function()
				{
				//begin tuto.view.mediator

					this.Mediator = function( name, viewComponent )
					{
						function Mediator( name, viewComponent )
						{
							/* VARS */
							var _this = this;
							var _name;
							var _viewComponent;

							var construct = function( name )
							{
								_this.setName( name );
							};

							/* ACTIONS */
							this.dispatch = function( name, body )
							{
								_this.getFacade().view.dispatchEvent( new _ns.core.controller.event.Event( name, body ) );
							};

							/* METHODS */
							this.addEventListener = function( name, callback )
							{
								_this.getFacade().view.addEventListener( name, callback );
							};

							/* SET AND GET */
							this.setViewComponent = function( viewComponent )
							{
								_viewComponent = viewComponent;
							};
							this.getViewComponent = function()
							{
								return _viewComponent;
							};

							this.setName = function( name )
							{
								_name = name;
							};
							this.getName = function()
							{
								return _name;
							};

							/* EVENTS */
							this.onRegister = function()
							{
								// console.log( "Mediator::onRegister( " + _this.getName() + " )" );
							};

							construct( name, viewComponent );
						}

						Mediator.prototype = new _ns.core.CoreClass();
						Mediator.prototype.constructor = Mediator;

						return new Mediator( name, viewComponent );
					};

				//end tuto.view.mediator
				};

				this.View = function( key )
				{
					function View( key )
					{
						/* VARS */
						var _this = this;
						var _facadeKey;
						var _mediatorMap = [];

						var construct = function( key )
						{
							_facadeKey = key;

							_ns.core.view.View.instanceMap[ _facadeKey ] = _this;
						};

						/* ACTIONS */

						/* METHODS */
						this.registerMediator = function( viewComponent, mediator )
						{
							// if( !(mediator instanceof _ns.core.view.mediator.Mediator) ) return console.log( "View::registerMediator - mediator doesn't extend Mediator" );

							mediator.initializeFacadeKey( _this.getFacadeKey() );
							mediator.setViewComponent( viewComponent );
							_mediatorMap[ mediator.getName() ] = mediator;
							mediator.onRegister();

							return mediator;
						};
						this.retrieveMediator = function( name )
						{
							return _mediatorMap[ name ];
						};

						/* SET AND GET */
						this.getFacadeKey = function()
						{
							return _facadeKey;
						};

						construct( key );
					}

					
					View.prototype = new _ns.core.controller.event.EventDispatcher();
					View.prototype.constructor = View;

					return new View( key );
				};

				this.View.instanceMap = [];
				this.View.getInstance = function( key )
				{
					if( !key ) return console.log( "View::getInstance - no key" );

					if( _ns.core.view.View.instanceMap[ key ] ) 
					{
						return _ns.core.view.View.instanceMap[ key ];
					}
					else
					{
						
						return new _ns.core.view.View( key );
					}

					return null;
				};

			// end tuto.view
			};

			this.controller = new function()
			{
			// begin tuto.controller

				this.command = new function()
				{
				// begin tuto.controller.command
					
					this.Command = function()
					{
						function Command()
						{
							/* ACTIONS */
							this.execute = function( event )
							{
								
							};
						}

						Command.prototype = new _ns.core.CoreClass();
						Command.prototype.constructor = Command;

						return new Command();
					};

				// end tuto.controller.command
				};

				this.Controller = new function()
				{
					function Controller( key )
					{
						/* VARS */
						var _this = this;
						var _facadeKey;
						var _commandMap = [];
						var _view;

						var construct = function( key )
						{
							_facadeKey = key;
							_view = _ns.core.view.View.getInstance( _facadeKey );

							Controller.instanceMap[ _facadeKey ] = _this;
						};

						/* ACTIONS */
						this.executeCommand = function( event )
						{
							// if( !(event instanceof _ns.core.controller.event.Event) ) return console.log( "Controller::executeCommand - event isnt instance of Event" );

							if( _commandMap[ event.getName() ] ) 
							{
								var command = new _commandMap[ event.getName() ]();
								// if( !(command instanceof _ns.core.controller.command.Command) ) return console.log( "Controller::executeCommand - command isnt instance of Command" );
								command.initializeFacadeKey( _this.getFacadeKey() );
								command.execute( event );
							}
						};

						/* METHODS */
						this.registerCommand = function( name, commandClassReference )
						{
							_commandMap[ name ] = commandClassReference;
							_view.addEventListener( name, _this.executeCommand );
						};

						/* SET AND GET */
						this.getFacadeKey = function()
						{
							return _facadeKey;
						};

						construct( key );
					}

					/* STATIC REFERENCES */
					Controller.instanceMap = [];
					Controller.getInstance = function( key )
					{
						if( !key ) return console.log( "Controller::getInstance - no key" );

						if( Controller.instanceMap[ key ] ) 
						{
							return Controller.instanceMap[ key ];
						}
						else
						{
							return new Controller( key );
						}

						return null;
					};

					return Controller;
				};

				this.event = new function()
				{
				// begin tuto.controller.event

					this.Event = function( name, body )
					{
						/* VARS */
						var _name = name;
						var _body = body;

						/* SET AND GET */
						this.setName = function( name )
						{
							_name = name;
						};
						this.getName = function()
						{
							return _name;
						};

						this.setBody = function( body )
						{
							_body = body;
						};
						this.getBody = function()
						{
							return _body;
						};
					};

					this.EventDispatcher = function()
					{
						/* VARS */
						var _this = this;
						var _listenerMap = [];

						/* ACTIONS */
						this.dispatchEvent = function( event )
						{
							// if( !(event instanceof _ns.core.controller.event.Event) ) return console.log( "EventDispatcher::dispatch - event isnt an instance of tuto.core.controller.event.Event" );

							if( _listenerMap[ event.getName() ] )
							{
								for(var k in _listenerMap[ event.getName() ])
								{
									var eventListenerCallback = _listenerMap[ event.getName() ][ k ];
									eventListenerCallback( event );
								}
							}
						};

						this.getEventMap = function()
						{
							return _listenerMap;
						};

						/* METHODS */
						this.addEventListener = function( eventName, callback )
						{
							if( !_listenerMap[ eventName ] ) _listenerMap[ eventName ] = [];

							_listenerMap[ eventName ].push( callback );

							return true;
						};

						this.removeEventListener = function( eventName, callback )
						{
							if( _listenerMap[ eventName ] )
							{
								for(var k in _listenerMap[ eventName ])
								{
									var eventListenerCallback = _listenerMap[ eventName.getName() ][ k ];
									if( eventListenerCallback == callback ) 
									{
										_listenerMap[ eventName.getName() ][ k ] = null;
										delete _listenerMap[ eventName.getName() ][ k ];
									}
								}
							}

							return true;
						};
					};

				// end tuto.controller.event
				};

			// end tuto.controller
			};
		};

		this.components = new function()
		{
		// tuto.components

			this.controller = new function()
			{
				this.event = new function()
				{
					this.EventTypes =
					{
						CHANGE : "change",
						EXPAND : "expand",
						COLLAPSE : "collapse"
					};
				};
			};

			this.model = new function()
			{
				// tuto.model
				this.proxy = new function()
				{
				// begin tuto.components.model.proxy

					this.Proxy = function( name )
					{

						/* VARS */
						var _this = this;
						var _name;
						var _map = [];

						var construct = function( name )
						{
							_name = name;
						};

						/* ACTIONS */
						this.empty = function()
						{
							for(var k in _map)
							{
								var item = _map[ k ];
								item = null;
								delete _map[ k ];
							}

							return true;
						};

						/* METHODS */
						this.addVO = function( name, value )
						{
							var vo = new _ns.components.model.vo.ValueObject( name, value );
							add( vo, vo.getValue() );
						};

						var add = function( item, key )
						{
							if(key) _map[ key ] = item;
							else _map.push( item );
						};

						this.has = function( key )
						{
							return _map[ key ] != null;
						};

						this.get = function( key )
						{
							return _map[ key ];
						};

						this.getVOByValue = function( value )
						{
							for(var voKey in _this.getMap())
							{
								var vo = _this.get( voKey );
								if( vo.getValue() == value ) return vo;
							}

							return null;
						};

						this.remove = function( key )
						{
							_map[ key ] = null;
							delete _map[ key ];
						};

						/* SET AND GET */
						this.getElement = function()
						{
							var element = $( "<div class='Proxy'></div>" );
							if( _this.getName() ) 
							{
								element.append( "<div class='Name'><span>" + _this.getName() + "</span></div>" );
							}

							var map = $( "<div class='Map'></div>" );

							for(var k in _map)
							{
								var vo = _map[ k ];
								var voElement = vo.getElement();
								voElement.attr( "dataProxyName", _this.getName() );
								map.append( voElement );
							}

							element.append( map );

							return element;
						};

						this.getMap = function()
						{
							return _map;
						};

						this.setName = function( name )
						{
							_name = name;
						};
						this.getName = function()
						{
							return _name;
						};

						this.getLength = function()
						{
							return _map.length;
						};

						construct( name );
					};

				// end tuto.components.model.proxy
				};
				this.vo = new function()
				{
				// tuto.components.model.vo

						this.ValueObject = function( name, value )
						{
							var _this = this;
							var _value;
							var _name;

							var construct = function( name, value )
							{
								_name = name;
								_value = value ? value : name;
							};

							/* SET AND GET */
							this.setValue = function( value )
							{
								_value = value;
							};
							this.getValue = function()
							{
								return _value;
							};

							this.setName = function( name )
							{
								_name = name;
							};
							this.getName = function()
							{
								return _name;
							};

							this.getElement = function()
							{
								var element = $( '<div class="ValueObject"></div>' );
								element.append( "<span class='Name'>" + _this.getName() + "</div>" );
								element.attr( "dataValue", _this.getValue() );
								element.attr( "dataName", _this.getName() );

								return element;
							};

							construct( name, value )
						};

				// end tuto.components.model.vo
				};

				this.Model = function()
				{
					/* VARS */
					var _this = this;
					var _facadeKey;
					var _proxyMap = [];

					var construct = function()
					{
					};

					/* ACTIONS */
					this.empty = function()
					{
						for(var k in _proxyMap)
						{
							var item = _proxyMap[ k ];
							item = null;
							delete _proxyMap[ k ];
						}

						return true;
					};

					/* METHODS */
					this.addProxy = function( proxy )
					{
						// if( !(proxy instanceof _ns.components.model.proxy.Proxy) ) return console.log( "Model::addProxy - proxy isnt instance of Proxy" );

						if( proxy.getName() ) _proxyMap[ proxy.getName() ] = proxy;
						else _proxyMap.push( proxy );

						return proxy;
					};

					this.getProxy = function( proxyName )
					{
						for(var proxyKey in _proxyMap)
						{
							var proxy = _proxyMap[ proxyKey ];
							if( proxyKey == proxyName ) return proxy;
						}

						return null;
					};

					this.getElement = function()
					{
						var element = $( "<div class='Model'></div>" );

						for(var i in _proxyMap)
						{
							var proxy = _proxyMap[ i ];
							element.append( proxy.getElement() );
						}

						return element;
					};

					this.getProxyByValue = function( value )
					{
						for(var proxyKey in _proxyMap)
						{
							var proxy = _this.getProxy( proxyKey );
							if( proxy.getVOByValue( value ) ) return proxy;
						}

						return null;
					};

					this.getMap = function()
					{
						return _proxyMap;
					};

					/* SET AND GET */

					construct();
				};

			// tuto.model
			};

			this.buttons = new function()
			{
			// tuto.components.buttons

				this.Button = function( label )
				{
					function Button( label )
					{
						var _this = this;
						this.element = $( '<div class="Button"></div>' );
						this.symbol = $( "<div class='Symbol'></div>" );
						this.element.append( _this.symbol );
						this.label = $( "<span class='Label'></span>" );
						this.element.append( _this.label );

						/* SET AND GET */
						this.setLabel = function( label )
						{
							_this.label.html( label );
						};
						this.getLabel = function()
						{
							return _this.label.html();
						};

						this.getElement = function()
						{
							return _this.element;
						};

						_this.setLabel( label );
					}

					Button.prototype = new _ns.core.controller.event.EventDispatcher();
					Button.prototype.constructor = Button;

					return new Button( label );
				};

			// end tuto.components.buttons
			};

			this.form = new function()
			{
			// tuto.components.form

				this.input = new function()
				{
				// tuto.components.form.input

					this.Input = function()
					{
						function Input()
						{
							var _this = this;

							/* DISPLAY OBJECTS */
							var _element;

							var construct = function()
							{
								draw();
							};

							var draw = function()
							{
								_this.setElement( $( "<input type='hidden' />" ) );
							};

							/* ACTIONS */
							this.reset = function()
							{
								_this.setValue( null );
							};

							/* SET AND GET */
							this.setElement = function( element )
							{
								_element = $( element );
							};
							this.getElement = function()
							{
								return _element;
							};

							this.setValue = function( value )
							{
								_element.val( value ? value : '' );
							};
							this.getValue = function()
							{
								return _element.val();
							};

							this.setName = function( name )
							{
								_element.attr( "name", name ? name : '' );
							};
							this.getName = function()
							{
								return _element.attr( "name" );
							};

							this.setID = function( id )
							{
								_element.attr( "id", id ? id : '' );
							};
							this.getID = function()
							{
								return _element.attr( "id" );
							};

							this.setType = function( type )
							{
								_element.attr( "type", type );
							};
							this.getType = function()
							{
								return _element.attr( "type" );
							};

							construct();
						}

						Input.prototype = new _ns.core.controller.event.EventDispatcher();
						Input.prototype.constructor = Input;

						return new Input();
					};

					this.Selector = function( element )
					{
						function Selector()
						{
							var _this = this;
							this.model;

							/* DISPLAY OBJECTS */
							var _element;
							var _input;

							var construct = function()
							{
								_this.setElement( $( "<div></div>" ) );
								_input = new _ns.components.form.input.Input();

								_this.model = new _ns.components.model.Model();

								_this.reset();
							};

							/* ACTIONS */
							this.reset = function()
							{
								_this.getElement().html("");

								_this.getElement().append( _input.getElement() );

								var modelElement = _this.model.getElement();
								_this.getElement().append( modelElement );

								_this.getElement().addClass( "Selector" );
							};

							/* SET AND GET */
							this.setElement = function( element )
							{
								_element = element;
							};
							this.getElement = function()
							{
								return _element;
							};

							this.setValue = function( value )
							{
								_input.setValue( value );
							};
							this.getValue = function()
							{
								return _input.getValue();
							};

							this.setName = function( name )
							{
								_input.setName( name );
							};
							this.getName = function()
							{
								return _input.getName();
							};

							this.getInput = function()
							{
								return _input;
							};

							construct();
						}

						Selector.prototype = new _ns.core.controller.event.EventDispatcher();
						Selector.prototype.constructor = Selector;

						return new Selector();
					};

					this.SwitchSelector = function( element )
					{
						function SwitchSelector()
						{
							var _this = this;
							var _super = this.constructor.prototype;

							var construct = function()
							{
								_this.reset();
							};

							/* ACTIONS */
							this.reset = function()
							{
								_super.reset();

								_this.getElement().find( ".Model" ).addClass( "clearfix" );

								_this.getElement().find( ".ValueObject" ).each(function()
									{
										$(this).addClass( "Button" );
										$(this).append( "<div class='Symbol'></div>" );
										$(this).off( "click" );
										$(this).on( "click", onSelect );
									});

								_this.getElement().removeClass( "Selector" );
								_this.getElement().addClass( "SwitchSelector" );

								_this.setValue(null);
							};

							this.select = function( value )
							{
								_this.setValue( value );
							};

							/* SET AND GET */
							this.setValue = function( value )
							{
								_super.setValue( value );

								_this.getElement().attr( "dataValue", value );

								_this.getElement().find( "div.ValueObject" ).each(function()
									{
										$(this).removeClass( "Deselected" );
										$(this).removeClass( "Selected" );

										if( $(this).attr("dataValue") == value) $(this).addClass( "Selected" );
										else $(this).addClass( "Deselected" );
									});
							};

							/* EVENT HANDLERS */
							var onSelect = function( e )
							{
								e.preventDefault();
								
								var newValue = $( e.currentTarget ).attr( "dataValue" );

								if( _this.getValue() != newValue )
								{
									_this.select( newValue  );

									_this.dispatchEvent( new _ns.core.controller.event.Event( _ns.components.controller.event.EventTypes.CHANGE, _this.getValue() ) );
								}
							};

							construct();
						}

						SwitchSelector.prototype = new _ns.components.form.input.Selector( element );
						SwitchSelector.prototype.constructor = SwitchSelector;

						return new SwitchSelector();
					};

					this.SingleSelector = function( element )
					{
						function SingleSelector()
						{
							var _this = this;
							var _super = this.constructor.prototype;
							this.autoUpdateLabel = true;
							this.button;
							var _label = "";
							var _expanded = false;

							var construct = function()
							{
								$( document ).mouseup( onClickOutside );

								_this.reset();
							};

							/* ACTIONS */
							this.reset = function()
							{
								_super.reset();

								_this.button = new _ns.components.buttons.Button();
								_this.button.setLabel( _label );
								_this.button.getElement().on( "click", _this.toggle );
								_this.getElement().append( _this.button.getElement() );

								_this.getElement().find( ".ValueObject" ).each(function()
									{
										$(this).addClass( "Button" );
										$(this).append( "<div class='Symbol'></div>" );
										$(this).off( "click" );
										$(this).on( "click", onSelect );
										if( $(this).attr("dataValue") == _this.getValue()) $(this).addClass( "Selected" );
										else $(this).addClass( "Deselected" );
									});

								_this.getElement().off( "mouseup" );
								_this.getElement().on( "mouseup", onClickInside );
								_this.getElement().find( ".Model .Proxy" ).off( "mouseup" );
								_this.getElement().find( ".Model .Proxy" ).on( "mouseup", onClickInside );

								_this.getElement().find( ".Model" ).append( "<div class='Symbol'></div>" );
								_this.getElement().find( ".Model" ).off( "mouseup" );
								_this.getElement().find( ".Model" ).on( "mouseup", onClickOutside );

								_this.getElement().addClass( "SingleSelector" );

								_this.collapse();
							};

							this.select = function( name, value )
							{
								_this.setLabel( name );
								_this.setValue( value );
							};

							this.toggle = function()
							{
								if(_expanded) _this.collapse();
								else _this.expand();
							};

							this.expand = function()
							{
								_this.getElement().removeClass( "Collapsed" );
								_this.getElement().addClass( "Expanded" );

								_expanded = true;

								_this.dispatchEvent( new _ns.core.controller.event.Event( _ns.components.controller.event.EventTypes.EXPAND ) );
							};

							this.collapse = function()
							{
								_this.getElement().addClass( "Collapsed" );
								_this.getElement().removeClass( "Expanded" );

								_expanded = false;

								_this.dispatchEvent( new _ns.core.controller.event.Event( _ns.components.controller.event.EventTypes.COLLAPSE ) );
							};

							/* METHODS */
							this.isExpanded = function()
							{
								return _expanded;
							};

							/* SET AND GET */
							this.setValue = function( value )
							{
								_super.setValue( value );

								_this.getElement().find( "div.ValueObject" ).each(function()
									{
										$(this).removeClass( "Deselected" );
										$(this).removeClass( "Selected" );

										if( $(this).attr("dataValue") == value) $(this).addClass( "Selected" );
										else $(this).addClass( "Deselected" );
									});
							};

							this.setLabel = function( label )
							{
								_label = label;

								if(_this.autoUpdateLabel) _this.button.setLabel( label );
							};
							this.getLabel = function()
							{
								if( _this.button ) return _this.button.getLabel();
							};

							/* EVENT HANDLERS */
							var onClickInside = function( e )
							{
								e.stopPropagation();
							};

							var onClickOutside = function( e )
							{
								_this.collapse();
							};

							var onSelect = function( e )
							{
								e.preventDefault();

								var newValue = $( e.currentTarget ).attr( "dataValue" );

								if( _this.getValue() != newValue )
								{
									var newName = $( e.currentTarget ).attr( "dataName" );

									_this.select( newName, newValue  );

									_this.dispatchEvent( new _ns.core.controller.event.Event( _ns.components.controller.event.EventTypes.CHANGE, _this.getValue() ) );
								}

								_this.collapse();
							};

							construct();
						}

						SingleSelector.prototype = new _ns.components.form.input.Selector( element );
						SingleSelector.prototype.constructor = SingleSelector;

						return new SingleSelector();
					};

					
					this.MultiSelector = function( element )
					{
						function MultiSelector()
						{
							var _this = this;
							var _super = this.constructor.prototype;
							var _modelFiltered;
							this.autoUpdateLabel = false;

							var construct = function()
							{
								_this.reset();
							};

							/* ACTIONS */
							this.reset = function()
							{
								_super.reset();

								_modelFiltered = new _ns.components.model.Model();

								_this.getElement().find( ".ValueObject" ).each(function()
									{
										$(this).off( "click" );
										$(this).on( "click", onSelect );
									});

								_this.getElement().addClass( "MultiSelector" );
							};

							this.select = function( proxyName, name, value )
							{
								_this.addValue( proxyName, name, value );

								/**
								*	TODO: Set value of the hidden input to JSON-object?
								*/
								var v = "";
								var i = 0;

								for(var pk in _modelFiltered.getMap())
								{
									var proxy = _modelFiltered.getProxy( pk );
									for(var vok in proxy.getMap())
									{
										if(i > 0) v += ",";
										v += proxy.get( vok ).getValue();
										i++;
									}
								}

								_this.getInput().attr( "value", v );
								_this.getElement().attr( "dataValue", v );
							};

							this.addValue = function( proxyName, name, value )
							{
								var proxy = _modelFiltered.getProxy( proxyName );
								if( !proxy ) proxy = new _ns.components.model.proxy.Proxy( proxyName );

								if( proxy.has( value ) )
								{
									proxy.remove( value );
								}
								else
								{
									proxy.addVO( name, value );
								}

								_this.getElement().find( "div.ValueObject" ).each(function()
									{
										if( $(this).attr( "dataProxyName" ) == proxy.getName() )
										{
											$(this).removeClass( "Deselected" );
											$(this).removeClass( "Selected" );

											if( proxy.has( $(this).attr("dataValue") ) ) $(this).addClass( "Selected" );
											else $(this).addClass( "Deselected" );
										}
									});

								_modelFiltered.addProxy( proxy );

								return proxy;
							};

							/* SET AND GET */
							this.getFilteredModel = function()
							{
								return _modelFiltered;
							};

							this.setLabel = function( label )
							{
								_this.button.setLabel( label );
							};

							/* EVENT HANDLERS */

							var onSelect = function( e )
							{
								e.preventDefault();

								_this.select( $( e.currentTarget ).attr( "dataProxyName" ), $( e.currentTarget ).attr( "dataName" ) , $( e.currentTarget ).attr( "dataValue" )  );

								_this.dispatchEvent( new _ns.core.controller.event.Event( _ns.components.controller.event.EventTypes.CHANGE, _this.getValue() ) );
							};

							construct();
						}

						MultiSelector.prototype = new _ns.components.form.input.SingleSelector( element );
						MultiSelector.prototype.constructor = MultiSelector;

						return new MultiSelector();
					};

					this.FilterMultiSelector = function( element )
					{
						function FilterMultiSelector()
						{
							var _this = this;
							var _super = this.constructor.prototype;
							var _filter;
							var _tagsContainer;
							var _blockFilterEvents = false;

							var construct = function()
							{
								_this.reset();
							};

							/* ACTIONS */
							var adjustUI = function()
							{
								_tagsContainer.find( ".ValueObject" ).remove();

								for(var pk in _this.getFilteredModel().getMap())
								{
									for(var vok in _this.getFilteredModel().getProxy( pk ).getMap())
									{
										var selectedVOElement = _this.getFilteredModel().getProxy( pk ).getMap()[ vok ].getElement();
										selectedVOElement.addClass( "Button" );
										selectedVOElement.attr( "dataProxyName", pk );
										selectedVOElement.append( "<div class='Symbol'></div>" );
										selectedVOElement.off( "click" );
										selectedVOElement.on( "click", onSelect );
										_filter.before( selectedVOElement );
									}
								}

								if( _tagsContainer.find( ".ValueObject" ).length != 0 ) _this.button.label.addClass( "tuto-hidden" );

								if(_this.isExpanded()) _this.getElement().find(".Model").css( "top", (_this.getElement().height() - 12) + "px" );

								// _this.resetFilter();
							};

							this.resetFilter = function()
							{
								_blockFilterEvents = true;

								_filter.val("");

								// _this.collapse();

								_this.filter(null);

								_blockFilterEvents = false;
							};

							this.reset = function()
							{
								_super.reset();

								_tagsContainer = $( "<div class='TagsContainer'></div>" );

								_filter = $( "<input type='text' autocomplete='off' />" );
								_filter.addClass( "TextBox" );
								_filter.on( "input", onFilter );
								_filter.on( "focus", onFocusIn );
								_filter.on( "focusout", onFocusOut );
								_filter.on( "keyup", onKeyUp );
								_tagsContainer.append( _filter );

								_this.button.element.addClass("clearfix");
								_this.button.element.addClass("filterButton");
								_this.button.getElement().append( _tagsContainer );
								_this.button.getElement().off( "click" );
								_this.button.getElement().on( "click", onClick );

								_this.getElement().find( ".ValueObject" ).each(function()
									{
										$(this).off( "click" );
										$(this).on( "click", onSelect );
									});

								_this.getElement().addClass( "TagMultiSelector" );
							};

							this.select = function( proxyName, name, value )
							{
								_super.select( proxyName, name, value );

								adjustUI();
							};

							this.customSelect = function()
							{
								if(_filter.val() && _filter.val().length && !_this.getFilteredModel().getProxyByValue( _filter.val() ))
								{
									_this.select( "", _filter.val(), _filter.val()  );

									_filter.val( "" );
									_this.filter( null );

									_this.dispatchEvent( new _ns.core.controller.event.Event( _ns.components.controller.event.EventTypes.CHANGE, _this.getValue() ) );
								}
								else
								{
									_filter.val("");
								}
							};

							this.filter = function( string )
							{
								var modelElement = _this.getElement().find(".Model");
								var totalHits = 0;

								if(string) string = string.toLowerCase();
								
								modelElement.find( ".Proxy" ).each( function()
									{
										var voProxy = $(this);
										voProxy.removeClass( "tuto-hidden" );
										var filterFoundInProxy = false;
										voProxy.find( ".ValueObject" ).each( function()
											{
												var vo = $(this);
												vo.removeClass( "tuto-hidden" );
												vo.find( ".Name" ).html( vo.attr("dataName") );
												var name = vo.attr("dataName").toLowerCase();
												var value = vo.attr("dataValue");
												if( !string || name.indexOf( string ) > -1 || value.indexOf( string )  > -1 )
												{
													filterFoundInProxy = true;
													totalHits++;

													var index = name.indexOf( string );
													if ( index >= 0 )
													{
														var innerHTML = vo.attr("dataName");
														var innerHTML = innerHTML.substring(0,index) + "<span class='highlight'>" + innerHTML.substring(index,index+string.length) + "</span>" + innerHTML.substring(index + string.length);
														vo.find( ".Name" ).html( innerHTML );
													}
												}
												else if( string && string.length > 0 )
												{
													vo.addClass( "tuto-hidden" );
												}
											} );
										if( !filterFoundInProxy && string && string.length > 0 ) voProxy.addClass( "tuto-hidden" );
									} );

								if( totalHits > 0 && !_this.isExpanded() ) _this.expand();
								else if( totalHits == 0 && _this.isExpanded() ) _this.collapse();
							};

							_this.expand = function()
							{
								_this.getElement().find(".Model").css( "top", (_this.getElement().height() - 12) + "px" );

								_super.expand();
							};

							_this.collapse = function()
							{
								_super.collapse();
							};

							/* SET AND GET */
							this.setLabel = function( label )
							{
								_this.button.setLabel( label );
							};

							/* EVENT HANDLERS */
							var onClick = function( e )
							{
								if( !_filter.is( ":focus" ) ) _filter.focus();

								if( !_this.isExpanded() ) _this.expand();
							};

							var onFocusIn = function( e )
							{
								if(_blockFilterEvents) return;

								// if( !_this.isExpanded() ) _this.expand();

								_this.getElement().addClass("Focus");

								_this.button.label.addClass( "tuto-hidden" );
							};

							var onFocusOut = function( e )
							{
								if(_blockFilterEvents) return;

								_this.getElement().removeClass("Focus");

								if( _tagsContainer.find( ".ValueObject" ).length == 0 ) _this.button.label.removeClass( "tuto-hidden" );

								// if( _this.isExpanded() ) _this.collapse();
							};

							var onSelect = function( e )
							{
								e.preventDefault();

								_this.select( $( e.currentTarget ).attr( "dataProxyName" ), $( e.currentTarget ).attr( "dataName" ) , $( e.currentTarget ).attr( "dataValue" )  );

								_this.dispatchEvent( new _ns.core.controller.event.Event( _ns.components.controller.event.EventTypes.CHANGE, _this.getValue() ) );

								_filter.val( "" );
	    						_this.filter( null );
								_filter.focus();
							};

							var onFilter = function( e )
							{
								if(_blockFilterEvents) return;

								_this.filter( _filter.val() );
							};

							var onKeyUp = function( e )
							{
								if(_blockFilterEvents) return;

								if(e.keyCode == 13)
	    						{
	    							// Tag input text
	    							_this.customSelect();
	    						}
	    						else if( e.keyCode == 27 )
	    						{
	    							_filter.val( "" );
	    							_this.filter( "" );
	    							_filter.blur();
	    							_this.collapse();
	    						}
							};

							construct();
						}

						FilterMultiSelector.prototype = new _ns.components.form.input.MultiSelector( element );
						FilterMultiSelector.prototype.constructor = FilterMultiSelector;

						return new FilterMultiSelector();
					};

				// end tuto.components.form.input
				};
			// end tuto.components.form
			};
		// end tuto.components
		};

		// init();
	// end tuto
	};
});