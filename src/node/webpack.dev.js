const webpack = require( 'webpack' )
const merge = require( 'webpack-merge' )
const common = require( './webpack.common.js' )
const pkg = require( '../../package.json' )
const path = require( 'path' )

const argv = require( 'minimist' )( process.argv.slice( 2 ) )

if ( argv && argv.env )
{
    if ( argv.env.entry && common.entry.hasOwnProperty( argv[ 'env' ][ 'entry' ] ) )
    {
        const entry = {}
        for ( var key in common.entry )
        {
            if ( key.indexOf( argv.env.entry ) >= 0 )
            {
                entry[ key ] = common.entry[ key ];
            }
        }
        common.entry = entry
    }
}

module.exports = merge( common, {
    mode: 'development',
    devtool: 'inline-source-map',
    plugins: [
        new webpack.LoaderOptionsPlugin( {
            debug: true
        } )
    ],
    module: {
        rules: [
            {
                enforce: 'pre',
                test: /\.js$/,
                loader: 'source-map-loader'
            }
        ]
    }
} )