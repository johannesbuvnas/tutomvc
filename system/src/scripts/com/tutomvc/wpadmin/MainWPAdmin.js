require([
	"jquery",
	"com/tutomvc/core/facade/Facade",
	"com/tutomvc/wpadmin/Constants",
	"com/tutomvc/wpadmin/controller/commands/StartUpCommand"
],
function( $, Facade, Constants, StartUpCommand, Test )
{
	"use strict";
	var facade = Facade.getInstance( Constants.FACADE_KEY );
	facade.controller.registerCommand( Constants.STARTUP, StartUpCommand );

	return $( document ).ready(function()
	{
		facade.dispatch( Constants.STARTUP, {} );
	});
});