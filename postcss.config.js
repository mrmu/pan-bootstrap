module.exports = {
	plugins: [
		// autoprefixer 會自動增加css前綴，如: -webkit-
		'autoprefixer',
		//'postcss-import',
		//'postcss-preset-env',
		// cssnano: 最佳化及minify css
		['cssnano', { preset: 'default' }]
	]
};
