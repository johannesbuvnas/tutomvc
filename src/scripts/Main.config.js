require.config( {
    //baseUrl : AppFacade.getURL( "src/scripts" ),
    //urlArgs : AppFacade.isProduction ? "v=" + AppFacade.version : "v=" + (new Date()).getTime(),
    paths: {
        "requirejs": "../../libs/scripts/requirejs/require",
        "text": "../../libs/scripts/requirejs-text/text",
        "bootstrap": "../../libs/scripts/bootstrap/dist/js/bootstrap",
        "bootstrap-select": "../../libs/scripts/bootstrap-select/dist/js/bootstrap-select"
    },
    shim: {
        backbone: {
            deps: [
                "jquery",
                "underscore"
            ]
        },
        "Main": {
            deps: [
                "backbone"
            ]
        },
        "modules/jQuery": {
            deps: [
                "jquery"
            ]
        }
    },
    map: {
        "modules/Backbone": {
            "backbone": "../../libs/scripts/backbone/backbone"
        },
        "modules/jQuery": {
            "jquery": "../../libs/scripts/jquery/jquery"
        },
        "modules/underscore": {
            "underscore": "../../libs/scripts/underscore/underscore"
        },
        "*": {
            "jquery": "modules/jQuery",
            "underscore": "modules/underscore",
            "backbone": "modules/Backbone"
        }
    }
} );

require( [ "Main" ] );