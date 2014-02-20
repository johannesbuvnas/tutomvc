({
    baseUrl : "../system/src/scripts/",
    include : [
    	"libs/requirejs/require",
    	"jquery",
    	"underscore",
    	"backbone",
    	"text",
    	"base64",
    	"tutomvc",
    	"com/tutomvc/wpadmin/MainWPAdmin"
    ],
    out : "../deploy/Main.pkgd.js",
    preserveLicenseComments : false,
    // optimize : "none",
    optimize : "uglify2",
    optimizeCss: "standard.keepLines.keepWhitespace",
    // cssIn: "path/to/main.css",
	// out: "path/to/css-optimized.css",
    paths :	{
		"jquery" : "com/tutomvc/modules/jquery",
		"underscore" : "com/tutomvc/modules/underscore",
		"backbone" : "com/tutomvc/modules/Backbone",
		"text" : "libs/requirejs-text/text",
		"base64" : "com/tutomvc/modules/base64",
		"tutomvc" : "../../../deploy/com.tutomvc.core"
	},
})