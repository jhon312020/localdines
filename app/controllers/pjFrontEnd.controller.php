<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontEnd extends pjFront
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setAjax(true);
		
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionLoadCss()
	{
	    $dm = new pjDependencyManager(PJ_INSTALL_PATH, PJ_THIRD_PARTY_PATH);
	   
	    $dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
	    
	    $theme = $this->option_arr['o_theme'];
	    if($this->_get->check('layout'))
	    {
	        if(in_array($this->_get->toString('layout'), array('theme1', 'theme2', 'theme3', 'theme4', 'theme5', 'theme6', 'theme7', 'theme8', 'theme9', 'theme10')))
	        {
	            $theme = $this->_get->toString('layout');
	        }
	    }
	    $arr = array(
	        array('file' => 'font-awesome.min.css', 'path' => $dm->getPath('font_awesome') . 'css/'),
	        array('file' => 'datepicker.css', 'path' => $dm->getPath('pj_bootstrap_datepicker')),
	        array('file' => 'calendar.css', 'path' => $dm->getPath('calendarjs')),
	        array('file' => 'FoodDelivery.css', 'path' => PJ_CSS_PATH),
	        array('file' => "themes/$theme.css", 'path' => PJ_CSS_PATH)
	    );
	    header("Content-Type: text/css; charset=utf-8");
	    foreach ($arr as $item)
	    {
	        ob_start();
	        @readfile($item['path'] . $item['file']);
	        $string = ob_get_contents();
	        ob_end_clean();
	        
	        if ($string !== FALSE)
	        {
	            echo str_replace(
	                array('../img/', '../fonts/glyphicons', '../fonts/fontawesome', '[URL]', "pjWrapper"),
	                array(
	                    PJ_INSTALL_URL . PJ_IMG_PATH,
	                    PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/fonts/glyphicons',
	                    PJ_INSTALL_URL . $dm->getPath('font_awesome') . 'fonts/fontawesome',
	                    PJ_INSTALL_URL,
	                    "pjWrapperFoodDelivery_" . $theme
	                ),
	                $string
	                ) . "\n";
	        }
	    }
	    exit;
	}
	
	public function pjActionLoad()
	{
	    $this->setAjax(false);
	    $this->setLayout('pjActionFront');
	    
	    ob_start();
	    header("Content-Type: text/javascript; charset=utf-8");
	    if($this->_get->toInt('hide') > 0)
	    {
	        $this->session->setData($this->defaultLangMenu, 'hide');
	    }else{
	        $this->session->setData($this->defaultLangMenu, 'show');
		}
		if (!$this->session->getData($this->defaultLocale))
	    {
	        $this->session->setData($this->defaultLocale, $this->_get->toInt('locale'));
	    }
	    $days_off = array();
	    
	    $w_arr = pjWorkingTimeModel::factory()->orderBy("t1.location_id ASC")->findAll()->getData();
	    foreach ($w_arr as $w)
	    {
	        if ($w['p_monday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['pickup'][] = 1;
	        }
	        if ($w['p_tuesday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['pickup'][] = 2;
	        }
	        if ($w['p_wednesday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['pickup'][] = 3;
	        }
	        if ($w['p_thursday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['pickup'][] = 4;
	        }
	        if ($w['p_friday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['pickup'][] = 5;
	        }
	        if ($w['p_saturday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['pickup'][] = 6;
	        }
	        if ($w['p_sunday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['pickup'][] = 0;
	        }
	        
	        if ($w['d_monday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['delivery'][] = 1;
	        }
	        if ($w['d_tuesday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['delivery'][] = 2;
	        }
	        if ($w['d_wednesday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['delivery'][] = 3;
	        }
	        if ($w['d_thursday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['delivery'][] = 4;
	        }
	        if ($w['d_friday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['delivery'][] = 5;
	        }
	        if ($w['d_saturday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['delivery'][] = 6;
	        }
	        if ($w['d_sunday_dayoff'] == 'T')
	        {
	            $days_off[$w['location_id']]['delivery'][] = 0;
	        }
	    }
	    $this->set('days_off', $days_off);
	    
	    $dates_off = $dates_on = array();
	    $d_arr = pjDateModel::factory()
	    ->where("t1.date >= CURDATE()")
	    ->findAll()
	    ->getData();
	    
	    foreach ($d_arr as $date)
	    {
	        if ($date['is_dayoff'] == 'T')
	        {
	            $dates_off[$date['location_id']][$date['type']][] = $date['date'];
	        } else {
	            $dates_on[$date['location_id']][$date['type']][] = $date['date'];
	        }
	    }
	    $this->set('dates_on', $dates_on);
	    $this->set('dates_off', $dates_off);
	}
	
	public function pjActionLocale()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if ($locale_id = $this->_get->toInt('locale_id'))
	        {
	            $this->pjActionSetLocale($locale_id);
	            $this->loadSetFields(true);
	            $day_names = __('day_names', true);
	            ksort($day_names, SORT_NUMERIC);
	            
	            $months = __('months', true);
	            ksort($months, SORT_NUMERIC);
	            
	            pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Locale have been changed.', 'opts' => array(
	                'day_names' => array_values($day_names),
	                'month_names' => array_values($months)
	            )));
	        }
	    }
	    exit;
	}
	
	public function pjActionCaptcha()
	{
	    $this->setAjax(true);
	    
	    header("Cache-Control: max-age=3600, private");
	    $rand = $this->_get->toInt('rand') ?: rand(1, 9999);
	    $patterns = 'app/web/img/button.png';
	    if(!empty($this->option_arr['o_captcha_background_front']) && $this->option_arr['o_captcha_background_front'] != 'plain')
	    {
	        $patterns = PJ_INSTALL_PATH . $this->getConstant('pjBase', 'PLUGIN_IMG_PATH') . 'captcha_patterns/' . $this->option_arr['o_captcha_background_front'];
	    }
	    $Captcha = new pjCaptcha(PJ_INSTALL_PATH . $this->getConstant('pjBase', 'PLUGIN_WEB_PATH') . 'obj/arialbd.ttf', $this->defaultCaptcha, (int) $this->option_arr['o_captcha_length_front']);
	    $Captcha->setImage($patterns)->setMode($this->option_arr['o_captcha_mode_front'])->init($rand);
	    exit;
	}
	
	public function pjActionCheckCaptcha()
	{
	    $this->setAjax(true);
	    if (!$this->_get->check('captcha') || !$this->_get->toString('captcha') || strtoupper($this->_get->toString('captcha')) != $_SESSION[$this->defaultCaptcha]){
	        echo 'false';
	    }else{
	        echo 'true';
	    }
	    exit;
	}
	
	public function pjActionCheckReCaptcha()
	{
	    $this->setAjax(true);
	    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$this->option_arr['o_captcha_secret_key_front'].'&response='.$this->_get->toString('recaptcha'));
	    $responseData = json_decode($verifyResponse);
	    echo $responseData->success ? 'true': 'false';
	    exit;
	}
	
	public function pjActionCheckEmail()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (!$this->_get->check('c_email'))
	        {
	            echo 'false';
	            exit;
	        }
	        $c_email = $this->_get->toString('c_email');
	        if(empty($c_email))
	        {
	            echo 'false';
	            exit;
	        }
			$pjClientModel = pjAuthUserModel::factory()
				->join('pjClient', 't2.foreign_id = t1.id', 'left')
				->where('t1.email', $c_email);
	        if ($this->isFrontLogged())
	        {
	            $pjClientModel->where('t2.foreign_id !=', $this->getClientId());
	        }
	        echo $pjClientModel->findCount()->getData() == 0 ? 'true' : 'false';
	    }
	    exit;
	}
	
	public function pjActionUpdateProfile()
	{
	    $this->isAjax = true;
	    
	    if ($this->isXHR())
	    {
	        if(self::isPost() && $this->_post->check('profile'))
	        {
	            if($this->isFrontLogged())
	            {
    	            $post = $this->_post->raw();
    	            $id = $this->_post->toInt('id');
    	            $pjClientModel = pjClientModel::factory();
    	            pjAuthUserModel::factory()->set('id', $id)->modify($post);
    	            unset($post['id']);
    	            $pjClientModel->where('foreign_id', $this->_post->toInt('id'))->limit(1)->modifyAll($post);
    	            pjFrontClient::init(array('id' => $id))->setClientSession();
    	            pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('front_profile_updated', true)));
	            }else{
	                pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('plugin_base_login_err_ARRAY_3', true)));
	            }
	        }
	    }
	}
	
	public function pjActionLogout()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if($this->isFrontLogged())
	        {
	            $this->session->unsetData($this->defaultClient);
	        }
	        pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	    }
	}
	
	public function pjActionGetPickupLocations()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        $arr = pjLocationModel::factory()
	        ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
	        ->join('pjMultiLang', "t3.foreign_id = t1.id AND t3.model = 'pjLocation' AND t3.locale = '".$this->getLocaleId()."' AND t3.field = 'address'", 'left')
	        ->select('t1.*, t2.content AS name, t3.content AS address')
	        ->findAll()
	        ->getData();
	        pjAppController::jsonResponse($arr);
	    }
	    exit;
	}
	
	public function pjActionGetLocation()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if ($id = $this->_get->toInt('id'))
	        {
	            $arr = pjLocationModel::factory()
	            ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	            ->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjLocation' AND t3.locale = '%u' AND t3.field = 'address'", $this->getLocaleId()), 'left')
	            ->select('t1.*, t2.content AS name, t3.content AS address')
	            ->find($id)
	            ->getData();
	            
	            if ($arr)
	            {
	                $arr['status'] = 'OK';
	            } else {
	                $arr['status'] = 'ERR';
	            }
	            pjAppController::jsonResponse($arr);
	        }
	        
	        pjAppController::jsonResponse(array('status' => 'ERR'));
	    }
	    exit;
	}
	
	public function pjActionGetLocations()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        $pjLocationCoordModel = pjLocationCoordModel::factory();
	        $arr = pjLocationModel::factory()
	        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	        ->join('pjMultiLang', sprintf("t3.foreign_id = t1.id AND t3.model = 'pjLocation' AND t3.locale = '%u' AND t3.field = 'address'", $this->getLocaleId()), 'left')
	        ->select('t1.*, t2.content AS name, t3.content AS address')
	        ->findAll()
	        ->getData();
	        foreach ($arr as $k => $v)
	        {
	            $arr[$k]['coords'] = $pjLocationCoordModel->reset()->where('t1.location_id', $v['id'])->findAll()->getData();
	        }
	        pjAppController::jsonResponse($arr);
	    }
	    exit;
	}
	
	public function pjActionSetTypes()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if($this->_post->check('loadTypes'))
	        {
	            $type = $this->_post->toString('type');
	            $this->_set('type', $type);
	            if($type == 'delivery')
	            {
	                $this->_set('d_date', $this->_post->toString('d_date'));
	                $this->_set('d_time', $this->_post->toString('d_time'));
	                $this->_set('d_location_id', $this->_post->toInt('d_location_id'));
	                
	                $this->_set('d_address_1', $this->_post->check('d_address_1') ? $this->_post->toString('d_address_1') : NULL);
	                $this->_set('d_address_2', $this->_post->check('d_address_2') ? $this->_post->toString('d_address_2') : NULL);
	                $this->_set('d_country_id', $this->_post->check('d_country_id') ? $this->_post->toInt('d_country_id') : NULL);
	                $this->_set('d_state', $this->_post->check('d_state') ? $this->_post->toString('d_state') : NULL);
	                $this->_set('d_city', $this->_post->check('d_city') ? $this->_post->toString('d_city') : NULL);
	                $this->_set('d_zip', $this->_post->check('d_zip') ? $this->_post->toString('d_zip') : NULL);
	                $this->_set('d_notes', $this->_post->check('d_notes') ? $this->_post->toString('d_notes') : NULL);
	                
	                $arr = pjPriceModel::factory()
	                ->where('t1.location_id', $this->_get('d_location_id'))
	                ->where('t1.total_from <= ' . $this->_get('price'))
	                ->where('t1.total_to >= ' . $this->_get('price'))
	                ->limit(1)
	                ->findAll()
	                ->getData();
	                
	                $delivery = 0;
	                if (count($arr) === 1)
	                {
	                    $delivery = $arr[0]['price'];
	                }
	                $this->_set('delivery', $delivery);

	                $unset = array('p_location_id', 'p_date', 'p_time', 'p_notes');
	            }else{
	                $this->_set('p_location_id', $this->_post->toInt('p_location_id'));
	                $this->_set('p_date', $this->_post->toString('p_date'));
	                $this->_set('p_time', $this->_post->toString('p_time'));
	                $this->_set('p_notes', $this->_post->check('p_notes') ? $this->_post->toString('p_notes') : NULL);
	                
	                $unset = array('delivery', 'd_location_id', 'd_date', 'd_time', 'd_address_1', 'd_address_2', 'd_country_id', 'd_state', 'd_city', 'd_zip', 'd_notes');
	            }
	            
	            foreach ($unset as $idx)
	            {
	            	if ($this->_is($idx))
	            	{
	            		$this->_unset($idx);
	            	}
	            }
	        }
	        $this->_unset('voucher_code');
	        $this->_unset('voucher_discount');
	        $this->_unset('voucher_type');
	        pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	    }
	    exit;
	}
	
	public function pjActionCheckLogin()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        $data = array();
	        $data['login_email'] = $this->_post->toString('login_email');
	        $data['login_password'] = $this->_post->toString('login_password');
	        $response = pjFrontClient::init($data)->doClientLogin();
	        $code = (int) $response['code'];
	        $login_err = __('plugin_base_login_err', true);
	        $text = '';
	        if(isset($login_err[$code]))
	        {
	            if($code == 5)
	            {
	                $text = sprintf($login_err[$code], (int)$tpl['option_arr']['o_failed_login_lock_after']);
	            }else{
	                $text = $login_err[$code];
	            }
            }
	        pjAppController::jsonResponse(array('status' => $response['status'], 'code' => $code, 'text' => $text));
	    }
	    exit;
	}
	
	
	public function pjActionSendPassword()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        $params = array();
	        $params['locale_id'] = $this->getLocaleId();
	        $params['email'] = $this->_post->toString('email');
	        $resp = pjFrontClient::init($params)->doSendPassword();
	        pjAppController::jsonResponse($resp);
	    }
	    exit;
	}
	
	public function pjActionSaveForm()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if($this->option_arr['o_captcha_type_front'] == 'system')
	        {
    	        $captcha = $this->_post->toString('captcha');
    	        if ((int) $this->option_arr['o_bf_include_captcha'] === 3 && (!$captcha || ($captcha && !pjCaptcha::validate($captcha, $this->session->getData($this->defaultCaptcha))) ))
    	        {
    	            pjAppController::jsonResponse(array('code' => 110, 'text' => $this->_post->toString('captcha')));
    	        }
	        }
	        if ($this->session->has($this->defaultForm))
	        {
	            $this->session->setData($this->defaultForm, array());
	        }
	        $this->session->setData($this->defaultForm, $this->_post->raw());
	        
	        $resp = array('code' => 200);
	        pjAppController::jsonResponse($resp);
	    }
	}
	
	public function pjActionSaveOrder()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        $STORAGE = $this->session->getData($this->defaultStore);
	        $FORM = $this->session->getData($this->defaultForm);
	        $check_data = array_merge($STORAGE, $FORM);
	        $response = $this->doubleCheckData($check_data);
	        if($response['status'] == 'ERR')
	        {
	            pjAppController::jsonResponse($response);
	        }
	        $cart = $this->_get('cart');
	        
	        $pjOrderModel = pjOrderModel::factory();
	        
	        $data = array();
	        $data['status'] = $this->option_arr['o_booking_status'];
	        $data['price'] = $this->_get('price');
	        $data['price_packing'] = $this->_get('packing');
	        $data['price_delivery'] = $this->_get('delivery');
	        $data['discount'] = $this->_get('discount');
	        $data['subtotal'] = $this->_get('subtotal');
	        $data['tax'] = $this->_get('tax');
	        $data['total'] = $this->_get('total');
	        $data['uuid'] = time();
	        $data['locale_id'] = $this->getLocaleId();
	        $data['ip'] = $_SERVER['REMOTE_ADDR'];
	        $data['phone_no'] = $FORM['c_phone'];
	        $data['first_name'] = $FORM['c_name'];
	        $data['sms_email'] = $FORM['c_email'];
	        // print_r($data);
	        // exit;
	        
	        switch ($this->_get('type'))
	        {
	            case 'pickup':
	                if($this->_get('p_time') == 'asap')
	                {
	                    $data['p_dt'] = pjDateTime::formatDate($this->_get('p_date'), $this->option_arr['o_date_format']) . " " . date('H:i:s');
	                    $data['p_asap'] = "T";
	                }else{
	                    if($this->_is('next_day'))
	                    {
	                        $data['p_dt'] = $this->_get('next_day');
	                    }else{
	                        
	                        $data['p_dt'] = pjDateTime::formatDate($this->_get('p_date'), $this->option_arr['o_date_format']) . " " . pjDateTime::formatTime($this->_get('p_time'), $this->option_arr['o_time_format']);;
	                    }
	                    $data['p_asap'] = "F";
	                }
	                unset($STORAGE['d_address_1']);
	                unset($STORAGE['d_address_2']);
	                unset($STORAGE['d_country_id']);
	                unset($STORAGE['d_state']);
	                unset($STORAGE['d_city']);
	                unset($STORAGE['d_zip']);
	                unset($STORAGE['d_notes']);
	                unset($STORAGE['d_date']);
	                unset($STORAGE['d_time']);
	                $data['location_id'] = $this->_get('p_location_id');
	                break;
	            case 'delivery':
	                if($this->_get('d_time') == 'asap')
	                {
	                    $data['d_dt'] = pjDateTime::formatDate($this->_get('d_date'), $this->option_arr['o_date_format']) . " " . date('H:i:s');
	                    $data['d_asap'] = "T";
	                }else{
	                    if($this->_is('next_day'))
	                    {
	                        $data['d_dt'] = $this->_get('next_day');
	                    }else{
	                        $data['d_dt'] = pjDateTime::formatDate($this->_get('d_date'), $this->option_arr['o_date_format']) . " " . pjDateTime::formatTime($this->_get('d_time'), $this->option_arr['o_time_format']);;
	                    }
	                    $data['d_asap'] = "F";
	                }
	                $data['location_id'] = $this->_get('d_location_id');
	                unset($STORAGE['p_date']);
	                unset($STORAGE['p_time']);
	                unset($STORAGE['p_notes']);
	                break;
	        }
	        
	        unset($STORAGE['cart']);
	        unset($STORAGE['subtotal']);
	        unset($STORAGE['total']);
	        unset($STORAGE['delivery']);
	        
	        $payment = 'none';
	        if(isset($FORM['payment_method']))
	        {
	            $payment = $FORM['payment_method'];
	        }
	        
	        $is_new_client = false;
	        $update_client = false;
	        $pjClientModel = pjClientModel::factory();
	        $data['client_id'] = ':NULL';
	        if($this->isFrontLogged())
	        {
	            $cnt = $pjClientModel
	            ->where('t1.foreign_id', $this->getClientId())
	            ->findCount()
	            ->getData();
	            if($cnt == 0)
	            {
	                $is_new_client = true;
	            }else{
	                $update_client = true;
	            }
	        }else{
	            $is_new_client = true;
	        }
	        if($is_new_client == true)
	        {
	            $FORM['status'] = 'T';
	            $FORM['locale_id'] = $this->getLocaleId();
	            $response = pjFrontClient::init(array_merge($FORM, $STORAGE))->createClient();
	            if(isset($response['client_id']) && (int) $response['client_id'] > 0)
	            {
	                $data['client_id'] = $response['client_id'];
	            }
	        }else{
	            $client = $pjClientModel->reset()->where('foreign_id', $this->getClientId())->findAll()->getDataIndex(0);
	            $data['client_id'] = $client['id'];
	        }
	        if($update_client == true)
	        {
	            $c_data = array();
	            $auth_data = array();
	            if(isset($FORM['c_address_1']))
	            {
	                $c_data['c_address_1'] = $FORM['c_address_1'];
	            }
	            if(isset($FORM['c_address_2']))
	            {
	                $c_data['c_address_1'] = $FORM['c_address_1'];
	            }
	            if(isset($FORM['c_country']))
	            {
	                $c_data['c_country'] = $FORM['c_country'];
	            }
	            if(isset($FORM['c_state']))
	            {
	                $c_data['c_state'] = $FORM['c_state'];
	            }
	            if(isset($FORM['c_city']))
	            {
	                $c_data['c_city'] = $FORM['c_city'];
	            }
	            if(isset($FORM['c_zip']))
	            {
	                $c_data['c_zip'] = $FORM['c_zip'];
	            }
	            if(isset($FORM['c_title']))
	            {
	                $c_data['c_title'] = $FORM['c_title'];
	            }
	            if(isset($FORM['c_company']))
	            {
	                $c_data['c_company'] = $FORM['c_company'];
	            }
	            if(isset($FORM['c_notes']))
	            {
	                $c_data['c_notes'] = $FORM['c_notes'];
	            }
	            if(isset($FORM['c_name']))
	            {
	                $auth_data['name'] = $FORM['c_name'];
	            }
	            if(isset($FORM['c_email']))
	            {
	                $auth_data['email'] = $FORM['c_email'];
	            }
	            if(isset($FORM['c_phone']))
	            {
	                $auth_data['phone'] = $FORM['c_phone'];
	            }
	            if(!empty($auth_data))
	            {
	                $auth_data['id'] = $this->getClientId();
	                pjAuth::init($auth_data)->updateUser();
	            }
	            $pjClientModel->reset()->where('foreign_id', $this->getClientId())->limit(1)->modifyAll($c_data);
	            $client = $pjClientModel->reset()->where('foreign_id', $this->getClientId())->findAll()->getDataIndex(0);
	            pjFrontClient::init(array('id' => $this->getClientId()))->setClientSession();
	            $data['client_id'] = $client['id'];
	        }
	        
	        $data = array_merge($STORAGE, $FORM, $data);
	       
	        $order_id = $pjOrderModel
	        ->setAttributes($data)
	        ->insert()
	        ->getInsertId();
	        if ($order_id !== false && (int) $order_id > 0)
	        {
	        	$id = $order_id;
	        	$id = "W".$id;
		        pjOrderModel::factory()
			        ->where('id', $order_id)
			        ->modifyAll(array(
			            'order_id' => $id
			        ));
	            $pjOrderItemModel = pjOrderItemModel::factory();
	            $pjProductPriceModel = pjProductPriceModel::factory();
	            $pjProductModel = pjProductModel::factory();
	            $pjExtraModel = pjExtraModel::factory();
	            
	            foreach ($cart as $item)
	            {
	                $price_id = ':NULL';
	                $price = 0;
	                if(!empty($item['price_id']))
	                {
	                    $price_arr = $pjProductPriceModel->find($item['price_id'])->getData();
	                    if($price_arr)
	                    {
	                        $price_id = $price_arr['id'];
	                        $price = $price_arr['price'];
	                    }
	                }else{
	                    $price_arr = $pjProductModel->reset()->find($item['product_id'])->getData();
	                    if(!empty($price_arr))
	                    {
	                        $price = $price_arr['price'];
	                    }
	                }
	                
	                $hash = md5(uniqid(rand(), true));
	                $oid = $pjOrderItemModel
	                ->reset()
	                ->setAttributes(array(
	                    'order_id' => $order_id,
	                    'foreign_id' => $item['product_id'],
	                    'type' => 'product',
	                    'price_id' => $price_id,
	                    'price' => $price,
	                    'hash' => $hash,
	                    'cnt' => $item['cnt']))
	                    ->insert();
	                    foreach ($item['extras'] as $extra_id => $extra_cnt)
	                    {
	                        if ($extra_cnt > 0)
	                        {
	                            $extra_price = 0;
	                            $extra_arr = $pjExtraModel
	                            ->reset()
	                            ->find($extra_id)
	                            ->getData();
	                            if(!empty($extra_arr) && !empty($extra_arr['price']))
	                            {
	                                $extra_price = $extra_arr['price'];
	                            }
	                            $pjOrderItemModel
	                            ->reset()
	                            ->setAttributes(array(
	                                'order_id' => $order_id,
	                                'foreign_id' => $extra_id,
	                                'type' => 'extra',
	                                'price_id' => ':NULL',
	                                'price' => $extra_price,
	                                'hash' => $hash,
	                                'cnt' => $extra_cnt))
                                ->insert();
	                        }
	                    }
	            }
	            
	            $order_arr = $pjOrderModel
	            ->reset()
	            ->join('pjClient', "t2.id=t1.client_id", 'left outer')
	            ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
	            ->select("t1.*, t2.c_title, t3.email as c_email, t3.name AS c_name, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes,
							AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
							AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
							AES_DECRYPT(t1.cc_exp, '".PJ_SALT."') AS `cc_exp`,
							AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
				->find($order_id)
				->getData();
				
				$pdata = array();
				$pdata['order_id'] = $order_id;
				$pdata['payment_method'] = $payment;
				$pdata['payment_type'] = 'online';
				$pdata['amount'] = $order_arr['total'];
				$pdata['status'] = 'notpaid';
				pjOrderPaymentModel::factory()->setAttributes($pdata)->insert();
				
				pjAppController::addOrderDetails($order_arr, $this->getLocaleId());
				
				pjFrontEnd::pjActionConfirmSend($this->option_arr, $order_arr, PJ_SALT, 'confirmation', $this->getLocaleId());
				
				$this->session->unsetData($this->defaultStore);
				$this->session->unsetData($this->defaultForm);
				$this->session->unsetData($this->defaultCaptcha);
				
				$json = array('code' => 200, 'text' => __('front_messages_ARRAY_4', true), 'order_id' => $order_id, 'payment' => $payment);
	        }else{
	            $json = array('code' => 100, 'text' => '');
	        }
	        
	        pjAppController::jsonResponse($json);
	    }
	}
	
	public function pjActionConfirm()
	{
	    $this->setAjax(true);
	 
	    if (pjObject::getPlugin('pjPayments') === NULL)
	    {
	        $this->log('pjPayments plugin not installed');
	        exit;
	    }
	    
	    $pjPayments = new pjPayments();
	    $post = $this->_post->raw();
	    $get = $this->_get->raw();
	    $request = array();
	    if(isset($get['payment_method']))
	    {
	        $request = $get;
	    }
	    if(isset($post['payment_method']))
	    {
	        $request = $post;
	    }
	    if($pjPlugin = $pjPayments->getPaymentPlugin($request))
	    {
	        if($uuid = $this->requestAction(array('controller' => $pjPlugin, 'action' => 'pjActionGetCustom', 'params' => $request), array('return')))
	        {
	            $pjOrderModel = pjOrderModel::factory();
	            $order_arr = $pjOrderModel
	            ->join('pjClient', "t2.id=t1.client_id", 'left outer')
	            ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer')
	            ->select("t1.*, t2.c_title, t3.email AS c_email, t3.name AS c_name, t3.phone AS c_phone, t2.c_company, t2.c_address_1, t2.c_address_2, t2.c_country, t2.c_state, t2.c_city, t2.c_zip, t2.c_notes,
					AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
					AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
					AES_DECRYPT(t1.cc_exp, '".PJ_SALT."') AS `cc_exp`,
					AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`")
				->where('t1.uuid', $uuid)
				->limit(1)
				->findAll()->getDataIndex(0);
				
				if (!empty($order_arr))
	            {
	                $params = array(
	                    'request'		=> $request,
	                    'payment_method' => $request['payment_method'],
	                    'foreign_id'	 => $this->getForeignId(),
	                    'amount'		 => $order_arr['total'],
	                    'txn_id'		 => $order_arr['txn_id'],
	                    'order_id'	   => $order_arr['id'],
	                    'cancel_hash'	=> sha1($order_arr['uuid'].strtotime($order_arr['created']).PJ_SALT),
	                    'key'			=> md5($this->option_arr['private_key'] . PJ_SALT)
	                );
	                $response = $this->requestAction(array('controller' => $pjPlugin, 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
	                if($response['status'] == 'OK')
	                {
	                    $this->log("Payments | {$pjPlugin} plugin<br>Order was confirmed. UUID: {$uuid}");
	                    
	                    $pjOrderModel->reset()
	                    ->setAttributes(array('id' => $order_arr['id']))
	                    ->modify(array('status' => $this->option_arr['o_payment_status'], 'is_paid' => 1, 'processed_on' => ':NOW()'));
	                    
	                    pjOrderPaymentModel::factory()->setAttributes(array('order_id' => $order_arr['id'], 'payment_type' => 'online'))
	                    ->modify(array('status' => 'paid'));
	                    $locale_id = $this->getLocaleId();
	                    if((int) $order_arr['locale_id'])
	                    {
	                        $locale_id = $order_arr['locale_id'];
	                    }
	                    pjAppController::addOrderDetails($order_arr, $locale_id);
	                    pjFrontEnd::pjActionConfirmSend($this->option_arr, $order_arr, PJ_SALT, 'payment', $this->getLocaleId());
	                    
	                    echo $this->option_arr['o_thankyou_page'];
	                    exit;
	                }elseif($response['status'] == 'CANCEL'){
	                    $this->log("Payments | {$pjPlugin} plugin<br>Payment was cancelled. UUID: {$uuid}");
	                    
	                    $pjOrderModel->reset()
	                    ->setAttributes(array('id' => $order_arr['id']))
	                    ->modify(array('status' => 'cancelled', 'processed_on' => ':NOW()'));
	                    
	                    pjAppController::addOrderDetails($order_arr, $this->getLocaleId());
	                    pjFrontEnd::pjActionConfirmSend($this->option_arr, $order_arr, PJ_SALT, 'cancel', $this->getLocaleId());
	                    
	                    echo $this->option_arr['o_thankyou_page'];
	                    exit;
	                }else{
	                    $this->log("Payments | {$pjPlugin} plugin<br>Order confirmation was failed. UUID: {$uuid}");
	                }
	                
	                if(isset($response['redirect']) && $response['redirect'] == true)
	                {
	                    echo $this->option_arr['o_thankyou_page'];
	                    exit;
	                }
	            }else{
	                $this->log("Payments | {$pjPlugin} plugin<br>Reservation with UUID {$uuid} not found.");
	            }
	            echo $this->option_arr['o_thankyou_page'];
	            exit;
	        }
	    }
	    echo $this->option_arr['o_thankyou_page'];
	    exit;
	}
	public static function pjActionGetSubjectMessage($notification_id, $locale_id)
	{
	    $pjMultiLangModel = pjMultiLangModel::factory();
	    $lang_message = $pjMultiLangModel
	    ->reset()
	    ->select('t1.*')
	    ->where('t1.foreign_id', $notification_id)
	    ->where('t1.model','pjNotification')
	    ->where('t1.locale', $locale_id)
	    ->where('t1.field', 'message')
	    ->limit(0, 1)
	    ->findAll()
	    ->getData();
	    $lang_subject = $pjMultiLangModel
	    ->reset()
	    ->select('t1.*')
	    ->where('t1.foreign_id',  $notification_id)
	    ->where('t1.model','pjNotification')
	    ->where('t1.locale', $locale_id)
	    ->where('t1.field', 'subject')
	    ->limit(0, 1)
	    ->findAll()
	    ->getData();
	    return compact('lang_message', 'lang_subject');
	}
	public static function pjActionConfirmSend($option_arr, $data, $salt, $opt, $locale)
	{
	    $Email = self::getMailer($option_arr);
	    
	    $pjMultiLangModel = pjMultiLangModel::factory();
	    
	    $admin_email = pjAppController::getAdminEmail();
	    $admin_phone = pjAppController::getAdminPhone();
	    $locale_id = isset($data['locale_id']) && (int) $data['locale_id'] > 0 ? (int) $data['locale_id'] : $locale;
	    
	    $pjNotificationModel = pjNotificationModel::factory();
	    
	    if($opt == 'account' || $opt == 'forgot')
	    {
	        $notification = $pjNotificationModel->reset()->where('recipient', 'client')->where('transport', 'email')->where('variant', $opt)->findAll()->getDataIndex(0);
	        if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
	        {
    	        $tokens = pjAppController::getClientTokens($option_arr, $data, PJ_SALT, $locale_id);
    	        $resp = pjFrontEnd::pjActionGetSubjectMessage($notification['id'], $locale_id);
    	        $lang_message = $resp['lang_message'];
    	        $lang_subject = $resp['lang_subject'];
    	        $client = pjClientModel::factory()->find($data)->getData();
    	        $auth_client = pjAuthUserModel::factory()->find($client['foreign_id'])->getData();
    	        if (count($lang_message) === 1 && count($lang_subject) === 1 && !empty($auth_client['email']))
    	        {
    	            $message = preg_replace('/\[Delivery\].*\[\/Delivery\]/s', '', $lang_message[0]['content']);
    	            $message = str_replace($tokens['search'], $tokens['replace'], $message);
    	            $Email
    	            ->setTo($auth_client['email'])
    	            ->setSubject($lang_subject[0]['content'])
    	            ->send($message);
    	        }
	        }
	    }else{
	        $tokens = pjAppController::getTokens($option_arr, $data, PJ_SALT, $locale_id);
            /*Confirmation sent to clients*/
            $notification = $pjNotificationModel->reset()->where('recipient', 'client')->where('transport', 'email')->where('variant', $opt)->findAll()->getDataIndex(0);
            if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
            {
	            $resp = pjFrontEnd::pjActionGetSubjectMessage($notification['id'], $locale_id);
	            $lang_message = $resp['lang_message'];
	            $lang_subject = $resp['lang_subject'];
	            if (count($lang_message) === 1 && count($lang_subject) === 1)
	            {
	                if ($data['type'] == 'delivery')
	                {
	                    $message = str_replace(array('<br />[Delivery]', '<br />[/Delivery]'), array('', ''), $lang_message[0]['content']);
	                    $message = str_replace(array('[Delivery]<br />', '[/Delivery]<br />'), array('', ''), $message);
	                    $message = str_replace(array('[Delivery]', '[/Delivery]'), array('', ''), $message);
	                } else {
	                    $message = preg_replace('/\[Delivery\].*\[\/Delivery\]/s', '', $lang_message[0]['content']);
	                }
	                $message = str_replace($tokens['search'], $tokens['replace'], $message);
	                $Email
	                ->setTo($data['c_email'])
	                ->setSubject($lang_subject[0]['content'])
	                ->send($message);
	            }
            }
            /*Confirmation sent to admin*/
            $notification = $pjNotificationModel->reset()->where('recipient', 'admin')->where('transport', 'email')->where('variant', $opt)->findAll()->getDataIndex(0);
            if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
            {
                $resp = pjFrontEnd::pjActionGetSubjectMessage($notification['id'], $locale_id);
                $lang_message = $resp['lang_message'];
                $lang_subject = $resp['lang_subject'];
                if (count($lang_message) === 1 && count($lang_subject) === 1)
                {
                    if ($data['type'] == 'delivery')
                    {
                        $message = str_replace(array('<br />[Delivery]', '<br />[/Delivery]'), array('', ''), $lang_message[0]['content']);
                        $message = str_replace(array('[Delivery]<br />', '[/Delivery]<br />'), array('', ''), $message);
                        $message = str_replace(array('[Delivery]', '[/Delivery]'), array('', ''), $message);
                    } else {
                        $message = preg_replace('/\[Delivery\].*\[\/Delivery\]/s', '', $lang_message[0]['content']);
                    }
                    $message = str_replace($tokens['search'], $tokens['replace'], $message);
                    
                    $Email
                    ->setTo($admin_email)
                    ->setSubject($lang_subject[0]['content'])
                    ->send($message);
                }
            }
            /*SMS sent to client*/
            if(!empty($data['c_phone']))
            {
                $notification = $pjNotificationModel->reset()->where('recipient', 'client')->where('transport', 'sms')->where('variant', $opt)->findAll()->getDataIndex(0);
                if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
                {
	                $lang_message = $pjMultiLangModel
	                ->reset()
	                ->select('t1.*')
	                ->where('t1.foreign_id', $notification['id'])
	                ->where('t1.model','pjNotification')
	                ->where('t1.locale', $locale_id)
	                ->where('t1.field', 'message')
	                ->limit(0, 1)
	                ->findAll()
	                ->getData();
	                if (count($lang_message) === 1)
	                {
	                    $message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
	                    $params = array(
	                        'text' => $message,
	                        'type' => 'unicode',
	                        'key' => md5($option_arr['private_key'] . PJ_SALT)
	                    );
	                    $params['number'] = $data['c_phone'];
	                    pjBaseSms::init($params)->pjActionSend();
	                }
                }
            }
            /*SMS sent to admin*/
            if(!empty($admin_phone))
            {
                $notification = $pjNotificationModel->reset()->where('recipient', 'admin')->where('transport', 'sms')->where('variant', $opt)->findAll()->getDataIndex(0);
                if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
                {
                    $lang_message = $pjMultiLangModel
                    ->reset()
                    ->select('t1.*')
                    ->where('t1.foreign_id', $notification['id'])
                    ->where('t1.model','pjNotification')
                    ->where('t1.locale', $locale_id)
                    ->where('t1.field', 'message')
                    ->limit(0, 1)
                    ->findAll()
                    ->getData();
                    if (count($lang_message) === 1)
                    {
                        $message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
                        $params = array(
                            'text' => $message,
                            'type' => 'unicode',
                            'key' => md5($option_arr['private_key'] . PJ_SALT)
                        );
                        $params['number'] = $admin_phone;
                        pjBaseSms::init($params)->pjActionSend();
                    }
                }
            }
	    }
	}
	
	private function doubleCheckData($data)
	{
	    $double_check_error = __('double_check_error', true);
	    if($this->option_arr['o_captcha_type_front'] == 'system')
	    {
	        if((int) $this->option_arr['o_bf_include_captcha'] === 3 && !isset($data['captcha']))
	        {
	            return array('status' => 'ERR', 'code' => 101, 'text' => $double_check_error[101]);
	        }
	        if((int) $this->option_arr['o_bf_include_captcha'] === 3 && !pjValidation::pjActionNotEmpty($data['captcha']))
	        {
	            return array('status' => 'ERR', 'code' => 102, 'text' => $double_check_error[102]);
	        }
	        if((int) $this->option_arr['o_bf_include_captcha'] === 3 && !pjCaptcha::validate($data['captcha'], $this->session->getData($this->defaultCaptcha)))
	        {
	            return array('status' => 'ERR', 'code' => 103, 'text' => $double_check_error[103]);
	        }
	    }else{
	        if((int) $this->option_arr['o_bf_include_captcha'] === 3 && !isset($data['recaptcha']))
	        {
	            return array('status' => 'ERR', 'code' => 101, 'text' => $double_check_error[101]);
	        }
	        if((int) $this->option_arr['o_bf_include_captcha'] === 3 && !pjValidation::pjActionNotEmpty($data['recaptcha']))
	        {
	            return array('status' => 'ERR', 'code' => 102, 'text' => $double_check_error[102]);
	        }
	    }
	    if($data['type'] == 'delivery')
	    {
	        if(!isset($data['d_location_id']))
	        {
	            return array('status' => 'ERR', 'code' => 106, 'text' => $double_check_error[104]);
	        }
	        if((int) $data['d_location_id'] <= 0)
	        {
	            return array('status' => 'ERR', 'code' => 107, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_address_1'] === 3 && !isset($data['d_address_1']))
	        {
	            return array('status' => 'ERR', 'code' => 108, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_address_1'] === 3 && !pjValidation::pjActionNotEmpty($data['d_address_1']))
	        {
	            return array('status' => 'ERR', 'code' => 109, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_address_2'] === 3 && !isset($data['d_address_2']))
	        {
	            return array('status' => 'ERR', 'code' => 110, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_address_2'] === 3 && !pjValidation::pjActionNotEmpty($data['d_address_2']))
	        {
	            return array('status' => 'ERR', 'code' => 111, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_city'] === 3 && !isset($data['d_city']))
	        {
	            return array('status' => 'ERR', 'code' => 112, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_city'] === 3 && !pjValidation::pjActionNotEmpty($data['d_city']))
	        {
	            return array('status' => 'ERR', 'code' => 113, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_state'] === 3 && !isset($data['d_state']))
	        {
	            return array('status' => 'ERR', 'code' => 114, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_state'] === 3 && !pjValidation::pjActionNotEmpty($data['d_state']))
	        {
	            return array('status' => 'ERR', 'code' => 115, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_zip'] === 3 && !isset($data['d_zip']))
	        {
	            return array('status' => 'ERR', 'code' => 116, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_zip'] === 3 && !pjValidation::pjActionNotEmpty($data['d_zip']))
	        {
	            return array('status' => 'ERR', 'code' => 117, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_country'] === 3 && !isset($data['d_country_id']))
	        {
	            return array('status' => 'ERR', 'code' => 118, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_country'] === 3 && (int) $data['d_country_id'] <= 0)
	        {
	            return array('status' => 'ERR', 'code' => 119, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_notes'] === 3 && !isset($data['d_notes']))
	        {
	            return array('status' => 'ERR', 'code' => 120, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_df_include_notes'] === 3 && !pjValidation::pjActionNotEmpty($data['d_notes']))
	        {
	            return array('status' => 'ERR', 'code' => 121, 'text' => $double_check_error[104]);
	        }
	    }else{
	        if((int) $this->option_arr['o_pf_include_notes'] === 3 && !isset($data['p_notes']))
	        {
	            return array('status' => 'ERR', 'code' => 104, 'text' => $double_check_error[104]);
	        }
	        if((int) $this->option_arr['o_pf_include_notes'] === 3 && !pjValidation::pjActionNotEmpty($data['p_notes']))
	        {
	            return array('status' => 'ERR', 'code' => 105, 'text' => $double_check_error[104]);
	        }
	    }
	    if((int) $this->option_arr['o_bf_include_address_1'] === 3 && !isset($data['c_address_1']))
	    {
	        return array('status' => 'ERR', 'code' => 122, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_address_1'] === 3 && !pjValidation::pjActionNotEmpty($data['c_address_1']))
	    {
	        return array('status' => 'ERR', 'code' => 123, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_address_2'] === 3 && !isset($data['c_address_2']))
	    {
	        return array('status' => 'ERR', 'code' => 124, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_address_2'] === 3 && !pjValidation::pjActionNotEmpty($data['c_address_2']))
	    {
	        return array('status' => 'ERR', 'code' => 125, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_city'] === 3 && !isset($data['c_city']))
	    {
	        return array('status' => 'ERR', 'code' => 126, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_city'] === 3 && !pjValidation::pjActionNotEmpty($data['c_city']))
	    {
	        return array('status' => 'ERR', 'code' => 127, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_state'] === 3 && !isset($data['c_state']))
	    {
	        return array('status' => 'ERR', 'code' => 128, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_state'] === 3 && !pjValidation::pjActionNotEmpty($data['c_state']))
	    {
	        return array('status' => 'ERR', 'code' => 129, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_zip'] === 3 && !isset($data['c_zip']))
	    {
	        return array('status' => 'ERR', 'code' => 130, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_zip'] === 3 && !pjValidation::pjActionNotEmpty($data['c_zip']))
	    {
	        return array('status' => 'ERR', 'code' => 131, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_country'] === 3 && !isset($data['c_country']))
	    {
	        return array('status' => 'ERR', 'code' => 132, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_country'] === 3 && (int) $data['c_country'] <= 0)
	    {
	        return array('status' => 'ERR', 'code' => 133, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_title'] === 3 && !isset($data['c_title']))
	    {
	        return array('status' => 'ERR', 'code' => 134, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_title'] === 3 && !pjValidation::pjActionNotEmpty($data['c_title']))
	    {
	        return array('status' => 'ERR', 'code' => 135, 'text' => $double_check_error[104]);
	    }
	    $name_titles = __('personal_titles', true, false);
	    if((int) $this->option_arr['o_bf_include_title'] === 3 && !array_key_exists($data['c_title'],$name_titles))
	    {
	        return array('status' => 'ERR', 'code' => 136, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_name'] === 3 && !isset($data['c_name']))
	    {
	        return array('status' => 'ERR', 'code' => 137, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_name'] === 3 && !pjValidation::pjActionNotEmpty($data['c_name']))
	    {
	        return array('status' => 'ERR', 'code' => 138, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_email'] === 3 && !isset($data['c_email']))
	    {
	        return array('status' => 'ERR', 'code' => 139, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_email'] === 3 && !pjValidation::pjActionNotEmpty($data['c_email']))
	    {
	        return array('status' => 'ERR', 'code' => 140, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_email'] === 3 && !pjValidation::pjActionEmail($data['c_email']))
	    {
	        return array('status' => 'ERR', 'code' => 141, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_phone'] === 3 && !isset($data['c_phone']))
	    {
	        return array('status' => 'ERR', 'code' => 142, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_phone'] === 3 && !pjValidation::pjActionNotEmpty($data['c_phone']))
	    {
	        return array('status' => 'ERR', 'code' => 143, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_company'] === 3 && !isset($data['c_company']))
	    {
	        return array('status' => 'ERR', 'code' => 144, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_company'] === 3 && !pjValidation::pjActionNotEmpty($data['c_company']))
	    {
	        return array('status' => 'ERR', 'code' => 145, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_notes'] === 3 && !isset($data['c_notes']))
	    {
	        return array('status' => 'ERR', 'code' => 146, 'text' => $double_check_error[104]);
	    }
	    if((int) $this->option_arr['o_bf_include_notes'] === 3 && !pjValidation::pjActionNotEmpty($data['c_notes']))
	    {
	        return array('status' => 'ERR', 'code' => 147, 'text' => $double_check_error[104]);
	    }
	    if($this->option_arr['o_payment_disable'] == 'No')
	    {
	        if(!isset($data['payment_method']))
	        {
	            return array('status' => 'ERR', 'code' => 148, 'text' => $double_check_error[104]);
	        }
	        if(!pjValidation::pjActionNotEmpty($data['payment_method']))
	        {
	            return array('status' => 'ERR', 'code' => 149, 'text' => $double_check_error[104]);
	        }
	    }
	    return array('status' => 'OK', 'code' => 200, 'text' => "");
	}
	


}
?>