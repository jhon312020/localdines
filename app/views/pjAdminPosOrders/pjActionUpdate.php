<style>
  .navbar-static-side {
    display: none;
  }
  #page-wrapper {
    margin: 0px;
  }
  .navbar-minimalize {
    display: none;
  }
  .wrapper {
    padding: 0px;
  }
  .wrapper-content {
    padding: 0px;
  }
  fieldset {
    background-color: #eeeeee;
    margin: 10px 0px;
  }
  legend {
    background-color: gray;
    color: white;
    padding-left: 10px;
  }
  fieldset .row {
    margin: 0px -5px;
  }
  .spacing {
    margin:  20px;
    font-size: 12px;
  }
  .legend-input {
    font-weight: 100;
    font-size: 12px;
  }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-keyboard@latest/build/css/index.css">
<script src="https://cdn.jsdelivr.net/npm/simple-keyboard@latest/build/index.js"></script>
<?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_welcome_header.php'; ?>
<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-sm-5" id="col-5">
    <div class="row">
      <div class="col-sm-12 bg-blue-secondary" style="min-height: 700px;">
        <input type='hidden' id='current_page' />
        <input type='hidden' id='show_per_page' />
        <input type='hidden' id='nop' />
        <div class="ibox-content" style="display: none;margin: 0px -20px 0px -20px;">
          <div class="sk-spinner sk-spinner-double-bounce"><div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>
          <div id="products-sec" class="row products" style="display: none;"></div>
        </div>
      </div>

      <div class="col-sm-12 bg-blue-secondary topnav-two">
        <div class="row" style="margin-top: 5px;margin-bottom: 5px;">
          <!-- <div class="col-sm-8" style="display: flex;">
            <div id="btnCategories" style="width: 50%; margin-right: 15px">
              Menu
            </div>
            <div id="slideCategories" style="display: none; border-radius: 50%; width:60px; color: black; padding:0px; height: 60px;">
              <i class="fa fa-chevron-circle-left fa-3x" aria-hidden="true"></i>
            </div>
          </div> -->
          <div id="page_navigation" class="col-sm-12" style="text-align: right;">
          </div>
        </div> 
      </div>

    </div>
  </div>
  <div class="col-sm-7 bg-light" id="col-7">
    <div class="col-sm-12">
      <?php 
        include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_inventory_header_buttons.php'; 
      ?> 
    </div>
      <?php if (strtolower($tpl['arr']['origin']) == "telephone" || (strtolower($tpl['arr']['origin']) == "web" )) { ?>
        <div class="col-sm-12" style="min-height: 500px;">
          <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_form_telephone_update.php'; ?>
        </div>
        <div class="col-sm-12">
          <?php 
          if ($tpl['arr']['status'] == PENDING_STATUS) { 
            include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_element.php';
          } ?>
        </div>

      <?php } elseif ($tpl['arr']['origin'] == "Pos") { ?>
        <div class="col-sm-12" style="min-height: 500px;">
          <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_form_pos_eatin_update.php'; ?>
        </div>
        <div class="col-sm-12">
          <?php include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_element.php'; ?>
        </div> 
      <?php } ?>
  </div>
</div>
<div class="row cus-pb-2 cus-pt-2" style="background-color: white;"> 
  <div class="col-sm-5"></div>
  <div class="col-sm-7">
    <?php
  if (strtolower($tpl['arr']['origin']) == "telephone" || (strtolower($tpl['arr']['origin']) == "web" )) {  
    if ($tpl['arr']['status'] == PENDING_STATUS) { 
      include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_bottom_element.php';
    }
  } elseif ($tpl['arr']['origin'] == "Pos") {
    include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_bottom_element.php';
  } ?>
  </div>
</div>

<div id="js-categories" class="row wrapper wrapper-content animated">
  <?php foreach($tpl['category_list'] as $key=>$category)  { ?>
  <div class="col-sm-2 col-lg-1 cus-category" data-id="<?php echo $key; ?>" data-category="<?php echo $category; ?>">
    <div class="category-container cus-pt-2 cus-pb-2" data-id="<?php echo $key; ?>">
      <div class="content"><h4><?php echo $category; ?></h4></div>
    </div>
  </div>
  <?php } ?>
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
<?php
//echo '<pre>'; print_r($tpl['arr']); echo '</pre>';
  $time_format = 'HH:mm';
  if((strpos($tpl['option_arr']['o_time_format'], 'a') > -1)) {
    $time_format = 'hh:mm a';
  }
  if((strpos($tpl['option_arr']['o_time_format'], 'A') > -1)) {
    $time_format = 'hh:mm A';
  }
  $months = __('months', true);
  if ($months) {
    ksort($months);
  }
  $short_days = __('short_days', true);
  $times = ["08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"];
?>
</div>

<!-- End of Modal -->
<?php
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/table_select_modal.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/cart_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/postal_modal.php';  
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_tel_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/price_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/special_ins_modal.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/special_ins_view_model.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/extra_model.php';
  
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_modal.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/pause_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/no_print.php'; 
?>
<!-- End of Payment Modal -->
<script src="https://cdn.jsdelivr.net/npm/@ideal-postcodes/core-browser-bundled/dist/core-browser.umd.min.js"></script>

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
</script>

