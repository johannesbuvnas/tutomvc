define([
	"com/tutomvc/core/controller/event/EventDispatcher",
	"jquery"
],
function( EventDispatcher, $ )
{
	SortableComponent.CLASS_ELEMENT = "SortableElement";
	SortableComponent.CLASS_HANDLE = "SortableHandle";
	SortableComponent.CLASS_DUMMY = "SortableDummy";
	function SortableComponent( element, handleSelector )
	{
		/* VARS */
		var _this = this;
		var _element = $( element );
		var _handleSelector = handleSelector;
		var _currentItem;
		var _currentItemAttributes;
		var _dummy;

		var draw = function()
		{
			$(window).on( "mouseup", onMouseUp );
			$(window).on( "mousemove", onMouseMove );

			_this.update();
		};

		var drawDummy = function()
		{
			_dummy = $( _currentItem.get(0).outerHTML );
			_dummy.attr("id", "");
			_dummy.html("");
			_dummy.addClass( SortableComponent.CLASS_DUMMY );
			_dummy.css( "position", _currentItemAttributes.position );
			_dummy.css( "opacity", _currentItemAttributes.opacity );
			_dummy.css( "width", _currentItemAttributes.width );
			_dummy.css( "height", _currentItemAttributes.height );
			_dummy.css( "top", _currentItemAttributes.top );
			_dummy.css( "left", _currentItemAttributes.left );
			_dummy.css( "z-index", _currentItemAttributes.z );
			_dummy.css( "margin", _currentItemAttributes.margin );
			_dummy.css("border", "2px dashed #ebebeb");
			_currentItem.after(_dummy);

			// _currentItem.css( "top", e.clientY - _currentItemAttributes.offsetY );
			// _currentItem.css( "left", e.clientX - _currentItemAttributes.offsetX );
			_currentItem.css( "width", _currentItem.width() + "px" );
			_currentItem.css( "height", _currentItem.outerHeight() + "px" );
			_currentItem.css( "opacity", .5 );
			_currentItem.css( "z-index", 99 );
			_currentItem.css( "position", "fixed" );
			// _currentItem.css( "transform", "scale(.75, .75)" );
			_currentItem.css( "pointer-events", "none" );
		};

		var removeDummy = function()
		{
			if(_dummy)
			{
				_dummy.before( _currentItem );
				_dummy.remove();
				_dummy = undefined;
			}
		};

		this.update = function()
		{
			if(!handleSelector) return;

			_element.children().each(function()
				{
					if( !$(this).hasClass( SortableComponent.CLASS_ELEMENT ) )
					{
						if(!$(this).find( handleSelector ).addClass( SortableComponent.CLASS_HANDLE ).length)
						{
							if(!$(this).is( handleSelector )) return;
							else $(this).addClass( SortableComponent.CLASS_HANDLE );
						}

						$(this).on("mousedown", onMouseDown );
						$(this).on("mouseover", onMouseOver );
						$(this).addClass( SortableComponent.CLASS_ELEMENT );
					}
				});
		};

		/* SET AND GET */
		this.getElement = function()
		{
			return _element;
		};

		/* EVENTS */
		var onMouseDown = function(e)
		{
			if(!_currentItem && ($(e.target).hasClass( SortableComponent.CLASS_HANDLE ) || $(e.currentTarget).hasClass( SortableComponent.CLASS_HANDLE ) || !handleSelector))
			{
				_currentItem = $(e.currentTarget);
				_currentItemAttributes = {
					width : _currentItem.css("width"),
					height : _currentItem.css("height"),
					position : _currentItem.css("position"),
					opacity : _currentItem.css("opacity"),
					top : _currentItem.css("top"),
					left : _currentItem.css("left"),
					margin : _currentItem.css("margin"),
					z : _currentItem.css("z-index"),
					offsetX : e.offsetX,
					offsetY : e.offsetY,
					index : _currentItem.index(),
					style : _currentItem.attr("style"),
					transform : _currentItem.css("transform"),
					pointerEvents : _currentItem.css("pointer-events")
				};
			}
		};
		var onMouseMove = function(e)
		{
			if(_currentItem)
			{
				if(!_dummy) drawDummy();

				_currentItem.css( "top", e.clientY - _currentItemAttributes.offsetY );
				_currentItem.css( "left", e.clientX - _currentItemAttributes.offsetX );
				window.getSelection().removeAllRanges();
			}
		};
		var onMouseOver = function(e)
		{
			if(_currentItem)
			{
				if( _dummy.index() < $(e.currentTarget).index() )
				{
					$(e.currentTarget).after(_dummy);
				}
				else
				{
					$(e.currentTarget).before(_dummy);
				}
			}
		};
		var onMouseUp = function(e)
		{
			if(_currentItem)
			{
				removeDummy();

				_currentItem.css( "position", _currentItemAttributes.position );
				_currentItem.css( "opacity", _currentItemAttributes.opacity );
				_currentItem.css( "width", _currentItemAttributes.width );
				_currentItem.css( "height", _currentItemAttributes.height );
				_currentItem.css( "top", _currentItemAttributes.top );
				_currentItem.css( "left", _currentItemAttributes.left );
				_currentItem.css( "z-index", _currentItemAttributes.z );
				_currentItem.css( "pointer-events", _currentItemAttributes.pointerEvents );
				_currentItem.css( "transform", _currentItemAttributes.transform );
				_currentItem.attr( "style", _currentItemAttributes.style );
				_currentItem = undefined;
			}
		};

		// Constructor
		(function()
			{
				draw();
			})();
	}

	return EventDispatcher.extend( SortableComponent );
});