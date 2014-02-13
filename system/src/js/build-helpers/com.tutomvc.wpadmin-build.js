({
    baseUrl : "../",
    include : [
    	"com/tutomvc/wpadmin/MainWPAdmin.config"
    ],
    out : "../com.tutomvc.wpadmin.js",
    // generateSourceMaps : true,
    preserveLicenseComments : false,
    // optimize : "none",
    paths : {
    	jquery : "empty:",
    	"base64" : "com/tutomvc/wpadmin/modules/base64"
    }
})