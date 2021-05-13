<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminOrders extends pjAdmin
{
	public function pjActionCheckClientEmail()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!$this->_get->toString('c_email'))
			{
				echo 'false';
				exit;
			}

			$pjClientModel = pjAuthUserModel::factory()
				->join("pjClient", 't2.foreign_id = t1.id', 'left outer')
				->where('t1.email', $this->_get->toString('c_email'));
			
			echo $pjClientModel->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionIndex()
	{
	    $this->checkLogin();

	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	    $this->appendJs('pjAdminOrders.js');
	}
	
	public function pjActionGetOrder()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjOrderModel = pjOrderModel::factory()
			->where('t1.deleted_order', 0)
			->join('pjClient', "t2.id=t1.client_id", 'left outer')
			->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer');
            

				
			if ($q = $this->_get->toString('q'))
			{
				$pjOrderModel->where("(t1.id='$q' OR t1.uuid = '$q' OR t3.name LIKE '%$q%' OR t3.email LIKE '%$q%')");
			}
			if ($this->_get->toString('status'))
			{
			    $status = $this->_get->toString('status');
			    if(in_array($status, array('confirmed','cancelled','pending','delivered')))
			    {
			        $pjOrderModel->where('t1.status', $status);
			    }
			}
			if ($this->_get->toString('type'))
			{
			    $type = $this->_get->toString('type');
			    if(in_array($type, array('pickup','delivery')))
			    {
			        $pjOrderModel->where('t1.type', $type);
			    }
			    if(in_array($type, array('confirmed','cancelled','pending','delivered')))
			    {
			        $pjOrderModel->where('t1.status', $type);
			    }
			}
			if ($client_id = $this->_get->toInt('client_id'))
			{
			    $pjOrderModel->where('t1.client_id', $client_id);
			}
			$column = 'created';
			$direction = 'DESC';
			if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
			    $column = $this->_get->toString('column');
			    $direction = strtoupper($this->_get->toString('direction'));
			}

			$total = $pjOrderModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = array();
			
			$data = $pjOrderModel
				->select("t1.*, t3.name as client_name, t2.c_type,
							AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,	
							AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
							AES_DECRYPT(t1.cc_exp, '".PJ_SALT."') AS `cc_exp`,
							AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
				->orderBy("$column $direction")
				->limit($rowCount, $offset)
				->findAll()
				->getData();

            //echo "<pre>";print_r($data[0]['p_dt']); echo "</pre>";
            // exit;
            
            // $this->set('data',$data);
			foreach($data as $k => $v)
			{
				$data[$k]['address'] = $v['d_address_1'].' '.$v['d_address_2']. ' '.$v['d_city'];
				//$data[$k]['post_code'] = 'post_code';
				//$data[$k]['address'] = pjOrder::
				$data[$k]['c_type'] = $v['c_type'];
				// $data[$k]['call_start'] = 'call_start';
				// $data[$k]['call_end'] = 'call_end';
				//$data[$k]['sms_email'] = 'SMS Email';
				// $data[$k]['order_despatched'] = '1';

				$v['d_dt'] == "" ? $data[$k]['excpected_delivery'] = $v['p_dt'] : $data[$k]['excpected_delivery'] = $v['d_dt'];
				//$data[$k]['excpected_delivery'] = $v['d_dt'];
				//$v['d_dt'] == NULL ? echo "Empty delivery" : echo "delivery is there";
				//$data[$k]['sms_sent_time'] = 'sms_sent_time';
				// $data[$k]['delivered_customer'] = 'delivered_customer';
				//print_r(explode("",$v['delivered_time']));
				//$data[$k]['delivered_time'] = $var[0];
				
				$data[$k]['total'] = pjCurrency::formatPrice($v['total']);
				// if($v['type'] == 'delivery')
				// {
				// 	if($v['d_asap'] == 'F')
				// 	{
				// 	    $data[$k]['datetime'] = date($this->option_arr['o_date_format'] . ', ' . $this->option_arr['o_time_format'], strtotime($v['d_dt']));
				// 	}else{
				// 	    $data[$k]['datetime'] = date($this->option_arr['o_date_format'], strtotime($v['d_dt'])) . ', ' . __('lblAsap', true);
				// 	}
				// }else if($v['type'] == 'pickup'){
				// 	if($v['p_asap'] == 'F')
				// 	{
				// 	    $data[$k]['datetime'] = date($this->option_arr['o_date_format'] . ', ' . $this->option_arr['o_time_format'], strtotime($v['p_dt']));
				// 	}else{
				// 	    $data[$k]['datetime'] = date($this->option_arr['o_date_format'], strtotime($v['p_dt'])) . ', ' . __('lblAsap', true);
				// 	}
				// }
				$data[$k]['client_name'] = pjSanitize::clean($v['client_name']);
			}
			//echo "<pre>";print_r($data); echo "</pre>";
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionSaveOrder()
	{
	    $this->setAjax(true);
	    
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    
	    if (!pjAuth::factory($this->_get->toString('controller'), 'pjActionUpdate')->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    $pjOrderModel = pjOrderModel::factory();
	    $arr = $pjOrderModel->find($this->_get->toInt('id'))->getData();

	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Order not found.'));
	    }
	    $pjOrderModel->reset()->where('id', $this->_get->toInt('id'))->limit(1)->modifyAll(array($this->_post->toString('column') => $this->_post->toString('value')));
	    self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Order has been updated.'));
	    exit;
	}
	
	public function pjActionExportOrder()
	{
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    if (!pjAuth::factory()->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    if (!$this->_post->has('record'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $record = $this->_post->toArray('record');
	    if (empty($record))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $arr = pjOrderModel::factory()->whereIn('id', $record)->findAll()->getData();
	    $csv = new pjCSV();
	    $csv
	    ->setHeader(true)
	    ->setName("Orders-".time().".csv")
	    ->process($arr)
	    ->download();
		$this->checkLogin();
		exit;
	}
	
	public function pjActionDeleteOrder()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    if (!pjAuth::factory()->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    if (!($this->_get->toInt('id')))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $pjOrderModel = pjOrderModel::factory();
	    $arr = $pjOrderModel->find($this->_get->toInt('id'))->getData();
	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Order not found.'));
	    }
	    $id = $this->_get->toInt('id');
	    // if ($pjOrderModel->reset()->setAttributes(array('id' => $id))->erase()->getAffectedRows() == 1)
	    if (pjOrderModel::factory()->where('id',$id)->modifyAll(array(
	            'deleted_order' => ":IF(`deleted_order`='0','1','0')"
	        ))->getAffectedRows() == 1)
	    {
	        //pjOrderItemModel::factory()->where('order_id', $id)->eraseAll();
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Order has been deleted'));
	    }else{
	        self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Order has not been deleted.'));
	    }
	    exit;
	}
	
	public function pjActionDeleteOrderBulk()
	{
	    $this->setAjax(true);
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    if (!pjAuth::factory()->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    if (!$this->_post->has('record'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $record = $this->_post->toArray('record');
	    if (empty($record))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    //pjOrderModel::factory()->whereIn('id', $record)->eraseAll();
	    //pjOrderItemModel::factory()->whereIn('order_id', $record)->eraseAll();
	    pjOrderModel::factory()->whereIn('id',$record)->modifyAll(array(
	            'deleted_order' => ":IF(`deleted_order`='0','1','0')"
	        ));
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Order(s) has been deleted.'));
	}
	
	public function pjActionFormatPrice()
	{
	    $this->setAjax(true);
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    $prices = $this->_post->toArray('prices');
	    if (empty($prices))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    foreach($prices as $index => $price)
	    {
	        $prices[$index] = pjCurrency::formatPrice($price);
	    }
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'prices' => $prices));
	}
	
	public function pjActionCreate()
	{
	    $this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    if (self::isPost() && $this->_post->toInt('order_create'))
	    {
	    	$post_total = $this->getTotal();
	    	
	        $post = $this->_post->raw();
	        $data = array();
	        $data['uuid'] = time();
	        $data['ip'] = pjUtil::getClientIp();
			$data['locale_id'] = $this->getLocaleId();
			$data['call_start'] = $this->_post->toString('call_start');
			$data['call_end'] = date('h:i:s A');
			$data['post_code'] = $this->_post->toString('post_code');
			$data['phone_no'] = $this->_post->toInt('phone_no');
			$data['surname'] = $this->_post->toString('surname');
			$data['sms_email'] = $this->_post->toString('sms_email');
			$data['first_name'] = $this->_post->toString('c_name');
			$data['chef_id'] = $this->session->getData('chef');
			$data['mobile_delivery_info'] = $this->_post->toBool('mobile_delivery_info');
			$data['mobile_offer'] = $this->_post->toBool('mobile_offer');
			$data['email_delivery_info'] = $this->_post->toBool('email_delivery_info');
			$data['email_receipt'] = $this->_post->toBool('email_receipt');
			$data['email_offer'] = $this->_post->toBool('email_offer');
			$data['preparation_time'] = $this->_post->toInt('preparation_time');
			// print_r($data);
			// exit;

	            $c_data = array();
	            $c_data['c_title'] = $this->_post->toString('c_title');
	            $c_data['c_name'] = $this->_post->toString('c_name');
	            
	            //$c_data['c_password'] = $this->_post->toString('c_password');
	            $c_data['c_phone'] = $this->_post->toString('phone_no');
	            //$c_data['c_company'] = $this->_post->toString('c_company');
	            $c_data['c_address_1'] = $this->_post->toString('d_address_1');
	            $c_data['c_address_2'] = $this->_post->toString('d_address_2');
	            $c_data['c_city'] = $this->_post->toString('d_city');
	            $c_data['password'] = $c_data['c_name'].$data['uuid'];
	            //$c_data['c_state'] = $this->_post->toString('d_state');
	            //$c_data['c_zip'] = $this->_post->toString('d_zip');
	            //$c_data['c_country'] = $this->_post->toInt('d_country_id');
	            $c_data['status'] = 'T';
	            //$c_data['postcode'] = $this->_post->toString('post_code');
	            $c_data['locale_id'] = $this->getLocaleId();
	            $client_exist = pjClientModel::factory()
	            ->join("pjAuthUser", "t2.id = t1.foreign_id", "left")
	            ->select("t1.*, t2.phone")
	            ->where("t2.phone",$c_data['c_phone'])
	            ->findAll()
	            ->getData();
	            
	            if (!empty($client_exist)) {
	            	
	            	
	            	$data['client_id'] = $client_exist[0]['id'];
	            	$post_clientType = $this->getClientType($data);
	            	// $c_data['c_type'] = $post_clientType;
	            	$modify = pjClientModel::factory()
			        ->where('id', $data['client_id'])
			        ->modifyAll(array(
			            'c_type' => $post_clientType
			        ))->getAffectedRows();
	            	
	            } else {
	            	$c_data['c_email'] = $this->_post->toString('sms_email');
	            	$c_data['c_type'] = "New";

	            	
	            	
	            	$response = pjFrontClient::init($c_data)->createClient();
		            if(isset($response['client_id']) && (int) $response['client_id'] > 0)
		            {
		                $data['client_id'] = $response['client_id'];

		            }
	            }
	            
	        //}
	        if($this->_post->check('is_paid'))
	        {
	            $data['is_paid'] = 1;
	        }else{
	            $data['is_paid'] = 0;
	        }
	        
	        if($this->_post->check('type'))
	        {
	            $data['type'] = 'delivery';
	            if (!empty($post['d_date']) && !empty($post['delivery_time']))
	            {   
	            	$data['d_time'] = $this->_post->toInt('d_time');
	                $d_date = $post['d_date'];

	                //$d_date = 2021-04-31;

	                $d_time = $post['delivery_time'];
	                if(count(explode(" ", $d_time)) == 2)
	                {
	                    list($_time, $_period) = explode(" ", $d_time);
	                    $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
	                }else{
	                   
	                    $time = pjDateTime::formatTime($d_time, $this->option_arr['o_time_format']);
	                }
	               
	                $data['d_dt']=pjDateTime::formatDate($d_date,$this->option_arr['o_date_format']) . ' ' . $time;
	                
	            }
	            if ($this->_post->toInt('d_location_id'))
	            {
	                $data['location_id'] = $this->_post->toInt('d_location_id');
	            }
	        }else{
	            $data['type'] = 'pickup';
	            if (!empty($post['p_date']) && !empty($post['pickup_time']))
	            {   
	            	$data['p_time'] = $this->_post->toInt('p_time');
	                $p_date = $post['p_date'];
	                $p_time = $post['pickup_time'];

	                
	                if(count(explode(" ", $p_time)) == 2)
	                {
	                    list($_time, $_period) = explode(" ", $p_time);
	                    $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
	                }else{
	                   
	                    $time = pjDateTime::formatTime($p_time, $this->option_arr['o_time_format']);
	                }
	                $data['p_dt']=pjDateTime::formatDate($p_date,$this->option_arr['o_date_format']) . ' ' . $time;

	            }
	            if ($this->_post->toInt('p_location_id'))
	            {
	                $data['location_id'] = $this->_post->toInt('p_location_id');
	            }
	        }
	        if ($this->_post->toString('payment_method') == 'creditcard')
	        {
	            $data['cc_exp'] = $this->_post->toString('cc_exp_month') . "/" . $this->_post->toString('cc_exp_year');
			}
			// print_r($data);
			// exit;
			
	        $id = pjOrderModel::factory(array_merge($post, $data, $post_total))->insert()->getInsertId();
	        //print_r($id);
	        $order_id = "T".$id;
	        pjOrderModel::factory()
		        ->where('id', $id)
		        ->modifyAll(array(
		            'order_id' => $order_id
		        ));
	        if ($id !== false && (int) $id > 0)
	        {
	            if (isset($post['product_id']) && count($post['product_id']) > 0)
	            {
	                $pjOrderItemModel = pjOrderItemModel::factory();
	                $pjProductPriceModel = pjProductPriceModel::factory();
	                $pjProductModel = pjProductModel::factory();
	                $pjExtraModel = pjExtraModel::factory();
	                
	                foreach ($post['product_id'] as $k => $pid)
	                {
	                    $product = $pjProductModel
	                    ->reset()
	                    ->find($pid)
	                    ->getData();
	                    if (strpos($k, 'new_') === 0)
	                    {
	                        $price = 0;
	                        $price_id = ":NULL";
	                        
	                        if($product['set_different_sizes'] == 'T')
	                        {
	                            $price_id = $post['price_id'][$k];
	                            $price_arr = $pjProductPriceModel
	                            ->reset()
	                            ->find($price_id)
	                            ->getData();
	                            if($price_arr)
	                            {
	                                $price = $price_arr['price'];
	                            }
	                        }else{
	                            $price = $product['price'];
	                        }
	                        
	                        $hash = md5(uniqid(rand(), true));
	                        $oid = $pjOrderItemModel
	                        ->reset()
	                        ->setAttributes(array(
	                            'order_id' => $id,
	                            'foreign_id' => $pid,
	                            'type' => 'product',
	                            'hash' => $hash,
	                            'price_id' => $price_id,
	                            'price' => $price,
	                            'cnt' => $post['cnt'][$k],
	                            'special_instruction' => $post['special_instruction']
	                        ))
	                        ->insert()
	                        ->getInsertId();
	                        if ($oid !== false && (int) $oid > 0)
	                        {
	                            if (isset($post['extra_id']) && isset($post['extra_id'][$k]))
	                            {
	                                foreach ($post['extra_id'][$k] as $i => $eid)
	                                {
	                                    $extra_price = 0;
	                                    $extra_arr = $pjExtraModel
	                                    ->reset()
	                                    ->find($eid)
	                                    ->getData();
	                                    if(!empty($extra_arr) && !empty($extra_arr['price']))
	                                    {
	                                        $extra_price = $extra_arr['price'];
	                                    }
	                                    $pjOrderItemModel
	                                    ->reset()
	                                    ->setAttributes(array(
	                                        'order_id' => $id,
	                                        'foreign_id' => $eid,
	                                        'type' => 'extra',
	                                        'hash' => $hash,
	                                        'price_id' => ':NULL',
	                                        'price' => $extra_price,
	                                        'cnt' => $post['extra_cnt'][$k][$i],
	                                        'special_instruction' => $post['special_instruction']
	                                    ))
	                                    ->insert();
	                                }
	                            }
	                        }
	                    }
	                }
	            }
	            $err = 'AR03';
	        }else{
	            $err = 'AR04';
	        }
	        pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminOrders&action=pjActionIndex&err=$err");
	    }
	    if (self::isGet())
	    {
	        $country_arr = pjBaseCountryModel::factory()
	        ->select('t1.id, t2.content AS country_title')
	        ->join('pjBaseMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
	        ->orderBy('`country_title` ASC')->findAll()->getData();
	        $this->set('country_arr', $country_arr);
	        
	        $product_arr = pjProductModel::factory()
	        ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
	        ->select("t1.*, t2.content AS name, (SELECT COUNT(*) FROM `".pjProductExtraModel::factory()->getTable()."` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras")
	        ->orderBy("name ASC")
	        ->findAll()
	        ->getData();
			$this->set('product_arr', $product_arr);

            //print_r($product_arr);
            
            // MEGAMIND

            $client_info = pjOrderModel::factory()
            ->join('pjClient', "t2.id = t1.client_id")
            ->select('t1.id, t1.phone_no, t1.surname, t1.sms_email, t1.post_code, t1.d_address_1, t1.d_address_2, t1.d_city, t1.first_name, t1.client_id, t1.kprint, t2.c_title, t1.type, t1.is_paid, t1.order_despatched, t1.mobile_delivery_info, t1.mobile_offer, t1.email_delivery_info, t1.email_offer, t1.email_receipt, t1.created, t1.preparation_time')
            ->findAll()
            ->getData();
            $this->set('client_info', $client_info);

            $chef_arr = pjAuthUserModel::factory()
            ->select("t1.id, t1.name")
            ->where('role_id', '5')
            ->findAll()
            ->getData();
            $this->set('chef_arr', $chef_arr); 

            // !MEGAMIND
			
			$category_arr = pjCategoryModel::factory()
	        ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
	        ->select("t1.*, t2.content AS name")
	        ->orderBy("t1.order ASC")
	        ->findAll()
			->getData();
			$this->set('category_arr', $category_arr);
            $category_list = [];
			foreach($category_arr as $category) {
				$category_list[$category['id']] = $category['name'];
			}
			$this->set('category_list', $category_list);
			
	        $location_arr = pjLocationModel::factory()
	        ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
	        ->select("t1.*, t2.content AS name")
	        ->orderBy("name ASC")
	        ->findAll()
	        ->getData();
	        $this->set('location_arr', $location_arr);

	        

            // $prdExtras = [];
            // foreach ($product_arr as $product) {
            //     foreach ($extras_arr as $extra) {
                
            //         if ($extra['product_id'] == $product['id']) {
            //            $prdExtras[] = $extra['extra_id'];
            //            //$product_arr[] = $prodExtras[];
            //         }
            //     }
            // }       

            //$this->set('prdExtras', $prdExtras);     
	        
	        $client_arr = pjClientModel::factory()
	        ->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
	        ->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
	        ->where('t2.status', 'T')
	        ->orderBy('t2.name ASC')
	        ->findAll()
	        ->getData();
	        $this->set('client_arr', $client_arr);
	        
	        $this->appendCss('jquery.bootstrap-touchspin.min.css', PJ_THIRD_PARTY_PATH . 'touchspin/');
	        $this->appendJs('jquery.bootstrap-touchspin.min.js', PJ_THIRD_PARTY_PATH . 'touchspin/');
	        $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
	        $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
	        $this->appendJs('moment-with-locales.min.js', PJ_THIRD_PARTY_PATH . 'moment/');
	        $this->appendCss('clockpicker.css', PJ_THIRD_PARTY_PATH . 'clockpicker/');
	        $this->appendJs('clockpicker.js', PJ_THIRD_PARTY_PATH . 'clockpicker/');
	        $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
	        $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
	        $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('additional-methods.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendCss('bootstrap-select.min.css', PJ_THIRD_PARTY_PATH . 'bootstrap_select/1.13.18/css/');
			$this->appendJs('bootstrap-select.min.js', PJ_THIRD_PARTY_PATH . 'bootstrap_select/1.13.18/js/');
	        $this->appendJs('pjAdminOrders.js');
	    }
	}
	
	public function pjActionUpdate()
	{
	    $this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    if (self::isPost() && $this->_post->toInt('order_update') && $this->_post->toInt('id'))
	    {
	        $pjOrderModel = pjOrderModel::factory();
	        $pjOrderItemModel = pjOrderItemModel::factory();
	        $pjProductPriceModel = pjProductPriceModel::factory();
	        $pjExtraModel = pjExtraModel::factory();
	        $pjProductModel = pjProductModel::factory();
	        
	        $id = $this->_post->toInt('id');
	        $post = $this->_post->raw();
	        $arr = $pjOrderModel->find($id)->getData();
	        if (empty($arr))
	        {
	            pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOrders&action=pjActionIndex&err=AR08");
	        }
	        
	        $new_client_id = NULL;
	        $c_data['phone'] = $this->_post->toInt('phone_no');
	        $isExist = $pjOrderModel
	                   ->select('t1.*')
	                   ->where('phone_no', $c_data['phone'])
	                   ->findAll()
	                   ->getData();
	        // echo "<pre>";print_r($post);
	        // exit;           
	        if(!$isExist)
	        {   print_r($isExist);
	            exit;  
	            $c_data = array();
	            $c_data['c_title'] = $this->_post->toString('c_title');
	            $c_data['c_name'] = $this->_post->toString('c_name');
	            $c_data['c_email'] = $this->_post->toString('sms_email');
	            //c_data['c_password'] = $this->_post->toString('c_password');
	            //$c_data['c_phone'] = $this->_post->toString('phone_no');
	            //$c_data['c_company'] = $this->_post->toString('c_company');
	            $c_data['c_address_1'] = $this->_post->toString('d_address_1');
	            $c_data['c_address_2'] = $this->_post->toString('d_address_2');
	            $c_data['c_city'] = $this->_post->toString('d_city');
	            $c_data['c_state'] = $this->_post->toString('d_state');
	            //$c_data['c_zip'] = $this->_post->toString('d_zip');
	            //$c_data['c_country'] = $this->_post->toInt('d_country_id');
	            $c_data['status'] = 'T';
	            $c_data['post_code'] = $this->_post->toString('post_code');
	            $c_data['locale_id'] = $this->getLocaleId();
	            $response = pjFrontClient::init($c_data)->createClient();
	            if(isset($response['client_id']) && (int) $response['client_id'] > 0)
	            {
	                $new_client_id = $response['client_id'];
	            }
	        }
	       
	        if (isset($post['product_id']) && count($post['product_id']) > 0)
	        {
	            $keys = array_keys($post['product_id']);
	            $pjOrderItemModel->reset()->where('order_id', $id)->whereNotIn('hash', $keys)->eraseAll();
	            
	            $pjOrderItemModel->reset()->where('order_id', $id)->where('type', 'extra')->eraseAll();
	            
	            foreach ($post['product_id'] as $k => $pid)
	            {
	                $product = $pjProductModel->reset()->find($pid)->getData();
	                $price = 0;
	                $price_id = ":NULL";

	                // print_r($k);
	                //     exit;
	                
	                if($product['set_different_sizes'] == 'T')
	                {
	                    $price_id = $post['price_id'][$k];
	                    $price_arr = $pjProductPriceModel->reset()->find($price_id)->getData();
	                    if($price_arr)
	                    {
	                        $price = $price_arr['price'];
	                    }
	                }else{
	                    $price = $product['price'];
	                }
	                if (strpos($k, 'new_') === 0)
	                {
	                    $hash = md5(uniqid(rand(), true));
	                    $oid = $pjOrderItemModel
	                    ->reset()
	                    ->setAttributes(array(
	                        'order_id' => $id,
	                        'foreign_id' => $pid,
	                        'type' => 'product',
	                        'hash' => $hash,
	                        'price_id' => $price_id,
	                        'price' => $price,
	                        'cnt' => $post['cnt'][$k],
	                        'special_instruction' => $post['special_instruction'][$k]
	                    ))
	                    ->insert()
	                    ->getInsertId();
                        
	                    // print_r($post);
	                    // exit;
	                    
	                    if ($oid !== false && (int) $oid > 0)
	                    {
	                        if (isset($post['extra_id']) && isset($post['extra_id'][$k]))
	                        {
	                            foreach ($post['extra_id'][$k] as $i => $eid)
	                            {
	                                $extra_price = 0;
	                                $extra_arr = $pjExtraModel->reset()->find($eid)->getData();
	                                if(!empty($extra_arr) && !empty($extra_arr['price']))
	                                {
	                                    $extra_price = $extra_arr['price'];
	                                }
	                                $pjOrderItemModel
	                                ->reset()
	                                ->setAttributes(array(
	                                    'order_id' => $id,
	                                    'foreign_id' => $eid,
	                                    'type' => 'extra',
	                                    'hash' => $hash,
	                                    'price_id' => ':NULL',
	                                    'price' => $extra_price,
	                                    'cnt' => $post['extra_cnt'][$k][$i],
	                                    'special_instruction' => $post['special_instruction']
	                                ))
	                                ->insert();
	                            }
	                        }
	                    }
	                    
	                } else {

	                    $pjOrderItemModel->reset()->where('hash', $k)->where('type', 'product')->limit(1)
	                    ->modifyAll(array(
	                        'foreign_id' => $pid,
	                        'cnt' => $post['cnt'][$k],
	                        'price_id' => $price_id,
	                        'price' => $price,
	                        'special_instruction' => $post['special_instruction'][$k] ,
	                    ));
	                    if (isset($post['extra_id']) && isset($post['extra_id'][$k]))
	                    {
	                        foreach ($post['extra_id'][$k] as $i => $eid)
	                        {
	                            $extra_price = 0;
	                            $extra_arr = $pjExtraModel->reset()->find($eid)->getData();
	                            if(!empty($extra_arr) && !empty($extra_arr['price']))
	                            {
	                                $extra_price = $extra_arr['price'];
	                            }
	                            
	                            $pjOrderItemModel
	                            ->reset()
	                            ->setAttributes(array(
	                                'order_id' => $id,
	                                'foreign_id' => $eid,
	                                'type' => 'extra',
	                                'hash' => $k,
	                                'price_id' => ':NULL',
	                                'price' => $extra_price,
	                                'cnt' => $post['extra_cnt'][$k][$i],
	                                'special_instruction' => $post['special_instruction']
	                            ))
	                            ->insert();
	                            
	                        }
	                    }
	                }
	            }
	        }
	        $data = array();
	        $data['ip'] = pjUtil::getClientIp();
	        if($this->_post->check('is_paid'))
	        {
	            $data['is_paid'] = 1;
	        }else{
	            $data['is_paid'] = 0;
	        }
	        if($this->_post->check('type'))
	        {
	            $data['type'] = 'delivery';
	            if (!empty($post['d_date']) && !empty($post['delivery_time']))
	            {
	                $d_date = $post['d_date'];
	                //$d_date = 2021-04-31;

	                $d_time = $post['delivery_time'];
	                if(count(explode(" ", $d_time)) == 2)
	                {
	                    list($_time, $_period) = explode(" ", $d_time);
	                    $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
	                }else{
	                   
	                    $time = pjDateTime::formatTime($d_time, $this->option_arr['o_time_format']);
	                }
	               
	                $data['d_dt']=pjDateTime::formatDate($d_date,$this->option_arr['o_date_format']) . ' ' . $time;
	                
	            }
	            if ($this->_post->toInt('d_location_id'))
	            {
	                $data['location_id'] = $this->_post->toInt('d_location_id');
	            }
	            unset($post['p_dt']);
	            $data['p_dt'] = ':NULL';
	        }else{
	            $data['type'] = 'pickup';
	            if (!empty($post['p_date']) && !empty($post['pickup_time']))
	            {
	                $p_date = $post['p_date'];
	                $p_time = $post['pickup_time'];

	                
	                if(count(explode(" ", $p_time)) == 2)
	                {
	                    list($_time, $_period) = explode(" ", $p_time);
	                    $time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
	                }else{
	                   
	                    $time = pjDateTime::formatTime($p_time, $this->option_arr['o_time_format']);
	                }
	                $data['p_dt']=pjDateTime::formatDate($p_date,$this->option_arr['o_date_format']) . ' ' . $time;

	            }
	            if ($this->_post->toInt('p_location_id'))
	            {
	                $data['location_id'] = $this->_post->toInt('p_location_id');
	            }
	            unset($post['d_dt']);
	            $data['d_dt'] = ':NULL';
	        }
	        $data['client_id'] = $new_client_id;
	        
	        $post_data = $this->getTotal();
	        
	        // print_r($post);
	        // exit;
	        
	        $pjOrderModel->reset()->where('id', $id)->limit(1)->modifyAll(array_merge($post, $data, $post_data));
	        
	        $err = 'AR01';
	        pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminOrders&action=pjActionIndex&err=$err");
	    }
	    if (self::isGet() && $this->_get->toInt('id'))
	    {
	        $id = $this->_get->toInt('id');
	        $arr = pjOrderModel::factory()
	        ->join('pjClient', "t2.id=t1.client_id", 'left outer')
	        ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
	        ->select("t1.*,t3.name as client_name, t2.c_title, t3.email as c_email, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip,t2.c_notes,
							AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
							AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
							AES_DECRYPT(t1.cc_exp, '".PJ_SALT."') AS `cc_exp`,
							AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`,
							AES_DECRYPT(t3.password, '".PJ_SALT."') AS `c_password`")
			->find($id)
			->getData();
			if(count($arr) <= 0)
			{
			    pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminOrders&action=pjActionIndex&err=AR08");
			}
			$this->set('arr', $arr);

			// MEGAMIND
			

            
            $category_arr = pjCategoryModel::factory()
	        ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
	        ->select("t1.*, t2.content AS name")
	        ->orderBy("t1.order ASC")
	        ->findAll()
			->getData();
			$this->set('category_arr', $category_arr);
            $category_list = [];
			foreach($category_arr as $category) {
				$category_list[$category['id']] = $category['name'];
			}
			$this->set('category_list', $category_list);

			$client_info = pjOrderModel::factory()
            ->join('pjClient', "t2.id = t1.client_id")
            ->select('t1.id, t1.phone_no, t1.surname, t1.sms_email, t1.post_code, t1.d_address_1, t1.d_address_2, t1.d_city, t1.first_name, t1.client_id, t1.kprint, t2.c_title, t1.type, t1.is_paid, t1.order_despatched, t1.mobile_delivery_info, t1.mobile_offer, t1.email_delivery_info, t1.email_offer, t1.email_receipt, t1.created, t1.preparation_time')
            ->findAll()
            ->getData();
            $this->set('client_info', $client_info);

            $chef_arr = pjAuthUserModel::factory()
            ->select("t1.id, t1.name")
            ->where('role_id', '5')
            ->findAll()
            ->getData();
            $this->set('chef_arr', $chef_arr); 

            $multilang = pjMultiLangModel::factory()
                         ->select('t1.*')
                         ->findAll()
                         ->getData();


           // print_r($multilang);
			// !MEGAMIND
			
			$country_arr = pjBaseCountryModel::factory()
			->select('t1.id, t2.content AS country_title')
			->join('pjBaseMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
			->orderBy('`country_title` ASC')->findAll()->getData();
			$this->set('country_arr', $country_arr);
			
			$product_arr = pjProductModel::factory()
			->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
			->select(sprintf("t1.*, t2.content AS name,
								(SELECT GROUP_CONCAT(extra_id SEPARATOR '~:~') FROM `%s` WHERE product_id = t1.id GROUP BY product_id LIMIT 1) AS allowed_extras,
								(SELECT COUNT(*) FROM `%s` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras", pjProductExtraModel::factory()->getTable(), pjProductExtraModel::factory()->getTable()))
			->orderBy("name ASC")
			->findAll()
			->toArray('allowed_extras', '~:~')
			->getData();
			$this->set('product_arr', $product_arr);
			
			$location_arr = pjLocationModel::factory()
			->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
			->select("t1.*, t2.content AS name")
			->orderBy("name ASC")
			->findAll()
			->getData();
			$this->set('location_arr', $location_arr);
			
			$extra_arr = pjExtraModel::factory()
			->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
			->select("t1.*, t2.content AS name")
			->orderBy("name ASC")
			->findAll()
			->getData();
			$this->set('extra_arr', $extra_arr);
			
			$pjProductPriceModel = pjProductPriceModel::factory();
			$oi_arr = array();
			$_oi_arr = pjOrderItemModel::factory()
			->where('t1.order_id', $arr['id'])
			->findAll()
			->getData();
			foreach ($_oi_arr as $item)
			{
			    if($item['type'] == 'product')
			    {
			        $item['price_arr'] = $pjProductPriceModel
			        ->reset()
			        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $this->getLocaleId()), 'left')
			        ->select("t1.*, t2.content AS price_name")
			        ->where('product_id', $item['foreign_id'])
			        ->findAll()
			        ->getData();
			    }
			    $oi_arr[] = $item;
			}
			
			$category = pjProductCategoryModel::factory()
			            ->select('t1.*')
			            ->findAll()
			            ->getData();
			// foreach ($category as $k => $v) {
			// 	print_r($v['product_id']);


			// }


			foreach ($oi_arr as $oi => $o) {
				foreach ($category as $k => $v) {
				if ($o['foreign_id'] == $v['product_id']) {
					//print_r($v['category_id']);
					$oi_arr[$oi]['category'] = $v['category_id'];
				}
			}}


			$this->set('oi_arr', $oi_arr);
			$spcl_ins = pjOrderItemModel::factory()
			->where('t1.order_id', $id)
			->findAll()
			->getData();
			$this->set('spcl_ins', $spcl_ins);
			//print_r($oi_arr[0]['foreign_id']);
			//print_r($category[0]['category_id']);
			// $desc_arr = pjProductModel::factory();
			// $pjMultiLangModel = pjMultiLangModel::factory();
			// $prodarr['i18n'] = $pjMultiLangModel->getMultiLang($product_id, 'pjProduct');
			// $desc_arr['description'] = $prodarr['i18n'][$this->getLocaleId()]['description'];
			// $this->set('desc_arr', $desc_arr);
			
			$client_arr = pjClientModel::factory()
			->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
			->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
			->orderBy('t2.name ASC')
			->findAll()
			->getData();
			$this->set('client_arr', $client_arr);
			
			$this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
			$this->appendCss('jquery.bootstrap-touchspin.min.css', PJ_THIRD_PARTY_PATH . 'touchspin/');
			$this->appendJs('jquery.bootstrap-touchspin.min.js', PJ_THIRD_PARTY_PATH . 'touchspin/');
			$this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
			$this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
			// $this->appendJs('moment-with-locales.min.js', PJ_THIRD_PARTY_PATH . 'moment/');
			$this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
	        $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			// $this->appendCss('build/css/bootstrap-datetimepicker.min.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datetimepicker/');
			// $this->appendJs('build/js/bootstrap-datetimepicker.min.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datetimepicker/');
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('additional-methods.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendCss('bootstrap-select.min.css', PJ_THIRD_PARTY_PATH . 'bootstrap_select/1.13.18/css/');
			$this->appendJs('bootstrap-select.min.js', PJ_THIRD_PARTY_PATH . 'bootstrap_select/1.13.18/js/');
			$this->appendJs('pjAdminOrders.js');
	    }
	}
	
	public function pjActionPrintOrder()
	{
		// print_r($this->_get->toString('hash'))
		// exit;
	    $this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }

	    $this->setLayout('pjActionPrint');
	    $id = $this->_get->toInt('id');
	    if($id = $this->_get->toInt('id'))
	    {
	    	
           // MEGAMIND

	    	$category_arr = pjCategoryModel::factory()
	        ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
	        ->select("t1.*, t2.content AS name")
	        ->orderBy("t1.order ASC")
	        ->findAll()
			->getData();

			$this->set('categories', $category_arr);
	
           $korder = pjOrderModel::factory()
           ->select("t1.*")
           ->find($id)
           ->getData();
           $korder['kprint'] = 1;

           $pjOrder = pjOrderModel::factory();
           $pjOrder->where('id',$id)->limit(1)->modifyAll($korder);

    	    $pjOrderModel = pjOrderModel::factory();

    		  // print_r("<pre>");print_r($korder);
    		  // exit;
    	   
     	    $arr = $pjOrderModel
    	    ->join('pjClient', "t2.id=t1.client_id", 'left outer')
    	    ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
    	    ->select("t1.*, t2.c_title, t3.email AS c_email, t3.name AS c_name, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes,
    						AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
    						AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
    						AES_DECRYPT(t1.cc_exp, '".PJ_SALT."') AS `cc_exp`,
    						AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
			->find($id)
    		->getData();

    		$this->set('arr', $arr);

    		$product_arr = pjProductModel::factory()
			->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
			->join('pjProductCategory', "t3.product_id = t1.id", 'left')
			->select(sprintf("t1.*, t2.content AS name, t3.category_id,
								(SELECT GROUP_CONCAT(extra_id SEPARATOR '~:~') FROM `%s` WHERE product_id = t1.id GROUP BY product_id LIMIT 1) AS allowed_extras,
								(SELECT COUNT(*) FROM `%s` AS TPE WHERE TPE.product_id=t1.id) as cnt_extras", pjProductExtraModel::factory()->getTable(), pjProductExtraModel::factory()->getTable()))
			->orderBy("name ASC")
			->findAll()
			->toArray('allowed_extras', '~:~')
			->getData();

			$this->set('product_arr', $product_arr);

			$pjProductPriceModel = pjProductPriceModel::factory();
			$oi_arr = array();
			$_oi_arr = pjOrderItemModel::factory()
			->where('t1.order_id', $arr['id'])
			->findAll()
			->getData();
			foreach ($_oi_arr as $item)
			{
			    if($item['type'] == 'product')
			    {
			        $item['price_arr'] = $pjProductPriceModel
			        ->reset()
			        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $this->getLocaleId()), 'left')
			        ->select("t1.*, t2.content AS price_name")
			        ->where('product_id', $item['foreign_id'])
			        ->findAll()
			        ->getData();
			    }
			    $oi_arr[] = $item;
			}
			$this->set('oi_arr', $oi_arr);
			
			$client_arr = pjClientModel::factory()
			->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
			->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
			->orderBy('t2.name ASC')
			->findAll()
			->getData();
			$this->set('client_arr', $client_arr);

			// !MEGAMIND

    		if (empty($arr))
    		{
    		    pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminOrders&action=pjActionIndex&err=AR08");
    		    // echo 'Array is empty';
    		    // exit;
    		}
    		
    		// $hash = sha1($arr['id'].$arr['created'].PJ_SALT);
    		// // print_r($hash);
		    // // exit;
    		// if($hash != $this->_get->toString('hash'))
    		// {
    		//     pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminOrders&action=pjActionIndex&err=AR08");
    		// }
    		
    		$locale_id = $this->getLocaleId();
    		if(isset($arr['locale_id']) && (int) $arr['locale_id']> 0)
    		{
    		    $locale_id = $arr['locale_id'];
    		}
    		pjAppController::addOrderDetails($arr, $locale_id);
    		
    		$pjMultiLangModel = pjMultiLangModel::factory();
    		$lang_template = $pjMultiLangModel
    		->reset()->select('t1.*')
    		->where('t1.model','pjOption')
    		->where('t1.locale', $locale_id)
    		->where('t1.field', 'o_print_order')
    		->limit(0, 1)
    		->findAll()->getData();
    		$template = '';
    		if (count($lang_template) === 1)
    		{
    		    $template = $lang_template[0]['content'];
    		}
    		
    		$template_arr = '';
    		$data = pjAppController::getTokens($this->option_arr, $arr, PJ_SALT, $locale_id);
    		$template_arr = str_replace($data['search'], $data['replace'], $template);
    		if ($arr['type'] == 'delivery')
    		{
    		    $template_arr = str_replace(array('[Delivery]', '[/Delivery]'), array('', ''), $template_arr);
    		} else {
    		    $template_arr = preg_replace('/\[Delivery\].*\[\/Delivery\]/s', '', $template_arr);
    		}
            
            // print_r($template_arr);
            // exit;

    		$this->set('template_arr', $template_arr);
    		//$this->appendJs('print.js');
	    }



	    
	}
	
	public function pjActionReminderEmail()
	{
		$this->setAjax(true);
		
		$this->checkLogin();
		if (!pjAuth::factory()->hasAccess())
		{
		    $this->sendForbidden();
		    return;
		}
		if (self::isPost())
		{
    		if($this->_post->toInt('send_email') && $this->_post->toString('to') && $this->_post->toString('subject') && $this->_post->toString('message') && $this->_post->toInt('id'))
    		{
    		    $Email = self::getMailer($this->option_arr);
    			$r = $Email
    			->setTo($this->_post->toString('to'))
    			->setSubject($this->_post->toString('subject'))
    			->send($this->_post->toString('message'));
    				
    			if (isset($r) && $r)
    			{
    				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('lblEmailSent', true, false)));
    			}
    			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('lblFailedToSend', true, false)));
    		}
		}
		if (self::isGet())
		{
    		if($id = $this->_get->toInt('id'))
    		{
    			$pjOrderModel = pjOrderModel::factory();
    							
    			$arr = $pjOrderModel
    				->join('pjClient', "t2.id=t1.client_id", 'left outer')
    				->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
    				->select("t1.*, t2.c_title, t3.email AS c_email, t3.name AS c_name, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes,
    						AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,	
    						AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
    						AES_DECRYPT(t1.cc_exp, '".PJ_SALT."') AS `cc_exp`,
    						AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
    						->find($id)
    				->getData();
    			if (!empty($arr))
    			{
    			    $locale_id = $this->getLocaleId();
    			    if(isset($arr['locale_id']) && (int) $arr['locale_id'] > 0)
    			    {
    			        $locale_id = $arr['locale_id'];
    			    }
    			    pjAppController::addOrderDetails($arr, $locale_id);
    				
    			    $tokens = pjAppController::getTokens($this->option_arr, $arr, PJ_SALT, $locale_id);
    				$notification = pjNotificationModel::factory()->where('recipient', 'client')->where('transport', 'email')->where('variant', 'confirmation')->findAll()->getDataIndex(0);
    				if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
    				{
    				    $resp = pjFrontEnd::pjActionGetSubjectMessage($notification['id'], $locale_id);
    				    $lang_message = $resp['lang_message'];
    				    $lang_subject = $resp['lang_subject'];
        				if (count($lang_message) === 1 && count($lang_subject) === 1)
        				{
        					if ($arr['type'] == 'delivery')
        					{
        					    $message = str_replace(array('<br />[Delivery]', '<br />[/Delivery]'), array('', ''), $lang_message[0]['content']);
        					    $message = str_replace(array('[Delivery]<br />', '[/Delivery]<br />'), array('', ''), $message);
        					    $message = str_replace(array('[Delivery]', '[/Delivery]'), array('', ''), $message);
        					} else {
        						$message = preg_replace('/\[Delivery\].*\[\/Delivery\]/s', '', $lang_message[0]['content']);
        					}
        					$subject_client = str_replace($tokens['search'], $tokens['replace'], $lang_subject[0]['content']);
        					$message_client = str_replace($tokens['search'], $tokens['replace'], $message);
        					$this->set('arr', array(
        					    'id' => $id,
        						'client_email' => $arr['c_email'],
        						'message' => $message_client,
        						'subject' => $subject_client
        					));
        				}
    				}
    			}else{
    				exit;
    			}
    		} else {
    			exit;
    		}
		}
	}
	
	public function pjActionGetExtras()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
		    if ($product_id = $this->_get->toInt('product_id'))
		    {
				$extra_arr = pjExtraModel::factory()
					->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
					->select("t1.*, t2.content AS name")
					->where("t1.id IN (SELECT TPE.extra_id FROM `".pjProductExtraModel::factory()->getTable()."` AS TPE WHERE TPE.product_id=".$product_id.")")
					->orderBy("name ASC")
					->findAll()
					->getData();
				$this->set('extra_arr', $extra_arr);
			}
		}
	}
	public function pjActionGetPrices()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
		    if ($product_id = $this->_get->toInt('product_id'))
			{
				$arr = pjProductModel::factory()
				->find($product_id)
				->getData();
				if (!empty($arr))
				{
					if($arr['set_different_sizes'] == 'T')
					{
						$price_arr = pjProductPriceModel::factory()
							->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'price_name'", 'left')
							->select("t1.*, t2.content AS price_name")
							->where("product_id", $product_id)
							->orderBy("price_name ASC")
							->findAll()
							->getData();
						$this->set('price_arr', $price_arr);
					}

                    //MEGAMIND

					$product_category = pjProductCategoryModel::factory()->where("product_id", $product_id)->findAll()->getData();
					if ($product_category) {
						$arr['category_id'] = $product_category[0]['category_id'];
					}

					//MEGAMIND
				}
				//Added by JR to get product description
				$pjMultiLangModel = pjMultiLangModel::factory();
				$prodarr['i18n'] = $pjMultiLangModel->getMultiLang($product_id, 'pjProduct');
				$arr['description'] = $prodarr['i18n'][$this->getLocaleId()]['description'];
				//End of it;
                //print_r($arr);
                //exit;

				$this->set('arr', $arr);
				//return $arr;
			}
		}
	}
	


	protected function getTotal()
	{
		$is_null = true;
		$product_id_arr = $this->_post->toArray('product_id');
		foreach($product_id_arr as $v)
		{
			if( (int) $v > 0 )
			{
				$is_null = false;
			}
		}
		
		if($is_null == false)
		{
			$price = 0;
			$discount = 0;
			$subtotal = 0;
			$price_packing = 0;
			$price_delivery = 0;
			$tax = 0;
			$total = 0;
			
			$price_format = "";
			$discount_format = "";
			$packing_format = "";
			$subtotal_format = "";
			$delivery_format = "";
			$tax_format = "";
			$total_format = "";
			
			$pjProductPriceModel = pjProductPriceModel::factory();
			$pjExtraModel = pjExtraModel::factory();
			
			$product_arr = pjProductModel::factory()
			->select('t1.id, t1.set_different_sizes, t1.price, MIN(t3.packing_fee) AS `packing_fee`')
			->join('pjProductCategory', 't2.product_id=t1.id', 'left outer')
			->join('pjCategory', 't3.id=t2.category_id', 'left outer')
			->whereIn("t1.id", $product_id_arr)
			->groupBy('t1.id, t1.set_different_sizes, t1.price')
			->findAll()
			->getData();
			$extra_arr = $pjExtraModel
			->findAll()
			->getData();
			foreach ($product_id_arr as $hash => $product_id)
			{
				foreach ($product_arr as $product)
				{
					if ($product['id'] == $product_id)
					{
						$_price = 0;
						$extra_price = 0;
						
						if($product['set_different_sizes'] == 'T')
						{
							$price_id_arr = $this->_post->toArray('price_id');
							$price_arr = $pjProductPriceModel
							->reset()
							->find($price_id_arr[$hash])
							->getData();
							if($price_arr)
							{
								$_price = $price_arr['price'];
							}
						}else{
							$_price = $product['price'];
						}
						$extra_id_arr = $this->_post->toArray('extra_id');
						$cnt_arr = $this->_post->toArray('cnt');
						// print_r($cnt_arr);
						// echo $hash;
						$product_price = $_price * $cnt_arr[$hash];
						//print_r($product['packing_fee']);
						$price_packing += $product['packing_fee'] * $cnt_arr[$hash];
						if (!empty($extra_id_arr) && isset($extra_id_arr[$hash]))
						{
							foreach ($extra_id_arr[$hash] as $oi_id => $extra_id)
							{
								$extra_cnt_arr = $this->_post->toArray('extra_cnt');
								if (!empty($extra_cnt_arr))
								{
									if (isset($extra_cnt_arr[$hash][$oi_id]) && (int) $extra_cnt_arr[$hash][$oi_id] > 0)
									{
										foreach ($extra_arr as $extra)
										{
											if ($extra['id'] == $extra_id)
											{
												$extra_price += $extra['price'] * $extra_cnt_arr[$hash][$oi_id];
												break;
											}
										}
									}
								}
							}
						}
						$_price = $product_price + $extra_price;
						$price += $_price;
						break;
					}
				}
			}
			
			if ($this->_post->has('type') && $this->_post->toInt('d_location_id'))
			{
				$d_location_id = $this->_post->toInt('d_location_id');
				$arr = pjPriceModel::factory()
				->where("t1.location_id", $d_location_id)
				->where("(t1.total_from <= $price)")
				->where("(t1.total_to >= $price)")
				->findAll()
				->limit(1)
				->getData();
				
				if (count($arr) === 1)
				{
					$price_delivery = $arr[0]['price'];
				}
			}
			
			if ($this->_post->has('voucher_code'))
			{
				$post = $this->_post->raw();
				$resp = pjAppController::getDiscount($post, $this->option_arr);
				if ($resp['code'] == 200)
				{
					$voucher_discount = $resp['voucher_discount'];
					switch ($resp['voucher_type'])
					{
						case 'percent':
							$discount = (($price + $price_packing) * $voucher_discount) / 100;
							break;
						case 'amount':
							$discount = $voucher_discount;
							break;
					}
				}
			}
			if ($discount > $price + $price_packing)
			{
				$discount = $price + $price_packing;
			}
			$subtotal = $price + $price_packing + $price_delivery - $discount;
			if (!empty($this->option_arr['o_tax_payment']))
			{
				if ($this->option_arr['o_add_tax'] == '1' && $this->_post->has('type'))
				{
					$tax = (($subtotal - $price_delivery) * $this->option_arr['o_tax_payment']) / 100;
				} else {
					$tax = ($subtotal * $this->option_arr['o_tax_payment']) / 100;
				}
			}
			$total = $subtotal + $tax;
			
			$price_format = pjCurrency::formatPrice($price);
			$discount_format = pjCurrency::formatPrice($discount);
			$packing_format = pjCurrency::formatPrice($price_packing);
			$delivery_format = pjCurrency::formatPrice($price_delivery);
			$subtotal_format = pjCurrency::formatPrice($subtotal);
			$tax_format = pjCurrency::formatPrice($tax);
			$total_format = pjCurrency::formatPrice($total);
			
			return compact('price', 'discount', 'price_packing', 'price_delivery', 'subtotal', 'tax', 'total', 'price_format', 'discount_format', 'packing_format', 'delivery_format', 'subtotal_format', 'tax_format', 'total_format');
		}

		return array('price' => 'NULL');
	}

	protected function getClientType($data)
	{   

		$regular = 0;
	            	
    	$c_exist_orders = pjOrderModel::factory()
    	                ->select("t1.*")
    	                ->where('t1.client_id',$data['client_id'])
    	                ->findAll()
    	                ->getData();
    	
    	$c_exist_orders_dates = array();
    	
    	foreach ($c_exist_orders as $k => $v) {
    		    $c_exist_orders_dates[] = explode(" ",$v['created'])[0];
    		    
	    	}

	    if (count($c_exist_orders)) {
	    	$weekDates[0] = date('Y-m-d');
	    	for ($i=1; $i < 7; $i++) { 
	    	 	$weekDates[$i] = date('Y-m-d',strtotime("-$i days"));
	    	 	
	    	 } 
	    	 
	    	foreach ($c_exist_orders_dates as $k) {
	    		foreach ($weekDates as $d) {
	    			if ($k == $d) {
	    				$regular = $regular + 1;
	    			}
	    		}
	    	}

	    	if ($regular >= 2) {
	    		return "Regular client";
	    	} else if ($regular == 1) {

	    		$frequent = 1;
	    		$frequentDates = [];
	    		for($j=7; $j < 28; $j++) {
	    			$frequentDates[$j] = date('Y-m-d',strtotime("-$j days"));
	    		}

	    		foreach ($c_exist_orders_dates as $k) {
	    			foreach ($frequentDates as $f) {
	    				if($k == $f) {
	    					$frequent = $frequent + 1;
	    				}
	    			}
	    		}
                
	    		if ($frequent >= 4) {

	    			return "Frequent";
	    		} else {
	    			return "Occasional";
	    		}
	    	} else {
	    		
	    		return "Rare";
	    	}
	    	
    	} 
    	
	}
	
	public function pjActionGetTotal()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			pjAppController::jsonResponse($this->getTotal());
		}
		exit;
	}
	
	public function pjActionGetClient()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$client_arr = pjClientModel::factory()->find($this->_get->toInt('id'))->getData();
			$user = pjAuth::init(array('id' => $client_arr['foreign_id']))->getUser();
			$client = array_merge($user, $client_arr);
			pjAppController::jsonResponse($client);
		}
		exit;
	}
	
	public function pjActionCheckPickup()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if (!$this->_post->toString('type') || !$this->_post->toInt('p_location_id'))
			{
				echo 'true';
				exit;
			}
			if ($this->_post->toString('type') != 'pickup')
			{
				echo 'true';
				exit;
			}
			$type = $this->_post->toString('type');
			$p_location_id = $this->_post->toInt('p_location_id');
			$date_time = $this->_post->toString('p_dt');
			if(count(explode(" ", $date_time)) == 3)
			{
				list($_date, $_time, $_period) = explode(" ", $date_time);
				$time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
			}else{
				list($_date, $_time) = explode(" ", $date_time);
				$time = pjDateTime::formatTime($_time, $this->option_arr['o_time_format']);
			}
			$date = pjDateTime::formatDate($_date, $this->option_arr['o_date_format']);
			$wt_arr = pjAppController::getWorkingTime($date, $p_location_id, $type);
			if($wt_arr == false)
			{
				echo 'false';
				exit;
			}
			$ts = strtotime($date . ' ' . $time);
			$start_ts = strtotime($date . ' ' . $wt_arr['start_hour'] . ':' . $wt_arr['start_minutes'] . ':00');
			$end_ts = strtotime($date . ' ' . $wt_arr['end_hour'] . ':' . $wt_arr['end_minutes'] . ':00');
			
			if($end_ts <= $start_ts)
			{
				$end_ts += 86400;
			}
			
			if($ts >= $start_ts && $ts <= $end_ts)
			{
				echo 'true';
			}else{
				if($ts < $start_ts)
				{
					$date = date('Y-m-d', ($ts - 86400));
					$wt_arr = pjAppController::getWorkingTime($date, $p_location_id, $type);
					if($wt_arr == false)
					{
						echo 'false';
						exit;
					}
					$start_ts = strtotime($date . ' ' . $wt_arr['start_hour'] . ':' . $wt_arr['start_minutes'] . ':00');
					$end_ts = strtotime($date . ' ' . $wt_arr['end_hour'] . ':' . $wt_arr['end_minutes'] . ':00');
						
					if($end_ts <= $start_ts)
					{
						$end_ts += 86400;
					}
					if($ts >= $start_ts && $ts <= $end_ts)
					{
						echo 'true';
					}else{
						echo 'false';
					}
				}else{
					echo 'false';
				}
			}
		}
		exit;
	}
	
	public function pjActionCheckDelivery()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
		    if (!$this->_post->toString('type') || !$this->_post->toInt('d_location_id'))
		    {
		        echo 'true';
		        exit;
		    }
		    if ($this->_post->toString('type') != 'delivery')
		    {
		        echo 'true';
		        exit;
		    }
		    $type = $this->_post->toString('type');
		    //print_r($type);
		    $d_location_id = $this->_post->toInt('d_location_id');
		    // print_r($d_location_id);
		    $d_date = $this->_post->toString('d_date');
		    $d_time = $this->_post->toString('d_time');
		    // print_r($date_time);
			if(count(explode(" ", $d_time)) == 2)
			{
				list($_time, $_period) = explode(" ", $d_time);
				$time = pjDateTime::formatTime($_time . ' ' . $_period, $this->option_arr['o_time_format']);
			}else{
				// list($_date, $_time) = explode(" ", $date_time);
				$time = pjDateTime::formatTime($d_time, $this->option_arr['o_time_format']);
			}
			$date = pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']);
			$wt_arr = pjAppController::getWorkingTime($date, $d_location_id, $type);
			
			if($wt_arr == false)
			{
				echo 'false';
				exit;
			}
			$ts = strtotime($date . ' ' . $time);
			$start_ts = strtotime($date . ' ' . $wt_arr['start_hour'] . ':' . $wt_arr['start_minutes'] . ':00');
			$end_ts = strtotime($date . ' ' . $wt_arr['end_hour'] . ':' . $wt_arr['end_minutes'] . ':00');
			
			if($end_ts <= $start_ts)
			{
				$end_ts += 86400;
			}
			
			if($ts >= $start_ts && $ts <= $end_ts)
			{
				echo 'true';
			}else{
				if($ts < $start_ts)
				{
					$date = date('Y-m-d', ($ts - 86400));
					$wt_arr = pjAppController::getWorkingTime($date, $d_location_id, $type);
					if($wt_arr == false)
					{
						echo 'false';
						exit;
					}
					$start_ts = strtotime($date . ' ' . $wt_arr['start_hour'] . ':' . $wt_arr['start_minutes'] . ':00');
					$end_ts = strtotime($date . ' ' . $wt_arr['end_hour'] . ':' . $wt_arr['end_minutes'] . ':00');
						
					if($end_ts <= $start_ts)
					{
						$end_ts += 86400;
					}
					if($ts >= $start_ts && $ts <= $end_ts)
					{
						echo 'true';
					}else{
						echo 'false';
					}
				}else{
					echo 'false';
				}
			}
		}
		exit;
	}

	/* Added by JR */
    public function pjActionGetProductsForCategory()
    {
        $this->setAjax(true);
    
        if ($this->isXHR())
        {
            $product_arr = [];
            $category_id = $this->_post->toInt('category_id');
            $category_arr = pjProductCategoryModel::factory()
            ->select('t1.product_id')
            ->whereIn("t1.category_id", $category_id)
            ->findAll()
            ->getData();
            if ($category_arr) {
                $category_arr = array_column($category_arr, 'product_id');
                $product_arr = pjProductModel::factory()
                ->select('t1.id, t2.content AS name, t1.set_different_sizes, t1.price, t1.status')
                ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
                ->whereIn("t1.id", $category_arr)
                ->groupBy('t1.id, t1.set_different_sizes, t1.price')
                ->findAll()
                ->getData();
            }
            $this->set('product_arr', $product_arr);

            $extras_arr = pjProductExtraModel::factory()
                        ->select('t1.*')
                        ->findAll()
                        ->getData();
            $this->set('extras_arr', $extras_arr);
            // $prdExtras = [];
            // foreach ($exras as $extra) {
            //     foreach ($product_arr as $product) {
            //         if ($extra['product_id'] == $product['id']) {
            //            $prdExtras[] = $extra['extra_id'];
            //            $product_arr[] = $prodExtras[];
            //         }
            //     }
            // }            
        }
    }

    // MEGAMIND

	public function pjActionSaveOrderDespatched()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (!self::isPost())
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
	        }
	        
	        if ($this->_get->toInt('id') <= 0)
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
	        }
	        $id = $this->_get->toInt('id');
	        
	        pjOrderModel::factory()
	        ->where('id', $id)
	        ->modifyAll(array(
	            'order_despatched' => ":IF(`order_despatched`='0','1','0')"
	        ));

	        $data = pjOrderModel::factory()
	        ->select("t1.phone_no, t1.order_despatched")
	        ->find($id)
	        ->getData(); 
	        //print_r($data);
	      //   if ($data['order_despatched']) {
	      //    	$params = array(
       //          'text' => 'Your order has been despatched',
       //          'type' => 'unicode',
       //          'key' => md5($this->option_arr['private_key'] . PJ_SALT)
       //         );
       //         // $params['number'] = $data['phone_no'];
       //          $params['number'] = "+919841646770";
       //          pjBaseSms::init($params)->pjActionSend();
	            date_default_timezone_set('Asia/Kolkata');
			    $_ss_time = date( 'y-m-d H:i:s', time () );
	            pjOrderModel::factory()
		        ->where('id', $id)
		        ->modifyAll(array(
		            'sms_sent_time' => $_ss_time
		         ));
	      //   }
            
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Your order has despatched.'));
	    }
	    exit;
	}
	public function pjActionSaveOrderPaid()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (!self::isPost())
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
	        }
	        
	        if ($this->_get->toInt('id') <= 0)
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
	        }
	        $id = $this->_get->toInt('id');
	        
	        pjOrderModel::factory()
	        ->where('id', $id)
	        ->modifyAll(array(
	            'is_paid' => ":IF(`is_paid`='0','1','0')"
	        ));
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Your order has despatched.'));
	    }
	    exit;
	}
	public function pjActionSaveDeliveredCustomer()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (!self::isPost())
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
	        }
	        
	        if ($this->_get->toInt('id') <= 0)
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
	        }
	        $id = $this->_get->toInt('id');
	        
	        pjOrderModel::factory()
	        ->where('id', $id)
	        ->modifyAll(array(
	            'delivered_customer' => ":IF(`delivered_customer`='0','1','0')"
	        ));

	        $data = pjOrderModel::factory()
	        ->select("t1.phone_no, t1.delivered_customer")
	        ->find($id)
	        ->getData();
	        if ($data['delivered_customer'] == 1) {
	        	pjOrderModel::factory()
	            ->where('id', $id)
	            ->modifyAll(array(
	            'status' => "delivered"
	            ));
	            date_default_timezone_set('Asia/Kolkata');
			    $delivered_time = date( 'y-m-d H:i:s', time () );
	            pjOrderModel::factory()
		        ->where('id', $id)
		        ->modifyAll(array(
		            'delivered_time' => $delivered_time
		        ));
	        }
	        //var_dump($data['d_dt'] > $data['delivered_time']);
	        //print_r($data);

	         // if ($data['delivered_customer']) {
	         // 	$params = array(
          //       'text' => 'Your order has been despatched',
          //       'type' => 'unicode',
          //       'key' => md5($this->option_arr['private_key'] . PJ_SALT)
          //      );
          //      // $params['number'] = $data['phone_no'];
          //      $params['number'] = "+919841646770";
          //      pjBaseSms::init($params)->pjActionSend();
          //      //print_r("Message has sent");
	         // }

            
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Your order has despatched.'));
	    }
	    exit;
	}
	public function pjActionDelayMessage()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (!self::isPost())
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
	        }
	        
	         if ($this->_post->toInt('id') <= 0)
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
	        }
	        if ($this->_post->toString('delay_msg') == "")
	        {
	        	self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Message is empty'));
	        }
	        else {
	        	$msg = $this->_post->toString('delay_msg');
	        }
	        $id = $this->_post->toInt('id');
	        
	        $data = pjOrderModel::factory()
	        ->select("t1.phone_no, t1.delivered_customer")
	        ->find($id)
	        ->getData();

	        // if ($data['delivered_customer'] == 1) {
	        // 	$params = array(
         //        'text' => $msg,
         //        'type' => 'unicode',
         //        'key' => md5($this->option_arr['private_key'] . PJ_SALT)
         //       );
         //       // $params['number'] = $data['phone_no'];
         //       $params['number'] = "+917449296732";
         //       pjBaseSms::init($params)->pjActionSend();
	        // }

	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Delay message has been sent.'));
	    }
	    exit;
	}

	public function pjActionGetDelayMessage() {

		$this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (!self::isPost())
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
	        }
	        if ($this->_post->toInt('value') <= 0) {
	        	self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
	        }
	        $id = $this->_post->toInt('value');
	        

	        $msg = pjDelayMessagesModel::factory()
	        ->select('t1.*')
	        ->where('t1.id',$id)
	        ->findAll()
	        ->getData();

	        //print_r($msg);

	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $msg[0]['message']));
	    }

	}

	// public function pjActionSetSession() {

	// 	$this->setAjax(true);
	//     if ($this->isXHR())
	//     {
	//     	if ($this->_post->toInt('chef_id') <= 0) {
	//         	self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
	//         }
	//     $chef = $this->_post->toInt('chef_id');
 //        $_SESSION['chef'] = $chef;
	//     self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'success'));
	//     }

	// }

	// !MEGAMIND
}
?>