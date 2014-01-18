define([
	"jquery",
	"tutomvc",
	"app/AppConstants",
	"app/controller/commands/StartUpCommand"
],
function( 
	$,
	tutomvc,
	AppConstants,
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
				_this.controller.registerCommand( AppConstants.STARTUP, StartUpCommand );
			};

			// Construct
			(function()
			{
				prepModel();
				prepView();
				prepController();

				_this.dispatch( AppConstants.STARTUP, {} );
			})();
		}

		AppFacade.prototype = tutomvc.core.Facade.getInstance( AppConstants.KEY );
		AppFacade.prototype.constructor = AppFacade;

		return new AppFacade();
	});
});