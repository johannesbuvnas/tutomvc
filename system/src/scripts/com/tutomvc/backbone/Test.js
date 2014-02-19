define([
	"backbone",
	"com/tutomvc/backbone/Facade"
],
function(Backbone, Facade)
{
	function Test()
	{
		var AppConstants = {
			KEY : "app/facade",
			STARTUP : "startup"
		};
		var AppFacade = Facade.extend({
			constructor : function()
			{
				Facade.call( this );
			},
			el : "body",
			key : AppConstants.KEY
		}, AppConstants);

		var instance = new AppFacade();
		instance.registerCommand( instance.$el, "click", function()
			{
				console.log(this.constructor.KEY, arguments);
			} );
		instance.trigger("startup");
	}

	return Test;
});