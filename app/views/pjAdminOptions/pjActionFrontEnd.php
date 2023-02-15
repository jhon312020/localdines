<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h2>Front End Options</h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i>Use these options to design front end.</p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
    <?php
	$error_code = $controller->_get->toString('err');
	if (!empty($error_code))
    {
    	$titles = __('error_titles', true);
    	$bodies = __('error_bodies', true);
    	switch (true)
    	{
    		case in_array($error_code, array('AO02')):
    			?>
    			<div class="alert alert-success">
    				<i class="fa fa-check m-r-xs"></i>
    				<strong><?php echo @$titles[$error_code]; ?></strong>
    				<?php echo @$bodies[$error_code]?>
    			</div>
    			<?php
    			break;
    		case in_array($error_code, array('')):
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
    ?>
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
			<div class="form-group">
                <label class="col-sm-3 control-label">Qr Menu Page</label>
				<div class="col-lg-5 col-sm-7">
					<div class="row">
						<div class="col-sm-6">
							<div class="switch onoffswitch-data pull-right">
								<div class="onoffswitch onoffswitch-front_qrMenu">
									<input type="checkbox" class="onoffswitch-checkbox" id="front_qr" name="front_qr" <?php echo $tpl['arr'][0]['value'] == 1 ? "checked" : NULL; ?>>
									<label class="onoffswitch-label" for="front_qr">
										<span class="onoffswitch-inner" data-on="enable" data-off="disable"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
							</div>
                        </div>
					</div>
                </div>
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
</div>