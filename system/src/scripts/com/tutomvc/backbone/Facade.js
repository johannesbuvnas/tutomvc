define([
	"backbone"
],
function(Backbone, Controller, Model)
{
	"use strict";

	var instanceMap = [];
	var Facade = Backbone.View.extend( {
		constructor : function( options )
		{
			_.extend( this, options );
			Backbone.View.call( this );

			if(!this.key) return console.log("Facade::constructor - ERROR: NO KEY");
			else if(this.key in instanceMap) return console.log("Facade::constructor - ERROR: FACADE EXISTS");
			instanceMap[ this.key ] = this;

			this.registerCommand = function( trigger, name, command )
			{
				if(typeof trigger == "string" && typeof name == "function")
				{
					command = name;
					name = trigger;
					trigger = this;
				}
				var _facade = this;
				trigger.on( name, function()
					{
						command.apply( _facade, arguments );
					} );
			}
		}
	});

	Facade.getInstance = function( key )
	{
		if( !key ) return console.log( "Facade::getInstance - ERROR: NO KEY" );

		if( instanceMap[ key ] ) return instanceMap[ key ];
		else return new Facade( {key:key} );

		return null;
	};

	return Facade;
});