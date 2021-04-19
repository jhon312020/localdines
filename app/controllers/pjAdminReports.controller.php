<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminReports extends pjAdmin
{
		
	public function pjActionIndex()
	{
	    $this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    if (self::isGet())
	    {
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
	
	public function pjActionPrint()
	{
	    $this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    $this->setLayout('pjActionPrint');
	    
	    $date_from = pjDateTime::formatDate($this->_get->toString('date_from'), $this->option_arr['o_date_format']);
	    $date_to = pjDateTime::formatDate($this->_get->toString('date_to'), $this->option_arr['o_date_format']);
	    $location_id = $this->_get->toInt('location_id');
	    
	    if($location_id > 0)
	    {
	        $location_arr = pjLocationModel::factory()
	        ->select('t1.*, t2.content as name')
	        ->join('pjMultiLang', sprintf("t2.foreign_id = t1.id AND t2.model = 'pjLocation' AND t2.locale = '%u' AND t2.field = 'name'", $this->getLocaleId()), 'left')
	        ->find($location_id)
	        ->getData();
	        $this->set('location_arr', $location_arr);
	    }
	    
	    $this->getReportData($location_id, $date_from, $date_to);
	}
	
	public function pjActionGenerate()
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
		if (!($this->_post->toInt('generate_report') && $this->_post->toString('date_from') && $this->_post->toString('date_to')))
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
		}
		$date_from = pjDateTime::formatDate($this->_post->toString('date_from'), $this->option_arr['o_date_format']);
		$date_to = pjDateTime::formatDate($this->_post->toString('date_to'), $this->option_arr['o_date_format']);
		$location_id = $this->_post->toInt('location_id');
		
		$this->getReportData($location_id, $date_from, $date_to);
	}
	
	protected function getReportData($location_id, $date_from, $date_to)
	{
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
	    foreach($confirmed_arr as $v)
	    {
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
	    if(!empty($duplicated_clients))
	    {
	        $pjOrderModel->reset();
	        $pjOrderModel->where("( (DATE(p_dt) < '$date_from') OR (DATE(d_dt) < '$date_from') )");
	        if((int) $location_id != '' > 0)
	        {
	            $pjOrderModel->where('t1.location_id', $location_id);
	        }
	        $previous_id_arr = $pjOrderModel->whereIn('t1.client_id', array_unique($duplicated_clients))->findAll()->getDataPair(null, 'client_id');
	        $more = count(array_unique($duplicated_clients)) - count(array_unique($previous_id_arr));
	    }
	    $first_time_clients = $first_time_clients + $more;
	    
	    $total_products = 0;
	    if(!empty($order_id_arr))
	    {
	        $product_arr = pjOrderItemModel::factory()->where('t1.type', 'product')->whereIn('t1.order_id', $order_id_arr)->findAll()->getData();
	        foreach($product_arr as $v)
	        {
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
}
?>