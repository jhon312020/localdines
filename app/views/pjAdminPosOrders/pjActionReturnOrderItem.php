<?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_welcome_header.php'; ?>
<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-sm-12 bg-light" id="col-12">
    <?php 
      include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_inventory_header_buttons.php'; 
    ?> 
    <?php if (strtolower($tpl['arr']['origin']) == "telephone" || (strtolower($tpl['arr']['origin']) == "web" )) { ?>
      <div class="col-sm-12" style="min-height: 500px;">
        <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_form_telephone_update.php'; ?>
      </div>
      <div class="col-sm-12">
        <?php 
        // if ($tpl['arr']['status'] == PENDING_STATUS) { 
        //   include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_element.php';
        // } ?>
      </div>
    <?php } elseif ($tpl['arr']['origin'] == "Pos") { ?>
      <div class="col-sm-12" style="min-height: 500px;">
        <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_form_pos_eatin_update.php'; ?>
      </div>
      <div class="col-sm-12">
        <?php //include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_element.php'; ?>
      </div> 
    <?php } ?>
  </div>
</div>
<div class="row cus-pb-2 cus-pt-2 bottom_row"> 
  <div class="col-sm-5">
    <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_bottom_left_element.php'; ?>
  </div>
  <div class="col-sm-7">

  </div>
</div>

<div class="row wrapper wrapper-content animated fadeInRight">

</div>

<!-- End of Modal -->
<?php
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/return_product_modal.php';
?>
<!-- End of Payment Modal -->

<script type="text/javascript">
var categoryList = '<?php echo json_encode($tpl['category_list']); ?>';  
categoryList =  JSON.parse(categoryList);  
var myLabel = myLabel || {};
myLabel.currency = "<?php echo $tpl['option_arr']['o_currency'];?>";
myLabel.restaurant_closed = <?php x__encode('lblRestaurantClosed');?>;
myLabel.email_exists = <?php x__encode('email_taken'); ?>;
myLabel.phoneNumber_err = '<?php echo 'Mobile Number is invalid'; ?>';
myLabel.email_err = '<?php echo 'Email address is invalid'; ?>';
myLabel.voucher_err = '<?php echo 'Voucher code is invalid'; ?>';
myLabel.delivery_fee_err = '<?php echo 'This field only accepts integer and float values'; ?>';
myLabel.mobileDelivery_err = '<?php echo 'Please select any one of the delivery info'; ?>'; 
myLabel.emailReceipt_err = '<?php echo 'Please select any one of the delivery info'; ?>'; 
var ideal_api_key = "<?php echo IDEAL_API_KEY;?>";
</script>
