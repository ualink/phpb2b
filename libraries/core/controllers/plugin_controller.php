<?php
class Plugin extends PbController {
	var $name = "Plugin";
	var $plugin_dir = "plugins";
	var $plugin_path;
	var $info_filename = "info.inc.php";
	var $plugin_name;
	var $need_config = false;
 	public static $instance = NULL;
	
	function __construct($plugin_name = '')
	{
		$this->plugin_path = PHPB2B_ROOT. $this->plugin_dir.DS;
		if (!empty($plugin_name)) {
			$this->plugin_name = $plugin_name;
		}
	}

	function install($entry)
	{
		global $smarty;
		$return = 0;
		$tpldir = realpath($this->plugin_path.$entry);
		//$_this = & Plugins::getInstance();
		$_this = Plugins::getInstance();
		if (is_dir($tpldir) && file_exists($tpldir.DS.$entry.".php")) {
			$data = $this->getSkinData($tpldir .DS.$entry.".php");
			extract($data);
			$_this->params['data']['name'] = $entry;
			$_this->params['data']['available'] = 1;
			$_this->params['data']['title'] = $Name;
			$_this->params['data']['description'] = $Description;
			$_this->params['data']['copyright'] = $Author;
			$_this->params['data']['version'] = $Version;
			$_this->params['data']['created'] = $_this->params['data']['modified'] = $_this->timestamp;
			$_this->save($_this->params['data']);
			if (file_exists($tpldir.'/template/admin'.$smarty->tpl_ext)) {
				$this->need_config = true;
			}
			$key = $_this->table_name."_id";
			$return = $_this->$key;
		}
		return $return;
	}
	
	function uninstall($id)
	{
		//$_this = & Plugins::getInstance();
		$_this = Plugins::getInstance();
		$_this->del($id);
	}
	
	function getPlugins(){
		//$_this = & Plugins::getInstance();
		$_this = Plugins::getInstance();
		$installed = $_this->getInstalled();
		$not_installed = $this->getUninstalled();
		$all = array_merge($installed, $not_installed);
		return $all;
	}
	
	function getUninstalled(){
		$built = $temp = array();
		$_this = & Plugins::getInstance();
		$installed = $_this->getInstalled();
		foreach($installed as $key=>$val){
			$temp[] = $val['name'];
		}
		$template_dir = dir($this->plugin_path);
		while($entry = $template_dir->read())  {
			$tpldir = realpath($this->plugin_path.'/'.$entry);
			if((!in_array($entry, array('.', '..', '.svn'))) && (!in_array($entry, $temp)) && is_dir($tpldir) && file_exists($tpldir.DS.$entry.".php")) {
				$data = $this->getSkinData($tpldir .DS.$entry.".php");
				extract($data);
				$built[] = array(
				'entry' => $entry,
				'name' => $Name,
				'title' => $Name,
				'version' => $Version,
				'available' => 0,
				'author' => $Author,
				'description' => $Description,
				);
			}
		}
		return $built;
	}

	function display($file_name)
	{
		global $smarty;
		$tpl_file = $this->plugin_path.$this->plugin_name.DS."template".DS.$file_name.".html";
		return $smarty->fetch($tpl_file, null, null, true);
	}
}
?>