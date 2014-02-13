define([
	"jquery",
	"com/tutomvc/component/form/input/Selector",
	"com/tutomvc/component/button/Button",
	"com/tutomvc/core/controller/event/Event"
],
function($, Selector, Button, Event)
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

			_this.button = new Button();
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

			_this.dispatchEvent( new Event( "expand" ) );
		};

		this.collapse = function()
		{
			_this.getElement().addClass( "Collapsed" );
			_this.getElement().removeClass( "Expanded" );

			_expanded = false;

			_this.dispatchEvent( new Event( "collapse" ) );
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

				_this.dispatchEvent( new Event( "change", _this.getValue() ) );
			}

			_this.collapse();
		};

		construct();
	}

	SingleSelector.extend = function( parentClass )
	{
		parentClass.prototype = new SingleSelector();
		parentClass.prototype.constructor = parentClass;

		return parentClass;
	};

	return Selector.extend( SingleSelector );
});