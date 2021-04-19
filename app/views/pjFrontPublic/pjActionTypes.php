<div class="fdLoader"></div>
<?php
$index = $controller->_get->toString('index');
$STORAGE = @$_SESSION[$controller->defaultStore];
$CLIENT = $controller->isFrontLogged() ? @$_SESSION[$controller->defaultClient] : array();

$isPickup = !isset($STORAGE['type']) || $STORAGE['type'] == 'pickup';
$isDelivery = isset($STORAGE['type']) && $STORAGE['type'] == 'delivery';

$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
?>
<br />
<div class="row">
	<div id="fdMain_<?php echo $index; ?>" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft">
		
		<div class="panel panel-default">
			<?php include_once dirname(__FILE__) . '/elements/header.php';?>	
			
			<div class="panel-body pjFdPanelBody">
				<?php
				if($tpl['status'] == 'OK')
				{ 
					?>
					<form id="fdTypeForm_<?php echo $index; ?>" action="" method="post" class="form-horizontal">
						<input type="hidden" name="loadTypes" value="1" />
						<div role="tabpanel">
							<ul class="nav nav-pills" role="tablist">
								<li role="presentation" class="fdTabOuter<?php echo $isPickup ? ' active' : NULL; ?>">
									<a href="#" class="text-uppercase fdTypeTab" aria-controls="pickup" role="tab">
										<i class="fa fa-suitcase"></i>
	
										&nbsp;
										<?php __('front_pickup');?>
									</a>
								</li>
								<li role="presentation" class="fdTabOuter<?php echo $isDelivery ? ' active' : NULL; ?>">
									<a href="#" class="text-uppercase fdTypeTab" aria-controls="delivery" role="tab">
										<i class="fa fa-taxi"></i>
										&nbsp;
										<?php __('front_delivery');?>
									</a>
								</li>
							</ul>
							<span style="overflow: hidden; height: 0; display: none;">
								<input type="radio" name="type" id="fdTypePickup_<?php echo $index; ?>" value="pickup"<?php echo $isPickup ? ' checked="checked"' : NULL; ?> />
								<input type="radio" name="type" id="fdTypeDelivery_<?php echo $index; ?>" value="delivery"<?php echo $isDelivery ? ' checked="checked"' : NULL; ?> />
							</span>
						</div><!-- role="tabpanel" -->
						<br/>
						<div class="tab-content">
							<div role="tabpanel" class="fdPickup" style="display: <?php echo @$STORAGE['type'] == 'delivery' ? 'none' : NULL; ?>">
								<div class="form-group">
									<label for="" class="col-sm-4 control-label"><?php __('front_location'); ?></label>
									<div class="col-sm-8" style="display:<?php echo count($tpl['location_arr']) != 1 ? ' block' : ' none';?>">
										<select name="p_location_id" class="form-control" data-err="<?php __('front_location_required'); ?>">
											<?php 
											if(count($tpl['location_arr']) != 1)
											{
												?>
												<option value="">-- <?php __('front_choose'); ?> --</option>
												<?php
											}
											foreach ($tpl['location_arr'] as $location)
											{
												?><option value="<?php echo $location['id']; ?>"<?php echo @$STORAGE['p_location_id'] == $location['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($location['name']); ?></option><?php
											}
											?>
										</select>
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
									</div>
									<div class="col-sm-8" style="display: <?php echo count($tpl['location_arr']) == 1 ? ' block' : ' none';?>">
										<label class="control-label"><?php echo pjSanitize::html($tpl['location_arr'][0]['name']);?></label>
									</div>
								</div><!-- /.form-group -->
								
								<div class="form-group" style="display:<?php echo count($tpl['location_arr']) == 1 ? ' block' : ' none';?>;">
									<label for="" class="col-sm-4 control-label">&nbsp;</label>
									<div class="col-sm-8">
										<strong><?php __('front_address'); ?></strong>: <span id="fdPickupAddressLabel_<?php echo $index;?>"><?php echo count($tpl['location_arr']) == 1 ? pjSanitize::html($tpl['location_arr'][0]['address']) : null; ?></span>
										<input type="hidden" id="fdPickupAddress_<?php echo $index;?>" name="address" value="<?php echo count($tpl['location_arr']) == 1 ? pjSanitize::html($tpl['location_arr'][0]['address']) : null; ?>" class="fdText fdW100p" readonly="readonly" />
									</div>
								</div><!-- /.form-group -->
									
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<div id="fdTypeMap_<?php echo $index; ?>" style="height: 300px;"></div>
									</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
									<br />
								</div><!-- /.row -->
								<br/>
								<div id="fdPickupDateTime_<?php echo $index;?>" class="form-group" style="display:<?php echo (isset($STORAGE['p_date']) && !empty($STORAGE['p_date']) || count($tpl['location_arr']) == 1) ? ' block' : ' none'; ?>;">
									<label for="" class="col-sm-4 control-label"><?php __('front_pickup_datetime'); ?></label>

									<div class="col-sm-8">
										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xss-12">
												<div class="input-group date">
													<span class="input-group-addon pjFdCursorPointer">
														<i class="fa fa-calendar"></i>
													</span>
													<input type="text" name="p_date" id="fd_p_date_<?php echo $index; ?>" class="form-control" value="<?php echo isset($STORAGE['p_date']) ? htmlspecialchars(stripslashes(@$STORAGE['p_date'])) : date($tpl['option_arr']['o_date_format'], time()); ?>" data-dformat="<?php echo $jqDateFormat; ?>" data-fday="<?php echo $week_start; ?>" data-err="<?php __('front_pickup_date_required'); ?>" />
												</div><!-- /.input-group date -->
												<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
											</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-6 -->
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xss-12">
												<div class="fdPickupTime">
													<?php
													$p_time = isset($STORAGE['p_time']) ? $STORAGE['p_time'] : NULL;
													
													if(count($tpl['location_arr']) != 1 || $p_time != NULL )
													{
														$date = date('Y-m-d');
														$start_time = strtotime(date('Y-m-d 00:00:00'));
														$end_time = strtotime(date('Y-m-d 23:45:00'));
														if(isset($STORAGE['p_date']))
														{
														    $date = pjDateTime::formatDate(@$STORAGE['p_date'], $tpl['option_arr']['o_date_format']);
														}
														
														if (isset($tpl['wt_arr']))
														{
															$start_time = strtotime($date . ' ' . $tpl['wt_arr']['start_hour'] . ':' . $tpl['wt_arr']['start_minutes'] . ':00');
															$end_time = strtotime($date . ' ' . $tpl['wt_arr']['end_hour'] . ':' . $tpl['wt_arr']['end_minutes'] . ':00');
														}
														if($end_time < $start_time)
														{
															$end_time += 86400;
														}
														$midnight = strtotime($tpl['date'] . ' 23:59:59');
														$interval = 900;
														$next = pjUtil::getCurrentTimeSnap15Minutes();
														if($start_time < $next)
														{
															$start_time = $next;
														}
														?>
														<select name="p_time" class="form-control">
															<option value="asap"><?php __('front_asap');?></option>
															<?php
															for($i = $start_time; $i <= $end_time; $i += $interval)
															{
																$iso_time = date($tpl['option_arr']['o_time_format'], $i);
																$time_text = $iso_time;
																if($i > $midnight )
																{
																	$time_text .= ' (' . __('front_next_day', true) . ')';
																}
																?><option value="<?php echo $iso_time;?>"<?php echo $iso_time == $p_time ? ' selected="selected"' : null; ?>>1212<?php echo $time_text;?></option><?php	
															} 
															?>
														</select>
														<?php
													}
													?>
												</div>
											</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-6 -->
										</div><!-- /.row -->
									</div>
								</div><!-- /.form-group -->
								<?php
								if (in_array($tpl['option_arr']['o_pf_include_notes'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_special_instructions'); ?></label>
										<div class="col-sm-8">
											<textarea name="p_notes" rows="4" class="form-control<?php echo (int) $tpl['option_arr']['o_pf_include_notes'] === 3 ? ' fdRequired' : NULL; ?>" data-err="<?php __('front_special_required');?>"><?php echo isset($STORAGE['p_notes']) ? htmlspecialchars(stripslashes(@$STORAGE['p_notes'])) : htmlspecialchars(stripslashes(@$CLIENT['p_notes'])); ?></textarea>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								} 
								?>
							</div><!-- /.tabpanel -->
							<?php
							$message = null;
							$front_messages = __('front_messages', true, false);
							?>
							<div role="tabpanel" class="fdDelivery" style="display: <?php echo @$STORAGE['type'] == 'delivery' ? NULL : 'none'; ?>">
								<div class="form-group">
									<label for="" class="col-sm-4 control-label"><?php __('front_location'); ?></label>
									<div class="col-sm-8" style="display:<?php echo count($tpl['location_arr']) != 1 ? ' block' : ' none';?>">
										<select name="d_location_id" class="form-control" data-err="<?php __('front_delivery_area_required'); ?>">
											<?php
											if(count($tpl['location_arr']) != 1)
											{
												?>
												<option value="">-- <?php __('front_choose'); ?> --</option>
												<?php
											}
											foreach ($tpl['location_arr'] as $location)
											{
												if(@$STORAGE['d_location_id'] == $location['id'])
												{
													$message = str_replace("{LOCATION}", $location['name'], $front_messages[11]);
												}
												?><option value="<?php echo $location['id']; ?>"<?php echo @$STORAGE['d_location_id'] == $location['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($location['name']); ?></option><?php
											}
											?>
										</select>
										<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
									</div>
									<div class="col-sm-8" style="display: <?php echo count($tpl['location_arr']) == 1 ? ' block' : ' none';?>">
										<label class="control-label"><?php echo pjSanitize::html($tpl['location_arr'][0]['name']);?></label>
									</div>
								</div><!-- /.form-group -->
								<?php
								if(count($tpl['location_arr']) != 1)
								{ 
									?>
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<?php __('front_delivery_info'); ?>
										</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
									</div><!-- /.row -->
									<?php
								}else{
									$message = str_replace("{LOCATION}", $tpl['location_arr'][0]['name'], $front_messages[11]);
								}
								?>
								<div class="row" style="display:<?php echo $message == null ? ' none' : ' block';?>">
									<div class="col-md-12 col-sm-12 col-xs-12 fdDeliveryNote"><?php echo $message;?></div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
								</div><!-- /.row -->
								<br/>
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<div id="fdDeliveryMap_<?php echo $index; ?>" style="height: 300px;"></div>
									</div><!-- /.col-md-12 col-sm-12 col-xs-12 -->
									<br />
								</div><!-- /.row -->
								<br/>
								<div id="fdDeliveryDateTime_<?php echo $index;?>" class="form-group" style="display:<?php echo (isset($STORAGE['d_date']) && !empty($STORAGE['d_date']) || $message != null) ? ' block' : ' none'; ?>;">
									<label for="" class="col-sm-4 control-label"><?php __('front_delivery_datetime'); ?></label>

									<div class="col-sm-8">
										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xss-12">
												<div class="input-group date">
													<span class="input-group-addon pjFdCursorPointer">
														<i class="fa fa-calendar"></i>
													</span>
													<input type="text" name="d_date" id="fd_d_date_<?php echo $index; ?>" class="form-control" value="<?php echo isset($STORAGE['d_date']) ? htmlspecialchars(stripslashes(@$STORAGE['d_date'])) : date($tpl['option_arr']['o_date_format'], time()); ?>" data-dformat="<?php echo $jqDateFormat; ?>" data-fday="<?php echo $week_start; ?>" data-err="<?php __('front_delivery_date_required'); ?>" />
												</div><!-- /.input-group date -->
												<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
											</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-6 -->
											
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-xss-12">
												<div class="fdDeliveryTime">
													<?php
													$d_time = isset($STORAGE['d_time']) ? $STORAGE['d_time'] : NULL;
													
													if(count($tpl['location_arr']) != 1 || ($d_time != NULL) )
													{
														$start_time = strtotime(date('Y-m-d 00:00:00'));
														$end_time = strtotime(date('Y-m-d 23:45:00'));
														
														if (isset($tpl['wt_arr']))
														{
															$start_time = strtotime($date . ' ' . $tpl['wt_arr']['start_hour'] . ':' . $tpl['wt_arr']['start_minutes'] . ':00');
															$end_time = strtotime($date . ' ' . $tpl['wt_arr']['end_hour'] . ':' . $tpl['wt_arr']['end_minutes'] . ':00');
														}
														if($end_time < $start_time)
														{
															$end_time += 86400;
														}
														$midnight = strtotime($tpl['date'] . ' 23:59:59');
														
														$interval = 900;
														$next = pjUtil::getCurrentTimeSnap15Minutes();
														if($start_time < $next)
														{
															$start_time = $next;
														}
														?>
														<select name="d_time" class="form-control">
															<option value="asap"><?php __('front_asap');?></option>
															<?php
															for($i = $start_time; $i <= $end_time; $i += $interval)
															{
																$iso_time = date($tpl['option_arr']['o_time_format'], $i);
																$time_text = $iso_time;
																if($i > $midnight )
																{
																	$time_text .= ' (' . __('front_next_day', true) . ')';
																}
																?><option value="<?php echo $iso_time;?>"<?php echo $iso_time == $d_time ? ' selected="selected"' : null; ?>><?php echo $time_text;?></option><?php	
															} 
															?>
														</select>
														<?php
													} 
													?>
												</div>
											</div><!-- /.col-lg-6 col-md-6 col-sm-6 col-xs-6 -->
										</div><!-- /.row -->
									</div>
								</div><!-- /.form-group -->
								<?php
								if(isset($tpl['order_arr']) && count($tpl['order_arr']) > 0)
								{ 
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_select_previous'); ?></label>
										<div class="col-sm-8">
											<select id="fdPreviousAddr_<?php echo $index;?>" name="previous_address" class="form-control">
												<option value="" data-add1="" data-add2="" data-city="" data-state="" data-zip="" data-country="">-- <?php __('front_choose'); ?> --</option>
												<?php
												foreach($tpl['order_arr'] as $v)
												{
													$order_detail = __('front_order', true, false) . ' ' . (!empty($v['uuid']) ? $v['uuid'] : strtotime($v['created'])) . ' ' . __('front_from', true, false) . ' ' . date($tpl['option_arr']['o_date_format'] . ', ' . $tpl['option_arr']['o_time_format'], strtotime($v['created']));
													?><option value="<?php echo $v['id']; ?>" data-add1="<?php echo pjSanitize::clean(@$v['d_address_1']);?>" data-add2="<?php echo pjSanitize::clean(@$v['d_address_2']);?>" data-city="<?php echo pjSanitize::clean(@$v['d_city']);?>" data-state="<?php echo pjSanitize::clean(@$v['d_state']);?>" data-zip="<?php echo pjSanitize::clean(@$v['d_zip']);?>" data-country="<?php echo pjSanitize::clean(@$v['d_country_id']);?>"><?php echo stripslashes($order_detail); ?></option><?php
												}
												?>
											</select>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_address_1'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_address_line_1'); ?></label>
										<div class="col-sm-8">
											<input type="text" name="d_address_1" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_address_1'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_address_1']) ? htmlspecialchars(stripslashes(@$STORAGE['d_address_1'])) : htmlspecialchars(stripslashes(@$CLIENT['c_address_1'])); ?>" data-err="<?php __('front_address1_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_address_2'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_address_line_2'); ?></label>
										<div class="col-sm-8">
											<input type="text" name="d_address_2" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_address_2'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_address_2']) ? htmlspecialchars(stripslashes(@$STORAGE['d_address_2'])) : htmlspecialchars(stripslashes(@$CLIENT['c_address_2'])); ?>" data-err="<?php __('front_address2_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_city'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_city'); ?></label>
										<div class="col-sm-8">
											<input type="text" name="d_city" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_city'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_city']) ? htmlspecialchars(stripslashes(@$STORAGE['d_city'])) : htmlspecialchars(stripslashes(@$CLIENT['c_city'])); ?>" data-err="<?php __('front_city_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_state'], array(2, 3)))
								{ 
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_state'); ?></label>
										<div class="col-sm-8">
											<input type="text" name="d_state" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_state'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_state']) ? htmlspecialchars(stripslashes(@$STORAGE['d_state'])) : htmlspecialchars(stripslashes(@$CLIENT['c_state'])); ?>" data-err="<?php __('front_state_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_zip'], array(2, 3)))
								{ 
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_zip'); ?></label>
										<div class="col-sm-8">
											<input type="text" name="d_zip" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_zip'] === 3 ? ' fdRequired' : NULL; ?>" value="<?php echo isset($STORAGE['d_zip']) ? htmlspecialchars(stripslashes(@$STORAGE['d_zip'])) : htmlspecialchars(stripslashes(@$CLIENT['c_zip'])); ?>" data-err="<?php __('front_zip_required');?>"/>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_country'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_country'); ?></label>
										<div class="col-sm-8">
											<select name="d_country_id" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_country'] === 3 ? ' fdRequired' : NULL; ?>" data-err="<?php __('front_country_required');?>">
												<option value="">-- <?php __('front_choose'); ?> --</option>
												<?php
												foreach ($tpl['country_arr'] as $country)
												{
												    ?><option value="<?php echo $country['id']; ?>"<?php echo isset($STORAGE['d_country_id']) ? (@$STORAGE['d_country_id'] == $country['id'] ? ' selected="selected"' : NULL) : (@$CLIENT['c_country'] == $country['id'] ? ' selected="selected"' : NULL); ?>><?php echo stripslashes($country['country_title']); ?></option><?php
												}
												?>
											</select>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								}
								if (in_array($tpl['option_arr']['o_df_include_notes'], array(2, 3)))
								{
									?>
									<div class="form-group">
										<label for="" class="col-sm-4 control-label"><?php __('front_special_instructions'); ?></label>
										<div class="col-sm-8">
											<textarea name="d_notes" rows="4" class="form-control<?php echo (int) $tpl['option_arr']['o_df_include_notes'] === 3 ? ' fdRequired' : NULL; ?>" data-err="<?php __('front_special_required');?>"><?php echo isset($STORAGE['d_notes']) ? htmlspecialchars(stripslashes(@$STORAGE['d_notes'])) : htmlspecialchars(stripslashes(@$CLIENT['d_notes'])); ?></textarea>
											<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
										</div>
									</div><!-- /.form-group -->
									<?php
								} 
								?>
							</div><!-- /.tabpanel -->
						</div><!-- /.tab-content -->
						
						<?php					
						$button_class = ' fdButtonGetLogin';
						if($controller->isFrontLogged())
						{
							$button_class = ' fdButtonGetCategories'; 
						}
						?>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left">
								<a href="#" class="btn btn-default text-uppercase<?php echo $button_class;?>"><?php __('front_button_back');?></a>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
								<a href="#" class="btn btn-default text-uppercase fdButtonRefPickup fdButtonPostPrice<?php echo ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart'])) > 0 ? null : ' fdButtonDisabled';?>" style="display:<?php echo $isPickup ? '' : 'none';?>"><?php __('front_button_continue');?></a>
								<a href="#" class="btn btn-default text-uppercase fdButtonRefDelivery fdButtonPostPrice<?php echo ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart'])) > 0 ? null : ' fdButtonDisabled';?>" style="display:<?php echo $isDelivery ? '' : 'none';?>"><?php __('front_button_continue');?></a>
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