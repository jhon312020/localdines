var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateClient = $("#frmCreateClient"),
			$frmUpdateClient = $("#frmUpdateClient"),
			chosen = ($.fn.chosen !== undefined),
			datagrid = ($.fn.datagrid !== undefined);
		
		if (chosen) {
			$("#c_country").chosen();
		}
		if($frmCreateClient.length > 0)
		{
			$frmCreateClient.validate({
				rules: {
					"c_phone": {
						required: true,
						
						remote: "index.php?controller=pjAdminClients&action=pjActionCheckPhoneNumber"
					}
				},
				messages: {
					"c_phone": {
						remote: myLabel.phoneno_exists
					}
				}
			});
		}
		if ($frmUpdateClient.length > 0) {
			$frmUpdateClient.validate({
				rules: {
					"c_phone": {
						required: true,
						
						remote: "index.php?controller=pjAdminClients&action=pjActionCheckPhoneNumber&id=" + $frmUpdateClient.find("input[name='id']").val()
					}
				},
				messages: {
					"c_phone": {
						remote: myLabel.phoneno_exists
					}
				}
			});
		}
		function formatOrders(str, obj) 
		{
			if(parseInt(str, 10) > 0)
			{
				return '<a href="index.php?controller=pjAdminOrders&action=pjActionIndex&client_id='+obj.id+'">'+str+'</a>';
			}else{
				return str;
			}
		}
		if ($("#grid").length > 0 && datagrid) 
		{
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminClients&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminClients&action=pjActionDeleteClient&id={:id}"}
				          ],
				columns: [{text: myLabel.firstname, type: "text", sortable: true, editable: false , editableWidth: 190},
				          {text: myLabel.surname, type: "text", sortable: true, editable: false , editableWidth: 190},
				          {text: myLabel.email, type: "text", sortable: true, editable: false , editableWidth: 190},
				          {text: myLabel.phone_no, type: "text", sortable: true, editable: false , editableWidth: 190},
				          {text: myLabel.c_postcode, type: "text", sortable: true, editable: false , editableWidth: 190},
				          {text: myLabel.orders, type: "text", sortable: true, editable: false , align: "center", renderer: formatOrders},
				          {text: myLabel.status, type: "toggle", sortable: true, editable: true, positiveLabel: myLabel.active, positiveValue: "T", negativeLabel: myLabel.inactive, negativeValue: "F"},
				          ],
				dataUrl: "index.php?controller=pjAdminClients&action=pjActionGetClient",
				dataType: "json",
				fields: ['c_name','c_surname', 'c_email','phone','c_postcode', 'cnt_orders', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminClients&action=pjActionDeleteClientBulk", render: true, confirmation: myLabel.delete_confirmation},
					   {text: myLabel.revert_status, url: "index.php?controller=pjAdminClients&action=pjActionStatusClient", render: true},
					   {text: myLabel.exported, url: "index.php?controller=pjAdminClients&action=pjActionExportClient", ajax: false}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminClients&action=pjActionSaveClient&id={:id}",
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
				}
			});
		}
		
		$(document).on("click", ".btn-all", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).addClass("btn-primary active").removeClass("btn-default")
				.siblings(".btn").removeClass("btn-primary active").addClass("btn-default");
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				status: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminClients&action=pjActionGetClient", "c_name", "ASC", content.page, content.rowCount);
			
		}).on("click", ".btn-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache"),
				obj = {};
			$this.addClass("btn-primary active").removeClass("btn-default")
				.siblings(".btn").removeClass("btn-primary active").addClass("btn-default");
			obj.status = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminClients&action=pjActionGetClient", "c_name", "ASC", content.page, content.rowCount);
			
		}).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminClients&action=pjActionGetClient", "id", "ASC", content.page, content.rowCount);
			return false;
		});
	});
})(jQuery_1_8_2);