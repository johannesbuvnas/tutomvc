define([
	"jquery",
	"com/tutomvc/core/facade/Facade",
	"com/tutomvc/wpadmin/Constants",
	"com/tutomvc/wpadmin/controller/commands/StartUpCommand"
],
function( 
	$,
	Facade,
	Constants,
	StartUpCommand 
	)
{
	return $( document ).ready(function()
	{
		function AppFacade()
		{
			var _this = this;

			/* PRIVATE METHODS */
			var prepModel = function()
			{

			};

			var prepView = function()
			{

			};

			var prepController = function()
			{
				_this.controller.registerCommand( Constants.STARTUP, StartUpCommand );
			};

			// Construct
			(function()
			{
				prepModel();
				prepView();
				prepController();

				_this.dispatch( Constants.STARTUP, {} );
			})();
		}

		AppFacade.prototype = Facade.getInstance( Constants.FACADE_KEY );
		AppFacade.prototype.constructor = AppFacade;

		return new AppFacade();
	});
});