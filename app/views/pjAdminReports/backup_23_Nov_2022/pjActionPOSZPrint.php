<div class="wrapper wrapper-content animated fadeInRight" style="margin: auto; text-align: left;">
  <div class="row" id="zReport">
    <style>
      .currency {
        margin-right: 5px;
        float: right;
      }
    </style>
    <div class="col-lg-12" style="margin: 10px 20px; width: 300px; text-align: center;">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
          <div class="hr-line-dashed"></div>
          <div class="pos-report">
            <div class="header">
            <h3><span style="text-align: center;">Z Report</span></h3><br/>
            <h5 style="font-size: 14px;">Taken: <?php echo date('d-m-Y h:i a'); ?> </h5><br/>
            <hr style="margin: 10px 20px; width: 300px;"/>
            <h5 style="font-size: 14px;">From: <?php echo date('d-m-Y', strtotime($tpl["date_from"])); ?></h5>
            <h5 style="font-size: 14px;">To: <?php echo date('d-m-Y', strtotime($tpl["date_to"])); ?></h5>
            <div style="margin: 10px 20px; width: 300px; text-align: left; "><hr/></div>
            <div class="hr-line-dashed"></div>
            <table class="table" style="margin: 10px 20px; width: 300px; text-align: left; ">
              <tr>
                <th><?php echo "No of Table Sales: ";?></th>
                <td class="currency"><?php echo $tpl['sales_report']['num_of_table_sales']; ?></td>
              </tr>
              <tr>
                <th><?php echo "No of Take Away Sales: ";?></th>
                <td class="currency"><?php echo $tpl['sales_report']['num_of_direct_sales']; ?></td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th><?php echo "No of Sales: ";?></th>
                <td class="currency"><?php echo $tpl['num_of_sales']; ?></td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th><?php echo "Cash Sales: ";?></th>
                <td class="currency"><?php echo pjCurrency::formatPrice($tpl['sales_report']['cash_sales']); ?></td>
              </tr>
              <tr>
                <th><?php echo "Card Sales: ";?></th>
                <td class="currency"><?php echo pjCurrency::formatPrice($tpl['sales_report']['card_sales']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th><?php echo "Total Table Sales: ";?></th>
                <td class="currency"><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_table_sales']); ?></td>
              </tr>
              
              <tr>
                <th><?php echo "Total Take Away Sales: ";?></th>
                <td class="currency"><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_direct_sales']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th><?php echo "Total Sales: ";?></th>
                <td class="currency"><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_amount']); ?></td>
              </tr>
            </table>       
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="hidden-print" style="margin-left:10px; text-align: center; width: 300px;">
 <button class="btn btn-primary printbutton" id="zReportBtn" type="button">Print</button>
 <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports&amp;action=pjActionPOSZIndex" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "Close" ?></a>
</div>
<script type="text/javascript">
  $(document).ready(function() {
  // your code
    $(document).on("click", "#zReportBtn", function(e) {
      var report_order_ids = "<?php echo $tpl['sales_report']['report_order_ids']; ?>";
      $.ajax({
        type: "POST",
        async: false,
        url: "index.php?controller=pjAdminReports&action=pjActionUpdateZViewReport",
        data: {
          order_ids: report_order_ids
        },
        success: function (data) {
        },
        // !MEGAMIND
      });
      printDiv('zReport');
    });
  });
      
</script>