define([
	"jquery"
],
function( $ )
{
	function ValueObject( name, value )
	{
		var _this = this;
		var _value = value ? value : name;
		var _name = name;

		/* SET AND GET */
		this.setValue = function( value )
		{
			_value = value;
		};
		this.getValue = function()
		{
			return _value;
		};

		this.setName = function( name )
		{
			_name = name;
		};
		this.getName = function()
		{
			return _name;
		};

		this.getElement = function()
		{
			var element = $( '<div class="ValueObject"></div>' );
			element.append( "<span class='Name'>" + _this.getName() + "</div>" );
			element.attr( "dataValue", _this.getValue() );
			element.attr( "dataName", _this.getName() );

			return element;
		};
	};

	return ValueObject;
});