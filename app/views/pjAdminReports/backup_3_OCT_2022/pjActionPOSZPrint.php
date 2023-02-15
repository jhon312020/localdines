<div class="wrapper wrapper-content animated fadeInRight" style="margin: auto; text-align: center;">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
          <div class="hr-line-dashed"></div>
          <div class="pos-report">
            <h3>Z Report</h3>
            <div class="hr-line-dashed"></div>
            <table class="table" style="margin: auto; text-align: center;">
              <tr>
                <th><?php echo "Cash Sales: ";?></th>
                <td><?php echo pjCurrency::formatPrice($tpl['sales_report']['cash_sales']); ?></td>
                <th><?php echo "Credit Sales: ";?></th>
                <td><?php echo pjCurrency::formatPrice($tpl['sales_report']['card_sales']); ?></td>
              </tr>
              <tr>
                <th><?php echo "No of Table Sales: ";?></th>
                <td><?php echo $tpl['sales_report']['num_of_table_sales']; ?></td>
                <th><?php echo "Total Table Sales: ";?></th>
                <td style="float: right;"><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_table_sales']); ?></td>
              </tr>
              <tr>
                <th><?php echo "No of Take Away Sales: ";?></th>
                <td><?php echo $tpl['sales_report']['num_of_direct_sales']; ?></td>
                <th><?php echo "Total Take Away Sales: ";?></th>
                <td style="float: right;"><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_direct_sales']); ?></td>
              </tr>
              <tr>
                <th><?php echo "No of Sales: ";?></th>
                <td><?php echo $tpl['num_of_sales']; ?></td>
                <th><?php echo "Total Sales: ";?></th>
                <td style="float: right;"><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_amount']); ?></td>
              </tr>
            </table>       
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="hidden-print" style="margin:auto; width:0px;">
 <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports&amp;action=pjActionPOSZIndex" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "Close" ?></a>
</div>