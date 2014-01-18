define([
	"tutomvc",
	"jquery"
],
function( tutomvc, $ )
{
	function TextareaWYSIWYGInput( content, id, settings )
	{
		/* VARS */
		var _this = this;
		var _super = this.constructor.prototype;
		var _content = content ? content : "";
		var _name;
		var _id = id ? id : "";
		var _settings = settings ? settings : "";

		/* DISPLAY OBJECTS */
		var _editor;

		var construct = function()
		{
			draw();
		};

		var draw = function()
		{
			_this.setElement( $( "<div></div>" ) );

			requestEditor();
		};

		/* ACTIONCS */
		var requestEditor = function()
		{
			_settings.quicktags = false;
			var data = 
			{
				action : "tutomvc/ajax/render/wp_editor",
				nonce : Tuto.nonce,
				content : _content,
				id : _id,
				settings : _settings
			};

			$.ajax({
				type: "post",
				dataType: "html",
				url: Tuto.ajaxURL,
				data: data,
				success: onAjaxResult,
				error: onAjaxError
			});
		};

		/* SET AND GET */
		this.setValue = function( value )
		{
			if (typeof value == 'string' || value instanceof String)
			{

			}
			else
			{
				value = "";
			}
			
			if( _editor )
			{
				_editor.setContent( value ? value : "" );
			}
			else
			{
				_content = value;
			}
		};

		this.setName = function( name )
		{
			_name = name;

			if(_editor) $("#" + _id).attr("name", name);
		};

		/* EVENT HANDLERS */
		var onAjaxResult = function(e)
		{
			_this.getElement().append( $(e) );

			// tinyMCE.init( {
			//     skin : "wp_theme",
			//     // mode : "exact",
			//     elements : _id
			//     // theme: "advanced"
			// } );

			// _this.getElement().find( ".switch-tmce" ).trigger( "click" );
			// var qtags = quicktags( {id : _id} );
			// console.log(qtags);
			// console.log( switchEditors.go( _id ) );

			// quicktags( {id : _id} );
			tinyMCE.execCommand( "mceAddControl", false, _id );
			// tinymce.init( tinyMCEPreInit.mceInit[ _id ] );

			// console.log( switchEditors.go( _id ) );

			_editor = tinyMCE.get( _id );
			$( _editor.getBody() ).on( "blur" , onEditorBlur );
			$( '#wp-' + _id + '-wrap' ).on( "click", onEditorFocus );
			_this.setValue( _content );

			wpActiveEditor = null;

			$("#" + _id).attr( "name", _name );
		};

		var onAjaxError = function(e)
		{
			// console.log(e);
		};

		var onEditorFocus = function(e)
		{
			wpActiveEditor = _id;
		};

		var onEditorBlur = function(e)
		{
			wpActiveEditor = null;
		};

		construct();
	}

	return function( content, id, settings )
	{
		TextareaWYSIWYGInput.prototype = new tutomvc.components.form.input.Input();
		TextareaWYSIWYGInput.prototype.constructor = TextareaWYSIWYGInput;

		return new TextareaWYSIWYGInput( content, id, settings );
	};
});