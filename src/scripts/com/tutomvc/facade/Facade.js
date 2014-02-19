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
			console.log( "Facade::constructor" );
		},
		// initialize : function( key )
		// {
		// 	this.key = key;
		// 	if(!this.key) return console.log("Facade::initialize - ERROR: NO KEY");
		// 	else if(this.key in instanceMap) return console.log("Facade::initialize - ERROR: FACADE EXISTS");
			
		// 	instanceMap[ this.key ] = this;
		// },
		registerCommand : function( trigger, name, command )
		{
			var _facade = this;
			trigger.on( name, function( body )
				{
					command.call( _facade, body );
				} );
		}
	} );

	Facade.getInstance = function( key )
	{
		if( !key ) return console.log( "Facade::getInstance - ERROR: NO KEY" );

		if( instanceMap[ key ] ) 
		{
			return instanceMap[ key ];
		}
		else
		{
			return new Facade( key );
		}

		return null;
	};

	return Facade;
});