define([
	"com/tutomvc/core/controller/event/EventDispatcher",
	"com/tutomvc/core/controller/event/Event",
	"jquery",
	"com/tutomvc/wpadmin/view/meta/components/field/MetaField"
],
function( EventDispatcher, Event, $, MetaField )
{
	function MetaBox( id, element )
	{
		/* VARS */
		var _this = this;
		var _cardinalityID;
		var _name;
		var _metaFieldMap;
		var _id = id;

		/* DISPLAY OBJECTS */
		var _element = element;
		var _label;
		var _removeButton;

		var construct = function()
		{
			_this.super();
			
			_name = _element.attr( "data-meta-box-name" );
			_cardinalityID = parseInt( _element.attr( "data-cardinality-id" ) );
			draw();
		};

		var draw = function()
		{
			_metaFieldMap = [];
			_element.find( ".MetaField" ).each( function()
			{
				var input = new MetaField( _name + "_" + _this.getID(), $( this ) );
				input.on( "change", onMetaFieldChange );
				_metaFieldMap.push( input );
			} );

			_label = _element.find( "div.title span.Label" );
			
			_removeButton = _element.find( ".RemoveMetaBoxButton" );
			// _removeButton.off( "click" );
			_removeButton.on( "click", onRemoveClick );

			_element.removeClass("Loading");
		};

		/* ACTIONS */
		this.reset = function()
		{
			$( _metaFieldMap ).each(function()
			{
				this.reset();
			});
		};

		this.change = function()
		{
			$( _metaFieldMap ).each(function()
			{
				this.change();
			});
		};

		/* SET AND GET */
		this.setLabel = function( label )
		{
			_label.html( label );
		};

		this.setCardinalityID = function( cardinalityID )
		{
			_cardinalityID = cardinalityID;

			_this.setLabel( "No. " + (_cardinalityID + 1) );

			var metaKey;
			$( _metaFieldMap ).each(function()
			{
				// [metaBoxName]_[cardinalityID]_[metaFieldName]
				metaKey = _name + "_" + _cardinalityID + "_" + this.model.get("metaFieldName");
				this.model.set( {name : metaKey, name : metaKey} );
			});
		};
		this.getCardinalityID = function()
		{
			return _cardinalityID;
		};

		this.getElement = function()
		{
			return _element;
		};

		this.getID = function()
		{
			return _id;
		};

		/* EVENT HANDLERS */
		var onRemoveClick = function(e)
		{
			e.preventDefault();

			_this.dispatchEvent( new Event( "remove", { id : _this.getID() } ) );
		};

		var onMetaFieldChange = function( metaFieldModel )
		{
			$( _metaFieldMap ).each(function()
			{
				this.metaBoxChange( _name, metaFieldModel.get("metaFieldName"), metaFieldModel.get("value") );
			});

			var event = new Event( "change", { metaFieldName : this.model.get("metaFieldName"), value : this.model.get("value") } );
			_this.dispatchEvent( event );
		};

		construct();
	}

	return MetaBox.extends( EventDispatcher );
});