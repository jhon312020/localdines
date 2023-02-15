<div class="row border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-10">
                <!-- <h2><?php //__('infoReportsTitle', false, true);?></h2> -->
                <h2><?php echo 'X Report'; ?></h2>
            </div>
        </div><!-- /.row -->
    </div><!-- /.col-md-12 -->
</div>
<style type = 'text/css'>
  .pos-report-row {
    margin-left: 0px;
  }
/*  .float-right {
    text-align: right;
    margin-left: 5%;
  }*/
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                	<div class="sk-spinner sk-spinner-double-bounce"><div class="sk-double-bounce1"></div><div class="sk-double-bounce2"></div></div>
                	<form action="" method="post" id="frmReport" autocomplete="off">
                		<input type="hidden" name="generate_report" value="1" />
                		<?php
                        $months = __('months', true);
                        ksort($months);
                        $short_days = __('short_days', true);
                        ?>
        				<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo pjUtil::toBootstrapDate($tpl['option_arr']['o_date_format']); ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
        				
                        <div class="row m-b-md">
                            <div class="col-md-4">
                                <label><?php __('lblDate'); ?></label>
    
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
    
                                        <input type="text" name="date_from" id="date_from" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['date_from']));?>" class="form-control" readonly>
    
                                        <span class="input-group-addon"><?php __('lblTo'); ?></span>
    
                                        <input type="text" name="date_to" id="date_to" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['date_to']));?>" class="form-control" readonly>
                                    </div>
                                </div><!-- /.form-group -->
                            </div>
    						<div class="col-md-3">
    							<label>&nbsp;</label>
    							<div class="form-group m-b-md">
    								<a id="pjFdPrintReprot" href="#" data-href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports&amp;action=pjActionPOSXPrint" class="btn btn-primary btn-outline"><i class="fa fa-print"></i> <?php __('btnPrint');?></a>
    							</div>
    						</div>
                        </div><!-- /.row -->
                    </form>

                    <div class="hr-line-dashed"></div>
                    <div class="pos-report">
                        <label> X Report</label>
                        <div class="hr-line-dashed"></div>
                        <label>Financial</label>
                        <div class="hr-line-dashed"></div>
                        
                        <div class="row pos-report-row">
                            <div class="col-lg-2">
                                <label class="control-label">Total No of Sales: </label>
                            </div>
                            <div class="col-lg-1">
                              <?php echo $tpl['num_of_sales']; ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row pos-report-row">
                            <div class="col-lg-2">
                                <label class="control-label">No of Table Sales: </label>
                            </div>
                            <div class="col-lg-1">
                              <?php echo $tpl['sales_report']['num_of_table_sales'] ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row pos-report-row">
                            <div class="col-lg-2">
                                <label class="control-label">No of Take Away Sales: </label>
                            </div>
                            <div class="col-lg-1">
                              <?php echo $tpl['sales_report']['num_of_direct_sales']; ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                         <div class="row pos-report-row">
                            <div class="col-lg-2">
                                <label class="control-label">Cash Sales: </label>
                            </div>
                            <div class="col-lg-1">
                              <?php echo pjCurrency::formatPrice($tpl['sales_report']['cash_sales']); ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                         <div class="row pos-report-row">
                            <div class="col-lg-2">
                                <label class="control-label">Card Sales: </label>
                            </div>
                            <div class="col-lg-1">
                              <?php echo pjCurrency::formatPrice($tpl['sales_report']['card_sales']); ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row pos-report-row">
                            <div class="col-lg-2">
                                <label class="control-label">Total Table Sales: </label>
                            </div>
                            <div class="col-lg-1">
                              <?php echo pjCurrency::formatPrice($tpl['sales_report']['total_table_sales']); ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row pos-report-row">
                            <div class="col-lg-2">
                                <label class="control-label">Total Take Away Sales: </label>
                            </div>
                            <div class="col-lg-1">
                              <?php echo pjCurrency::formatPrice($tpl['sales_report']['total_direct_sales']); ?>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="row pos-report-row">
                            <div class="col-lg-2">
                                <label class="control-label">Total Gross Sales: </label>
                            </div>
                            <div class="col-lg-1">
                              <?php echo pjCurrency::formatPrice($tpl['sales_report']['total_amount']); ?>
                            </div>
                        </div>
                        
                    </div>
                    
					<!-- <div id="pjFdReportContent">
                        
                    </div> --><!-- /#pjFdReportContent -->
                </div>
            </div>
        </div>
    </div>
</div>