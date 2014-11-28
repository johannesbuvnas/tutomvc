define([
	"jquery",
	"underscore",
	"backbone",
	"com/tutomvc/component/form/MultiSelector"
],
function($, _, Backbone, MultiSelector)
{
	var TutoMVCLogsMediator = Backbone.View.extend({
		initialize : function()
		{
			// Model
			this.model = new Backbone.Model( $.parseJSON( this.$el.attr("data-provider") ) );
			var selectorModel = new MultiSelector.Model();
			for(var k in this.model.attributes)
			{
				selectorModel.get("options").add({
					name : k,
					value : this.model.attributes[k]
				}).on("change:selected", _.bind( this.onOptionChange, this ));
			}
			// View
			this.selector = new MultiSelector({
				model : selectorModel
			});
			this.$el.prepend( this.selector.$el );
			// Controller
		},
		add : function( inputModel )
		{
			this.requestLog( inputModel.get( "name" ), inputModel.get( "value" ) );
		},
		remove : function( inputModel )
		{
			this.$( ".Log[data-key='" + inputModel.get("value") + "']" ).remove();
		},
		requestLog : function( title, file )
		{
			var data = 
			{
				action : "tutomvc/ajax/render/log",
				nonce : TutoMVC.nonce,
				file : file,
				title : title
			};

			var _this = this;

			$.ajax({
				type: "post",
				dataType: "html",
				url: TutoMVC.ajaxURL,
				data: data,
				success: function(e)
				{
					var element = $(e).addClass( "Log" ).attr( "data-key", file );
					_this.$(".Logs").prepend( element );
				},
				error: function(e)
				{

				}
			});
		},
		// Events
		onOptionChange : function( model )
		{
			if( model.get("selected") ) this.add( model );
			else this.remove( model );
		}
	});

	return TutoMVCLogsMediator;
});