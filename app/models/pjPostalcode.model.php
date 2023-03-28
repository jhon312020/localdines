<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjPostalcodeModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'postalcodes';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'name', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'postal_code', 'type' => 'varchar', 'default' => ':NULL')
		);
	
	public static function factory($attr=array())
	{
		return new pjPostalcodeModel($attr);
	}
}
?>