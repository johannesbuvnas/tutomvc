define([
	"backbone",
	"com/tutomvc/component/form/Input"
],
function( Backbone, Input )
{
	"use strict";

	var TextArea = Input.extend({
		tagName : "textarea",
		attributes : null,
		initialize : function(options)
		{
		},
		render : function()
		{
			this.$el.attr( "name", this.model.get("name") );
			this.$el.attr( "id", this.model.get("id") );
			this.$el.attr( "rows", this.model.get("rows") );
			this.$el.html( this.model.get("value") );

			return this;
		},
	},
	{
		Model : Input.Model.extend({
			defaults : {
				rows : 5, 
			}
		})
	});

	return TextArea;
});