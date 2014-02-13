define([
	"jquery",
	"com/tutomvc/component/form/input/MultiSelector",
	"com/tutomvc/core/controller/event/Event"
],
function( $, MultiSelector, Event )
{
						function TagSelector()
						{
							var _this = this;
							var _super = this.constructor.prototype;
							var _filter;
							var _tagsContainer;
							var _blockFilterEvents = false;
							this.freeTagEnabled = true;

							var construct = function()
							{
								_this.reset();
							};

							/* ACTIONS */
							var adjustUI = function()
							{
								_tagsContainer.find( ".ValueObject" ).remove();

								for(var pk in _this.getFilteredModel().getMap())
								{
									for(var vok in _this.getFilteredModel().getProxy( pk ).getMap())
									{
										var selectedVOElement = _this.getFilteredModel().getProxy( pk ).getMap()[ vok ].getElement();
										selectedVOElement.addClass( "Button" );
										selectedVOElement.attr( "dataProxyName", pk );
										selectedVOElement.append( "<div class='Symbol'></div>" );
										selectedVOElement.off( "click" );
										selectedVOElement.on( "click", onSelect );
										_filter.before( selectedVOElement );
									}
								}

								if( _tagsContainer.find( ".ValueObject" ).length != 0 ) _this.button.label.addClass( "HiddenElement" );

								if(_this.isExpanded()) _this.getElement().find(".Model").css( "top", (_this.getElement().height()) + "px" );

								// _this.resetFilter();
							};

							this.resetFilter = function()
							{
								_blockFilterEvents = true;

								_filter.val("");

								// _this.collapse();

								_this.filter(null);

								_blockFilterEvents = false;
							};

							this.reset = function()
							{
								_super.reset();

								_tagsContainer = $( "<div class='Tags'></div>" );

								_filter = $( "<input type='text' autocomplete='off' />" );
								_filter.addClass( "TextBox" );
								_filter.on( "input", onFilter );
								_filter.on( "focus", onFocusIn );
								_filter.on( "focusout", onFocusOut );
								_filter.on( "keyup", onKeyUp );
								_tagsContainer.append( _filter );

								_this.button.element.addClass("clearfix");
								_this.button.element.addClass("filterButton");
								_this.button.getElement().append( _tagsContainer );
								_this.button.getElement().off( "click" );
								_this.button.getElement().on( "click", onClick );

								_this.getElement().find( ".ValueObject" ).each(function()
									{
										$(this).off( "click" );
										$(this).on( "click", onSelect );
									});

								_this.getElement().addClass( "TagSelector" );
							};

							this.select = function( proxyName, name, value )
							{
								_super.select( proxyName, name, value );

								adjustUI();
							};

							this.customSelect = function()
							{
								if(!_this.freeTagEnabled) return;

								if(_filter.val() && _filter.val().length && !_this.getFilteredModel().getProxyByValue( _filter.val() ))
								{
									_this.select( "", _filter.val(), _filter.val()  );

									_filter.val( "" );
									_this.filter( null );

									_this.dispatchEvent( new Event( "change", _this.getValue() ) );
								}
								else
								{
									_filter.val("");
								}
							};

							this.filter = function( string )
							{
								var modelElement = _this.getElement().find(".Model");
								var totalHits = 0;

								if(string) string = string.toLowerCase();
								
								modelElement.find( ".Proxy" ).each( function()
									{
										var voProxy = $(this);
										voProxy.removeClass( "HiddenElement" );
										var filterFoundInProxy = false;
										voProxy.find( ".ValueObject" ).each( function()
											{
												var vo = $(this);
												vo.removeClass( "HiddenElement" );
												vo.find( ".Name" ).html( vo.attr("dataName") );
												var name = vo.attr("dataName").toLowerCase();
												var value = vo.attr("dataValue");
												if( !string || name.indexOf( string ) > -1 || value.indexOf( string )  > -1 )
												{
													filterFoundInProxy = true;
													totalHits++;

													var index = name.indexOf( string );
													if ( index >= 0 )
													{
														var innerHTML = vo.attr("dataName");
														var innerHTML = innerHTML.substring(0,index) + "<span class='Highlight'>" + innerHTML.substring(index,index+string.length) + "</span>" + innerHTML.substring(index + string.length);
														vo.find( ".Name" ).html( innerHTML );
													}
												}
												else if( string && string.length > 0 )
												{
													vo.addClass( "HiddenElement" );
												}
											} );
										if( !filterFoundInProxy && string && string.length > 0 ) voProxy.addClass( "HiddenElement" );
									} );

								if( totalHits > 0 && !_this.isExpanded() ) _this.expand();
								else if( totalHits == 0 && _this.isExpanded() ) _this.collapse();
							};

							_this.expand = function()
							{
								_this.getElement().find(".Model").css( "top", (_this.getElement().height()) + "px" );

								_super.expand();
							};

							_this.collapse = function()
							{
								_super.collapse();
							};

							_this.focus = function()
							{
								onClick();
							};

							/* SET AND GET */
							this.setLabel = function( label )
							{
								_this.button.setLabel( label );
							};

							/* EVENT HANDLERS */
							var onClick = function( e )
							{
								if( !_filter.is( ":focus" ) ) _filter.focus();

								if( !_this.isExpanded() ) _this.expand();
							};

							var onFocusIn = function( e )
							{
								if(_blockFilterEvents) return;

								if( !_this.isExpanded() ) _this.expand();

								_this.getElement().addClass("Focus");

								_this.button.label.addClass( "HiddenElement" );
							};

							var onFocusOut = function( e )
							{
								if(_blockFilterEvents) return;

								_this.getElement().removeClass("Focus");

								if( _tagsContainer.find( ".ValueObject" ).length == 0 ) _this.button.label.removeClass( "HiddenElement" );

								// if( _this.isExpanded() ) _this.collapse();
							};

							var onSelect = function( e )
							{
								e.preventDefault();

								_this.select( $( e.currentTarget ).attr( "dataProxyName" ), $( e.currentTarget ).attr( "dataName" ) , $( e.currentTarget ).attr( "dataValue" )  );

								_this.dispatchEvent( new Event( "change", _this.getValue() ) );

								_filter.val( "" );
	    						_this.filter( null );
								_filter.focus();
							};

							var onFilter = function( e )
							{
								if(_blockFilterEvents) return;

								_this.filter( _filter.val() );
							};

							var onKeyUp = function( e )
							{
								if(_blockFilterEvents) return;

								if(e.keyCode == 13)
	    						{
	    							// Tag input text
	    							_this.customSelect();
	    						}
	    						else if( e.keyCode == 27 )
	    						{
	    							_filter.val( "" );
	    							_this.filter( "" );
	    							_filter.blur();
	    							_this.collapse();
	    						}
							};

							construct();
						}

						return MultiSelector.extend( TagSelector );
});