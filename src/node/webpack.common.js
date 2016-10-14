var webpack = require( 'webpack' );
var pkg = require( "../../package.json" );
var ExtractTextPlugin = require( "extract-text-webpack-plugin" );
var fileLoader

module.exports = {
    entry: {
        'tutomvc': [ './src/tsc/tutomvc.js' ]
    },
    resolve: {
        extensions: [ '', '.js' ],
        modulesDirectories: [
            'node_modules',
            'src/node/loaders'
        ]
    },
    resolveLoader: {
        modulesDirectories: [
            "node_modules",
            'src/node/loaders'
        ]
    },
    output: {
        filename: '[name].js',
        path: './dist/'
    },
    externals: {
        // require("jquery") is external and available
        //  on the global var jQuery
        "jquery": "jQuery"
    },
    module: {
        loaders: [
            {
                test: /\.less$/,
                loader: ExtractTextPlugin.extract(
                    // activate source maps via loader query
                    'css?sourceMap!' +
                    'less?sourceMap'
                )
            },
            {
                test: /\.woff$|\.woff2$|\.eot$|\.ttf$/,
                loader: require.resolve( "file-loader" ) + "?name=assets/fonts/[name].[ext]"
            },
            {
                test: /\.jpe?g$|\.gif$|\.png$|\.svg$/,
                loader: require.resolve( "file-loader" ) + "?name=assets/images/[name].[ext]"
            }
        ]
    },
    plugins: [
    ]
};