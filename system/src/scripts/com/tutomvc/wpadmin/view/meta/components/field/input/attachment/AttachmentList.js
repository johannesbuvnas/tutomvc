define(
[	
	"backbone",
	"com/tutomvc/component/button/BBButton",
	"com/tutomvc/wpadmin/view/meta/components/field/input/attachment/AttachmentItem",
	"com/tutomvc/wpadmin/view/components/ArrangeableList",
	"com/tutomvc/component/form/input/BBInput"
],
function( Backbone, Button, AttachmentItem, ArrangeableList, Input )
{
	var AttachmentList = ArrangeableList.extend({
		className : "AttachmentList cf",
		collection : new Backbone.Collection([], {
			model : AttachmentItem.Model
		}),
		itemSelector : ".AttachmentItem",
		handleSelector : "img",
		initialize : function(options)
		{
			if(!this.model) this.model = new BBAttachmentList.Model();

			this.addButton = new Button({
				className : "AddButton"
			});
			this.$el.append( this.addButton.$el );

			this.wpMedia = wp.media({
			    title: this.model.get("title"),
			    multiple: this.model.get("maxCardinality") < 0 || this.model.get("maxCardinality") > 1 ? true : false,
			    library: this.model.get("filter") ? { type: this.model.get("filter") } : undefined,
			    button : { text : this.model.get("buttonTitle") },
			    frame: 'select'
			});

			this.listenTo( this.collection, "add", this.onAdd );
			this.listenTo( this.collection, "change", this.adjust );
			this.listenTo( this.collection, "remove", this.adjust );
			this.listenTo( this.collection, "add", this.adjust );
			this.listenTo( this.model, "change:value", this.onValueChange );
			this.listenTo( this.model, "change:name", this.onNameChange );
			this.listenTo( this.wpMedia, "select", this.onWPMediaSelect );

			this.adjust();
		},
		adjust : function()
		{
			if(this.collection.length >= this.model.get("maxCardinality") && this.model.get("maxCardinality") >= 0) this.addButton.$el.addClass( "HiddenElement" );
			else this.addButton.$el.removeClass( "HiddenElement" );
		},
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
			// this.collection.invoke( "set", {name:name} );
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
		},

		// Events
		events : {
			"click .AddButton" : "onAddClick",
		},
		onNameChange : function(model, value)
		{
			this.collection.invoke( "set", {name:value} );
		},
		onValueChange : function()
		{
			if( !this.model.get("value") )
			{
				this.collection.reset();
			}
			else
			{
				for(var key in this.model.get("value"))
				{
					var attachment = this.model.get("value")[key];
					this.collection.add({
						attachmentID : attachment.id,
						title : attachment.title,
						thumbnailURL : attachment.thumbnailURL,
						iconURL : attachment.iconURL,
						editURL : attachment.editURL
					});
				}
			}
		},
		onAddClick : function()
		{
			this.wpMedia.open();
		},
		onAdd : function( model )
		{
			var item = new AttachmentItem({
				model : model
			});
			this.addButton.$el.before( item.el );
		},
		onWPMediaSelect : function(e)
		{
			var selection = this.wpMedia.state().get('selection');
			var _this = this;
			selection.each(function(attachment)
			{
				if(_this.collection.length < _this.model.get("maxCardinality"))
				{
					_this.collection.add( {
						attachmentID : attachment.id,
						title : attachment.attributes.filename,
						thumbnailURL : attachment.attributes.sizes ? attachment.attributes.sizes.thumbnail.url : null,
						iconURL : attachment.attributes.icon,
						editURL : attachment.attributes.editLink,
						name : _this.model.get("name")
					} );
				}
			});
		},
	},
	{
		Model : Input.Model.extend({
			defaults : {
				title : null,
				buttonTitle : "Select",
				maxCardinality : -1,
				filter : null,
			}
		})
	});

	return AttachmentList;
});