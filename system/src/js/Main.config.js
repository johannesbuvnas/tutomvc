require.config({
	baseUrl: TutoMVC.getURL( "system/src/js" ),
	paths :	{
		"jquery" : "libs/jquery-1.11.0.min",
		"base64" : "com/tutomvc/modules/base64",
		"tutomvc" : "../../../src/js/com.tutomvc.core"
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
		"com/tutomvc/wpadmin/MainWPAdmin" : {
			deps : [ "jquery", "base64", "tutomvc" ]
		}
	}
});
// require( ["Main"] );
require( ["com/tutomvc/wpadmin/MainWPAdmin"] );