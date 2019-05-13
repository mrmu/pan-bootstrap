# Pan Bootstrap - WordPress Starter Theme

### 特點
* 整合 webpack 4
* 使用 Bootstrap 4, SASS/SCSS

### 安裝
* npm install
* 開發: npm run start
* 正式: npm run build

### 說明
1. 載入 src/ 下的 assets，輸出至 dist/ 
2. 實作各頁面僅載入特定 assets：
	* 首頁(home) 載入 home.bundle.js 及 style.min.css
	* 內頁(文章及其他) 載入 main.bundle.js 及 style.min.css
