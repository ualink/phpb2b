<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class Payments extends PbObject
{
    function __construct()
    {
    	global $pdb, $tb_prefix;
    	$this->db =& $pdb;
    	$this->table_prefix = $tb_prefix;
		$this->payment_dir = 'payments'; 
		$this->payment_path = PHPB2B_ROOT. 'plugins'.DS.'payments'.DS; 
    }

	function install($entry)
	{
		$tpldir = realpath($this->payment_path.$entry.".php");
		if (is_file($tpldir)) {
			$this->params['data']['name'] = $entry;
			$this->params['data']['title'] = strtoupper($entry);
			$this->params['data']['available'] = 1;
			$this->params['data']['created'] = $_SERVER['REQUEST_TIME'];
			$this->save($this->params['data']);
		}
	}
	
	function uninstall($id)
	{
		$sql = "DELETE FROM {$this->table_prefix}payments WHERE id=".$id;
		return $this->db->Execute($sql);
	}
	
	function getPayments(){
		$installed = $this->getInstalled();
		$not_installed = $this->getUninstalled();
		$all = array_merge($installed, $not_installed);
		return $all;
	}
	
	function getInstalled()
	{
		$sql = "SELECT * FROM {$this->table_prefix}payments";
		$result = $this->db->GetArray($sql);
		return $result;
	}
	
	function getUninstalled(){
		$uninstalled = $temp = array();
		$installed = $this->getInstalled();
		foreach($installed as $key=>$val){
			$temp[] = $val['name'];
		}
		$template_dir = dir($this->payment_path);
		while($entry = $template_dir->read())  {
			$tpldir = realpath($this->payment_path.$entry);
			$k = reset(explode(".", $entry));
			if((!in_array($entry, array('.', '..', '.svn'))) && (!in_array($k, $temp)) && is_file($tpldir)) {
				//get info from tip
				$pay_controller = new PbController();
				$cfg = $pay_controller->getSkinData($tpldir);
				$uninstalled[] = array(
				'name' => $k,
				'title' => $cfg['Name'],
				'description' => $cfg['Description'],
				'available' => 0,
				);
			}
		}
		return $uninstalled;
	}
}
?>