define(
[
	"tutomvc",
	"app/view/meta/MetaBoxModelViewComponent"
],
function
( 
	tutomvc,
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

	MainViewComponent.prototype = new tutomvc.core.controller.event.EventDispatcher();
	MainViewComponent.prototype.constructor = MainViewComponent;

	return MainViewComponent;
});