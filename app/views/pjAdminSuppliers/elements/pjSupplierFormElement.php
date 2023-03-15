<div class="row">
  <div class="col-sm-12 col-lg-12">

    <div class="form-group">
      <label class="control-label">Supplier Name</label>
      <input type="text" name="name" id="company_name" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['name'] ? $tpl['arr']['name'] : '' ?>" class="form-control required" data-msg-required="This field is required." />
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Contact Name</label>
      <input type="text" name="contact_person" id="contact_person" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['contact_person'] ? $tpl['arr']['contact_person'] : '' ?>" class="form-control required" data-msg-required="This field is required." />
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Contact Number</label>
      <input type="text" name="contact_number" id="contact_number" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['contact_number'] ? $tpl['arr']['contact_number'] : '' ?>" class="form-control required number" data-msg-required="This field is required." />
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Address</label>
        <textarea class="form-control form-control-lg required" name="address"><?php echo array_key_exists('arr', $tpl) && $tpl['arr']['address'] ? $tpl['arr']['address'] : '' ?></textarea>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Postal Code</label>
      <input type="text" name="postal_code" id="postal_code" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['postal_code'] ? $tpl['arr']['postal_code'] : '' ?>" class="form-control required" data-msg-required="This field is required." />
    </div><!-- /.form-group -->

  </div>

  <div class="col-sm-12 col-lg-12">
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <div class="hr-line-dashed"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <div class="clearfix">
      <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
        <span class="ladda-label"><?php __('btnSave'); ?></span>
          <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
      </button>
      <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminCompanies&action=pjActionIndex"><?php __('btnCancel'); ?></a>
    </div><!-- /.clearfix -->
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <div class="hr-line-dashed"></div>
  </div>
</div>