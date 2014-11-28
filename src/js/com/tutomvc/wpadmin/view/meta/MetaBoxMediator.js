define(
[
	"jquery",
	"underscore",
	"backbone",
	"com/tutomvc/wpadmin/view/meta/components/MetaBoxProxy"
],
function( $, _, Backbone, MetaBoxProxy )
{
	var MetaBoxMediator = Backbone.View.extend({
		initialize : function()
		{
			this.model = new MetaBoxMediator.Model({
				map : []
			});

			var _this = this;
			$( "div.MetaBoxModel" ).each(function()
			{
				var proxy = new MetaBoxProxy( {
					el : $( this ) 
				});
				proxy.on( "change", _.bind( _this.onMetaBoxChange, _this ) );
				_this.model.get("map").push( proxy );
			});

			$( this.model.get("map") ).each(function()
			{
				this.change();
			});
		},
		// Events
		onMetaBoxChange : function( metaBoxName, metaFieldName, value )
		{
			$( this.model.get("map") ).each(function()
			{
				if(this.model.get("name") != metaBoxName)
				{
					this.metaBoxChange( metaBoxName, metaFieldName, value );
				}
			});
		}
	},
	{
		Model : Backbone.Model.extend({
			map : undefined
		})
	});

	return MetaBoxMediator;
});