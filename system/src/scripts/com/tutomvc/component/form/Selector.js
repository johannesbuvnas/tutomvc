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
			title : "Select"
		},
		initialize : function()
		{
			if(!this.get("options"))
			{
				this.set({
					options : new Input.Collection()
				});
			}
		}
	});

	// Selector
	var Selector = Input.extend({
		tagName : "div",
		attributes : {
			class : "BBSelector"
		},
		template : _.template( HTML ),
		initialize : function()
		{
			if(!this.model) this.model = new Selector.Model();

			this.template = _.template( HTML );

			this.listenTo( this.model.get("options"), "add", this.render );
			this.listenTo( this.model.get("options"), "remove", this.render );
			this.listenTo( this.model.get("options"), "change", this.render );
			this.listenTo( this.model, "change", this.render );
		},
		render : function()
		{
			this.$el.html( this.template(this) );

			return this;
		},
		// Events
		events : {
			"click .Options > .Model" : "onSelect"
		},
		onSelect : function(e)
		{
			var selectedModel = this.model.get("options").get( Backbone.$( e.currentTarget ).attr("data-cid") );

			this.model.set({
				title : selectedModel.get("name"),
				value : selectedModel.get("value")
			});
		},
		onCollectionChange : function()
		{
			console.log("onCollectionChange");
		}
	},
	{
		Model : Model
	});

	return Selector;
});