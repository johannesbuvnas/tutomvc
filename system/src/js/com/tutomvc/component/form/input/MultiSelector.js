define([
	"jquery",
	"com/tutomvc/component/form/input/SingleSelector",
	"com/tutomvc/component/model/Model",
	"com/tutomvc/component/model/proxy/Proxy",
	"com/tutomvc/core/controller/event/Event"
],
function( $, SingleSelector, Model, Proxy, Event )
{
	function MultiSelector()
	{
		var _this = this;
		var _super = this.constructor.prototype;
		var _modelFiltered;
		this.autoUpdateLabel = false;

		var construct = function()
		{
			_this.reset();
		};

		/* ACTIONS */
		this.reset = function()
		{
			_super.reset();

			_modelFiltered = new Model();

			_this.getElement().find( ".ValueObject" ).each(function()
				{
					$(this).off( "click" );
					$(this).on( "click", onSelect );
				});

			_this.getElement().addClass( "MultiSelector" );
		};

		this.select = function( proxyName, name, value )
		{
			_this.addValue( proxyName, name, value );

			/**
			*	TODO: Set value of the hidden input to JSON-object?
			*/
			var v = "";
			var i = 0;

			for(var pk in _modelFiltered.getMap())
			{
				var proxy = _modelFiltered.getProxy( pk );
				for(var vok in proxy.getMap())
				{
					if(i > 0) v += ",";
					v += proxy.get( vok ).getValue();
					i++;
				}
			}

			_this.getInput().getElement().attr( "value", v );
			_this.getElement().attr( "dataValue", v );
		};

		this.addValue = function( proxyName, name, value )
		{
			var proxy = _modelFiltered.getProxy( proxyName );
			if( !proxy ) proxy = new Proxy( proxyName );

			if( proxy.has( value ) )
			{
				proxy.remove( value );
			}
			else
			{
				proxy.addVO( name, value );
			}

			_this.getElement().find( "div.ValueObject" ).each(function()
				{
					if( $(this).attr( "dataProxyName" ) == proxy.getName() )
					{
						$(this).removeClass( "Deselected" );
						$(this).removeClass( "Selected" );

						if( proxy.has( $(this).attr("dataValue") ) ) $(this).addClass( "Selected" );
						else $(this).addClass( "Deselected" );
					}
				});

			_modelFiltered.addProxy( proxy );

			return proxy;
		};

		/* SET AND GET */
		this.getFilteredModel = function()
		{
			return _modelFiltered;
		};

		this.setLabel = function( label )
		{
			_this.button.setLabel( label );
		};

		/* EVENT HANDLERS */

		var onSelect = function( e )
		{
			e.preventDefault();

			_this.select( $( e.currentTarget ).attr( "dataProxyName" ), $( e.currentTarget ).attr( "dataName" ) , $( e.currentTarget ).attr( "dataValue" )  );

			_this.dispatchEvent( new Event( "change", _this.getValue() ) );
		};

		construct();
	}

	MultiSelector.extend = function( parentClass )
	{
		parentClass.prototype = new MultiSelector();
		parentClass.prototype.constructor = parentClass;

		return parentClass;
	};

	return SingleSelector.extend( MultiSelector );
});