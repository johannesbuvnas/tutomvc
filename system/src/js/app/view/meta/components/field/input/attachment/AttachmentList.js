define(
[
	"tutomvc",
	"jquery",
	"app/view/meta/components/field/input/attachment/AttachmentItem"
],
function( tutomvc, $, AttachmentItem )
{
	function AttachmentList( attributes )
	{
		var _this = this;
		var _attributes = $.extend( {}, {title:"", maxCardinality:-1, filter:[], buttonTitle:"Select"}, attributes );
		var _name = "";

		/* DISPLAY OBJECTS */
		var _element;
		var _inputProxy;
		var _addButton;
		var _wpMedia;

		var construct = function()
		{
			draw();
			adjustButton();
		};

		var draw = function()
		{
			_element = $( "<div class='AttachmentList cf'></div>" );

			_inputProxy = $( "<div class='HiddenElement'></div>" );
			_element.append( _element );

			_addButton = new tutomvc.components.buttons.Button();
			_addButton.getElement().addClass( "AddButton" );
			_addButton.getElement().on( "click", onOpenClick );
			_element.append( _addButton.getElement() );

			_this.setElement( _element );

			_wpMedia = wp.media({
			    title: _attributes.title ? _attributes.title : "",
			    multiple: _attributes.maxCardinality < 0 || _attributes.maxCardinality > 1 ? true : false,
			    library: { type: _attributes.filter ? _attributes.filter : "" },
			    button : { text : _attributes.buttonTitle ? _attributes.buttonTitle : "Select" },
			    frame: 'select'
			});

			_wpMedia.on( 'select', onSelectAttachment );
		};

		var adjustButton = function()
		{
			if( _attributes.hasOwnProperty( "maxCardinality" ) )
			{
				if(_element.find( ".AttachmentItem" ).length >= _attributes.maxCardinality && _attributes.maxCardinality >= 0) _addButton.getElement().addClass( "HiddenElement" );
				else _addButton.getElement().removeClass( "HiddenElement" );
			}
			else
			{
				_addButton.getElement().removeClass( "HiddenElement" );
			}
		};

		/* METHODS */
		var addAttachment = function( id, title, thumbnailURL, iconURL )
		{
			if( _attributes.hasOwnProperty( "maxCardinality" ) )
			{
				if(_element.find( ".AttachmentItem" ).length >= _attributes.maxCardinality && _attributes.maxCardinality >= 0) return false;
			}

			var attachment = new AttachmentItem( id, title, thumbnailURL, iconURL );
			attachment.input.setName( _name + "[]" );
			attachment.addEventListener( "remove", adjustButton );

			_addButton.getElement().before( attachment.getElement() );

			adjustButton();

			return true;
		};

		/* SET AND GET */
		this.setValue = function( value )
		{
			if( !value )
			{
				_element.find( ".AttachmentItem" ).each(function()
					{
						$(this).remove();
					});
			}
			else
			{
				for(var key in value)
				{
					var attachment = value[key];

					addAttachment( attachment.id, attachment.title, attachment.thumbnailURL, attachment.iconURL );
				}
			}

			adjustButton();
		};

		this.setName = function( name )
		{
			_name = name;

			_element.find( "input" ).each(function()
				{
					$(this).attr( "name", _name + "[]" );
				});
		};
		this.getName = function()
		{
			return _name;
		};

		/* EVENT HANDLERS */
		var onOpenClick = function(e)
		{
			_wpMedia.open();
		};

		var onSelectAttachment = function()
		{
			var selection = _wpMedia.state().get('selection');

			selection.each(function(attachment)
			{
			    if(!addAttachment( attachment.id, attachment.attributes.filename, attachment.attributes.sizes ? attachment.attributes.sizes.thumbnail.url : null, attachment.attributes.icon )) return;
			});
		};

		construct();
	}

	return function( attributes )
	{
		AttachmentList.prototype = new tutomvc.components.form.input.Input();
		AttachmentList.prototype.constructor = AttachmentList;

		return new AttachmentList( attributes );
	}
});