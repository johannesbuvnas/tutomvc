define(
[
	"tuto",
	"jquery",
	"app/view/meta/components/MetaBoxProxy"
],
function
(
	tuto,
	$,
	MetaBoxProxy
)
{
	function MetaBoxModelViewComponent()
	{
		var construct = function()
		{
			$( "div.MetaBoxModel" ).each(function()
				{
					new MetaBoxProxy( $( this ) );
				});
		}

		construct();
	}

	MetaBoxModelViewComponent.prototype = new tuto.core.controller.event.EventDispatcher();
	MetaBoxModelViewComponent.prototype.constructor = MetaBoxModelViewComponent;
	return MetaBoxModelViewComponent;
})