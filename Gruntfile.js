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
                    //sourceMapURL: 'dist/css/tutomvc.css.map',
                    //sourceMapFilename: 'dist/css/tutomvc.css.map'
                },
                files: {
                    "dist/css/tutomvc.css": "src/less/tutomvc.less"
                }
            }
        },
        concat: {
            options: {
                stripBanners: false
            },
            tutomvc: {
                src: [
                    'bower_components/bootstrap/dist/bootstrap.js',
                    'bower_components/bootstrap-select/bootstrap.js',
                    'bower_components/select2/dist/js/select2.full.js',
                    'src/js/wp/jquery.wpattachmentforminput.js',
                    'src/js/wp/tutomvc.js'
                ],
                dest: 'dist/js/<%= pkg.name %>.js'
            }
        },
        uglify: {
            options: {
                compress: {
                    warnings: false
                },
                mangle: true,
                preserveComments: 'some'
            },
            tutomvc: {
                src: '<%= concat.tutomvc.dest %>',
                dest: 'dist/js/<%= pkg.name %>.min.js'
            }
        },
        cssmin: {
            options: {
                compatibility: 'ie8',
                keepSpecialComments: '*',
                sourceMap: true,
                advanced: false
            },
            tutomvc: {
                src: 'dist/css/<%= pkg.name %>.css',
                dest: 'dist/css/<%= pkg.name %>.min.css'
            }
        },
        watch: {
            js: {
                files: [ 'src/js/**/*.js' ],
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
    grunt.loadNpmTasks( 'grunt-contrib-concat' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );
    grunt.loadNpmTasks( 'grunt-contrib-uglify' );
    grunt.loadNpmTasks( 'grunt-contrib-cssmin' );

    // All CSS tasks
    grunt.registerTask( 'dist-css', [ 'less:compile', "cssmin:tutomvc" ] );
    // All JS tasks
    grunt.registerTask( 'dist-js', [ 'concat:tutomvc', 'uglify:tutomvc' ] );
    // All dist tasks
    grunt.registerTask( 'dist', [ 'dist-css', 'dist-js' ] );
};