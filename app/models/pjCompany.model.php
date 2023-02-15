<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCompanyModel extends pjAppModel {
	protected $primaryKey = 'id';
	
	protected $table = 'companies';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'address', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'contact_person', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_active', 'type' => 'boolean', 'default' => ':NULL')
	);
	
	public static function factory($attr=array()) {
		return new pjCompanyModel($attr);
	}
}
?>