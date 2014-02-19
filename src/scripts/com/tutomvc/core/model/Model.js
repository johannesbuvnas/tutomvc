define(function()
{
	/* CLASS */
	function Model( facadeKey )
	{
		/* VARS */
		var _this = this;
		var _facadefacadeKey;
		var _proxyMap = [];

		/* METHODS */
		this.registerProxy = function( proxy )
		{
			proxy.initializeFacadefacadeKey( _this.getFacadefacadeKey() );
			_proxyMap[ proxy.getName() ] = proxy;
			proxy.onRegister();

			return proxy;
		};

		this.retrieveProxy = function( proxyName )
		{
			return _proxyMap[ proxyName ];
		};

		/* SET AND GET */
		this.getFacadefacadeKey = function()
		{
			return _facadefacadeKey;
		};

		// Initiate
		(function( facadeKey )
		{
			_facadefacadeKey = facadeKey;
			Model.instanceMap[ _facadefacadeKey ] = _this;
		})( facadeKey );
	}

	/* STATIC REFERENCES */
	Model.instanceMap = [];
	Model.getInstance = function( facadeKey )
	{
		if( !facadeKey ) return console.log( "Model::getInstance - no facadeKey" );

		if( Model.instanceMap[ facadeKey ] ) 
		{
			return Model.instanceMap[ facadeKey ];
		}
		else
		{
			return new Model( facadeKey );
		}

		return undefined;
	};

	return Model;
});