define([
	"backbone"
],
function(Backbone)
{
	"use strict";
	var Input = Backbone.View.extend({
		constructor : function(id, name, value)
		{
			this.attributes = 
			{
				type : "hidden",
				id : id,
				name : name,
				value : value
			}

			Backbone.View.call(this);
		},
		tagName : "input"
	},
	{
		Model : Backbone.Model.extend({
			constructor : function( options )
			{
				var defaults = {
					value : null,
					name : null,
					id : null,
					type : "hidden",
				};
				this.defaults = _.extend( defaults, this.defaults );

				Backbone.Model.call( this, options );
			},
		})
	});
	return Input;
});