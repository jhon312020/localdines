<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontPublic extends pjFront
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setAjax(true);
		
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionMain()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        $this->set('main', $this->getCategories());
	        $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
	    }
	}
	
	public function pjActionCategories()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        $this->set('main', $this->getCategories());
	    }
	}
	
	public function pjActionCart()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        $cart_box = pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId());
	        
	        $price = 0;
	        $packing = 0;
	        foreach ($cart_box['cart'] as $hash => $item)
	        {
	            foreach ($cart_box['product_arr'] as $product)
	            {
	                if ($product['id'] == $item['product_id'])
	                {
	                    $product_price = 0;
	                    $product_price = $item['price'] * $item['cnt'];
	                    $packing = $product['packing_fee'] * $item['cnt'];
	                    $price += $product_price;
	                    foreach ($item['extra_arr'] as $extra_id => $extra)
	                    {
	                        $price += $extra['price'] * $extra['qty'];
	                    }
	                }
	            }
	        }
	        $arr = pjPriceModel::factory()
	        ->where('t1.location_id', $this->_get('d_location_id'))
	        ->where('t1.total_from <= ' . $price)
	        ->where('t1.total_to >= ' . $price)
	        ->limit(1)
	        ->findAll()
	        ->getData();
	        
	        $delivery = 0;
	        if (count($arr) === 1)
	        {
	            $delivery = $arr[0]['price'];
	        }
	        $this->_set('delivery', $delivery);
	        $this->_set('packing', $packing);
	        $this->set('cart_box', $cart_box);
	    }
	}
	
	public function pjActionTypes()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
	        {
	            $location_arr = pjLocationModel::factory()
	            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	            ->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjLocation' AND t3.locale = '%u' AND t3.field = 'address'", $this->getLocaleId()), 'left')
	            ->select('t1.*, t2.content AS name, t3.content AS address')
	            ->orderBy("name ASC")
	            ->findAll()
	            ->getData();
	            $type = $this->_get('type');
	            if ($type !== false)
	            {
	                switch ($type)
	                {
	                    case 'pickup':
	                        $p_location_id = $this->_get('p_location_id');
	                        $p_date = $this->_get('p_date');
	                        if ($p_location_id !== false && $p_date !== false)
	                        {
	                            $wt_arr = pjAppController::getWorkingTime(pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']), $p_location_id, 'pickup');
	                            $this->set('wt_arr', $wt_arr);
	                        }
	                        $this->set('date', pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']));
	                        break;
	                    case 'delivery':
	                        $d_location_id = $this->_get('d_location_id');
	                        $d_date = $this->_get('d_date');
	                        if ($d_location_id !== false && $d_date !== false)
	                        {
	                            $wt_arr = pjAppController::getWorkingTime(pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']), $d_location_id, 'delivery');
	                            $this->set('wt_arr', $wt_arr);
	                        }
	                        $this->set('date', pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']));
	                        break;
	                }
	            }
	            $country_arr = pjBaseCountryModel::factory()
	            ->select('t1.id, t2.content AS country_title')
	            ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
	            ->orderBy('`country_title` ASC')
	            ->findAll()
	            ->getData();
	            
	            if($this->isFrontLogged())
	            {
	                $order_arr = pjOrderModel::factory()
	                ->select("t1.*")
	                ->where('client_id', $this->getClientId())
	                ->where('type', 'delivery')
	                ->orderBy("t1.created DESC")
	                ->findAll()
	                ->getData();
	                
	                $this->set('order_arr', $order_arr);
	            }
	            
	            $this->set('country_arr', $country_arr);
	            $this->set('location_arr', $location_arr);
	            $this->set('status', 'OK');
	        }else{
	            $this->set('status', 'ERR');
	        }
	        $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
	    }
	}
	
	public function pjActionLogin()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if($this->isFrontLogged())
	        {
	            pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200));
	        }else{
	            $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
	        }
	    }
	}
	
	public function pjActionProfile()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if($this->isFrontLogged())
	        {
	            $country_arr = pjBaseCountryModel::factory()
	            ->select('t1.id, t2.content AS country_title')
	            ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
	            ->orderBy('`country_title` ASC')
	            ->findAll()
	            ->getData();
	            
	            $this->set('country_arr', $country_arr);
	            $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
	        }else{
	            pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100));
	        }
	    }
	}
	
	public function pjActionForgot()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
	    }
	}
	
	public function pjActionVouchers()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
	        {
	            $this->set('status', 'OK');
	        }else{
	            $this->set('status', 'ERR');
	        }
	        $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
	    }
	}
	
	
	public function pjActionCheckout()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
	        {
	            $country_arr = pjBaseCountryModel::factory()
	            ->select('t1.id, t2.content AS country_title')
	            ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
	            ->orderBy('`country_title` ASC')
	            ->findAll()
	            ->getData();
	            $this->set('country_arr', $country_arr);
	            
	            $this->set('status', 'OK');
	        }else{
	            $this->set('status', 'ERR');
	        }
	        $cart_box = pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId());
	        
	        $price = 0;
	        
	        foreach ($cart_box['cart'] as $hash => $item)
	        {
	            foreach ($cart_box['product_arr'] as $product)
	            {
	                if ($product['id'] == $item['product_id'])
	                {
	                    $product_price = 0;
	                    $product_price = $item['price'] * $item['cnt'];
	                    $price += $product_price;
	                    foreach ($item['extra_arr'] as $extra_id => $extra)
	                    {
	                        $price += $extra['price'] * $extra['qty'];
	                    }
	                }
	            }
	        }
	        
	        $arr = pjPriceModel::factory()
	        ->where('t1.location_id', $this->_get('d_location_id'))
	        ->where('t1.total_from <= ' . $price)
	        ->where('t1.total_to >= ' . $price)
	        ->limit(1)
	        ->findAll()
	        ->getData();
	        
	        $delivery = 0;
	        if (count($arr) === 1)
	        {
	            $delivery = $arr[0]['price'];
	        }
	        $this->_set('delivery', $delivery);
	        $this->set('cart_box', $cart_box);
	        
	        $bank_account = pjMultiLangModel::factory()
	        ->select('t1.content')
	        ->where('t1.model','pjOption')
	        ->where('t1.locale', $this->getLocaleId())
	        ->where('t1.field', 'o_bank_account')
	        ->limit(1)
	        ->findAll()->getDataIndex(0);
	        $this->set('bank_account', $bank_account['content']);
	        
	        if(pjObject::getPlugin('pjPayments') !== NULL)
	        {
	            $this->set('payment_option_arr', pjPaymentOptionModel::factory()->getOptions($this->getForeignId()));
	            $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId(), $this->getLocaleId()));
	        }else{
	            $this->set('payment_titles', __('payment_methods', true));
	        }
	    }
	}
	
	public function pjActionPreview()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if (isset($_SESSION[$this->defaultStore]) && count($_SESSION[$this->defaultStore]) > 0)
	        {
	            $STORE = $_SESSION[$this->defaultStore];
	            
	            $price = $STORE['price'];
	            if($price < (float) $this->option_arr['o_minimum_order'] && $STORE['type'] == 'delivery')
	            {
	                pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100));
	            }
	            
	            $pjLocationModel = pjLocationModel::factory();
	            $country_arr = array();
	            if(isset($_SESSION[$this->defaultForm]['c_country']) && !empty($_SESSION[$this->defaultForm]['c_country']))
	            {
	                $country_arr = pjBaseCountryModel::factory()
	                ->select('t1.id, t2.content AS country_title')
	                ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
	                ->find($_SESSION[$this->defaultForm]['c_country'])->getData();
	            }
	            if($STORE['type'] == 'delivery' && isset($STORE['d_location_id']) && !empty($STORE['d_location_id']))
	            {
	                $location_arr = $pjLocationModel
	                ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	                ->select('t1.*, t2.content AS name')
	                ->find($STORE['d_location_id'])
	                ->getData();
	                $this->set('location_arr', $location_arr);
	            }
	            if($STORE['type'] == 'pickup' && isset($STORE['p_location_id']) && !empty($STORE['p_location_id']))
	            {
	                $location_arr = $pjLocationModel->reset()
	                ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	                ->select('t1.*, t2.content AS name')
	                ->find($STORE['p_location_id'])
	                ->getData();
	                $this->set('p_location_arr', $location_arr);
	            }
	            if(isset($STORE['d_country_id']) && !empty($STORE['d_country_id']))
	            {
	                $d_country_arr = pjBaseCountryModel::factory()
	                ->select('t1.id, t2.content AS country_title')
	                ->join('pjBaseMultiLang', sprintf("t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
	                ->find($STORE['d_country_id'])
	                ->getData();
	                $this->set('d_country_arr', $d_country_arr);
	            }
	            $this->set('country_arr', $country_arr);
	            
	            $date = null;
	            $selected_ts = null;
	            $wt_arr = array();
	            $type = $this->_get('type');
	            if ($type !== false)
	            {
	                switch ($type)
	                {
	                    case 'pickup':
	                        $p_location_id = $this->_get('p_location_id');
	                        $p_date = $this->_get('p_date');
	                        $date = pjDateTime::formatDate($p_date, $this->option_arr['o_date_format']);
	                        if ($p_location_id !== false && $p_date !== false)
	                        {
	                            $wt_arr = pjAppController::getWorkingTime($date, $p_location_id, 'pickup');
	                        }
	                        $selected_ts = strtotime($date . ' '. $this->_get('p_time') . ':00');
	                        break;
	                    case 'delivery':
	                        $d_location_id = $this->_get('d_location_id');
	                        $d_date = $this->_get('d_date');
	                        $date = pjDateTime::formatDate($d_date, $this->option_arr['o_date_format']);
	                        if ($d_location_id !== false && $d_date !== false)
	                        {
	                            $wt_arr = pjAppController::getWorkingTime($date, $d_location_id, 'delivery');
	                        }
	                        $selected_ts = strtotime($date . ' '. $this->_get('d_time') . ':00');
	                        break;
	                }
	            }
	            if($selected_ts != null && $date != null && !empty($wt_arr))
	            {
	                $start_time = strtotime($date . ' ' . $wt_arr['start_hour'] . ':' . $wt_arr['start_minutes'] . ':00');
	                $end_time = strtotime($date . ' ' . $wt_arr['end_hour'] . ':' . $wt_arr['end_minutes'] . ':00');
	                
	                if($selected_ts < $start_time)
	                {
	                    $selected_ts += 86400;
	                    $this->_set('next_day', date('Y-m-d H:i:s', $selected_ts));
	                }else{
	                    if($this->_is('next_day'))
	                    {
	                        $this->_unset('next_day');
	                    }
	                }
	            }else{
	                if($this->_is('next_day'))
	                {
	                    $this->_unset('next_day');
	                }
	            }
	            if(pjObject::getPlugin('pjPayments') !== NULL)
	            {
	                $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId(), $this->getLocaleId()));
	            }else{
	                $this->set('payment_titles', __('payment_methods', true));
	            }
	            $this->set('status', 'OK');
	        }else{
	            $this->set('status', 'ERR');
	        }
	        $this->set('cart_box', pjFrontCart::getCartInfo($this->_get('cart'), $this->getLocaleId()));
	    }
	}
	
	
	public function pjActionGetWTime()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        $get_date = $this->_get->toString('date');
	        if ($this->_get->toInt('location_id') > 0 &&  !empty($get_date))
	        {
	            $date = pjDateTime::formatDate($get_date, $this->option_arr['o_date_format']);
	            $wt_arr = pjAppController::getWorkingTime($date, $this->_get->toInt('location_id'), $this->_get->toString('type'));
	        } else {
	            $wt_arr = array('start_hour' => 0, 'end_hour' => 23);
	            $this->_set('p_time', '00:00');
	            $this->_set('d_time', '00:00');
	        }
	        $this->tpl['date'] = $date;
	        $this->tpl['wt_arr'] = $wt_arr;
	    }
	}
	
	public function pjActionGetTerms()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			$terms_conditions = '';
			$terms_lang_arr = pjMultiLangModel::factory()
				->select('t1.content')
				->where('t1.model','pjOption')
				->where('t1.locale', $this->getLocaleId())
				->where('t1.field', 'o_terms')
				->limit(1)
				->findAll()
				->getDataIndex(0);
			if ($terms_lang_arr)
			{
				$terms_conditions = $terms_lang_arr['content'];
			}
			$this->set('terms_conditions', $terms_conditions);
		}
	}
	
	public function pjActionGetProducts()
	{
	    $this->setAjax(true);
	    
	    if($category_id = $this->_get->toInt('category_id'))
	    {
	        $pjProductModel = pjProductModel::factory();
	        $arr = $pjProductModel
	        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	        ->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjProduct' AND t3.locale = '%u' AND t3.field = 'description'", $this->getLocaleId()), 'left')
	        ->join('pjProductCategory', 't4.product_id=t1.id', 'left outer')
	        ->select("t1.*, t2.content AS name, t3.content AS description, t4.category_id")
	        ->where('t4.category_id', $category_id)
	        ->orderBy("t1.is_featured DESC, t2.content ASC")
	        ->findAll()
	        ->getData();
	        
	        $pjExtraModel = pjExtraModel::factory();
	        $pjProductExtraTable = pjProductExtraModel::factory()->getTable();
	        $pjProductPriceModel = pjProductPriceModel::factory();
	        
	        $price_arr = array();
	        $extra_arr = array();
	        $product_id_arr = $pjProductModel->findAll()->getDataPair(null, 'id');
	        if(!empty($product_id_arr))
	        {
	            $temp_extra_arr = $pjExtraModel
	            ->reset()
	            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjExtra' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	            ->join('pjProductExtra', "t3.extra_id = t1.id", 'left')
	            ->where(sprintf("(t1.id IN (SELECT TPE.extra_id FROM `%s` AS TPE WHERE TPE.product_id IN(".join(",", $product_id_arr).") ))", $pjProductExtraTable))
	            ->select("t1.*, t2.content AS name, t3.product_id")
	            ->orderBy("name ASC")
	            ->findAll()
	            ->getData();
	            foreach($temp_extra_arr as $k => $v)
	            {
	                $extra_arr[$v['product_id']][] = $v;
	            }
	            $temp_price_arr = $pjProductPriceModel
	            ->reset()
	            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProductPrice' AND t2.locale = '%u' AND t2.field = 'price_name'", $this->getLocaleId()), 'left')
	            ->whereIn('t1.product_id', $product_id_arr)
	            ->select("t1.*, t2.content AS price_name")
	            ->findAll()
	            ->getData();
	            foreach($temp_price_arr as $k => $v)
	            {
	                $price_arr[$v['product_id']][] = $v;
	            }
	        }
	        foreach($arr as $k => $product)
	        {
	            $product['price_arr'] = array();
	            $product['extra_arr'] = array();
	            
	            if(isset($extra_arr[$product['id']]))
	            {
	                $product['extra_arr'] = $extra_arr[$product['id']];
	            }
	            if($product['set_different_sizes'] == 'T' && isset($price_arr[$product['id']]))
	            {
	                $product['price_arr'] = $price_arr[$product['id']];
	            }
	            $arr[$k] = $product;
	        }
	        $this->set('arr', $arr);
	    }
	}
	
	public function pjActionGetPaymentForm()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        $order_id = $this->_get->toInt('order_id');
	        $arr = pjOrderModel::factory()
	        ->select('t1.*')
	        ->find($order_id)
	        ->getData();
	        
	        $item_arr = pjOrderItemModel::factory()
	        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.foreign_id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	        ->select('t1.*, t2.content as name')
	        ->where('t1.order_id', $order_id)
	        ->where('t1.type', 'product')
	        ->findAll()
	        ->getData();
	        $_arr = array();
	        foreach($item_arr as $v)
	        {
	            $_arr[] = stripslashes($v['name']);
	        }
	        $arr['product_name'] = !empty($_arr) ? join("; ", $_arr) : null;
	        $client = pjClientModel::factory()
	        ->select("t1.*, t2.email as c_email, t2.name as c_name, t2.phone as c_phone")
	        ->join('pjAuthUser', "t1.foreign_id=t2.id", 'left outer')
	        ->find($arr['client_id'])->getData();
	        if(pjObject::getPlugin('pjPayments') !== NULL)
	        {
	            $pjPlugin = pjPayments::getPluginName($arr['payment_method']);
	            if(pjObject::getPlugin($pjPlugin) !== NULL)
	            {
	                $this->set('params', $pjPlugin::getFormParams(array('payment_method' => $arr['payment_method']), array(
	                    'locale_id'	 => $this->getLocaleId(),
	                    'return_url'	=> $this->option_arr['o_thankyou_page'],
	                    'id'			=> $arr['id'],
	                    'foreign_id'	=> $this->getForeignId(),
	                    'uuid'		  => $arr['uuid'],
	                    'name'		  => @$client['c_name'],
	                    'email'		 => @$client['c_email'],
	                    'phone'		 => @$client['c_phone'],
	                    'amount'		=> $arr['total'],
	                    'cancel_hash'   => sha1($arr['uuid'].strtotime($arr['created']).PJ_SALT),
	                    'currency_code' => $this->option_arr['o_currency'],
	                )));
	            }
	            
	            if ($arr['payment_method'] == 'bank')
	            {
	                $bank_account = pjMultiLangModel::factory()->select('t1.content')
	                ->where('t1.model','pjOption')
	                ->where('t1.locale', $this->getLocaleId())
	                ->where('t1.field', 'o_bank_account')
	                ->limit(1)
	                ->findAll()->getDataIndex(0);
	                $this->set('bank_account', $bank_account['content']);
	            }
	        }
	        
	        $this->set('arr', $arr);
	        $this->set('get', $this->_get->raw());
	    }
	}
	
	public function pjActionCancel()
	{
	    $this->setLayout('pjActionCancel');
	    
	    $pjOrderModel = pjOrderModel::factory();
	    
	    if ($this->_post->check('order_cancel'))
	    {
	        $order_arr = $pjOrderModel
	        ->reset()
	        ->join('pjClient', "t2.id=t1.client_id", 'left outer')
	        ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
	        ->select("t1.*, t2.c_title, t3.email AS c_email, t3.name AS c_name, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes,
						AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
						AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
						AES_DECRYPT(t1.cc_exp, '".PJ_SALT."') AS `cc_exp`,
						AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
			->find($this->_post->toInt('id'))
			->getData();
			if (count($order_arr) > 0)
			{
			    $pjOrderModel
					->reset()
					->setAttributes(array("id" => $order_arr['id']))
					->modify(array('status' => 'cancelled'));
				
			    $locale_id = $this->getLocaleId();
			    if((int) $order_arr['locale_id'] > 0)
			    {
			        $locale_id = $order_arr['locale_id'];
			    }
			    pjAppController::addOrderDetails($order_arr, $locale_id);
			    pjFrontEnd::pjActionConfirmSend($this->option_arr, $order_arr, PJ_SALT, 'cancel', $locale_id);
			    pjUtil::redirect($_SERVER['PHP_SELF'] . '?controller=pjFrontPublic&action=pjActionCancel&err=200');
			}
	    }else{
	        if($id = $this->_get->toInt('id'))
	        {
	            $locale_id = $this->getLocaleId();
	            $order_arr = $pjOrderModel->reset()->find($id)->getData();
	            if(isset($order_arr['locale_id']) && (int) $order_arr['locale_id'] > 0)
	            {
	                $locale_id = $order_arr['locale_id'];
	                $this->pjActionSetLocale($locale_id);
	                $this->loadSetFields(true);
	            }
	            $arr = $pjOrderModel
	            ->reset()
	            ->join('pjClient', "t2.id=t1.client_id", 'left outer')
	            ->join('pjMultiLang', sprintf("t3.model='pjCountry' AND t3.foreign_id=t1.d_country_id AND t3.field='name' AND t3.locale='%u'", $locale_id), 'left outer')
	            ->join('pjMultiLang', sprintf("t4.model='pjCountry' AND t4.foreign_id=t1.location_id AND t4.field='name' AND t4.locale='%u'", $locale_id), 'left outer')
	            ->join('pjAuthUser', "t5.id=t2.foreign_id", 'left outer')
	            ->select('t1.*, t3.content as d_country, t4.content as location, t2.c_title, t5.email AS c_email, t5.name AS c_name, t5.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes')
	            ->find($id)
	            ->getData();
	            if (count($arr) == 0)
	            {
	                $this->set('status', 2);
	            }else{
	                if ($arr['status'] == 'cancelled')
	                {
	                    $this->set('status', 4);
	                }else{
	                    $hash = sha1($arr['id'] . $arr['created'] . PJ_SALT);
	                    if ($this->_get->check('hash') != $hash)
	                    {
	                        $this->set('status', 3);
	                    }else{
	                        pjAppController::addOrderDetails($arr, $locale_id);
	                        $this->set('arr', $arr);
	                        if(pjObject::getPlugin('pjPayments') !== NULL)
	                        {
	                            $this->set('payment_titles', pjPayments::getPaymentTitles($this->getForeignId(), $locale_id));
	                        }else{
	                            $this->set('payment_titles', __('payment_methods', true));
	                        }
	                    }
	                }
	            }
	        }elseif (!$this->_get->check('err')) {
	            $this->set('status', 1);
	        }
	    }
	}
	
	private function getCategories()
	{
	    $pjCategoryModel = pjCategoryModel::factory();
	    
	    $category_arr = $pjCategoryModel
	    ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	    ->select(sprintf("t1.*, t2.content as name, (SELECT COUNT(TPC.product_id) FROM `%s` AS TPC WHERE TPC.category_id=t1.id) AS cnt_products", pjProductCategoryModel::factory()->getTable()))
	    ->where('t1.status', 'T')
	    ->orderBy("`order` ASC")
	    ->findAll()
	    ->getData();
	    
	    $category_id_arr = array();
	    $product_arr = array();
	    
	    return compact('category_arr', 'product_arr');
	}
}
?>