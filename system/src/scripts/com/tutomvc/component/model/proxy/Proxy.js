define([
	"jquery",
	"com/tutomvc/component/model/vo/ValueObject"
],
function($, ValueObject)
{
	function Proxy( name )
	{

		/* VARS */
		var _this = this;
		var _name;
		var _map = [];

		var construct = function( name )
		{
			_name = name;
		};

		/* ACTIONS */
		this.empty = function()
		{
			for(var k in _map)
			{
				var item = _map[ k ];
				item = null;
				delete _map[ k ];
			}

			return true;
		};

		/* METHODS */
		this.addVO = function( name, value )
		{
			var vo = new ValueObject( name, value );
			add( vo, vo.getValue() );
		};

		var add = function( item, key )
		{
			if(key) _map[ key ] = item;
			else _map.push( item );
		};

		this.has = function( key )
		{
			return _map[ key ] != null;
		};

		this.get = function( key )
		{
			return _map[ key ];
		};

		this.getVOByValue = function( value )
		{
			for(var voKey in _this.getMap())
			{
				var vo = _this.get( voKey );
				if( vo.getValue() == value ) return vo;
			}

			return null;
		};

		this.remove = function( key )
		{
			_map[ key ] = null;
			delete _map[ key ];
		};

		/* SET AND GET */
		this.getElement = function()
		{
			var element = $( "<div class='Proxy'></div>" );
			if( _this.getName() ) 
			{
				element.append( "<div class='Name'><span>" + _this.getName() + "</span></div>" );
			}

			var map = $( "<div class='Map'></div>" );

			for(var k in _map)
			{
				var vo = _map[ k ];
				var voElement = vo.getElement();
				voElement.attr( "dataProxyName", _this.getName() );
				map.append( voElement );
			}

			element.append( map );

			return element;
		};

		this.getMap = function()
		{
			return _map;
		};

		this.setName = function( name )
		{
			_name = name;
		};
		this.getName = function()
		{
			return _name;
		};

		this.getLength = function()
		{
			return _map.length;
		};

		construct( name );
	}

	return Proxy;
});