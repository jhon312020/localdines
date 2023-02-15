<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjSpecialInstructionModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'special_instructions';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'parent_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'order', 'type' => 'int', 'default' => ':NULL'),
		//array('name' => 'instruction', 'type' => 'text', 'default' => ':NULL'),
        array('name' => 'image', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => 'parent'),
		array('name' => 'status', 'type' => 'enum', 'default' => 'T'),
	);
	
	public $i18n = array('name');
	
	public static function factory($attr=array())
	{
		return new pjSpecialInstructionModel($attr);
	}
	
	public function getLastOrder()
	{
		$order = 1;
		$arr = $this
			->reset()
			->orderBy("`order` DESC")
			->limit(1)
			->findAll()
			->getData();
		if(!empty($arr))
		{
			$order = $arr[0]['order'] + 1;
		}
		return $order;
	}
}
?>