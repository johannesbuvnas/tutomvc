define([
	"underscore",
	"backbone",
	"com/tutomvc/component/form/Selector",
	"text!com/tutomvc/component/form/MultiSelector.tpl.html"
],
function(
	_,
	Backbone,
	Selector,
	HTML
)
{
	"use strict";
	var MultiSelector = Selector.extend({
		template : _.template( HTML ),
		initialize : function()
		{
			// Prep model
			var filteredValue = this.model.get("value");

			switch(typeof filteredValue)
			{
				case "string":

					var selectedOption = this.model.get("options").findWhere({value:this.model.get("value")});
					if(selectedOption) selectedOption.set({selected:true});

				break;
				default:

					for(var i in filteredValue)
					{
						var selectedOption = this.model.get("options").findWhere({value:filteredValue[i]});
						if(selectedOption) selectedOption.set({selected:true});
					}

				break;
			}

			// Prep view
			this.$el.addClass("MultiSelector");

			// Prep controller
			this.events["click .SelectedOptions > .Model"] = "onSelect";
			this.events["keyup .SelectedOptions > input.Filter"] = "onTypeFilter";

			this.render();
		},
		render : function()
		{
			this.$el.html( this.template(this) );

			return this;
		},
		filter : function(value)
		{
			if(!value || !value.length)
			{
				this.$(".SelectedOptions > input.Filter").val( value );
				this.render();
				this.$el.addClass("Expanded");
			}
			else
			{
				console.log("filter:", value);
				this.model.get("options").forEach(function(model)
					{
						var index = model.get("name").toLowerCase().indexOf( value.toLowerCase() );
						if ( index >= 0 )
						{
							var innerHTML = model.get("name");
							var innerHTML = innerHTML.substring(0,index) + "<span class='Highlight'>" + innerHTML.substring(index,index+value.length) + "</span>" + innerHTML.substring(index + value.length);
							Backbone.$(".Options > .Model[data-cid="+model.cid+"]").removeClass("HiddenElement");
							Backbone.$(".Options > .Model[data-cid="+model.cid+"] > .Label").html( innerHTML );
						}
						else
						{
							Backbone.$(".Options > .Model[data-cid="+model.cid+"]").addClass("HiddenElement");
						}
					});
			}

			return this;
		},
		// Events
		onTypeFilter : function(e)
		{
			this.filter( this.$(".SelectedOptions > input.Filter").val() );
		},
		onSelect : function(e)
		{
			var selectedModel = this.model.get("options").get( Backbone.$( e.currentTarget ).attr("data-cid") );

			if(selectedModel.get("selected")) selectedModel.set({selected:false});
			else selectedModel.set({selected:true});

			if(selectedModel.get("selected")) this.$el.removeClass("Expanded");
			else this.$el.addClass("Expanded");

			var newValue = [];
			var selectedModels = this.model.get("options").where({selected:true});
			Backbone.$(selectedModels).each(function()
				{
					newValue.push( this.get("value") );
				});

			this.model.set({value:newValue});
		}
	});

	return MultiSelector;
});