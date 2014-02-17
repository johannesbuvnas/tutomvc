define([
	"com/tutomvc/core/controller/event/EventDispatcher"
],
function( EventDispatcher )
{
	/* CLASS */
	function View( facadeKey )
	{
		/* VARS */
		var _this = this;
		var _facadeKey = facadeKey;
		var _mediatorMap = [];
		var _viewComponentMap = [];

		/* ACTIONS */

		/* METHODS */
		this.registerMediator = function( viewComponent, mediator )
		{
			// if( !(mediator instanceof _ns.core.view.mediator.Mediator) ) return console.log( "View::registerMediator - mediator doesn't extend Mediator" );

			_viewComponentMap[ mediator.getName() ] = viewComponent;

			mediator.initializeFacadeKey( _this.getFacadeKey() );
			_mediatorMap[ mediator.getName() ] = mediator;
			mediator.onRegister();

			return mediator;
		};
		this.retrieveMediator = function( name )
		{
			return _mediatorMap[ name ];
		};

		this.retrieveViewComponent = function( mediatorName )
		{
			return _viewComponentMap[ mediatorName ];
		};

		/* SET AND GET */
		this.getFacadeKey = function()
		{
			return _facadeKey;
		};

		// Initiate
		(function( facadeKey )
		{
			_this.super();
			_facadeKey = facadeKey;
			View.instanceMap[ _facadeKey ] = _this;
		})( facadeKey );
	}
	View.extends( EventDispatcher );

	/* STATIC */
	ClassConstructor.instanceMap = View.instanceMap = [];
	ClassConstructor.getInstance = View.getInstance = function( facadeKey )
	{
		if( !facadeKey ) return console.log( "View::getInstance - no facadeKey" );

		if( View.instanceMap[ facadeKey ] ) 
		{
			return View.instanceMap[ facadeKey ];
		}
		else
		{
			return new View( facadeKey );
		}

		return null;
	};

	function ClassConstructor( facadeKey )
	{
		return View.getInstance( facadeKey );
	}

	return ClassConstructor;
});