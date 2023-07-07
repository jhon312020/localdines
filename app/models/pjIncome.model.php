<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjIncomeModel extends pjAppModel {
	protected $primaryKey = 'id';
	
	protected $table = 'incomes';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'address', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'amount', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'contact_person', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_z_viewed', 'type' => 'tinyint', 'default' => '0'),
    array('name' => 'is_active', 'type' => 'boolean', 'default' => ':NULL'),
    array('name' => 'postal_code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'contact_number', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'created_date', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'updated_date', 'type' => 'datetime', 'default' => ':NULL')
	);
	
	public static function factory($attr=array()) {
		return new pjIncomeModel($attr);
	}
}
?>