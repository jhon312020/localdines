var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
    var $frmProduct = $("#frmProduct");
		var $frmReport = $("#frmReport"),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined);
		var urlParams = new URLSearchParams(window.location.search);
		var updated_category = urlParams.get('category'); 
		var err_code = urlParams.get('err');
		var page = urlParams.get('page');
		var $frmFromAndToDate = $("#frmFromAndToDate");

		function generateReport() {
			var $printUrl = $('#pjFdPrintReport');
			var href = $printUrl.attr('data-href');
			href = href + "&location_id=" + $("#location_id").val();
			href = href + "&date_from=" + $("#date_from").val();
			href = href + "&date_to=" + $("#date_to").val();
			$printUrl.attr('href', href);
			$('.ibox-content').addClass('sk-loading');
			//$.post("index.php?controller=pjAdminReports&action=pjActionGenerate", $frmReport.serialize()).done(function (data) {
			$.post("index.php?controller=pjAdminReports&action=pjActionPOSGenerate", $frmReport.serialize()).done(function (data) {
				if (!(data.code != undefined && data.status == 'ERR')) 
				{
					$('#pjFdReportContent').html(data);
				}
				$('.ibox-content').removeClass('sk-loading');
			});
		}

    function filterProduct() {
      viewProductList(loadData);
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
    };
		
		if ($frmReport.length > 0) {
			generateReport.call(null);
      $('#date_from').datepicker({
          autoclose: true
      }).on('changeDate', function (e) {
      	generateReport.call(null);
      });
      $('#date_to').datepicker({
          autoclose: true
      }).on('changeDate', function (e) {
      	generateReport.call(null);
	    });
		}
    if ($frmProduct.length > 0) {
      $('#date_from').datepicker({
          autoclose: true
      }).on('changeDate', function (e) {
        filterProduct.call(null);
      });
      $('#date_to').datepicker({
          autoclose: true
      }).on('changeDate', function (e) {
        filterProduct.call(null);
      });
    }

    if ($("#id").length > 0) {
      $.ajax({
        type: "POST",
        async: false,
        global: false,
        url: "index.php?controller=pjAdminReports&action=pjActionCheckOrderTime",
        success: function (data) {
       
          if(data.status == 'true') {
            var order_id =data.orders;
            swal({
              title: "Please Complete and take reports ",
              type: "warning",
              html:true,
              customClass: "swal-width",
              text: "<h3 style='font-weight: 600; font-size: 22px'>Existing Orders still pending in Order List</h3> <p style='font-size: 22px'>Order ID:<span style='font-weight:bold'><span> "+ order_id.join(", ")+"</p>",
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "OK",
              closeOnConfirm: false,                
            });
          }
        },
      });
    } 
		
		
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [
            {
              type: "OrderInfo",
              text: " OrderInfo",
              url: "#",
            },
            {
              type: "CancelInfo",
              text: " info",
              url: "#",
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
              text: myLabel.posType,
              type: "text",
              sortable: false,
              editable: false,
            },
            {
              text: myLabel.order_date,
              type: "text",
              sortable: false,
              editable: false,
            },
            {
              text: myLabel.cancel_amount,
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
          ],
				dataUrl: "index.php?controller=pjAdminReports&action=pjActionGetCancelReturnOrders" + pjGrid.queryString,
				dataType: "json",
				fields: ["order_id", "total", "table_name", "order_date", "cancel_amount", "payment_method", "status"],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminReports&action=pjActionDeleteExpenseBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminReports&action=pjActionSaveProduct&id={:id}",
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
				}
			});
		}

    function formatImage(val, obj) {
      var src = val ? val : 'app/web/img/backend/no_image.png';
      return ['<img src="', src, '" style="width: 84px" />'].join("");
    }
    if ($("#grid_products").length > 0 && datagrid) {
      if(loadData == "pjActionGetTopProductsReport") {
        $("#heading-Product-list").html("Top Selling Products");
      } else if(loadData == "pjActionGetNonProductsReport") {
        $("#heading-Product-list").html("Non Selling Products");
      }
      var $grid = $("#grid_products").datagrid({
        buttons: [],
        columns: [{text: myLabel.image, type: "text", sortable: false, editable: false, renderer: formatImage},
                  {text: myLabel.name, type: "text", sortable: false, editable: true},
                  {text: myLabel.count, type: "text", sortable: false, editable: true, 
                    editableRenderer: function () {
                      return 0;
                    },
                    saveUrl: "index.php?controller=pjAdminProducts&action=pjActionSaveFeatured&id={:id}",
                    positiveLabel: myLabel.yes, positiveValue: "1", negativeLabel: myLabel.no, negativeValue: "0", 
                    cellClass: "text-center"}],
        dataUrl: "index.php?controller=pjAdminReports&action="+loadData + pjGrid.queryString,
        dataType: "json",
        fields: ['image', 'name', 'count'],
        paginator: {
          gotoPage: true,
          paginate: true,
          total: true,
          rowCount: true
        },
        saveUrl: "index.php?controller=pjAdminProducts&action=pjActionSaveProduct&id={:id}",
        select: {
          field: "id",
          name: "record[]",
          cellClass: 'cell-width-2'
        }
      });
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
		function generateList() {
			$('.ibox-content').addClass('sk-loading');
			var from = $('#date_from').val();
			var to = $('#date_to').val();
			var q = $('#query').val();
			var content = $grid.datagrid("option", "content"),
			cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				date_to: to,
				date_from: from,
				q: q,
				category_id: updated_category,
				page: page
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminReports&action=pjActionGetCancelReturnOrders", "order_date", "ASC", content.page, content.rowCount);
			return false;
			$('.ibox-content').removeClass('sk-loading');
		}

    function viewProductList(action) {
      $('.ibox-content').addClass('sk-loading');
      var from = $('#date_from').val();
      var to = $('#date_to').val();
      var q = $('#query').val();
      var category = $('#filter_category').val();
      var content = $grid.datagrid("option", "content"),
      cache = $grid.datagrid("option", "cache");
      $.extend(cache, {
        date_to: to,
        date_from: from,
        q: q,
        category_id: category,
        page: page
      });
      $grid.datagrid("option", "cache", cache);
      $grid.datagrid("load", "index.php?controller=pjAdminReports&action="+action, "count", "DESC", content.page, content.rowCount);
      return false;
      $('.ibox-content').removeClass('sk-loading');
    }

		$(document).ready(function() {
			if (updated_category != '' && page > 0) {
				// var updated_category = $("#updated_product_category");
				// var page = $("#updated_product_page");
				var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
				$.extend(cache, {
					category_id: updated_category,
					page: page
				});
				$grid.datagrid("option", "cache", cache);
				$grid.datagrid("load", "index.php?controller=pjAdminReports&action=pjActionGetProduct", "is_featured", "DESC", content.page, content.rowCount);
				return false;
			}

			if ($frmFromAndToDate.length > 0) {
				//generateList.call(null);
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
        	}
        $('#date_from').datepicker({
          autoclose: true
        }).on('changeDate', function (e) {
        	generateList.call(null);
				});
        $('#date_to').datepicker({
          autoclose: true
        }).on('changeDate', function (e) {
        	generateList.call(null);
				});
			}

			$(document).on("submit", ".frm-filter", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          generateList.call(null);
        }).on("submit", ".frm-filter-TopProduct", function (e) {
          if (e && e.preventDefault) {
            e.preventDefault();
          }
          // viewTopProductList.call(null);
          viewProductList(loadData);
        }).on("change", '#filter_category', function(e){
          var q = $('#query').val();
          var $this = $(this),
            content = $grid.datagrid("option", "content"),
            cache = $grid.datagrid("option", "cache");
          $.extend(cache, {
            q: q,
            category_id: $this.val(),
          });
          $grid.datagrid("option", "cache", cache);
          $grid.datagrid("load", "index.php?controller=pjAdminReports&action="+loadData, "count", "DESC", content.page, content.rowCount);
          return false;
          
        })
        .on("change", '#filter_reports', function(e){
          var q = $('#query').val();
          var $this = $(this);
          // console.log($this.val());
          switch ($this.val()) { 
            case 'top-selling': 
              loadData = "pjActionGetTopProductsReport";
              $("#heading-Product-list").html("Top Selling Products");
              viewProductList('pjActionGetTopProductsReport');
              break;
            case 'non-selling': 
              loadData = "pjActionGetNonProductsReport"
              $("#heading-Product-list").html("Non Selling Products");
              viewProductList('pjActionGetNonProductsReport');
              break;
            case 'cancel-return-Report': 
              window.location.replace(app_url+"index.php?controller=pjAdminReports&action=pjActionCancelReturnReport");
              break;
            default:
              viewProductList.call('pjActionGetTopProductsReport');
          }
          console.log(loadData);
          
        })
        .on("change", '#filter_reports2', function(e){
          var q = $('#query').val();
          var $this = $(this);
          console.log($this.val());
          switch ($this.val()) { 
            case 'top-selling': 
              window.location.replace(app_url+"index.php?controller=pjAdminReports&action=pjActionTopProductsReport&loadData=pjActionGetTopProductsReport");
              break;
            case 'non-selling': 
              window.location.replace(app_url+"index.php?controller=pjAdminReports&action=pjActionTopProductsReport&loadData=pjActionGetNonProductsReport");
              break;
            case 'cancel-return-Report': 
              window.location.replace(app_url+"index.php?controller=pjAdminReports&action=pjActionCancelReturnReport");
              break;
            default:
              viewProductList.call('pjActionGetTopProductsReport');
          }
          
        })
	  });


		$(document).on("change", "#location_id", function (e) {
			generateReport.call(null);
		});
	});
})(jQuery_1_8_2);