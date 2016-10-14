var webpack = require( 'webpack' );
var pkg = require( "../../package.json" );
var merge = require( "webpack-merge" );
var commonConfig = require( "./webpack.common" );
var ExtractTextPlugin = require( "extract-text-webpack-plugin" );

var config = {
    output: {
        filename: '[name].min.js',
        path: './dist/'
    },
    module: {
        preLoaders: [
            { test: /\.js$/, loader: "custom-script-preloader" }
        ]
    },
    plugins: [
        // new webpack.optimize.CommonsChunkPlugin( {
        //     name: "stickerapp"
        // } ),
        new webpack.optimize.UglifyJsPlugin( {
            sourceMap: false,
            compress: {
                sequences: true,
                dead_code: true,
                conditionals: true,
                booleans: true,
                unused: true,
                if_return: true,
                join_vars: true,
                drop_console: true
            },
            mangle: {
                except: [ '$super', '$', 'exports', 'require' ]
            },
            output: {
                comments: false
            }
        } ),
        new webpack.optimize.DedupePlugin(),
        new webpack.DefinePlugin( {
            "process.env": {
                VERSION: JSON.stringify( pkg.version ),
                NODE_ENV: JSON.stringify( "production" ),
                ENV: JSON.stringify( "production" )
            }
        } ),
        new ExtractTextPlugin( "[name].min.css" )
    ]
};

module.exports = merge( commonConfig, config );