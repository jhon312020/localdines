<div class="row">
  <div class="col-sm-6">
    
    <div class="form-group">
      <label class="control-label">Supplier</label>
      <select name="supplier" id="supplier" class="form-control select-voucher required" data-msg-required="This field is required.">
        <option value="">-- Choose --</option>
        <?php
          if(!empty($tpl['suppliers'])) {
            foreach($tpl['suppliers'] as $supplier) {
              ?><option value="<?php echo $supplier['id'] ?>"
               <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['supplier_id']== $supplier['id'] ? "selected" : '' ?>><?php echo $supplier['name'] ?></option><?php 
            }
          }
        ?>                                
      </select>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Category</label>
      <select name="category" id="category" class="form-control select-voucher required" data-msg-required="This field is required.">
        <option value="">-- Choose --</option>
        <?php
          if(!empty($tpl['category_arr'])) {
            foreach($tpl['category_arr'] as $cat) {
              ?><option value="<?php echo $cat['id'] ?>"
                <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['category_id']== $cat['id'] ? "selected" : '' ?>
                ><?php echo $cat['name'] ?></option><?php 
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
      <label class="control-label">Expense Name</label>
      <input type="text" name="expense_name" id="exp_name" value=" <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['expense_name'] ? $tpl['arr']['expense_name'] : '' ?>" class="form-control " data-msg-required="This field is required." />
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Sub category</label>
      <input type="text" name="sub_category" id="sub_cat" value=" <?php echo array_key_exists('arr', $tpl) && $tpl['arr']['sub_category'] ? $tpl['arr']['sub_category'] : '' ?>" class="form-control " data-msg-required="This field is required." />
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Description</label>
      <textarea class="form-control form-control-lg" name="description"><?php echo array_key_exists('arr', $tpl) && $tpl['arr']['description'] ? $tpl['arr']['description'] : '' ?></textarea>
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

      <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminExpense&action=pjActionIndex"><?php __('btnCancel'); ?></a>
    </div><!-- /.clearfix -->

  </div>
</div>
<div class="hr-line-dashed"></div>