var rbApp = rbApp || {};
var jQuery_1_8_2 = jQuery_1_8_2 || jQuery.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		$("#content").on("click", ".notice-close", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).closest(".notice-box").fadeOut();
			return false;
		});
		rbApp.enableButtons = function ($dialog) {
			if ($dialog.length > 0) {
				$dialog.siblings(".ui-dialog-buttonpane").find("button").button("enable");
			}
		};
		
		rbApp.disableButtons = function ($dialog) {
			if ($dialog.length > 0) {
				$dialog.siblings(".ui-dialog-buttonpane").find("button").button("disable");
			}
		};
	});
})(jQuery_1_8_2);