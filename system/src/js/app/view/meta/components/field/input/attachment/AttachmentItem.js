define([
	"tutomvc",
	"jquery"
],
function( tutomvc, $ )
{
	function AttachmentItem( id, title, thumbnailURL, iconURL, editURL )
	{
		/* VARS */
		var _this = this;
		var _id = id;
		var _title = title;
		var _thumbnailURL = thumbnailURL;
		var _iconURL = iconURL;
		var _editURL = editURL;

		/* DISPLAY OBJECTS */
		var _element;
		this.input;
		var _removeButton;

		var construct = function()
		{
			draw();
		};

		var draw = function()
		{
			_element = $( "<div class='AttachmentItem'></div>" );

			_this.input = new tutomvc.components.form.input.Input();
			_this.input.setValue( _id );
			_element.append( _this.input.getElement() );

			if(_thumbnailURL) _element.append( "<a href='"+_editURL+"'><img src='" + _thumbnailURL + "' /></a>" );
			else if(_iconURL) _element.append( "<a href='"+_editURL+"'><img src='" + _iconURL + "' class='Icon' /></a>" );

			if(_title) _element.append( "<div class='AttachmentTitle'><span>" + _title + "</span></div>" );

			_removeButton = new tutomvc.components.buttons.Button();
			_removeButton.getElement().addClass( "RemoveButton" );
			_removeButton.getElement().addClass( "HiddenElement" );
			_removeButton.getElement().on( "click", onRemove );
			_element.append( _removeButton.getElement() );

			_element.on( "mouseover", onMouseOver );
			_element.on( "mouseout", onMouseOut );
		};

		/* SET AND GET */
		this.getElement = function()
		{
			return _element;
		};

		/* EVENT HANDLERS */
		var onMouseOver = function()
		{
			_removeButton.getElement().removeClass( "HiddenElement" );
		};

		var onMouseOut = function()
		{
			_removeButton.getElement().addClass( "HiddenElement" );
		};

		var onRemove = function()
		{
			_element.remove();

			_this.dispatchEvent( new tutomvc.core.controller.event.Event( "remove" ) );
		};

		construct();
	}

	return function( id, title, thumbnailURL, iconURL, editURL )
	{
		AttachmentItem.prototype = new tutomvc.core.controller.event.EventDispatcher();
		AttachmentItem.prototype.constructor = AttachmentItem;

		return new AttachmentItem( id, title, thumbnailURL, iconURL, editURL );
	}
});