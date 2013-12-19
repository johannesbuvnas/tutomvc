define(
[
	"tutomvc",
	"jquery",
	"app/view/meta/components/MetaBoxProxy"
],
function
(
	tutomvc,
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

	MetaBoxModelViewComponent.prototype = new tutomvc.core.controller.event.EventDispatcher();
	MetaBoxModelViewComponent.prototype.constructor = MetaBoxModelViewComponent;
	return MetaBoxModelViewComponent;
})