/**
 * Created by johannesbuvnas on 18/01/15.
 */
define( [
        "jquery",
        "backbone",
        "underscore"
    ],
    function ( $, Backbone, _, Input )
    {
        var WPEditor = Backbone.View.extend( {
                id: "",
                name: "",
                _editor: undefined,
                _editorHTML: undefined,
                initialize: function ()
                {
                    console.log( "wpeditor:", this.$el.attr( "id" ), this.$el.val() );
                    //Model
                    this.id = "TUTOMVC_WP_EDITOR-" + WPEditor.instanceMap.length;
                    this.name = "TUTOMVC_WP_EDITOR-" + WPEditor.instanceMap.length;
                    //View
                    this._editor = new Backbone.View( {} );
                    this.$el.before( this._editor.$el );
                    //Controller
                    this.$el.closest( ".form-group-detachable" ).on( "predetach", _.bind( this.onPredetach, this ) );
                    this.$el.closest( ".form-group-detachable" ).on( "reattach", _.bind( this.render, this ) );

                    this.requestEditor();
                },
                requestEditor: function ()
                {
                    console.log( "WPEditor::requestEditor", this.id );
                    var data =
                    {
                        action: "tutomvc/ajax/render/wp_editor",
                        nonce: TutoMVC.nonce,
                        elementID: this.id,
                        textarea_name: this.name,
                        content: this.$el.val(),
                        settings: {}
                    };

                    if ( TutoMVC.currentScreenID == "profile" || TutoMVC.currentScreenID == "user-edit" )
                    {
                        data.userID = data.postID;
                        data.postID = undefined;
                    }
                    $.ajax( {
                        type: "post",
                        dataType: "html",
                        url: TutoMVC.ajaxURL,
                        data: data,
                        success: _.bind( this.onAjaxResult, this ),
                        error: _.bind( this.onAjaxError, this )
                    } );
                },
                reset: function ()
                {
                    return this;
                },
                render: function ()
                {
                    console.log( "WPEditor::render", this.id );

                    if ( this.wpEditor )
                    {
                        if ( parseInt( tinyMCE.majorVersion ) >= 4 ) tinyMCE.execCommand( "mceRemoveEditor", false, this.id ); // New versions
                        else tinyMCE.execCommand( "mceRemoveControl", false, this.id ); // Old versions

                        this.wpEditor = null;
                    }

                    if ( this._editorHTML )
                    {
                        this._editor.$el.html( this._editorHTML );
                        if ( parseInt( tinyMCE.majorVersion ) >= 4 ) tinyMCE.execCommand( "mceAddEditor", false, this.id ); // New versions
                        else tinyMCE.execCommand( "mceAddControl", false, this.id ); // Old versions

                        this.wpEditor = tinyMCE.get( this.id );
                        this.wpEditor.setContent( this.$el.val(), { format: 'raw' } );
                        $( this.wpEditor.getBody() ).on( "blur", _.bind( this.onEditorBlur, this ) );
                        WPEditor.setActiveWPEditor( null );
                    }

                    return this;
                },

                // Events
                events: {
                    "click": "onEditorFocus",
                    "predetch": "onPredetach"
                },
                onPredetach: function ( e )
                {
                    console.log( "WPEditor::predetach" );
                    //this._editor.$el.detach();
                    //this.reset();
                },
                onEditorFocus: function ( e )
                {
                    WPEditor.setActiveWPEditor( this.$el.attr( "id" ) );
                },
                onEditorBlur: function ( e )
                {
                    console.log( "WPEditor::onEditorBlur" );
                    WPEditor.setActiveWPEditor( null );
                    this.$el.val( this.wpEditor.getContent( { format: 'raw' } ) );
                },
                onAjaxResult: function ( e )
                {
                    this._editorHTML = e;
                    this.render();
                },
                onAjaxError: function ( e )
                {
                    console.log( e );
                }
            },
            {
                instanceMap: [],
                autoInstance: function ( $el )
                {
                    $el.find( "textarea.tutomvc-wp-editor" ).each( function ()
                    {
                        var wpEditor = new WPEditor( {
                            el: Backbone.$( this )
                        } );
                        WPEditor.instanceMap.push( wpEditor );
                    } );
                },
                setActiveWPEditor: function ( id )
                {
                    wpActiveEditor = id;
                }
            } );

        return WPEditor;
    } );