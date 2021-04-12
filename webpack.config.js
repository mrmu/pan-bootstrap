// node's native package 'path'
const path = require('path');
const webpack = require('webpack'); // reference to webpack Object
const TerserPlugin = require('terser-webpack-plugin'); // minify js
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const glob = require('glob');

const env = process.env.NODE_ENV || 'development';
const isDevEnv = env == 'development';

// Constant with our paths (path.resolve: 將路徑解譯為絕對路徑)
const paths = {
    DIST: path.resolve('./dist'),
    SRC: path.resolve('./src'),
    THEME_DIR: __dirname,
    PLUGINS_DIR: path.resolve('../../plugins'),
}

// const global_paths = {
//     css : [
//         // './css/3TH_PARTY.css',
//         // './style.css',
//         // paths.PLUGINS_DIR + '/MY_PLUGIN/css/display.css',
//     ],
//     js : [
//         paths.SRC + '/global.js',
//         // paths.PLUGINS_DIR + '/MY_PLUGIN/js/display.js',
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
    // 在 entry 裡指定來源檔案 (如：home_paths.js) 及其目標檔案 (打包到 home.min.js)
    // entry 裡有幾組設定就會打包成幾個檔案
    entry: {
        // main: path.join(paths.SRC, 'index.js'),
        // home: path.join(paths.SRC, 'home.js'),

        // glob 傳入類似 regex 的查詢字串，收集符合條件的值放入陣列，最後回傳陣列
        // 因為要傳入的參數必須是字串，若傳入值為只有單一值的陣列，就前後加 "" 來轉為字串
        // (glob.sync 表示要同步處理)

        // global : glob.sync("" + global_paths.js + ""),
        home : glob.sync("" + home_paths.js + ""),
        archive : glob.sync("" + archive_paths.js + ""),
        single : glob.sync("" + single_paths.js + "")
    },

    // output 指定最終打包的路徑和檔名格式 (home.min.js, archive.min.js, single.min.js)
    output: {
        path: paths.DIST,
        filename: '[name].min.js'
    },

    // watch 設為 true 表示檔案有變動就會重新 compile 和打包
    watch: true,

    // 如果 jquery 要從外部 include (如cdn)，不參與 bundle 的話
	externals: {
	  jquery: 'jQuery'
    },
    
    // plugins 提供多種方式可自訂 webpack 建構過程
    plugins: [
        // 自動載入module，就不用到處 import or require
        new webpack.ProvidePlugin({
        //   $: 'jquery',
        //   jQuery: 'jquery',
        //   Popper: 'popper.js'
        }),

        // minify JS
        new TerserPlugin(),

        // 會從有包含 css 的 js 檔案提取 css 並建立 css 檔案
        // 大致符合 entry 的設定，如 filename
        // chunkFilename: 如果有未定義於 entry 的檔案就會使用這個命名方式
        new MiniCssExtractPlugin({
            filename: '[name].min.css',
            chunkFilename: '[id].min.css',
        }),
    ],

    // module 定義了一個專案裡不同的 module 應該如何被處理
    module: {
        // 套用不同的規則來建立個別 module 
        rules: [
            // Rule 物件包含三個部份：Conditions, Results and nested Rules
            //
            // * Rule Conditions 包含 resource 和 issuer
            //  例：在 app.js 裡 import './style.css' 時，
            //   1. resource 是 /path/to/style.css 
            //   2. issuer 是 /path/to/app.js
            //   屬性: "test, include, exclude and resource" 都會跟 resource 比對
            //   屬性: "issuer" 會跟 issuer 比對
            //
            // * Rule Results 只有在 Conditions 符合時才會被使用
            //  一個 Rule 有兩個輸出值：
            // 1. Applied loaders: 套用至 resource 的一個 loaders 陣列
            // 2. Parser options: 一個用於建立此 module parser 的 options 物件
            // 會影響 loaders 的屬性: loader, options, use.
            // enforce 屬性會影響 loader 分類: normal, pre- or post-
            // parser 屬性影響 parser
            //
            // * Nested Rules
            // 定義在 rules 和 oneOf 屬性底下
            // 
            {
                // Condition.test 可以是
                // 1. 字串: 比對的 input 開頭符合此字串，如檔案或目錄的絕對路徑
                // 2. RegExp
                // 3. function: 傳入 input 回傳比對值
                // 4. An array of Conditions
                // 5. An object: All properties must match. Each property has a defined behavior.
                test: /\.(js|jsx)$/,
                exclude: /(node_modules|bower_components)/,
                // use: useEntry 物件構成的陣列，每個 Entry 指定一個要套用的 loader

                // 若傳入的 useEntry 為字串 (如本例)，代表是 loader 屬性的簡寫 (i.e. use: [ { loader: 'babel-loader '} ]).
                // babel-loader 會幫我們 compile ES6 語法
                // 另外在 .babelrc 有定義 presets
                //   @babel/preset-env 的主要功能有兩個：
                //     (1) 將尚未被大部分瀏覽器支援的 JavaScript 語法轉換成能被瀏覽器支援的語法
                //     (2) 讓較舊的瀏覽器也能支援大部分瀏覽器能支援的語法，例如 Promise、Map、Set等。
                use: [
                    'babel-loader',
                ],
            },{
                // js 在 import css/scss 檔案時，裡面可能有指定圖檔，比如 background-image 的設定
                test: /\.(woff|woff2|eot|ttf|otf|png|svg|jpg|gif)$/,
                // url-loader 預設超過 limit 參數大小的檔案，會傳給 file-loader 處理。
                // 在 Webpack 設定，只需要設定 url-loader 即可，因為 url-loader 有一個 fallback[3] 
                // 參數，預設值是 file-loader ，所以在預設的情況下，會呼叫 file-loader，不用另外設定。
                use: {
                  loader: 'url-loader',
                  options: {
                    limit: 1000, //bytes
                    name: '[hash:7].[ext]',
                    outputPath: 'assets'
                  }
                }
            },{
            	test: /\.s[ac]ss$/i,
                use: [
                    // use 裡定義多個 loader 時，會由右至左 (或稱由下而上) 被執行
                    // 此例的執行順序為：sass-loader, postcss-loader, css-loader, style-loader/MiniCssExtractPlugin.loader
                    
                    isDevEnv ? {loader: "style-loader"} : { loader: MiniCssExtractPlugin.loader },

                    // Translates CSS into CommonJS
                    {
                        loader: "css-loader"
                    },

                    // postcss-loader: 設定在 postcss.config.js
                    'postcss-loader' ,

                    // sass-loader 會將 sass/scss 語法轉換為 css
                    {
                        loader: "sass-loader",
                        options: {
                            // implementation: 在同時安裝 node-sass 與 dart-sass 編譯器情況下，
                            // 強制切換成需要的編譯器，本例設定為使用 dart-sass
                            // dart-sass 的非同步編輯比同步快兩倍，使用fiber 能在同步的程式碼path裡呼叫非同步的importer
                            implementation: require("sass"),
                            sassOptions: {
                                fiber: false, //require('fibers'),
                            },
                        }
                    }
                ],
            },{
                test: /\.css$/i,
                use: ["style-loader", "css-loader"],
            },

        ],
    },
    // resolver 為協助定義絕對路徑的lib
    // resolve: 改變 module 如何被定義絕對路徑的方式
    // 在 import module 時，會依 .js 和 .jsx 的順序載入
    resolve: {
        extensions: ['.js', '.jsx'],
    },
};