# Pan Bootstrap - WordPress Starter Theme

### 特點
* 整合 webpack 4
* 使用 Bootstrap 4, SASS/SCSS

### 安裝
* npm install
* 開發: npm run start
* 正式: npm run build

### 說明
* 載入 src/ 下的 assets，輸出至 dist/ 

### 問題
1. 安裝 npm 套件會發現 node-sass 使用到 tar 這個套件版本有問題，目前尚未解決，work around 在：https://github.com/sass/node-sass/issues/2625#issuecomment-482224111

2. 特定頁面載入特定 css 未完成