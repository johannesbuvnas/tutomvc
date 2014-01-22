define(
[
	"tutomvc",
	"jquery",
	"app/view/meta/MetaBoxModelMediator",
	"app/view/page/SettingsPageMediator"
],
function
( 
	tutomvc,
	$,
	MetaBoxModelMediator,
	SettingsPageMediator
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
			_this.getFacade().view.registerMediator( $("body"), new SettingsPageMediator() );
		};
	}

	return function()
	{
		MainMediator.prototype.constructor = MainMediator;
		MainMediator.prototype.NAME = MainMediator.prototype.constructor.name;
		MainMediator.prototype = new tutomvc.core.view.mediator.Mediator( MainMediator.prototype.NAME );

		return new MainMediator();
	};
});