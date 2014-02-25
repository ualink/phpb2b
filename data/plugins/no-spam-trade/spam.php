<?php
/* 
Plugin Name: Name Of The Plugin 
Plugin URI: http://www.phpb2b.com
Description: A brief description of the Plugin. 
Version: The Plugin's Version Number, e.g.: 1.0 
Author: Name Of The Plugin Author 
Author URI: http://www.phpb2b.com
*/
/** 
*需要注意的几个默认规则： 
*    1. 本插件类的文件名必须是action 
*    2. 插件类的名称必须是{插件名_actions} 
*/ 
define('ANTI_FORCE', true);
define('ANTI_LEVEL', 3);//higher,will be more secure,default 3.
class Spam_Actions 
{ 
	private $_name = 'spam';
    //解析函数的参数是pluginManager的引用 
    public function __construct(&$pluginManager) 
    { 
        //注册这个插件 
        //第一个参数是钩子的名称 
        //第二个参数是pluginManager的引用 
        //第三个是插件所执行的方法 
        $pluginManager->register($this->_name, $this, 'anti_spam_login'); 
    } 
     
    public function anti_spam_login() 
    { 
    	global $log;
    	$log->lwrite($_SERVER['HTTP_USER_AGENT']);
    	$client_agent = $_SERVER['HTTP_USER_AGENT'];
    	if (preg_match('/windows 2000/', $client_agent)){
    		header("Location:".URL);
    		exit;
    	}
    	$temp = explode('(', $client_agent);
    	$Part = $temp[0];
    	$ext_info = $temp[1];
    	$ext_info = explode(')', $ext_info);
    	$temp = explode(';', trim($ext_info[0]));
    	$r_info = array();
    	if (!empty($ext_info[1])) {
    		$r_info = trim($ext_info[1]);
    		$r_info = explode(" ", $r_info);
    	}
    	$temp = array_filter($temp);
    	$browser_info = am($Part, $temp, $r_info);
    	$ext_len = count($browser_info);
    	if($ext_len<ANTI_LEVEL){
    		header("Location:".URL);
    		exit;
    	}
    } 
}
?>