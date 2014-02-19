define([
	"com/tutomvc/core/controller/event/EventDispatcher",
	"com/tutomvc/core/controller/event/Event",
	"base64",
	"com/tutomvc/wpadmin/view/meta/components/field/input/MetaFieldInput"
],
function( EventDispatcher, Event, Base64, MetaFieldInput )
{
	function MetaField( metaBoxID, element )
	{
		/* VARS */
		var _this = this;
		var _metaBoxID = metaBoxID;
		var _id;
		var _attributes;

		/* DISPLAY OBJECTS */
		var _element = element;
		var _label;
		var _inputComponent;

		var construct = function()
		{
			_this.super();
			
			_attributes = JSON.parse( decodeURIComponent( _element.find(".JSON").html() ) );
			if (typeof _attributes.value == 'string' || _attributes.value instanceof String) _attributes.value = Base64.decode( _attributes.value );

			_attributes.id = _id = _attributes.name + "_" + _metaBoxID;

			draw();
		};

		var draw = function()
		{
			_label = _element.find( "label" );
			_label.attr( "for", _id );

			_inputComponent = new MetaFieldInput( _attributes );

			if(_inputComponent)
			{
				_inputComponent.setID( _id );
				_inputComponent.addEventListener( "change", _this.change );
				_element.append( _inputComponent.getElement() );
			}

			switch( _attributes.type.name )
			{
				case "selector_single":


					_label.remove();

				break;
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

			var event = new Event( "change", { metaFieldName : _attributes.name, value : _inputComponent.getValue() } );
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

		/* METHODS */
		/* SET AND GET */
		this.getElement = function()
		{
			return _element;
		};
		this.getLabelElement = function()
		{
			return _label;
		};

		this.getName = function()
		{
			return _attributes.name;
		};

		/**
		*	Set and get meta key.
		*/
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

	return MetaField.extends( EventDispatcher );
});