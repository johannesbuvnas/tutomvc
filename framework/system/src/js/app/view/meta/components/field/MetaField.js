define([
	"tuto",
	"app/view/meta/components/field/input/TextareaWYSIWYGInput",
	"base64",
	"app/view/meta/components/field/input/attachment/AttachmentList"
],
function( tuto, TextareaWYSIWYGInput, Base64, AttachmentList )
{
	function MetaField( metaBoxID, element )
	{
		/* VARS */
		var _this = this;
		var _metaBoxID = metaBoxID;
		var _attributes;

		/* DISPLAY OBJECTS */
		var _element = element;
		var _label;
		var _inputComponent;

		var construct = function()
		{
			_attributes = JSON.parse( decodeURIComponent( _element.find(".JSON").html() ) );
			if (typeof _attributes.value == 'string' || _attributes.value instanceof String) _attributes.value = Base64.decode( _attributes.value );

			draw();
		};

		var draw = function()
		{
			_label = _element.find( "label" );
			_label.attr( "for", _attributes.name + "_" + _metaBoxID );

			switch( _attributes.type.name )
			{
				case "textarea_wysiwyg":

					_inputComponent = new TextareaWYSIWYGInput( _attributes.value, _attributes.name + "_" + _metaBoxID, _attributes.type.settings );

				break;
				case "attachment":

					_inputComponent = new AttachmentList( _attributes.type.settings );
					_inputComponent.setName( _attributes.name );
					_inputComponent.setValue( _attributes.value );

				break;
				case "selector_single":

					_inputComponent = new tuto.components.form.input.SingleSelector();
					_inputComponent.setLabel( _attributes.title );
					_label.remove();

					var proxy = new tuto.components.model.proxy.Proxy();

					for(var key in _attributes.type.settings.options)
					{
						proxy.addVO( _attributes.type.settings.options[key], key );
						if(key == _attributes.value)
						{
							_inputComponent.setLabel( _attributes.type.settings.options[key] );
							_inputComponent.setValue( _attributes.value );
						}
					}

					_inputComponent.model.addProxy( proxy );
					_inputComponent.reset();

				break;
			}

			if(_inputComponent)
			{
				_inputComponent.addEventListener( "change", _this.change );
				_element.append( _inputComponent.getElement() );
			}
		};

		/* ACTIONS */
		this.reset = function()
		{
			if(_inputComponent) _inputComponent.setValue( null );
		};

		this.change = function()
		{
			if(!_inputComponent) return;

			var event = new tuto.core.controller.event.Event( "change", { name : _attributes.name, value : _inputComponent.getValue() } );
			_this.dispatchEvent( event );
		};

		this.metaBoxChange = function( metaBoxName, metaFieldName, value )
		{
			// console.log("Appearently", metaBoxName + "_" + metaFieldName, "has changed to", value);

			for(var key in _attributes.conditions)
			{
				var condition = _attributes.conditions[key];
				if(condition.metaBoxName == metaBoxName && condition.metaFieldName == metaFieldName)
				{
					if(condition.value == value)
					{
						if( condition.onElse ) _this[ condition.onMatch ]();
					}
					else
					{
						if( condition.onElse ) _this[ condition.onElse ]();
					}
				}
			}
		};

		this.show = function()
		{
			_element.removeClass( "HiddenElement" );
		};

		this.hide = function()
		{
			_element.addClass( "HiddenElement" );
		};

		/* SET AND GET */
		this.getName = function()
		{
			return _attributes.name;
		};

		this.setKey = function( key )
		{
			_attributes.key = key;

			if(_inputComponent) _inputComponent.setName( _attributes.key );
		};
		this.getKey = function()
		{
			return _attributes.key;
		};

		var focus = function(e)
		{
			
		};

		/* EVENT HANDLERS */
		

		construct();
	}

	return function( metaBoxID, element )
	{
		MetaField.prototype = new tuto.core.controller.event.EventDispatcher();
		MetaField.prototype.constructor = MetaField;

		return new MetaField( metaBoxID, element );
	}
});