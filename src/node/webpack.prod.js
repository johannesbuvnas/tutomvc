const webpack = require('webpack')
const merge = require('webpack-merge')
const common = require('./webpack.common.js')
const pkg = require('../../package.json')
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')

module.exports = merge(common, {
    mode: 'production',
    optimization: {
        minimizer: [
            new UglifyJsPlugin({
                sourceMap: false,
                uglifyOptions: {
                    compress: {
                        passes: 2,
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
                        reserved: ['$super', '$', 'exports', 'require']
                    },
                    output: { comments: false }
                }
            }),
            new OptimizeCSSAssetsPlugin({
                cssProcessorOptions: {
                    discardComments: { removeAll: true },
                    discardUnused: false
                },
                canPrint: true
            })
        ]
    },
    plugins: [
        new webpack.EnvironmentPlugin({
            VERSION: pkg.version,
            NODE_ENV: 'production'
        })
    ],
    module: {
        rules: [
            {
                enforce: 'pre',
                test: /\.js$/,
                loader: 'ugly-load'
            }
        ]
    }
})