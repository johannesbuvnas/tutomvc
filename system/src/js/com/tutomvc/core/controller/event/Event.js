define(function()
{
	function Event( name, body )
	{
		/* VARS */
		var _name = name;
		var _body = body;

		/* SET AND GET */
		this.setName = function( name )
		{
			_name = name;
		};
		this.getName = function()
		{
			return _name;
		};

		this.setBody = function( body )
		{
			_body = body;
		};
		this.getBody = function()
		{
			return _body;
		};
	}

	return Event;
});