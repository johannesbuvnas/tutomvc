define([
	"jquery",
	"underscore",
	"backbone",
	"com/tutomvc/wpadmin/view/meta/components/field/MetaField"
],
function( $, _, Backbone, MetaField )
{
	var MetaBox = Backbone.View.extend({
		id : undefined,
		initialize : function()
		{
			var _this = this;

			// Model
			this.model = new MetaBox.Model({
				name : this.$el.attr("data-meta-box-name"),
				cardinalityID : parseInt( this.$el.attr( "data-cardinality-id" ) ),
				metaFieldMap : []
			});

			// View
			this.$(".MetaField").each(function()
			{
				var input = new MetaField( _this.model.get("name") + "_" + _this.id, $( this ) );
				input.on( "change", _.bind( _this.onMetaFieldChange, _this ) );
				_this.model.get("metaFieldMap").push( input );
			});
			this.$el.removeClass("Loading");

			// Controller
			this.listenTo( this.model, "change:cardinalityID", this.render );
			this.listenTo( this.model, "change:cardinalityID", this.updateMetaKeys );

			this.render();
		},
		render : function()
		{
			this.$( "div.title span.Label" ).html(  "No. " + (this.model.get("cardinalityID") + 1) );
		},
		reset : function()
		{
			$( this.model.get("metaFieldMap") ).each(function()
			{
				this.reset();
			});
		},
		updateMetaKeys : function()
		{
			var _this = this;
			$( this.model.get("metaFieldMap") ).each(function()
			{
				this.model.setMetaKey( _this.model.get("name"), _this.model.get("cardinalityID") );
			});
		},
		change : function()
		{
			$( this.model.get("metaFieldMap") ).each(function()
			{
				this.change();
			});

			return this;
		},
		// Events
		events : {
			"click .RemoveMetaBoxButton" : "onRemoveClick"
		},
		onRemoveClick : function(e)
		{
			e.preventDefault();

			this.trigger( "remove" );
		},
		onMetaFieldChange : function( metaFieldModel )
		{
			// console.log("onMetaFieldChange",this.model.get("cardinalityID"),this.model.get("name"),metaFieldModel.get("metaFieldName"));
			
			var _this = this;
			$( this.model.get("metaFieldMap") ).each(function()
			{
				this.metaBoxChange( _this.model.get("name"), metaFieldModel.get("metaFieldName"), metaFieldModel.get("value") );
			});

			this.trigger( "change", metaFieldModel );
		}
	},
	{
		Model : Backbone.Model.extend({
			defaults : {
				name : undefined,
				cardinalityID : undefined,
				metaFieldMap : undefined
			}
		})
	});
	
	return MetaBox;
});