var webpack = require( 'webpack' );
var pkg = require( "../../package.json" );
var ExtractTextPlugin = require( "extract-text-webpack-plugin" );

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
                test: /\.jpe?g$|\.gif$|\.png$|\.svg$|\.woff$|\.woff2$|\.eot$|\.ttf$|\.wav$|\.mp3$/,
                loader: require.resolve( "file-loader" ) + "?name=[path][name].[ext]"
            }
        ]
    },
    plugins: [
    ]
};