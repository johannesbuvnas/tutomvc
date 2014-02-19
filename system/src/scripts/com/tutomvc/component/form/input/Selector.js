define([
	"jquery",
	"com/tutomvc/core/controller/event/EventDispatcher",
	"com/tutomvc/component/form/input/Input",
	"com/tutomvc/component/model/Model",
],
function($, EventDispatcher, Input, Model)
{
	"use strict";
	function Selector()
	{
		"use strict";
		var _this = this;
		this.model;

		/* DISPLAY OBJECTS */
		var _element;
		var _input;

		var construct = function()
		{
			_this.super();
			
			_this.setElement( $( "<div></div>" ) );
			_input = new Input();

			_this.model = new Model();

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
		this.setID = function( id )
		{
			_input.setID( id );
		};
		this.getID = function()
		{
			return _input.getID();
		};

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

	return Selector.extends( EventDispatcher );
});