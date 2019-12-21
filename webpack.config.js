// node's native package 'path'
const path = require('path');
const Fiber = require('fibers');
const webpack = require('webpack'); // reference to webpack Object
const TerserPlugin = require('terser-webpack-plugin'); // minify js
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const glob = require('glob');

const env = process.env.NODE_ENV || 'development';
const isDevEnv = env == 'development';

// Constant with our paths
const paths = {
    ROOT: path.resolve(__dirname),
    DIST: path.resolve(__dirname, 'dist'),
    SRC: path.resolve(__dirname, 'src'),
    PLUGINS: path.resolve(__dirname, '../../plugins'),
}
// const global_paths = {
//     css : [
//         // './css/3TH_PARTY.css',
//         // './style.css',
//         // paths.PLUGINS + '/MY_PLUGIN/css/display.css',
//     ],
//     js : [
//         paths.SRC + '/global.js',
//         // paths.PLUGINS + '/MY_PLUGIN/js/display.js',
//     ]
// }
const home_paths = {
    css : [
    ],
    js : [
        paths.SRC + '/home.js'
    ]
}
const archive_paths = {
    css : [
    ],
    js : [
        paths.SRC + '/archive.js'
    ]
}
const single_paths = {
    css : [
    ],
    js : [
        paths.SRC + '/single.js'
    ]
}
// Webpack configuration
module.exports = {
    entry: {
        // main: path.join(paths.SRC, 'index.js'),
        // home: path.join(paths.SRC, 'home.js'),
        // global : glob.sync("" + global_paths.js + ""),
        home : glob.sync("" + home_paths.js + ""),
        archive : glob.sync("" + archive_paths.js + ""),
        single : glob.sync("" + single_paths.js + "")
    },
    output: {
        path: paths.DIST,
        filename: '[name].min.js'
    },
    watch: true,

	externals: {
	  jquery: 'jQuery'
    },
    
    plugins: [
        new webpack.ProvidePlugin({
          $: 'jquery',
          jQuery: 'jquery',
          Popper: 'popper.js'
        }),
        new TerserPlugin(),
        new MiniCssExtractPlugin({
            filename: '[name].min.css',
            chunkFilename: '[id].min.css',
        }),
    ],

    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: [
                    'babel-loader'
                ],
            },{
                test: /\.(woff|woff2|eot|ttf|otf|png|svg|jpg|gif)$/,
                use: {
                  loader: 'url-loader',
                  options: {
                    limit: 1000, //bytes
                    name: '[hash:7].[ext]',
                    outputPath: 'assets'
                  }
                }
            },{
            	test: /\.scss$/,
                use: [
                    isDevEnv ? {loader: "style-loader"} : { loader: MiniCssExtractPlugin.loader },
                    {
                        loader: "css-loader"
                    },{
                        loader: 'postcss-loader',
                        options: {
                            ident: 'postcss',
                            plugins: [
                                require('autoprefixer')({}),
                                require('cssnano')({ preset: 'default' })
                            ],
                            minimize: true
                        }
                    },{
                        loader: "sass-loader",
                        options: {
                            implementation: require("sass"),
                            fiber: Fiber
                        }
                    }
                ],
            },{
                test:/\.css$/,
                loader:"style-loader!css-loader"
            },

        ],
    },
    resolve: {
        extensions: ['.js', '.jsx'],
    },
};