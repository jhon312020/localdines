<div class="fdLoader"></div>
<?php $index = $controller->_get->toString('index');?>
<br />
<div class="row">
	<div id="fdMain_<?php echo $index; ?>" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft">
		
		<div class="panel panel-default">
			<?php include_once dirname(__FILE__) . '/elements/header.php';?>
			<div class="panel-body  pjFdPanelBody">
				<form id="fdForgotForm_<?php echo $index;?>" action="" method="post" class="form-horizontal" data-toggle="validator">
					
					<div class="alert alert-warning alert-dismissible pjFdAlert" role="alert" style="display:none;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-info-circle"></i> <span id="fdForgotMessage_<?php echo $index;?>"></span>
					</div>
 					
					<div class="form-group">
						<label for="" class="col-md-2 col-sm-4 control-label"><?php __('front_email'); ?></label>

						<div class="col-md-10 col-sm-8">
							<input type="email" class="form-control" name="email" data-required="<?php __('front_email_address_required');?>" data-email="<?php __('front_email_not_valid');?>"/>

							<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
						</div>
					</div><!-- /.form-group -->

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="button" class="btn btn-default text-uppercase fdButtonSend"><?php __('front_send_password');?></button>
							<a href="#" class="btn btn-default text-uppercase fdButtonGetLogin"><?php __('front_client_login');?></a>
						</div><!-- /.col-sm-offset-2 col-sm-10 -->
					</div><!-- /.form-group -->
				</form><!-- /.form-horizontal -->
			</div><!-- /.panel-body pjFdPanelBody -->
			
		</div><!-- /.panel panel-default -->
	</div><!-- /.col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft -->
	<div id="fdCart_<?php echo $index; ?>" class="col-md-4 col-sm-4 col-xs-12 pjFdPanelRight">
		<?php include_once dirname(__FILE__) . '/elements/cart.php';?>
	</div><!-- /.col-md-4 col-sm-4 col-xs-12 pjFdPanelRight -->
</div><!-- /.row -->