define([
	"jquery",
	"backbone",
	"underscore",
	"com/tutomvc/component/form/Input"
],
function( $, Backbone, _, Input )
{
	var TextareaWYSIWYGInput = Input.extend({
		tagName : "div",
		editorRequested : false,
		attributes : null,
		render : function()
		{
			if(this.wpEditor)
			{
				this.$( "textarea" ).attr( "name", this.model.get("name") );
			}
			else
			{
				if(!this.editorRequested)
				{
					this.editorRequested = true;
						var data = 
						{
							action : "tutomvc/ajax/render/wp_editor",
							nonce : TutoMVC.nonce,
							content : this.model.get("value"),
							id : this.model.get("id"),
							postID : this.model.get( "postID" ),
							metaKey : this.model.get( "key" ),
							settings : {}
						};

						$.ajax({
							type: "post",
							dataType: "html",
							url: TutoMVC.ajaxURL,
							data: data,
							success: _.bind( this.onAjaxResult, this ),
							error: _.bind( this.onAjaxError, this )
						});
				}
			}

			return this;
		},

		// Events
		events : {
			"click" : "onEditorFocus"
		},
		onEditorFocus : function(e)
		{
			TextareaWYSIWYGInput.setActiveWPEditor( this.model.get("id") );
		},
		onEditorBlur : function(e)
		{
			TextareaWYSIWYGInput.setActiveWPEditor( null );
		},
		onAjaxResult : function(e)
		{
			this.$el.append( e );

			tinyMCE.execCommand( "mceAddControl", false, this.model.get("id") );
			this.wpEditor = tinyMCE.get( this.model.get("id") );
			$(this.wpEditor.getBody()).on( "blur", _.bind( this.onEditorBlur, this ) );
			TextareaWYSIWYGInput.setActiveWPEditor( null );
			
			this.render();
		},
		onAjaxError : function(e)
		{
			console.log(e);
		}
	}, 
	{
		setActiveWPEditor : function( id )
		{
			wpActiveEditor = id;
		}
	});

	return TextareaWYSIWYGInput;
});