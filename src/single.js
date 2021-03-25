import './global';
import "./sass/single.scss";

"use strict";

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
		$('form.comment-form').on('submit', function(event){
			return validateForm();
		});
	});
})(jQuery);
