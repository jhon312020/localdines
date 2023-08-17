var rbApp = rbApp || {};
var jQuery_1_8_2 = jQuery_1_8_2 || jQuery.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
        var app_url = $("#app_url").val();
		
		var audio = new Audio(app_url+'app/web/img/backend/notify.mp3');
		$(window).on('load', function() {
			//getClientMessage();
			//$(document).off("ajaxStart.dg");
			// $(document).bind("ajaxStart.dg", function(e) {
			// 	$target.closest(wrapper).addClass("sk-loading");
			// }).bind("ajaxStop.dg", function(e) {
			// 	$target.closest(wrapper).removeClass("sk-loading");
			// });
			$.post(
				"index.php?controller=pjFrontPublic&action=pjActionCheckNewOrder",
			  ).done(function (data) {
				if(data.status == 'true') {
				  $("#notify_count").text(data.orders);
				} 
			  });

			$.post(
			"index.php?controller=pjFrontPublic&action=pjActionGetCreditBalance",
			
			).done(function (data) {
				if (data.status == 'OK' && data.text != null) {
                    $("#credits_count").text(data.text.sms);
					$("#credits_count").attr("data-api-set", "true")
				} else {
					$("#credits_count").attr("data-api-set", "false");
				}
			});
			//getClientMessage();
		})
		setInterval(function() {
			//alert("new order");
			//$(document).off("ajaxStart.dg");
			// $(document).off('ajaxStart.dg');
            // $(document).off('ajaxStop.dg');
			// $(document).on("ajaxStart.dg", function () {
			// 	$('.ibox-content.sk-loading > .sk-spinner').hide();
			//global: false, });
			//$(".sk-spinner-double-bounce.sk-spinner").css("width", "0px");
			// $.post(
			//   "index.php?controller=pjFrontPublic&action=pjActionCheckNewOrder",
			// ).done(function (data) {
			//   if(data.status == 'true') {
			// 	//console.log(data.orders)
			// 	$("#notify_count").text(data.orders);
            //     GetNewOrder();

			//   } 
			//   //$(".sk-spinner-double-bounce.sk-spinner").css("width", "40px");
			// });
			$.ajax({
				type: "POST",
				async: false,
				global: false,
				url: "index.php?controller=pjFrontPublic&action=pjActionCheckNewOrder",
				success: function (data) {
				  
					if(data.status == 'true') {
						//console.log(data.orders)
						$("#notify_count").text(data.orders);
						GetNewOrder();
		
					} 
				 
				},
			  });
			
		  }, 20000);
		setInterval(getClientMessage(), 10000);
		$('#showTerms').on("click",function() {
			$.ajax({
          type: "POST",
          async: false,
          url: "index.php?controller=pjFrontPublic&action=pjActionGetAdminTerms",
          data: { 
          },
          success: function (data) {
          	console.log(data);
          	$("#jsTerms").html(data);
            $("#TermsModal").modal();
          },
          // !MEGAMIND
        });
      
      return false;
    })
		function GetNewOrder() {
			$.post(
				"index.php?controller=pjFrontPublic&action=pjActionGetNewOrder",
			  ).done(function (data) {
				if (data.status == 'true') {
					//console.log($.parseJSON(data.order));
					// var no_of_orders = data.orders.length;
					// var orders = data.orders;
                   showNotification(data.order);
				}
			});
		}
		function showNotification(newOrder) {
			//console.log(newOrder);
			audio.loop = true;
      audio.play();
			$("#o_type").text(newOrder[0].type);
			if (newOrder[0].type == "delivery") {
				var icon = "  <i class='fa fa-truck' aria-hidden='true'></i>"
				$("#o_type").addClass("btn-success");
				$("#o_type").append(icon);
				var address = newOrder[0].d_address_1+","+newOrder[0].d_address_2+","+newOrder[0].d_city+","+newOrder[0].post_code;
				$("#c_address").text(address);
			} else {
				var icon = "  <i class='fa fa-suitcase' aria-hidden='true'></i>"
				$("#o_type").addClass("btn-danger");
				$("#o_type").append(icon);
			}
			$("#c_name").text(newOrder[0].surname);
			$("#c_phone").text(newOrder[0].phone_no)
			$("#o_id").val(newOrder[0].id);
			$("#order_id").text("Web - "+newOrder[0].type+" - "+newOrder[0].order_id);

      $("#newOrderNotif").modal("show");
		}

		function getClientMessage() {
			$.post(
				"index.php?controller=pjFrontPublic&action=pjActionGetClientMessage",
			  ).done(function (data) {
				if (data.status == 'true') {
          showClientMessage(data.message);
				}
			});
		}
		function showClientMessage(message) {
			console.log(message);
			$("#jsClientTitle").html(message[0].title);
			$("#jsClientMessage").html(message[0].message);
      $("#jsClientMessageModal").modal("show");
		}



		$("#orderViewed-btn").on("click", function() {
			audio.loop = false;
      audio.currentTime = 0;
			audio.pause();
			var $o_id = $("#o_id").val();
			$.post(
				"index.php?controller=pjFrontPublic&action=pjActionOrderViewed",
				{order_id: $o_id}
			  ).done(function (data) {
				if (data.status == 'true') {
                   //showNotification(data.order);
				}
			});
			location.reload();
		})
		$('#newOrderNotif').on('hidden.bs.modal', function () {
		  audio.loop = false;
      audio.currentTime = 0;
			audio.pause();
		})
		// $("#orderConfirm-btn").on("click", function() {
		// 	var $o_id = $("#o_id").val();
		// 	$.post(
		// 		"index.php?controller=pjAdminOrders&action=pjActionConfirmOrder",
		// 		{order_id: $o_id}
		// 	  ).done(function (data) {
		// 		// if (data.status == 'true') {
		// 		// 	//console.log($.parseJSON(data.order));
        //         //    showNotification(data.order);
		// 		// }
		// 	});
		// })
		// $("#orderCancel-btn").on("click", function() {
		// 	var $o_id = $("#o_id").val();
		// 	$.post(
		// 		"index.php?controller=pjAdminOrders&action=pjActionCancelOrder",
		// 		{order_id: $o_id}
		// 	  ).done(function (data) {
		// 		// if (data.status == 'true') {
		// 		// 	//console.log($.parseJSON(data.order));
        //         //    showNotification(data.order);
		// 		// }
		// 	});
		// })
		$("#nav-item-sms").on("mouseover", function() {
			if($("#credits_count").attr("data-api-set") == "true") {
				$("#popover").popover('hide');
			}
		})
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
function confirmLogout() {
  swal({
      title: 'Are you sure?',
      text: "You will be logged out.",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Log out'
  }, function(result) {
      if (result) {
          var actionUrl = document.getElementById('logoutLink').getAttribute('data-action');
          window.location.href = actionUrl;
      } 
  });

  return false;
}

