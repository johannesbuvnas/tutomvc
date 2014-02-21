define([
	"backbone"
],
function(Backbone)
{
	"use strict";

	var Input = Backbone.View.extend({
		tagName : "input",
		constructor : function(options)
		{
			var attributes = 
			{
				type : "hidden",
				id : null,
				name : null,
				value : null
			}
			this.attributes = options ? _.extend( attributes, options.attributes ) : attributes;

			Backbone.View.call(this, options);

			if(!this.model) this.model = new Input.Model();
			this.listenTo(this.model, 'change', this.render);
			this.listenTo(this.model, 'destroy', this.remove);
			this.render();
		},
		render : function()
		{
			this.$el.attr( "type", this.model.get("type") );
			this.$el.attr( "name", this.model.get("name") );
			this.$el.attr( "id", this.model.get("id") );
			this.$el.val( this.model.get("value") );

			return this;
		},

		// EVENTS

		// TODO: Remove this later
		addEventListener :  function( eventName, callback )
		{
			this.on( eventName, callback );
		},
		getElement : function()
		{
			return this.$el;
		},
		setID : function(id)
		{
			this.$el.attr("id", id);
		},
		setName : function(name)
		{
			this.model.set( {name:name} );
		},
		getName : function()
		{
			return this.model.get("name");
		},
		setValue : function(value)
		{
			this.model.set( "value", value );
		},
		getValue : function()
		{
			this.$("input").val();
		}
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