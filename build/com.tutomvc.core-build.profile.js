({
    baseUrl : "../src/scripts",
    include : [
    	"com/tutomvc/core/Function.helpers",
    	"com/tutomvc/core/controller/command/Command",
    	"com/tutomvc/core/view/mediator/Mediator",
    	"com/tutomvc/core/model/proxy/Proxy"
    ],
    out : "../deploy/com.tutomvc.core.js",
    optimize : "none"
})