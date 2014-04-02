({
    baseUrl : "../src/scripts/",
    paths : {
        "jquery" : "libs/jquery/jquery",
        "datetimepicker" : "libs/datetimepicker/jquery.datetimepicker",
        "jquery-mousewheel" : "libs/jquery-mousewheel/jquery.mousewheel",
        "backbone" : "com/tutomvc/modules/Backbone",
        "underscore" : "com/tutomvc/modules/underscore",
        "text" : "libs/requirejs-text/text",
        "base64" : "com/tutomvc/modules/base64",
        "tutomvc" : "libs/com.tutomvc.core"
    },
    map : {
        "*" : {
            "jquery" : "com/tutomvc/modules/jquery"
        },
        "com/tutomvc/modules/jquery" : {
            "jquery" : "jquery"
        },
        "datetimepicker" : {
            "jquery" : "jquery"
        }
    },
    shim : {
        "com/tutomvc/modules/jquery" : {
            deps : [ "jquery" ]
        },
        "backbone" : {
            deps : [ "underscore", "jquery" ]
        },
        "com/tutomvc/wpadmin/MainWPAdmin" : {
            deps : [ "backbone", "tutomvc" ]
        }
    },
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
    optimize : "uglify2"
})