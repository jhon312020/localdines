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
  .col-container {
    display: table;
    width: 100%;
  }
  .col {
    display: table-cell;
    padding: 16px;
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
  <?php
    $time_format = 'HH:mm';
    if ((strpos($tpl['option_arr']['o_time_format'], 'a') > -1)) {
      $time_format = 'hh:mm a';
    }
    if ((strpos($tpl['option_arr']['o_time_format'], 'A') > -1)) {
      $time_format = 'hh:mm A';
    }
    $months = __('months', true);
    if ($months) {
      ksort($months);
    }
    $short_days = __('short_days', true);
    $statuses = __('order_statuses', true, false);
    unset($statuses['cancelled'], $statuses['delivered'], $statuses['confirmed']);
    //unset($statuses['delivered']);
    $times = ["08:00","08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"];
  ?>
  <div class="col-sm-5" id="col-5">
    <div class="row">
      
      <div class="col-sm-12 bg-blue-secondary" style="min-height: 700px;">
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
          <div id="page_navigation" class="col-sm-12 text-right" style="text-align: right;"></div>
        </div>
      </div>

    </div>
  </div><!-- /.col-sm-6 -->
  <div class="col-sm-7  bg-light" id="col-7">
    <div class="col-sm-12" style="margin-top: 10px;">
      <div class="row">
        <!-- <div class="col-sm-1">
          <div class="arr arr-left"><i class="fa fa-bars"></i></div>
          <div class="arr arr-right" style="display:none;"><i class="fa fa-bars"></i></div> 
        </div> -->
        <div class="col-lg-4 col-sm-5 text-left">
          <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionIndex" class="btn btn-default" style="color: #676a6c;">
            <i class="fa fa-chevron-circle-left fa-3x" aria-hidden="true"></i>
          </a>
          <a href="#" class="btn btn-primary" id="btn-pause">
            <i class="fa fa-pause" aria-hidden="true"></i>
          </a>
          <a href="#" class="btn btn-primary" id="showPostalCodes">
            <i class="fa fa-map-marker" aria-hidden="true"></i>
          </a>
        </div>
        <div class="col-lg-4 col-sm-5 text-right">
          <form class="form-inline" style="" onkeydown="return event.key != 'Enter';">
            <div id="product_input" class="input-group d-none">
              <input id="inputSearch" type="text" class="form-control" type="search" placeholder="Search Products..." aria-label="Search">
              <div id="productSearch" class="input-group-addon btn btn-outline-success my-2 my-sm-0" type="button" style="background-color: #fff;color: #000;"><i class="fa fa-search" aria-hidden="true"></i></div>
            </div>
            <button id="productSearchHide" class="btn btn-outline-success my-2 my-sm-0" type="button" style="background-color: #0a5114;color: white;"><i class="fa fa-search" aria-hidden="true"></i></button>
          </form>
        </div>
        <div class="col-lg-4 col-sm-2 text-right">
          <?php if ($_SESSION[$controller->defaultUser]['role_id'] != WAITER_R0LE_ID) { ?>
          <!-- <a href="#" class="btn btn-primary" id="btn-openDrawer">
            <i class="fa fa-unlock" aria-hidden="true"> TOpen</i>
          </a> -->
          <?php } ?>
          <a href="#" class="btn btn-primary" id="showCart">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            <span id="cartPriceBottom"><?php echo pjCurrency::formatPrice(0); ?></span>
          </a>
        </div>
      </div>
         
    </div>  
    <div class="col-sm-12">
      <input type="hidden" id="frm-type" value="#frmCreateOrder_pos">
      <?php 
        include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/order_form_telephone.php'; 
      ?>
    </div>
  </div><!-- /.col-sm-6 -->
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
<?php
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/table_select_modal.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/cart_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/postal_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/price_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/special_ins_modal.php';
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/client_phone_modal.php';

  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/payment_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/pause_modal.php'; 
  include PJ_VIEWS_PATH . 'pjAdminPosOrders/elements/no_print.php'; 
?>
<script src="https://cdn.jsdelivr.net/npm/@ideal-postcodes/core-browser-bundled/dist/core-browser.umd.min.js"></script>

<script type="text/javascript">
//console.log(client_info);
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

</script>

