define([
	"backbone",
	"underscore",
	"com/tutomvc/component/form/Input",
	"text!com/tutomvc/wpadmin/view/meta/components/field/input/attachment/AttachmentItem.tpl.html"
],
function( Backbone, _, Input, Template )
{
	var AttachmentItem = Input.extend({
		tagName : "div",
		className : "AttachmentItem",
		attributes : null,
		template : _.template( Template ),
		initialize : function()
		{
		},
		// Methods
		render : function()
		{
			this.$el.html( this.template( this.model.toJSON() ) );
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