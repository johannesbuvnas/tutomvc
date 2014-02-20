require.config({
	baseUrl: TutoMVC.getURL( "system/src/scripts" ),
	paths :	{
		"jquery" : "com/tutomvc/modules/jquery",
		"backbone" : "com/tutomvc/modules/Backbone",
		"underscore" : "com/tutomvc/modules/underscore",
		"text" : "libs/requirejs-text/text",
		"base64" : "com/tutomvc/modules/base64",
		"tutomvc" : "../../../deploy/com.tutomvc.core"
	},
	shim : {
		"backbone" : {
			deps : [ "underscore", "jquery" ]
		},
		"com/tutomvc/wpadmin/MainWPAdmin" : {
			deps : [ "backbone", "tutomvc" ]
		}
	}
});
require( ["com/tutomvc/wpadmin/MainWPAdmin"] );