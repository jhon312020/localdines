var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
    var $frmFindDate = $("#frmFindDate"),
      $frmCreateSupplier = $("#frmCreateSupplier"),
      $frmUpdateSupplier = $("#frmUpdateSupplier"),
      $dialogDelete = $("#dialogDeleteImage"),
      dialog = ($.fn.dialog !== undefined),
      chosen = ($.fn.chosen !== undefined),
      multilang = ($.fn.multilang !== undefined),
      validate = ($.fn.validate !== undefined),
      datagrid = ($.fn.datagrid !== undefined),
      remove_arr = new Array();
    

    var urlParams = new URLSearchParams(window.location.search);
    var updated_category = urlParams.get('category'); 
    var err_code = urlParams.get('err');
    var page = urlParams.get('page');
    
    if (multilang && 'pjCmsLocale' in window) {
      $(".multilang").multilang({
        langs: pjCmsLocale.langs,
        flagPath: pjCmsLocale.flagPath,
        tooltip: "",
        select: function (event, ui) {
          $("input[name='locale_id']").val(ui.index);
        }
      });
    }

    if ($("#grid").length > 0 && datagrid) {
      var $grid = $("#grid").datagrid({
        buttons: [{type: "edit", url: "index.php?controller=pjAdminSuppliers&action=pjActionUpdate&id={:id}"},
                  {type: "delete", url: "index.php?controller=pjAdminSuppliers&action=pjActionDeleteSupplier&id={:id}"}
                  ],
        columns: [
              {text: myLabel.product_name, type: "text", sortable: false, editable: false},
              {text: myLabel.return_date, type: "text", sortable: true, editable: true},
              {text: myLabel.total_amount, type: "text", sortable: true, editable: true},
              {text: myLabel.purchase_date, type: "text", sortable: false, editable: false},
              {text: myLabel.product_qty, type: "text", sortable: false, editable: false}],
        dataUrl: "index.php?controller=pjAdminOrderReturns&action=pjActionGetReturnOrderList" + pjGrid.queryString,
        dataType: "json",
        fields: ['product_name','created_date', 'amount', 'purchase_date', 'qty'],
        paginator: {
          actions: [
             {text: myLabel.delete_selected, url: "index.php?controller=pjAdminSuppliers&action=pjActionDeleteCompanyBulk", render: true, confirmation: myLabel.delete_confirmation}
          ],
          gotoPage: true,
          paginate: true,
          total: true,
          rowCount: true
        },
        saveUrl: "index.php?controller=pjAdminOrderReturns&action=pjActionSaveProduct&id={:id}",
        select: {
          field: "id",
          name: "record[]",
          cellClass: 'cell-width-2'
        }
      });
    }
		
	});
})(jQuery_1_8_2);