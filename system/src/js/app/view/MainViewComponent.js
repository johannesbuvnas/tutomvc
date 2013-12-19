define(
[
	"tuto",
	"app/view/meta/MetaBoxModelViewComponent"
],
function
( 
	tuto,
	MetaBoxModelViewComponent
)
{
	function MainViewComponent()
	{
		/* PRIVATE REFERENCES */
		var _this = this;

		this.metaBoxModelViewComponent;

		/* PUBLIC REFERENCES */

		var construct = function()
		{
			_this.metaBoxProxyViewComponent = new MetaBoxModelViewComponent();
		};

		construct();
	}

	MainViewComponent.prototype = new tuto.core.controller.event.EventDispatcher();
	MainViewComponent.prototype.constructor = MainViewComponent;

	return MainViewComponent;
});