define(
[
	"com/tutomvc/core/controller/event/EventDispatcher",
	"jquery",
	"com/tutomvc/wpadmin/view/meta/components/MetaBox",
	"com/tutomvc/wpadmin/view/components/SortableComponent"
],
function( EventDispatcher, $, MetaBox, SortableComponent )
{
	function MetaBoxProxy( element )
	{
		var _this = this;
		
		/* VARS */
		var _attributes;
		var _postID;
		var _metaBoxName;
		var _maxCardinality;
		var _metaBoxNum = 0;
		var _metaBoxID = 0;
		var _map;
		var _loading = false;

		/* DISPLAY OBJECTS */
		var _element = $( element );
		var _proxyElement;
		var _sortableComponent;
		var _input;
		var _addButton;
		var _metaBoxDummyHTML;

		var construct = function()
		{
			_this.super();
			
			_attributes = $.extend({}, {conditions:[]}, JSON.parse( decodeURIComponent( _element.find(".MetaBoxAttributes").html() ) ) );

			_map = {};
			_postID = _element.attr( "data-post-id" );
			_metaBoxName = _element.attr( "data-meta-box-name" );
			_maxCardinality = parseInt( _element.attr( "data-max-cardinality" ) );

			draw();
		};

		var draw = function()
		{
			_proxyElement = _element.find( ".MetaBoxProxy" );

			_input = _element.find( "input#" + _metaBoxName );

			_proxyElement.find(".MetaBox").each( function()
			{
				addMetaBox( $( this ) );
			});

			_addButton = _element.find( ".AddMetaBoxButton" );
			_addButton.on( "click", onAddClick );

			// _sortableComponent = new SortableComponent( _proxyElement, ".title" );

			_metaBoxDummyHTML = _element.find(".MetaBoxDummy").html();
			_element.find(".MetaBoxDummy").remove();

			adjustUI();
		};

		var adjustUI = function()
		{
			var i = 0;
			for(var key in _map)
			{
				var metaBox = _map[ key ];
				metaBox.setCardinalityID( i );
				i++;
			}

			_metaBoxNum = i;

			_input.val( _metaBoxNum );

			adjustAddButton();
		};

		var adjustAddButton = function()
		{
			if(_addButton)
			{
				if( hasReachedMax() ) _addButton.addClass( "HiddenElement" );
				else _addButton.removeClass( "HiddenElement" );
			}
		};

		var hasReachedMax = function()
		{
			return _metaBoxNum >= _maxCardinality && _maxCardinality >= 0;
		};

		/* ACTIONS */
		this.change = function()
		{
			for(var key in _map)
			{
				var metaBox = _map[ key ];
				metaBox.change();
			}
		};

		var requestMetaBoxHTML = function()
		{
			// return onGetMetaBoxHTML( _metaBoxDummyHTML );

			var data = 
			{
				action : "tutomvc/ajax/render/metabox",
				nonce : TutoMVC.nonce,
				postID : 0,
				metaBoxName : _metaBoxName,
				key : 0
			};

			// console.log(data);

			$.ajax({
				type: "post",
				dataType: "html",
				url: TutoMVC.ajaxURL,
				data: data,
				success: onGetMetaBoxHTML,
				error: onAjaxError
			});
		};

		var addMetaBox = function( metaBoxElement, append )
		{
			var id = getNewMetaBoxID();
			var metaBox = new MetaBox( id, $( metaBoxElement ) );
			metaBox.addEventListener( "remove", onRemoveMetaBox );
			metaBox.addEventListener( "change", onMetaFieldChange );

			_map[ id ] = metaBox;

			if( append )
			{
				_proxyElement.append( metaBox.getElement() );
				//_sortableComponent.update();
			}

			adjustUI();

			return metaBox;
		};

		var removeMetaBox = function( id )
		{
			var metaBox = _map[ id ];

			if(metaBox)
			{
				metaBox.getElement().remove();
				delete _map[ id ];
			}

			adjustUI();
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
		var getNewMetaBoxID = function()
		{
			return _metaBoxID++;
		};

		this.getMetaBoxName = function()
		{
			return _metaBoxName;
		};

		/* EVENT HANDLERS */
		var onAddClick = function(e)
		{
			e.preventDefault();

			// requestMetaBoxHTML();
			if(!hasReachedMax()) addMetaBox( $( _metaBoxDummyHTML ), true ).change();
		};

		var onGetMetaBoxHTML = function(e)
		{
			addMetaBox( $( e ), true ).change();
		};

		var onRemoveMetaBox = function(e)
		{
			var id = e.getBody().id;

			removeMetaBox( id );
		};

		var onAjaxError = function(e)
		{
			console.log(e);
		};

		var onMetaFieldChange = function(e)
		{
			_this.metaBoxChange( _metaBoxName, e.getBody().metaFieldName, e.getBody().value );

			e.getBody().metaBoxName = _metaBoxName;
			_this.dispatchEvent( e );
		};


		construct();
	}

	return MetaBoxProxy.extends( EventDispatcher );
})