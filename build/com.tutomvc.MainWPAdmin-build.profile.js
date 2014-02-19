({
    baseUrl : "./",
    include : [
        "../../../src/js/com.tutomvc.core",
    	"com/tutomvc/wpadmin/MainWPAdmin"
    ],
    out : "./Main.js",
    // generateSourceMaps : true,
    preserveLicenseComments : false,
    optimize : "none",
    paths : {
    	jquery : "empty:",
    	"base64" : "com/tutomvc/modules/base64"
    }
})