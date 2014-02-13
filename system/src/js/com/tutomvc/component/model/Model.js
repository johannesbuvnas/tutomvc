define([
	"jquery",
],
function( $ )
{
	function Model()
	{
		/* VARS */
		var _this = this;
		var _facadeKey;
		var _proxyMap = [];

		var construct = function()
		{
		};

		/* ACTIONS */
		this.empty = function()
		{
			for(var k in _proxyMap)
			{
				var item = _proxyMap[ k ];
				item = null;
				delete _proxyMap[ k ];
			}

			return true;
		};

		/* METHODS */
		this.addProxy = function( proxy )
		{
			if( !proxy.getName() ) proxy.setName( _proxyMap.length );

			if( proxy.getName() ) _proxyMap[ proxy.getName() ] = proxy;
			else _proxyMap.push( proxy );

			return proxy;
		};

		this.getProxy = function( proxyName )
		{
			for(var proxyKey in _proxyMap)
			{
				var proxy = _proxyMap[ proxyKey ];
				if( proxyKey == proxyName ) return proxy;
			}

			return null;
		};

		this.getElement = function()
		{
			var element = $( "<div class='Model'></div>" );

			for(var i in _proxyMap)
			{
				var proxy = _proxyMap[ i ];
				element.append( proxy.getElement() );
			}

			return element;
		};

		this.getProxyByValue = function( value )
		{
			for(var proxyKey in _proxyMap)
			{
				var proxy = _this.getProxy( proxyKey );
				if( proxy.getVOByValue( value ) ) return proxy;
			}

			return null;
		};

		this.getMap = function()
		{
			return _proxyMap;
		};

		/* SET AND GET */

		construct();
	}

	return Model;
});