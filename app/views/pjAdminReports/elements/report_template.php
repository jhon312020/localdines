<div class="pos-report" id="pjFdReportContent">
  <!-- <label><?php //echo $tpl['report_title']; ?></label>
  <div class="hr-line-dashed"></div> -->
  <label>Financial</label>
  <div class="hr-line-dashed"></div>
  <div class="row pos-report-row">
    <div class="col-xs-3 col-sm-4 col-lg-2">
      <label class="control-label">Total No of Sales: </label>
    </div>
    <div class="col-xs-3 col-sm-2 col-lg-1">
      <?php echo $tpl['num_of_sales']; ?>
    </div>
    <div class="col-xs-3 col-sm-4 col-lg-2">
      <label class="control-label">No of Pos Sales: </label>
    </div>
    <div class="col-xs-3 col-sm-2 col-lg-1">
      <?php echo $tpl['sales_report']['num_of_pos_sales'] ?>
    </div>
  </div>
  <div class="hr-line-dashed"></div>
  <div class="row pos-report-row">
    <div class="col-xs-4 col-sm-4 col-lg-2">
      <label class="control-label">No of Table Sales: </label>
    </div>
    <div class="col-xs-3 col-sm-2 col-lg-1">
      <?php echo $tpl['sales_report']['num_of_table_sales']; ?>
    </div>
    <div class="col-xs-3 col-sm-4 col-lg-2">
      <label class="control-label">No of Tele Sales: </label>
    </div>
    <div class="col-xs-3 col-sm-2 col-lg-1">
      <?php echo $tpl['sales_report']['num_of_telephone_sales']; ?>
    </div>
  </div>
  <div class="hr-line-dashed"></div>
  <div class="row pos-report-row">
    <div class="col-xs-3 col-sm-4 col-lg-2">
      <label class="control-label">No of Take Away Sales: </label>
    </div>
    <div class="col-xs-3 col-sm-2 col-lg-1">
      <?php echo $tpl['sales_report']['num_of_direct_sales']; ?>
    </div>
    <div class="col-xs-3 col-sm-4 col-lg-2">
      <label class="control-label">No of Web Sales: </label>
    </div>
    <div class="col-xs-3 col-sm-2 col-lg-1">
      <?php echo $tpl['sales_report']['num_of_web_sales']; ?>
    </div>
    <div class="col-xs-3 col-sm-4 col-lg-2">
      <label class="control-label">No of Incomes: </label>
    </div>
    <div class="col-xs-3 col-sm-2 col-lg-1">
      <?php echo $tpl['sales_report']['num_of_incomes']; ?>
    </div>
  </div>
  <div class="hr-line-dashed"></div>
  <div class="hr-line-dashed"></div>
  <div class="row pos-report-row">
    <div class="col-xs-4 col-lg-2">
      <label class="control-label">Cash Sales: </label>
    </div>
    <div class="col-xs-4 col-lg-1">
      <?php echo pjCurrency::formatPrice($tpl['sales_report']['cash_sales']); ?>
    </div>
    <div class="col-xs-4 col-lg-2">
      <label class="control-label">Number of Return Orders: </label>
    </div>
    <div class="col-xs-4 col-lg-1">
      <?php echo $tpl['sales_report']['num_of_return_orders']; ?>
    </div>
  </div>
  <div class="hr-line-dashed"></div>
   <div class="row pos-report-row">
    <div class="col-xs-4 col-lg-2">
      <label class="control-label">Card Sales: </label>
    </div>
    <div class="col-xs-4 col-lg-1">
      <?php echo pjCurrency::formatPrice($tpl['sales_report']['card_sales']); ?>
    </div>
    <div class="col-xs-4 col-lg-2">
      <label class="control-label">Total Return Orders: </label>
    </div>
    <div class="col-xs-4 col-lg-1">
      <?php echo pjCurrency::formatPrice($tpl['sales_report']['total_return_orders']); ?>
    </div>
  </div>
  <div class="hr-line-dashed"></div>
  <div class="row pos-report-row">
    <div class="col-xs-4 col-lg-2">
      <label class="control-label">Total Table Sales: </label>
    </div>
    <div class="col-xs-4 col-lg-1">
      <?php echo pjCurrency::formatPrice($tpl['sales_report']['total_table_sales']); ?>
    </div>
    <div class="col-xs-3 col-lg-2">
      <label class="control-label">Number of Suppliers Exp: </label>
    </div>
    <div class="col-xs-3 col-lg-1">
      <?php echo $tpl['sales_report']['num_of_expenses']; ?>
    </div>
    <div class="col-xs-3 col-lg-2">
      <label class="control-label">Number of Incomes: </label>
    </div>
    <div class="col-xs-3 col-lg-1">
      <?php echo $tpl['sales_report']['num_of_incomes']; ?>
    </div>
  </div>
  <div class="hr-line-dashed"></div>
  <div class="row pos-report-row">
    <div class="col-xs-3 col-lg-2">
      <label class="control-label">Total Take Away Sales: </label>
    </div>
    <div class="col-xs-3 col-lg-1">
      <?php echo pjCurrency::formatPrice($tpl['sales_report']['total_direct_sales']); ?>
    </div>
    <div class="col-xs-3 col-lg-2">
      <label class="control-label">Total Suppliers Expenses: </label>
    </div>
    <div class="col-xs-3 col-lg-1">
      <?php echo pjCurrency::formatPrice($tpl['sales_report']['total_supplier_exp']); ?>
    </div>
      <div class="col-xs-3 col-lg-2">
      <label class="control-label">Total Incomes: </label>
    </div>
    <div class="col-xs-3 col-lg-1">
      <?php echo pjCurrency::formatPrice($tpl['sales_report']['total_incomes']); ?>
    </div>
  </div>
  <div class="hr-line-dashed"></div>
  <div class="row pos-report-row">
    <div class="col-xs-3 col-lg-2">
      <label class="control-label">Total Gross Sales: </label>
    </div>
    <div class="col-xs-3 col-lg-1">
      <?php echo pjCurrency::formatPrice($tpl['sales_report']['total_amount']); ?>
    </div>
    <div class="col-xs-3 col-lg-2">
      <label class="control-label">Total Expenses: </label>
    </div>
    <div class="col-xs-3 col-lg-1">
      <?php echo pjCurrency::formatPrice($tpl['sales_report']['total_expenses']); ?>
    </div>
  </div>
  <div class="hr-line-dashed"></div>
  <div class="hr-line-dashed"></div>
  <div class="row pos-report-row">
    <div class="col-xs-3 col-lg-2">
      <label class="control-label">Cash in Hand: </label>
    </div>
    <div class="col-xs-3 col-lg-1">
      <?php echo pjCurrency::formatPrice($tpl['sales_report']['cash_in_hand']); ?>
    </div>
  </div>
</div>