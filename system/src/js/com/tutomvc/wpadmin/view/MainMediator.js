define(
[
	"com/tutomvc/core/view/mediator/Mediator",
	"jquery",
	"com/tutomvc/wpadmin/view/meta/MetaBoxModelViewComponent",
	"com/tutomvc/wpadmin/view/meta/MetaBoxModelMediator",
	"com/tutomvc/wpadmin/view/page/SettingsPageMediator",
	"com/tutomvc/wpadmin/view/page/tutomvc/TutoMVCLogsMediator"
],
function( Mediator,	$, MetaBoxModelViewComponent, MetaBoxModelMediator, SettingsPageMediator, TutoMVCLogsMediator )
{
	MainMediator.NAME = "MainMediator";
	function MainMediator()
	{
		/* VARS */
		var _this = this;

		/* EVENTS */
		this.onRegister = function()
		{
			_this.getFacade().view.registerMediator( new MetaBoxModelViewComponent(), new MetaBoxModelMediator() );
			_this.getFacade().view.registerMediator( $("body"), new SettingsPageMediator() );

			if($("#" + TutoMVCLogsMediator.VIEW_COMPONENT_NAME).length)
			{
				_this.getFacade().view.registerMediator( $("#" + TutoMVCLogsMediator.VIEW_COMPONENT_NAME), new TutoMVCLogsMediator() );
			}
		};

		this.super( MainMediator.NAME );
	}

	return Mediator.superOf( MainMediator );
});