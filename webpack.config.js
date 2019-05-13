// We are using node's native package 'path'
// https://nodejs.org/api/path.html
const path = require('path');

const webpack = require('webpack'); // reference to webpack Object

// Including our UglifyJS
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');

const ExtractTextPlugin = require("extract-text-webpack-plugin");
const extractSass = new ExtractTextPlugin({
    filename: "style.min.css",
});

// Constant with our paths
const paths = {
    DIST: path.resolve(__dirname, 'dist'),
    SRC: path.resolve(__dirname, 'src')
};

// Webpack configuration
module.exports = {
    entry: {
        main: path.join(paths.SRC, 'index.js'),
        home: path.join(paths.SRC, 'home.js')
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
        new UglifyJSPlugin(),
        extractSass
    ],

    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: [
                'babel-loader'
                ],
            },
            {
            	test: /\.scss$/,
            	use: extractSass.extract({
            		use: [
                        {
            			    loader: "css-loader"
                        },
                        {
                            loader: 'postcss-loader',
                            options: {
                                ident: 'postcss',
                                plugins: [
                                    require('autoprefixer')({}),
                                    require('cssnano')({ preset: 'default' })
                                ],
                                minimize: true
                            }
                        },
                        {
            			    loader: "sass-loader"
                        }
                    ],
					// use style-loader in development
					fallback: "style-loader"
				})
            }, 
            {
                test: /\.css$/, 
                use: [
                    {
                        loader: "style-loader"
                    },
                    {
            			loader: "css-loader"
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            ident: 'postcss',
                            plugins: [
                                require('autoprefixer')({}),
                                require('cssnano')({ preset: 'default' })
                            ],
                            minimize: true
                        }
                    },
                ]
            },
        ],
    },
    resolve: {
        extensions: ['.js', '.jsx'],
    },
};