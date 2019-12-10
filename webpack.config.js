// node's native package 'path'
const path = require('path');
const Fiber = require('fibers');
const webpack = require('webpack'); // reference to webpack Object
const TerserPlugin = require('terser-webpack-plugin'); // minify js
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const env = process.env.NODE_ENV || 'development';
const isDevEnv = env == 'development'

// Constant with our paths
const paths = {
    DIST: path.resolve(__dirname, 'dist'),
    SRC: path.resolve(__dirname, 'src')
};

// Webpack configuration
module.exports = {
    entry: {
        main: path.join(paths.SRC, 'index.js'),
        home: path.join(paths.SRC, 'home.js'),
    },
    output: {
        path: paths.DIST,
        filename: '[name].bundle.js'
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