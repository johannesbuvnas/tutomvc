define(
[
	"tutomvc",
	"app/view/MainViewComponent",
	"app/view/MainMediator"
],
function
( 
	tutomvc,
	MainViewComponent,
	MainMediator
)
{
	function StartUpCommand()
	{
		/* VARS */
		var _this = this;

		/* METHODS */
		var prepModels = function()
		{
		};

		var prepCommands = function()
		{

		};

		var prepViews = function()
		{
			_this.getFacade().view.registerMediator( new MainViewComponent(), new MainMediator() );
		};

		this.execute = function( event )
		{
			prepModels();
			prepCommands();
			prepViews();
		};
	}

	StartUpCommand.prototype = new tutomvc.core.controller.command.Command();
	StartUpCommand.prototype.constructor = StartUpCommand;

	return StartUpCommand;
});