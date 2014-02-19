define([
	"com/tutomvc/component/form/input/Input",
	"jquery"
],
function( Input, $ )
{
	function TextInput( value )
	{
		/* VARS */
		var _this = this;
		var _value = value ? value : "";

		var construct = function()
		{
			_this.super();
			draw();
		};

		var draw = function()
		{
			_this.setType( "text" );
			_this.setValue( _value );
		};

		/* ACTIONCS */
		/* SET AND GET */
		/* EVENT HANDLERS */

		construct();
	}

	return TextInput.extends( Input );
});