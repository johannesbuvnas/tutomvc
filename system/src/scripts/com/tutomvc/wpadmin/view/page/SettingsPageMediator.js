define(
[
	"com/tutomvc/core/view/mediator/Mediator",
	"jquery",
	"com/tutomvc/wpadmin/view/meta/components/field/MetaField"
],
function( Mediator, $, MetaField )
{
	SettingsPageMediator.NAME = "SettingsPageMediator";
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
					var description = metaField.$( ".description" );
					$(this).append ("<p class='description'>" + description.html() + "</p>" );
				});
		};

		this.super( SettingsPageMediator.NAME );
	}

	return Mediator.superOf( SettingsPageMediator );
});