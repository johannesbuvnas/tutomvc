define([
	"backbone",
	"underscore",
	"com/tutomvc/component/form/Input",
	"text!com/tutomvc/wpadmin/view/meta/components/field/input/text/LinkInput.tpl.html"
],
function(Backbone, _, Input, HTML)
{
	"use strict";

	var LinkInput = Input.extend({
		tagName : "div",
		attributes : {
			// type : "text"
		},
		template : _.template( HTML ),
		initialize : function()
		{
			var value = typeof this.model.get("value") == "string" ? {href:this.model.get("value"),title:null,target:null} : this.model.get("value");

			this.model.set({
				value : new Backbone.Model( value )
			});
			this.model.get("value").on( "change", _.bind( this.render, this ) );

			this.$url = Backbone.$( '#url-field' );
			this.$title = Backbone.$( '#link-title-field' );
			this.$openInNewTab = Backbone.$( '#link-target-checkbox' );
			this.$submit = Backbone.$( '#wp-link-submit' );
			this.$submit.on( "click", _.bind( this.onSubmit, this ) );
		},
		render : function()
		{
			this.$el.html( this.template( this ) );
			this.$el.attr( "id", this.model.get("elementID") );

			return this;
		},
		// Events
		events : {
			"click" : "onFocus"
		},
		onFocus : function(e)
		{
			// Set active editor to hidden Tuto MVC Editor
			wpActiveEditor = "tutomvc-editor";
			window.wpLink.open();

			if(this.model.get("value").get("href").length) this.$url.val( this.model.get("value").get("href") );
			this.$title.val( this.model.get("value").get("title") );
			this.$openInNewTab.prop('checked', '_blank' == this.model.get("value").get("target") );
		},
		onClose : function(e)
		{
			console.log(wpLink.range);
		},
		onSubmit : function(e)
		{
			e.preventDefault();

			this.model.get("value").set( window.wpLink.getAttrs() );

			window.wpLink.refresh();
			window.wpLink.close();
			wpActiveEditor = null;
		}
	});

	return LinkInput;
});