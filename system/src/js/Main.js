define([
	"jquery",
	"tuto",
	"app/AppConstants",
	"app/controller/commands/StartUpCommand"
],
function( 
	$,
	tuto,
	AppConstants,
	StartUpCommand 
	)
{
	return $( window ).load(function()
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

		AppFacade.prototype = tuto.core.Facade.getInstance( AppConstants.KEY );
		AppFacade.prototype.constructor = AppFacade;

		return new AppFacade();
	});
});