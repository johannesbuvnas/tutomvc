var webpack = require( 'webpack' );
var pkg = require( "../../package.json" );
var merge = require( "webpack-merge" );
var commonConfig = require( "./webpack.common" );
var ExtractTextPlugin = require( "extract-text-webpack-plugin" );

var config = {
    debug: true,
    devtool: "#inline-source-map",
    module: {
        preLoaders: [
            // All output '.js' files will have any sourcemaps re-processed by 'source-map-loader'.
            { test: /\.js$/, loader: "source-map-loader" }
        ]
    },
    plugins: [
        new webpack.DefinePlugin( {
            "process.env": {
                VERSION: JSON.stringify( pkg.version ),
                NODE_ENV: JSON.stringify( "development" ),
                ENV: JSON.stringify( "development" )
            }
        } ),
        new ExtractTextPlugin( "[name].css" )
    ]
};

module.exports = merge( commonConfig, config );