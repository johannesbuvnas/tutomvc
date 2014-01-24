define([
	"tutomvc",
	"jquery",
	"app/view/meta/components/field/MetaField"
],
function( tutomvc, $, MetaField )
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
				input.addEventListener( "change", onMetaFieldChange );
				_metaFieldMap.push( input );
			} );

			_label = _element.find( "div.title span.Label" );
			
			_removeButton = _element.find( ".RemoveMetaBoxButton" );
			// _removeButton.off( "click" );
			_removeButton.on( "click", onRemoveClick );
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

			$( _metaFieldMap ).each(function()
			{
				this.setKey( _name + "_" + _cardinalityID + "_" + this.getName() );
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

			_this.dispatchEvent( new tutomvc.core.controller.event.Event( "remove", { id : _this.getID() } ) );
		};

		var onMetaFieldChange = function(e)
		{
			$( _metaFieldMap ).each(function()
			{
				this.metaBoxChange( _name, e.getBody().metaFieldName, e.getBody().value );
			});

			_this.dispatchEvent( e );
		};

		construct();
	}

	
	return function( id, element )
	{
		MetaBox.prototype = new tutomvc.core.controller.event.EventDispatcher();
		MetaBox.prototype.constructor = MetaBox;

		return new MetaBox( id, element );
	};
});