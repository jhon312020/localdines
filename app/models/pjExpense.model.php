<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjExpenseModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'expenses';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'supplier_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'category_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'sub_category', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'expense_name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'description', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'amount', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'is_z_viewed', 'type' => 'tinyint', 'default' => '0'),
		array('name' => 'created_date', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'updated_date', 'type' => 'datetime', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjExpenseModel($attr);
	}
}
?>