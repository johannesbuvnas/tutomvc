require([
	"jquery",
	"com/tutomvc/core/facade/Facade",
	"com/tutomvc/wpadmin/Constants",
	"com/tutomvc/wpadmin/controller/commands/StartUpCommand"
],
function( $, Facade, Constants, StartUpCommand )
{
	"use strict";
	var facade = Facade.getInstance( Constants.FACADE_KEY );
	facade.controller.registerCommand( Constants.STARTUP, StartUpCommand );

	// function A()
	// {
	// 	var _this = this;
	// 	this.element = $("<div class='A' />");
	// 	this.element.on("click", function(e)
	// 	{
	// 		console.log(e);
	// 	});
	// 	this.getElement = function()
	// 	{
	// 		return _this.element;
	// 	};
	// }

	// function B()
	// {
	// 	this.super();
	// 	this.element.addClass("B");
	// }
	// A.superOf(B);

	// function C()
	// {
	// 	this.super();
	// 	this.element.addClass("C");
	// }
	// B.superOf(C);

	// var instance = new C();
	// instance.getElement().trigger("click");
	// return;

	return $( document ).ready(function()
	{
		facade.dispatch( Constants.STARTUP, {} );
	});
});