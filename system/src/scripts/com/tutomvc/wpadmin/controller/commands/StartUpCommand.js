define([
	"jquery",
	"com/tutomvc/wpadmin/view/meta/components/field/MetaField",
	"com/tutomvc/wpadmin/view/meta/MetaBoxMediator",
	"com/tutomvc/wpadmin/view/page/tutomvc/TutoMVCLogsMediator"
],
function( $,
	MetaField,
	MetaBoxMediator,
	TutoMVCLogsMediator
)
{
	"use strict";
	return function()
	{
		var app = this;

		var prepModel = function()
		{
		};

		var prepView = function()
		{
			app.$( ".SettingsField" ).each(function()
			{
				var metaField = new MetaField( "", $( this ) );
				var description = metaField.$( ".description" );
				$(this).append ("<p class='description'>" + description.html() + "</p>" );
			});
			app.$( ".TaxonomyMetaField" ).each(function()
			{
				var metaField = new MetaField( "", $( this ) );
				// var description = metaField.$( ".description" );
				// $(this).append ("<p class='description'>" + description.html() + "</p>" );
			});

			new MetaBoxMediator();

			if($( "#logsViewComponent" ).length)
			{
				new TutoMVCLogsMediator({
					el : $( "#logsViewComponent" )
				});
			}
		};

		var prepController = function()
		{

		};

		prepModel();
		prepView();
		prepController();
	};
});