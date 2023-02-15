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
      $products_in_cart = [],
      
      // ! MEGAMIND

      $frmCreateOrder = $("#frmCreateOrder_pos"),
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
            var cd = $(this).val().split(".");
            if (todayDate < cd[0] || todayMonth < cd[1] || todayYear < cd[2]) {
              console.log("not today");
              $("#jsTimeDiv").css("display", "none");
              $("#jsChangeTimeDiv").css("display", "block");
              $("#d_time").removeClass("fdRequired");
              $("#d_time").removeClass("required");
              $("#jsChangeTimeDivSelect").addClass("fdRequired");
              $("#jsChangeTimeDivSelect").addClass("required");
            } else {
              $("#jsTimeDiv").css("display", "block");
              $("#jsChangeTimeDiv").css("display", "none");
              $("#d_time").addClass("fdRequired");
              $("#d_time").addClass("required");
              $("#jsChangeTimeDivSelect").removeClass("fdRequired");
              $("#jsChangeTimeDivSelect").removeClass("required");
            }
            validateDeliveryTime();
            
            if ($("#voucher_code").val()) {
              validateVoucher($("#voucher_code").val());
            } else {
              calPrice(1);
            }
          });
          $("#p_date").on("changeDate", function (e) {
            var cd = $(this).val().split(".");
            
            if (todayDate < cd[0] || todayMonth < cd[1] || todayYear < cd[2]) {
              console.log("not today");
              $("#jsPTimeDiv").css("display", "none");
              $("#jsChangePTimeDiv").css("display", "block");
              $("#p_time").removeClass("fdRequired");
              $("#p_time").removeClass("required");
              $("#jsChangePTimeDivSelect").addClass("fdRequired");
              $("#jsChangePTimeDivSelect").addClass("required");
            } else {
              $("#jsPTimeDiv").css("display", "block");
              $("#jsChangePTimeDiv").css("display", "none");
              $("#p_time").addClass("fdRequired");
              $("#p_time").addClass("required");
              $("#jsChangePTimeDivSelect").removeClass("fdRequired");
              $("#jsChangePTimeDivSelect").removeClass("required");
            }
            validatePickupTime();
            
            if ($("#voucher_code").val()) {
              validateVoucher($("#voucher_code").val());
            } else {
              calPrice(1);
            }
          });
    }
    $("#jsChangeTimeDivSelect").change(function() {
      $(this).parents().find(".col-lg-4").removeClass("has-error");
      $("#delivery_time").val($(this).val());
      $("#voucher_code").removeAttr("disabled");
    })
    $("#jsChangePTimeDivSelect").change(function() {
      $(this).parents().find(".col-lg-4").removeClass("has-error");
      $("#pickup_time").val($(this).val());
      $("#voucher_code").removeAttr("disabled");
    })
    $('#d_time').on("keyup",function() {
      var mins;
      if($(this).val()) { 
        mins = parseInt($(this).val()); 
        deliveryTime(mins);
        validateDeliveryTime();
        if ($("#delivery_time").val()) {
           $("#delivery_time").parent().parent().removeClass("has-error")
        }
        if ($("#voucher_code").val()) {
          validateVoucher($("#voucher_code").val());
        } else {
          calPrice(1);
          $("#voucher_code").removeAttr("disabled");
        }
      } else {
        $("#delivery_time").val('');
        $("#delivery_time").parent().parent().addClass("has-error");
        $("#aproxDt").text('');
        if ($("#voucher_code").val()) {
          calPrice(1)
        }
      }
    })
    $('#d_time').on("change",function() {
      var mins;
      if($(this).val()) { 
        mins = parseInt($(this).val()); 
        deliveryTime(mins);
        validateDeliveryTime();
        if ($("#delivery_time").val()) {
           $("#delivery_time").parent().parent().removeClass("has-error")
        }
        
        if ($("#voucher_code").val()) {
          validateVoucher($("#voucher_code").val());
        } else {
          calPrice(1);
          $("#voucher_code").removeAttr("disabled");
        }
      } else {
        $("#delivery_time").val('');
        $("#delivery_time").parent().parent().addClass("has-error");
        $("#aproxDt").text('');
        if ($("#voucher_code").val()) {
          calPrice(1);
        }
      }
    })
    $('#d_time').on("focusout",function() {
      var mins;
      if($(this).val()) { 
        mins = parseInt($(this).val()); 
        deliveryTime(mins);
        validateDeliveryTime();
        if ($("#delivery_time").val()) {
           $("#delivery_time").parent().parent().removeClass("has-error")
        }
        if ($("#voucher_code").val()) {
          validateVoucher($("#voucher_code").val());
        } else {
          calPrice(1);
          $("#voucher_code").removeAttr("disabled");
        }
      } else {
        $("#delivery_time").val('');
        $("#delivery_time").parent().parent().addClass("has-error");
        $("#aproxDt").text('');
        if ($("#voucher_code").val()) {
          calPrice(1);
        }
      }
    })
    $('#p_time').on("keyup",function() {
      var mins;
      if($(this).val()) {
        mins = parseInt($(this).val());
        pickupTime(mins);
        validatePickupTime();
        if ($("#pickup_time").val()) {
           $("#pickup_time").parent().parent().removeClass("has-error")
        }
        if ($("#voucher_code").val()) {
          validateVoucher($("#voucher_code").val());
        } else {
          calPrice(1);
          $("#voucher_code").removeAttr("disabled");
        }
      } else {
        $("#pickup_time").val('');
        $("#pickup_time").parent().parent().addClass("has-error");
        $("#aproxPt").text('');
        if ($("#voucher_code").val()) {
          calPrice(1);
        }
      }
    })
    $('#p_time').on("change",function() {
      var mins;
      if($(this).val()) {
        mins = parseInt($(this).val());
        pickupTime(mins);
        validatePickupTime();
        if ($("#pickup_time").val()) {
           $("#pickup_time").parent().parent().removeClass("has-error")
        }
        if ($("#voucher_code").val()) {
          validateVoucher($("#voucher_code").val());
        } else {
          calPrice(1);
          $("#voucher_code").removeAttr("disabled");
        }
      } else {
        $("#pickup_time").val('');
        $("#pickup_time").parent().parent().addClass("has-error");
        $("#aproxPt").text('');
        if ($("#voucher_code").val()) {
          calPrice(1);
        }
      }
    })
    $('#p_time').on("focusout",function() {
      var mins;
      if($(this).val()) {
        mins = parseInt($(this).val());
        pickupTime(mins);
        validatePickupTime();
        if ($("#pickup_time").val()) {
           $("#pickup_time").parent().parent().removeClass("has-error")
        }
        if ($("#voucher_code").val()) {
          validateVoucher($("#voucher_code").val());
        } else {
          calPrice(1);
          $("#voucher_code").removeAttr("disabled");
        }
      } else {
        $("#pickup_time").val('');
        $("#pickup_time").parent().parent().addClass("has-error");
        $("#aproxPt").text('');
        if ($("#voucher_code").val()) {
          calPrice(1);
        } 
      }
    })
    $('#phone_no').on("focusout", function() {
      validatePhoneNumber($(this).val());
    })
    $('#c_email').on("focusout", function() {
      validateEmail($(this).val());
    })
    $('[name=email_offer]').on("click", function() {
      $('[name=email_offer]').attr("data-wt","valid");
    })
    $('#voucher_code').on("change", function() {
      if ($(this).val()) {
        validateVoucher($(this).val());
      } else {
        $("#voucher_code").parent().removeClass("has-error");
        calPrice(1);
      }
      
    })
    $('#voucher_code').on("keyup", function() {
      
      if ($(this).val()) {
        validateVoucher($(this).val());
      } else {
        $("#voucher_code").parent().removeClass("has-error");
        calPrice(1);
      }
      
    })
    $('#voucher_code').on("input", function() {
      
      if ($(this).val()) {
        validateVoucher($(this).val());
      } else {
        $("#voucher_code").parent().removeClass("has-error");
        calPrice(1);
      }
    })
      if ($frmCreateOrder.length > 0 || $frmUpdateOrder.length > 0) {
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
          //console.log("Comes to up");
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
        $frmCreateOrder.validate({
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
              voucher: myLabel.voucher_err,
            },
            delivery_fee: {
              delivery_fee: myLabel.delivery_fee_err,
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
            console.log(form);
            var $ele = $(validator.errorList[0].element);
            var $closest = $ele.closest(".tab-pane");
            var id = $closest.attr("id");
            $('.nav a[href="#' + id + '"]').tab("show");
          },
          submitHandler: function (form) {
            
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
             }
          },
        });
        $frmUpdateOrder.validate({
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
            var $firstEle = $(validator.errorList[0].element);
            var $closest = $firstEle.closest(".tab-pane");
            var id = $closest.attr("id");
            $('.nav a[href="#' + id + '"]').tab("show");
          },
          submitHandler: function (form) {
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
            //console.log(data)
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
        //console.log(len);
      }
      function validateEmail(email){
        
        var id = email;
        id = $.trim(id);
        var filter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  
        if (filter.test(id)) {
          console.log(email);
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
        if($(".onoffswitch-order .onoffswitch-checkbox").prop("checked")) {
          date = $("#d_date").val();
          time = $("#delivery_time").val();
        } else {
          date = $("#p_date").val();
          time = $("#pickup_time").val();
        }
        
        $.get(
              "index.php?controller=pjVouchers&action=pjActionCheckVoucherCode",
              {code,date,time}
            ).done(function (data) {
              //console.log(data);
              if (data == "true") {
                $("#voucher_code").attr("data-wt","valid");
                $("#voucher_code").parent().removeClass("has-error");
                if ($("#voucher_code-error")) {
                   $("#voucher_code-error").css("display","none");
                }
                $("#vouchercode").val(code);
                calPrice(1);
              } else {
                $("#voucher_code").attr("data-wt","invalid");
                $("#voucher_code").parent().addClass("has-error");
                $("#vouchercode").val('');
                calPrice(1);
              }
              
            });
      }
      function validateDeliveryFee(deliveryfee){
        var regex = /^\d*[.]?\d*$/;
        var inputVal = deliveryfee;
        if (regex.test(inputVal)) {
          $("#delivery_fee").attr("data-wt","valid");
          calPrice(1);
        } else {
          $("#delivery_fee").val(0);
          $("#delivery_fee").attr("data-wt","invalid");
          
        }
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
          console.log(mins);
          
          var now_hr = now.getHours();
          var now_mins = now.getMinutes();
          
          if (delivery_time >= 60) {
             delivery_mins = delivery_time % 60;
  
            delivery_hr = (delivery_time - delivery_mins) / 60;
           
            d_mins = delivery_mins + now_mins;
            now_hr = now_hr + delivery_hr;
          } else {
            d_mins = delivery_time + now_mins;
            console.log("d_mins:" + d_mins)
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
        console.log(mins);
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
          //console.log(obj.is_paid);
          // var isPaid = obj.isPaid;
          //console.log(isPaid);
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
          //console.log(obj.is_paid);
          // var isPaid = obj.isPaid;
          //console.log(isPaid);
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
          return (
            '<div class="badge badge-primary btn-xs no-margin"><span class="p-w-xs">' +
            myLabel.delivery +
            "</span></div>"
          );
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
      if ($("#grid").length > 0 && datagrid) {
        var $grid = $("#grid").datagrid({
          buttons: [
            {
              type: "delay",
              //text: "Delay",
              url:
                 "#",
              // .on("click",function() {
              //   console.log("Delay Message")
              // }
            },
            {
              type: "print",
              //text: " Kprint",
              url:
                "index.php?controller=pjAdminPosOrders&action=pjActionPrintOrder&id={:id}",
            },
            
            {
              type: "delete",
              url:
                "index.php?controller=pjAdminOrders&action=pjActionDeleteOrder&id={:id}",
                //"#",
            },
            {
              type: "edit",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionUpdate&id={:id}",
            },
            {
              type: "info",
              url: "#",
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
          dataUrl:
            "index.php?controller=pjAdminPosOrders&action=pjActionGetOrder" +
            pjGrid.queryString,
          dataType: "json",
          fields: ["order_id", "surname",  "post_code",  "order_despatched", "delivered_customer", "deliver_t", "total", "type"],
          paginator: {
            actions: [
              {
                text: myLabel.delete_selected,
                url:
                  "index.php?controller=pjAdminOrders&action=pjActionDeleteOrderBulk",
                render: true,
                confirmation: myLabel.delete_confirmation,
              },
              {
                text: myLabel.exported,
                url:
                  "index.php?controller=pjAdminOrders&action=pjActionExportOrder",
                ajax: false,
              },
            ],
            gotoPage: true,
            paginate: true,
            total: true,
            rowCount: true,
          },
          saveUrl:
            "index.php?controller=pjAdminOrders&action=pjActionSaveOrder&id={:id}",
          select: {
            field: "id",
            name: "record[]",
            cellClass: "cell-width-2",
          },
        });
      }

      if ($("#grid-epos").length > 0 && datagrid) {
        var $grid_epos = $("#grid-epos").datagrid({
          buttons: [
            {
              type: "delete",
              url:
                "index.php?controller=pjAdminOrders&action=pjActionDeleteOrder&id={:id}",
            },
            {
              type: "edit",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionUpdate&id={:id}",
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
            {
              text: myLabel.type,
              type: "text",
              sortable: false,
              editable: false,
              renderer: formatType,
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
          dataUrl:
            "index.php?controller=pjAdminPosOrders&action=pjActionGetPosOrder" +
            pjGrid.queryString,
          dataType: "json",
          fields: ["order_id", "total", "type", "status"],
          paginator: {
            actions: [
              {
                text: myLabel.delete_selected,
                url:
                  "index.php?controller=pjAdminOrders&action=pjActionDeleteOrderBulk",
                render: true,
                confirmation: myLabel.delete_confirmation,
              },
              {
                text: myLabel.exported,
                url:
                  "index.php?controller=pjAdminOrders&action=pjActionExportOrder",
                ajax: false,
              },
            ],
            gotoPage: true,
            paginate: true,
            total: true,
            rowCount: true,
          },
          saveUrl:
            "index.php?controller=pjAdminOrders&action=pjActionSaveOrder&id={:id}",
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
          var $getOrder;
          if ($("#grid-type").val() == "Pos") {
            $getOrder = "index.php?controller=pjAdminPosOrders&action=pjActionGetPosOrder";
            $grid = $("#grid-epos");
          } else {
            $getOrder = "index.php?controller=pjAdminPosOrders&action=pjActionGetOrder";
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
          var $chef_id = $(this).val()
          $.ajax({
            type: "POST",
            async: false,
            url: "index.php?controller=pjAdminOrders&action=pjActionSetSession",
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
          $("#grid").addClass("d-none");
          $("#grid-epos").removeClass("d-none");
          $("#grid-type").val("Pos");
        })
        .on("click", "#btn-listtelephone", function() {
          $("#grid").removeClass("d-none");
          $("#grid-epos").addClass("d-none");
          $("#grid-type").val("Telephone");
          $(".frm-filter").submit();
        })
        .on("click", "#btn-listweb", function() {
          $("#grid").removeClass("d-none");
          $("#grid-epos").addClass("d-none");
          $("#grid-type").val("Web");
          $(".frm-filter").submit();
        })
        .on("click", "#btn-epos", function() {
          $("#rowSwitch").css("display", "none");
          $("#orderTab").css("display", "none");
          // $("#btns-pos").toggleClass("d-none");
          // $("#btns-epos").toggleClass("d-none");
          $("#type").removeAttr("checked");
          $("#btn-pause").removeClass("d-none");
          $("#alertJs").addClass("d-none");
          $("#frmCreateOrder_pos").addClass("d-none");
          $("#frmCreateOrder_epos").removeClass("d-none");
          $("#frmCreateOrder_pos").find("table").removeAttr("id");
          $("#frmCreateOrder_epos").find("table").attr("id", "fdOrderList_1");
          $("#frmCreateOrder_pos").find(".voucher").removeAttr("id");
        })
        .on("click", "#btn-telephone", function() {
          $("#rowSwitch").css("display", "block");
          $("#orderTab").css("display", "block");
          // $("#btns-pos").toggleClass("d-none");
          // $("#btns-epos").toggleClass("d-none");
          $("#type").prop("checked", true);
          $("#btn-pause").addClass("d-none");
          $("#alertJs").addClass("d-none");
          $("#frmCreateOrder_pos").removeClass("d-none");
          $("#frmCreateOrder_epos").addClass("d-none");
          $("#frmCreateOrder_pos").find("table").attr("id", "fdOrderList_1");
          $("#frmCreateOrder_epos").find("table").removeAttr("id");
          $("#frmCreateOrder_pos").find(".voucher").attr("id", "voucher_code");
        })
        .on("click", "#btn-pause", function() {
          
          if($("#fdOrderList_1 .main-body tr").length > 0) {
            swal({
              title: "Pause Order",
              text: "Do you really want to hold the order?",
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "OK",
              closeOnConfirm: false,
              showCancelButton: true,
              cancelButtonText: "Cancel"
            // }).then( function() {
            //   $("#is_paused").val("1");
            //   $("#frmCreateOrder_epos").submit();
            //   swal.close();
           }, function(dismiss) {
              if(dismiss == 'cancel'){
                swal.close();
              } else {
                $("#is_paused").val("1");
                $("#frmCreateOrder_epos").submit();
                swal.close();
              }
           });
            
          } else {
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
         
          $("#frmCreateOrder_pos");
        })
        .on("click", "#btn-payment", function() {
          
          if($("#fdOrderList_1 .main-body tr").length > 0) {
            var $cart_tot = $(this).attr("data-cart");
            $("#paymentModal").modal();
            $("#paymentModal #payment_modal_tot").text($cart_tot);
          } else {
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
         
          $("#frmCreateOrder_pos");
        })
        .on("click", "#paymentBtn", function() {
          var $form;
          if($(this).attr("data-valid") == "true") {
            if($frmUpdatePosOrder.length > 0) {
              $form = $("#frmUpdateOrder_epos")
            } else {
              $form = $("#frmCreateOrder_epos")
            }
            $form.submit();
          } else {
            $("#paymentModal #error-msg").removeClass("d-none");
          }
        })
        .on("input", "#pause_phone", function() {
          $("#clientPhoneNumberBtn").attr("data-phone", $(this).val());
          $("#pause_phone-error").addClass("d-none");
        })
        .on("input", "#paymentModal #payment_modal_pay", function() {
          var tot = $("#paymentModal #payment_modal_tot").text();
          var tot_int = parseFloat(tot);
          var balance;
          var balance_format;
          var currency = $("#paymentModal #payment_modal_curr").text();
          if( $(this).val() == '' ||  $(this).val() < tot_int ) {
            $("#paymentModal #error-msg").removeClass("d-none");
            balance_format = "0.00" + currency;
            $("#paymentModal #paymentBtn").attr("data-valid", "false");
          } else {
            $("#paymentModal #error-msg").addClass("d-none");
            balance = parseFloat($(this).val()) - tot_int;
            balance_format = parseFloat(balance) + ".00 " + currency;
            $("#paymentModal #paymentBtn").attr("data-valid", "true");
          }
          
          $("#paymentModal #payment_modal_bal").text(balance_format);
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
          // console.log('Index:',index);
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
        .on("click", ".pj-add-extra", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          var $this = $(this);
          var index = $this.attr("data-index");
          var $productEle = $("#fdProduct_" + index);
          var product_id = $productEle.val();
          var $extra_table = $this.parent().siblings(".pj-extra-table");
          if (product_id != "") {
            $.get("index.php?controller=pjAdminOrders&action=pjActionGetExtras", {
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
          //$this.valid();
          //console.log($this);
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
            //console.log($prepTime);
            $totPrepTime = $totPrepTime + parseInt($prepTime);
  
            //$totPrepTime = $totPrepTime + parseInt($kordersPrepTime);
            
            $("#fdPrepTime_" + index).html($prepTime);
            //console.log($fdSelectedProduct.category_id);
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
          //console.log($("#fdOrderList tr:last").attr("data-index"));
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
          console.log(index);
          
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
          console.log("hi");
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
          console.log("changing");
          getClientInfo($(this));
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
              if ($("#c_name").length > 0) {
                $("#c_name").removeClass("required");
                $("#c_name").val(data.name).valid();
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
          var msgArea = $("#message");
          if (val == 4) {
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
          console.log("hey onoff");
        })
        .on("change", ".onoffswitch-order .onoffswitch-checkbox", function (e) {
          if ($(this).prop("checked")) {
            $(".order-delivery").show();
            $("#address_title").show();
            $(".order-delivery").find(".fdRequired").addClass("required");
            $(".order-pickup").hide();
            $(".order-pickup").find(".fdRequired").removeClass("required");
            $("#delivery_fee_frmgrp").css("display","block");
            $("#jsOverridePc").css("display","block");
            var min_amt = $("#min_amt").val();
            var total_amt = $("#total_format").text();
            total_amt = total_amt.split(" ");
            var $price;
            var cur_place = $("#currency_place").val();
            cur_place == 'back' ? $price = parseFloat(total_amt[0]) : $price = parseFloat(total_amt[1]);
            
            if ($price < min_amt) {
              $("#submitJs").attr('disabled','true');
              $("#alertJs").css('display','block');
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
          console.log('Called me on close');
         // $('#orderContainer').addClass('animated fadeInRight');
      });
      $("#inputPostCode").change(function (e) {
        
  
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
      $("#phone_no").on("input", function(){
        getClientInfo($(this));
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
       //console.log($page.text());
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
          //console.log("clicked slide without fade");
          $("#page_navigation").html('');
          $(this).css("display", "none");
        })
        $(".next-tab").on("click", function() {
          var nextTab = $("#orderTab li.active").next("li");
          var tabContent = nextTab.children("a").attr("href");
          $("#orderTab li.active").removeClass("active");
          $("#orderTabContent .tab-pane.active").removeClass("active in");
          nextTab.addClass("active");
          $(tabContent).addClass("active in");

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
    })
    $(document).on('click', '#sizeBtnSubmit' ,function (e) {
      $(this).prop("disabled", true);
      $("#sizeModal").modal('hide');
      var $productId = $(this).siblings("#sizeValue").attr('data-productId');
      var $sizeId = $(this).siblings("#sizeValue").val();
      var oldProduct = false;
      var oldProductt = false;
      var $totalPrepTime = 0;
      $kordersPrepTime = parseInt($("#totKorderPrepTimeInput").val());
      $totalPrepTime = $kordersPrepTime;
      if ($("#fdOrderList_1").find("tbody.main-body > tr").length != 0) {
        //console.log("comes to iterate");
        var rows = $("#fdOrderList_1").find("tbody.main-body > tr");
        //console.log(rows);
        
        rows.each(function() {
          var $index = $(this).data("index");
          var proInListId = $(this).children("td:nth-of-type(1)").children("input").val();
          var prodSizeInListId = $(this).children("#fdPriceTD_"+$index).children(".fdSize").val();
          $totalPrepTime = $totalPrepTime + parseInt($(this).data("preptime"));
          //console.log($totalPrepTime);
          //var 
          console.log(prodSizeInListId);
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
            
            //console.log($totalPrepTime);
          
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
              
            
            // setTimeout(function() {
            //   var dblclick = parseInt($(that).data('double'), 10);
            //   if (dblclick > 0) {
            //       $(that).data('double', dblclick-1);
            //   } else {
                var oldProduct = false;
                var oldProductt = false;
                var $totalPrepTime = 0;
                $kordersPrepTime = parseInt($("#totKorderPrepTimeInput").val());
                $totalPrepTime = $kordersPrepTime;
                if ($("#fdOrderList_1").find("tbody.main-body > tr").length != 0) {
                  //console.log("comes to iterate");
                  var rows = $("#fdOrderList_1").find("tbody.main-body > tr");
                  //console.log(rows);
                  
                  rows.each(function() {
                    var $index = $(this).data("index");
                    var proInListId = $(this).children("td:nth-of-type(1)").children("input").val();
                    $totalPrepTime = $totalPrepTime + parseInt($(this).data("preptime"));
                    //console.log($totalPrepTime);
                    //var 
                    if (proInListId == parseInt($this.data('id'))) {
                      var prevVal = parseInt($("#fdProductQty_"+$index).val());
                      var newVal = prevVal + 1;
                      $("#fdProductQty_"+$index).val(newVal);
                      oldProduct = true;
                      calPrice(1);
                    }
                  })
                }
                // if ($products_in_cart.length > 0) {
                //   for(var i=0; i<$products_in_cart.length; i++) {
                //     if($products_in_cart[i] == $this.data('id')) {
                //       oldProductt = true;
                //     }
                //   }
                // }
                // console.log(oldProductt);
                // if(oldProductt == false) {
                //   $products_in_cart.push($this.data('id'));
                //   //console.log($products_in_cart);
                // }
                if (oldProduct == false) {
                  $("#products-sec").parent().addClass("ibox-content");
                  $("#products-sec").parent().addClass("sk-loading");
                  $.get("index.php?controller=pjAdminPosOrders&action=pjActionGetProductPrices", {
                      product_id: $this.data('id'), size_id: ''
                  }).done(function (data) { 
                      $("tbody.main-body").append(data);
                      //$("tbody.main-body > tr:last").data("preptime");
                      if ($("tbody.main-body > tr").last().data("preptime") != '') {
                        $totalPrepTime = $totalPrepTime + parseInt($("tbody.main-body > tr").last().data("preptime"));
                      }
                      
                      //console.log($totalPrepTime);
                    
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
        .on("click", '#fdOrderList_1 .main-body .fdLine .tdProductName',function (e) { 
          // $(this).data('double', 2);
          var $this = $(this);
          var $id = $this.children("input").val();
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
          var special_instructions = $(this).siblings(".special-instruction").val();
          var custom_special_instructions = $(this).siblings(".custom-special-instruction").val();
          var special_instructions_imgs = $("#fdSpecialInstructionImgs_"+index_id).children("input").attr("data-imgs");
          $.get(
            "index.php?controller=pjAdminPosOrders&action=pjActionGetSpecialInstructionTypes&selected_ins="+special_instructions, 
          ).done(function (data) {
            $("#specialInstructionsModal").modal();
            $("#specialInstructionsModal .modal-body").html(data);
            $("#specialInstructionsModal #custom_special-instruction").val(custom_special_instructions)
            $("#selectedInsValue").attr("data-index", index_id);
            $("#selectedInsValue").val(special_instructions);
            $("#selectedInsValue").attr("data-images", special_instructions_imgs);
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
          var id = $(this).attr("data-id");
          var img = $(this).attr("src");
          var selected_ins_arr = $("#selectedInsValue").val();
          var selected_ins_imgs = $("#selectedInsValue").attr("data-images");
          var sibling_selected = $(this).siblings(".spcl_ins_selected");
      
          if(sibling_selected.length > 0) {
            sibling_selected.removeClass("spcl_ins_selected");
            var sibling_selected_id = sibling_selected.attr("data-id");
            var sibling_selected_img = sibling_selected.attr("src");
            selected_ins_arr = selected_ins_arr.replace(sibling_selected_id+',','');
            selected_ins_imgs = selected_ins_imgs.replace(sibling_selected_img+',','');
          }
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
        .on("click", '#specialInstructionsModal .specialInstructionsBtn',function (e) { 
          var index = $("#selectedInsValue").attr("data-index");
          var selected_ins_arr = $("#selectedInsValue").val();
          var selected_ins_imgs = $("#selectedInsValue").attr("data-images");
          var selected_imgs = selected_ins_imgs.split(",");
          var custom_si = $("#specialInstructionsModal #custom_special-instruction").val();
          if(custom_si != '') {
            $("tr[data-index='"+index+"'] #fdCustomSpecialInstruction_"+index).val(custom_si);
          }
          var $elem_images = '';
          $.each(selected_imgs, function($img) {
            if(selected_imgs[$img] != '' && selected_imgs[$img] != 'undefined') {
              var $Img = "<img src='"+selected_imgs[$img]+"' />";
              $elem_images = $elem_images + $Img;
            }
          });
          $elem_images = $elem_images + "<input type='hidden' data-imgs='"+ selected_ins_imgs +"' />";
          
          $("tr[data-index='"+index+"'] #fdSpecialInstruction_"+index).val(selected_ins_arr);
          $("tr[data-index='"+index+"'] #fdSpecialInstructionImgs_"+index).html($elem_images);
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
  
            if ($("#fdOrderList_1").find("tbody.main-body > tr").length == 0) {
                //$("#fdOrderList").hide();
            } else {
                calPrice(1);
            }
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
        
          function getTotal() {
              
            var $frm = null;
            if ($frmCreateOrder.length > 0) {
              $frm = $frmCreateOrder;
            }
            if ($frmUpdateOrder.length > 0) {
              $frm = $frmUpdateOrder;
            }
            if ($frmUpdatePosOrder.length > 0) {
              $frm = $frmUpdatePosOrder;
            }
           
            if ($("#fdOrderList_1").find("tbody.main-body > tr").length > 0) {
              $(".ibox-content").addClass("sk-loading");
              $.post(
                "index.php?controller=pjAdminPosOrders&action=pjActionGetTotal",
                $frm.serialize()
              ).done(function (data) {
                if (data.price != "NULL") {
                  $("#price").val(data.price).valid();
                  $("#price_packing").val(data.price_packing);
                  $("#price_delivery").val(data.price_delivery);
                  $("#discount").val(data.discount);
                  $("#subtotal").val(data.subtotal);
                  $("#tax").val(data.tax);
                  $("#total").val(data.total);
      
                  $("#price_format").html(data.price_format);
                  $("#packing_format").html(data.packing_format);
                  $("#delivery_format").html(data.delivery_format);
                  $("#discount_format").html(data.discount_format);
                  $("#subtotal_format").html(data.subtotal_format);
                  $("#tax_format").html(data.tax_format);
                  $("#total_format").html(data.total_format);
                  
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
                  $("#btn-payment").attr("data-cart", data.total+".00");

                  var min_amt = $("#min_amt").val();
                  var type = $(".onoffswitch-order .onoffswitch-checkbox").prop("checked");
                  
                  if (data.total < min_amt && type == true) {
                    $("#submitJs").attr('disabled','true');
                    $("#alertJs").css('display','block');
                  } else {
                    
                    $("#submitJs").removeAttr('disabled');
                    $("#alertJs").css('display','none');
                  }
                }
                $(".ibox-content").removeClass("sk-loading");
              });
            }
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
                  //var attr = $("option:selected", price_element).attr("data-price");
                  var attr = $(price_element).attr("data-price");
                  console.log(attr);
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
                  console.log(o);
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
                //console.log(result[0].postcode_outward);
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
                        url: "index.php?controller=pjAdminOrders&action=pjActionCheckPostcode",
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
                  // console.log($pc_valid);
                  if ($pc_valid) {
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
                }
                if (result.length == 1) {
                  $("#selAddress").change(function(){
                  var index = $(this).val();
                  index = index - 1;
                  //console.log('Index', index);
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
            }
      
          }
          function getClientInfo($this) {
            $c_phone = $this.val();
              $cinfo = $.ajax({
                type: "POST",
                async: false,
                url: "index.php?controller=pjAdminOrders&action=pjActionCheckClientPhoneNumber",
                data: { 
                  value: function() {
                    return $c_phone;
                  }
                },
            });
            //console.log($cinfo);
            if($cinfo.responseText == 'new user') {
      
              $("#c_title").val("");
              $("#c_email").val("");
              $("#c_email").attr('data-wt','invalid');
              $("#c_surname").val("");
              $("#inputPostCode").val("");
              $("#d_address_1").val("");
              $("#d_address_2").val("");
              $("#d_city").val("");
              $("#c_name").val("");
              $("#mobile_delivery_info_yes").prop("checked",true);
              $("#mobile_offer_yes").prop("checked",false);
              $("#email_receipt_yes").prop("checked",true);
              $("#email_offer_yes").prop("checked",false);
              $('#jsEmailOffer').css("display","none");
            } else {
              var c_arr = $cinfo.responseJSON[0];
          
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
              navigation_html += "<a class='nextbutton previous' >«</a>";
            } else {
              navigation_html += "<a class='nextbutton previousDisabled' >«</a>";
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
                //console.log(current_link);
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
          function timeNow() {
            var currentUtcTime = new Date($.now());
            var d = new Date(currentUtcTime.toLocaleString('en-US', { timeZone: 'Europe/London' }));
            var yr = d.getFullYear();
            var month = d.getMonth() + 1;
            var date = d.getDate();
            var hr = d.getHours();
            var min = d.getMinutes();
            var sec = d.getSeconds();
            //console.log(date<10);
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
})(jQuery_1_8_2);