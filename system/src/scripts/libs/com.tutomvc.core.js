

Function.prototype.superOf = function( child )
{
	var parent = this;
	if(parent && child)
	{
		var superArgs = [];
		var evalSuperArgs = "";
		for(var i = 0; i < parent.length; i++) superArgs.push( "arg" + i );
		if(superArgs.length) evalSuperArgs += ", " + superArgs.join(",");
		
		// If super constructor isnt called
		// var regex = /[^{](this.super)[^}]/;
		// if(!regex.test( child.toString() ))
		// {
		// 	var oldChild = child;
		// 	var childArgs = [];
		// 	var evalChildArgs = "";
		// 	for(var i = 0; i < parent.length; i++) childArgs.push( "arg" + i );
		// 	if(childArgs.length) evalChildArgs += ", " + childArgs.join(",");
		// 	eval( 'child.prototype = function(' + childArgs.join(",") + '){this.super();oldChild.call( this ' + evalChildArgs + ' );};' );
		// }
		eval( 'child.prototype.super=function(' + superArgs.join(",") + '){var _this=this;this.super = new function(){};if(parent.prototype.super) this.super.super = parent.prototype.super;eval("parent.call( _this.super' + evalSuperArgs + ' );");for(var p in _this.super) if(!(p in _this)) _this[p]=_this.super[p];};' );
		// eval( 'child.prototype.super=function(' + superArgs.join(",") + '){if(parent.prototype.super) this.super = parent.prototype.super;eval("parent.call( this' + evalSuperArgs + ' );");};' );
		// eval( 'child.prototype.super=function(' + superArgs.join(",") + '){if(parent.prototype.super) this.super = parent.prototype.super;eval("parent.call( this' + evalSuperArgs + ' );");var obj = {};for(var p in this) obj[p] = this[p];return obj;};' );
	}

	return child ? child : parent;
};
Function.prototype.extends = function( parent )
{
	return parent.superOf( this );
};
define("com/tutomvc/core/Function.helpers", function(){});

define('com/tutomvc/core/model/Model',[],function()
{
	/* CLASS */
	function Model( facadeKey )
	{
		/* VARS */
		var _this = this;
		var _facadefacadeKey;
		var _proxyMap = [];

		/* METHODS */
		this.registerProxy = function( proxy )
		{
			proxy.initializeFacadefacadeKey( _this.getFacadefacadeKey() );
			_proxyMap[ proxy.getName() ] = proxy;
			proxy.onRegister();

			return proxy;
		};

		this.retrieveProxy = function( proxyName )
		{
			return _proxyMap[ proxyName ];
		};

		/* SET AND GET */
		this.getFacadefacadeKey = function()
		{
			return _facadefacadeKey;
		};

		// Initiate
		(function( facadeKey )
		{
			_facadefacadeKey = facadeKey;
			Model.instanceMap[ _facadefacadeKey ] = _this;
		})( facadeKey );
	}

	/* STATIC REFERENCES */
	Model.instanceMap = [];
	Model.getInstance = function( facadeKey )
	{
		if( !facadeKey ) return console.log( "Model::getInstance - no facadeKey" );

		if( Model.instanceMap[ facadeKey ] ) 
		{
			return Model.instanceMap[ facadeKey ];
		}
		else
		{
			return new Model( facadeKey );
		}

		return undefined;
	};

	return Model;
});
define('com/tutomvc/core/controller/event/EventDispatcher',[],function()
{
	function EventDispatcher()
	{
		/* VARS */
		var _this = this;
		var _listenerMap = [];

		/* ACTIONS */
		this.dispatch = this.dispatchEvent = function( event )
		{
			// if( !(event instanceof _ns.core.controller.event.Event) ) return console.log( "EventDispatcher::dispatch - event isnt an instance of tutomvc.core.controller.event.Event" );

			if( _listenerMap[ event.getName() ] )
			{
				for(var k in _listenerMap[ event.getName() ])
				{
					var eventListenerCallback = _listenerMap[ event.getName() ][ k ];
					eventListenerCallback( event );
				}
			}
		};

		this.addListener = this.addEventListener = function( eventName, callback )
		{
			if( !_listenerMap[ eventName ] ) _listenerMap[ eventName ] = [];

			_listenerMap[ eventName ].push( callback );

			return true;
		};

		this.removeListener = this.removeEventListener = function( eventName, callback )
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
	}

	return EventDispatcher;
});
define('com/tutomvc/core/view/View',[
	"com/tutomvc/core/controller/event/EventDispatcher"
],
function( EventDispatcher )
{
	/* CLASS */
	function View( facadeKey )
	{
		/* VARS */
		var _this = this;
		var _facadeKey = facadeKey;
		var _mediatorMap = [];
		var _viewComponentMap = [];

		/* ACTIONS */

		/* METHODS */
		this.registerMediator = function( viewComponent, mediator )
		{
			// if( !(mediator instanceof _ns.core.view.mediator.Mediator) ) return console.log( "View::registerMediator - mediator doesn't extend Mediator" );

			_viewComponentMap[ mediator.getName() ] = viewComponent;

			mediator.initializeFacadeKey( _this.getFacadeKey() );
			_mediatorMap[ mediator.getName() ] = mediator;
			mediator.onRegister();

			return mediator;
		};
		this.retrieveMediator = function( name )
		{
			return _mediatorMap[ name ];
		};

		this.retrieveViewComponent = function( mediatorName )
		{
			return _viewComponentMap[ mediatorName ];
		};

		/* SET AND GET */
		this.getFacadeKey = function()
		{
			return _facadeKey;
		};

		// Initiate
		(function( facadeKey )
		{
			_this.super();
			_facadeKey = facadeKey;
			View.instanceMap[ _facadeKey ] = _this;
		})( facadeKey );
	}
	View.extends( EventDispatcher );

	/* STATIC */
	ClassConstructor.instanceMap = View.instanceMap = [];
	ClassConstructor.getInstance = View.getInstance = function( facadeKey )
	{
		if( !facadeKey ) return console.log( "View::getInstance - no facadeKey" );

		if( View.instanceMap[ facadeKey ] ) 
		{
			return View.instanceMap[ facadeKey ];
		}
		else
		{
			return new View( facadeKey );
		}

		return null;
	};

	function ClassConstructor( facadeKey )
	{
		return View.getInstance( facadeKey );
	}

	return ClassConstructor;
});
define('com/tutomvc/core/controller/Controller',[
	"com/tutomvc/core/view/View"
],
function( View )
{
	function Controller( facadeKey  )
	{
		/* VARS */
		var _this = this;
		var _facadeKey;
		var _commandMap = [];
		var _view;

		/* ACTIONS */
		this.executeCommand = function( event )
		{
			if( _commandMap[ event.getName() ] ) 
			{
				var command = new _commandMap[ event.getName() ]();
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

		// Initiate
		(function( facadeKey )
		{
			_facadeKey = facadeKey;
			_view = View.getInstance( _facadeKey );

			Controller.instanceMap[ _facadeKey ] = _this;
		})( facadeKey );
	}

	/* STATICS */
	Controller.instanceMap = [];
	Controller.getInstance = function( facadeKey )
	{
		if( !facadeKey ) return console.log( "Controller::getInstance - no facadeKey" );

		if( Controller.instanceMap[ facadeKey ] ) 
		{
			return Controller.instanceMap[ facadeKey ];
		}
		else
		{
			return new Controller( facadeKey );
		}

		return null;
	};

	return Controller;
});
define('com/tutomvc/core/controller/event/Event',[],function()
{
	function Event( name, body )
	{
		/* VARS */
		var _this = this;
		this.name = name;
		this.body = body;

		/* SET AND GET */
		this.setName = function( name )
		{
			_this.name = name;
		};
		this.getName = function()
		{
			return _this.name;
		};

		this.setBody = function( body )
		{
			_this.body = body;
		};
		this.getBody = function()
		{
			return _this.body;
		};
	}

	return Event;
});
define('com/tutomvc/core/facade/Facade',[
	"com/tutomvc/core/model/Model",
	"com/tutomvc/core/view/View",
	"com/tutomvc/core/controller/Controller",
	"com/tutomvc/core/controller/event/Event"
],
function( Model, View, Controller, Event )
{
	/* CLASS */
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
			_this.model = Model.getInstance( _this.getKey() );
		};
		var initializeController = function()
		{
			_this.controller = Controller.getInstance( _this.getKey() );
		};
		var initializeView = function()
		{
			_this.view = View.getInstance( _this.getKey() );
		};

		/* ACTIONS */
		this.dispatch = function( name, body )
		{
			_this.view.dispatchEvent( new Event( name, body ) );
		};

		
		/* SET AND GET */
		this.getKey = function()
		{
			return _key;
		};

		/* EVENTS */
		this.onRegister = function()
		{
			console.log("Facade");
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
});
define('com/tutomvc/core/CoreClass',[
	"com/tutomvc/core/facade/Facade"
],
function( Facade )
{
	function CoreClass()
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
			return Facade.getInstance( _facadeKey );
		};

		/* EVENTS */
		this.onRegister = function()
		{
		};
	}

	return CoreClass;
});
define('com/tutomvc/core/controller/command/Command',[
	"com/tutomvc/core/CoreClass"
],
function( CoreClass )
{
	/* CLASS */
	function Command()
	{
		/* ACTIONS */
		this.execute = function( event )
		{
			
		};

		this.super();
	}

	return Command.extends( CoreClass );
});
define('com/tutomvc/core/view/mediator/Mediator',[
	"com/tutomvc/core/CoreClass",
	"com/tutomvc/core/controller/event/Event"
],
function( CoreClass, Event )
{
	/* CLASS */
	function Mediator( name )
	{
		/* VARS */
		var _this = this;
		var _name = name;
		var _viewComponent;


		/* ACTIONS */
		this.dispatch = function( name, body )
		{
			_this.getFacade().view.dispatchEvent( new Event( name, body ) );
		};

		/* METHODS */
		this.addEventListener = function( name, callback )
		{
			_this.getFacade().view.addEventListener( name, callback );
		};

		/* SET AND GET */
		this.getViewComponent = function()
		{
			return _this.getFacade().view.retrieveViewComponent( _this.getName() );
		};

		this.getName = function()
		{
			return _name;
		};

		/* EVENTS */
		this.onRegister = function()
		{
		};

		this.super();
	}

	return Mediator.extends( CoreClass );
});
define('com/tutomvc/core/model/proxy/Proxy',[
	"com/tutomvc/core/CoreClass"
],
function( CoreClass )
{
	function Proxy( name )
	{
		/* VARS */
		var _this = this;
		var _name = name;
		var _map = [];

		/* METHODS */
		this.add = function( item, key )
		{
			if(key) _map[ key ] = item;
			else _map.push( item );
		};

		this.has = function( key )
		{
			return _map[ key ] != undefined;
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

		this.super();
	}

	return Proxy.extends( CoreClass );
});