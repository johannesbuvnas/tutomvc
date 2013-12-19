define(
[
	"tutomvc",
	"jquery",
	"app/view/meta/components/MetaBox"
],
function( tutomvc, $, MetaBox )
{
	function MetaBoxProxy( element )
	{
		var _this = this;
		
		/* VARS */
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
		var _input;
		var _addButton;

		var construct = function()
		{
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
				if( _metaBoxNum >= _maxCardinality && _maxCardinality >= 0 ) _addButton.addClass( "tutomvc-hidden" );
				else _addButton.removeClass( "tutomvc-hidden" );
			}
		};

		/* ACTIONS */
		var getMetaBoxHTML = function()
		{
			var data = 
			{
				action : "tutomvc/ajax/render/metabox",
				nonce : Tuto.nonce,
				postID : 0,
				metaBoxName : _metaBoxName,
				key : 0
			};

			// console.log(data);

			$.ajax({
				type: "post",
				dataType: "html",
				url: Tuto.ajaxURL,
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

			_map[ id ] = metaBox;

			if( append ) _proxyElement.append( metaBox.getElement() );

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

		/* SET AND GET */
		var getNewMetaBoxID = function()
		{
			return _metaBoxID++;
		};

		/* EVENT HANDLERS */
		var onAddClick = function(e)
		{
			e.preventDefault();

			getMetaBoxHTML();
		};

		var onGetMetaBoxHTML = function(e)
		{
			addMetaBox( $( e ), true ).reset();
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

		construct();
	}

	return MetaBoxProxy;
})