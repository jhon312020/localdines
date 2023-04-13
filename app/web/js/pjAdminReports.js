var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var $frmReport = $("#frmReport"),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined);
		var urlParams = new URLSearchParams(window.location.search);
		var updated_category = urlParams.get('category'); 
		var err_code = urlParams.get('err');
		var page = urlParams.get('page');

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
		
		if ($frmReport.length > 0) {
			generateReport.call(null);
			
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
		
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [
            {
              type: "print",
              text: " Rprint",
              url: "index.php?controller=pjAdminPosOrders&action=pjActionSalePrint&id={:id}&source=index",
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
				fields: ["order_id", "total", "table_name", "order_date", "payment_method", "status"],
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
			var content = $grid.datagrid("option", "content"),
			cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				date_to: to,
				date_from: from,
				category_id: updated_category,
				page: page
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminReports&action=pjActionGetCancelReturnOrders", "c_name", "ASC", content.page, content.rowCount);
			return false;
			$('.ibox-content').removeClass('sk-loading');

		}

		$(document).ready(function() {
			if(updated_category != '' && page > 0) {
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
		});

		$(document).on("change", "#location_id", function (e) {
			generateReport.call(null);
		});
	});
})(jQuery_1_8_2);