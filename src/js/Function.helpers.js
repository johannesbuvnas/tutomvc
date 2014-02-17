"use strict";
Function.prototype.superOf = function( child )
{
	var parent = this;
	if(parent && child)
	{
		var superArgs = [];
		var evalSuperArgs = "";
		for(var i = 0; i < parent.length; i++) superArgs.push( "arg" + i );
		if(superArgs.length) evalSuperArgs += ", " + superArgs.join(",");
		
		// If super constructor isnt called
		// var regex = /[^{](this.super)[^}]/;
		// if(!regex.test( child.toString() ))
		// {
		// 	var oldChild = child;
		// 	var childArgs = [];
		// 	var evalChildArgs = "";
		// 	for(var i = 0; i < parent.length; i++) childArgs.push( "arg" + i );
		// 	if(childArgs.length) evalChildArgs += ", " + childArgs.join(",");
		// 	eval( 'child.prototype = function(' + childArgs.join(",") + '){this.super();oldChild.call( this ' + evalChildArgs + ' );};' );
		// }
		eval( 'child.prototype.super=function(' + superArgs.join(",") + '){var _this=this;this.super = new function(){};if(parent.prototype.super) this.super.super = parent.prototype.super;eval("parent.call( _this.super' + evalSuperArgs + ' );");for(var p in _this.super) if(!(p in _this)) _this[p]=_this.super[p];};' );
	}

	return child ? child : parent;
};
Function.prototype.extends = function( parent )
{
	return parent.superOf( this );
};