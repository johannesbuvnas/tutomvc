define([
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