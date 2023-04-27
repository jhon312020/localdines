<?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_welcome_header.php'; ?>
<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-sm-5" id="col-5">
    <div class="row">
      <div class="col-sm-12 bg-blue-secondary" style="min-height: 700px;">
        <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/hot_keys_list.php'; ?>
        <input type='hidden' id='current_page' />
        <input type='hidden' id='show_per_page' />
        <input type='hidden' id='nop' />
        <div class="ibox-content" style="display: none;margin: 0px -20px 0px -20px;">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>
          <div id="products-sec" class="row products" style="display: none;"></div>
        </div>  
      </div>

      <div class="col-sm-12 bg-blue-secondary topnav-two">
        <div class="row" style="margin-top: 5px;margin-bottom: 5px;">
          <!-- <div class="col-sm-8" style="display: flex;">
            <div id="btnCategories" style="width: 50%; margin-right: 15px">Menu</div>
            <div id="slideCategories" style="display: none; border-radius: 50%; width:60px; color: black; padding:0px; height: 60px;">
              <i class="fa fa-chevron-circle-left fa-3x" aria-hidden="true"></i>
            </div>
          </div> -->
          <div id="page_navigation" class="col-sm-12 text-right"></div>
        </div>
      </div>

    </div>
  </div>
  <div class="col-sm-7  bg-light" id="col-7">
   <?php 
      include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_inventory_header_buttons.php'; 
    ?>  
    <div class="col-sm-12">
      <input type="hidden" id="frm-type" value="#frmCreateOrder_epos">
      <?php  
        include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_form_pos_eatin.php';
      ?>
    </div>
    <div class="col-sm-12">
      <?php  
        include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_element.php';
      ?>
    </div>
  </div>
</div>
<div class="row cus-pb-2 cus-pt-2 bottom_row">
  <div class="col-sm-5">
    <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_bottom_left_element.php'; ?>
  </div>
  <div class="col-sm-7">
    <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_bottom_element.php'; ?>
  </div>
</div>
<?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/category_list.php'; ?>
<div class="row wrapper wrapper-content animated fadeInRight">
</div>
<?php
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/cart_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/postal_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/price_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/special_ins_modal.php';
  // include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/special_ins_view_model.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/extra_model.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/client_phone_modal.php';
  // include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/pause_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/custom_product_modal.php'; 
  //include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/no_print.php'; 
?>
<script src="https://cdn.jsdelivr.net/npm/@ideal-postcodes/core-browser-bundled/dist/core-browser.umd.min.js"></script>

<script type="text/javascript">
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
  var categoryList = '<?php echo json_encode($tpl['category_list']); ?>';  
  categoryList =  JSON.parse(categoryList);  
  var client_info = '<?php echo json_encode($tpl['client_info']); ?>';
  client_info = JSON.parse(client_info);
  var ideal_api_key = "<?php echo IDEAL_API_KEY;?>";
  var dojo_host = "<?php echo DOJO_PAYMENT_URL;?>"+"/"+"<?php echo DOJO_PAY_TYPE_PAC;?>"+"?token="+"<?php echo DOJO_TOKEN; ?>"+"&api-version=v1&software-house-id="+"<?php echo DOJO_SOFTWARE_HOUSE_ID;?>"+"&installer-id="+"<?php echo DOJO_INSTALLER_ID; ?>"+"&final-pos-receipt-request";
  // wss://sd711b330000.test.connect.paymentsense.cloud/PAT?token=0247c27a-3e9d-41a5-bb20-a0c05db55f38&api-version=<connect-version>&software-house-id=SD711B33&installer-id=SD711B33&[final-pos-receipt-request]


// URL - sd711b330000.test.connect.paymentsense.cloud
// API key - 0247c27a-3e9d-41a5-bb20-a0c05db55f38
// Software-House-Id and Installer-Id - SD711B33

// VCMINVLSIP0 - simulates a successful chip and pin payment
// VCMINVLDIP0 - simulates a declined chip and pin payment
// VCMINVLSCD0 - simulates a contactless payment with device verification
// VCMINVLSIS0 - simulates a signature payment
// VCMINVLUIP0 - simulates an unsuccessful payment result
// VCMINVLTIP0 - simulates a "TIMED_OUT" payment result
// wss://<account-name>.connect.paymentsense.cloud/<integration-type>?token=<api-key>&api-version=<connect-version>&software-house-id=<software-house-id>&installer-id=<installer-id>&[final-pos-receipt-request]
  //var host = 'wss://sd711b330000.test.connect.paymentsense.cloud/pac?token=0247c27a-3e9d-41a5-bb20-a0c05db55f38&api-version=v1&software-house-id=SD711B33&installer-id=SD711B33&final-pos-receipt-request';
</script>

