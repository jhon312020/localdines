<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
        	<div class="col-lg-9 col-md-8 col-sm-6">
                <h2>List of Expense<?php //__('infoExtrasTitle');?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
        	</div>
        </div><!-- /.row -->
 
        <!-- <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php //__('infoExtrasDesc', false, true);?></p> -->
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                	<div class="col-md-4">
                    	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExpense&amp;action=pjActionCreate" class="btn btn-primary"><i class="fa fa-plus"></i> Add Expense<?php //__('btnAddExtra') ?></a>
                    </div><!-- /.col-md-6 -->
                    <div class="col-md-4">
                    	<form action="" method="get" class="form-horizontal frm-filter">
                            <div class="input-group m-b-md">
                                <input type="text" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.col-lg-6 -->
                    <div class="col-md-4">
                        <?php if ( $_SESSION['admin_user']['role_id'] == ADMIN_R0LE_ID) { ?>

                            <form id="frmFindDate">
                                <?php
                                  $months = __('months', true);
                                  if ($months) {
                                    ksort($months);
                                  }
                                  $short_days = __('short_days', true);
                                ?>
                                <div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 

                                        <input type="text" name="date_from" id="date_from" value="<?php echo date("d.m.Y");?>" class="form-control" readonly>

                                        <span class="input-group-addon"><?php __('lblTo'); ?></span>

                                        <input type="text" name="date_to" id="date_to" value="<?php echo date("d.m.Y");?>" class="form-control" readonly>
                                    </div>
                                </div><!-- /.form-group -->
                            </form>

                        <?php } ?>
                        
                        
                    </div>
                </div><!-- /.row -->
                
                <div id="grid"></div>
                
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
</div>
<script type="text/javascript">
    var pjGrid = pjGrid || {};
    pjGrid.queryString = "";
var myLabel = myLabel || {};
myLabel.date = "Date";
myLabel.company = "Company";
myLabel.product_name = "Product Name";
myLabel.amount = "Amount";
myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
myLabel.trigger_create = <?php echo $controller->_get->toInt('create'); ?>;
</script>
<?php if ($tpl['is_flag_ready']) : ?>
<script type="text/javascript">
var myLabel = myLabel || {};
var pjCmsLocale = pjCmsLocale || {};
pjCmsLocale.langs = <?php echo $tpl['locale_str']; ?>;
pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
</script>
<?php endif; ?>