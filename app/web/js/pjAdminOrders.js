var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
  $(function () {
    "use strict";
    var validator,
      
      //MEGAMIND

      $fdSelectedProduct,
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


      // ! MEGAMIND

      $frmCreateOrder = $("#frmCreateOrder"),
      $frmUpdateOrder = $("#frmUpdateOrder"),
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
    // $("#d_date").datepicker();
    if ($tabs.length > 0 && tabs) {
      $tabs.tabs(tOpt);
    }
    if (chosen) {
      $("#client_id").chosen();
    }
    // if ($("#dateTimePickerOptions").length) {
    //   var currentDate = new Date();
    //   var $optionsEle = $("#dateTimePickerOptions");

    //   moment.updateLocale("en", {
    //     week: { dow: parseInt($optionsEle.data("wstart"), 10) },
    //     months: $optionsEle.data("months").split("_"),
    //     weekdaysMin: $optionsEle.data("days").split("_"),
    //   });
    //   datetimeOptions = {
    //     format: $optionsEle.data("format"),
    //     locale: moment.locale("en"),
    //     allowInputToggle: true,
    //     ignoreReadonly: true,
    //   };
    //   datetimeOptions.minDate = new Date(
    //     currentDate.getFullYear(),
    //     currentDate.getMonth(),
    //     currentDate.getDate()
    //   );
    //   $("#d_dt").datetimepicker(datetimeOptions);
    //   $("#p_dt").datetimepicker(datetimeOptions);
    //   $("#d_dt").on("dp.hide", function (e) {
    //     validateDeliveryTime();
    //     calPrice(1);
    //   });
    //   $("#p_dt").on("dp.hide", function (e) {
    //     validatePickupTime();
    //     calPrice(1);
    //   });
    // }

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
          $("#d_date").on("changeDate", function (e) {
            //console.log($(this).val())
            validateDeliveryTime();
            calPrice(1);
          });
          $("#p_date").on("changeDate", function (e) {
            validatePickupTime();
            calPrice(1);
          });
    }
    // if ($('.pj-timepicker').length) {
    //       $( ".pj-timepicker" ).each(function( index ) {
    //         var $this = $(this);
    //         $this.clockpicker({
    //               // twelvehour: myLabel.showperiod,
    //               autoclose: true,
    //               afterDone: function() {
    //               //dateChange();
    //               $frmCreateOrder.find("input[name='type']").is(":checked")
    //               ? validateDeliveryTime()
    //               : validatePickupTime();
    //                 calPrice(1);
    //                 }
    //             });
    //     });
    // }
    $('#d_time').on("focusout",function() {
      var delivery_time = parseInt($(this).val());
      
      var delivery_hr;
      var delivery_mins;
      var d_mins;
      //var d_hr;
      var now = new Date($.now());
      
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
      
      $("#delivery_time").val(now_hr + ":" + d_mins);
      $("#aproxDt").text(now_hr + ":" + d_mins);
      validateDeliveryTime();
      calPrice(1);
    })
    $('#p_time').on("focusout",function() {
      var pickup_time = parseInt($(this).val());
      var pickup_hr;
      var pickup_mins;
      var p_mins;
      //var p_hr;
      var now = new Date($.now());
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
      $("#pickup_time").val(now_hr + ":" + p_mins);
      $("#aproxPt").text(now_hr + ":" + p_mins);
      
      validatePickupTime();
      calPrice(1);
    })
    $('#phone_no').on("focusout", function() {
      validatePhoneNumber($(this).val());
    })
    $('#c_email').on("focusout", function() {
      validateEmail($(this).val());
    })
    if ($frmCreateOrder.length > 0 || $frmUpdateOrder.length > 0) {
      $.validator.addMethod("pickupTime", function (value, element) {
        //console.log(element);
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
      $.validator.addMethod("email", function (value, element) {
        if ($(element).attr("data-wt") == "valid") {
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
          },
          mobile_offer: {
            required: true,
          },
          email_receipt: {
            required: true,
          },
          email_offer: {
            required: true,
          },
          phone_no: {
            phoneNumber: true,
          },
          sms_email: {
            email: true,
          },
          //phone_no:{matches:"[0-9]+",minlength:11, maxlength:11},
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
          phone_no: {
            phoneNumber: myLabel.phoneNumber_err,
          },
          sms_email: {
            email: myLabel.email_err,
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
          //$("#mobile_delivery_info_err") = $("input[name=mobile_delivery_info]").is(":checked") ? "none" : "block";
          
          var firstRowIndex = $('#fdOrderList').find("tbody.main-body > tr:first-child").attr("data-index");
          var lastRow = $("#fdOrderList tr:last");
          var lastRowIndex = $("#fdOrderList tr:last").attr("data-index");
          var $product_last = $("#fdProduct_"+lastRowIndex);
          var $price_last = $("#fdPrice_"+lastRowIndex);
          
          if ($product_last.val() == "" && $price_last.val() == "") {
            if (lastRowIndex == firstRowIndex) {
              $product_last.parent().parent().addClass("has-error");
              $price_last.parent().addClass('has-error');
              valid = false;
              $ele = $product_last;
              $err_ele.push($product_last);
            }
            else{

              lastRow.remove();
              $product_last.parent().parent().removeClass("has-error");
              $price_last.parent().removeClass('has-error');
              $('#fdOrderList').find("tbody.main-body > tr.fdLine").each(function() {
                  var index = $(this).attr('data-index'),
                    $product = $('#fdProduct_' + index),
                    $price = $('#fdPrice_' + index);
                  if($price.val() == '')
                  {
                    $price.parent().addClass('has-error');
                    valid = false;
                    $ele = $product;
                    $err_ele.push($price);
                  }else{
                    $price.parent().removeClass('has-error');
                  }
                });
              //valid = true;
            }
          } else {
            $('#fdOrderList').find("tbody.main-body > tr.fdLine").each(function() {
                  var index = $(this).attr('data-index'),
                    $product = $('#fdProduct_' + index),
                    $price = $('#fdPrice_' + index);
                  if($price.val() == '')
                  {
                    $price.parent().addClass('has-error');
                    valid = false;
                    $ele = $product;
                    $err_ele.push($price);
                  }else{
                    $price.parent().removeClass('has-error');
                  }
                });

          }
          
          // !MEGAMIND

          if (valid == true) {
            $(window).off('beforeunload');
            form.submit();
           } else {
              $('html,body').animate({
                  scrollTop: $err_ele[0].offset().top
              }, 1000);
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
          },
          mobile_offer: {
            required: true,
          },
          email_receipt: {
            required: true,
          },
          email_offer: {
            required: true,
          },
          phone_no: {
            phoneNumber: true,
          },
          sms_email: {
            email: true,
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
          phone_no: {
            phoneNumber: myLabel.phoneNumber_err,
          },
          sms_email: {
            email: myLabel.email_err,
          },
        },
         errorPlacement: function(error, element) {
            if (element.attr("type") == "radio") {
                error.insertAfter(element.parent());
                error.css('color','#a94442');
                error.siblings("label").css('color','#ed5565')
                //element.css('border','2px solid red');
            } else if (element.siblings("span").hasClass("input-group-addon")) {
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
          // MEGAMIND
           
         
         
          var firstRowIndex = $('#fdOrderList').find("tbody.main-body > tr:first").attr("data-index");
          var lastRow = $("#fdOrderList tr:last");
          var lastRowIndex = $("#fdOrderList tr:last").attr("data-index");
          var $product_last = $("#fdProduct_"+lastRowIndex);
          var $price_last = $("#fdPrice_"+lastRowIndex);
          
          if ($product_last.val() == "" && $price_last.val() == "") {
            if (lastRowIndex == firstRowIndex) {
              
              $product_last.parent().parent().addClass("has-error");
              $price_last.parent().addClass('has-error');
              valid = false;
              $ele = $product_last;
              $err_ele.push($product_last);
            }
            else{
              
              lastRow.remove();
              $product_last.parent().parent().removeClass("has-error");
              $price_last.parent().removeClass('has-error');
              $('#fdOrderList').find("tbody.main-body > tr.fdLine").each(function() {
                  var index = $(this).attr('data-index'),
                    $product = $('#fdProduct_' + index),
                    $price = $('#fdPrice_' + index);
                  if($price.val() == '')
                  {
                    $price.parent().addClass('has-error').focus();
                    valid = false;
                    $ele = $product;
                    $err_ele.push($price);
                  }else{
                    $price.parent().removeClass('has-error');
                  }
                });
            }
          } else {
            
            $('#fdOrderList').find("tbody.main-body > tr.fdLine").each(function() {
                var index = $(this).attr('data-index'),
                  $product = $('#fdProduct_' + index),
                  $price = $('#fdPrice_' + index);
                if($price.val() == '')
                {
                  $price.parent().addClass('has-error').focus();
                  valid = false;
                  $ele = $product;
                  $err_ele.push($price);
                }else{
                  $price.parent().removeClass('has-error');
                }
              });
          }
          
          // !MEGAMIND
          if (valid == true) {
            $(window).off('beforeunload');
            form.submit();
          } else {
              $('html,body').animate({
                  scrollTop: $err_ele[0].offset().top
              }, 1000);
           }
        },
      });

      bindTouchSpin();
      if ($frmUpdateOrder.length > 0) {
        if ($("#fdOrderList").find("tbody.main-body > tr").length > 0) {
          calPrice(0);
          $("#fdOrderList").show();
        }
      }
    }
    function bindTouchSpin() {
      if ($("#fdOrderList").length > 0) {
        $("#fdOrderList").find(".pj-field-count").TouchSpin({
          verticalbuttons: true,
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
        url: "index.php?controller=pjAdminOrders&action=pjActionCheckDelivery",
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
    function validatePhoneNumber(data){
      
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
        $("#c_email").attr("data-wt","valid");
      } else {
         $("#c_email").attr("data-wt","invalid");
      }
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
              "index.php?controller=pjAdminOrders&action=pjActionPrintOrder&id={:id}",
          },
          
          {
            type: "delete",
            url:
              "index.php?controller=pjAdminOrders&action=pjActionDeleteOrder&id={:id}",
              //"#",
          },
          {
            type: "edit",
            url: "index.php?controller=pjAdminOrders&action=pjActionUpdate&id={:id}",
          },
        ],
        columns: [
          { text: myLabel.id, type: "text", sortable: false},
          { text: myLabel.phone, type: "text", sortable: false },
          { text: myLabel.name, type: "text", sortable: false },
          { text: myLabel.address, type: "text", sortable: false },
          { text: myLabel.postcode, type: "text", sortable: false },
          { text: myLabel.c_type, type: "text", sortable: false },
          // { text: myLabel.call_start, type: "text", sortable: false },
          // { text: myLabel.call_end, type: "text", sortable: false },
          { text: myLabel.sms_email, type: "text", sortable: false },
          // { text: myLabel.order_despatched, type: "toggle", sortable: false },
          {text: myLabel.order_despatched, type: "toggle", sortable: false, editable: true, 
                    editableRenderer: function () {
                      return 0;
                    },
                    saveUrl: "index.php?controller=pjAdminOrders&action=pjActionSaveOrderDespatched&id={:id}",
                    positiveLabel: myLabel.yes, positiveValue: "1", negativeLabel: myLabel.no, negativeValue: "0", 
                    cellClass: "text-center",
                    //negativeClass: "bg-danger"
                  },
          
          { text: myLabel.sms_sent_time, type: "text", sortable: false },
          { text: myLabel.expected_delivery, type: "text", sortable: false },
          // { text: myLabel.delivered_customer, type: "text", sortable: false },
          {text: myLabel.delivered_customer, type: "toggle", sortable: false, editable: false, 
                    editableRenderer: function () {
                      return 0;
                    },
                    saveUrl: "index.php?controller=pjAdminOrders&action=pjActionSaveDeliveredCustomer&id={:id}",
                    positiveLabel: myLabel.yes, positiveValue: "1", negativeLabel: myLabel.no, negativeValue: "0", 
                    cellClass: "text-center"},
          { text: myLabel.delivered_time, type: "text", sortable: false },
          {text: myLabel.is_paid, type: "toggle", sortable: false, editable: true, 
                    editableRenderer: function () {
                      return 0;
                    },
                    saveUrl: "index.php?controller=pjAdminOrders&action=pjActionSaveOrderPaid&id={:id}",
                    positiveLabel: myLabel.yes, positiveValue: "1", negativeLabel: myLabel.no, negativeValue: "0", 
                    cellClass: "text-center",
                    //negativeClass: "bg-danger"
                  },
          

          {
            text: myLabel.date_time,
            type: "text",
            sortable: false,
            editable: false,
          },
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
            sortable: true,
            editable: true,
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
          "index.php?controller=pjAdminOrders&action=pjActionGetOrder" +
          pjGrid.queryString,
        dataType: "json",
        fields: ["order_id","phone_no", "surname", "address", "post_code", "c_type", "sms_email", "order_despatched", "sms_sent_time", "expected_delivery", "delivered_customer", "delivered_time","is_paid","datetime", "total", "type", "status"],
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

          cache = $grid.datagrid("option", "cache");

        $.extend(cache, {
          q: $this.find("input[name='q']").val(),
          type: $this.find("select[name='type']").val(),
        });
        $grid.datagrid("option", "cache", cache);
        $grid.datagrid("load", "index.php?controller=pjAdminOrders&action=pjActionGetOrder", "created", "DESC", content.page, content.rowCount);
        // $grid.datagrid(
        //   "load",
        //   "index.php?controller=pjAdminOrders&action=pjActionGetOrder",
        //   "created",
        //   "DESC",
        //   content.page,
        //   content.rowCount
        // );
        // $grid.datagrid(
        //   "load",
        //   "index.php?controller=pjAdminOrders&action=pjActionGetOrder",
        //   "order_despatched",
        //   "DESC",
        //   content.page,
        //   content.rowCount
        // );
        // $grid.datagrid(
        //   "load",
        //   "index.php?controller=pjAdminOrders&action=pjActionGetOrder",
        //   "delivered_customer",
        //   "DESC",
        //   content.page,
        //   content.rowCount
        // );
        // $grid.datagrid(
        //   "load",
        //   "index.php?controller=pjAdminOrders&action=pjActionGetOrder",
        //   "delivered_customer",
        //   "DESC",
        //   content.page,
        //   content.rowCount
        // )
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
      .on("change", "#voucher_code", function (e) {
        calPrice(1);
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
          $kordersPrepTime = $("#totKorderPrepTimeInput").val();
          
          if ($fdSelectedProduct.preparation_time == "") {
            $prepTime = "0";
          } else {
            $prepTime = $fdSelectedProduct.preparation_time;
          }//? $prepTime = 0 : $prepTime = $fdSelectedProduct.preparation_time;
          //console.log($prepTime);
          $totPrepTime = $totPrepTime + parseInt($prepTime) + parseInt($kordersPrepTime);

          //$totPrepTime = $totPrepTime + parseInt($kordersPrepTime);
          
          $("#fdPrepTime_" + index).html($prepTime);
          //console.log($fdSelectedProduct.category_id);
          $("#fdCategory_" + index).html(categoryList[$fdSelectedProduct.category_id]);
          $("#fdDescription").html($fdSelectedProduct.description);
          $("#total_prep-time_format").html($totPrepTime);
          $("#prep_time").val($totPrepTime);
          $totPrepTime = $totPrepTime - parseInt($kordersPrepTime);
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
        $kordersPrepTime = $("#totKorderPrepTimeInput").val();
        var $deletedPrepTime = parseInt($(this).parents("tr").children("td:nth-child(6)").children().text());
        $totPrepTime = $totPrepTime - $deletedPrepTime;
        $("#total_prep-time_format").text($totPrepTime + parseInt($kordersPrepTime));
        $("#prep_time").val($totPrepTime + parseInt($kordersPrepTime));

      })
      .on("click", ".dropdown-toggle", function (e) { 

        var index = $(this).siblings("select").attr("data-index"); 
        $fdSelectedProduct = $("#prdInfo_" + index).val();
        //console.log(index);
        
        if ($fdSelectedProduct == undefined) {
          return;
        } else {
          $fdSelectedProduct = JSON.parse($fdSelectedProduct);
          $("#fdDescription").html($fdSelectedProduct.description);
        }
        
      })
      // .on("change", "#d_date", function (e){
      //   d_dt = $("#d_dt").val();
      //   ddate = $this.val();
      //   d_dt = d_dt + ddate;
      //   $("#d_dt").val() = d_dt;
      // })
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
        $c_phone = $(this).val();
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
        $("input:radio").prop("checked", false);
      
      } else {
        var c_arr = $cinfo.responseJSON[0];
        $("#c_title").val(c_arr.c_title);
        $("#c_email").val(c_arr.email);
        c_arr.sms_email == '' ? $("#c_email").attr('data-wt','invalid') : $("#c_email").attr('data-wt','valid');
        $("#c_surname").val(c_arr.u_surname);
        $("#inputPostCode").val(c_arr.c_postcode);
        $("#d_address_1").val(c_arr.c_address_1);
        $("#d_address_2").val(c_arr.c_address_2);
        $("#d_city").val(c_arr.c_city);
        $("#c_name").val(c_arr.name);
        c_arr.email_delivery_info == 1 ? $("#email_delivery_info_yes").prop("checked",true) : $("#email_delivery_info_no").prop("checked",true);
        c_arr.email_offer == 1 ? $("#email_offer_yes").prop("checked",true) : $("#email_offer_no").prop("checked",true);
        c_arr.email_receipt == 1 ? $("#email_receipt_yes").prop("checked",true) : $("#email_receipt_no").prop("checked",true);
        c_arr.mobile_delivery_info == 1 ? $("#mobile_delivery_info_yes").prop("checked",true) : $("#mobile_delivery_info_no").prop("checked",true); 
        c_arr.mobile_offer == 1 ? $("#mobile_offer_yes").prop("checked",true) : $("#mobile_offer_no").prop("checked",true);
        }  
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
      .on("change", ".onoffswitch-order .onoffswitch-checkbox", function (e) {
        if ($(this).prop("checked")) {
          $(".order-delivery").show();
          $(".order-delivery").find(".fdRequired").addClass("required");
          $(".order-pickup").hide();
          $(".order-pickup").find(".fdRequired").removeClass("required");
        } else {
          $(".order-delivery").hide();
          $(".order-delivery").find(".fdRequired").removeClass("required");
          $(".order-pickup").show();
          $(".order-pickup").find(".fdRequired").addClass("required");
          $("#inputPostCode").val("");
          $("#d_address_1").val("");
          $("#d_address_2").val("");
          $("#d_city").val("");
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
        if(value == 'delivered'){value = 'success'}
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
        }
        //})

      })
      .on("click", ".clickCategory", function (e) {
          var categoryID = $(this).data("id");
          var catName = $(this).text();
          $.post(
            "index.php?controller=pjAdminOrders&action=pjActionGetProductsForCategory",
            {category_id: categoryID}
          ).done(function (data) {
            //console.log(data);
            $('#catModalTitle').text(catName);
            $('#catModalBody').html(data);
            $("#catModal").modal();
            //$(".modal-dialog").attr('class','modal-lg')
          });
          return false;
      })
     
      ;
    $cols = $("table");//.on("click", function(){
    
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
    $("#inputPostCode").keydown(function (e) {
      if (e.keyCode == 13) {
      e.preventDefault();
      //return false;
      }
      var $this = $(this);
      getAddresses($this);
    });
     $(document).ready(function() {
     var $page = $('h2');
     if ($page.text() == "Update Order") {

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
        if($product != "" && $price != ""){
          var index = Math.ceil(Math.random() * 999999),
          $clone = $("#boxProductClone").find("tbody").html();
          $clone = $clone.replace(/\{INDEX\}/g, "new_" + index);
          $("#fdOrderList").find("tbody.main-body").append($clone);
          bindTouchSpin();
          $("#fdOrderList").show();
          // console.log('Index:',index);
          $('#fdProduct_new_'+index).addClass('selectpicker').selectpicker('refresh');
        }


      // alert("Update page");
      
        
     }
     
    });
     $(window).on('beforeunload', function(){
        if ($("h2").text()=="Add new order" || $("h2").text()=="Update Order") {
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
    function getTotal() {
      var $frm = null;
      if ($frmCreateOrder.length > 0) {
        $frm = $frmCreateOrder;
      }
      if ($frmUpdateOrder.length > 0) {
        $frm = $frmUpdateOrder;
      }
      if ($("#fdOrderList").find("tbody.main-body > tr").length > 0) {
        $(".ibox-content").addClass("sk-loading");
        $.post(
          "index.php?controller=pjAdminOrders&action=pjActionGetTotal",
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
          }
          $(".ibox-content").removeClass("sk-loading");
        });
      }
    }
    function calPrice(get_total) {
      var prices = {};
      $("#fdOrderList")
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
      $.post("index.php?controller=pjAdminOrders&action=pjActionFormatPrice", {
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
      //console.log($this);
      Client = IdealPostcodes.Client;
        client = new Client({ api_key: "iddqd" });
        postcode = $this.val();
        if (postcode) {
          var addressList = $(
            '<select id="selAddress" name="selectAddress" class="form-control"/>'
          );
          $("<option />", { value: 0, text: "--Choose--"}).appendTo(addressList);
          client.lookupPostcode({ postcode }).then(function (result) {
            postalResult = result;
            if (result.length > 0) {
              var i = 1;
              if ($("#post_code").hasClass("has-error")) {
                $("#post_code").removeClass("has-error");
                $("#postCodeErr").css("display","none");
              }
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
             }  
            if(result.length == 0) {
              $("#post_code").addClass("has-error");
              $("#postCodeErr").css("display","block");
            }
            if (result.length == 1) {
              $("#selAddress").click(function(){
              var index = $(this).val();
              $("#d_address_1").val(result[index-1].line_1);
              $("#d_address_2").val(result[index-1].line_2);
              $("#d_city").val(result[index-1].post_town);
            })
           }
          });
        }

    }
  });
})(jQuery_1_8_2);
