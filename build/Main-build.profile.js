({
    baseUrl: "../src/js/",
    paths :	{
        "jquery" : "libs/jquery/jquery",
        "datetimepicker" : "libs/datetimepicker/jquery.datetimepicker",
        "jquery-mousewheel" : "libs/jquery-mousewheel/jquery.mousewheel",
        "backbone" : "com/tutomvc/modules/Backbone",
        "underscore" : "com/tutomvc/modules/underscore",
        "text" : "libs/requirejs-text/text",
        "async" : "libs/requirejs-plugins/src/async",
        "base64" : "com/tutomvc/modules/base64",
        "doc-ready/doc-ready" : "libs/doc-ready/doc-ready",
        "eventie/eventie" : "libs/eventie/eventie",
        "console" : "libs/console"
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
            deps : [ "console", "backbone" ]
        }
    },
    include: [
        "libs/requirejs/require",
        "async",
        "com/tutomvc/wpadmin/MainWPAdmin"
    ],
    out: "../deploy/Main.pkgd.js",
    preserveLicenseComments: false,
    //optimize : "none"
    optimize: "uglify2"
})