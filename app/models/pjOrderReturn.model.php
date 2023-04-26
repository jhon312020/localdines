<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjOrderReturnModel extends pjAppModel {
	protected $primaryKey = 'id';
	
	protected $table = 'order_returns';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'product_name', 'type' => 'varchar', 'default' => ':NULL'),
    array('name' => 'reason', 'type' => 'text', 'default' => ':NULL'),
    array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
    array('name' => 'qty', 'type' => 'int', 'default' => ':NULL'),
    array('name' => 'amount', 'type' => 'decimal', 'default' => ':NULL'),
    array('name' => 'purchase_date', 'type' => 'datetime', 'default' => ':NULL'),
    array('name' => 'is_z_viewed', 'type' => 'tinyint', 'default' => '0'),
    array('name' => 'created_date', 'type' => 'datetime', 'default' => ':NULL'),
    array('name' => 'updated_date', 'type' => 'datetime', 'default' => ':NULL')
	);
	
	public static function factory($attr=array()) {
		return new pjOrderReturnModel($attr);
	}
}
?>