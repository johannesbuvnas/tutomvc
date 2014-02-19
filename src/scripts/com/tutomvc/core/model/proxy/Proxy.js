define([
	"com/tutomvc/core/CoreClass"
],
function( CoreClass )
{
	function Proxy( name )
	{
		/* VARS */
		var _this = this;
		var _name = name;
		var _map = [];

		/* METHODS */
		this.add = function( item, key )
		{
			if(key) _map[ key ] = item;
			else _map.push( item );
		};

		this.has = function( key )
		{
			return _map[ key ] != undefined;
		};

		this.get = function( key )
		{
			return _map[ key ];
		};

		/* SET AND GET */
		this.getMap = function()
		{
			return _map;
		};
		this.getName = function()
		{
			return _name;
		};

		this.super();
	}

	return Proxy.extends( CoreClass );
});