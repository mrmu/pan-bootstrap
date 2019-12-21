import './global';
import "./sass/style.scss";
import hljs from 'highlight.js/lib/highlight';
import hi_js from 'highlight.js/lib/languages/javascript';
import hi_css from 'highlight.js/lib/languages/css';
import hi_php from 'highlight.js/lib/languages/php';
import hi_xml from 'highlight.js/lib/languages/xml';
import hi_bash from 'highlight.js/lib/languages/bash';
import 'highlight.js/styles/github.css';

"use strict";

hljs.registerLanguage('javascript', hi_js);
hljs.registerLanguage('xml', hi_xml);
hljs.registerLanguage('bash', hi_bash);
hljs.registerLanguage('css', hi_css);
hljs.registerLanguage('php', hi_php);

// hljs.configure({useBR: true});
document.querySelectorAll('pre').forEach((block) => {
	hljs.highlightBlock(block);
});

(function($) {
	$(function() {
		/*
		basic javascript form validation
		For more information: https://getbootstrap.com/docs/4.3/components/forms/#validation
		*/
		function validateForm() {
			var form 	=  document.forms.commentForm,
				x 		= form.author.value,
				y 		= form.email.value,
				z 		= form.comment.value,
				flag 	= true,
				d1 		= document.getElementById("d1"),
				d2 		= document.getElementById("d2"),
				d3 		= document.getElementById("d3");
				
			if (x === null || x === "") {
				d1.innerHTML = single_obj.i18n.name_is_required;
				flag = false;
			} else {
				d1.innerHTML = "";
			}
			
			if (y === null || y === "") {
				d2.innerHTML = single_obj.i18n.email_is_required;
				flag = false;
			} else {
				d2.innerHTML = "";
			}
			
			if (z === null || z === "") {
				d3.innerHTML = single_obj.i18n.comment_is_required;
				flag = false;
			} else {
				d3.innerHTML = "";
			}
			
			return flag;
			
		}
		$('form.comment-form').submit(function(event){
			return validateForm();
		});
	});
})(jQuery);
