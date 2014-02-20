define([
	"backbone",
	"underscore",
	"text!com/tutomvc/component/button/Button.tpl.html"
],
function( Backbone, _, Template )
{
	"use strict";
	var Button = Backbone.View.extend({
		template : _.template( Template ),
		initialize : function()
		{
			this.$el.addClass( "Button" );
			this.$el.html( this.template() );
		},
	});
	return Button;
});