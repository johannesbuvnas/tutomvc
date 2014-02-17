define([
	"jquery",
	"com/tutomvc/core/controller/command/Command",
	"com/tutomvc/wpadmin/view/MainViewComponent",
	"com/tutomvc/wpadmin/view/MainMediator"
],
function( $, Command, MainViewComponent, MainMediator )
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
			_this.getFacade().view.registerMediator( $("body"), new MainMediator() );
		};

		this.execute = function( event )
		{
			prepModels();
			prepCommands();
			prepViews();
		};

		this.super();
	}

	return Command.superOf( StartUpCommand );
});