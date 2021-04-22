<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjOrderModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'orders';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'uuid', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'client_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'location_id', 'type' => 'int', 'default' => ':NULL'),
	    array('name' => 'locale_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'pending'),
		array('name' => 'payment_method', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_paid', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'txn_id', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'processed_on', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'ip', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'price_packing', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'price_delivery', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'discount', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'subtotal', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'tax', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'total', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'voucher_code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'p_dt', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'p_asap', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'p_notes', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'd_address_1', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'd_address_2', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'd_country_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'd_state', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'd_city', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'd_zip', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'd_notes', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'd_dt', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'd_asap', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'cc_type', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES'),
		array('name' => 'cc_num', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES'),
		array('name' => 'cc_exp', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES'),
		array('name' => 'cc_code', 'type' => 'blob', 'default' => ':NULL', 'encrypt' => 'AES'),
		array('name' => 'call_start', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'call_end', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'post_code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'phone_no', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'surname', 'type' => 'char', 'default' => ':NULL'),
	    array('name' => 'sms_email', 'type' => 'varchar', 'default' => ':NULL'),
	    array('name' => 'first_name', 'type' => 'char', 'default' => ':NULL'),
	    array('name' => 'kprint', 'type' => 'boolean', 'default' => '0'),
	    array('name' => 'order_despatched', 'type' => 'boolean', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjOrderModel($attr);
	}
}
?>