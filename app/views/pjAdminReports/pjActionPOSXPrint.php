<?php $paperWidth = "290px"; ?>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row" id="xReport" >
    <style>
      .currency {
        margin-right: 5px;
        float: right;
      }
    </style>
    <div class="col-lg-12" style="margin: 10px 10px 10px 20px; width: <?php echo $paperWidth; ?>; text-align:center;">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
          <div class="hr-line-dashed"></div>
          <div class="pos-report" >
            <h3><span style="text-align: center;">X Report</span></h3><br/>
            <h5 style="font-size: 14px;">Taken: <?php echo date('d-m-Y h:i a'); ?> </h5><br/>
            <hr style="margin: 10px 20px; width: <?php echo $paperWidth; ?>;"/>
            <h5 style="font-size: 14px;">From: <?php echo date('d-m-Y', strtotime($tpl["date_from"])); ?></h5>
            <h5 style="font-size: 14px;">To: <?php echo date('d-m-Y', strtotime($tpl["date_to"])); ?></h5>
            <div style="margin: 10px 20px; width: <?php echo $paperWidth; ?>; text-align: left; "><hr/></div>
            <div class="hr-line-dashed"></div>
            <table class="table" style="margin: 10px 10px 10px 20px; width: <?php echo $paperWidth; ?>; text-align: left; ">
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
                <th><?php echo "No of Tele Sales: ";?></th>
                <td class="currency"><?php echo $tpl['sales_report']['num_of_telephone_sales']; ?></td>
              </tr>
              <tr>
                <th><?php echo "No of Pos Sales: ";?></th>
                <td class="currency"><?php echo $tpl['sales_report']['num_of_pos_sales']; ?></td>
              </tr>
               <tr>
                <th><?php echo "No of Web Sales: ";?></th>
                <td class="currency"><?php echo $tpl['sales_report']['num_of_web_sales']; ?></td>
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
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th><?php echo "Total Return Order Exp: ";?></th>
                <td class="currency"><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_return_orders']); ?></td>
              </tr>
              <tr>
                <th><?php echo "Total Suppliers Exp: ";?></th>
                <td class="currency"><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_supplier_exp']); ?></td>
              </tr>
              <tr>
                <th><?php echo "Total Expenses: ";?></th>
                <td class="currency"><?php echo pjCurrency::formatPrice($tpl['sales_report']['total_expenses']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><hr/><hr/></td>
              </tr>
              <tr>
                <th><?php echo "Cash in Hand: ";?></th>
                <td class="currency"><?php 
                echo pjCurrency::formatPrice($tpl['sales_report']['cash_in_hand']); 
                ?>
              </td>
              </tr>
            </table>       
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="hidden-print" style="margin-left:10px; text-align: center; width: <?php echo $paperWidth; ?>;">
<button class="btn btn-primary printbutton" onClick="printDiv('xReport')">Print</button>
 <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports&amp;action=pjActionPOSXIndex" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "Close" ?></a>
</div>