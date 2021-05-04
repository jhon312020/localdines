<div class="row wrapper border-bottom white-bg page-heading">
    <!-- <?php //print_r($tpl['product_arr']); ?>  -->
    <!-- <?php //print_r($tpl['data']); ?> -->
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-10">
                <h2 id="orderIndexTitle"><?php __('infoOrdersListTitle');?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoOrdersListDesc');?></p>
    </div><!-- /.col-md-12 -->
</div>
<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
    	<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
    	{
    	    $titles = __('error_titles', true);
    	    $bodies = __('error_bodies', true);
    	    switch (true)
    	    {
    	        case in_array($error_code, array('AR01', 'AR03')):
    	            ?>
    				<div class="alert alert-success">
    					<i class="fa fa-check m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
                case in_array($error_code, array('AR04', 'AR08')):
    				?>
    				<div class="alert alert-danger">
    					<i class="fa fa-exclamation-triangle m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
    		}
    	}
    	$statuses = __('order_statuses', true, false);
        $statuses['delivered'] = "Delivered";
        // print_r($statuses);
    	?>
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row m-b-md">
                    <div class="col-md-4">
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOrders&amp;action=pjActionCreate" class="btn btn-primary"><i class="fa fa-plus"></i> <?php __('btnAddOrder') ?></a>
                    </div><!-- /.col-md-6 -->
                    <!-- <input type="text" name="order_info" value="<?php //echo $tpl['order_info']; ?>"> -->
					 <form action="" method="get" class="form-horizontal frm-filter">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div><!-- /.col-md-3 -->
    
                        <div class="col-md-2 col-md-offset-2 text-right">
                        	<select name="type" id="filter_type" class="form-control">
                				<option value="">-- <?php __('lblAll'); ?> --</option>
                				<?php
                				foreach (__('types', true, false) as $k => $v)
                				{
                				    ?><option value="<?php echo $k; ?>"<?php echo $controller->_get->toString('type') == $k ? ' selected="selected"' : NULL;?>><?php echo stripslashes($v); ?></option><?php
                				}
                				// foreach (__('order_statuses', true, false) as $k => $v)
                				// {
                				
                				// }
                                foreach ($statuses as $k => $v) {
                                    # code...
                                    ?><option value="<?php echo $k; ?>"><?php echo stripslashes($v); ?></option><?php
                                }
                				?>
                			</select>
                        </div><!-- /.col-md-6 -->
                    </form>
                </div><!-- /.row -->

                <div id="grid"></div>
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
   <!--  <center>
    <input type="button" id="btnShowPopup" value="Show Popup" class="btn btn-info btn-lg" />
</center> -->
<!-- Modal Popup -->
<!-- <div id="MyPopup" class="modal fade" role="dialog">
    <div class="modal-dialog"> -->
        <!-- Modal content-->
        <!-- <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;</button>
                <h4 class="modal-title">
                </h4>
            </div>
            <div class="modal-body">
                
                <form role='form_edit'action="" method="post"> -->

             <!--  <div class="form-group">
                <label for="Title" >Title</label>
                <input type="text"  class="form-control" id="titleId" name='tit' placeholder="Enter the title of the article">
              </div> -->
            <!-- <select class="form-control" id="delay_reason">
              <option selected>Choose the reason</option>
              <option value="1">Traffic Delay</option>
              <option value="2">Too busy with more orders</option>
              <option value="3">Short of staff</option>
              <option value="4">Custom</option>
            </select>
 
              <div class="form-group" style="margin-top: 20px;">
                
                <textarea class="form-control" rows='5' id="message" name="delay_msg"></textarea>
              </div>

              <button type="submit" class="btn btn-default btn-primary btn-block" id="d_msg" >Submit</button>

          </form> -->
                <!-- <textarea name="dataMsg"></textarea> -->
            <!-- </div> -->
            <!-- <div class="modal-footer">
                <a type="submit" class="btn btn-primary">
                    Submit</a>
            </div> -->
        <!-- </div>
    </div>
</div> -->
<!-- Button trigger modal -->
<style>
    .modal h2 {
        color: #575757;
        font-size: 30px;
        text-align: center;
        font-weight: 600;
    }
    .modal .modal-body {
        font-size: 20px;
        font-weight: 400;
    }
</style>
<div class="modal fade" id="MyPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="width:100%">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
                    &times;</button>
        <h2 class="modal-title" id="modalTitle">Delay Message</h2>
      </div>
      <div class="modal-body" id="modalBody">
        <form role='form_edit'action="" method="post"> 

            <select class="form-control" id="delay_reason">
              <option selected>Choose the reason</option>
              <option value="1">Traffic Delay</option>
              <option value="2">Too busy with more orders</option>
              <option value="3">Short of staff</option>
              <option value="4">Custom</option>
            </select>
 
              <div class="form-group" style="margin-top: 20px;">
                
                <textarea class="form-control" rows='5' id="message" name="delay_msg"></textarea>
              </div>

              <button type="submit" class="btn btn-default btn-primary" id="d_msg" >Submit</button>

          </form>

      </div>
    </div>
  </div>
</div>
<!-- End of Modal -->
</div>
<script type="text/javascript">
var pjGrid = pjGrid || {};
pjGrid.queryString = "";
<?php
if ($controller->_get->toInt('client_id'))
{
    ?>pjGrid.queryString += "&client_id=<?php echo $controller->_get->toInt('client_id'); ?>";<?php
}
if ($controller->_get->toString('type'))
{
    ?>pjGrid.queryString += "&type=<?php echo $controller->_get->toString('type'); ?>";<?php
}
?>
var myLabel = myLabel || {};
//myLabel.name = <?php x__encode('lblName'); ?>;
myLabel.id = '<?php echo 'ID'; ?>';
myLabel.name = '<?php echo 'Surname'; ?>';
myLabel.phone = <?php x__encode('lblPhone'); ?>;
myLabel.address = '<?php echo 'Address'; ?>';
myLabel.postcode = '<?php echo 'Postcode'; ?>';
myLabel.c_type = '<?php echo 'C.Type'; ?>';
// myLabel.call_start = '<?php //echo 'Call Start'; ?>';
// myLabel.call_end = '<?php //echo 'Call End'; ?>';
myLabel.sms_email = '<?php echo 'SMS/Email'; ?>';
myLabel.order_despatched = '<?php echo 'OD'; ?>';
myLabel.yes = <?php x__encode('_yesno_ARRAY_T'); ?>;
myLabel.no = <?php x__encode('_yesno_ARRAY_F'); ?>;
myLabel.excpected_delivery = '<?php echo 'ED'; ?>';
myLabel.sms_sent_time = '<?php echo 'SST'; ?>';
myLabel.delivered_customer = '<?php echo 'DC'; ?>';
myLabel.review = '<?php echo 'R'; ?>';
//myLabel.date_time = <?php x__encode('lblDateTime'); ?>;
myLabel.total = <?php x__encode('lblTotal'); ?>;
myLabel.type = <?php x__encode('lblType'); ?>;
myLabel.pickup = <?php x__encode('lblPickup'); ?>;
myLabel.delivery = <?php x__encode('lblDelivery'); ?>;
myLabel.status = <?php x__encode('lblStatus'); ?>;
myLabel.exported = <?php x__encode('lblExport'); ?>;
myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
myLabel.pending = <?php x__encode('order_statuses_ARRAY_pending'); ?>;
myLabel.confirmed = <?php x__encode('order_statuses_ARRAY_confirmed'); ?>;
myLabel.cancelled = <?php x__encode('order_statuses_ARRAY_cancelled'); ?>;
myLabel.delivered = '<?php echo "delivered"; ?>';

</script>