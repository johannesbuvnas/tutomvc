define([
	"backbone",
	"underscore",
	"com/tutomvc/component/form/Input",
	"text!com/tutomvc/wpadmin/view/meta/components/field/input/attachment/AttachmentItem.tpl.html"
],
function( Backbone, _, Input, Template )
{
	var AttachmentItem = Backbone.View.extend({
		tagName : "div",
		className : "AttachmentItem",
		template : _.template( Template ),
		initialize : function()
		{
			if(this.model)
			{
				this.render();
				this.listenTo(this.model, 'change', this.render);
				this.listenTo(this.model, 'destroy', this.remove);
			}
		},
		// Methods
		render : function()
		{
			this.$el.html( this.template( this.model.toJSON() ) );
			return this;
		},
		setName : function(name)
		{
			this.$("input").attr("name", name);
			return this;
		},
		// Events
		events : {
			"mouseover" : "onMouseOver",
			"mouseout" : "onMouseOut",
			"click .RemoveButton" : "onRemove",
		},
		onRemove : function()
		{
			// this.trigger( "remove" );
			// this.remove();
			this.model.destroy();
		},
		onMouseOver : function()
		{
			this.$(".RemoveButton").toggleClass( "HiddenElement" );
		},
		onMouseOut : function()
		{
			this.$(".RemoveButton").toggleClass( "HiddenElement" );
		}
	},
	{
		Model : Input.Model.extend({
			defaults : {
				attachmentID : "",
				title : "",
				thumbnailURL : null,
				iconURL : null,
				editURL : ""
			}
		})
	});

	return AttachmentItem;
});