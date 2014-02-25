<?php
class Templet extends PbController {
	var $name = "Templet";
	var $skin_dir = "templates/skins";
	var $system_skin_dir = "templates/site";
	var $theme_dir = 'skins';
	var $type;
	
	function __construct()
	{
		$this->default_headers['Style'] = 'Sample Color';
	}
	
	function install($entry)
	{
//		global $_GET;
//		$_this = & Templets::getInstance();
		$_this = Templets::getInstance();
		if (isset($_GET['type']) && $_GET['type'] == "system") {
			$dir = PHPB2B_ROOT. $this->system_skin_dir.'/';
			$this->theme_dir = $this->system_skin_dir;
			$type = "system";
			$css_dir = "";
//			$css_dir = "css".DS;
		}else{
			$dir = PHPB2B_ROOT. $this->skin_dir.'/';
//			$this->theme_dir = $this->skin_dir;
			$type = "user";
			$css_dir = "";
		}
		$tpldir = realpath($dir.'/'.$entry);
		if (is_dir($tpldir) && file_exists($tpldir .DS.$css_dir."style.css")) {
			$data = $this->getSkinData($tpldir .DS.$css_dir."style.css");
			extract($data);
			$_this->params['data']['name'] = $entry;
			$_this->params['data']['title'] = $Name;
			$_this->params['data']['description'] = $Description;
			$_this->params['data']['author'] = $Author;
			$_this->params['data']['directory'] = $this->theme_dir."/".$entry."/";
			$_this->params['data']['type'] = $type;
			$_this->params['data']['style'] = $Style;
//			$_this->params['data']['label'] = $entry;
//			$_this->params['data']['background'] = $Style;
			$_this->save($_this->params['data']);
		}
	}
	
	function uninstall($id)
	{
//		$_this = & Templets::getInstance();
		$_this = Templets::getInstance();
		$_this->del($id);
	}
	
	function getTemplate(){
//		$_this = & Templets::getInstance();
		$_this = Templets::getInstance();
		$installed = $_this->getInstalled();
		$not_installed = $this->getNoBuilt();
		$all = array_merge($installed, $not_installed);
		return $all;
	}
	
	function getStyle($site_theme = ''){
		$built = array();
		$css_dir = "style".DS;
		if (empty($site_theme)) {
			$site_theme = 'default';
		}
		$dir = PHPB2B_ROOT. $this->system_skin_dir.DS.$site_theme.DS.$css_dir;
		$template_dir = dir($dir);
		while($entry = $template_dir->read())  {
			$tpldir = realpath($dir.$entry);
			if((!in_array($entry, array('.', '..', '.svn'))) && is_dir($tpldir)) {
				$data = $this->getSkinData($tpldir.DS."style.css");
				extract($data);
				$built[] = array(
					'entry' => $entry,
					'label' => $entry,
					'name' => $Name,
					'style' => $Style,
					'background' => $Style,
					);
			}
		}
		return $built;
	}
	
	function getNoBuilt(){
		global $_GET;
		$built = $temp = array();
//		$_this = & Templets::getInstance();
		$_this = Templets::getInstance();
		$installed = $_this->getInstalled();
		foreach($installed as $key=>$val){
			$temp[] = $val['directory'];
		}
		if (isset($_GET['type']) && $_GET['type'] == "system") {
			$dir = PHPB2B_ROOT. $this->system_skin_dir.'/';
			$this->theme_dir = $this->system_skin_dir;
			$type = "system";
			$css_dir = "";
//			$css_dir = "css".DS;
		}else{
			$dir = PHPB2B_ROOT. $this->skin_dir.'/';
			$this->theme_dir = $this->skin_dir;
			$type = "user";
			$css_dir = "";
		}
		if(is_dir($dir)) {
			$template_dir = dir($dir);
			while($entry = $template_dir->read())  {
				$tpldir = realpath($dir.'/'.$entry);
				if((!in_array($entry, array('.', '..', '.svn'))) && (!in_array($this->theme_dir."/".$entry."/", $temp)) && is_dir($tpldir)) {
					$data = $this->getSkinData($tpldir .DS.$css_dir."style.css");
					if(!empty($data)){
						$screenshot = false;
						foreach ( array('png', 'gif', 'jpg', 'jpeg') as $ext ) {
							if (file_exists($tpldir.DS."screenshot.".$ext)) {
								$screenshot = URL.$this->theme_dir.'/'.$entry."/screenshot.".$ext;
								break;
							}
						}
						extract($data);
						$built[] = array(
						'entry' => $entry,
						'label' => $entry,
						'name' => $Name,
						'title' => $Name,
						'type' => $type,
						'style' => $Style,
						'background' => $Style,
						'available' => 0,
						'directory' => $this->theme_dir.'/'.$entry.'/',
						'author' => $Author,
						'picture' => $screenshot,
						);
					}
				}
			}
		}
		return $built;
	}
}
?>