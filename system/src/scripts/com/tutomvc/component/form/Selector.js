define([
	"backbone",
	"underscore",
	"com/tutomvc/component/form/Input",
	"text!com/tutomvc/component/form/Selector.tpl.html"
],
function(Backbone, _, Input, HTML)
{
	"use strict";
	// Selector.Model
	var Model = Input.Model.extend({
		defaults :{
			label : "Select"
		},
		initialize : function()
		{
			if(!this.get("options") || !Input.Collection.prototype.isPrototypeOf( this.get('options') ))
			{
				this.set({
					options : new Input.Collection( this.get('options') )
				});
			}
		}
	});

	// Selector
	var Selector = Input.extend({
		tagName : "div",
		attributes : {
			class : "BBSelector Unselectable"
		},
		template : _.template( HTML ),
		initialize : function()
		{
			if(!this.model) this.model = new Selector.Model();

			this.listenTo( this.model.get("options"), "add", this.render );
			this.listenTo( this.model.get("options"), "remove", this.render );
			this.listenTo( this.model.get("options"), "change", this.render );
			this.listenTo( this.model, "change", this.render );

			this.listenTo( this, "focus", this.onClick );
		},
		render : function()
		{
			if(this.model.hasChanged("value") && !this.model.hasChanged("label"))
			{
				var option = this.model.get("options").findWhere( {value:this.model.get("value")} );
				if(option)
				{
					this.model.set({
						label : option.get("name")
					});

					return this;
				}
			}

			this.$el.html( this.template(this) );

			return this;
		},
		// Events
		events : {
			"click .Options > .Model" : "onSelect",
			"click" : "onClick"
		},
		onClick : function(e)
		{
			if(e) e.preventDefault();
			
			this.$el.toggleClass("Expanded");
		},
		onSelect : function(e)
		{
			var selectedModel = this.model.get("options").get( Backbone.$( e.currentTarget ).attr("data-cid") );

			this.model.set({
				label : selectedModel.get("name"),
				value : selectedModel.get("value")
			});
		}
	},
	{
		Model : Model
	});

	return Selector;
});