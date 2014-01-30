define(
[
	"tutomvc",
	"jquery",
	"app/view/meta/MetaBoxModelMediator",
	"app/view/page/SettingsPageMediator",
	"app/view/page/tutomvc/TutoMVCLogsMediator"
],
function
( 
	tutomvc,
	$,
	MetaBoxModelMediator,
	SettingsPageMediator,
	TutoMVCLogsMediator
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

			if($("#" + TutoMVCLogsMediator.VIEW_COMPONENT_NAME).length)
			{
				_this.getFacade().view.registerMediator( $("#" + TutoMVCLogsMediator.VIEW_COMPONENT_NAME), new TutoMVCLogsMediator() );
			}
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