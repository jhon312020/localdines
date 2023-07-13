<div class="row">
  <div class="col-sm-6">
  
    <div class="form-group">
      <label class="control-label">Name</label>
      <div class="input-group pjFdProductName">
        <input type="text" id="name" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['name'] ? $tpl['arr']['name'] : '' ?>" name="name" class="form-control pj-field-name required name" data-msg-required="This field is required."/>
      </div>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Postal Code</label>
      <div class="input-group pjFdProductPostal_Code">
        <input type="text" id="postal_code" value="<?php echo array_key_exists('arr', $tpl) && $tpl['arr']['postal_code'] ? $tpl['arr']['postal_code'] : '' ?>" name="postal_code" class="form-control pj-field-postal_code required postal_code" data-msg-required="This field is required."/>
      </div>
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

      <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminPostalcode&action=pjActionIndex"><?php __('btnCancel'); ?></a>
    </div><!-- /.clearfix -->

  </div>
</div>
<div class="hr-line-dashed"></div>