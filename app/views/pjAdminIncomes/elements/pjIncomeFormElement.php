<div class="row">
  <div class="col-sm-6">
    
    <div class="form-group">
      <label class="control-label">Partner</label>
      <select name="master" id="master" class="form-control select-voucher required" data-msg-required="This field is required.">
        <option value="">-- Choose --</option>
        <?php
          if(!empty($tpl['masters'])) {
            foreach($tpl['masters'] as $master) {
              ?><option value="<?php echo $master['id'] ?>"
               <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['master_id']== $master['id'] ? "selected" : '' ?>><?php echo $master['name'] ?></option><?php 
            }
          }
        ?>                                
      </select>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Amount</label>
      <div class="input-group pjFdProductAmount">
        <input type="text" id="amount" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['amount'] ? $tpl['arr']['amount'] : '' ?>" name="amount" class="form-control pj-field-amount required number" data-msg-required="This field is required."/>
        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']); ?></span> 
      </div>
    </div><!-- /.form-group -->

  </div>


<div class="col-sm-6">
    <div class="form-group">
      <label class="control-label">Description</label>
      <textarea class="form-control form-control-lg" name="address"><?php echo array_key_exists('arr', $tpl) && $tpl['arr']['address'] ? $tpl['arr']['address'] : '' ?></textarea>
    </div><!-- /.form-group -->

  </div>
  </div>

<div class="hr-line-dashed"></div>
<div class="row">
  <div class="col-sm-12">

    <div class="clearfix">
      <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
        <span class="ladda-label"><?php __('btnSave'); ?></span>
          <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
      </button>

      <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminIncome&action=pjActionIndex"><?php __('btnCancel'); ?></a>
    </div><!-- /.clearfix -->

  </div>
</div>
<div class="hr-line-dashed"></div>