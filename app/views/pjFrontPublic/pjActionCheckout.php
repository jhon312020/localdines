<?php include_once dirname(__FILE__) . '/elements/navbar.php';?>
<div class="fdLoader"></div>
<?php
$index = $controller->_get->toString('index');
$STORAGE = @$_SESSION[$controller->defaultStore];
$FORM = isset($_SESSION[$controller->defaultForm]) ? $_SESSION[$controller->defaultForm] : array();
$CLIENT = $controller->isFrontLogged() ? @$_SESSION[$controller->defaultClient] : array();
?>
<br />
<div class="container">
<div class="row">
	<div id="fdMain_<?php echo $index; ?>" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft">
		
		<div class="panel panel-default">
			<?php include_once dirname(__FILE__) . '/elements/header.php';?>
			<div class="panel-body pjFdPanelBody">
				<?php
				if($tpl['status'] == 'OK')
				{
					?>
					<form id="fdCheckoutForm_<?php echo $index;?>" action="" method="post" class="form-horizontal" data-toggle="validator">
						
						<?php
						ob_start();
						if (in_array($tpl['option_arr']['o_bf_include_address_1'], array(2, 3)))
						{ 
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_address_line_1'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<input type="text" name="c_address_1" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_address_1'] === 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_address_1']) ? htmlspecialchars(stripslashes(@$FORM['c_address_1'])) : htmlspecialchars(stripslashes(@$CLIENT['c_address_1'])); ?>" data-err="<?php __('front_address1_required');?>"/>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_include_address_2'], array(2, 3)))
						{ 
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_address_line_2'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<input type="text" name="c_address_2" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_address_2'] === 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_address_2']) ? htmlspecialchars(stripslashes(@$FORM['c_address_2'])) : htmlspecialchars(stripslashes(@$CLIENT['c_address_2'])); ?>" data-err="<?php __('front_address2_required');?>"/>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_include_city'], array(2, 3)))
						{ 
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_city'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<input type="text" name="c_city" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_city'] === 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_city']) ? htmlspecialchars(stripslashes(@$FORM['c_city'])) : htmlspecialchars(stripslashes(@$CLIENT['c_city'])); ?>" data-err="<?php __('front_city_required');?>"/>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_include_state'], array(2, 3)))
						{ 
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_state'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<input type="text" name="c_state" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_state'] === 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_state']) ? htmlspecialchars(stripslashes(@$FORM['c_state'])) : htmlspecialchars(stripslashes(@$CLIENT['c_state'])); ?>" data-err="<?php __('front_state_required');?>"/>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_include_zip'], array(2, 3)))
						{ 
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_zip'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<input type="text" name="c_zip" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_zip'] === 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_zip']) ? htmlspecialchars(stripslashes(@$FORM['c_zip'])) : htmlspecialchars(stripslashes(@$CLIENT['c_zip'])); ?>" data-err="<?php __('front_zip_required');?>"/>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_include_country'], array(2, 3)))
						{ 
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_country'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<select name="c_country" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_country'] === 3 ? ' required' : NULL; ?>" data-err="<?php __('front_country_required');?>">
										<option value="">-- <?php __('front_choose'); ?> --</option>
										<?php
										foreach ($tpl['country_arr'] as $country)
										{
											?><option value="<?php echo $country['id']; ?>"<?php echo isset($FORM['c_country']) ? (@$FORM['c_country'] == $country['id'] ? ' selected="selected"' : NULL) : (@$CLIENT['c_country'] == $country['id'] ? ' selected="selected"' : NULL); ?>><?php echo stripslashes($country['country_title']); ?></option><?php
										}
										?>
									</select>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						$ob_address = ob_get_contents();
						ob_end_clean();
						if (!empty($ob_address))
						{ 
							?>
							<h2 class="text-muted text-center"><?php __('front_address');?></h2>
		
							<br />
							<?php
							echo $ob_address;
						} 
						?>
						<h2 class="text-muted text-center"><?php __('front_personal_details');?></h2>
		
						<br />
						<?php
						if (in_array($tpl['option_arr']['o_bf_include_title'], array(2, 3)))
						{
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_title'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<select name="c_title" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_title'] == 3) ? ' required' : NULL; ?>" data-err="<?php __('front_title_required');?>">
										<option value="">----</option>
										<?php
										$title_arr = pjUtil::getTitles();
										$name_titles = __('personal_titles', true, false);
										foreach ($title_arr as $v)
										{
											?><option value="<?php echo $v; ?>"<?php echo isset($FORM['c_title']) ? (@$FORM['c_title'] == $v ? ' selected="selected"' : NULL) : (@$CLIENT['c_title'] == $v ? ' selected="selected"' : NULL); ?>><?php echo $name_titles[$v]; ?></option><?php
										}
										?>
									</select>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_include_name'], array(2, 3)))
						{
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_name'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<input type="text" name="c_name" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_name'] === 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_name']) ? htmlspecialchars(stripslashes(@$FORM['c_name'])) : htmlspecialchars(stripslashes(@$CLIENT['name'])); ?>" data-err="<?php __('front_name_required');?>"/>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_include_email'], array(2, 3)))
						{
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_email'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<input type="text" name="c_email" class="form-control email<?php echo (int) $tpl['option_arr']['o_bf_include_email'] === 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_email']) ? htmlspecialchars(stripslashes(@$FORM['c_email'])) : htmlspecialchars(stripslashes(@$CLIENT['email'])); ?>" data-err="<?php __('front_email_required');?>" data-email="<?php __('front_email_not_valid');?>"/>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_include_phone'], array(2, 3)))
						{ 
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_phone'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<input type="text" name="c_phone" class="form-control fdW50p<?php echo (int) $tpl['option_arr']['o_bf_include_phone'] === 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_phone']) ? htmlspecialchars(stripslashes(@$FORM['c_phone'])) : htmlspecialchars(stripslashes(@$CLIENT['phone'])); ?>" data-err="<?php __('front_phone_required');?>"/>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_include_company'], array(2, 3)))
						{ 
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_company'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<input type="text" name="c_company" class="form-control fdW100p<?php echo (int) $tpl['option_arr']['o_bf_include_company'] === 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['c_company']) ? htmlspecialchars(stripslashes(@$FORM['c_company'])) : htmlspecialchars(stripslashes(@$CLIENT['c_company'])); ?>" data-err="<?php __('front_company_required');?>"/>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_include_notes'], array(2, 3)))
						{ 
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_notes'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<textarea name="c_notes" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_include_notes'] === 3 ? ' required' : NULL; ?>" data-err="<?php __('front_notes_required');?>"><?php echo isset($FORM['c_notes']) ? htmlspecialchars(stripslashes(@$FORM['c_notes'])) : htmlspecialchars(stripslashes(@$CLIENT['c_notes'])); ?></textarea>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						?>
						
						<?php
						if($tpl['option_arr']['o_payment_disable'] == 'No')
						{
							?>
							<h2 class="text-muted text-center"><?php __('front_payment', false, false); ?></h2>
		
							<br />
						
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_payment_medthod'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<?php
									$plugins_payment_methods = pjObject::getPlugin('pjPayments') !== NULL? pjPayments::getPaymentMethods(): array();
									$haveOnline = $haveOffline = false;
									foreach ($tpl['payment_titles'] as $k => $v)
									{
									    if( $k != 'cash' && $k != 'bank' )
									    {
									        if( (int) $tpl['payment_option_arr'][$k]['is_active'] == 1)
									        {
									            $haveOnline = true;
									            break;
									        }
									    }
									}
									foreach ($tpl['payment_titles'] as $k => $v)
									{
									    if( $k == 'cash' || $k == 'bank' )
									    {
									        if( (int) $tpl['payment_option_arr'][$k]['is_active'] == 1)
									        {
									            $haveOffline = true;
									            break;
									        }
									    }
									}
									?>
									<select id="fdPaymentMethod_<?php echo $index;?>" name="payment_method" class="form-control required" data-err="<?php __('front_payment_method_required');?>">
										<option value="">-- <?php __('front_choose');?> --</option>
										<?php
										if ($haveOnline && $haveOffline)
										{
										    ?><optgroup label="<?php __('script_online_payment_gateway', false, true); ?>"><?php
                                        }
                                        foreach ($tpl['payment_titles'] as $k => $v)
                                        {
                                            if($k == 'cash' || $k == 'bank' ){
                                                continue;
                                            }
                                            if (array_key_exists($k, $plugins_payment_methods))
                                            {
                                                if(!isset($tpl['payment_option_arr'][$k]['is_active']) || (isset($tpl['payment_option_arr']) && $tpl['payment_option_arr'][$k]['is_active'] == 0) )
                                                {
                                                    continue;
                                                }
                                            }
                                            ?><option value="<?php echo $k; ?>"<?php echo isset($FORM['payment_method']) && $FORM['payment_method']==$k ? ' selected="selected"' : NULL;?>><?php echo $v; ?></option><?php
                                        }
                                        if ($haveOnline && $haveOffline)
                                        {
                                            ?>
                                        	</optgroup>
                                        	<optgroup label="<?php __('script_offline_payment', false, true); ?>">
                                        	<?php 
                                        }
                                        foreach ($tpl['payment_titles'] as $k => $v)
                                        {
                                            if( $k == 'cash' || $k == 'bank' )
                                            {
                                                if( (int) $tpl['payment_option_arr'][$k]['is_active'] == 1)
                                                {
                                                    ?><option value="<?php echo $k; ?>"<?php echo isset($FORM['payment_method']) && $FORM['payment_method']==$k ? ' selected="selected"' : NULL;?>><?php echo $v; ?></option><?php
                                                }
                                            }
                                        }
                                        if ($haveOnline && $haveOffline)
                                        {
                                            ?></optgroup><?php
                                        }
										?>
									</select>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						} 
						?>
						<div id="fdBankData_<?php echo $index;?>" style="display: <?php echo isset($FORM['payment_method']) && $FORM['payment_method'] == 'bank' ? 'block' : 'none'; ?>">
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize">&nbsp;</label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<p class="fdParagraph fdTermsConditions">
										<?php echo nl2br(pjSanitize::html($tpl['bank_account'])); ?>
									</p>
								</div>
							</div>
						</div>
						<?php
						if (in_array($tpl['option_arr']['o_bf_include_captcha'], array(2, 3))){
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize"><?php __('front_captcha'); ?></label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<?php
									if($tpl['option_arr']['o_captcha_type_front'] == 'system')
									{
    									?>
    									<div class="row">
    										<div class="col-md-5">
    											<input type="text" id="fdCaptcha_<?php echo $index;?>" name="captcha" maxlength="<?php echo $tpl['option_arr']['o_captcha_mode_front'] == 'string'? (int) $tpl['option_arr']['o_captcha_length_front']: 10 ?>" class="form-control<?php echo ($tpl['option_arr']['o_bf_include_captcha'] == 3) ? ' required' : NULL; ?>" autocomplete="off" data-err="<?php __('front_captcha_required');?>" data-incorrect="<?php __('frotn_incorrect_captcha');?>"/>
    										</div>
    										<div class="col-md-7">
    											<img id="pjFdCaptchaImage_<?php echo $index;?>" src="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFrontEnd&action=pjActionCaptcha&rand=<?php echo rand(1, 9999); ?><?php echo $controller->_get->check('session_id') ? '&session_id=' . $controller->_get->toString('session_id') : NULL;?>" alt="Captcha" style="border: solid 1px #E0E3E8;cursor: pointer;"/>
    										</div>
    									</div>
    									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
    									<?php
									}else{
									    ?>
									    <div id="g-recaptcha_<?php echo $index; ?>" class="g-recaptcha" data-sitekey="<?php echo $tpl['option_arr']['o_captcha_site_key_front'] ?>"></div>
                                        <input type="hidden" id="recaptcha" name="recaptcha" class="recaptcha<?php echo ($tpl['option_arr']['o_bf_include_captcha'] == 3) ? ' required' : NULL; ?>" autocomplete="off" data-err="<?php __('front_captcha_required');?>" data-incorrect="<?php __('frotn_incorrect_captcha');?>"/>
                                        <div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
									    <?php
									}
    								?>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						if ($tpl['option_arr']['o_terms_show'])
						{
							?>
							<div class="form-group">
								<label for="" class="col-lg-3 col-md-4 col-sm-4 control-label text-capitalize">&nbsp;</label>
								<div class="col-lg-9 col-md-8 col-sm-8">
									<div class="checkbox">
									    <label>
									      	<input id="fdAgree_<?php echo $index;?>" name="agreement" type="checkbox" class="required" data-err="<?php __('front_agree_required');?>" />
									      	<?php __('front_agree');?>&nbsp;<a href="#" data-target="#pjFdTermsAndConditions" data-toggle="modal"><?php __('front_terms_conditions');?></a>
									    </label>
								  	</div>
								  	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div><!-- /.form-group -->
							<?php
						}
						$can_delivery = true;
						if ($controller->_get('type') == 'delivery')
						{
							$price = $controller->_get('price');
							if($price < $tpl['option_arr']['o_minimum_order'])
							{
								$can_delivery = false;
								$message = __('front_minimum_order_amount', true);
								$message = str_replace("{AMOUNT}", pjCurrency::formatPrice($tpl['option_arr']['o_minimum_order']), $message);
								?>
								<div class="row">
									<div class="col-sm-12 text-left">
										<div class="alert alert-warning" role="alert"><?php echo $message;?></div>
									</div>
									<br/>
								</div><!-- /.row -->
								<?php
							}
						}
						?>
						<br/>
						
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
								<a href="#" class="btn btn-default text-uppercase fdButtonGetVouchers"><?php __('front_button_back');?></a>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
								<a href="#" class="btn btn-default text-uppercase fdButtonGetPreview<?php echo ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart'])) > 0 ? ($can_delivery == true ? null : ' fdButtonDisabled') : ' fdButtonDisabled';?>"<?php echo $can_delivery == true ? null : ' disabled="disabled"';?>><?php __('front_button_continue');?></a>
							</div><!-- /.col-sm-8 col-sm-offset-4 -->
						</div><!-- /.row -->
					</form>
					<?php
				}else{
					?>
					<div>
						<?php
						$front_messages = __('front_messages', true, false);
						$system_msg = str_replace("[STAG]", "<a href='#' class='fdStartOver'>", $front_messages[13]);
						$system_msg = str_replace("[ETAG]", "</a>", $system_msg); 
						echo $system_msg; 
						?>
					</div>
					<?php
				}					 
				?>
			</div><!-- /.panel-body pjFdPanelBody -->
			
		</div><!-- /.panel panel-default -->
	</div><!-- /.col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft -->
	<div id="fdCart_<?php echo $index; ?>" class="col-md-4 col-sm-4 col-xs-12 pjFdPanelRight">
		<?php include_once dirname(__FILE__) . '/elements/cart.php';?>
	</div><!-- /.col-md-4 col-sm-4 col-xs-12 pjFdPanelRight -->
</div><!-- /.row -->
</div>
<?php include_once dirname(__FILE__) . '/elements/footer.php';?>