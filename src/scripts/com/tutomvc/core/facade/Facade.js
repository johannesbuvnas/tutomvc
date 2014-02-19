define([
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