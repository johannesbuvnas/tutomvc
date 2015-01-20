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
            tinymce: undefined,
            initialize: function ()
            {
                //View
                var html = WPEditor.html;
                html = html.replace( /\[ID\]/gi, this.model.get( "elementID" ) );
                this.$el.html( html );
                this.$( "textarea" ).val( this.model.get( "content" ) );
            },
            destroy: function ()
            {
                if ( this.tinymce )
                {
                    if ( parseInt( tinyMCE.majorVersion ) >= 4 ) tinyMCE.execCommand( "mceRemoveEditor", false, this.model.get( "elementID" ) ); // New versions
                    else tinyMCE.execCommand( "mceRemoveControl", false, this.model.get( "elementID" ) ); // Old versions

                    this.tinymce = null;
                }
            },
            render: function ()
            {
                this.destroy();

                if ( parseInt( tinyMCE.majorVersion ) >= 4 ) tinyMCE.execCommand( "mceAddEditor", false, this.model.get( "elementID" ) ); // New versions
                else tinyMCE.execCommand( "mceAddControl", false, this.model.get( "elementID" ) ); // Old versions

                this.tinymce = tinyMCE.get( this.model.get( "elementID" ) );
                if ( this.tinymce )
                {
                    this.tinymce.setContent( this.model.get( "content" ), { format: 'raw' } );
                    $( this.tinymce.getBody() ).on( "blur", _.bind( this.onBlur, this ) );
                }
                WPEditor.setActiveWPEditor( null );

                return this;
            },
            events: {
                "click": "onFocus",
                "blur body": "onBlur"
            },
            onFocus: function ( e )
            {
                WPEditor.setActiveWPEditor( this.model.get( "elementID" ) );
            },
            onBlur: function ()
            {
                this.model.set( { content: this.tinymce.getContent( { format: 'raw' } ) } );
                this.trigger( "blur" );
            }
        }, {
            html: undefined,
            setActiveWPEditor: function ( id )
            {
                wpActiveEditor = id;
            },
            Model: Backbone.Model.extend( {
                defaults: {
                    elementID: "",
                    content: ""
                }
            } )
        } );

        var WPEditorTextarea = Backbone.View.extend( {
                wpEditor: undefined,
                initialize: function ()
                {
                    //Model
                    WPEditorTextarea.instanceMap.push( this );
                    //View
                    this.wpEditor = new WPEditor( {
                        model: new WPEditor.Model( {
                            elementID: "tutomvc-" + WPEditorTextarea.instanceMap.length,
                            content: this.$el.val()
                        } )
                    } );
                    this.onFocus();
                    //Controller
                    this.$el.closest( ".form-group-etaetachable" ).on( "predetach", _.bind( this.onPredetach, this ) );
                    this.listenTo( this.wpEditor, "blur", this.onEditorBlur );
                },
                render: function ()
                {
                    this.$el.before( this.wpEditor.$el );
                    this.wpEditor.render();
                    return this;
                },
                // Events
                events: {
                    "focusin": "onFocus"
                },
                onFocus: function ( e )
                {
                    if ( !this.$el.hasClass( "hidden" ) )
                    {
                        this.$el.addClass( "hidden" );
                        this.render();
                        if ( this.wpEditor.tinymce ) this.wpEditor.tinymce.focus();
                        this.$el.closest( ".form-group-detachable" ).on( "reattach", _.bind( this.render, this ) );
                    }
                },
                onPredetach: function ( e )
                {
                    //console.log( "WPEditorTextarea::predetach" );
                    //this.wpEditor.$el.detach();
                },
                onEditorBlur: function ( e )
                {
                    WPEditor.setActiveWPEditor( null );
                    this.$el.val( this.wpEditor.tinymce.getContent( { format: 'raw' } ) );
                    console.log(this.$el.val());
                    this.$el.trigger("change");
                }
            },
            {
                instanceMap: [],
                autoInstance: function ( $el )
                {
                    if ( !WPEditor.html )
                    {
                        WPEditor.html = Backbone.$( "textarea.tutomvc-wp-editor-html" ).val();
                    }
                    if ( WPEditor.html )
                    {
                        $el.find( "textarea.tutomvc-wp-editor" ).each( function ()
                        {
                            var wpEditorTextarea = new WPEditorTextarea( {
                                el: Backbone.$( this )
                            } );
                        } );
                    }
                }
            } );

        return WPEditorTextarea;
    } );