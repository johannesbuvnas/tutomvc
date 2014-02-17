define(
[
	"com/tutomvc/core/controller/event/EventDispatcher",
	"jquery",
	"com/tutomvc/wpadmin/view/meta/components/MetaBoxProxy"
],
function( EventDispatcher, $, MetaBoxProxy )
{
	function MetaBoxModelViewComponent()
	{
		/* VARS */
		var _this = this;
		var _map = [];

		var construct = function()
		{
			$( "div.MetaBoxModel" ).each(function()
				{
					var proxy = new MetaBoxProxy( $( this ) );
					proxy.addEventListener( "change", onMetaBoxChange );
					_map.push( proxy );
				});

			$( _map ).each(function()
			{
				this.change();
			});
		}

		/* EVENTS */
		var onMetaBoxChange = function( e )
		{
			$( _map ).each(function()
			{
				if(this.getMetaBoxName() != e.getBody().metaBoxName)
				{
					this.metaBoxChange( e.getBody().metaBoxName, e.getBody().metaFieldName, e.getBody().value );
				}
			});
		};

		construct();
	}

	return MetaBoxModelViewComponent;
})