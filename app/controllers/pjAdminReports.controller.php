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


  public function pjActionCancelReturnReport() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $this->set('loadData', "CancelRetrun");
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('pjAdminReports.js');
  }

  public function pjActionTopProductsReport() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    $loadData = $this->_get->toString('loadData');

    $categories = pjCategoryModel::factory()
    ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjCategory' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
    ->where('t1.status', 'T')
    ->select(sprintf("t1.*, t2.content AS name, (SELECT COUNT(TPC.product_id) FROM `%s` AS TPC WHERE TPC.category_id=t1.id) AS cnt_products", pjProductCategoryModel::factory()->getTable()))
    ->findAll()
    ->getData();
    $this->set('categories', $categories);
    $this->set('loadData', $loadData);


    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('pjAdminReports.js');
  }

  public function pjActionGetTopProductsReport() {
    $this->setAjax(true);
  
    if ($this->isXHR())
    {
      $date_from = date('Y-m-d 00:00:00', strtotime('-3 month'));
      
      $pjOrderItemModel = pjOrderItemModel::factory()
        ->where("(t1.order_id IN (SELECT TPC.id FROM `".pjOrderModel::factory()->getTable()."` AS TPC WHERE TPC.status = 'delivered'  AND TPC.created >='".$date_from."'))")
        ->where('t1.status', null)
        ->where('t1.type', 'product')
        ->join('pjProduct', "t2.id = t1.foreign_id", 'left')
        ->join('pjMultiLang', "t3.foreign_id = t1.foreign_id AND t3.model = 'pjProduct' AND t3.locale = '".$this->getLocaleId()."' AND t3.field = 'name'", 'left')
        ->groupBy('t1.foreign_id')
        ->orderBy('count DESC');
      
      if ($this->_get->toString('q')) {
        $q = $this->_get->toString('q');
        $pjOrderItemModel->where("(t3.content LIKE '%$q%')");
      }
      if ($category_id = $this->_get->toInt('category_id'))
      {
          $pjOrderItemModel->where("(t2.id IN (SELECT TPC.product_id FROM `".pjProductCategoryModel::factory()->getTable()."` AS TPC WHERE TPC.category_id='".$category_id."'))");
      }

      $column = 'count';
      $direction = 'DESC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
      {
          $column = $this->_get->toString('column');
          $direction = strtoupper($this->_get->toString('direction'));
      }

      $total = $pjOrderItemModel->findCount()->getData();
      $rowCount = $this->_get->toInt('rowCount') ?: 10;
      $pages = ceil($total / $rowCount);
      $page = $this->_get->toInt('page') ?: 1;
      //$page = 3;
      $offset = ((int) $page - 1) * $rowCount;
      if ($page > $pages)
      {
        $page = $pages;
      }
      
      $data = $pjOrderItemModel
        ->select('t1.foreign_id, t3.content as name, t2.image, COUNT(*) as count')
        ->orderBy("$column $direction")
        ->limit($rowCount, $offset)
        ->findAll()
        ->getData(); 
      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function pjActionGetNonProductsReport() {
    $this->setAjax(true);
  
    if ($this->isXHR())
    {
      $date_from = date('Y-m-d 00:00:00', strtotime('-3 month'));

      $pjOrderItemModel = pjOrderItemModel::factory()
        ->where("(t1.order_id IN (SELECT TPC.id FROM `".pjOrderModel::factory()->getTable()."` AS TPC WHERE TPC.status = 'delivered'  AND TPC.created >='".$date_from."'))")
        ->where('t1.status', null)
        ->where('t1.type', 'product')
        ->select('t1.foreign_id')
        ->groupBy('t1.foreign_id')
        ->findAll()
        ->getData(); 
      $order_ids = implode(", ",array_column($pjOrderItemModel, 'foreign_id'));
      $pjProductModel = pjProductModel::factory()
        ->where("(t1.id NOT IN ($order_ids))")
        ->join('pjMultiLang', "t2.foreign_id = t1.id AND t2.model = 'pjProduct' AND t2.locale = '".$this->getLocaleId()."' AND t2.field = 'name'", 'left')
        ->groupBy('t1.id');
      
      if ($this->_get->toString('q')) {
        $q = $this->_get->toString('q');
        $pjProductModel->where("(t2.content LIKE '%$q%')");
      }
      if ($category_id = $this->_get->toInt('category_id'))
      {
          $pjProductModel->where("(t1.id IN (SELECT TPC.product_id FROM `".pjProductCategoryModel::factory()->getTable()."` AS TPC WHERE TPC.category_id='".$category_id."'))");
      }

      $column = 'count';
      $direction = 'DESC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
      {
          $column = $this->_get->toString('column');
          $direction = strtoupper($this->_get->toString('direction'));
      }

      $total = $pjProductModel->findCount()->getData();
      $rowCount = $this->_get->toInt('rowCount') ?: 10;
      $pages = ceil($total / $rowCount);
      $page = $this->_get->toInt('page') ?: 1;
      //$page = 3;
      $offset = ((int) $page - 1) * $rowCount;
      if ($page > $pages)
      {
        $page = $pages;
      }
      
      $data = $pjProductModel
        ->select('t1.id as foreign_id, t2.content as name, t1.image, 0 as count')
        ->orderBy("$column $direction")
        ->limit($rowCount, $offset)
        ->findAll()
        ->getData(); 
      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function pjActionGetCancelReturnOrders() {
    $this->setAjax(true);
   
    if ($this->isXHR()) {
      $pjOrderModel = pjOrderModel::factory();
      //$pjOrderItemModel = pjOrderItemModel::factory();
      $today = date('Y-m-d');
      $from = $today . " " . "00:00:00";
      $to = $today . " " . "23:59:59";
      if ($q = $this->_get->toString('q')) {
        // MEGAMIND
     //    $pjOrderModel->where("(t1.id ='$q' OR t1.uuid = '$q' OR t1.surname LIKE '%$q%'
          // OR t3.email LIKE '%$q%' OR t3.phone = '$q' OR t1.post_code = '$q')");
        //$table_name = $q;
        //$q = preg_replace("/[^0-9]/", "", $q );
        $pjOrderModel->where("(t1.order_id LIKE '%$q%' OR t1.uuid LIKE '%$q%' OR t1.table_name LIKE '%$q%')");
        // !MEGAMIND
      }
      if ($this->_get->toString('date_from') && $this->_get->toString('date_to')) {
          $date_from = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_from'));
          $from = $date_from->format('Y-m-d'). " " . "00:00:00";
          $date_to = DateTime::createFromFormat('d.m.Y', $this->_get->toString('date_to'));
          $to = $date_to->format('Y-m-d'). " " . "23:59:59";
      }
      $return_types = implode("','", RETURN_TYPES);
      //$to = ''
      //echo $from;
      //echo $to;
      $pjOrderModel = $pjOrderModel
      ->select("t1.*")
      ->where("((t1.p_dt >= '$from' AND t1.p_dt <= '$to') OR (t1.d_dt >= '$from' AND t1.d_dt <= '$to'))")
      ->where('t1.deleted_order', 0)
      ->where("t1.id IN (SELECT ORDITEM.order_id FROM `" . pjOrderItemModel::factory()
      ->getTable() . "` AS ORDITEM WHERE ORDITEM.STATUS IN ('".$return_types."'))")->orderBy("name ASC")
      ->where('status', 'delivered');
      // if ($q = $this->_get->toString('q'))
      // {
      //     // echo "hello "; exit;
      //     //$pjOrderModel = $pjOrderModel->where("(t1.expense_name LIKE '%$q%' OR t2.name LIKE '%$q%')");
      // }
       
      $column = 'id';
      $direction = 'desc';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC'))) {
        //$column = $this->_get->toString('column');
        //$direction = strtoupper($this->_get->toString('direction'));
      }
     

      $total = $pjOrderModel->findCount()->getData();
      $rowCount = $this->_get->toInt('rowCount') ?: 10;
      $pages = ceil($total / $rowCount);
      if ($this->_get->toInt('page')) {
        $page = $this->_get->toInt('page') ?: 1;
      } else {
        $page = 1;
      }
       
      $offset = ((int) $page - 1) * $rowCount;
      if ($page > $pages) {
        $page = $pages;
      }

      $data = $pjOrderModel
      ->orderBy("$column $direction")
      ->limit($rowCount, $offset)
      ->findAll()
      ->getData();

      $order_ids = array_column($data, 'id');
      //$this->pr($order_ids);
      $pjOrderItems = pjOrderItemModel::factory();
      $pjOrderData = $pjOrderItems->whereIn('order_id', $order_ids)
      ->whereIn('status', RETURN_TYPES)
      ->findAll()
      ->getData();
      //$this->pr($pjOrderData);
      $groupedOrderItems = array();
      if ($pjOrderData) {
        $groupedOrderItems = array_reduce($pjOrderData, function($carry, $item) {
            if(!isset($carry[$item['order_id']])){
                $carry[$item['order_id']] = ['order_id'=>$item['order_id'],'cancel_amount'=>$item['price'] * $item['cnt']];
            } else {
                $carry[$item['order_id']]['cancel_amount'] += $item['price'] * $item['cnt'];
            }
            return $carry;
        });
      }
      //$this->pr($groupedOrderItems);
      $table_list = $this->getRestaurantTables();
      foreach ($data as $k => $v) {
        // MEGAMIND
        $v['sms_sent_time'] == "" ? $data[$k]['sms_sent_time'] = '-' : $data[$k]['sms_sent_time'] = explode(" ", $v['sms_sent_time']) [1];
        if (explode(" ", $v['p_dt']) [0] == explode(" ", $today) [0] || explode(" ", $v['d_dt']) [0] == explode(" ", $today) [0]) {
          $v['d_dt'] == "" ? $data[$k]['expected_delivery'] = explode(" ", $v['p_dt']) [1] : $data[$k]['expected_delivery'] = explode(" ", $v['d_dt']) [1];
        } else {
          $v['d_dt'] == "" ? $data[$k]['expected_delivery'] = $this->getDateFormatted($v['p_dt']) : $data[$k]['expected_delivery'] = $this->getDateFormatted($v['d_dt']);
        }
        if ($v['delivered_time'] == null) {
          $data[$k]['deliver_t'] = $data[$k]['expected_delivery'];
          $data[$k]['deliver_sts'] = "none";
        }
        else {
          if (explode(" ", $v['delivered_time']) [1] > explode(" ", $v['delivery_dt']) [1]) {
            $data[$k]['deliver_sts'] = "failure";
          } else {
            $data[$k]['deliver_sts'] = "success";
          }
          $data[$k]['deliver_t'] = $v['delivered_time'];
        }
        $v['delivered_time'] == "" ? $data[$k]['delivered_time'] = '-' : $data[$k]['delivered_time'] = explode(" ", $v['delivered_time']) [1];
        $data[$k]['total'] = "<strong class='list-pos-type'>".pjCurrency::formatPrice($v['total'])."</strong>";
        if (array_key_exists($v['table_name'], $table_list)) {
          $data[$k]['table_name'] = $table_list[$v['table_name']];
        }
        $data[$k]['table_name'] = "<strong class='list-pos-type'>".$data[$k]['table_name']."</strong>";
        // !MEGAMIND
        if ($data[$k]['is_paid'] == 1) {
          if (strtolower($data[$k]['payment_method']) == 'bank') {
            $data[$k]['payment_method'] = 'Card';
          } else {
            $data[$k]['payment_method'] = 'Cash';
          }
        } else {
          $data[$k]['payment_method'] = '';
        }
        $data[$k]['order_date'] = date("d-m-Y", strtotime($v['created']));
        $data[$k]['cancel_amount'] = "<strong class='list-pos-type'>".pjCurrency::formatPrice($groupedOrderItems[$v['id']]['cancel_amount'])."</strong>";
      }
      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function pjActionGetCancelOrderInfo() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      if ($this->_get->toInt('id') <= 0) {
        echo "invalid parameter";
        exit;
      } else {
        $id = $this->_get->toInt('id');
        $order_type = strtolower($this->_get->toString('type'));
        $pjOrderModel = pjOrderModel::factory()->where('t1.deleted_order', 0)
          ->join('pjClient', "t2.id=t1.client_id", 'left outer')
          ->join('pjAuthUser', "t3.id=t2.foreign_id", 'left outer');
        $order = $pjOrderModel->select("t1.*, t3.name as client_name, t2.c_type, 
              AES_DECRYPT(t1.cc_type, '" . PJ_SALT . "') AS `cc_type`,  
              AES_DECRYPT(t1.cc_num, '" . PJ_SALT . "') AS `cc_num`,
              AES_DECRYPT(t1.cc_exp, '" . PJ_SALT . "') AS `cc_exp`,
              AES_DECRYPT(t1.cc_code, '" . PJ_SALT . "') AS `cc_code`")
          //->where("t1.id", $id)
        // ->orderBy("$col $dir")
        // ->limit($rowCount, $offset)
        ->find($id)
        ->getData();
        //$this->pr_die($order);
        $this->getOrderItems($id, false);
        $role_id = $this->getRoleId();

        if ($order["surname"] == '' || is_null($order["surname"]) || $order["surname"] === 0) {
          $order["surname"] = $order["surname"] = $order["first_name"];
        }
        if ($role_id != ADMIN_R0LE_ID && $order['status'] == 'delivered') {  
          //$order["surname"] = substr($order["surname"], 0, 2).str_repeat("*", strLen($order['surname']) - 2); 
          if ($order["surname"]) {
            $order["surname"] = substr($order["surname"], 0, 2).str_repeat("*", 10); 
          }
          if ($order["sms_email"]) {
            $order["sms_email"] = substr($order["sms_email"], 0, 2).str_repeat("*", (strLen($order['sms_email']) - 2));
          }
          if ($order["phone_no"]) {
            $order["phone_no"] = substr($order["phone_no"], 0, 2).str_repeat("*", (strLen($order['phone_no']) - 2));
          }
        }
        if($order["d_address_2"]) {
          $address = $order["d_address_1"].",<br/>".$order["d_address_2"].",<br/>".$order["d_city"];
        } else { 
          $address = $order["d_address_1"].",<br/>".$order["d_city"];
        }
        $order['sms_sent_time'] = $order['sms_sent_time'] == ''  ?  "-" : date("d-M-Y H:m:s", strtotime($order['sms_sent_time']));
        $order['delivered_time'] = $order['delivered_time'] == ''  ? "-" : date("d-M-Y H:m:s", strtotime($order['delivered_time']));
        if ($order['client_id'] == NULL && $order['origin'] == "web") {
          $order['c_type'] = "guest";
        }
        $order['address'] = $address;
        $this->set('order_details', $order);
        $this->set('order_type', $order_type);
       
      }
    }
  }

  public function pjActionCheckOrderTime() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $today = date( 'y-m-d', time ());
      $today = $today." "."00:00:00";
      $create = pjOrderModel::factory()
        ->select('t1.created , t1.order_id')
        ->where('t1.status', 'pending')
        ->where('t1.deleted_order', '0')
        ->where("(t1.created >= '$today')")
        ->findAll()
        ->getData();
      $ids = [];
      foreach ($create as $k => $created) {
        $new = strtotime($created['created']);
        $current_time = time();
        $time_difference = $current_time - $new;
        if($time_difference > 3600) {
          array_push($ids, $created['order_id']);
        }
      }
      if(count($ids)) {
        return self::jsonResponse(array('status' => 'true', 'orders' => $ids));
      } else {
        return self::jsonResponse(array('status' => 'false', 'orders' => 'no pending orders'));
      }
    }
  }


}
?>