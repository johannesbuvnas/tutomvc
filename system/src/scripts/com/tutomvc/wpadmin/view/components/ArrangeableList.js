define([
	"backbone",
	"jquery",
	"underscore"
],
function( Backbone, $, _ )
{
	"use strict";

	var ArrangeableList = Backbone.View.extend({
		itemSelector : "",
		handleSelector : "",
		constructor : function(options)
		{
			this.events = this.events || {};
			this.events[ "mousedown" + ( this.handleSelector ? " " + this.handleSelector : "" ) ] = "onMouseDown";
			this.events[ "mouseover " + this.itemSelector ] = "onMouseOver";

			Backbone.View.call(this, options);
		},
		drawDummy : function()
		{
			if(this.currentItem)
			{
				this.dummy = $( this.currentItem.get(0).outerHTML );
				this.dummy.attr("id", "");
				this.dummy.html("");
				this.dummy.css( "position", this.currentItem.ATTRIBUTES.position );
				this.dummy.css( "opacity", this.currentItem.ATTRIBUTES.opacity );
				this.dummy.css( "width", this.currentItem.ATTRIBUTES.width );
				this.dummy.css( "height", this.currentItem.ATTRIBUTES.height );
				this.dummy.css( "top", this.currentItem.ATTRIBUTES.top );
				this.dummy.css( "left", this.currentItem.ATTRIBUTES.left );
				this.dummy.css( "z-index", this.currentItem.ATTRIBUTES.z );
				this.dummy.css( "margin", this.currentItem.ATTRIBUTES.margin );
				this.dummy.css("border", "2px dashed #ebebeb");
				this.currentItem.after(this.dummy);

				this.currentItem.css( "width", this.currentItem.width() + "px" );
				this.currentItem.css( "height", this.currentItem.outerHeight() + "px" );
				this.currentItem.css( "opacity", .5 );
				this.currentItem.css( "z-index", 99 );
				this.currentItem.css( "position", "fixed" );
				this.currentItem.css( "pointer-events", "none" );
			}
		},
		removeDummy : function()
		{
			if(this.dummy)
			{
				this.dummy.before( this.currentItem );
				this.dummy.remove();
				delete this.dummy;
			}
		},

		/* EVENTS */
		onMouseDown : function(e)
		{
			if(!this.currentItem)
			{
				var $item = $(e.target);
				if(!$item.is( this.itemSelector ))
				{
					$item = $($item.parents( this.itemSelector ).get( 0 ));
				}

				this.currentItem = $item;
				this.currentItem.ATTRIBUTES = {
					width : $item.css("width"),
					height : $item.css("height"),
					position : $item.css("position"),
					opacity : $item.css("opacity"),
					top : $item.css("top"),
					left : $item.css("left"),
					margin : $item.css("margin"),
					z : $item.css("z-index"),
					offsetX : e.offsetX,
					offsetY : e.offsetY,
					index : $item.index(),
					style : $item.attr("style"),
					transform : $item.css("transform"),
					pointerEvents : $item.css("pointer-events")
				};

				$(window).on( "mouseup." + this.cid, _.bind( this.onMouseUp, this ) );
				$(window).on( "mousemove." + this.cid, _.bind( this.onMouseMove, this ) );

				this.dragging = true;
			}
		},
		onMouseMove : function(e)
		{
			if(!this.dummy) this.drawDummy();
			
			this.currentItem.css( "top", e.clientY - this.currentItem.ATTRIBUTES.offsetY );
			this.currentItem.css( "left", e.clientX - this.currentItem.ATTRIBUTES.offsetX );
			window.getSelection().removeAllRanges();
		},
		onMouseOver : function(e)
		{
			if(this.currentItem)
			{
				if( this.dummy.index() < $(e.currentTarget).index() )
				{
					$(e.currentTarget).after(this.dummy);
				}
				else
				{
					$(e.currentTarget).before(this.dummy);
				}
			}
		},
		onMouseUp : function(e)
		{
			$(window).off( "mouseup." + this.cid );
			$(window).off( "mousemove." + this.cid );

			this.removeDummy();

			this.currentItem.css( "position", this.currentItem.ATTRIBUTES.position );
			this.currentItem.css( "opacity", this.currentItem.ATTRIBUTES.opacity );
			this.currentItem.css( "width", this.currentItem.ATTRIBUTES.width );
			this.currentItem.css( "height", this.currentItem.ATTRIBUTES.height );
			this.currentItem.css( "top", this.currentItem.ATTRIBUTES.top );
			this.currentItem.css( "left", this.currentItem.ATTRIBUTES.left );
			this.currentItem.css( "z-index", this.currentItem.ATTRIBUTES.z );
			this.currentItem.css( "pointer-events", this.currentItem.ATTRIBUTES.pointerEvents );
			this.currentItem.css( "transform", this.currentItem.ATTRIBUTES.transform );
			this.currentItem.attr( "style", this.currentItem.ATTRIBUTES.style );

			delete this.currentItem;
		}
	});

	return ArrangeableList;
});