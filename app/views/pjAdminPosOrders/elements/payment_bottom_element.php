<div id="paymentModal">
  <div class="row">
    <div class="col-lg-4 col-sm-4 text-left">
      <div>
        <span class="confirm_payment_method" id="confirm_payment_method">
          <button id = "paymentTypeCash" class="btn payment-method-btn"><?php echo "Cash"; ?></button>
          <button id = "paymentTypeCard" class="btn payment-method-btn"><?php echo "Card"; ?></button>
        </span>
      </div>
    </div>
    <div class="col-lg-4 col-sm-1 text-center">
      <div>
        <button type="button" id="paymentBtn" data-valid = "false" data-phone="" class="btn btn-primary">Pay</button>
      </div>
    </div>
    <div class="col-lg-4 col-sm-7 text-right">
      <div>
        <span style="padding-left:10px">
          <?php if ($_SESSION[$controller->defaultUser]['role_id'] != WAITER_R0LE_ID) { ?>
          <a href="#" class="btn btn-primary" id="btn-openDrawer"><i class="fa fa-unlock" aria-hidden="true"> TOpen</i></a>
          <?php } ?>
          <button type="button" class="btn btn-secondary" id="btn-clear">Clear</button>
          <a class="btn btn-secondary" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminPosOrders&action=pjActionIndex"><?php __('btnCancel'); ?></a>
          
        </span>
      </div>
    </div>
  </div>
</div>