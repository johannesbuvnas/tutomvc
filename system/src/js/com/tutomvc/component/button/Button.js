define([
	"jquery",
	"com/tutomvc/core/controller/event/EventDispatcher"
],
function( $, EventDispatcher )
{
	function Button( label )
	{
		var _this = this;
		this.element = $( '<div class="Button"></div>' );
		this.symbol = $( "<div class='Symbol'></div>" );
		this.element.append( _this.symbol );
		this.label = $( "<span class='Label'></span>" );
		this.element.append( _this.label );

		/* SET AND GET */
		this.setLabel = function( label )
		{
			_this.label.html( label );
		};
		this.getLabel = function()
		{
			return _this.label.html();
		};

		this.getElement = function()
		{
			return _this.element;
		};

		_this.setLabel( label );
	}

	return EventDispatcher.extend( Button );
});