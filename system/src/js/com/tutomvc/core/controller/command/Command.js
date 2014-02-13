define([
	"com/tutomvc/core/CoreClass"
],
function( CoreClass )
{
	/* CLASS */
	function Command()
	{
		/* ACTIONS */
		this.execute = function( event )
		{
			
		};
	}

	/* STATIC */
	Command.extend = function( parentClass )
	{
		Command.prototype = new CoreClass();
		Command.prototype.constructor = Command;

		parentClass.prototype = new Command();
		parentClass.prototype.constructor = parentClass;

		return parentClass;
	};

	return Command;
});