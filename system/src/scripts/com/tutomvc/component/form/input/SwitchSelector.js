define([
	"jquery",
	"com/tutomvc/component/form/input/Selector"
	"com/tutomvc/core/controller/event/Event"
],
function( $, Selector, Event )
{
	function SwitchSelector()
	{
		var _this = this;

		var construct = function()
		{
			_this.super();
			_this.reset();
		};

		/* ACTIONS */
		this.reset = function()
		{
			_this.super.reset();

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
			_this.super.setValue( value );

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

				_this.dispatchEvent( new Event( "change" , _this.getValue() ) );
			}
		};

		construct();
	}

	return SwitchSelector.extends( Selector );
});