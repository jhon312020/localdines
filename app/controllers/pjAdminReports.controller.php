<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminReports extends pjAdmin {		
	public function pjActionIndex() {
  	$this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isGet()) {
      $location_arr = pjLocationModel::factory()
      ->select('t1.*, t2.content as name')
      ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
      ->orderBy("name ASC")
      ->findAll()
      ->getData();
      
      $date_from = date('Y-m-d', time() - 30 * 86400 );
      $date_to = date('Y-m-d');
      
      $this->set('location_arr', $location_arr);
      $this->set('date_from', $date_from);
      $this->set('date_to', $date_to);
      
      $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('pjAdminReports.js');
    }
	}
	
	public function pjActionPrint() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setLayout('pjActionPrint');
    
    $date_from = pjDateTime::formatDate($this->_get->toString('date_from'), $this->option_arr['o_date_format']);
    $date_to = pjDateTime::formatDate($this->_get->toString('date_to'), $this->option_arr['o_date_format']);
    $location_id = $this->_get->toInt('location_id');
    
    if($location_id > 0) {
      $location_arr = pjLocationModel::factory()
      ->select('t1.*, t2.content as name')
      ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
      ->find($location_id)
      ->getData();
      $this->set('location_arr', $location_arr);
    }
    
    $this->getReportData($location_id, $date_from, $date_to);
	}
	
	public function pjActionGenerate() {
		$this->setAjax(true);
		if (!$this->isXHR()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
		}
		if (!self::isPost()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
		}
		if (!pjAuth::factory()->hasAccess()) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
		}
		if (!($this->_post->toInt('generate_report') && $this->_post->toString('date_from') && $this->_post->toString('date_to'))) {
	    self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
		}
		$date_from = pjDateTime::formatDate($this->_post->toString('date_from'), $this->option_arr['o_date_format']);
		$date_to = pjDateTime::formatDate($this->_post->toString('date_to'), $this->option_arr['o_date_format']);
		$location_id = $this->_post->toInt('location_id');
		$this->getReportData($location_id, $date_from, $date_to);
	}
	
	protected function getReportData($location_id, $date_from, $date_to) {
    $pjOrderModel = pjOrderModel::factory();
    $pjOrderModel->where(sprintf("( (DATE(p_dt) BETWEEN '%1\$s' AND '%2\$s') OR (DATE(d_dt) BETWEEN '%1\$s' AND '%2\$s') )", $date_from, $date_to));
    $location_clause = "";
    if($location_id > 0)
    {
        $pjOrderModel->where('t1.location_id', $location_id);
        $location_clause = sprintf(" AND `TO`.location_id=%u", $location_id);
    }
    $total_orders = $pjOrderModel->findCount()->getData();
    
    $pjOrderModel->where('t1.status', 'confirmed');
    $confirmed_orders = $pjOrderModel->findCount()->getData();
    $confirmed_arr = $pjOrderModel->findAll()->getData();
    $order_id_arr = $pjOrderModel->getDataPair(null, 'id');
    
    $unique_clients = 0;
    $first_time_clients = 0;
    
    $client_id_arr = array();
    
    $total_amount = 0;
    $delivery_fee = 0;
    $packing_fee = 0;
    $tax = 0;
    $discount = 0;
    foreach($confirmed_arr as $v) {
    	$total_amount += $v['total'];
      $delivery_fee += $v['price_delivery'];
      $packing_fee += $v['price_packing'];
      $tax += $v['tax'];
      $discount += $v['discount'];
      $client_id_arr[] = $v['client_id'];
    }
    
    $pjOrderModel->where('t1.type', 'pickup');
    $pickup_orders = $pjOrderModel->findCount()->getData();
    $delivery_orders = $confirmed_orders - $pickup_orders;
    
    $unique_id_arr = array_unique($client_id_arr);
    $unique_clients = count($unique_id_arr);
    $duplicated_clients = array_diff_assoc($client_id_arr, $unique_id_arr);
    $first_time_clients = count(array_diff($client_id_arr, $duplicated_clients));
    $more = 0;
    if(!empty($duplicated_clients)) {
      $pjOrderModel->reset();
      $pjOrderModel->where("( (DATE(p_dt) < '$date_from') OR (DATE(d_dt) < '$date_from') )");
      if((int) $location_id != '' > 0) {
        $pjOrderModel->where('t1.location_id', $location_id);
      }
      $previous_id_arr = $pjOrderModel->whereIn('t1.client_id', array_unique($duplicated_clients))->findAll()->getDataPair(null, 'client_id');
      $more = count(array_unique($duplicated_clients)) - count(array_unique($previous_id_arr));
    }
    $first_time_clients = $first_time_clients + $more;
    
    $total_products = 0;
    if(!empty($order_id_arr)) {
      $product_arr = pjOrderItemModel::factory()->where('t1.type', 'product')->whereIn('t1.order_id', $order_id_arr)->findAll()->getData();
      foreach($product_arr as $v) {
        $total_products += (int)$v['cnt'];
      }
    }
	    
    $order_item_tbl = pjOrderItemModel::factory()->getTable();
    $product_category_tbl = pjProductCategoryModel::factory()->getTable();
	    
    $category_arr = pjCategoryModel::factory()
	    ->select(sprintf("t1.*, t2.content AS name,
							(SELECT SUM(`TOI`.price * `TOI`.cnt) FROM `%1\$s` AS `TOI` WHERE `TOI`.`type`='product' AND `TOI`.order_id IN ( SELECT `TO`.id FROM `%2\$s` AS `TO` WHERE `TO`.status='confirmed' AND ((DATE(p_dt) BETWEEN '%4\$s' AND '%5\$s') OR (DATE(d_dt) BETWEEN '%4\$s' AND '%5\$s'))%6\$s ) AND `TOI`.foreign_id IN (SELECT `TPC`.product_id FROM `%3\$s` AS `TPC` WHERE TPC.category_id=t1.id ) ) AS total_amount,
							(SELECT SUM(`TOI`.cnt) FROM `%1\$s` AS `TOI` WHERE `TOI`.`type`='product' AND `TOI`.order_id IN ( SELECT `TO`.id FROM `%2\$s` AS `TO` WHERE `TO`.status='confirmed' AND ((DATE(p_dt) BETWEEN '%4\$s' AND '%5\$s') OR (DATE(d_dt) BETWEEN '%4\$s' AND '%5\$s'))%6\$s ) AND `TOI`.foreign_id IN (SELECT `TPC`.product_id FROM `%3\$s` AS `TPC` WHERE TPC.category_id=t1.id ) ) AS total_products", $order_item_tbl, $pjOrderModel->getTable(), $product_category_tbl, $date_from, $date_to, $location_clause))
		->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
		->where('t1.status', 'T')
		->orderBy("`order` ASC")
		->findAll()
		->getData();
		
		$product_arr = pjProductModel::factory()
		->select(sprintf("t1.*, t2.content AS name,
    		(SELECT SUM(`TOI`.cnt) FROM `%1\$s` AS `TOI` WHERE `TOI`.`type`='product' AND `TOI`.order_id IN ( SELECT `TO`.id FROM `%2\$s` AS `TO` WHERE `TO`.status='confirmed' AND ((DATE(p_dt) BETWEEN '%3\$s' AND '%4\$s') OR (DATE(d_dt) BETWEEN '%3\$s' AND '%4\$s'))%5\$s ) AND `TOI`.foreign_id=t1.id ) AS total_products,
    		(SELECT SUM(`TOI`.price * `TOI`.cnt) FROM `%1\$s` AS `TOI` WHERE `TOI`.`type`='product' AND `TOI`.order_id IN ( SELECT `TO`.id FROM `%2\$s` AS `TO` WHERE `TO`.status='confirmed' AND ((DATE(p_dt) BETWEEN '%3\$s' AND '%4\$s') OR (DATE(d_dt) BETWEEN '%3\$s' AND '%4\$s'))%5\$s ) AND `TOI`.foreign_id=t1.id ) AS total_amount", $order_item_tbl,$pjOrderModel->getTable(), $date_from, $date_to, $location_clause))
		->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
		->orderBy("total_products DESC")
		->limit(10)
		->findAll()
		->getData();
		
		$this->set('total_orders', $total_orders);
		$this->set('confirmed_orders', $confirmed_orders);
		$this->set('pickup_orders', $pickup_orders);
		$this->set('delivery_orders', $delivery_orders);
		
		$this->set('unique_clients', $unique_clients);
		$this->set('first_time_clients', $first_time_clients);
		
		$this->set('total_products', $total_products);
		$this->set('category_arr', $category_arr);
		$this->set('product_arr', $product_arr);
		
		$this->set('price_info', compact('total_amount', 'delivery_fee', 'tax', 'discount', 'packing_fee'));
	}

	public function pjActionPOSXIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isGet()) {
      $date_from = date('Y-m-d');
      $date_to = date('Y-m-d');
      $data = $this->getPOSReportData(0, $date_from, $date_to);
      $this->set('date_from', $date_from);
      $this->set('date_to', $date_to);
      $this->set('num_of_sales', $data['num_of_sales']); 
      $this->set('report_title', "X Report"); 
      $this->set('sales_report', $data);
      $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('pjAdminReports.js');
    }
	}

	public function pjActionPOSGenerate() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setAjax(true);
    if (self::isPost() && $this->_post->toInt('date_from') && $this->_post->toString('date_to')) {
    	$date_from = pjDateTime::formatDate($this->_post->toString('date_from'), $this->option_arr['o_date_format']);
			$date_to = pjDateTime::formatDate($this->_post->toString('date_to'), $this->option_arr['o_date_format']);
      echo $report_type = $this->_post->toString('report_type');
      if ($report_type == 'zReport') {
        $data = $this->getPOSReportData(1, $date_from, $date_to);
      } else {
        $data = $this->getPOSReportData(0, $date_from, $date_to);
      }
	    //echo '<pre>'; print_r($data); echo '</pre>';
	    $this->set('date_from', $date_from);
	    $this->set('date_to', $date_to);
	    $this->set('report_title', "X Report");
	    $this->set('num_of_sales', $data['num_of_sales']);
	    $this->set('sales_report', $data);
  	}
	}

	public function pjActionPOSZIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isGet()) {
      //$date_from = date('Y-m-d', time() - 30 * 86400 );
      $date_from = date('Y-m-d');
      $date_to = date('Y-m-d');
      $data = $this->getPOSReportData(true, $date_from, $date_to);
      $this->set('date_from', $date_from);
      $this->set('date_to', $date_to);
      $this->set('report_title', "Z Report");
      $this->set('num_of_sales', $data['num_of_sales']);
      $this->set('sales_report', $data);
      $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
      $this->appendJs('pjAdminReports.js');
    }
	}

	public function pjActionPOSXPrint() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setLayout('pjActionPrint');
    if (self::isGet()) {
    	$date_from = pjDateTime::formatDate($this->_get->toString('date_from'), $this->option_arr['o_date_format']);
			$date_to = pjDateTime::formatDate($this->_get->toString('date_to'), $this->option_arr['o_date_format']);
		} else {
			$date_from = date('Y-m-d');
    	$date_to = date('Y-m-d');
		}
    $data = $this->getPOSReportData(0, $date_from, $date_to);
    $this->set('date_from', $date_from);
    $this->set('date_to', $date_to);
    $this->set('num_of_sales', $data['num_of_sales']);
    $this->set('sales_report', $data);
	}

	public function pjActionPOSZPrint() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->setLayout('pjActionPrint');
    $date_from = date('Y-m-d');
    $date_to = date('Y-m-d');
    $data = $this->getPOSReportData(1, $date_from, $date_to);
    $date_from = $date_to = date('Y-m-d');
    $origin = 'Pos';
    $this->set('date_from', $date_from);
    $this->set('date_to', $date_to);
    $this->set('num_of_sales', $data['num_of_sales']);
    $this->set('sales_report', $data);
	}

	public function pjActionUpdateZViewReport() {
		$this->checkLogin();
    $this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 100,
        'text' => 'Missing headers.'
      ));
    }
    if (!self::isPost()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 101,
        'text' => 'HTTP method not allowed.'
      ));
    }
    if (!pjAuth::factory()->hasAccess()) {
      self::jsonResponse(array(
        'status' => 'ERR',
        'code' => 102,
        'text' => 'Access denied.'
      ));
    }
		$order_ids = $this->_post->toString('order_ids');
		if ($order_ids) {
			$order_ids_arr = explode(',', $order_ids);
			$pjOrderModel = pjOrderModel::factory();
	    $pjOrderModel->whereIn('id', $order_ids_arr);
	    //$origin = 'Pos';
	    //$pjOrderModel->where('origin', $origin);
	    $pjOrderModel->modifyAll(array('is_z_viewed'=>1))->getAffectedRows();
		}
   	self::jsonResponse(array(
      'status' => 'OK',
      'code' => 200,
      'text' => 'Order(s) has been updated.'
    ));
	}

	public function getPOSReportData($zReport = 0, $date_from, $date_to) {
    //$date_from = $date_to = date('Y-m-d');
    $origin = 'Pos';
    $pjOrderModel = pjOrderModel::factory();
    $pjOrderModel->where(sprintf("( (DATE(p_dt) BETWEEN '%1\$s' AND '%2\$s') OR (DATE(d_dt) BETWEEN '%1\$s' AND '%2\$s') )", $date_from, $date_to));
    $location_clause = "";
    //$pjOrderModel->where('t1.origin', $origin)->where('t1.deleted_order', 0);
    $pjOrderModel->where('t1.deleted_order', 0);
    if ($zReport) {
    	$pjOrderModel->where('t1.is_z_viewed', 0);
    }
    $pjOrderModel->where('t1.status', 'delivered');
    //$num_of_sales = $pjOrderModel->findCount()->getData();
    $num_of_sales = $confirmed_orders = $pjOrderModel->findCount()->getData();
    $confirmed_arr = $pjOrderModel->findAll()->getData();
    $order_id_arr = $pjOrderModel->getDataPair(null, 'id');
    $report_order_ids = implode(',', $order_id_arr);
    $unique_clients = 0;
    $first_time_clients = 0;
    $client_id_arr = array();
    $total_amount = 0;
    $delivery_fee = 0;
    $packing_fee = 0;
    $tax = 0;
    $discount = 0;
    $num_of_table_sales = 0;
    $num_of_direct_sales = 0;
    $total_table_sales = 0;
    $total_direct_sales = 0;
    $num_of_card_sales = 0;
    $num_of_cash_sales = 0;
    $card_sales = 0;
    $cash_sales = 0;
    $num_of_web_sales = 0;
    $num_of_pos_sales = 0;
    $num_of_telephone_sales = 0;
    foreach($confirmed_arr as $v) {
      $total_amount += $v['total'];
      //echo '<pre>'; print_r($v); echo '</pre>';
      if (strtolower($v['table_name']) === 'take away') {
      	$num_of_direct_sales++;
      	$total_direct_sales += $v['total'];
      } else {
      	$num_of_table_sales++;
      	$total_table_sales += $v['total'];
      }
      if (strtolower($v['payment_method']) === 'bank') {
      	$num_of_card_sales++;
      	$card_sales += $v['total'];
      } else {
      	$num_of_cash_sales++;
      	$cash_sales += $v['total'];
      }
      switch (strtolower($v['origin'])) {
      	case "pos":
      		$num_of_pos_sales++;
      	break;
      	case "telephone":
      		$num_of_telephone_sales++;
      	break;
      	case "web":
      		$num_of_web_sales++;
      	break;
      }
      $delivery_fee += $v['price_delivery'];
      $packing_fee += $v['price_packing'];
      $tax += $v['tax'];
      $discount += $v['discount'];
      $client_id_arr[] = $v['client_id'];
    }
    return compact('total_amount', 'num_of_direct_sales', 'total_direct_sales', 'num_of_table_sales', 'total_table_sales', 'num_of_sales', 'num_of_card_sales', 'num_of_cash_sales', 'card_sales', 'cash_sales', 'report_order_ids', 'num_of_pos_sales', 'num_of_web_sales', 'num_of_telephone_sales');
	}
}
?>