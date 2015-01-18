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
                _editor: undefined,
                initialize: function ()
                {
                    console.log( "wpeditor:", this.$el.attr( "id" ), this.$el.val() );
                    //Model
                    var data =
                    {
                        action: "tutomvc/ajax/render/wp_editor",
                        nonce: TutoMVC.nonce,
                        elementID: this.$el.attr( "id" ),
                        textarea_name: this.$el.attr("name"),
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
                    //View
                    this._editor = new Backbone.View( {} );
                    this.$el.after( this._editor.$el );
                    //Controller
                },
                render: function ()
                {
                    return this;
                },

                // Events
                events: {
                    "click": "onEditorFocus"
                },
                onEditorFocus: function ( e )
                {
                    WPEditor.setActiveWPEditor( this.$el.attr( "id" ) );
                },
                onEditorBlur: function ( e )
                {
                    WPEditor.setActiveWPEditor( null );
                },
                onAjaxResult: function ( e )
                {
                    this._editor.$el.html( e );

                    var id = this.$el.attr( "id" );
                    var name = this.$el.attr( "name" );
                    this.$el.attr( "id", "" );
                    this.$el.attr( "name", "" );

                    if ( parseInt( tinyMCE.majorVersion ) >= 4 ) tinyMCE.execCommand( "mceAddEditor", false, id ); // New versions
                    else tinyMCE.execCommand( "mceAddControl", false, id ); // Old versions

                    this.wpEditor = tinyMCE.get( id );
                    $( this.wpEditor.getBody() ).on( "blur", _.bind( this.onEditorBlur, this ) );
                    WPEditor.setActiveWPEditor( null );

                    this.render();
                },
                onAjaxError: function ( e )
                {
                    console.log( e );
                }
            },
            {
                autoInstance: function ( $el )
                {
                    $el.find( "textarea.tutomvc-wp-editor" ).each( function ()
                    {
                        new WPEditor( {
                            el: Backbone.$( this )
                        } );
                    } );
                },
                setActiveWPEditor: function ( id )
                {
                    wpActiveEditor = id;
                }
            } );

        return WPEditor;
    } );