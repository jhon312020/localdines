<div class="">
  <div class="panel no-borders ibox-content text-right" id="voucher-container" style="padding: 5px 5px; ">
    <div class="form-group row" style="margin-bottom: 2px;">
      <div class="control-label col-sm-9"></div>
      <label class="control-label col-sm-1"><?php __('lblVoucher'); ?></label>
      <div class="col-sm-2">
        <?php
          $voucher_code = isset($tpl['arr']['voucher_code'])?stripslashes($tpl['arr']['voucher_code']):''; ?>
        <input type="text" name="voucher_code" class="jsVK-normal form-control voucher" value="<?php echo $voucher_code;?>">
      </div>
    </div>
  </div><!-- /.col-md-3 -->
</div><!-- /.m-b-lg -->