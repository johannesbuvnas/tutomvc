define(
[
	"tuto"
],
function
( 
	tuto
)
{
	function MetaBoxModelMediator()
	{
		/* VARS */
		var _this = this;

		/* EVENTS */
		this.onRegister = function()
		{
		};
	}

	MetaBoxModelMediator.prototype.constructor = MetaBoxModelMediator;
	MetaBoxModelMediator.prototype = new tuto.core.view.mediator.Mediator( "MetaBoxModelMediator" );

	return MetaBoxModelMediator;
});