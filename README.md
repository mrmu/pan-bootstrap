# Pan Bootstrap - WordPress Starter Theme

## Feature

* A WordPress starter theme with Bootstrap 4, SASS, and a Webpack config.
* Try to demo that specific template of [hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/) could load different assets from others. In this repo the assets would be loaded differently as below, and you can modify and add new settings as you like:
  * Home/FrontPage：dist/home.bundle.js & home.min.css
  * Other Templates：dist/main.bundle.js & main.min.css

## Installation

* git clone this repo to /your-wp-site-path/wp-content/themes/
* Install packages (if you want to re-install, you can del node_modules/ and then "npm i" again):
```
npm i
```
* for development: 
```
npm run start
```
* for production (recommanded): 
```
npm run build
```
* Since mini-extract-css-plugin only can be used in production mode, and we need it to export *.min.css to make the image paths that set in .css can load correctly, so recommanded use npm run build even in local development.

## Dev Dependencies

* [@babel/core](https://babeljs.io/)
* [@babel/preset-env](https://github.com/babel/babel/tree/master/packages/babel-preset-env)
* [babel-loader](https://github.com/babel/babel-loader)
* [css-loader](https://www.npmjs.com/package/css-loader)
* [cssnano](https://github.com/cssnano/cssnano)：minify css
* [fibers](https://github.com/laverdet/node-fibers)：Dart Sass 需要用到
* [file-loader](https://github.com/webpack-contrib/file-loader)：載入字型、圖檔
* [mini-css-extract-plugin](https://github.com/webpack-contrib/mini-css-extract-plugin)：輸出 css 檔
* [postcss-loader](https://github.com/postcss/postcss-loader)：載入處理 css, minify css
* [sass](https://github.com/sass/dart-sass)：dart sass，用來取代 node-sass
* [sass-loader](https://www.npmjs.com/package/sass-loader)
* [style-loader](https://www.npmjs.com/package/style-loader)
* [terser-webpack-plugin](https://github.com/webpack-contrib/terser-webpack-plugin)
* [url-loader](https://github.com/webpack-contrib/url-loader)：將檔案載成 base64 URIs，太小的圖檔就交給它
* [webpack](https://webpack.js.org/)
* [webpack-cli](https://webpack.js.org/api/cli/)

## Dependencies

* [Bootstrap](https://getbootstrap.com/)
* [Popper.js](https://popper.js.org/)

## Acknowledgements

* [WP Bootstrap4 SASS](https://github.com/tone4hook/wp-bootstrap4-sass)
* [WP Bootstrap Navwalker](https://github.com/wp-bootstrap/wp-bootstrap-navwalker)
* [HTML 5 Blank](https://github.com/toddmotto/html5blank)

## Murmur

簡單來說，本 Repo 想以 Webpack 打包針對不同頁面所需的樣式，輸出不同的 css 檔，再由 WordPress 判斷頁面載入不同的 css 檔，避免所有頁面都要載入全部 assets。

比較需要注意的是，在處理 scss/css 的部份，若是使用開發環境指令 (npm run start)，最後會交由 style-loder 處理 (與 mini extract css plugin 不相容)，也就是不會輸出 *.min.css 檔案，而是把樣式設定拼進 *.bundle.js 裡。這樣會造成 css 裡設定的圖片路徑發生錯誤 (因為圖片路徑是跟著正式環境指令走)，所以如果 care 圖片正確顯示，<b>建議用正式環境指令 (npm run build) 來進行開發</b>，這樣最後會用 mini extract css loader 來處理 scss/css，並輸出成 *.min.css。

比較不熟悉 webpack 設定，想了解這邊它怎麼做事的朋友：
1. 可以先看 webpack.config.js，定義兩個 entry，來自 /src/ 下的 index.js 和 home.js，表示打包會從這兩個檔開始。
  * index.js：除了 import jquery, bootstrap外，還 import 了 /src/sass/style.scss，查看 style.scss 會發現它幾乎載入了所有的 scss 檔。
  * home.js：除了 import jquery, bootstrap外，還 import plugin_a 和 plugin_b 這兩個自訂的 .js，其中一個是有 export function 出來，表示 home 這頁可以載入一些不一樣的 js。最後 import 了 /src/sass/home.scss，它的內容與 style.scss 也不同，表示 home 也載入了不同的 css 設定。
2. 查看 wp-themes/pan-bootstrap/functions.php，其中 pan_bootstrap_styles() 裡定義了在首頁才 wp enqueue home的 assets，如此就實現不同 template 載入不同 assets。

## Hooks
1. pb_load_wc_assets_or_not : 如果有使用 WooCommerce，預設佈景會將非 WC 頁面的 wc assets (js/css) 停止載入，如果有特定頁面需要使用 WC styles 或 JS 功能如 blockUI，可以用此 hook 開啟。

## Memo

1. <del>安裝 npm 套件會發現 node-sass 使用到 tar 這個套件版本有問題，目前尚未解決，work around 在：https://github.com/sass/node-sass/issues/2625#issuecomment-482224111</del> 已將 node-sass 換成 dart-sass
1. extract-text-webpack-plugin 4.0 beta replace to mini-css-extract-plugin.
