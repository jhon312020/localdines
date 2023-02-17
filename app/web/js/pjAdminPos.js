var jQuery_1_8_2 = jQuery_1_8_2 || jQuery.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
    var validator,
      // MEGAMIND
      $fdSelectedProduct, // Variables
      $prepTime,
      $totPrepTime = 0,
      $cinfo,
      $c_phone,
      $cname,
      result,
      Client,
      client,
      postcode,
      postalResult,
      $cols,
      $kordersPrepTime,
      prodSelect,
      tableID,
      shownModalName,
      defaultTableValue = 'Take Away',
      $products_in_cart = [],
      // ! MEGAMIND
      $frmCreateOrder = $("#frmCreateOrder_pos"),
      $frmCreatePosOrder = $("#frmCreateOrder_epos"),
      $frmUpdateOrder = $("#frmUpdateOrder_pos"),
      $frmUpdatePosOrder = $("#frmUpdateOrder_epos"),
      $dialogReminderEmail = $("#dialogReminderEmail"),
      $dialogConfirm = $("#dialogConfirm"),
      dialog = $.fn.dialog !== undefined,
      datepicker = $.fn.datepicker !== undefined,
      datagrid = $.fn.datagrid !== undefined,
      validate = $.fn.validate !== undefined,
      chosen = $.fn.chosen !== undefined,
      spinner = $.fn.spinner !== undefined,
      tabs = $.fn.tabs !== undefined,
      autocomplete = $.fn.autocomplete !== undefined,
      datetimeOptions = null,
      $tabs = $("#tabs"),
      tOpt = {
        select: function (event, ui) {
          $(":input[name='tab_id']").val(ui.panel.id);
        },
      };
    if ($tabs.length > 0 && tabs) {
      $tabs.tabs(tOpt);
    }
    if (chosen) {
      $("#client_id").chosen();
    }
    if ($('#datePickerOptions').length) {
      $.fn.datepicker.dates['en'] = {
        days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        daysMin: $('#datePickerOptions').data('days').split("_"),
        daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        months: $('#datePickerOptions').data('months').split("_"),
        monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        format: $('#datePickerOptions').data('format'),
        weekStart: parseInt($('#datePickerOptions').data('wstart'), 10),
      };
      $('#d_date').datepicker({
        autoclose: true,
        startDate: '-0d'
      })
      $('#p_date').datepicker({
        autoclose: true,
        startDate: '-0d'
      })
      var d = new Date();
      var todayDate = d.getDate();
      var todayMonth = d.getMonth()+1;
      var todayYear = d.getFullYear();
      $("#d_date").on("changeDate", function (e) {
        // var cd = $(this).val().split(".");
        // if (todayDate < cd[0] || todayMonth < cd[1] || todayYear < cd[2]) {
          
        //   $("#jsTimeDiv").css("display", "none");
        //   $("#jsChangeTimeDiv").css("display", "block");
        //   $("#d_time").removeClass("fdRequired");
        //   $("#d_time").removeClass("required");
        //   $("#jsChangeTimeDivSelect").addClass("fdRequired");
        //   $("#jsChangeTimeDivSelect").addClass("required");
        // } else {
        //   $("#jsTimeDiv").css("display", "block");
        //   $("#jsChangeTimeDiv").css("display", "none");
        //   $("#d_time").addClass("fdRequired");
        //   $("#d_time").addClass("required");
        //   $("#jsChangeTimeDivSelect").removeClass("fdRequired");
        //   $("#jsChangeTimeDivSelect").removeClass("required");
        // }
        validateDeliveryTime();
        if ($("#voucher-container input").val()) {
          validateVoucher($("#voucher-container input").val());
        } else {
          calPrice(1);
        }
      });
      $("#p_date").on("changeDate", function (e) {
        //var cd = $(this).val().split(".");
        // if (todayDate < cd[0] || todayMonth < cd[1] || todayYear < cd[2]) {
        //   $("#jsPTimeDiv").css("display", "none");
        //   $("#jsChangePTimeDiv").css("display", "block");
        //   $("#p_time").removeClass("fdRequired");
        //   $("#p_time").removeClass("required");
        //   $("#jsChangePTimeDivSelect").addClass("fdRequired");
        //   $("#jsChangePTimeDivSelect").addClass("required");
        // } else {
        //   $("#jsPTimeDiv").css("display", "block");
        //   $("#jsChangePTimeDiv").css("display", "none");
        //   $("#p_time").addClass("fdRequired");
        //   $("#p_time").addClass("required");
        //   $("#jsChangePTimeDivSelect").removeClass("fdRequired");
        //   $("#jsChangePTimeDivSelect").removeClass("required");
        // }
        validatePickupTime();
        if ($("#voucher-container input").val()) {
          validateVoucher($("#voucher-container input").val());
        } else {
          calPrice(1);
        }
      });
    }
    $("#jsChangeTimeDivSelect").change(function() {
      $(this).parents().find(".col-lg-4").removeClass("has-error");
      $("#delivery_time").val($(this).val());
      $("#voucher-container input").removeAttr("disabled");
    })
    $("#jsChangePTimeDivSelect").change(function() {
      $(this).parents().find(".col-lg-4").removeClass("has-error");
      $("#pickup_time").val($(this).val());
      $("#voucher-container input").removeAttr("disabled");
    })
    $('#d_time').on("keyup focusout change",function() {
      var mins;
      if ($(this).val() && !(isNaN($(this).val()))) { 
        mins = parseInt($(this).val()); 
        deliveryTime(mins);
        validateDeliveryTime();
        if ($("#delivery_time").val()) {
           $("#delivery_time").parent().parent().removeClass("has-error")
        }
        if ($("#voucher-container input").val()) {
          validateVoucher($("#voucher-container input").val());
        } else {
          calPrice(1);
          $("#voucher-container input").removeAttr("disabled");
        }
      } else {
        $(this).val('');
        $("#delivery_time").val('');
        $("#d_time").parent().addClass("has-error");
        $("#aproxDt").text('');
        if ($("#voucher-container input").val()) {
          calPrice(1)
        }
      }
    })
    // $('#d_time').on("change",function() {
    //   var mins;
    //   if ($(this).val() && !(isNaN($(this).val()))) {  
    //     mins = parseInt($(this).val()); 
    //     deliveryTime(mins);
    //     validateDeliveryTime();
    //     if ($("#delivery_time").val()) {
    //        $("#delivery_time").parent().parent().removeClass("has-error")
    //     }
        
    //     if ($("#voucher-container input").val()) {
    //       validateVoucher($("#voucher-container input").val());
    //     } else {
    //       calPrice(1);
    //       $("#voucher-container input").removeAttr("disabled");
    //     }
    //   } else {
    //     $(this).val('');
    //     $("#delivery_time").val('');
    //     $("#delivery_time").parent().parent().addClass("has-error");
    //     $("#aproxDt").text('');
    //     if ($("#voucher-container input").val()) {
    //       calPrice(1);
    //     }
    //   }
    // })
    // $('#d_time').on("focusout",function() {
    //   var mins;
    //   if ($(this).val() && !(isNaN($(this).val()))) {  
    //     mins = parseInt($(this).val()); 
    //     deliveryTime(mins);
    //     validateDeliveryTime();
    //     if ($("#delivery_time").val()) {
    //        $("#delivery_time").parent().parent().removeClass("has-error")
    //     }
    //     if ($("#voucher-container input").val()) {
    //       validateVoucher($("#voucher-container input").val());
    //     } else {
    //       calPrice(1);
    //       $("#voucher-container input").removeAttr("disabled");
    //     }
    //   } else {
    //     $("#delivery_time").val('');
    //     $(this).val('');
    //     $("#delivery_time").parent().parent().addClass("has-error");
    //     $("#aproxDt").text('');
    //     if ($("#voucher-container input").val()) {
    //       calPrice(1);
    //     }
    //   }
    // })
    $('#p_time').on("keyup change focusout",function() {
      var mins;
      if($(this).val()) {
        mins = parseInt($(this).val());
        pickupTime(mins);
        validatePickupTime();
        if ($("#pickup_time").val()) {
          $("#pickup_time").parent().removeClass("has-error")
        }
        if ($("#voucher-container input").val()) {
          validateVoucher($("#voucher-container input").val());
        } else {
          calPrice(1);
          $("#voucher-container input").removeAttr("disabled");
        }
      } else {
        $("#pickup_time").val('');
       $("#pickup_time").parent().addClass("has-error");
        $("#aproxPt").text('');
        if ($("#voucher-container input").val()) {
          calPrice(1);
        }
      }
    })
    // $('#p_time').on("change",function() {
    //   var mins;
    //   if($(this).val()) {
    //     mins = parseInt($(this).val());
    //     pickupTime(mins);
    //     validatePickupTime();
    //     if ($("#pickup_time").val()) {
    //        $("#pickup_time").parent().parent().removeClass("has-error")
    //     }
    //     if ($("#voucher-container input").val()) {
    //       validateVoucher($("#voucher-container input").val());
    //     } else {
    //       calPrice(1);
    //       $("#voucher-container input").removeAttr("disabled");
    //     }
    //   } else {
    //     console.log("came here");
    //     console.log("261",$("#pickup_time").parent().parent());
    //     $("#pickup_time").val('');
    //     $("#pickup_time").parent().parent().addClass("has-error");
    //     $("#aproxPt").text('');
    //     if ($("#voucher-container input").val()) {
    //       calPrice(1);
    //     }
    //   }
    // })
    // $('#p_time').on("focusout",function() {
    //   var mins;
    //   if($(this).val()) {
    //     mins = parseInt($(this).val());
    //     pickupTime(mins);
    //     validatePickupTime();
    //     if ($("#pickup_time").val()) {
    //        $("#pickup_time").parent().parent().removeClass("has-error")
    //     }
    //     if ($("#voucher-container input").val()) {
    //       validateVoucher($("#voucher-container input").val());
    //     } else {
    //       calPrice(1);
    //       $("#voucher-container input").removeAttr("disabled");
    //     }
    //   } else {
    //     $("#pickup_time").val('');
    //     $("#pickup_time").parent().parent().addClass("has-error");
    //     $("#aproxPt").text('');
    //     if ($("#voucher-container input").val()) {
    //       calPrice(1);
    //     } 
    //   }
    // })
    $('#phone_no').on("focusout", function() {
      validatePhoneNumber($(this).val());
    })
    $('#c_email').on("focusout", function() {
      validateEmail($(this).val());
    })
    $('[name=email_offer]').on("click", function() {
      $('[name=email_offer]').attr("data-wt","valid");
    })
    $('#voucher-container input').on("input keyup", function() {
      if ($(this).val()) {
        validateVoucher($(this).val());
      } else {
        $("#voucher-container input").parent().removeClass("has-error");
        var active_frm;
        if ($frmCreateOrder.length > 0) {
          active_frm = $("#frm-type").val();
        }     
        if ($frmUpdateOrder.length > 0) {
          active_frm = "#frmUpdateOrder_pos";
        }
        if ($frmUpdatePosOrder.length > 0) {
          active_frm = "#frmUpdateOrder_epos";
        }
        $(active_frm + " #vouchercode").val('');
        calPrice(1);
      }   
    })
    // $('#voucher-container input').on("keyup", function() {
      
    //   if ($(this).val()) {
    //     validateVoucher($(this).val());
    //   } else {
    //     $("#voucher-container input").parent().removeClass("has-error");
    //     calPrice(1);
    //   }
      
    // })
    // $('#voucher-container input').on("input", function() {
      
    //   if ($(this).val()) {
    //     validateVoucher($(this).val());
    //   } else {
    //     $("#voucher-container input").parent().removeClass("has-error");
    //     calPrice(1);
    //   }
    // })
      if ($frmCreateOrder.length > 0 || $frmUpdateOrder.length > 0 || $frmUpdatePosOrder.length > 0) {
        if($frmCreateOrder.length > 0) {
          deliveryTime(40);
        }
        $.validator.addMethod("pickupTime", function (value, element) {
          if ($(element).attr("data-wt") == "open") {
            return true;
          } else {
            return false;
          }
        });
        $.validator.addMethod("deliveryTime", function (value, element) {
          if ($(element).attr("data-wt") == "open") {
            return true;
          } else {
            return false;
          }
        });
        $.validator.addMethod("phoneNumber", function (value, element) {
          if ($(element).attr("data-wt") == "valid") {
            return true;
          } else {
            return false;
          }
        });
        $.validator.addMethod("emailOffer", function (value, element) {
          if ($(element).attr("data-wt") == "valid") {
            return true;
          } else {
            return false;
          }
        });
        $.validator.addMethod("mobileDelivery", function (value, element) {
          
          if ($(element).attr("data-wt") == "valid") {
            return true;
          } else {
            return false;
          }
        });
        $.validator.addMethod("emailReceipt", function (value, element) {
          if ($(element).attr("data-wt") == "valid") {
            return true;
          } else {
            return false;
          }
        });
        $.validator.addMethod("voucher", function (value, element) {
          if ($(element).attr("data-wt") == "valid") {
            return true;
          } else if($(element).val() == "") {
            return true;
          } else {
            return false;
          }
        });
        $.validator.addMethod("delivery_fee", function (value, element) {
          if ($(element).attr("data-wt") == "valid") {
            return true;
          } else if($(element).val() == "") {
            return true;
          } else {
            return false;
          }
        });
        $.validator.addMethod("inputPostCode", function (value, element) {
          var type = $(".onoffswitch-order .onoffswitch-checkbox").prop("checked");
          var override = $(".onoffswitch-overridePc .onoffswitch-checkbox").prop("checked");
          console.log('override', override);
          console.log('type', type);
          $("#postCodeErr").css("display","none");
          if (type) {
            return true;
          } else if (override) {
            return true;
          } else if ($(element).attr("data-wt") == "valid") {
            return true;
          } else if($(element).val() == "") {
            return true;
          } else {
            return false;
          }
        });
        $frmCreatePosOrder.validate({ignore: ".ignore",
          rules: {
            p_dt: {
              pickupTime: true,
            },
            d_dt: {
              deliveryTime: true,
            },
            c_email: {
              remote:
                "index.php?controller=pjAdminOrders&action=pjActionCheckClientEmail",
            },
            mobile_delivery_info: {
              required: true,
              mobileDelivery: true,
            },
            mobile_offer: {
              required: true,
            },
            email_receipt: {
              required: true,
              emailReceipt: true,
            },
            email_offer: {
              emailOffer: true,
            },
            phone_no: {
              phoneNumber: true,
            },
            voucher_code: {
              required: false,
              voucher: true,
            },
            delivery_fee: {
              required: false,
              delivery_fee: true,
            },
          },
          messages: {
            p_dt: {
              pickupTime: myLabel.restaurant_closed,
            },
            d_dt: {
              deliveryTime: myLabel.restaurant_closed,
            },
            c_email: {
              remote: myLabel.email_exists,
            },
            mobile_delivery_info: {
              mobileDelivery: myLabel.mobileDelivery_err,
            },
            email_receipt: {
              emailReceipt: myLabel.emailReceipt_err,
            },
            email_offer: {
              emailOffer: "This field is required",
            },
            phone_no: {
              phoneNumber: myLabel.phoneNumber_err,
            },
            voucher_code: {
              voucher: "voucher code is invalid",
            },
            delivery_fee: {
              delivery_fee: myLabel.delivery_fee_err,
            },
            post_code: {
              inpu: "Invalid / Post code is not available for delivery",
            },
          },
          errorPlacement: function(error, element) {
            if (element.attr("type") == "radio") {
              error.insertAfter(element.parent());
              error.css('color','#a94442');
              error.siblings("label").css('color','#ed5565')
            } else if (element.siblings("span").hasClass("input-group-addon") || element.attr('name') == 'post_code') {
              error.insertAfter(element.parent())
            } else {
              error.insertAfter(element);
            }
          },
          ignore: "",
          invalidHandler: function (form, validator) {
            var $ele = $(validator.errorList[0].element);
            var $closest = $ele.closest(".tab-pane");
            var id = $closest.attr("id");
            $('.nav a[href="#' + id + '"]').tab("show");
            $("#submitJs").prop('disabled', false);
          },
          submitHandler: function (form) {
            $("#submitJs").prop('disabled', true);
            var valid = true;
            var $ele = null;
            var $err_ele = [];
            // MEGAMIND
            if ($("#fdOrderList_1").find("tbody.main-body > tr").length == 0) { 
              var errTab = $("#orderTab li").first();
              var errTabContent = errTab.children("a").attr("href");
              $("#orderTab li.active").removeClass("active");
              $("#orderTabContent .tab-pane.active").removeClass("active in");
              errTab.addClass("active");
              $(errTabContent).addClass("active in");
              var valid = false;
            } else {
              $('#fdOrderList_1').find("tbody.main-body > tr.fdLine").each(function() {
                var index = $(this).attr('data-index'),
                  $product = $('#fdProduct_' + index),
                  $price = $('#fdPrice_' + index);
                if($price.val() == '') {
                  $price.parent().addClass('has-error');
                  var errTab = $("#orderTab li").first();
                  var errTabContent = errTab.children("a").attr("href");
                  $("#orderTab li.active").removeClass("active");
                  $("#orderTabContent .tab-pane.active").removeClass("active in");
                  errTab.addClass("active");
                  $(errTabContent).addClass("active in");
                  valid = false;
                  $ele = $product;
                  $err_ele.push($price);
                } else {
                  $price.parent().removeClass('has-error');
                }
              });
            }
            if (valid == true) {
              $(window).off('beforeunload');
              form.submit();
             } else {
               $("#submitJs").prop('disabled', false);
             }
          },
        });
        $frmCreateOrder.validate({ ignore: ".ignore",
          rules: {
            p_dt: {
              pickupTime: true,
            },
            d_dt: {
              deliveryTime: true,
            },
            c_email: {
              remote:
                "index.php?controller=pjAdminOrders&action=pjActionCheckClientEmail",
            },
            mobile_delivery_info: {
              required: true,
              mobileDelivery: true,
            },
            mobile_offer: {
              required: true,
            },
            email_receipt: {
              required: true,
              emailReceipt: true,
            },
            email_offer: {
              emailOffer: true,
            },
            phone_no: {
              phoneNumber: true,
            },
            voucher_code: {
              required: false,
              voucher: true,
            },
            delivery_fee: {
              required: false,
              delivery_fee: true,
            },
            post_code: {
              inputPostCode: true
            }
          },
          messages: {
            p_dt: {
              pickupTime: myLabel.restaurant_closed,
            },
            d_dt: {
              deliveryTime: myLabel.restaurant_closed,
            },
            c_email: {
              remote: myLabel.email_exists,
            },
            mobile_delivery_info: {
              mobileDelivery: myLabel.mobileDelivery_err,
            },
            email_receipt: {
              emailReceipt: myLabel.emailReceipt_err,
            },
            email_offer: {
              emailOffer: "This field is required",
            },
            phone_no: {
              phoneNumber: myLabel.phoneNumber_err,
            },
            voucher_code: {
              voucher: "voucher code is invalid",
            },
            delivery_fee: {
              delivery_fee: myLabel.delivery_fee_err,
            },
            post_code: {
              inputPostCode: "Invalid / Post code is not available for delivery",
            },
          },
          errorPlacement: function(error, element) {
            if (element.attr("type") == "radio") {
              error.insertAfter(element.parent());
              error.css('color','#a94442');
              error.siblings("label").css('color','#ed5565')
            } else if (element.siblings("span").hasClass("input-group-addon") || element.attr('name') == 'post_code') {
              error.insertAfter(element.parent())
            } else{
              error.insertAfter(element);
            }
          },
          ignore: "",
          invalidHandler: function (form, validator) {
            console.log($(validator.errorList[0].element));
            var $ele = $(validator.errorList[0].element);
            var $closest = $ele.closest(".tab-pane");
            var id = $closest.attr("id");
            $('.nav a[href="#' + id + '"]').tab("show");
            $("#submitJs").prop('disabled', false);
          },
          submitHandler: function (form) {
            $("#submitJs").prop('disabled', true);
            var valid = true;
            var $ele = null;
            var $err_ele = [];
            // MEGAMIND
            if ($("#fdOrderList_1").find("tbody.main-body > tr").length == 0) { 
              var errTab = $("#orderTab li").first();
              var errTabContent = errTab.children("a").attr("href");
              $("#orderTab li.active").removeClass("active");
              $("#orderTabContent .tab-pane.active").removeClass("active in");
              errTab.addClass("active");
              $(errTabContent).addClass("active in");
              var valid = false;
            } else {
              $('#fdOrderList_1').find("tbody.main-body > tr.fdLine").each(function() {
                var index = $(this).attr('data-index'),
                  $product = $('#fdProduct_' + index),
                  $price = $('#fdPrice_' + index);
                if($price.val() == '')
                {
                  $price.parent().addClass('has-error');
                  var errTab = $("#orderTab li").first();
                  var errTabContent = errTab.children("a").attr("href");
                  $("#orderTab li.active").removeClass("active");
                  $("#orderTabContent .tab-pane.active").removeClass("active in");
                  errTab.addClass("active");
                  $(errTabContent).addClass("active in");
                  valid = false;
                  $ele = $product;
                  $err_ele.push($price);
                }else{
                  $price.parent().removeClass('has-error');
                }
              });
            }
            //return false;
            
            // var firstRowIndex = $('#fdOrderList_1').find("tbody.main-body > tr:first-child").attr("data-index");
            // var lastRow = $("#fdOrderList_1 tr:last");
            // var lastRowIndex = $("#fdOrderList_1 tr:last").attr("data-index");
            // var $product_last = $("#fdProduct_"+lastRowIndex);
            // var $price_last = $("#fdPrice_"+lastRowIndex);
            
            // if ($product_last.val() == "" && $price_last.val() == "") {
            //   if (lastRowIndex == firstRowIndex) {
            //     $product_last.parent().parent().addClass("has-error");
            //     $price_last.parent().addClass('has-error');
            //     valid = false;
            //     $ele = $product_last;
            //     $err_ele.push($product_last);
            //   }
            //   else{
  
            //     lastRow.remove();
            //     $product_last.parent().parent().removeClass("has-error");
            //     $price_last.parent().removeClass('has-error');
            //     $('#fdOrderList_1').find("tbody.main-body > tr.fdLine").each(function() {
            //         var index = $(this).attr('data-index'),
            //           $product = $('#fdProduct_' + index),
            //           $price = $('#fdPrice_' + index);
            //         if($price.val() == '')
            //         {
            //           $price.parent().addClass('has-error');
            //           valid = false;
            //           $ele = $product;
            //           $err_ele.push($price);
            //         }else{
            //           $price.parent().removeClass('has-error');
            //         }
            //       });
            //     //valid = true;
            //   }
            // } else {
            //   $('#fdOrderList_1').find("tbody.main-body > tr.fdLine").each(function() {
            //         var index = $(this).attr('data-index'),
            //           $product = $('#fdProduct_' + index),
            //           $price = $('#fdPrice_' + index);
            //         if($price.val() == '')
            //         {
            //           $price.parent().addClass('has-error');
            //           valid = false;
            //           $ele = $product;
            //           $err_ele.push($price);
            //         }else{
            //           $price.parent().removeClass('has-error');
            //         }
            //       });
  
            // }
            
            // !MEGAMIND
            //valid = false;
            if (valid == true) {
              $(window).off('beforeunload');
              form.submit();
             } else {
                // $('html,body').animate({
                //     scrollTop: $err_ele[0].offset().top
                // }, 1000);
                $("#submitJs").prop('disabled', false);
             }
          },
        });
        $frmUpdateOrder.validate({ignore:":not(:visible)",
          rules: {
            p_dt: {
              pickupTime: true,
            },
            d_dt: {
              deliveryTime: true,
            },
            c_email: {
              remote:
                "index.php?controller=pjAdminOrders&action=pjActionCheckClientEmail",
            },
            mobile_delivery_info: {
              required: true,
              mobileDelivery: true,
            },
            mobile_offer: {
              required: true,
            },
            email_receipt: {
              required: true,
              emailReceipt: true,
            },
            email_offer: {
              required: true,
            },
            phone_no: {
              phoneNumber: true,
            },
            // sms_email: {
            //   email: true,
            // },
            voucher_code: {
              required: false,
              voucher: true,
            },
            delivery_fee: {
              required: false,
              delivery_fee: true,
            },
            post_code: {
              inputPostCode: true
            }
          },
          messages: {
            p_dt: {
              pickupTime: myLabel.restaurant_closed,
            },
            d_dt: {
              deliveryTime: myLabel.restaurant_closed,
            },
            c_email: {
              remote: myLabel.email_exists,
            },
            mobile_delivery_info: {
              mobileDelivery: myLabel.mobileDelivery_err,
            },
            email_receipt: {
              emailReceipt: myLabel.emailReceipt_err,
            },
            phone_no: {
              phoneNumber: myLabel.phoneNumber_err,
            },
            // sms_email: {
            //   email: myLabel.email_err,
            // },
            voucher_code: {
              voucher: myLabel.voucher_err,
            },
            delivery_fee: {
              delivery_fee: myLabel.delivery_fee_err,
            },
            post_code: {
              inputPostCode: "Invalid / Post code is not available for delivery",
            },
          },
           errorPlacement: function(error, element) {
              if (element.attr("type") == "radio") {
                error.insertAfter(element.parent());
                error.css('color','#a94442');
                error.siblings("label").css('color','#ed5565')
                //element.css('border','2px solid red');
              } else if (element.siblings("span").hasClass("input-group-addon") || element.attr('name') == 'post_code') {
                error.insertAfter(element.parent())
              } else{
                error.insertAfter(element);
              }
          },
          ignore: "",
          invalidHandler: function (form, validator) {
            console.log('845 invalidHandler ');
            console.log($(validator.errorList[0].element));
            var $firstEle = $(validator.errorList[0].element);
            var $closest = $firstEle.closest(".tab-pane");
            var id = $closest.attr("id");
            $('.nav a[href="#' + id + '"]').tab("show");
            $("#submitJs").prop('disabled', false);
          },
          submitHandler: function (form) {
            console.log('852 submithandler ');
            $("#submitJs").prop('disabled', false);
            var valid = true;
            var $ele = null;
            var $err_ele = [];
            // if($("#inputPostCode").val($("#inputPostCode").val().trim())){
            //   valid = false;
            // };
            // if ($("#inputPostCode").val()) {
            //   var $pc = $("#inputPostCode").val();
            //   console.log($pc);
            //   $pc = $pc.trim();
            //   console.log($pc);
            //   if (!$pc) {
            //     valid = false;
            //   }
            // }
            // MEGAMIND

            if ($("#fdOrderList_1").find("tbody.main-body > tr").length == 0) { 
              var errTab = $("#orderTab li").first();
              var errTabContent = errTab.children("a").attr("href");
              $("#orderTab li.active").removeClass("active");
              $("#orderTabContent .tab-pane.active").removeClass("active in");
              errTab.addClass("active");
              $(errTabContent).addClass("active in");
              valid = false;
            } else {
              $('#fdOrderList_1').find("tbody.main-body > tr.fdLine").each(function() {
                var index = $(this).attr('data-index'),
                  $product = $('#fdProduct_' + index),
                  $price = $('#fdPrice_' + index);
                if($price.val() == '')
                {
                  $price.parent().addClass('has-error');
                  var errTab = $("#orderTab li").first();
                  var errTabContent = errTab.children("a").attr("href");
                  $("#orderTab li.active").removeClass("active");
                  $("#orderTabContent .tab-pane.active").removeClass("active in");
                  errTab.addClass("active");
                  $(errTabContent).addClass("active in");
                  valid = false;
                  $ele = $product;
                  $err_ele.push($price);
                }else{
                  $price.parent().removeClass('has-error');
                }
              });
              if (valid == false) {
               $("#submitJs").prop('disabled', false);
             }
            }
             
           
           
            // var firstRowIndex = $('#fdOrderList_1').find("tbody.main-body > tr:first").attr("data-index");
            // var lastRow = $("#fdOrderList_1 tr:last");
            // var lastRowIndex = $("#fdOrderList_1 tr:last").attr("data-index");
            // var $product_last = $("#fdProduct_"+lastRowIndex);
            // var $price_last = $("#fdPrice_"+lastRowIndex);
            
            // if ($product_last.val() == "" && $price_last.val() == "") {
            //   if (lastRowIndex == firstRowIndex) {
                
            //     $product_last.parent().parent().addClass("has-error");
            //     $price_last.parent().addClass('has-error');
            //     valid = false;
            //     $ele = $product_last;
            //     $err_ele.push($product_last);
            //   }
            //   else{
                
            //     lastRow.remove();
            //     $product_last.parent().parent().removeClass("has-error");
            //     $price_last.parent().removeClass('has-error');
            //     $('#fdOrderList_1').find("tbody.main-body > tr.fdLine").each(function() {
            //         var index = $(this).attr('data-index'),
            //           $product = $('#fdProduct_' + index),
            //           $price = $('#fdPrice_' + index);
            //         if($price.val() == '')
            //         {
            //           $price.parent().addClass('has-error').focus();
            //           valid = false;
            //           $ele = $product;
            //           $err_ele.push($price);
            //         }else{
            //           $price.parent().removeClass('has-error');
            //         }
            //       });
            //   }
            // } else {
              
            //   $('#fdOrderList_1').find("tbody.main-body > tr.fdLine").each(function() {
            //       var index = $(this).attr('data-index'),
            //         $product = $('#fdProduct_' + index),
            //         $price = $('#fdPrice_' + index);
            //       if($price.val() == '')
            //       {
            //         $price.parent().addClass('has-error').focus();
            //         valid = false;
            //         $ele = $product;
            //         $err_ele.push($price);
            //       }else{
            //         $price.parent().removeClass('has-error');
            //       }
            //     });
            // }
            
            // !MEGAMIND
            //valid = false;
            if (valid == true) {
              $(window).off('beforeunload');
              form.submit();
            } else {
                // $('html,body').animate({
                //     scrollTop: $err_ele[0].offset().top
                // }, 1000);
             }
          },
        });
  
        bindTouchSpin();
        if ($frmUpdateOrder.length > 0) {
          if ($("#fdOrderList_1").find("tbody.main-body > tr").length > 0) {
            calPrice(0);
            $("#fdOrderList_1").show();
          }
        }
        if ($frmUpdatePosOrder.length > 0) {
          if ($("#fdOrderList_1").find("tbody.main-body > tr").length > 0) {
            calPrice(0);
            $("#fdOrderList_1").show();
          }
        }
      }
      function bindTouchSpin() {
        if ($("#fdOrderList_1").length > 0) {
          $("#fdOrderList_1").find(".pj-field-count").TouchSpin({
            verticalbuttons: false,
            buttondown_class: "btn btn-white",
            buttonup_class: "btn btn-white",
            min: 1,
            max: 4294967295,
          });
        }
      }
      function validatePickupTime() {
        var $frm = $frmCreateOrder;
        if ($frmUpdateOrder.length > 0) {
          $frm = $frmUpdateOrder;
        }
        $.ajax({
          type: "POST",
          async: false,
          url: "index.php?controller=pjAdminOrders&action=pjActionCheckPickup",
          data: {
            type: function () {
              return $frm.find("input[name='type']").is(":checked")
                ? "delivery"
                : "pickup";
            },
            p_location_id: function () {
              return $frm.find("select[name='p_location_id']").val();
            },
            // MEGAMIND
            p_date: function () {
              return $frm.find("input[name='p_date']").val();
            },
            p_time: function () {
              
              return $("#pickup_time").val();
            }
          },
          success: function (data) {
            if (data == "false") {
              $frm.find("input[name='p_date']").attr("data-wt", "closed").valid();
            } else {
              $frm.find("input[name='p_date']").attr("data-wt", "open").valid();
            }
          },
          // !MEGAMIND
        });
      }
      function validateDeliveryTime() {
        var $frm = $frmCreateOrder;
       
        if ($frmUpdateOrder.length > 0) {
          $frm = $frmUpdateOrder;
        }
        $.ajax({
          type: "POST",
          async: false,
          url: "index.php?controller=pjAdminPosOrders&action=pjActionCheckDelivery",
          data: {
            type: function () {
              return $frm.find("input[name='type']").is(":checked")
                ? "delivery"
                : "pickup";
            },
            d_location_id: function () {
              
              return $frm.find("select[name='d_location_id']").val();
            },
            // MEGAMIND
            d_date: function () {
              
              return $frm.find("input[name='d_date']").val();
            },
            d_time: function () {
          
              return $("#delivery_time").val();
            },
          },
          success: function (data) {
            if (data == "false") {
              $frm.find("input[name='d_date']").attr("data-wt", "closed").valid();
            } else {
              $frm.find("input[name='d_date']").attr("data-wt", "open").valid();
            }
          },
          // !MEGAMIND
        });
      }
      function validatePhoneNumber(data) {
        var ph = data;
        ph = $.trim(ph);
        var len = ph.toString().length;
        if (len == 11 && isNaN(ph) == false) {
          $("#phone_no").attr("data-wt","valid");
        } else {
          $("#phone_no").attr("data-wt","invalid");
        }
      }
      function validateEmail(email){
        var id = email;
        id = $.trim(id);
        var filter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (filter.test(id)) {
          $("#c_email").attr("data-wt","valid");
          $("#jsEmailOffer").css("display","block");
          //$("[name=email_offer]").attr("data-wt", "invalid");
        } else {
          $("#c_email").attr("data-wt","invalid");
        }
        if (email == '') {
          $("#jsEmailOffer").css("display","none");
          $("[name=email_offer]").attr("data-wt", "valid");
        }
      }
  
      function validateVoucher(voucher){
        var code = voucher;
        var date;
        var time;
        var active_frm = getActiveForm();
        if(!$(".onoffswitch-order .onoffswitch-checkbox").prop("checked") && $(active_frm+ ' #origin').val().toLowerCase() != 'pos') {
          date = $(active_frm + " #d_date").val();
          time = $(active_frm + " #delivery_time").val();
        } else {
          date = $(active_frm + " #p_date").val();
          time = $(active_frm + " #pickup_time").val();
        }
        $.get(
              "index.php?controller=pjVouchers&action=pjActionCheckVoucherCode",
              {code,date,time}
            ).done(function (data) {
              // var active_frm;
              // if ($frmCreateOrder.length > 0) {
              //   active_frm = $("#frm-type").val();
              // }     
              // if ($frmUpdateOrder.length > 0) {
              //   active_frm = "#frmUpdateOrder_pos";
              // }
              // if ($frmUpdatePosOrder.length > 0) {
              //   active_frm = "#frmUpdateOrder_epos";
              // }
              
              if (data == "true") {
                $("#voucher-container input").attr("data-wt","valid");
                $("#voucher-container input").parent().removeClass("has-error");
                if ($("#voucher-container input-error").length > 0) {
                  $("#voucher-container input-error").css("display","none");
                }
                $(active_frm + " #vouchercode").val(code);
                calPrice(1);
              } else {
                $("#voucher-container input").attr("data-wt","invalid");
                $("#voucher-container input").parent().addClass("has-error");
                $(active_frm + " #vouchercode").val('');
                calPrice(1);
              }
              
            });
      }
      function validateDeliveryFee(deliveryfee){
        var regex = /^\d*[.]?\d*$/;
        var inputVal = deliveryfee;
        if (regex.test(inputVal)) {
          $("#delivery_fee").attr("data-wt","valid");
        } else {
          $("#delivery_fee").val(0);
          $("#delivery_fee").attr("data-wt","invalid");
        }
      }
      function getActiveForm() {
        var active_frm;
        console.log(`${$frmCreateOrder.length}`);
        if ($frmCreateOrder.length > 0 || $frmCreatePosOrder.length > 0) {
          console.log(`${$frmCreateOrder.length}`);
          active_frm = $("#frm-type").val();
          //$frm = $(active_frm);
        }     
        if ($frmUpdateOrder.length > 0) {
          //$frm = $frmUpdateOrder;
          active_frm = "#frmUpdateOrder_pos";
        }
        if ($frmUpdatePosOrder.length > 0) {
          //$frm = $frmUpdatePosOrder;
          active_frm = "#frmUpdateOrder_epos";
        }
        return active_frm;
      }
      function deliveryTime(mins) {
        var delivery_time = parseInt(mins);
        var delivery_hr;
        var delivery_mins;
        var d_mins;
        //var d_hr;
        if($.isNumeric(delivery_time)) {
          $("#d_time").val(delivery_time);
        } else {
          delivery_time = 0;
          $("#d_time").val(delivery_time);
        }
        var currentUtcTime = new Date($.now());
        var now = new Date(currentUtcTime.toLocaleString('en-US', { timeZone: 'Europe/London' }));
        // now.setTimezone();
        var now_hr = now.getHours();
        var now_mins = now.getMinutes();
          
        if (delivery_time >= 60) {
          delivery_mins = delivery_time % 60;
          delivery_hr = (delivery_time - delivery_mins) / 60;
          d_mins = delivery_mins + now_mins;
          now_hr = now_hr + delivery_hr;
        } else {
          d_mins = delivery_time + now_mins;
        }
        while (d_mins >= 60) {
          now_hr++;
          d_mins = d_mins % 60;
        } 
        if (now_hr >= 24) {
          now_hr = now_hr % 24;
        }
        now_hr = now_hr < 10 ? '0' + now_hr : now_hr;
        d_mins = d_mins < 10 ? '0' + d_mins : d_mins;
        $("#delivery_time").val(now_hr + ":" + d_mins);
        $("#aproxDt").text(now_hr + ":" + d_mins); 
      }
      function pickupTime(mins) {
        var pickup_time = parseInt(mins);
        var pickup_hr;
        var pickup_mins;
        var p_mins;
        //var p_hr;
        if($.isNumeric(pickup_time)) {
          $("#p_time").val(pickup_time);
        } else {
          pickup_time = 0;
          $("#p_time").val(pickup_time);
        }
        var currentUtcTime = new Date($.now());
        var now = new Date(currentUtcTime.toLocaleString('en-US', { timeZone: 'Europe/London' }));
        var now_hr = now.getHours();
        var now_mins = now.getMinutes();
        if (pickup_time >= 60) {
          pickup_mins = pickup_time % 60;
          pickup_hr = (pickup_time - pickup_mins) / 60;
          p_mins = pickup_mins + now_mins;
          now_hr = pickup_hr + now_hr;
        } else {
          p_mins = pickup_time + now_mins;
        }
        while (p_mins >= 60) {
          now_hr++;
          p_mins = p_mins % 60;
        } 
        if (now_hr >= 24) {
          now_hr = now_hr % 24;
        }
        now_hr = now_hr < 10 ? '0' + now_hr : now_hr;
        p_mins = p_mins < 10 ? '0' + p_mins : p_mins;
        $("#pickup_time").val(now_hr + ":" + p_mins);
        $("#aproxPt").text(now_hr + ":" + p_mins);
      }
      function formatType(val, obj) {
        if (val == "pickup") {
          if (obj.is_paid == 1) {
            return (
              '<div class="badge badge-success btn-xs no-margin"><span class="p-w-xs">' +
              myLabel.pickup +
              "</span></div>"
            );
          } else {
            return (
              '<div class="badge badge-danger btn-xs no-margin"><span class="p-w-xs">' +
              myLabel.pickup +
              "</span></div>"
            );
          }
        } else if (val == "pickup & call") {
          if (obj.is_paid == 1) {
            return (
              '<div class="badge badge-success btn-xs no-margin"><span class="p-w-xs">' +
              myLabel.pickupAndCall +
              "</span></div>"
            );
          } else {
            return (
              '<div class="badge badge-danger btn-xs no-margin"><span class="p-w-xs">' +
              myLabel.pickupAndCall +
              "</span></div>"
            );
          }
        } else {
           if (obj.is_paid == 1) {
            return (
              '<div class="badge badge-success btn-xs no-margin"><span class="p-w-xs">' +
              myLabel.delivery +
              "</span></div>"
            );
          } else {
            return (
              '<div class="badge badge-danger btn-xs no-margin"><span class="p-w-xs">' +
              myLabel.delivery +
              "</span></div>"
            );
          }
        }
      }
      function formatStatus(val, obj) {
        if (val == "pending") {
          return (
            '<div class="btn bg-pending btn-xs no-margin"><i class="fa fa-exclamation-triangle"></i> ' +
            myLabel.pending +
            "</div>"
          );
        } else if (val == "confirmed") {
          return (
            '<div class="btn bg-confirmed btn-xs no-margin"><i class="fa fa-check"></i> ' +
            myLabel.confirmed +
            "</div>"
          );
        } else if (val == "delivered") {
          return (
            '<div class="btn bg-confirmed btn-xs no-margin"><i class="fa fa-check"></i> ' +
            myLabel.delivered +
            "</div>"
          );
        } else {
          return (
            '<div class="btn bg-canceled btn-xs no-margin"><i class="fa fa-times"></i> ' +
            myLabel.cancelled +
            "</div>"
          );
        }
      }

      if ($("#grid-epos").length > 0 && datagrid) {
        var queryString = '';
        if (pjGrid.queryString == '') {
         queryString += '&origin='+$("#grid-type").val()
        } else {
          queryString += pgGrid.queryString;
        }
        var $grid_epos = $("#grid-epos").datagrid({
          buttons: [
            {
              type: "print",
              text: " Kprint",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionPrintOrder&id={:id}&source=index",
            },
            {
              type: "print",
              text: " Rprint",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionSalePrint&id={:id}&source=index",
            },
            {
              type: "edit",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionUpdate&id={:id}",
            },
            {
              type: "cancel",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionCancelOrder&id={:id}",
            },
            {
              type: "delete",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionDeleteOrder&id={:id}",
              model: "Order"
            },
          ],
          columns: [
            { text: myLabel.id, type: "text", sortable: false},
            {
              text: myLabel.total,
              type: "text",
              sortable: false,
              editable: false,
            },
            // {
            //   text: myLabel.type,
            //   type: "text",
            //   sortable: false,
            //   editable: false,
            //   renderer: formatType,
            // },
            {
              text: myLabel.posType,
              type: "text",
              sortable: false,
              editable: false,
            },
            {
              text: myLabel.paymentType,
              type: "text",
              sortable: false,
              editable: false,
            },
            {
              text: myLabel.status,
              type: "text",
              sortable: false,
              editable: false,
              renderer: formatStatus,
            },
            // { text: myLabel.is_featured, type: "toggle", sortable: false, editable: true, 
            //           editableRenderer: function () {
            //             return 0;
            //           },
            //           saveUrl: "",
            //           positiveLabel: myLabel.rigtht, positiveValue: "1", negativeLabel: myLabel.no, negativeValue: "0", 
            //           cellClass: "text-center"},
          ],
          dataUrl: "index.php?controller=pjAdminPosOrders&action=pjActionGetPosOrder" + queryString,
          dataType: "json",
          fields: ["order_id", "total", "table_name", "payment_method", "status"],
          paginator: {
            actions: [
              {
                text: myLabel.delete_selected,
                url: "index.php?controller=pjAdminOrders&action=pjActionDeleteOrderBulk",
                render: true,
                confirmation: myLabel.delete_confirmation,
              },
              {
                text: myLabel.exported,
                url: "index.php?controller=pjAdminOrders&action=pjActionExportOrder",
                ajax: false,
              },
            ],
            gotoPage: true,
            paginate: true,
            total: true,
            rowCount: true,
          },
          saveUrl: "index.php?controller=pjAdminOrders&action=pjActionSaveOrder&id={:id}",
          select: {
            field: "id",
            name: "record[]",
            cellClass: "cell-width-2",
          },
        });
      }
      
      if ($("#grid").length > 0 && datagrid) {
        var queryString = '';
        if (pjGrid.queryString == '') {
         queryString += '&origin='+$("#grid-type").val();
        } else {
          queryString += pgGrid.queryString;
        }
        var $grid = $("#grid").datagrid({
          buttons: [
            {
              type: "delay",
              //text: "Delay",
              url: "#",
              // .on("click",function() {
              //   console.log("Delay Message")
              // }
            },
            {
              type: "info",
              url: "#",
            },
            {
              type: "print",
              text: " Kprint",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionPrintOrder&id={:id}&source=index",
            },
            {
              type: "print",
              //text: " Rprint",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionSalePrint&id={:id}&source=index",
            },
            {
              type: "edit",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionUpdate&id={:id}",
            },
            {
              type: "cancel",
              url:"index.php?controller=pjAdminPosOrders&action=pjActionCancelOrder&id={:id}",
            },
            {
              type: "delete",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionDeleteOrder&id={:id}",
              model: "Order"
            },
          ],
          columns: [
            { text: myLabel.id, type: "text", sortable: false},
            //{ text: myLabel.phone, type: "text", sortable: false },
            { text: myLabel.name, type: "text", sortable: false },
            //{ text: myLabel.address, type: "text", sortable: false },
            { text: myLabel.postcode, type: "text", sortable: false },
            //{ text: myLabel.c_type, type: "text", sortable: false },
            // { text: myLabel.call_start, type: "text", sortable: false },
            // { text: myLabel.call_end, type: "text", sortable: false },
            //{ text: myLabel.sms_email, type: "text", sortable: false },
            // { text: myLabel.order_despatched, type: "toggle", sortable: false },
            {text: myLabel.order_despatched, type: "toggle", sortable: false, editable: true, 
                      editableRenderer: function () {
                        return 0;
                      },
                      //saveUrl: "index.php?controller=pjAdminOrders&action=pjActionSaveOrderDespatched&id={:id}",
                      positiveLabel: myLabel.yes, positiveValue: "1", negativeLabel: myLabel.no, negativeValue: "0", 
                      cellClass: "text-center",
                      //negativeClass: "bg-danger"
                    },
            
            //{ text: myLabel.sms_sent_time, type: "text", sortable: false },
            //{ text: myLabel.expected_delivery, type: "text", sortable: false },
            // { text: myLabel.delivered_customer, type: "text", sortable: false },
            {text: myLabel.delivered_customer, type: "toggle", sortable: false, editable: false, 
                      editableRenderer: function () {
                        return 0;
                      },
                      // if() {
                      //saveUrl: "index.php?controller=pjAdminOrders&action=pjActionSaveDeliveredCustomer&id={:id}",
                      // }
                      positiveLabel: myLabel.yes, positiveValue: "1", negativeLabel: myLabel.no, negativeValue: "0", 
                      cellClass: "text-center"},
            { text: "Delivery Time", type: "text", sortable: false },
            //{ text: myLabel.delivered_time, type: "text", sortable: false },
            // {text: myLabel.is_paid, type: "toggle", sortable: false, editable: true, 
            //           editableRenderer: function () {
            //             return 0;
            //           },
            //           saveUrl: "index.php?controller=pjAdminOrders&action=pjActionSaveOrderPaid&id={:id}",
            //           positiveLabel: myLabel.yes, positiveValue: "1", negativeLabel: myLabel.no, negativeValue: "0", 
            //           cellClass: "text-center",
            //           //negativeClass: "bg-danger"
            //         },
            
  
            // {
            //   text: myLabel.date_time,
            //   type: "text",
            //   sortable: false,
            //   editable: false,
            // },
            {
              text: myLabel.total,
              type: "text",
              sortable: false,
              editable: false,
            },
            {
              text: myLabel.paymentType,
              type: "text",
              sortable: false,
              editable: false,
            },
            {
              text: myLabel.type,
              type: "text",
              sortable: false,
              editable: false,
              renderer: formatType,
            },
            // {
            //   text: myLabel.status,
            //   type: "text",
            //   sortable: false,
            //   editable: false,
            //   renderer: formatStatus,
            // },
            // { text: myLabel.is_featured, type: "toggle", sortable: false, editable: true, 
            //           editableRenderer: function () {
            //             return 0;
            //           },
            //           saveUrl: "",
            //           positiveLabel: myLabel.rigtht, positiveValue: "1", negativeLabel: myLabel.no, negativeValue: "0", 
            //           cellClass: "text-center"},
          ],
          dataUrl: "index.php?controller=pjAdminPosOrders&action=pjActionGetOrder" +queryString,
          dataType: "json",
          fields: ["order_id", "surname",  "post_code",  "order_despatched", "delivered_customer", "deliver_t", "total", "payment_method", "type"],
          paginator: {
            actions: [
              {
                text: myLabel.delete_selected,
                url: "index.php?controller=pjAdminOrders&action=pjActionDeleteOrderBulk",
                render: true,
                confirmation: myLabel.delete_confirmation,
              },
              {
                text: myLabel.exported,
                url: "index.php?controller=pjAdminOrders&action=pjActionExportOrder",
                ajax: false,
              },
            ],
            gotoPage: true,
            paginate: true,
            total: true,
            rowCount: true,
          },
          saveUrl: "index.php?controller=pjAdminPosOrders&action=pjActionSaveOrder&id={:id}",
          select: {
            field: "id",
            name: "record[]",
            cellClass: "cell-width-2",
          },
        });
      }
      $(document)
        .on("focusin", ".datepick", function (e) {
          var $this = $(this);
          $this.datepicker({
            firstDay: $this.attr("rel"),
            dateFormat: $this.attr("rev"),
            onSelect: function (dateText, inst) {},
          });
        })
        .on("submit", ".frm-filter", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          var $this = $(this),
            content = $grid.datagrid("option", "content"),
            cache = $grid.datagrid("option", "cache"),
            $origin = $("#grid-type").val();
          $.extend(cache, {
            q: $this.find("input[name='q']").val(),
            type: $this.find("select[name='type']").val(),
            page: 1,
            origin: $origin
          });
          var $getOrder = "index.php?controller=pjAdminPosOrders";
          if ($("#grid-type").val() == "Pos") {
            $getOrder += "&action=pjActionGetPosOrder";
            $grid = $("#grid-epos");
          } else {
            $getOrder += "&action=pjActionGetOrder";
            $grid = $("#grid");
          }
          $grid.datagrid("option", "cache", cache);
          $grid.datagrid("load", $getOrder, "delivery_dt", "DESC", 1, content.rowCount);
          return false;
        })
        .on("change", "#payment_method", function (e) {
          switch ($("option:selected", this).val()) {
            case "creditcard":
              $(".boxCC").show();
              break;
            default:
              $(".boxCC").hide();
          }
        })
        .on("change", "#delivery_fee", function(e) {
          validateDeliveryFee($(this).val());
          calPrice(1);
        })
        .on("change", "#status", function() {
          if ($(this).val() == 'delivered' && $("input[name='delivered_customer']").val() == 0) {
            var $time = timeNow();
            var dtOfDelivery = $("#d_date").val();
            //var $dg = new Datagrid();
            var dtToday = $time.split(" ");
            if (dtToday[0] < dateFormat(dtOfDelivery)) {
              swal({
                title: "Dispatch Order?",
                text: "Today is not a delivery date...",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: false,
                
              },function () {
                  $("#status").val('pending');
                  $("#pjFdPriceWrapper").find(".panel-heading").addClass("bg-pending");
                  $("#pjFdPriceWrapper").find(".status-text").html("Pending");
                  swal.close();
              
              });
            } else if (!($("#is_paid").prop("checked")) && $("input[name='order_despatched']").val() == 0) {
           
              swal({
                title: "Delivered Customer?",
                text: "Before Delivery, is the order despatched and the payment made?",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
              }, function (data) {
                if (data) {
                  swal.close();
                  $("input[name='order_despatched']").val(1);
                  $("input[name='sms_sent_time']").val($time);
                  $("#is_paid").prop("checked", true);
                  if ($("input[name='delivered_customer']").val() == 0) {
                    $("input[name='delivered_customer']").val(1);
                    $("input[name='delivered_time']").val($time);
                  }
                } else {
                  $("#status").val('pending');
                  $("#pjFdPriceWrapper").find(".panel-heading").addClass("bg-pending");
                  $("#pjFdPriceWrapper").find(".status-text").html("Pending");
                }
              })
            } else if(!($("#is_paid").prop("checked"))) {
              swal({
                title: "Is Paid?",
                text: "Is payment made for the Order?",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
              }, function (data) {
                if (data) {
                  swal.close();
                  $("input[name='order_despatched']").val(1);
                  $("input[name='sms_sent_time']").val($time);
                  $("#is_paid").prop("checked", true);
                } else {
                  $("#status").val('pending');
                  $("#pjFdPriceWrapper").find(".panel-heading").addClass("bg-pending");
                  $("#pjFdPriceWrapper").find(".status-text").html("Pending");
                }
              })
            } else if ($("input[name='order_despatched']").val() == 0) {
              swal({
                title: "Is order despatched?",
                text: "Is this order despatched?",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
              }, function (data) {
                if (data) {
                  swal.close();
                  $("input[name='order_despatched']").val(1);
                  $("input[name='sms_sent_time']").val($time);
                  if ($("input[name='delivered_customer']").val() == 0) {
                    $("input[name='delivered_customer']").val(1);
                    $("input[name='delivered_time']").val($time);
                  }
                } else {
                  $("#status").val('pending');
                  $("#pjFdPriceWrapper").find(".panel-heading").addClass("bg-pending");
                  $("#pjFdPriceWrapper").find(".status-text").html("Pending");
                }
              })
            } 
          }
        })
        .on("change", "#chef", function() {
          var $chef_id = $(this).val();
          $.ajax({
            type: "POST",
            async: false,
            url: "index.php?controller=pjAdminPosOrders&action=pjActionSetSession",
            data: { 
              chef_id: function () {
                return $chef_id;
              },
            },
            success: function (data) {
              return; 
            },
          });
        })
        .on("click", "#btn-listpos", function() {
          $('.pos-list-button').removeClass('selected');
          $(this).addClass('selected');
          $("#grid").addClass("d-none");
          $("#grid-epos").removeClass("d-none");
          $("#grid-type").val("Pos");
          getPendingOrders("Pos");
        })
        .on("click", "#btn-listtelephone", function() {
          $('.pos-list-button').removeClass('selected');
          $(this).addClass('selected');
          $("#grid").removeClass("d-none");
          $("#grid-epos").addClass("d-none");
          $("#grid-type").val("Telephone");
          $(".frm-filter").submit();
          getPendingOrders("Telephone");
        })
        .on("click", "#btn-listweb", function() {
          $('.pos-list-button').removeClass('selected');
          $(this).addClass('selected');
          $("#grid").removeClass("d-none");
          $("#grid-epos").addClass("d-none");
          $("#grid-type").val("Web");
          $(".frm-filter").submit();
          getPendingOrders("Web");
        })
        .on("click", "#btn-epos", function() {
          $(this).toggleClass("activeFrmBtn");
          $("#btn-telephone").toggleClass("activeFrmBtn");
          $(this).attr("disabled", true);
          $("#btn-telephone").attr("disabled", false);
          $("#frm-type").val("#frmCreateOrder_epos");
          resetCart("#frmCreateOrder_pos");
          $("#frmCreateOrder_pos #fdPosTableContainer .main-body").html("");
          var $table_pos = $("#frmCreateOrder_pos #fdPosTableContainer").html();
          $("#frmCreateOrder_epos #fdEposTableContainer").html($table_pos);
          $("#frmCreateOrder_pos #fdPosTableContainer").html("");
          $("#rowSwitch").css("display", "none");
          $("#orderTab").css("display", "none");
          // $("#btns-pos").toggleClass("d-none");
          // $("#btns-epos").toggleClass("d-none");
          $("#type").removeAttr("checked");
          $("#btn-pause").removeClass("d-none");
          $("#alertJs").addClass("d-none");
          $("#frmCreateOrder_pos").addClass("d-none");
          $("#frmCreateOrder_epos").removeClass("d-none");
          // $("#frmCreateOrder_pos").find("table").removeAttr("id");
          // $("#frmCreateOrder_epos").find("table").attr("id", "fdOrderList_1");
          $("#frmCreateOrder_pos").find(".voucher").removeAttr("id");
          $("#frmCreateOrder_epos").find(".voucher").attr("id", "voucher_code");
        })
        .on("click", "#btn-telephone", function() {
          $(this).toggleClass("activeFrmBtn");
          $("#btn-epos").toggleClass("activeFrmBtn");
          $(this).attr("disabled", true);
          $("#btn-epos").attr("disabled", false);
          $("#frm-type").val("#frmCreateOrder_pos");
          resetCart("#frmCreateOrder_epos");
          $("#frmCreateOrder_epos #fdEposTableContainer .main-body").html("");
          var $table_epos = $("#frmCreateOrder_epos #fdEposTableContainer").html();
          $("#frmCreateOrder_pos #fdPosTableContainer").html($table_epos);
          $("#frmCreateOrder_epos #fdEposTableContainer").html("");
          $("#rowSwitch").css("display", "block");
          $("#orderTab").css("display", "block");
          // $("#btns-pos").toggleClass("d-none");
          // $("#btns-epos").toggleClass("d-none");
          $("#type").prop("checked", true);
          $("#btn-pause").addClass("d-none");
          $("#alertJs").addClass("d-none");
          $("#frmCreateOrder_pos").removeClass("d-none");
          $("#frmCreateOrder_epos").addClass("d-none");
          // $("#frmCreateOrder_pos").find("table").attr("id", "fdOrderList_1");
          // $("#frmCreateOrder_epos").find("table").removeAttr("id");
          $("#frmCreateOrder_epos").find(".voucher").removeAttr("id");
          $("#frmCreateOrder_pos").find(".voucher").attr("id", "voucher_code");
          hideTelPhoneDelivery();
        })
        .on("click", "#paymentModal .money-container .btn", function() {
          var amt = $(this).attr("data-rs");
          var curModalName = "#paymentModal";
          $(curModalName+" #payment_modal_pay").val(amt);
          showBalance(amt, curModalName);
        })
        .on("click", "#paymentTelModal .money-container .btn", function() {
          var amt = $(this).attr("data-rs");
          var curModalName = "#paymentTelModal";
          $(curModalName+" #payment_modal_pay").val(amt);
          showBalance(amt, curModalName);
        })
        .on("click", "#btn-clear", function() {
          $('#payment_modal_pay').val('');
          $('#payment_modal_bal').text('');
          $("#paymentBtn").attr("data-valid", "false");
          $(".payment-method-btn").removeClass("selected");
          $(".confirm_payment_method button:first-child").addClass("selected");
        })
        .on("click", "#btn-save", function() {
          if($("#fdOrderList_1 .main-body tr").length > 0) {
            $(".confirm-table-btn").removeClass("selected");
            tableID = $('#res_table_name').val();
            $('#pauseModal .confirm-table-btn').each(function(index, obj) {
              if (obj.id == tableID) {
                $(obj).addClass("selected");
              }
            });
            shownModalName = '#pauseModal';
            $(shownModalName+' #confirm-table-error-msg').text('');
            $(shownModalName).modal();
          } else {
            cartEmptyPopup();
          }
        })
        .on("click", "#btn-pause", function() {
           $("#is_paused").val("1");
          if($("#fdOrderList_1 .main-body tr").length > 0) {
            if ($("#frmCreateOrder_epos").length == 1) {
              $('#frmCreateOrder_epos').find('#customer_paid').val(0);
              $("#frmCreateOrder_epos").submit();
            } else if ($("#frmUpdateOrder_epos").length == 1) {
              $('#frmUpdateOrder_epos').find('#customer_paid').val(0);
              $("#frmUpdateOrder_epos").submit();
            }
          } else {
            cartEmptyPopup();
          }
        })
        .on("click", "#update-btn-pause", function() {
          if($("#fdOrderList_1 .main-body tr").length > 0) {
           $("#frmUpdateOrder_epos").submit();
          } else {
            cartEmptyPopup();
          }
        })
        .on("click", "#btn-openDrawer", function() {
          $.ajax({
            type: "POST",
            async: false,
            url: "index.php?controller=pjAdminPosOrders&action=pjActionOpenDrawer",
              success: function (msg) {
                swal({
                  title: "Drawer Status",
                  text: msg.message,
                  type: "info",
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "OK",
                  closeOnConfirm: false,
                },function () {
                  swal.close();
                });
              },
          
            });
        })
        .on("click", "#btn-payment", function() {
          if($("#fdOrderList_1 .main-body tr").length == 0) {
            cartEmptyPopup();
          }
          else if (!validateCart()) {
            $(getActiveForm()).find('#customer_paid').val(0);
            var $curModalName = "#paymentModal";
            shownModalName = "#paymentModal";
            $(".payment-method-btn").removeClass("selected");
            $(".confirm_payment_method button:first-child").addClass("selected");
            $('#pos_payment_method').val($(".confirm_payment_method button:first-child").text());
            $(".confirm-table-btn").removeClass("selected");
            tableID = $('#res_table_name').val();
            $('#paymentModal .confirm-table-btn').each(function(index, obj) {
              if (obj.id == tableID) {
                $(obj).addClass("selected");
                return false;
              }
            });
            var cart_tot = $(this).attr("data-cart");
            var htmlBtns = currencyBtns(cart_tot, $curModalName);
            $($curModalName+" .money-container").html(htmlBtns);
            $($curModalName+' #confirm-table-error-msg').text('');
            $($curModalName).modal();
            $($curModalName+" #payment_modal_tot").text(cart_tot);
          } 
          // else {
          //   cartEmptyPopup();
          // }
        })
        .on("click", "#btn-payment-tel", function() {
          var active_frm = getActiveForm();
          var frm = $(active_frm);
          var total = parseFloat($(active_frm +" #total").val());
          var min_amt = parseFloat($(active_frm +" #min_amt").val());
          var currency = $("#paymentModal #payment_modal_curr").text();
          if(validateOrderTab(total, min_amt, currency)) {
            $(getActiveForm()).find('#customer_paid').val(0);
            var $curModalName = "#paymentTelModal";
            shownModalName = "#paymentTelModal";
            $(".payment-method-btn").removeClass("selected");
            $(".confirm_payment_method button:first-child").addClass("selected");
            // $('#payment_method').val($(".confirm_payment_method button:first-child").text());
            var cart_tot = $(this).attr("data-cart");
            var htmlBtns = currencyBtns(cart_tot, $curModalName);
            $($curModalName+" .money-container").html(htmlBtns);
            $($curModalName+' #confirm-table-error-msg').text('');
            $($curModalName).modal();
            $($curModalName+" #payment_modal_tot").text(cart_tot);
          } else {
            cartEmptyPopup();
          }
        })
        .on("click", "#paymentModal #paymentBtn", function() {
          if ($(shownModalName+' #confirm-table-error-msg').text() != '') {
            return;
          }
          if (tableID) {
            $('#res_table_name').val(tableID);
          }
          var $form = null;
          var $activeForm = null;
          if($(this).attr("data-valid") == "true") {
            if($frmUpdatePosOrder.length > 0) {
              $("#is_paused").val(0);
              $("#is_paid").val(1);
            } 
            $activeForm = getActiveForm();
            $form = $($activeForm);
            $form.find('#customer_paid').val($('#paymentModal').find('#payment_modal_pay').val());
            $(this).attr("disabled", true);
            $form.submit();
          } else {
            $("#paymentModal #error-msg").removeClass("d-none");
          }
           $(this).attr("disabled",false)
        })
        .on("click", "#pauseModal #paymentBtn", function() {
          if (tableID) {
            $('#res_table_name').val(tableID);
          }
          $("#is_paused").val("1");
          if ($(shownModalName+" #confirm-table-error-msg").text() == "") {
            if ($("#frmCreateOrder_epos").length == 1) {
              $('#frmCreateOrder_epos').find('#customer_paid').val(0);
              $("#frmCreateOrder_epos").submit();
            } else if ($("#frmUpdateOrder_epos").length == 1) {
              $('#frmUpdateOrder_epos').find('#customer_paid').val(0);
              $("#frmUpdateOrder_epos").submit();
            }
            //$("#pauseModal").modal("hide");
          } else {
            tableID = '';
            $('#res_table_name').val(defaultTableValue);
          }
        })
        .on("click", "#tableModal #selectTableBtn", function() {
          var no_of_persons = parseInt($('#no_of_persons').val());
          tableID = parseInt($('#no_of_persons').val());
          if (tableID && no_of_persons) {
            var lblText = 'Table'+tableID+'-Count-'+no_of_persons;
            $('#tableModal #confirm-table-error-msg').val("");
            $('#res_table_name').val(tableID);
            $('#total_persons').val(no_of_persons);
            $("#tableModal").modal("hide");
            $("#sel_table_name").removeClass("d-none");
            $("#sel_table_name_modal").html(lblText);
          } else {
            $('#confirm-table-error-msg').html("All fields are required.");
          } 
        })
        .on("click", "#paymentTelModal #paymentBtn", function() {
          if($(this).attr("data-valid") == "true") {
            var $activeForm = getActiveForm();
            var $form = $($activeForm);
            $("#is_paid").prop("checked", true);
            //$("#status").val('delivered');
            $("#order_despatched_tel").val(1);
            $("#delivered_customer_tel").val(1);
            //$("#submitJs").trigger("click");
            //console.log('2010', $('#paymentTelModal').find('#payment_modal_pay').val());
            $form.find('#customer_paid').val($('#paymentTelModal').find('#payment_modal_pay').val());
            $(this).attr("disabled", true);
            $form.submit();
            $("#paymentTelModal").modal("hide");
          } else {
            $("#paymentTelModal").modal("hide");
            $("#paymentTelModal #error-msg").removeClass("d-none");
          }
          $(this).attr("disabled",false)
        })
        .on("click", ".confirm-table-btn", function() {
          $(".confirm-table-btn").removeClass("selected");
          $(this).addClass('selected');
          tableID = this.id;
          var msgArea = $(shownModalName+' #confirm-table-error-msg');
          if (tableID) {
             $.ajax({
              type: "POST",
              async: false,
              url: "index.php?controller=pjAdminPosOrders&action=pjActionValidateTable",
              data: { 
                table_id: tableID
              },
              success: function (msg) {
                if (msg.code == 200 && msg.count > 0) {
                  msgArea.html(msg.text);
                }
                else {
                  msgArea.html('');
                }
              },
            });
          }
        })
        .on("click", "#paymentModal .payment-method-btn", function() {
          $(".payment-method-btn").removeClass("selected");
          $(this).addClass('selected');
          var paymentType = $(this).text().trim().toLowerCase();
          var amt = $('#payment_modal_tot').text();
          var $modalName = '#paymentModal' ;
          if (paymentType == 'card') {
            $('#pos_payment_method').val(paymentType); 
            $('#payment_modal_pay').val(amt);
            showBalance(amt, $modalName);
          } else {
            $('#payment_modal_pay').val('');
            $('#payment_modal_bal').text('');
            $($modalName+" #paymentBtn").attr("data-valid", "false");
          }

        })
        .on("click", "#paymentTelModal .payment-method-btn", function() {
          $(".payment-method-btn").removeClass("selected");
          $(this).addClass('selected');
          if ($(this).text().toLowerCase == 'card') {
            $('#payment_method').val('bank');
          } else {
            $('#payment_method').val('cash');
          } 
        })
        .on("hidden.bs.modal", "#paymentModal, #paymentTelModal", function (e) {
          var modalID = '#'+e.target.id;
          $(modalID+" #payment_modal_pay").val("");
          $(modalID+" #error-msg").addClass("d-none");
          $(modalID+" #payment_modal_bal").text("");
        })
        .on("input", "#pause_phone", function() {
          $("#clientPhoneNumberBtn").attr("data-phone", $(this).val());
          $("#pause_phone-error").addClass("d-none");
        })
        .on("input", "#paymentModal #payment_modal_pay", function() {
          $("#paymentModal #payment_modal_pay").inputFilter(function(value) {
            return /^\d*\.?\d*$/.test(value);    // Allow digits only, using a RegExp
           },"Only digits allowed");
          var curModalName = "#paymentModal";
          showBalance($(this).val(), curModalName);
        })
        .on("input", "#paymentTelModal #payment_modal_pay", function() {
          $("#paymentTelModal #payment_modal_pay").inputFilter(function(value) {
            return /^\d*\.?\d*$/.test(value);    // Allow digits only, using a RegExp
           },"Only digits allowed");
          var curModalName = "#paymentTelModal";
          showBalance($(this).val(), curModalName);
        })
        .on("click", "#clientPhoneNumberBtn", function() {
          if($(this).attr("data-phone") == '') {
            $("#pause_phone-error").removeClass("d-none");
          } else {
            var phone = $(this).attr("data-phone");
            $("#clientPhoneNumberModal").modal("hide");
          }
        })
        .on("click", "#btnAddProduct", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          var index = Math.ceil(Math.random() * 999999),
          $clone = $("#boxProductClone").find("tbody").html();
          $clone = $clone.replace(/\{INDEX\}/g, "new_" + index);
          $("#fdOrderList").find("tbody.main-body").append($clone);
          bindTouchSpin();
          $("#fdOrderList").show();
          $('#fdProduct_new_'+index).addClass('selectpicker').selectpicker('refresh');
        })
        .on("click", ".pj-remove-product", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          $(this).closest(".fdLine").remove();
          if ($("#fdOrderList").find("tbody.main-body > tr").length == 0) {
            $("#fdOrderList").hide();
          } else {
            calPrice(1);
          }
          return false;
        })
        .on("click", ".add_more_extras", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          var $this = $(this);
          var index = $this.attr("data-index");
          var $productEle = $("#fdProduct_" + index);
          var product_id = $productEle.val();
          var $extra_table = $this.parent().siblings(".pj-extra-table");
          if (product_id != "") {
            $.get("index.php?controller=pjAdminPosOrders&action=pjActionGetExtras", {
              product_id: product_id,
              index: $this.attr("data-index"),
            }).done(function (data) {
              $(data).appendTo($extra_table.find("tbody"));
              $extra_table.find(".pj-field-count").TouchSpin({
                verticalbuttons: true,
                buttondown_class: "btn btn-white",
                buttonup_class: "btn btn-white",
                min: 1,
                max: 4294967295,
              });
            });
          }
          return false;
        })
        .on("click", ".pj-add-extra", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          var $this = $(this);
          var index = $this.attr("data-index");
          var table = $("#fdExtraTable_"+index).html();
          var tableID = "fdExtraTable_show_"+index;
          $("#extraModal").modal();
          $("#extraModal .modal-body .add_more_extras").attr("data-index", index);
          $("#extraModal .copy-extra-table").attr("data-index", index);
          $("#extraModal .modal-body table").html(table);
          $("#extraModal .modal-body table").attr("id", tableID);
          return false;
        })
        .on("click", ".copy-extra-table", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          var $this = $(this);
          var index = $this.attr("data-index");
          var table = $("#fdExtraTable_show_"+index).html();
          var hiddenTable = $("#fdExtraTable_"+index).html(table);
          return false;
        })
        .on("click", ".pj-remove-extra", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          $(this).parent().parent().remove();
          calPrice(1);
          return false;
        })
        .on("click", "input[type = 'radio']", function(e) {
          $(this).parent().siblings("label").css('color','#676a6c');
        })
        .on("change", "select.fdProduct", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          var $this = $(this),
            index = $this.attr("data-index"),
            option = $("option:selected", this).attr("data-extra");
          $(".fdExtraBusiness_" + index).hide();
          $.get("index.php?controller=pjAdminOrders&action=pjActionGetPrices", {
            product_id: $this.val(),
            index: index,
          }).done(function (data) {
            $("#fdPriceTD_" + index).html(data);
            
            //MEGAMIND
            $("#productDelete_"+ index).css("display","block");
  
            $fdSelectedProduct = $("#prdInfo_" + index).val();
            $fdSelectedProduct = JSON.parse($fdSelectedProduct);
            //$kordersPrepTime = $("#totKorderPrepTimeInput").val();
            
            if ($fdSelectedProduct.preparation_time == "") {
              $prepTime = "0";
            } else {
              $prepTime = $fdSelectedProduct.preparation_time;
            }//? $prepTime = 0 : $prepTime = $fdSelectedProduct.preparation_time;
            $totPrepTime = $totPrepTime + parseInt($prepTime);
  
            //$totPrepTime = $totPrepTime + parseInt($kordersPrepTime);
            
            $("#fdPrepTime_" + index).html($prepTime);
            $("#fdCategory_" + index).html(categoryList[$fdSelectedProduct.category_id]);
            $("#fdDescription").html($fdSelectedProduct.description);
            $("#total_prep-time_format").html($totPrepTime);
            $("#prep_time").val($totPrepTime);
            //$totPrepTime = $totPrepTime - parseInt($kordersPrepTime);
            // !MEGAMIND
  
            $("#curProdDesc").html($("#prdDesc_" + index).val());
            $("#fdExtraTable_" + index)
              .find("tbody")
              .html("");
            if ($this.val() != "") {
              $(".business-" + index).show();
            } else {
              $(".business-" + index).hide();
            }
            if (option == "0") {
              $(".fdExtraNA_" + index).show();
            } else {
              $(".fdExtraButton_" + index).show();
            }
            calPrice(1);
          })
              
          // MEGAMIND
  
          .done(function(e){
          // if (e && e.preventDefault) {
          //   e.preventDefault();
          // }
            var lastRowIndex = $("#fdOrderList tr:last").attr("data-index"),
            $product = $("#fdProduct_" + lastRowIndex).val(),
            $price = $("#fdPrice_" + lastRowIndex).val();
            if($product != "" || $price != ""){
              var index = Math.ceil(Math.random() * 999999),
              $clone = $("#boxProductClone").find("tbody").html();
              $clone = $clone.replace(/\{INDEX\}/g, "new_" + index);
              $("#fdOrderList").find("tbody.main-body").append($clone);
              bindTouchSpin();
              $("#fdOrderList").show();
              $('#fdProduct_new_'+index).addClass('selectpicker').selectpicker('refresh');
            }
            
          });
           //return false;
        })
        .on("click", "#fdOrderList .pj-remove-product", function (e) {
          //$kordersPrepTime = $("#totKorderPrepTimeInput").val();
          var $deletedPrepTime = parseInt($(this).parents("tr").children("td:nth-child(6)").children().text());
          $totPrepTime = $totPrepTime - $deletedPrepTime;
          $("#total_prep-time_format").text($totPrepTime);
          $("#prep_time").val($totPrepTime);
  
        })
        // .on("click", ".bootstrap-touchspin", function(e) {
        //   console.log("hey");
          // var $qnty = $(this).val();
          // var index = $(this).attr('id');
  
          // var index = index.split("_");
          
          // var prept = parseInt($("#fdPrepTime_"+index[1]+"_"+index[2]).text());
          // console.log(prept);
          // var preptOne = prept/($qnty - 1);
          // var preptNow = preptOne * $qnty;
          // $(this).parent().siblings(".pj-field-count").val(preptNow);
        //})
        .on("click", ".dropdown-toggle", function (e) { 
  
          var index = $(this).siblings("select").attr("data-index"); 
          $fdSelectedProduct = $("#prdInfo_" + index).val();
         
          
          if ($fdSelectedProduct == undefined) {
            return;
          } else {
            $fdSelectedProduct = JSON.parse($fdSelectedProduct);
            $("#fdDescription").html($fdSelectedProduct.description);
          }
          
        })
        .on("change", "tbody tr:nth-child(2) .bootstrap-select select", function (e){
          //$("tbody tr:nth-child(1) td div[id^=productDelete_]").css("display","block")
          // var rowOneIndex = $("#jsIndex").val();
          $("#productDelete_rowOne").css("display","block");
         
        })
        // .on("change", "#d_time", function (e){
        //   d_dt = $("#d_dt").val();
        //   dtime = $this.val();
        //   d_dt = d_dt + dtime;
        //   $("#d_dt").val() = d_dt;
        // })
        // .on("change", "#p_date", function (e){
        //   p_dt = $("#p_dt").val();
        //   pdate = $this.val();
        //   p_dt = p_dt + pdate;
        //    $("#p_dt").val() = 
        // })
        //  .on("change", "#p_time", function (e){
        //   p_dt = $("#p_dt").val();
        //   ptime = $this.val();
        //   p_dt = p_dt + ptime;
        // })
        .on("click", ".pj-table-cell-editable", function (e) {
  
         var $editBtn = $(this).parent().find('td div.m-t-xs a.pj-table-icon-edit');//.find('pj-table-icon-edit');
         $editBtn.remove();
         
        })
        .on("change", "#phone_no", function (e) {
          // $cinfo = client_info;
          console.log('called me');
          getClientInfo($(this));
          validatePhoneNumber($(this).val());
        })
        
        // !MEGAMIND
  
        .on("change", "#client_id", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          var $pjFdEditClient = $("#pjFdEditClient");
          if ($(this).val() != "") {
            var href = $pjFdEditClient.attr("data-href");
            href = href.replace("{ID}", $(this).val());
            $pjFdEditClient.attr("href", href);
            $pjFdEditClient.css("display", "inline-block");
            $.get(
              "index.php?controller=pjAdminOrders&action=pjActionGetClient&id=" +
                $(this).val()
            ).done(function (data) {
              if ($("#c_title").length > 0) {
                $("#c_title").removeClass("required");
                $("#c_title").val(data.c_title).valid();
              }
              if ($("#c_email").length > 0) {
                $("#c_email").removeClass("required");
                /*$('#c_email').val(data.email).valid();*/
                $("#c_email").val("").valid();
              }
              if ($("#c_password").length > 0) {
                $("#c_password").removeClass("required");
                $("#c_password").val(data.password).valid();
              }
              if ($("#c_name").length > 0 || $("#c_surname").length > 0 ) {
                $("#c_name").removeClass("required");
                $("#c_name").val(data.name).valid();
                $("#c_surname").removeClass("required");
                $("#c_surname").val(data.name).valid();
              }
              if ($("#c_phone").length > 0) {
                $("#c_phone").removeClass("required");
                $("#c_phone").val(data.phone).valid();
              }
              if ($("#c_company").length > 0) {
                $("#c_company").removeClass("required");
                $("#c_company").val(data.c_company).valid();
              }
              if ($("#c_address_2").length > 0) {
                $("#c_address_2").removeClass("required");
                $("#c_address_2").val(data.c_address_2).valid();
              }
              if ($("#c_address_1").length > 0) {
                $("#c_address_1").removeClass("required");
                $("#c_address_1").val(data.c_address_1).valid();
              }
              if ($("#c_city").length > 0) {
                $("#c_city").removeClass("required");
                $("#c_city").val(data.c_city).valid();
              }
              if ($("#c_state").length > 0) {
                $("#c_state").removeClass("required");
                $("#c_state").val(data.c_state).valid();
              }
              if ($("#c_zip").length > 0) {
                $("#c_zip").removeClass("required");
                $("#c_zip").val(data.c_zip).valid();
              }
              if ($("#c_country").length > 0) {
                $("#c_country").removeClass("required");
                $("#c_country")
                  .val(data.c_country)
                  .trigger("liszt:updated")
                  .valid();
              }
              if (
                ($("#d_address_1").length > 0 && $("#d_address_1").val() != "") ||
                ($("#d_address_2").length > 0 && $("#d_address_2").val() != "") ||
                ($("#d_city").length > 0 && $("#d_city").val() != "") ||
                ($("#d_state").length > 0 && $("#d_state").val() != "") ||
                ($("#d_zip").length > 0 && $("#d_zip").val() != "") ||
                ($("#d_country_id").length > 0 && $("#d_country_id").val() != "")
              ) {
              } else {
                if ($("#d_address_1").length > 0) {
                  $("#d_address_1").val(data.c_address_1).valid();
                }
                if ($("#d_address_2").length > 0) {
                  $("#d_address_2").val(data.c_address_2).valid();
                }
                if ($("#d_city").length > 0) {
                  $("#d_city").val(data.c_city).valid();
                }
                if ($("#d_state").length > 0) {
                  $("#d_state").val(data.c_state).valid();
                }
                if ($("#d_zip").length > 0) {
                  $("#d_zip").val(data.c_zip).valid();
                }
                if ($("#d_country_id").length > 0) {
                  $("#d_country_id")
                    .val(data.c_country)
                    .trigger("liszt:updated")
                    .valid();
                }
              }
            });
          } else {
            $pjFdEditClient.css("display", "none");
            if ($("#c_title").length > 0) {
              if ($("#c_title").hasClass("fdRequired")) {
                $("#c_title").addClass("required");
              }
              $("#c_title").val("").valid();
            }
            if ($("#c_email").length > 0) {
              if ($("#c_email").hasClass("fdRequired")) {
                $("#c_email").addClass("required");
              }
              $("#c_email").val("").valid();
            }
            if ($("#c_password").length > 0) {
              if ($("#c_password").hasClass("fdRequired")) {
                $("#c_password").addClass("required");
              }
              $("#c_password").val("").valid();
            }
            if ($("#c_name").length > 0) {
              if ($("#c_name").hasClass("fdRequired")) {
                $("#c_name").addClass("required");
              }
              $("#c_name").val("").valid();
            }
            if ($("#c_phone").length > 0) {
              if ($("#c_phone").hasClass("fdRequired")) {
                $("#c_phone").addClass("required");
              }
              $("#c_phone").val("").valid();
            }
            if ($("#c_company").length > 0) {
              if ($("#c_company").hasClass("fdRequired")) {
                $("#c_company").addClass("required");
              }
              $("#c_company").val("").valid();
            }
            if ($("#d_address_1").length > 0) {
              if ($("#d_address_1").hasClass("fdRequired")) {
                $("#d_address_1").addClass("required");
              }
              $("#d_address_1").val("").valid();
            }
            if ($("#d_address_2").length > 0) {
              if ($("#d_address_2").hasClass("fdRequired")) {
                $("#d_address_2").addClass("required");
              }
              $("#d_address_2").val("").valid();
            }
            if ($("#d_city").length > 0) {
              if ($("#d_city").hasClass("fdRequired")) {
                $("#d_city").addClass("required");
              }
              $("#d_city").val("").valid();
            }
            if ($("#d_state").length > 0) {
              if ($("#d_state").hasClass("fdRequired")) {
                $("#d_state").addClass("required");
              }
              $("#d_state").val("").valid();
            }
            if ($("#d_zip").length > 0) {
              if ($("#d_zip").hasClass("fdRequired")) {
                $("#d_zip").addClass("required");
              }
              $("#d_zip").val("").valid();
            }
            if ($("#d_country_id").length > 0) {
              if ($("#d_country_id").hasClass("fdRequired")) {
                $("#d_country_id").addClass("required");
              }
              $("#d_country_id").val("").trigger("liszt:updated").valid();
            }
          }
        })
        .on("change", ".fdSize", function (e) {
          calPrice(1);
        })
        .on("change", ".fdExtra", function (e) {
          calPrice(1);
        })
        .on("change", ".pj-field-count", function (e) {
         
          calPrice(1);
        })
        .on("change", "#filter_type", function (e) {
          $(".frm-filter").submit();
        })
        .on("change", "#delay_reason", function (e) {
          var val = $(this).val();
          console.log('val',val);
          var msgArea = $("#message");
          if (val == 5) {
            msgArea.val("");
            msgArea.attr("placeholder","Type the reason...")
          } else {
            $.ajax({
              type: "POST",
              async: false,
              url: "index.php?controller=pjAdminOrders&action=pjActionGetDelayMessage",
              data: { 
                value: function() {
                  return val;
                }
              },
              success: function (msg) {
                if (msg.code == 200) {
                  msgArea.val(msg.text);
                }
                else {
                  alert("Something is wrong");
                }
              },
            });
          }
        })
        .on("click", ".pjFdOpenClientTab", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          $("#tabs").tabs("option", "active", 1);
        })
        .on("change", "#d_location_id", function (e) {
          $("#btnCalc").trigger("click");
        })
        .on("click", ".onoffswitch-order .onoffswitch-checkbox", function (e) {
          console.log('comes in');
        })
        .on("change", ".onoffswitch-order .onoffswitch-checkbox", function (e) {
          if (!$(this).prop("checked")) {
            $(".order-delivery").show();
            $("#address_title").show();
            $(".order-delivery").find(".fdRequired").addClass("required");
            $(".order-pickup").hide();
            $(".order-pickup").find(".fdRequired").removeClass("required");
            $("#delivery_fee_frmgrp").css("display","block");
            $("#jsOverridePc").css("display","block");
            var min_amt = $("#min_amt").val();
            var total_amt = $(".total_format").text();
            total_amt = total_amt.split(" ");
            var price;
            var cur_place = $("#currency_place").val();
            cur_place == 'back' ? price = parseFloat(total_amt[0]) : price = parseFloat(total_amt[1]);
            if (isNaN(price) || price < min_amt) {
              $("#submitJs").prop('disabled','true');
              $("#alertJs").css('display','block');
              $("#alertJs").removeClass("d-none");
            } else {
              $("#submitJs").removeAttr('disabled');
            }
            
            $c_phone = $('#phone_no').val();
            if ($c_phone) {
              // if ($frmUpdateOrder.length > 0) {
              //   $cinfo = $.ajax({
              //     type: "POST",
              //     async: false,
              //     url: "index.php?controller=pjAdminOrders&action=pjActionCheckClientPhoneNumber",
              //     data: { 
              //       value: function() {
              //         return $c_phone;
              //       }
              //     },
              //   });
              //   if ($cinfo.responseJSON) {
              //     var c_arr = $cinfo.responseJSON[0];
              //     $("#inputPostCode").val(c_arr.c_postcode);
              //     $("#d_address_1").val(c_arr.c_address_1);
              //     $("#d_address_2").val(c_arr.c_address_2);
              //     $("#d_city").val(c_arr.c_city);
              //   }
              // } else {
              
                $('#phone_no').val("")
                $("#c_title").val("");
                $("#c_email").val("");
                $("#c_email").attr('data-wt','invalid');
                $("#c_surname").val("");
                $("#inputPostCode").val("");
                $("#d_address_1").val("");
                $("#d_address_2").val("");
                $("#d_city").val("");
                $("#c_name").val("");
                //$("input:radio").prop("checked", false);
                $("#mobile_delivery_info_yes").prop("checked", true);
                $("#email_receipt_yes").prop("checked", true);
                $("#mobile_offer_no").prop("checked", true);
                $("#email_offer_no").prop("checked", true);
  
              //} 
            }
            if ($("#d_time").val() && !($("#delivery_time").val())) {
              var mins = parseInt($("#d_time").val());
              deliveryTime(mins);
            }
            if ($("#p_time").val() != '') {
              var temp = $("#p_time").val();
              $("#d_time").val(temp);
              deliveryTime(temp)
            }
          } else {
            hideTelPhoneDelivery();
          }
        })
        .on("change", ".onoffswitch-client .onoffswitch-checkbox", function (e) {
          if ($(this).prop("checked")) {
            $(".current-client-area").hide();
            $(".current-client-area").find(".fdRequired").removeClass("required");
            $(".new-client-area").show();
            $(".new-client-area").find(".fdRequired").addClass("required");
          } else {
            $(".current-client-area").show();
            $(".current-client-area").find(".fdRequired").addClass("required");
            $(".new-client-area").hide();
            $(".new-client-area").find(".fdRequired").removeClass("required");
            $("#c_email").val("").valid();
          }
        })
        .on("change", "#status", function (e) {
          var $pjFdPriceWrapper = $("#pjFdPriceWrapper");
          var value = $("#status option:selected").val();
          var text = $("#status option:selected").text();
          if (value == 'delivered') {value = 'success'}
          var bg_class = "bg-" + value;
          $pjFdPriceWrapper
            .find(".panel-heading")
            .removeClass("bg-pending")
            .removeClass("bg-cancelled")
            .removeClass("bg-confirmed")
            .addClass(bg_class);
          $pjFdPriceWrapper.find(".status-text").html(text);
        })
        .on("click", "#btnEmail", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          var booking_id = $(this).attr("data-id");
          var document_id = 0;
          var $emailContentWrapper = $("#emailContentWrapper");
  
          $("#btnSendEmailConfirm").attr("data-booking_id", booking_id);
  
          $emailContentWrapper.html("");
          $.get(
            "index.php?controller=pjAdminOrders&action=pjActionReminderEmail",
            {
              id: booking_id,
            }
          ).done(function (data) {
            $emailContentWrapper.html(data);
            if (data.indexOf("pjResendAlert") == -1) {
              myTinyMceInit.call(null, "textarea#mceEditor", "mceEditor");
              validator = $emailContentWrapper.find("form").validate({});
              $("#btnSendEmailConfirm").show();
            } else {
              $("#btnSendEmailConfirm").hide();
            }
            $("#reminderEmailModal").modal("show");
          });
          return false;
        })
        .on("click", "#btnSendEmailConfirm", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          var $this = $(this);
          var $emailContentWrapper = $("#emailContentWrapper");
          if (validator.form()) {
            $("#mceEditor").html(tinymce.get("mceEditor").getContent());
            $(this).attr("disabled", true);
            var l = Ladda.create(this);
            l.start();
            $.post(
              "index.php?controller=pjAdminOrders&action=pjActionReminderEmail",
              $emailContentWrapper.find("form").serialize()
            ).done(function (data) {
              if (data.status == "OK") {
                $("#reminderEmailModal").modal("hide");
              } else {
                $("#reminderEmailModal").modal("hide");
              }
              $this.attr("disabled", false);
              l.stop();
            });
          }
          return false;
          //Added by JR
        }).on("click", ".showpage", function(e){
          showPage($(this).text());
        }).on("click", ".previous", function(e){
          previous();
        }).on("click", ".first", function(e){
          first();
        }).on("click", ".next", function(e){
          next();
        }).on("click", ".last", function(e){
          var $nop = $("#nop").val()
          last($nop);
        })
        .on("click", "#btnFindPostCode", function (e) {
          var postcode_input = $("#inputPostCode");
          getAddresses(postcode_input);
        })
        // .on("click", "#selAddress", function(e) {
        //   var index = $(this).val();
        //   $("#d_address_1").val(result[index].line_1);
        //   $("#d_address_2").val(result[index].line_2);
        //   $("#d_city").val(result[index].post_town);
        // })
        // .on("click", "#selAddress option:first", function (e) {
        //   alert("clicked")
        // })
        .on("change", "#selAddress", function (e) {
          var index = $(this).val();
          //console.log(postalResult);
          // return;
          //client.lookupPostcode({ postcode }).then(function (result) {
          if (index != 0) {
            if (postalResult.length > 0) {
              $("#d_address_1").val(postalResult[index-1].line_1);
              $("#d_address_2").val(postalResult[index-1].line_2);
              $("#d_city").val(postalResult[index-1].post_town);
  
            }
          } else {
              $("#d_address_1").val("");
              $("#d_address_2").val("");
              $("#d_city").val("");
  
          }
          //})
  
        // })
        // .on("click", ".clickCategory", function (e) {
        //     var categoryID = $(this).data("id");
        //     var catName = $(this).text();
        //     $.post(
        //       "index.php?controller=pjAdminOrders&action=pjActionGetProductsForCategory",
        //       {category_id: categoryID}
        //     ).done(function (data) {
        //       //console.log(data);
        //       $('#catModalTitle').text(catName);
        //       $('#catModalBody').html(data);
        //       $("#catModal").modal();
              
        //       if ($("#paginate")) {
          
        //           showPage(1);
          
        //       }
              
  
        //     });
            
  
        //     return false;
        }).on("click", "#showCart", function (e) {
          calPrice(1);
          $("#cartModal").modal();
          // var categoryID = $(this).data("id");
          // var catName = $(this).text();
          // $.post(
          //   "index.php?controller=pjAdminOrders&action=pjActionGetProductsForCategory",
          //   {category_id: categoryID}
          // ).done(function (data) {
          //   //console.log(data);
          //   $('#catModalTitle').text(catName);
          //   $('#catModalBody').html(data);
          //   if ($("#paginate")) {
          //       showPage(1);
          //   }
          // });
          return false;
      }).on("click", "#showPostalCodes", function (e) {
        calPrice(1);
        $("#postalCodesModal").modal();
        return false;
    }).on("click", "#mobile_delivery_info_no", function() {
          var $email = $("#email_receipt_no").prop("checked");
          if ($email) {
            $("input[name='mobile_delivery_info']").attr("data-wt","invalid");
            $(this).parent().addClass("has-error");
            $(this).parent().siblings("label").css("color", "#ed5565");
          }
        }).on("click", "#email_receipt_no", function() {
          var $mobile = $("#mobile_delivery_info_no").prop("checked");
          if ($mobile) {
            $("input[name='email_receipt']").attr("data-wt","invalid");
            $(this).parent().addClass("has-error");
            $(this).parent().siblings("label").css("color", "#ed5565");
          }
        }).on("click", "#mobile_delivery_info_yes", function() {
          var $has_thisErr = $(this).parent().hasClass("has-error");
          var $has_emailErr = $("#email_receipt_no").parent().hasClass("has-error");
          if ($has_emailErr) {
            $("input[name='email_receipt']").attr("data-wt","valid");
            $("#email_receipt_no").parent().removeClass("has-error");
            $("#email_receipt_no").parent().siblings("label").css("color", "#676A6C");
          }
          if ($has_thisErr) {
            $("input[name='mobile_delivery_info']").attr("data-wt","valid");
            $("#mobile_delivery_info_no").parent().removeClass("has-error");
            $("#mobile_delivery_info_no").parent().siblings("label").css("color", "#676A6C");
          }
        }).on("click", "#email_receipt_yes", function() {
          var $has_thisErr = $(this).parent().hasClass("has-error");
          var $has_mobileErr = $("#mobile_delivery_info_no").parent().hasClass("has-error");
          if ($has_mobileErr) {
            $("input[name='mobile_delivery_info']").attr("data-wt","valid");
            $("#mobile_delivery_info_no").parent().removeClass("has-error");
            $("#mobile_delivery_info_no").parent().siblings("label").css("color", "#676A6C");
          }
          if ($has_thisErr) {
            $("input[name='email_receipt']").attr("data-wt","valid");
            $("#email_receipt_no").parent().removeClass("has-error");
            $("#email_receipt_no").parent().siblings("label").css("color", "#676A6C");
          }
        })
        ;
      $cols = $("table");//.on("click", function(){
  
      // $('[class*="bootstrap-touchspin-"]').click(function(event) {
      //   var $this = $(this);
  
      //   if ($this.hasClass('bootstrap-touchspin-down')) {
      //     alert('down');
      //   } else if ($this.hasClass('bootstrap-touchspin-up')) {
      //     alert('up');
      //   }
      // });
      
      $('#catModal').on('show.bs.modal', function (event) {
          // Fix Animate.css
          //console.log('Called me');
          $('#orderContainer').removeClass('animated fadeInRight');
      });
  
      $('#catModal').on('hidden.bs.modal', function (e) {
          // Fix Animate.css
         
         // $('#orderContainer').addClass('animated fadeInRight');
      });
      $("#inputPostCode").change(function (e) {
        
        console.log("Called change event of inputpostcode 2382");
        // if (e.keyCode == 13) {
        // e.preventDefault();
        getAddresses($(this));
        //return false;
        //}
        
      });
      $(".arr").on("click", function() {
        $("#col-5").toggle("slow");
        // $("#col-5").toggleClass("col-sm-5");
        // $("#col-5").toggleClass("d-none");
        $("#col-7").toggleClass("col-sm-7");
        $("#col-7").toggleClass("col-sm-12");
        $(".fa-arrow-left").toggleClass("d-none");
        $(".fa-arrow-right").toggleClass("d-none");
        $(".arr-right").toggle();
        $(".arr-left").toggle();
      })
      // $("#inputPostCode").on("change", function() {
      //   if ($("#inputPostCode").val() == '' && $("#postCodeErr").css("display") == "block") {
      //     $("#postCodeErr").css("display","none");
      //   }
      // })
      // $("#inputPostCode").on("focusout", function() {
      //   var postCode = $(this).val();
      //   console.log("check post code");
      //   if (postCode != '') {
      //     $.ajax({
      //       type: "POST",
      //       async: false,
      //       url: "index.php?controller=pjAdminOrders&action=pjActionCheckPostcode",
      //       data: {post_code: postCode},
      //       success: function (data) {
      //         if (data.code == 100) {
      //           $("#inputPostCode").parent().addClass("has-error");
      //           $("#postCodeErr").text("Post code is not available for delivery");
      //           $("#postCodeErr").css("display","block");
      //         } 
      //       }
      //     });
      //   } else {
      //     $("#inputPostCode").parent().removeClass("has-error");
      //     $("#postCodeErr").css("display","none");
      //   }
        
      // })
      $("#phone_no").on("input", function() {
        getClientInfo($(this));
      })
      // $("#submitJs").on("click", function(e) {
      //   e.preventDefault();
      //   $("#submitJs").prop('disabled', true);
      //   if ($("#frmCreateOrder_pos").valid()) {
      //     $("#frmCreateOrder_pos").submit();
      //   } else {
      //     $("#submitJs").prop('disabled', false);
      //   }
      // })
      $("#c_name, #c_surname").on("change input", function(e) {
        var firstname = $("#c_name").val();
        var surname = $("#c_surname").val();
        if (firstname.length > 0) {
          $("#c_surname").removeClass('required');
        } else if (surname.length > 0) {
          $("#c_name").removeClass('required');
        } else {
          $("#c_name").addClass('required');
        }
      })
      $("#phone_no").keydown(function (e) {
        if (e.keyCode == 13) {
          e.preventDefault();
          getClientInfo($(this));
        //return false;
        }
      });
      
      $(document).ready(function() {
       var $page = $('#updatePage');
       if ($page.text() === "Update Order") {
  
        $("#fdOrderList").children("tbody").children("tr.fdLine").each(function(){
  
        var pTime = $(this).children("td:nth-child(6)").children().text();
        $(this).children("td:first").find("select").change(function(){
          $totPrepTime = $totPrepTime - parseInt($(this).parents("tr").children("td:nth-child(6)").children().text());
        })
        $totPrepTime = $totPrepTime + parseInt(pTime);
        
       });
       var lastRowIndex = $("#fdOrderList tr:last").attr("data-index"),
          $product = $("#fdProduct_" + lastRowIndex).val(),
          $price = $("#fdPrice_" + lastRowIndex).val(); 
          if($product != "" || $price != ""){
            var index = Math.ceil(Math.random() * 999999),
            $clone = $("#boxProductClone").find("tbody").html();
            $clone = $clone.replace(/\{INDEX\}/g, "new_" + index);
            $("#fdOrderList").find("tbody.main-body").append($clone);
            bindTouchSpin();
            $("#fdOrderList").show();
            // console.log('Index:',index);
            $('#fdProduct_new_'+index).addClass('selectpicker').selectpicker('refresh');
          }
        //alert("Update page");
       }    
      });
       $(window).on('beforeunload', function(){
          if ($("#createPage").text()=="Add new order" || $("#updatePage").text()=="Update Order") {
          return 'Are you sure you want to leave?';
        }
       });
      // $(document).submit(function(){
        
      // });
      // $(document).ready()
      function myTinyMceInit(pSelector, pValue) {
        tinymce.init({
          relative_urls: false,
          remove_script_host: false,
          convert_urls: true,
          browser_spellcheck: true,
          contextmenu: false,
          selector: pSelector,
          theme: "modern",
          height: 300,
          plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor",
          ],
          toolbar:
            "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
          image_advtab: true,
          menubar: "file edit insert view table tools",
          setup: function (editor) {
            editor.on("change", function (e) {
              editor.editorManager.triggerSave();
            });
          },
        });
        if (tinymce.editors.length) {
          tinymce.execCommand("mceAddEditor", true, pValue);
        }
      }
      $("#reminderEmailModal").on("hidden.bs.modal", function () {
        if (tinymce.editors.length > 0) {
          tinymce.execCommand("mceRemoveEditor", true, "mceEditor");
        }
      });
		$(".category-container").on("click", function() {
          var id = $(this).data("id");
          var category = $(this).text();
          $("#btnCategories").text(category);
          $("#slideCategories").css("display", "block");
          $("#categories-sec").slideUp(); 
          $.ajax({
            type: "POST",
            url: "index.php?controller=pjAdminPosOrders&action=pjActionGetProductsForCategory",
            data: {"category_id": id},
            success: function (data) {
              $("#products-sec").parents(".ibox-content").fadeIn();
                $("#products-sec").html(data);
                $("#products-sec").fadeIn();
                if ($("#products-sec")) {
                  showPage(1);
                }
            }
          })
        })

        $("#slideCategories").on("click", function() {
          $("#btnCategories").text("Menu")
          $("#categories-sec").slideDown(); 
          $("#products-sec").parents(".ibox-content").fadeOut();
          $("#products-sec").fadeOut();
          $("#page_navigation").html('');
          $(this).css("display", "none");
        })
        $(".next-tab").on("click", function(e) {
          e.preventDefault();
          var nextTab = $("#orderTab li.active").next("li");
          var caseValue = nextTab.children("a").attr("href");
          return validateAllTabs(caseValue, nextTab);
        })
        $("#orderTab .nav-link").on("click", function(e) {
          e.preventDefault();
          var nextTab = $(this).parent('li');
          var caseValue = $(this).attr('href');
          return validateAllTabs(caseValue, nextTab); 
        })
      //   function singleClick(e) {
      //     // do something, "this" will be the DOM element
      //     alert("clicked one time");
      // }
      
      // function doubleClick(e) {
      //     // do something, "this" will be the DOM element
      //     alert("clicked two times");

      // }
      
      // $("#checkDblClick").click(function(e) {
      //     var that = this;
      //     setTimeout(function() {
      //         var dblclick = parseInt($(that).data('double'), 10);
      //         if (dblclick > 0) {
      //             $(that).data('double', dblclick-1);
      //         } else {
      //             singleClick.call(that, e);
      //         }
      //     }, 300);
      // }).dblclick(function(e) {
      //     $(this).data('double', 2);
      //     doubleClick.call(this, e);
      // });
    $(document).on('click', '#sizeModal .form-check-input' ,function (e) {
      $("#sizeValue").val($(this).val());
      $("#sizeValue").siblings(".btn-primary").prop("disabled", false);
      $("#sizeModal .radio-buttons").removeClass("selected");
      $(this).addClass("selected");
    })
    $(document).on('click', '#sizeBtnSubmit' ,function (e) {
      $(this).prop("disabled", true);
      $("#sizeModal").modal('hide');
      var $productId = $(this).siblings("#sizeValue").attr('data-productId');
      var $sizeId = $(this).siblings("#sizeValue").val();
      var oldProduct = false;
      var $totalPrepTime = 0;
      $kordersPrepTime = parseInt($("#totKorderPrepTimeInput").val());
      $totalPrepTime = $kordersPrepTime;
      if ($("#fdOrderList_1").find("tbody.main-body > tr").length != 0) {
        var rows = $("#fdOrderList_1").find("tbody.main-body > tr");        
        rows.each(function() {
          var $index = $(this).data("index");
          var proInListId = $(this).children("td:nth-of-type(1)").children("input").val();
          var prodSizeInListId = $(this).children("#fdPriceTD_"+$index).children(".fdSize").val();
          $totalPrepTime = $totalPrepTime + parseInt($(this).data("preptime"));
          if (proInListId == parseInt($productId) && prodSizeInListId == parseInt($sizeId)) {
            var prevVal = parseInt($("#fdProductQty_"+$index).val());
            var newVal = prevVal + 1;
            $("#fdProductQty_"+$index).val(newVal);
            oldProduct = true;
            calPrice(1);
          }
        })
      }
      if (oldProduct == false) {
        $("#products-sec").parent().addClass("ibox-content");
        $("#products-sec").parent().addClass("sk-loading");
        $.get("index.php?controller=pjAdminPosOrders&action=pjActionGetProductPrices", {
            product_id: $productId, size_id: $sizeId
        }).done(function (data) { 
            $("tbody.main-body").append(data);
            //$("tbody.main-body > tr:last").data("preptime");
            if ($("tbody.main-body > tr").last().data("preptime") != '') {
              $totalPrepTime = $totalPrepTime + parseInt($("tbody.main-body > tr").last().data("preptime"));
            }
           $("#total_prep-time_format").text($totalPrepTime);
           $("#prep_time").val($totalPrepTime);
           calPrice(1);
           bindTouchSpin();
        })
      }
    })
		$(document).on('click', '#products-sec .img-container' ,function (e) {
            if (e && e.preventDefault) {
                e.preventDefault();
            }
            var $this = $(this);
            var that = this;
            if($this.attr("data-hasSize") == 'T') {
              $.post(
                "index.php?controller=pjAdminPosOrders&action=pjActionGetProductSizes", 
                { product_id: $this.data('id') }
              ).done(function (data) {
                $("#sizeModal").modal();
                $("#sizeModal .modal-body").html(data);
                $("#sizeModal #sizeValue").attr("data-productId", $this.data('id'));
              });
            } else {
                var oldProduct = false;
                var $totalPrepTime = 0;
                $kordersPrepTime = parseInt($("#totKorderPrepTimeInput").val());
                $totalPrepTime = $kordersPrepTime;
                if ($("#fdOrderList_1").find("tbody.main-body > tr").length != 0) {
                  var rows = $("#fdOrderList_1").find("tbody.main-body > tr.new");
                  console.log('Rows', rows);
                  rows.each(function() {
                    var $index = $(this).data("index");
                    var proInListId = $(this).children("td:nth-of-type(1)").children("input").val();
                    $totalPrepTime = $totalPrepTime + parseInt($(this).data("preptime"));
                    if (proInListId == parseInt($this.data('id'))) {
                      var prevVal = parseInt($("#fdProductQty_"+$index).val());
                      var newVal = prevVal + 1;
                      $("#fdProductQty_"+$index+".pj-field-count").val(newVal);
                      oldProduct = true;
                      calPrice(1);
                    }
                  })
                }
               
                if (oldProduct == false) {
                  console.log('Not old product');
                  $("#products-sec").parent().addClass("ibox-content");
                  $("#products-sec").parent().addClass("sk-loading");
                  $.get("index.php?controller=pjAdminPosOrders&action=pjActionGetProductPrices", {
                      product_id: $this.data('id'), size_id: ''
                  }).done(function (data) { 
                      $("tbody.main-body").append(data);
                      if ($("tbody.main-body > tr").last().data("preptime") != '') {
                        $totalPrepTime = $totalPrepTime + parseInt($("tbody.main-body > tr").last().data("preptime"));
                      }
                      $("#total_prep-time_format").text($totalPrepTime);
                      $("#prep_time").val($totalPrepTime);
                      calPrice(1);
                      bindTouchSpin();
                  })
                }
              //}
            //}, 300);
              }
        })
        .on("click", '#fdOrderList_1 .main-body .fdLine .tdProductName .product_desc',function (e) { 
          // $(this).data('double', 2);
          var $this = $(this);
          var $id = $this.siblings("input").val();
          $.post(
            "index.php?controller=pjAdminPosOrders&action=pjActionGetDescription", 
            { product_id: $id }
          ).done(function (data) {
            if (data.code == 200) {
              swal({
                title: data.product,
                text: data.description,
                // type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: false,
              },function () {
                swal.close();
              });
            }
            if (data.code == 201) {
              swal({
                title: data.product,
                text: "No Description",
                // type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: false,
              },function () {
                swal.close();
              });
            }
          });
          //doubleClick.call(this, e);
        })
        .on("click", '#fdOrderList_1 .main-body .fdLine .spcl_ins',function (e) { 
          var index_id = $(this).attr("data-index");
          var product_name = $(this).attr("data-pdname");
          var product_qty = parseInt($("#fdProductQty_" + index_id).val(), 10);
          $("#spl_ins_type_title").html(product_name+" X"+ product_qty);
          var special_instructions = $(this).siblings(".special-instruction").val();
          //var custom_special_instructions = $(this).siblings(".custom-special-instruction").val();
          var special_instructions_imgs = $("#fdSpecialInstructionImgs_"+index_id).children("input").attr("data-imgs");
          var product_qty = parseInt($("#fdProductQty_" + index_id).val(), 10);
          $.get(
            "index.php?controller=pjAdminPosOrders&action=pjActionGetSpecialInstructionTypes&selected_ins="+special_instructions+ "&qty=" + product_qty+ "&pdname=" + product_name,
          ).done(function (data) {
            $("#specialInstructionsModal").modal();
            $("#specialInstructionsModal .modal-body").html(data);
            //$("#specialInstructionsModal #custom_special-instruction").val(custom_special_instructions)
            $("#selectedInsValue").attr("data-index", index_id);
            $("#selectedInsValue").val(special_instructions);
            $("#selectedInsValue").attr("data-images", special_instructions_imgs);
            KioskBoard.run('.js-kioskboard-input');
          });
        })
        .on("click", '.product_spcl_ins',function (e) {
          var index = $(this).attr("data-index");
          var product_qty = parseInt($("#fdProductQty_" + index).val(), 10);
          var product_name = $("#fdSpecialInstructionImgs_"+index).attr("data-name");
          $("#spl_ins_view_title").html(product_name+" X"+ product_qty);
          var custom_ins = $("tr[data-index='"+index+"'] #fdCustomSpecialInstruction_"+index).val(); 
          $.get(
            "index.php?controller=pjAdminPosOrders&action=pjActionViewSpecialInstructionTypes&selected_ins="+custom_ins,
          ).done(function (data) {
            $("#specialInstructionsViewModal").modal();
            $("#specialInstructionsViewModal .modal-body").html(data);
          });
        })
        .on("click", '#section-special-instructions .instruction_type',function (e) { 
          var type_id = $(this).children("h2").attr("data-id");
          var selected_ins = $("#selectedInsValue").val();
          $("#back_btn").removeClass("d-none");
          $.get(
            "index.php?controller=pjAdminPosOrders&action=pjActionGetSpecialInstructions&id="+type_id+"&selected_ins="+selected_ins, 
          ).done(function (data) {
            $("#specialInstructionsModal .modal-body").html(data);
          });
        })
        .on("click", '#back_btn',function (e) { 
          $("#back_btn").addClass("d-none");
          $.get(
            "index.php?controller=pjAdminPosOrders&action=pjActionGetSpecialInstructionTypes", 
          ).done(function (data) {
            $("#specialInstructionsModal .modal-body").html(data);
          });
        })
        // .on("click", '#fdOrderList_1 .main-body .fdLine .spcl_ins',function (e) { 
        //   var index_id = $(this).attr("data-index");
        //   var special_instructions = $(this).siblings("input").val();
        //   $.get(
        //     "index.php?controller=pjAdminPosOrders&action=pjActionGetSpecialInstructions", 
        //   ).done(function (data) {
        //     $("#specialInstructionsModal").modal();
        //     $("#specialInstructionsModal .modal-body").html(data);
        //     $("#selectedInsValue").attr("data-index", index_id);
        //     $("#selectedInsValue").val(special_instructions);
        //     if(special_instructions != '') {
        //       var special_instructions_array = special_instructions.split(",");
        //       var special_instructions_imgs = "";
        //       $.each(special_instructions_array, function($i, $data) {
        //         $("#section-special-instructions .img_"+$data).addClass("spcl_ins_selected");
        //         var img_src = $("#section-special-instructions .img_"+$data).attr("src");
        //         special_instructions_imgs = special_instructions_imgs + img_src + ",";
        //       });
        //       $("#selectedInsValue").attr("data-images", special_instructions_imgs);
        //     }
        //     // $("#sizeModal #sizeValue").attr("data-productId", $this.data('id'));
        //   });
        //   //doubleClick.call(this, e);
          
        // })
        .on("click", '.spl_reset', function (e) {
          var id = $(this).attr("data-id");
          var clear = $(this).attr("data-clear");
          var clearSpl = $(this).attr("data-ins");
          var clearImgClass = $(".img_class_"+id);
          // $("#arr_"+id).attr("data-images", "");
          clearImgClass.removeClass("spcl_ins_selected");
          $("#"+clear).empty();
          $("#"+clearSpl).val("");
          var selected_ins_arr = $("#selectedInsValue").val();
          if (selected_ins_arr) {
            var ins_arr = JSON.parse(selected_ins_arr);
            var new_ins_arr = ins_arr.filter((temp) => {
              return temp.qid != id;
            });
            $("#selectedInsValue").val(JSON.stringify(new_ins_arr));
          }
          
        })
        .on("click", '#section-special-instructions img',function (e) { 
          var id = $(this).attr("data-id");
          var img = $(this).attr("src");
          var selected_ins_arr = $("#selectedInsValue").val();
          var selected_ins_imgs = $("#selectedInsValue").attr("data-images");
          $(this).toggleClass('spcl_ins_selected');
          if($(this).hasClass('spcl_ins_selected')) {
              selected_ins_arr = selected_ins_arr + id + ",";
              selected_ins_imgs = selected_ins_imgs + img + ",";
          } else {
            selected_ins_arr = selected_ins_arr.replace(id+',','');
            selected_ins_imgs = selected_ins_imgs.replace(img+',','');
          }
          $("#selectedInsValue").val(selected_ins_arr);
          $("#selectedInsValue").attr("data-images", selected_ins_imgs);
        })
        .on("click", '.col-special-instructions img',function (e) { 
          var qty_id = $(this).attr("data-qty_id");
          var id = $(this).attr("data-id");
          var img = $(this).attr("src");
          var cus_ins = $("#custom_special_"+qty_id).val();
          
          var selected_inst_arry = $("#arr_"+qty_id).val();
          var selected_inst_imgs = $("#arr_"+qty_id).attr("data-images");
          var sibling_select = $(this).siblings(".spcl_ins_selected");

          if (sibling_select.length > 0) {
            sibling_select.removeClass("spcl_ins_selected");
            var siblin_id = sibling_select.attr("data-id");
            var siblin_img = sibling_select.attr("src");
            selected_inst_arry = selected_inst_arry.replace(siblin_id + ',','');
            selected_inst_imgs = selected_inst_imgs.replace(siblin_img + ',','');
          }
          $(this).toggleClass('spcl_ins_selected');

          if ($(this).hasClass('spcl_ins_selected')) {
            selected_inst_arry = selected_inst_arry + id + ",";
            selected_inst_imgs = selected_inst_imgs + img + ",";
          } else {
            selected_inst_arry = selected_inst_arry.replace(id + ',','');
            selected_inst_imgs = selected_inst_imgs.replace(img + ',','');
          }

          var selected_ins_arr = $("#selectedInsValue").val();
          var selected_ins_imgs = $("#selectedInsValue").attr("data-images");

          $("#arr_"+qty_id).val(selected_inst_arry);
          $("#arr_"+qty_id).attr("data-images", selected_inst_imgs);

          var imgs_arr = selected_inst_imgs.split(",");
          var loadimgParent = $("#imgs_"+qty_id).empty();

          for (var i = 0; i < imgs_arr.length; i++) {
            if (imgs_arr[i] != "") {
              var img = new Image();
              img.src = imgs_arr[i];
              img.width = 20;
              img.height = 20;
              loadimgParent.append(img);
            }
            
          }
          // console.log(loadimgParent);

          var current_si = {
            "qid": qty_id,
            "ids": selected_inst_arry,
            "imgs": selected_inst_imgs,
            "cus_ins": cus_ins
          }

          let spl_ins = [];
          if (selected_ins_arr) {
            let pars = JSON.parse(selected_ins_arr);
            const index = pars.findIndex(obj => obj.qid == qty_id);
            if (index == -1) {
              pars.push(current_si);
            } else {
              pars[index] = current_si;
            }
            spl_ins = pars;
          } else {
            spl_ins.push(current_si);
          }
          selected_ins_arr = JSON.stringify(spl_ins);
          // console.log(spl_ins);
          $("#selectedInsValue").val(selected_ins_arr);
          $("#selectedInsValue").attr("data-images", selected_ins_imgs);
        })
        .on("click", '#specialInstructionsModal .specialInstructionsBtn',function (e) { 
          var index = $("#selectedInsValue").attr("data-index");
          var selected_ins_arr = $("#selectedInsValue").val();
          var selected_ins_imgs = $("#selectedInsValue").attr("data-images");
          var selected_imgs = selected_ins_imgs.split(",");
          var custom_si = $("#specialInstructionsModal #custom_special-instruction").val();
          
          var $elem_images = '';
          if (selected_ins_arr) {
            var parse = JSON.parse(selected_ins_arr);
          } else {
            var parse = [];
          }
          
          // const output = parse.reduce((prev, curr) => {
          //   return `${prev}<img src=${curr.imgs}>`;
          // }, "")
          var img_arr = parse.map( temp => temp.imgs);
          $.each(img_arr, function($img) {
            if (img_arr[$img] != '' && img_arr[$img] != 'undefined') {
              var $Img = "<img src='"+img_arr[$img]+"' />";
              $elem_images = $elem_images + $Img;
            }
          });

          var custom_si = $("#selectedInsValue").val();

          // var custom_si = $("#specialInstructionsModal #custom_special-instruction").val();

          if(custom_si != '') {
            $("tr[data-index='"+index+"'] #fdCustomSpecialInstruction_"+index).val(custom_si);
            $elem_images ="<i data-index ="+ index +" class='fa fa-paperclip product_spcl_ins'></i>";
          }
          
          $("tr[data-index='"+index+"'] #fdSpecialInstruction_"+index).val(selected_ins_arr);
          $("tr[data-index='"+index+"'] #fdSpecialInstructionImgs_"+index).html($elem_images);
          // $("tr[data-index='"+index+"'] #fdSpecialInstructionCustom_"+index+" input").val(custom_si);
          //$("#fdSpecialInstructionImgs_"+index).html($elem_images);
          $("#selectedInsValue").val('');
          $("#selectedInsValue").attr("data-images", "");
          $('#specialInstructionsModal').modal('hide');
        })
        .on("click", ".pj-remove-product", function (e) {
            if (e && e.preventDefault) {
              e.preventDefault();
            }
            var preptime = parseInt($(this).closest(".fdLine").data("preptime"));
            // $("#total_prep-time_format").text($totalPrepTime);
            //           $("#prep_time").val($totalPrepTime);
            var totprep =  parseInt($("#prep_time").val());
            if (totprep > 0 && (preptime > 0 || preptime != '')) {
               totprep = totprep - preptime;
            }
            $("#total_prep-time_format").text(totprep);
            $("#prep_time").val(totprep);
            $(this).closest(".fdLine").remove();
  
            // if ($("#fdOrderList_1").find("tbody.main-body > tr").length == 0) {
            //     //$("#fdOrderList").hide();
            // } else {
            //     calPrice(1);
            // }
            calPrice(1);
            return false;
        }).on("change", ".fdSize", function (e) {
            calPrice(1);
        }).on("change", ".pj-field-count", function (e) {
            calPrice(1);
        }).on("change", ".fdExtra", function (e) {
            calPrice(1);
        //}).on("click", ".pj-add-extra", function (e) {
            // if (e && e.preventDefault) {
            //   e.preventDefault();
            // }
            // var $this = $(this);
            // var index = $this.attr("data-index");
            // var $productEle = $("#fdProduct_" + index);
            // var $product_id = $productEle.val();
            // var $extra_table = $this.parent().siblings(".pj-extra-table");
            // console.log($product_id);
            // if ($product_id != "") {
            //   $.get("index.php?controller=pjAdminPos&action=pjActionGetExtras", {
            //     product_id: $product_id,
            //     index: $this.attr("data-index"),
            //   }).done(function (data) {
            //     $(data).appendTo($extra_table.find("tbody"));
            //     $extra_table.find(".pj-field-count").TouchSpin({
            //       verticalbuttons: true,
            //       buttondown_class: "btn btn-white",
            //       buttonup_class: "btn btn-white",
            //       min: 1,
            //       max: 4294967295,
            //     });
            //   });
            // }
            // return false;
          }).on("click", ".pj-remove-extra", function (e) {
            if (e && e.preventDefault) {
              e.preventDefault();
            }
            $(this).parent().parent().remove();
            calPrice(1);
            return false;
        }).on("click", ".close-keyboard", function (e) {
          $('.keyboard-container').addClass('d-none');
        }).on("click", "#sel_table_name_modal", function() {
          console.log('Called me');
          tableID = parseInt($('#res_table_name').val());
          $('#tableModal #no_of_persons').val($('#total_persons').val());
          $('#tableModal .confirm-table-btn').each(function(index, obj) {
            if (obj.id == tableID) {
              $(obj).addClass("selected");
            }
          });
          $('#tableModal').modal({backdrop: 'static', keyboard: false}, 'show');
        }).on("click", "#table_select", function() {
          console.log('Called me');
          $('#tableModal').modal();
        });
        $("#productSearch").click(function() {
          var $key = $("#inputSearch").val();
          if ($key != '') {
            $.get(
              "index.php?controller=pjAdminPosOrders&action=pjActionGetSearchResults",
              {
                q: $key,
              }
            ).done(function (data) {
              $("#products-sec").parents(".ibox-content").fadeIn();
              $("#categories-sec").slideUp();
              $("#products-sec").fadeOut();
              $("#products-sec").html(data);
              $("#products-sec").fadeIn();
              $("#slideCategories").slideDown();
              if ($("#products-sec")) {
                showPage(1);
              }
            });
          }
        })

      $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        $('#spl_pagination_container').children().removeClass('table-bg-primary').addClass('table-bg-default');
        $(this).removeClass('table-bg-default').addClass('table-bg-primary');
        var page = $(this).data('page');
        $("#pos_si_qty").children().addClass('d-none');
        $("#"+page).removeClass('d-none');
      });
      $(document).on('change', '.custom_inst', function(e) {
        var qty_id = $(this).attr("data-id");
        var cus_ins = $(e.target).val();

        var selected_ins_arr = $("#selectedInsValue").val();

        var current_si = {
          "qid": qty_id,
          "ids": "",
          "imgs": "",
          "cus_ins": cus_ins
        }

        let spl_ins = [];
        if (selected_ins_arr) {
          let pars = JSON.parse(selected_ins_arr);
          const index = pars.findIndex(obj => obj.qid == qty_id);
          if (index == -1) {
            pars.push(current_si);
          } else {
            pars[index].cus_ins = cus_ins;
          }
          spl_ins = pars;
        } else {
          spl_ins.push(current_si);
        }
        selected_ins_arr = JSON.stringify(spl_ins);
        $("#selectedInsValue").val(selected_ins_arr);
      });

        // $('#cookieConsent').cookieConsent({
        //   message: 'This website uses cookies. By using this website you consent to our use of these cookies.'
        // });

        function validateOrderTab(total, min_amt, currency) {
          if($("#fdOrderList_1 .main-body tr").length == 0) {
            cartEmptyPopup();
            return false;
          } else if(!$(".onoffswitch-order .onoffswitch-checkbox").prop("checked")) {
            if (total < min_amt) {
              swal({
                  title: "Minimum order amount is <br/> "+currency+' '+min_amt ,
                  type: "warning",
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "OK",
                  closeOnConfirm: true,
                  html: true
                },function () {
               });
              return false;
            } else {
              return true;
            }
          } else {
            return true;
          }
        }

        function validateAllTabs(caseValue, nextTab) {
          var active_frm = getActiveForm();
          var frm = $(active_frm);
          var total = parseFloat($(active_frm +" #total").val());
          var min_amt = parseFloat($(active_frm +" #min_amt").val());
          var currency = $("#paymentModal #payment_modal_curr").text();
          switch(caseValue) {
            case '#client':
              if (validateOrderTab(total, min_amt, currency)) {
                $("#orderTab li.active").removeClass("active");
                $("#orderTabContent .tab-pane.active").removeClass("active in");
                nextTab.addClass("active");
                $(caseValue).addClass("active in");
              } else {
                return false;
              }
            break;
            case '#orderType':
              if (!validateOrderTab(total, min_amt, currency)) {
                $("#order").addClass("active in");
                return false;
              }
              else if (validateClientTab()) {
                $("#orderTab li.active").removeClass("active");
                $("#orderTabContent .tab-pane.active").removeClass("active in");
                $("#chef").addClass("required");
                nextTab.addClass("active");
                $(caseValue).addClass("active in");
              } else {
                return false;
              }
            break;
            case '#order':
              return true;
            break; 
            default:
              return validateClientTab();
            break;
          }
        }

        function validateClientTab() {
          var active_frm = getActiveForm();
          var frm = $(active_frm);  
          return frm.valid();
        }
        
          function getTotal() {
            var $frm = null;
            var active_frm = getActiveForm();
            console.log(`active form ${active_frm}`);
            $frm = $(active_frm);
            console.log('form', $frm);
            //if ($("#fdOrderList_1").find("tbody.main-body > tr").length > 0 || 1) {
              $(".ibox-content").addClass("sk-loading");
              $.post(
                "index.php?controller=pjAdminPosOrders&action=pjActionGetTotal",
                 $frm.serialize()
              ).done(function (data) {
                if (data.price != "NULL") {
                  $(active_frm +" #price").val(data.price).valid();
                  $(active_frm +" #price_packing").val(data.price_packing);
                  $(active_frm +" #price_delivery").val(data.price_delivery);
                  $(active_frm +" #discount").val(data.discount);
                  $(active_frm +" #subtotal").val(data.subtotal);
                  $(active_frm +" #tax").val(data.tax);
                  $(active_frm +" #total").val(data.total);
      
                  $("#price_format").html(data.price_format);
                  $("#packing_format").html(data.packing_format);
                  $("#delivery_format").html(data.delivery_format);
                  $("#discount_format").html(data.discount_format);
                  $("#subtotal_format").html(data.subtotal_format);
                  $("#tax_format").html(data.tax_format);
                  //$(".total_format").html(data.total_format);
                  
                  // FOR CART POPUP

                  $(".price_format").html(data.price_format);
                  $(".packing_format").html(data.packing_format);
                  $(".delivery_format").html(data.delivery_format);
                  $(".discount_format").html(data.discount_format);
                  $(".subtotal_format").html(data.subtotal_format);
                  $(".tax_format").html(data.tax_format);
                  $(".total_format").html(data.total_format);

                  // END OF CART POPUP

                  // FOR BOTTOM CART TOTAL
                  $("#cartPriceBottom").html(data.total_format);
                  // FOR EPOS PAYMENT BUTTON 
                  
                  $("#btn-payment").attr("data-cart", data.total.toFixed(2));
                  $("#btn-payment-tel").attr("data-cart", data.total.toFixed(2));

                  var min_amt = $("#min_amt").val();
                  var type = !$(".onoffswitch-order .onoffswitch-checkbox").prop("checked");
                  
                  if (data.total < min_amt && type == true) {
                    $("#submitJs").attr('disabled','true');
                    $("#alertJs").css('display','block');
                  } else {
                    $("#submitJs").removeAttr('disabled');
                    $("#alertJs").css('display','none');
                  }
                } else {
                  resetCart(active_frm);
                }
                $(".ibox-content").removeClass("sk-loading");
              });
            //} 
          }
          function resetCart(frm_id) {
            $(frm_id +" #price").val(0).valid();
            $(frm_id +" #price_packing").val(0);
            $(frm_id +" #price_delivery").val(0);
            $(frm_id +" #discount").val(0);
            $(frm_id +" #subtotal").val(0);
            $(frm_id +" #tax").val(0);
            $(frm_id +" #total").val(0);
            $("#price_format").html("");
            $("#packing_format").html("");
            $("#delivery_format").html("");
            $("#discount_format").html("");
            $("#subtotal_format").html("");
            $("#tax_format").html("");
            $(".total_format").html("");
            // FOR CART POPUP
            $(".price_format").html("");
            $(".packing_format").html("");
            $(".delivery_format").html("");
            $(".discount_format").html("");
            $(".subtotal_format").html("");
            $(".tax_format").html("");
            $(".total_format").html("");
            // END OF CART POPUP
            // FOR BOTTOM CART TOTAL
            $("#cartPriceBottom").html("");
            // FOR EPOS PAYMENT BUTTON 
            //$("#btn-payment").attr("data-cart", data.total+".00");
          }
          function calPrice(get_total) {
            var prices = {};
            $("#fdOrderList_1")
              .find("tbody.main-body > tr")
              .each(function () {
                var total = 0,
                  total_format = "",
                  index = $(this).attr("data-index"),
                  product = $("#fdProduct_" + index).val(),
                  price_element = $("#fdPrice_" + index),
                  product_qty = parseInt($("#fdProductQty_" + index).val(), 10),
                  price = 0;
                var element_type = price_element.attr("data-type");
                if (element_type == "input") {
                  price = parseFloat(price_element.val());
                } else {
                  var attr = $("option:selected", price_element).attr("data-price");
                  if (typeof attr === typeof undefined) {
                    attr = $(price_element).attr("data-price");
                  }
                  
                  if (typeof attr !== typeof undefined && attr !== false) {
                    price = parseFloat(attr, 10);
                  }
                }
                if (price > 0 && product_qty > 0) {
                  total += parseFloat(price) * product_qty;
                  $(".fdExtra_" + index).each(function () {
                    var extra_index = $(this).attr("data-index"),
                      extra = $(this).val(),
                      extra_qty = parseInt($("#fdExtraQty_" + extra_index).val(), 10);
                    if (extra != "" && extra_qty > 0) {
                      var extra_price = 0;
                      var extra_attr = $("option:selected", this).attr("data-price");
                      if (
                        typeof extra_attr !== typeof undefined &&
                        extra_attr !== false
                      ) {
                        extra_price = parseFloat(extra_attr, 10);
                      }
                      if (extra_price > 0) {
                        total += extra_price * extra_qty;
                      }
                    }
                  });
                }
                prices[index] = total;
              });
            
            $.post("index.php?controller=pjAdminPosOrders&action=pjActionFormatPrice", {
              prices: prices,
            }).done(function (data) {
              if (data.status == "OK") {
                for (var o in data.prices) {
                  if (data.prices.hasOwnProperty(o)) {
                    $("#fdTotalPrice_" + o).html(data.prices[o]);
                  }
                }
                if (get_total == 1) {
                  getTotal();
                }
              }
            });
          }
          function getAddresses($this) { 
            var Client = IdealPostcodes.Client;
            var lookupPostcode = IdealPostcodes.lookupPostcode;
            var client = new Client({ api_key: "iddqd" });
            postcode = $this.val();
            if (postcode != '') {
              var addressList = $(
                '<select id="selAddress" name="selectAddress" class="form-control"/>'
              );
              $("<option />", { value: 0, text: "--Choose--"}).appendTo(addressList);
             
              lookupPostcode({ postcode, client }).then(function (result) {
                postalResult = result;
                if (result.length > 0) {
                  var $pc_outward = result[0].postcode_outward;
                  var $pc_valid;
                  var i = 1;
                  if ($("#post_code").hasClass("has-error")) {
                    $("#post_code").removeClass("has-error");
                    $("#postCodeErr").css("display","none");
                  }
                  if ($pc_outward) {
                    if($(".onoffswitch-overridePc .onoffswitch-checkbox").prop("checked")) {
                      $pc_valid = true;
                    } else {
                      $.ajax({
                        type: "POST",
                        async: false,
                        url: "index.php?controller=pjAdminPosOrders&action=pjActionCheckPostcode",
                        data: {post_code: $pc_outward},
                        success: function (data) {
                          if (data.code == 100) {
                            $pc_valid = false;
                          } else {
                            $pc_valid = true;
                          }
                        }
                      });
                    }
                    
                  }
                  if ($pc_valid) {
                    $("#inputPostCode").attr('data-wt','valid');
                    $.each(result, function (index) {
                    var address =
                      result[index].line_1 +
                      ", " +
                      result[index].line_2 +
                      ", " +
                      result[index].line_3;
                    
                    $("<option />", { value: i, text: address }).appendTo(
                      addressList
                    );
                    i = i + 1;
                    });
                    $("#addressList").html(addressList);
                  } else {
                    $("#inputPostCode").parent().addClass("has-error");
                    $("#inputPostCode").attr('data-wt','invalid');
                    $("#postCodeErr").text("Post code is not available for delivery");
                    $("#postCodeErr").css("display","block");
                    $("#d_address_1").val('');
                    $("#d_address_2").val('');
                    $("#d_city").val('');
                  }
                 }  
                if (result.length == 0) {
                  $("#post_code").addClass("has-error");
                  $("#postCodeErr").css("display","block");
                  $("#d_address_1").val('');
                  $("#d_address_2").val('');
                  $("#d_city").val('');
                  $("#inputPostCode").attr('data-wt','invalid');
                }
                if (result.length == 1) {
                  $("#selAddress").change(function(){
                  var index = $(this).val();
                  index = index - 1;
                  if (index >= 0) {
                    $("#d_address_1").val(result[index].line_1);
                    $("#d_address_2").val(result[index].line_2);
                    $("#d_city").val(result[index].post_town);
                  } else {
                    $("#d_address_1").val('');
                    $("#d_address_2").val('');
                    $("#d_city").val('');
                  }
                })
               }
              });
            } else {
              $("#postCodeErr").css("display","none");
              $("#inputPostCode").attr('data-wt','invalid');
            }
      
          }
          function getClientInfo($this) {
            $c_phone = $this.val();
              $cinfo = $.ajax({
                type: "POST",
                async: false,
                url: "index.php?controller=pjAdminPosOrders&action=pjActionCheckClientPhoneNumber",
                data: { 
                  value: function() {
                    return $c_phone;
                  }
                },
            });
            var fieldName = '#phone_no';
            if($cinfo.responseText == 'new user') {
              if ($(fieldName).val().length == 11) {
                reValidate('#phone_no');
              }
              $("#c_title").val("mr");
              $("#c_email").val("");
              $("#c_email").attr('data-wt','invalid');
              //$("#c_surname").val("");
              $("#inputPostCode").val("");
              $("#d_address_1").val("");
              $("#d_address_2").val("");
              $("#d_city").val("");
              //$("#c_name").val("");
              $("#mobile_delivery_info_yes").prop("checked",true);
              $("#mobile_offer_yes").prop("checked",false);
              $("#email_receipt_yes").prop("checked",true);
              $("#email_offer_yes").prop("checked",false);
              $('#jsEmailOffer').css("display","none");
            } else {
              var c_arr = $cinfo.responseJSON[0];
              reValidate('#phone_no');
              $("#c_title").val(c_arr.c_title);
              //c_arr.email == '0' ? $("#c_email").val("") : ;
              if (c_arr.email == '0') {
                $("#c_email").val("");
              } else {
                $("#c_email").val(c_arr.email);
                $('#jsEmailOffer').css("display","block");
                c_arr.email_offer == 1 ? $("#email_offer_yes").prop("checked",true) : $("#email_offer_no").prop("checked",true);
                $('[name=email_offer]').attr("data-wt","valid");
              }
              if ($("#c_name").length > 0 || $("#c_surname").length > 0 ) {
                $("#c_name").removeClass("required");
                $("#c_surname").removeClass("required");
              }
              if (c_arr.status == 'F') {
                swal({
                  title: "Account Disabled!",
                  text: "The Client account has been disabled...",
                  type: "warning",
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "OK",
                  closeOnConfirm: true,
                },function () {
                });
              }
              //c_arr.sms_email == '' ? $("#c_email").attr('data-wt','invalid') : $("#c_email").attr('data-wt','valid');
              $("#c_surname").val(c_arr.u_surname);
              $("#inputPostCode").val(c_arr.c_postcode);
              if (c_arr.c_postcode) {
                $("#inputPostCode").attr('data-wt','valid');
              }
              $("#d_address_1").val(c_arr.c_address_1);
              $("#d_address_2").val(c_arr.c_address_2);
              $("#d_city").val(c_arr.c_city);
              $("#c_name").val(c_arr.name);
              c_arr.email_delivery_info == 1 ? $("#email_delivery_info_yes").prop("checked",true) : $("#email_delivery_info_no").prop("checked",true);
              c_arr.email_receipt == 1 ? $("#email_receipt_yes").prop("checked",true) : $("#email_receipt_no").prop("checked",true);
              c_arr.mobile_delivery_info == 1 ? $("#mobile_delivery_info_yes").prop("checked",true) : $("#mobile_delivery_info_no").prop("checked",true); 
              c_arr.mobile_offer == 1 ? $("#mobile_offer_yes").prop("checked",true) : $("#mobile_offer_no").prop("checked",true);
              }  
          }
          function reValidate(fieldName) {
            $(fieldName).attr("data-wt","valid");
            $(fieldName).parent().removeClass('has-error');
            $(fieldName+'-error').css("display", "none");
          }
          function makePager(page){
            var show_per_page = 16;
            var number_of_items = $('#products-sec .col-sm-3').length;
            var number_of_pages = Math.ceil(number_of_items / show_per_page);
            var number_of_pages_todisplay = 5;
            var navigation_html = '';
            var current_page = page;
            var current_link = (number_of_pages_todisplay >= current_page ? 1 : number_of_pages_todisplay + 1);
            $("#nop").val(number_of_pages);
            if (current_page > 1)
                current_link = current_page;
            if (current_link != 1) {
              navigation_html += "<a class='nextbutton previous' >«</a>&nbsp;";
            } else {
              navigation_html += "<a class='nextbutton previousDisabled' >«</a>&nbsp;";
            }
            
            if (current_link == number_of_pages - 1) current_link = current_link - 3;
            else if (current_link == number_of_pages) current_link = current_link - 4;
            else if (current_link > 2) current_link = current_link - 2;
            else current_link = 1;
            var pages = number_of_pages_todisplay;
            while (pages != 0) {
                if (number_of_pages < current_link) { break; }
                if (current_link >= 1)
                    navigation_html += "<a class='" + ((current_link == current_page) ? "currentPageButton" : "numericButton") + " showpage'>" + (current_link) + "</a>&nbsp;";
                current_link++;
                pages--;
            }
            if (number_of_pages > current_page){
                navigation_html += "<a class='nextbutton next' >»</a>";
            } else {
                navigation_html += "<a class='nextbutton nextDisabled' >»</a>";
            }
            if (number_of_pages == 1) {
              $('#page_navigation').html("");
            } else {
              $('#page_navigation').html(navigation_html);
            }
                          
          }
          function showPage(page) {
            var pageSize = 16;
            $("#products-sec .col-sm-3").hide();
            $('#current_page').val(page);
            $("#products-sec .col-sm-3").each(function (n) {
              if (n >= pageSize * (page - 1) && n < pageSize * page) {
                $(this).show();
              }
               
            });
            makePager(page);
          }
            //showPage(1);
          function next() {
            var new_page = parseInt($('#current_page').val()) + 1;
            showPage(new_page);
          }
          function last(number_of_pages) {
            var new_page = number_of_pages;
            $('#current_page').val(new_page);
            showPage(new_page);
          }
          function first() {
            var new_page = "1";
            $('#current_page').val(new_page);
            showPage(new_page);    
          }
          function previous() {
            var new_page = parseInt($('#current_page').val()) - 1;
            $('#current_page').val(new_page);
            showPage(new_page);
          }
          function showBalance(paying, $modalName) {
            var tot = $($modalName+" #payment_modal_tot").text();
            var tot_int = parseFloat(tot);
            var balance;
            var balance_format;
            paying = paying.trim();
            var currency = $($modalName+" #payment_modal_curr").text();
            if(isNaN(paying) || paying == '' ||  parseFloat(paying) < tot_int ) {
              $($modalName+" #error-msg").removeClass("d-none");
              balance_format = currency + " 0.00" ;
              $($modalName+" #paymentBtn").attr("data-valid", "false");
            } else {
              $($modalName+" #error-msg").addClass("d-none");
              balance = parseFloat(paying) - tot_int;
              balance_format = currency +" "+ parseFloat(balance).toFixed(2);
              $($modalName+" #paymentBtn").attr("data-valid", "true");
            }
            $($modalName+" #payment_modal_bal").text(balance_format);
          }
          function currencyBtns(cart_tot, $modalName) {
            var currency_sign = $($modalName+" #payment_modal_curr").text();
            var currencies = [1, 2, 5, 10, 20, 50];
            let returnLarger = (arr, num) => arr.filter(n => n > num);
            var currenciesLarger = returnLarger(currencies, parseFloat(cart_tot));
            while (currenciesLarger.length > 6) { currenciesLarger.pop(); }
            var htmlBtns = "";
            for(var counter = 0; counter<currenciesLarger.length; counter++) {
              htmlBtns += "<a href='javascript:;' class='btn' data-rs='"+ currenciesLarger[counter] +".00'>"+ currency_sign +" "+ currenciesLarger[counter] +".00</a>";
            }
            if (parseFloat(cart_tot) > 10 && parseFloat(parseFloat(cart_tot)) <= 15 ) {
              htmlBtns +=  "<a href='javascript:;' class='btn' data-rs='15.00'>"+ currency_sign +" " +"15.00</a>";
            }
            return htmlBtns +=  "<a href='javascript:;' class='btn' data-rs='"+ cart_tot +"'>"+ currency_sign +" "+ cart_tot +"</a>";
          }
          function timeNow() {
            var currentUtcTime = new Date($.now());
            var d = new Date(currentUtcTime.toLocaleString('en-US', { timeZone: 'Europe/London' }));
            var yr = d.getFullYear();
            var month = d.getMonth() + 1;
            var date = d.getDate();
            var hr = d.getHours();
            var min = d.getMinutes();
            var sec = d.getSeconds();
            if (month < 10) {
              month = "0" + month;
            } 
            if(hr < 10) {
              hr = "0" + hr;
            } 
            if(min < 10) {
              min = "0" + min;
            } 
            if(sec < 10) {
              sec = "0" + sec;
            } 
            if (date<10) {
              date = "0" + date;
            }
            return yr + "-" + month + "-" + date + " " + hr + ":" + min + ":" + sec;
          }
          function dateFormat($date) {
            var dateArr = $date.split(".");
            return dateArr[2]+"-"+dateArr[1]+"-"+dateArr[0];
          }
          function hideTelPhoneDelivery() {
            $(".order-delivery").hide();
            $("#address_title").hide();
            $(".order-delivery").find(".fdRequired").removeClass("required");
            $(".order-pickup").show();
            $(".order-pickup").find(".fdRequired").addClass("required");
            $("#jsOverridePc").css("display","none");
            $("#inputPostCode").val("");
            $("#d_address_1").val("");
            $("#d_address_2").val("");
            $("#d_city").val("");
            $("#delivery_fee_frmgrp").css("display","none");
            if ($("#delivery_fee").val()) {
              $("#delivery_fee").val("");
              calPrice(1);
              
            }
            if ($("#p_time").val() && !($("#pickup_time").val())) {
              var mins = parseInt($("#p_time").val());
              pickupTime(mins);
            }
            if ($("#submitJs").prop('disabled')) {
              $("#submitJs").removeAttr("disabled");
              $("#alertJs").css('display','none');
            }
            if ($("#d_time").val() != '') {
              var temp = $("#d_time").val();
              $("#p_time").val(temp);
              pickupTime(temp)
            }
          }
          function getPendingOrders(origin = 'Pos') {
            $.ajax({
              type: "POST",
              async: false,
              url: "index.php?controller=pjAdminPosOrders&action=pjActionGetPendingOrders",
              data: { 
                'origin': origin
              },
              success: function (data) {
                var $listObj = $('#pending-'+origin.toLowerCase());
                $listObj.html(' - '+data.message);
                return;
              },
            });
          }
        function validateCart() {
          var cartError = false;
          $('#fdOrderList_1 .main-body tr.fdLine').each(function(index, tr) {
            //console.log(tr);
            var rowID = $(tr).data('index');
            //console.log('RowID: ', rowID);
            var qtyID = 'fdProductQty_'+rowID;
            var extraID = 'fdExtra_'+rowID;
            var extraTableID = 'fdExtraTable_'+rowID;
            var itemQty = $(tr).find('#'+qtyID).val();
            if (itemQty < 0 || itemQty == '') {
              $(tr).find('#'+qtyID).parent().addClass('has-error');
              cartError = true;
              //return false;
            } else {
              $(tr).find('#'+qtyID).parent().removeClass('has-error');
            }
           //var $extraQtyObj = $(tr).find('#'+extraID);
            var $extraTableObj = $(tr).find('#'+extraTableID);
            console.log('Length: ', $extraTableObj.length);

            if ($extraTableObj.length > 0) {
              $($extraTableObj).find('tr').each(function(extraIndex, extraTR) {
                console.log("Extra tr", extraTR);
                console.log($(extraTR).find('#'+extraID).val());
                var $extraQtyObj = $(extraTR).find('#'+extraID);
                var extraQtyID = 'fdExtraQty_'+$extraQtyObj.data('index');
                var extraItemVal = $(extraTR).find('#'+extraID).val();
                var extraQty = $extraQtyObj.val();
                //console.log('extraItemVal: ',extraItemVal, 'extraQty: ', extraQty);
                if (extraQty < 0 || extraQty == '' || extraItemVal == '') {
                  // if (extraQty < 0 || extraQty == '') {
                  //   $(tr).find('#'+extraQtyID).parent().addClass('has-error');
                  // }
                  console.log('came in');
                  $extraQtyObj.parent().parent().addClass('has-error');
                  cartError = true;
                  //return false;
                } else {
                  $extraQtyObj.parent().parent().removeClass('has-error');
                }
              });
            }
            var $updatePriceObj = $(tr).find('#fdPrice_'+rowID);
            if ($updatePriceObj.length) {
              var updatePriceVal = $updatePriceObj.val();
              if (updatePriceVal == '') {
                $updatePriceObj.parent().addClass('has-error');
                cartError = true;
                //return false;
              } else {
                $updatePriceObj.parent().removeClass('has-error');
              }
            }
            if (cartError) {
              return false;
            }
          });
          return cartError;
        }
        function cartEmptyPopup() {
          if ($("#fdOrderList_1 .main-body tr").length == 0) {
            swal({
              title: "Cart is empty!",
              // text: "Cart is empty!",
              type: "warning",
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "OK",
              closeOnConfirm: false,
            },function () {
              swal.close();
            });
          }
        }
   

        // $("#products-sec .img-container").on("click", function (e) {
        //     console.log("clicked product");
        //     if (e && e.preventDefault) {
        //         e.preventDefault();
        //     }
        //     var $this = $(this);
            
        //     $.get("index.php?controller=pjAdminPos&action=pjActionGetProductPrice", {
        //         product_id: $this.data('id')
        //       }).done(function (data) { 
        //         $("tbody .main-body").append(data);
        //       })
        //})

        /*******************************************************************************************************************/
	});

  $.fn.inputFilter = function(callback, errMsg) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
      if (callback(this.value)) {
        // Accepted value
        if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
          $(this).removeClass("input-error");
          this.setCustomValidity("");
        }
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        // Rejected value - restore the previous one
        $(this).addClass("input-error");
        this.setCustomValidity(errMsg);
        this.reportValidity();
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        // Rejected value - nothing to restore
        this.value = "";
      }
    });
  };
})(jQuery_1_8_2);