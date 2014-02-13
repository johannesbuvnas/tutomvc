define(function()
{
	function EventDispatcher()
	{
		/* VARS */
		var _this = this;
		var _listenerMap = [];

		/* ACTIONS */
		this.dispatchEvent = function( event )
		{
			// if( !(event instanceof _ns.core.controller.event.Event) ) return console.log( "EventDispatcher::dispatch - event isnt an instance of tutomvc.core.controller.event.Event" );

			if( _listenerMap[ event.getName() ] )
			{
				for(var k in _listenerMap[ event.getName() ])
				{
					var eventListenerCallback = _listenerMap[ event.getName() ][ k ];
					eventListenerCallback( event );
				}
			}
		};

		this.getEventMap = function()
		{
			return _listenerMap;
		};

		/* METHODS */
		this.addEventListener = function( eventName, callback )
		{
			if( !_listenerMap[ eventName ] ) _listenerMap[ eventName ] = [];

			_listenerMap[ eventName ].push( callback );

			return true;
		};

		this.removeEventListener = function( eventName, callback )
		{
			if( _listenerMap[ eventName ] )
			{
				for(var k in _listenerMap[ eventName ])
				{
					var eventListenerCallback = _listenerMap[ eventName.getName() ][ k ];
					if( eventListenerCallback == callback ) 
					{
						_listenerMap[ eventName.getName() ][ k ] = null;
						delete _listenerMap[ eventName.getName() ][ k ];
					}
				}
			}

			return true;
		};
	}

	EventDispatcher.extend = function( parentClass )
	{
		parentClass.prototype = new EventDispatcher();
		parentClass.prototype.constructor = parentClass;

		return parentClass;
	};

	return EventDispatcher;
});