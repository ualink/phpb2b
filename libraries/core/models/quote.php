<?php
class Quotes extends PbModel {
 	
 	var $name = "Quote";
 	var $cache_datafile = null;
 	var $cache_ext = ".txt";
 	var $max_price;
 	var $min_price;

 	function __construct()
 	{
 		parent::__construct();
 	}
 	
 	function getInfo($id)
	{
		$sql = "SELECT mq.*,m.name AS marketname,p.name AS productname FROM {$this->table_prefix}quotes mq LEFT JOIN {$this->table_prefix}markets m ON m.id=mq.market_id LEFT JOIN {$this->table_prefix}products p ON mq.product_id=p.id WHERE mq.id=".$id;
		$result = $this->dbstuff->GetRow($sql);
		return $result;
	}
	
	function mkCacheData($date_from, $date_to, $product_id)
	{
		$conditions = array();
		$info = $this->dbstuff->GetRow("SELECT * FROM ".$this->table_prefix."products WHERE id='".$product_id."'");
		if(!empty($info))
		$_GET['pn'] = $info['name'];
		$mdt = date("Ymd")."_".substr(md5($_GET['pn'].$_GET['ds'].$_GET['de']), 0, 6);
		$file_item = $mdt;
		$file_path = DATA_PATH."tmp/".$file_item.$this->cache_ext;
		if (!file_exists($file_path)) {
			// use the chart class to build the chart:
			include_once(LIB_PATH. 'ofc/chart.php' );
			$g = new graph();
			$result = $this->dbstuff->GetArray("SELECT DATE_FORMAT(FROM_UNIXTIME(created),'%m') as mn,avg(max_price) AS avmax,avg(min_price) AS avmin FROM ".$this->table_prefix."quotes WHERE product_id=".$product_id." AND created BETWEEN $date_from AND $date_to group by mn ORDER BY created ASC");
			if (!empty($result)) {
				foreach ($result as $val) {
					$data[] = floor(($val['avmax']+$val['avmin'])/2);
				}
				$title = L("stat_charts", "tpl"). $_GET['ds']. L("arrive_to", "tpl"). $_GET['de'];
			}elseif($info['name']){
				$title = $info['name'];
			}else{
				$title = L("data_not_exists"). df();
			}
			$g->title( $title , '{font-size: 24px;color: #0000FF}' );
			$g->set_data( $data );
			$g->line_hollow( 2, 4, '0x80a033', $_GET['pn'], 10 );
			// label each point with its value
			//$g->set_x_labels( explode(",", L("months", "tpl")) );
			$x_result = $this->dbstuff->GetArray("select CONCAT(FROM_UNIXTIME(created, '%Y'),'/',FROM_UNIXTIME(created, '%m')) as m from ".$this->table_prefix."quotes WHERE created BETWEEN $date_from AND $date_to group by DATE_FORMAT(FROM_UNIXTIME(created),'%m') ORDER BY created ASC");
			foreach ($x_result as $val) {
				$x_tmp[] = $val['m'];
			}
			$g->set_x_labels( $x_tmp );
			$label_y = $this->dbstuff->GetRow("select max(max_price) as price_max,min(min_price) as price_min from ".$this->table_prefix."quotes WHERE product_id=".$product_id." AND created BETWEEN $date_from AND $date_to");
			$y_max = (!empty($label_y['price_max']))?$label_y['price_max']:100;
			// set the Y max
			$this->max_price = $label_y['price_max'];
			$this->min_price = $label_y['price_min'];
			$g->set_y_max( $label_y['price_max'] );
			// label every 20 (0,20,40,60)
			$g->y_label_steps( 6 );
			
			// display the data
			file_put_contents($file_path, $g->render());
		}
		$this->cache_datafile = $file_item.$this->cache_ext;
	}
}
?>