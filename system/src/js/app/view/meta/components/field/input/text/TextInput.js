define([
	"tutomvc",
	"jquery"
],
function( tutomvc, $ )
{
	function TextInput( value )
	{
		/* VARS */
		var _this = this;
		var _super = this.constructor.prototype;
		var _value = value ? value : "";

		var construct = function()
		{
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

	return function( value )
	{
		TextInput.prototype = new tutomvc.components.form.input.Input();
		TextInput.prototype.constructor = TextInput;

		return new TextInput( value );
	};
});