define([
	"backbone",
	"com/tutomvc/component/form/Input"
],
function( Backbone, Input )
{
	"use strict";

	var TextArea = Input.extend({
		tagName : "textarea",
		initialize : function(options)
		{
		},
		render : function()
		{
			this.$el.attr( "name", this.model.get("name") );
			this.$el.attr( "id", this.model.get("id") );
			this.$el.html( this.model.get("value") );

			return this;
		},

		//TODO: Remove this when all is backbone
		onNameChange : function()
		{
			this.$el.attr("name", this.model.get("name"));
		},
		onValueChange : function(e)
		{
			this.$el.html( this.model.get("value") );
		},
	});

	return TextArea;
});