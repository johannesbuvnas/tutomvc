define([
	"base64",
	"backbone",
	"underscore",
	"text!com/tutomvc/wpadmin/view/meta/components/field/MetaField.tpl.html",
	"com/tutomvc/component/form/Input",
	"com/tutomvc/component/form/input/SingleSelector",
	"com/tutomvc/component/form/Selector",
	"com/tutomvc/component/model/proxy/Proxy",
	"com/tutomvc/wpadmin/view/meta/components/field/input/text/TextareaWYSIWYGInput",
	"com/tutomvc/wpadmin/view/meta/components/field/input/attachment/AttachmentList",
	"com/tutomvc/component/form/TextArea",
	"com/tutomvc/wpadmin/view/meta/components/field/input/text/LinkInput"
],
function( Base64, Backbone, _, Template, Input, SingleSelector, Selector, Proxy, TextareaWYSIWYGInput, AttachmentList, TextArea, LinkInput )
{
	"use strict";

	var MetaField = Backbone.View.extend({
		template : _.template( Template ),
		constructor : function(metaBoxID, element)
		{
			var attr = JSON.parse( decodeURIComponent( element.find(".JSON").html() ) );
			if (typeof attr.value == 'string' || attr.value instanceof String) attr.value = Base64.decode( attr.value );
			attr.elementID = attr.metaFieldName + "_" + metaBoxID;
			attr.metaBoxID = metaBoxID;

			Backbone.View.call(this, {
				el : element,
				model : new MetaField.Model( attr )
			});
		},
		initialize : function()
		{
			this.input = MetaField.getInputInstance( this.model );
			this.$el.append( this.input.$el );
			if(this.model.get("dividerAfter")) this.$el.append("<hr/>");
			// if(this.model.get("type") == "selector_single") this.input.addEventListener( "change", _.bind(this.change, this) );

			this.listenTo( this.model, "change:elementID", this.render );
			this.listenTo( this.model, "change:title", this.render );
			this.listenTo( this.model, "change:description", this.render );
			this.listenTo( this.model, "change:name", this.onKeyChange );
			this.listenTo( this.model, "change:value", this.change );
			this.render();
		},
		render : function()
		{
			this.$(".MetaFieldHeader").remove();
			this.$el.prepend( this.template( this.model.toJSON() ) );
			return this;
		},
		show : function()
		{
			this.$el.removeClass( "HiddenElement" );
		},
		hide : function()
		{
			this.$el.addClass( "HiddenElement" );
		},
		change : function()
		{
			// if(this.model.get("type") == "selector_single") this.model.set({value:this.input.getValue()})

			// var event = new Event( "change", { metaFieldName : this.model.get("metaFieldName"), value : this.model.get("value") } );
			this.trigger( "change", this.model );
		},

		// EVENTS
		events : {
			"click label" : "onClick"
		},
		// TODO: Remove this later
		onKeyChange : function()
		{
			// if(this.model.get("type") == "selector_single") this.input.setName( this.model.get("name") );
		},
		metaBoxChange : function( metaBoxName, metaFieldName, value )
		{
			// console.log("Appearently", metaBoxName + "_" + metaFieldName, "has changed to", value);

			for(var key in this.model.get("conditions"))
			{
				var condition = this.model.get("conditions")[key];
				if(condition.metaBoxName == metaBoxName && condition.metaFieldName == metaFieldName)
				{
					if(condition.value == value)
					{
						if( condition.onElse ) this[ condition.onMatch ]();
					}
					else
					{
						if( condition.onElse ) this[ condition.onElse ]();
					}
				}
			}
		},
		onClick : function(e)
		{
			this.input.trigger( "focus" );
		}
	},
	{
		Model : Input.Model.extend({
			defaults : {
				metaBoxID : "",
				title : "",
				description : "",
				metaFieldName : "",
				conditions : [],
				type : "text",
				dividerBefore : false,
				dividerAfter : false
			}
		}),
		getInputInstance : function( model )
		{
			var component;

			switch( model.get("type") )
			{
				case "link":

					component = new LinkInput({
						model : model
					});

				break;
				case "textarea_wysiwyg":

					component = new TextareaWYSIWYGInput({
						model : model
					});

				break;
				case "attachment":

					component = new AttachmentList( {
						model : model
					} );

				break;
				case "selector_single":

					// console.log( model.toJSON() );
					var options = new Input.Collection();
					var optionsObject = model.get("options");
					for(var key in optionsObject)
					{
						options.add({
							value : key,
							name : optionsObject[ key ]
						});
						if(key == model.get("value")) model.set({label:optionsObject[key]});
					}
					model.set({
						options : options
					});
					component = new Selector({
						model : model
					});

					// component = new SingleSelector();
					// component.setLabel( model.get("title") );

					// var proxy = new Proxy();

					// for(var key in model.get("options"))
					// {
					// 	proxy.addVO( model.get("options")[key], key );
					// 	if(key == model.get("value"))
					// 	{
					// 		component.setLabel( model.get("options")[key] );
					// 		component.setValue( model.get("value") );
					// 	}
					// }

					// component.model.addProxy( proxy );
					// component.reset();

				break;
				case "textarea":

					component = new TextArea({
						model : model
					});

				break;
				case "selector_datetime":

					component = new Input({
						model : model,
						attributes : {
							type : "text"
						}
					});
					component.$el.datetimepicker({
						format : 'Y-m-d H:i',
						value : model.get("value")
					});

				break;
				default:

					component = new Input({
						model : model,
						attributes : {
							type : "text",
							readonly : model.get("readOnly") ? model.get("readOnly") : false
						}
					});

				break;
			}

			if(component && component.setName) component.setName( model.get("name") );

			return component;
		}
	});

	return MetaField;
});