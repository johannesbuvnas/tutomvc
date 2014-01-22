define(
[
	"tutomvc",
	"jquery",
	"app/view/meta/MetaBoxModelViewComponent"
],
function
( 
	tutomvc,
	$,
	MetaBoxModelViewComponent
)
{
	function MainViewComponent()
	{
		/* PRIVATE REFERENCES */
		var _this = this;

		/* PUBLIC REFERENCES */
		this.metaBoxModelViewComponent;

		// Constructor
		(function()
		{
			_this.metaBoxModelViewComponent = new MetaBoxModelViewComponent();
		})();
	}

	return function()
	{
		MainViewComponent.prototype = new tutomvc.core.controller.event.EventDispatcher();
		MainViewComponent.prototype.constructor = MainViewComponent;

		return new MainViewComponent();
	};
});