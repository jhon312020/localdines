<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjPaymentResponseModel extends pjAppModel {
	protected $primaryKey = 'id';
	
	protected $table = 'api_payment_responses';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int',),
		array('name' => 'order_id', 'type' => 'varchar'),
		array('name' => 'response', 'type' => 'text'),
		array('name' => 'method', 'type' => 'varchar'),
    array('name' => 'is_active', 'type' => 'boolean'),
	);
	
	public static function factory($attr=array()) {
		return new pjPaymentResponseModel($attr);
	}
}
?>