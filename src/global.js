// import $ from "jquery";
// import "popper.js";
// import "bootstrap";
import 'bootstrap/dist/js/bootstrap.bundle'; //含 popper.js

import '@fortawesome/fontawesome-free/js/fontawesome';
import '@fortawesome/fontawesome-free/js/solid';
// import '@fortawesome/fontawesome-free/js/regular';
// import '@fortawesome/fontawesome-free/js/brands';
import "./sass/style.scss";

"use strict";

(function($) {
	let is_mobile_view = function() {
		return ( window.innerWidth <= 800 );
	}
	// 移入 menu 根元素時要自動 toggle show class 來顯示 menu (預設要 click)
	let menu_toggle_dropdown = function (e) {
		var _d = $(e.target).closest('.dropdown');
		_d.addClass('show');
		setTimeout(function(){
			_d[_d.is(':hover')?'addClass':'removeClass']('show');
			$('[data-toggle="dropdown"]', _d).attr('aria-expanded',_d.is(':hover'));
		},300);
	}

	$(function() {
		if (!is_mobile_view()) {
			// PC
			// 移入或移出 mega menu 的根 li 選項元素
			$('body').on('mouseenter mouseleave', '.dropdown', menu_toggle_dropdown);
		}

		$('#buttonsearch').click(function(){
			$('#formsearch').toggleClass( "d-xl-none" ).toggleClass( "pos_fixed" );
			$('#searchbox').focus()
			$('.openclosesearch').toggle();
		});
	});
})(jQuery);
