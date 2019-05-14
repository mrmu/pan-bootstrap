# Pan Bootstrap - WordPress Starter Theme

### 特點
* 整合 webpack 4
* 使用 Bootstrap 4, SASS/SCSS
* 可讓個別特定頁面載入所需js及css，本Repo Demo 於首頁載入 dist/home.bundle.js 和 home.min.css；於其他頁面載入 dist/main.bundle.js 和 main.min.css。

### 安裝
* 將佈景 git clone 至 wp-content/themes/ 下，再執行 npm i (重新安裝可刪除 node_modules/ 後再下 npm i)
* 開發環境: npm run start
* 正式環境 (建議用): npm run build
* 在處理 scss/css 的部份，若是使用開發環境指令，最後會交由 style-loder 處理 (與 mini extract css plugin 不相容)，也就是不會輸出 *.min.css 檔案，而是把樣式設定拼進 *.bundle.js 裡。這樣會造成 css 裡設定的圖片路徑發生錯誤 (因為圖片路徑是跟著正式環境指令走)，所以如果 care 圖片正確顯示，建議可以用正式環境指令來開發，這樣最後會用 mini extract css loader 來處理 scss/css，並輸出成 *.min.css。

### 說明
* 載入 src/ 下的 assets (scss, js, images)，輸出至 dist/ 

### 問題
1. <del>安裝 npm 套件會發現 node-sass 使用到 tar 這個套件版本有問題，目前尚未解決，work around 在：https://github.com/sass/node-sass/issues/2625#issuecomment-482224111</del> 已將 node-sass 換成 dart-sass
2. extract-text-webpack-plugin 的 4.0 beta 雖可用於 webpack 4，但已超過1年未更新，想想還是換成 mini-css-extract-plugin