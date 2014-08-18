define(
[
	"jquery",
	"underscore",
	"backbone",
	"com/tutomvc/wpadmin/view/meta/components/MetaBox"
],
function( $, _, Backbone, MetaBox )
{
	var MetaBoxProxy = Backbone.View.extend({
		template : "",
		initialize : function()
		{
			var _this = this;

			// Model
			this.model = new MetaBoxProxy.Model( $.parseJSON( decodeURIComponent( this.$( ".MetaBoxAttributes" ).html() ) ) );
			// View
			this.template = this.$(".MetaBoxDummy").html();
			this.$(".MetaBoxDummy").remove();
			this.$( ".MetaBoxProxy" ).find( ".MetaBox" ).each( function()
			{
				_this.addMetaBox( $( this ) );
			});
			// Controller
		},
		render : function()
		{
			var i = 0;
			for(var key in this.model.get("metaBoxMap"))
			{
				var metaBox = this.model.get("metaBoxMap")[ key ];
				metaBox.model.set( {
					cardinalityID : i 
				});
				i++;

				if(i <= this.model.get("minCardinality")) metaBox.$( "> .title" ).css("display", "none");
				else metaBox.$( "> .title" ).css("display", "block");
			}

			this.model.set({
				metaBoxNum : i
			});

			this.$( "input#" + this.model.get("name") ).val( this.model.get("metaBoxNum") );

			if( this.hasReachedMax() ) this.$( ".AddMetaBoxButton" ).addClass( "HiddenElement" );
			else this.$( ".AddMetaBoxButton" ).removeClass( "HiddenElement" );
		},
		show : function()
		{
			this.$el.removeClass("HiddenElement");
			return this;
		},
		hide : function()
		{
			this.$el.addClass("HiddenElement");
			return this;
		},
		change : function()
		{
			for(var key in this.model.get("metaBoxMap"))
			{
				var metaBox = this.model.get("metaBoxMap")[ key ];
				metaBox.change();
			}

			return this;
		},
		addMetaBox : function( el, append )
		{
			var _this = this;
			this.model.set({
				metaBoxIndex : this.model.get("metaBoxIndex") + 1
			});

			var metaBox = new MetaBox( {
				id : this.model.get("metaBoxIndex"),
				el : el
			} );
			metaBox.on( "remove", function()
				{
					_this.removeMetaBox( this.id );
				} );
			metaBox.on( "change", _.bind( this.onMetaFieldChange, this ) );

			this.model.get("metaBoxMap")[ metaBox.id ] = metaBox;

			if( append ) this.$( ".MetaBoxProxy" ).append( metaBox.$el );

			this.render();

			return metaBox;
		},
		removeMetaBox : function( id )
		{
			var metaBox = this.model.get("metaBoxMap")[ id ];

			if(metaBox)
			{
				metaBox.remove();
				delete this.model.get("metaBoxMap")[ id ];
			}

			this.render();

			return this;
		},
		metaBoxChange : function( metaBoxName, metaFieldName, value )
		{
			// console.log("Appearently", metaBoxName + "_" + metaFieldName, "has changed to", value);

			for(var key in this.model.get("conditions"))
			{
				var condition = this.model.get("conditions")[key];

				var test;
				eval( "test = " + condition.jsValidation );
				var tested = test( metaBoxName, metaFieldName, value );
				if( typeof tested !== "undefined" )
				{
					if( tested )
					{
						if( condition.onMatch ) this[ condition.onMatch ]();
					}
					else
					{
						if( condition.onElse ) this[ condition.onElse ]();
					}
				}
			}
		},
		hasReachedMax : function()
		{
			return this.model.get("metaBoxNum") >= this.model.get("maxCardinality") && this.model.get("maxCardinality") >= 0;
		},
		// Events
		events : {
			"click .AddMetaBoxButton" : "onAddClick"
		},
		onAddClick : function(e)
		{
			e.preventDefault();

			if(!this.hasReachedMax()) this.addMetaBox( $( this.template ).clone(), true ).change();
		},
		onMetaFieldChange : function( metaFieldModel )
		{
			this.metaBoxChange( this.model.get("name"), metaFieldModel.get("metaFieldName"), metaFieldModel.get("value") );

			this.trigger( "change", this.model.get("name"), metaFieldModel.get("metaFieldName"), metaFieldModel.get("value") );
		}
	},
	{
		Model : Backbone.Model.extend({
			defaults : {
				name : "",
				conditions : undefined,
				maxCardinality : -1,
				minCardinality : 0,
				metaBoxIndex : 0,
				metaBoxNum : 0,
				metaBoxMap : undefined
			}
		})
	});

	return MetaBoxProxy;
})