define(function()
{
	function Event( name, body )
	{
		/* VARS */
		var _this = this;
		this.name = name;
		this.body = body;

		/* SET AND GET */
		this.setName = function( name )
		{
			_this.name = name;
		};
		this.getName = function()
		{
			return _this.name;
		};

		this.setBody = function( body )
		{
			_this.body = body;
		};
		this.getBody = function()
		{
			return _this.body;
		};
	}

	return Event;
});