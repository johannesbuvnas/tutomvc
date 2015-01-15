module.exports = function ( grunt )
{
    "use strict";
    // Project configuration.
    grunt.initConfig( {
        pkg: grunt.file.readJSON( 'package.json' ),
        banner: '/**\n' +
        '*  Text Domain: <%= pkg.name %>\n' +
        '*/\n',
        less: {
            compile: {
                options: {
                    rootpath: "src/less/",
                    strictMath: true,
                    sourceMap: true,
                    outputSourceFiles: true,
                    sourceMapURL: 'style.css.map',
                    sourceMapFilename: 'style.css.map'
                },
                files: {
                    "dist/css/style.css": "src/less/style.less"
                }
            }
        },
        requirejs: {
            "compile-js": {
                options: {
                    paths: {
                        "requirejs": "../../libs/scripts/requirejs/require",
                        "text": "../../libs/scripts/requirejs-text/text",
                        "bootstrap": "../../libs/scripts/bootstrap/dist/js/bootstrap"
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
                    },
                    "baseUrl": "src/scripts",
                    "include": [
                        "requirejs",
                        "Main.config"
                    ],
                    optimize : "none",
                    "out": "dist/js/script.min.js"
                }
            },
            "minify-css": {
                options: {
                    cssIn: "dist/css/style.css",
                    out: "dist/css/style.min.css",
                    // optimizeCss: "standard.keepLines.keepWhitespace"
                    optimizeCss: "standard"
                }
            }
        },
        usebanner: {
            options: {
                position: 'top',
                banner: '<%= banner %>'
            },
            files: {
                src: [ "dist/css/style.css" ]
            }
        },
        watch: {
            scripts: {
                files: [ 'src/scripts/**/*.js' ],
                tasks: [ 'dist-js' ]
            },
            less: {
                files: [ 'src/less/**/*.less' ],
                tasks: [ 'dist-css' ]
            }
        }
    } );

    // Load npm tasks
    grunt.loadNpmTasks( 'grunt-contrib-less' );
    grunt.loadNpmTasks( 'grunt-banner' );
    grunt.loadNpmTasks( 'grunt-requirejs' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );

    // All CSS tasks
    grunt.registerTask( 'dist-css', [ 'less:compile', 'requirejs:minify-css', 'usebanner' ] );
    // All JS tasks
    grunt.registerTask( 'dist-js', [ 'requirejs:compile-js' ] );

    // All dist tasks
    grunt.registerTask( 'dist', [ 'dist-css', 'dist-js' ] );
    // DEFAULT
    grunt.registerTask( 'default', [ 'dist' ] );
};