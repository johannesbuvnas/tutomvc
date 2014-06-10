require([
	"jquery",
	"backbone",
	"com/tutomvc/wpadmin/Constants",
	"com/tutomvc/wpadmin/controller/commands/StartUpCommand",
	"doc-ready/doc-ready"
],
function( 
	$,
	Backbone,
	Constants,
	StartUpCommand,
	DocReady
	)
{
	"use strict";
	var App = Backbone.View.extend({
		el : "body",
		initialize : function()
		{
			this.listenTo( this, Constants.STARTUP, StartUpCommand );
		}
	});

	var instance = new App;

	return DocReady(function()
	{
		instance.trigger( Constants.STARTUP );
	});
});