define(
[
	"tutomvc",
	"jquery",
	"app/view/meta/components/field/MetaField"
],
function
( 
	tutomvc,
	$,
	MetaField
)
{
	function SettingsPageMediator()
	{
		/* VARS */
		var _this = this;

		/* EVENTS */
		this.onRegister = function()
		{
			_this.getViewComponent().find( ".SettingsField" ).each(function()
				{
					var metaField = new MetaField( "", $( this ) );
				});
		};
	}

	return function()
	{
		SettingsPageMediator.prototype.constructor = SettingsPageMediator;
		SettingsPageMediator.prototype = new tutomvc.core.view.mediator.Mediator( "SettingsPageMediator" );

		return new SettingsPageMediator();
	};
});