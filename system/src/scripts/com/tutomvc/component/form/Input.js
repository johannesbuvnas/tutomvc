define([
	"backbone"
],
function(Backbone)
{
	"use strict";

	var Input = Backbone.View.extend({
		tagName : "input",
		attributes : {
			type : "hidden"
		},
		constructor : function(options)
		{
			Backbone.View.call(this, options);

			if(!this.model) this.model = new Input.Model();
			this.listenTo(this.model, 'change', this.render);
			this.listenTo(this.model, 'destroy', this.remove);
			this.render();
		},
		render : function()
		{
			this.$el.attr( "name", this.model.get("name") );
			this.$el.attr( "id", this.model.get("elementID") );
			this.$el.val( this.model.get("value") );

			return this;
		},
	},
	{
		Model : Backbone.Model.extend({
			constructor : function( options )
			{
				var defaults = {
					value : "",
					name : "",
					elementID : ""
				};
				this.defaults = _.extend( defaults, this.defaults );

				Backbone.Model.call( this, options );
			},
		})
	});
	return Input;
});