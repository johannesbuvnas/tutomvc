define([
	"com/tutomvc/core/view/mediator/Mediator"
],
function( Mediator )
{
	MetaBoxModelMediator.NAME = "MetaBoxModelMediator";
	function MetaBoxModelMediator()
	{
		/* VARS */
		var _this = this;

		/* EVENTS */
		this.onRegister = function()
		{
		};
	}

	return Mediator.extend( MetaBoxModelMediator, MetaBoxModelMediator.NAME );
});