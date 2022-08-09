const path = require( 'path' )
const pkg = require( '../../package.json' )
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' )
const CleanWebpackPlugin = require( 'clean-webpack-plugin' )
const sass = require( 'sass' )
const sassUtils = require( 'node-sass-utils' )( sass )
const FixStyleOnlyEntriesPlugin = require( "webpack-fix-style-only-entries" );
const argv = require( 'minimist' )( process.argv.slice( 2 ) )
var entry = require( 'webpack-glob-entry' )

let plugins
if ( argv && argv.env && argv.env.keepBuildFolder )
{
	plugins = [
		new MiniCssExtractPlugin( {
			filename: '[name].css'
		} ),
		new FixStyleOnlyEntriesPlugin()
	]
}
else
{
	plugins = [
		new MiniCssExtractPlugin( {
			filename: '[name].css'
		} ),
		new FixStyleOnlyEntriesPlugin()
		// new CleanWebpackPlugin( [ __dirname + '/../../dist/' ], {
		// 	root: __dirname + '/../../', // An absolute path for the root.
		// 	verbose: true // Write logs to console.
		// 	// "dry": false // Do not delete anything, good for testing.
		// } )
	]
}
// Environment specific SCSS variables
const sassVars = require( __dirname + '/../scss/sassVars.js' )

module.exports = {
	entry: {
		tutomvc: [ './src/tsc/tutomvc.ts' ],
	},
	resolve: {
		extensions: [ '.tsx', '.ts', '.js', 'jsx' ],
		modules: [ 'node_modules', 'src/node/loaders' ]
	},
	resolveLoader: {
		modules: [ 'node_modules', 'src/node/loaders' ]
	},
	output: {
		filename: '[name].js',
		path: __dirname + '/../../dist/',
		publicPath: ''
	},
	externals: {
		jquery: 'jQuery'
	},
	plugins,
	module: {
		rules: [
			{
				test: /\.(ts|tsx)?$/,
				loader: 'ts-loader',
				exclude: /node_modules/,
				options: {
					transpileOnly: true
				}
			},
			{
				test: /\.less$/,
				use: [
					'style-loader',
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: process.env.NODE_ENV === 'production' ? false : true
						}
					},
					'less-loader'
				]
			},
			{
				test: /\.scss$/,
				use: [
					'style-loader',
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: process.env.NODE_ENV === 'production' ? false : true
						}
					},
					{
						loader: 'sass-loader',
						options: {
							sourceMap: process.env.NODE_ENV === 'production' ? false : true,
							precision: 8,
							functions: {
								'get($keys)': function ( keys ) {
									keys = keys.getValue().split( '.' )
									let result = sassVars
									let i
									for ( i = 0; i < keys.length; i++ )
									{
										result = result[ keys[ i ] ]
									}
									result = sassUtils.castToSass( result )
									return result
								}
							}
						}
					}
				]
			},
			{
				test: /\.jpe?g$|\.gif$|\.png$|\.svg$|\.woff$|\.woff2$|\.eot$|\.ttf$|\.wav$|\.mp3$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '[path][name].[ext]'
						}
					}
				]
			}
		]
	}
}
