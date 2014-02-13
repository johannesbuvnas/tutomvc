define([
	"com/tutomvc/core/facade/Facade"
],
function( Facade )
{
	function CoreClass()
	{
		/* PRIVATE REFERENCES */
		var _this = this;
		var _facadeKey;

		/* ACTIONS */
		this.initializeFacadeKey = function( facadeKey )
		{
			_facadeKey = facadeKey;

			return _facadeKey;
		};

		/* SET AND GET */
		this.getFacadeKey = function()
		{
			return _facadeKey;
		};

		this.getFacade = function()
		{
			return Facade.getInstance( _facadeKey );
		};

		/* EVENTS */
		this.onRegister = function()
		{
		};
	}

	return CoreClass;
});