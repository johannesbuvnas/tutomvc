require.config({
	baseUrl: TutoMVC.getURL( "system/src/scripts" ),
	paths :	{
		"jquery" : "libs/jquery/dist/jquery.min",
		"backbone" : "com/tutomvc/modules/Backbone",
		"underscore" : "libs/underscore/underscore",
		"base64" : "com/tutomvc/modules/base64",
		"tutomvc" : "../../../deploy/com.tutomvc.core"
	},
	map : 	{
		"com/tutomvc/modules/jquery" : { "jquery" : "jquery" },
		"*" : { "jquery" : "com/tutomvc/modules/jquery" }
	},
	// shim : {
	// 	"Main" : {
	// 		deps : [ "jquery", "base64" ]
	// 	}
	// }
	shim : {
		"backbone" : {
			deps : [ "underscore", "jquery" ]
		},
		"com/tutomvc/wpadmin/MainWPAdmin" : {
			deps : [ "jquery", "base64", "tutomvc" ]
		}
	}
});
// require( ["Main"] );
require( ["com/tutomvc/wpadmin/MainWPAdmin"] );