define([
	"com/tutomvc/core/view/View"
],
function( View )
{
	function Controller( facadeKey  )
	{
		/* VARS */
		var _this = this;
		var _facadeKey;
		var _commandMap = [];
		var _view;

		/* ACTIONS */
		this.executeCommand = function( event )
		{
			if( _commandMap[ event.getName() ] ) 
			{
				var command = new _commandMap[ event.getName() ]();
				command.initializeFacadeKey( _this.getFacadeKey() );
				command.execute( event );
			}
		};

		/* METHODS */
		this.registerCommand = function( name, commandClassReference )
		{
			_commandMap[ name ] = commandClassReference;
			_view.addEventListener( name, _this.executeCommand );
		};

		/* SET AND GET */
		this.getFacadeKey = function()
		{
			return _facadeKey;
		};

		// Initiate
		(function( facadeKey )
		{
			_facadeKey = facadeKey;
			_view = View.getInstance( _facadeKey );

			Controller.instanceMap[ _facadeKey ] = _this;
		})( facadeKey );
	}

	/* STATICS */
	Controller.instanceMap = [];
	Controller.getInstance = function( facadeKey )
	{
		if( !facadeKey ) return console.log( "Controller::getInstance - no facadeKey" );

		if( Controller.instanceMap[ facadeKey ] ) 
		{
			return Controller.instanceMap[ facadeKey ];
		}
		else
		{
			return new Controller( facadeKey );
		}

		return null;
	};

	return Controller;
});