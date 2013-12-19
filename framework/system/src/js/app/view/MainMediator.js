define(
[
	"tuto",
	"app/view/meta/MetaBoxModelMediator"
],
function
( 
	tuto,
	MetaBoxModelMediator
)
{
	function MainMediator()
	{
		/* VARS */
		var _this = this;

		/* EVENTS */
		this.onRegister = function()
		{
			_this.getFacade().view.registerMediator( _this.getViewComponent().metaBoxModelViewComponent, new MetaBoxModelMediator() );
		};
	}

	MainMediator.prototype.constructor = MainMediator;
	MainMediator.prototype.NAME = MainMediator.prototype.constructor.name;
	MainMediator.prototype = new tuto.core.view.mediator.Mediator( MainMediator.prototype.NAME );

	return MainMediator;
});