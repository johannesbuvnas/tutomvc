define(
[
	"tutomvc"
],
function
( 
	tutomvc
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
	MetaBoxModelMediator.prototype = new tutomvc.core.view.mediator.Mediator( "MetaBoxModelMediator" );

	return MetaBoxModelMediator;
});