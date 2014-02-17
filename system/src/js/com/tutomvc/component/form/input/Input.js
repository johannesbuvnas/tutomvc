define([
	"jquery",
	"com/tutomvc/core/controller/event/EventDispatcher"
],
function($, EventDispatcher)
{
	function Input()
	{
		var _this = this;

		/* DISPLAY OBJECTS */
		var _element;

		var construct = function()
		{
			_this.super();
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

	return Input.extends( EventDispatcher );
});