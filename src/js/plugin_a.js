// only for demo

"use strict";

var load_plugin_a = () => {
	console.log('plugin_a.js loaded.');
}

(function($) {
	$(function() {
		load_plugin_a();
	});
})(jQuery);
