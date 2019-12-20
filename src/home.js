// import $ from "jquery";
import "popper.js";
import "bootstrap";
import '@fortawesome/fontawesome-free/js/fontawesome';
import '@fortawesome/fontawesome-free/js/solid';
// import '@fortawesome/fontawesome-free/js/regular';
// import '@fortawesome/fontawesome-free/js/brands';
import './js/plugin_a';
import { call_plugin_b } from './js/plugin_b';
import "./sass/home.scss";

"use strict";

(function($) {
	$(function() {
		$('#buttonsearch').click(function(){
			$('#formsearch').toggleClass( "d-xl-none" ).toggleClass( "pos_fixed" );
			$('#searchbox').focus()
			$('.openclosesearch').toggle();
		});
	});
})(jQuery);
