<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdmin extends pjAppController
{
	public $defaultUser = 'admin_user';
	
	public $thumbSize = 'thumb_size';
	
	public $previewSize = 'preview_size';
	
	public $requireLogin = true;
	
	public function __construct($requireLogin=null)
	{
		$this->setLayout('pjActionAdmin');
		
		if (!is_null($requireLogin) && is_bool($requireLogin))
		{
			$this->requireLogin = $requireLogin;
		}
		
		if ($this->requireLogin)
		{
			if (!$this->isLoged() && !in_array(@$_GET['action'], array('pjActionLogin', 'pjActionForgot', 'pjActionPreview')))
			{
				if (!$this->isXHR())
				{
			    	pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjBase&action=pjActionLogin");
				} else {
					header('HTTP/1.1 401 Unauthorized');
					exit;
				}
			}
		}
		
		$inherits_arr = array(
			'pjAdminOrders::pjActionFormatPrice' => 'pjAdminOrders::pjActionCreate',
			'pjAdminOrders::pjActionGetExtras' => 'pjAdminOrders::pjActionCreate',
			'pjAdminOrders::pjActionGetOrder' => 'pjAdminOrders::pjActionIndex',
			'pjAdminOrders::pjActionGetPrices' => 'pjAdminOrders::pjActionCreate',
		    'pjAdminOrders::pjActionGetTotal' => 'pjAdminOrders::pjActionCreate',
		    'pjAdminOrders::pjActionGetClient' => 'pjAdminOrders::pjActionCreate',
		    'pjAdminOrders::pjActionCheckPickup' => 'pjAdminOrders::pjActionCreate',
		    'pjAdminOrders::pjActionCheckDelivery' => 'pjAdminOrders::pjActionCreate',
			'pjAdminOrders::pjActionCheckClientEmail' => 'pjAdminOrders::pjActionCreate',
			'pjAdminOrders::pjActionPrintOrder' => 'pjAdminOrders::pjActionUpdate',
			'pjAdminOrders::pjActionReminderEmail' => 'pjAdminOrders::pjActionUpdate',
		    'pjAdminCategories::pjActionCreate' => 'pjAdminCategories::pjActionCreateForm',
		    'pjAdminExtras::pjActionCreate' => 'pjAdminExtras::pjActionCreateForm',
			'pjAdminExtras::pjActionUpdate' => 'pjAdminExtras::pjActionCreateForm',
			'pjAdminExtras::pjActionGetExtra' => 'pjAdminExtras::pjActionIndex',
			'pjAdminExtras::pjActionSaveExtra' => 'pjAdminExtras::pjActionIndex',
		    'pjAdminClients::pjActionGetClient' => 'pjAdminClients::pjActionIndex',
            'pjAdminClients::pjActionCheckEmail' => 'pjAdminClients::pjActionCreate',
            'pjAdminClients::pjActionSaveClient' => 'pjAdminClients::pjActionUpdate',
			'pjAdminOptions::pjActionNotificationsSetContent' => 'pjAdminOptions::pjActionNotifications',
			'pjAdminOptions::pjActionNotificationsGetContent' => 'pjAdminOptions::pjActionNotifications',
			'pjAdminOptions::pjActionNotificationsGetMetaData' => 'pjAdminOptions::pjActionNotifications',
			'pjAdminOptions::pjActionPaymentOptions' => 'pjAdminOptions::pjActionPayments',
			'pjAdminTime::pjActionGetDate' => 'pjAdminTime::pjActionIndex',
		    'pjAdminTime::pjActionSaveCustom' => 'pjAdminTime::pjActionIndex',
		    'pjAdminTime::pjActionResetForm' => 'pjAdminTime::pjActionIndex',
		    'pjAdminTime::pjActionGetCustomDate' => 'pjAdminTime::pjActionIndex',
			'pjAdminReports::pjActionGenerate' => 'pjAdminReports::pjActionIndex',
			'pjAdminReports::pjActionPrint' => 'pjAdminReports::pjActionIndex',		
			'pjAdminLocations::pjActionGetLocation' => 'pjAdminLocations::pjActionIndex',
			'pjAdminLocations::pjActionSaveLocation' => 'pjAdminLocations::pjActionIndex',
			'pjAdminLocations::pjActionGetCoords' => 'pjAdminLocations::pjActionCreate',		
			'pjAdmin::pjActionVerifyAPIKey' => 'pjBaseOptions::pjActionApiKeys',
			'pjAdminTime::pjActionCheckDefaultTime' => 'pjAdminTime::pjActionIndex',
			'pjAdminTime::pjActionCheckCustomTime' => 'pjAdminTime::pjActionIndex',
			'pjAdminTime::pjActionDeleteDate' => 'pjAdminTime::pjActionIndex',
			'pjAdminTime::pjActionDeleteDateBulk' => 'pjAdminTime::pjActionIndex',
			'pjAdminCategories::pjActionGetCategory' => 'pjAdminCategories::pjActionIndex',
			'pjAdminCategories::pjActionUpdate' => 'pjAdminCategories::pjActionIndex',
			'pjAdminCategories::pjActionSaveCategory' => 'pjAdminCategories::pjActionIndex',
			'pjAdminProducts::pjActionGetProduct' => 'pjAdminProducts::pjActionIndex',
			'pjAdminProducts::pjActionSaveProduct' => 'pjAdminProducts::pjActionIndex',
			'pjAdminProducts::pjActionDeleteImage' => 'pjAdminProducts::pjActionUpdate',
		    'pjAdminProducts::pjActionCheckPrices' => 'pjAdminProducts::pjActionCreate',
		    'pjAdminProducts::pjActionCheckPrices' => 'pjAdminProducts::pjActionUpdate',
			'pjAdminOptions::pjActionDeliveryForm' => 'pjAdminOptions::pjActionOrderForm'
		);
		
		if ($_REQUEST['controller'] == 'pjAdminOptions' && isset($_REQUEST['next_action'])) {
			switch ($_REQUEST['next_action'])
			{
				case 'pjActionOrders':
					$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::pjActionOrders';
					break;
				case 'pjActionTerm':
					$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::pjActionTerm';
					break;
				case 'pjActionOrderForm':
					$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::pjActionOrderForm';
					break;
				case 'pjActionDeliveryForm':
					$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::pjActionOrderForm';
					break;
				case 'pjActionPrintOrder':
					$inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::pjActionPrintOrder';
					break;
			}
		}
		pjRegistry::getInstance()->set('inherits', $inherits_arr);
	}
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		
		if (@$_REQUEST['controller'] == 'pjAdmin' && @$_REQUEST['action'] == 'pjActionMessages') {
			return true;
		} else {
			if (!pjAuth::factory()->hasAccess())
			{
				$this->sendForbidden();
				return false;
			}
			return true;
		}
	}

	public function afterFilter()
	{
		parent::afterFilter();
		if ($this->isLoged() && !in_array(@$_REQUEST['action'], array('pjActionLogin')))
		{
		    $this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		}
    }

	public function beforeRender()
	{
		
	}

	public function pjActionMessages()
	{
		$this->setAjax(true);
		header("Content-Type: text/javascript; charset=utf-8");
	}

	public function setLocalesData()
    {
        $locale_arr = pjLocaleModel::factory()
            ->select('t1.*, t2.file')
            ->join('pjBaseLocaleLanguage', 't2.iso=t1.language_iso', 'left')
            ->where('t2.file IS NOT NULL')
            ->orderBy('t1.sort ASC')->findAll()->getData();

        $lp_arr = array();
        foreach ($locale_arr as $item)
        {
            $lp_arr[$item['id']."_"] = $item['file'];
        }
        $this->set('lp_arr', $locale_arr);
        $this->set('locale_str', pjAppController::jsonEncode($lp_arr));
        $this->set('is_flag_ready', $this->requestAction(array('controller' => 'pjBaseLocale', 'action' => 'pjActionIsFlagReady'), array('return')));
    }

    public function pjActionVerifyAPIKey()
    {
        $this->setAjax(true);

        if ($this->isXHR())
        {
            if (!self::isPost())
            {
                self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('plugin_base_api_key_text_ARRAY_100', true)));
            }

            $option_key = $this->_post->toString('key');
            if (!array_key_exists($option_key, $this->option_arr))
            {
                self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => __('plugin_base_api_key_text_ARRAY_101', true)));
            }

            $option_value = $this->_post->toString('value');
            if(empty($option_value))
            {
                self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => __('plugin_base_api_key_text_ARRAY_102', true)));
            }

            $html = '';
            $isValid = false;
            switch ($option_key)
            {
                case 'o_google_maps_api_key':
                    $address = preg_replace('/\s+/', '+', $this->option_arr['o_timezone']);
                    $api_key_str = $option_value;
                    $gfile = "https://maps.googleapis.com/maps/api/geocode/json?key=".$api_key_str."&address=".$address;
                    $Http = new pjHttp();
                    $response = $Http->request($gfile)->getResponse();
                    $geoObj = pjAppController::jsonDecode($response);
                    $geoArr = (array) $geoObj;
                    if ($geoArr['status'] == 'OK')
                    {
                        $isValid = true;
                    }
                    break;
                default:
                    // API key for an unknown service. We can't verify it so we assume it's correct.
                    $isValid = true;
            }

            if ($isValid)
            {
                self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('plugin_base_api_key_text_ARRAY_200', true), 'html' => $html));
            }
            else
            {
                self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => __('plugin_base_api_key_text_ARRAY_103', true), 'html' => $html));
            }
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
        
        $pjOrderModel = pjOrderModel::factory();
        
        $cnt_delivery_orders = $pjOrderModel->where("type", "delivery")->where("DATE(t1.d_dt) = CURDATE()")->findCount()->getData();
        $amount_delivery_orders = $pjOrderModel->reset()->select("SUM(total) AS amount")->where("type", "delivery")->where("DATE(t1.d_dt) = CURDATE()")->findAll()->getData();
        $cnt_pickup_orders = $pjOrderModel->reset()->where("type", "pickup")->where("DATE(t1.p_dt) = CURDATE()")->findCount()->getData();
        $amount_pickup_orders = $pjOrderModel->reset()->select("SUM(total) AS amount")->where("type", "pickup")->where("DATE(t1.p_dt) = CURDATE()")->findAll()->getData();
        $cnt_orders = $pjOrderModel->reset()->findCount()->getData();
        $amount_orders = $pjOrderModel->reset()->select("SUM(total) AS amount")->findAll()->getData();
        
        $this->set('cnt_delivery_orders', $cnt_delivery_orders);
        $this->set('amount_delivery_orders', !empty($amount_delivery_orders) ? $amount_delivery_orders[0]['amount'] : 0);
        $this->set('cnt_pickup_orders', $cnt_pickup_orders);
        $this->set('amount_pickup_orders', !empty($amount_pickup_orders) ? $amount_pickup_orders[0]['amount'] : 0);
        $this->set('cnt_orders', $cnt_orders);
        $this->set('amount_orders', !empty($amount_orders) ? $amount_orders[0]['amount'] : 0);
        
        $latest_delivery = $pjOrderModel
        ->reset()
        ->join('pjClient', "t2.id=t1.client_id", 'left outer')
        ->join('pjMultiLang', sprintf("t3.model='pjLocation' AND t3.foreign_id=t1.location_id AND t3.field='name' AND t3.locale='%u'", $this->getLocaleId()), 'left outer')
        ->join('pjAuthUser', "t4.id=t2.foreign_id", 'left outer')
        ->select('t1.*, t4.name AS client_name, t3.content as location')
        ->where("type", "delivery")
        ->orderBy("d_dt DESC")
        ->limit(6)
        ->findAll()
        ->getData();
        
        $latest_pickup = $pjOrderModel
        ->reset()
        ->join('pjClient', "t2.id=t1.client_id", 'left outer')
        ->join('pjMultiLang', sprintf("t3.model='pjLocation' AND t3.foreign_id=t1.location_id AND t3.field='name' AND t3.locale='%u'", $this->getLocaleId()), 'left outer')
        ->join('pjAuthUser', "t4.id=t2.foreign_id", 'left outer')
        ->select('t1.*, t4.name AS client_name, t3.content as location')
        ->where("type", "pickup")
        ->orderBy("p_dt DESC")
        ->limit(6)
        ->findAll()
        ->getData();
        
        $this->set('latest_delivery', $latest_delivery);
        $this->set('latest_pickup', $latest_pickup);
        
        $location_arr = pjWorkingTimeModel::factory()
        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.location_id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
        ->select('t1.*, t2.content as location_title')
        ->findAll()
        ->getData();
        $week_day = strtolower(date("l"));
        $current_time = time();
        $current_date = date('Y-m-d');
        foreach($location_arr as $k => $v)
        {
        	$p_start_time = strtotime($current_date . ' ' . $v['p_' . $week_day . '_from']);
			$p_end_time = strtotime($current_date . ' ' . $v['p_' . $week_day . '_to']);	
        	if($p_end_time < $p_start_time)
			{
				$p_end_time += 86400;
			}			
            if($p_start_time <= $current_time && $current_time <= $p_end_time)
            {
                $v['pickup'] = __('lblOpened', true);
            }else{
                $v['pickup'] = __('lblClosed', true);
            }
            
            $d_start_time = strtotime($current_date . ' ' . $v['d_' . $week_day . '_from']);
			$d_end_time = strtotime($current_date . ' ' . $v['d_' . $week_day . '_to']);	
        	if($d_end_time < $d_start_time)
			{
				$d_end_time += 86400;
			}	
            if($d_start_time <= $current_time && $current_time <= $d_end_time)
            {
                $v['delivery'] = __('lblOpened', true);
            }else{
                $v['delivery'] = __('lblClosed', true);
            }
            $location_arr[$k] = $v;
        }
        $this->set('location_arr', $location_arr);
        
        $this->appendCss('dashboard.css');
    }
}
?>