<?php
class Orders extends PbModel {
 	var $name = "Orders";
 	var $result;
 	
 	function __construct()
 	{
 		parent::__construct();
 	}

	function checkOrders($id = null, $status = null)
	{
		if(is_array($id)){
			$checkId = "id IN (".implode(",",$id).")";
		}else {
			$checkId = "id=".$id;
		}
		$sql = "UPDATE ".$this->getTable()." SET status='".$status."' WHERE ".$checkId;
		$return = $this->dbstuff->Execute($sql);
		if($return){
			return true;
		}else {
			return false;
		}
	}
	
	function checkPayByTradeNo($trade_no, $pay_status = 1){
		$sql = "UPDATE ".$this->table_prefix."orders SET pay_status='".intval($pay_status)."' WHERE trade_no='".$trade_no."'";
		$return = $this->dbstuff->Execute($sql);
		if($return){
			//add payhistory
			return true;
		}else {
			return false;
		}
	}
	
	function getInfoByTradeNo($trade_no){
		$trade_no = trim($trade_no);
		$sql = "SELECT * FROM ".$this->table_prefix."orders WHERE trade_no='".$trade_no."'";
		$result = $this->dbstuff->GetRow($sql);
		if (!empty($result)){
			return $result;
		}else{
			return false;
		}
	}
	
	function createTradeNo($pre = '')
	{
		$trade_no = date("YmdH");
		$trade_no.= rand(1000,9999);
		if ($pre) {
			$trade_no.=$pre.$trade_no;
		}
		return $trade_no;
	}
	
	function Add($data)
	{
		extract($data);
		if (empty($data) || !is_array($data) || $total_price<=0) {
			return false;
		}else{
			$order_id = $this->createTradeNo();
			$result = $this->dbstuff->Execute("INSERT INTO {$this->table_prefix}orders (member_id,trade_no,cache_username,subject,content,total_price,pay_id,pay_name,created,modified) VALUE ('".$member_id."','".$order_id."','".$username."','".$subject."','".$content."','".$total_price."','".$pay_id."','".$pay_name."',".$this->timestamp.",".$this->timestamp.")");
			if ($result) {
				return $order_id;
			}else{
				return false;
			}
		}
	}
}
?>