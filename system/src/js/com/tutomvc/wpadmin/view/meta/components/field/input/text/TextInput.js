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
		TextInput.prototype = new Input();
		TextInput.prototype.constructor = TextInput;

		return new TextInput( value );
	};
});