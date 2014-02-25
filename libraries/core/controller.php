<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
if (!class_exists("L10n")) {
	require("l10n.class.php");
}
class PbController extends PbObject
{
	public $params;
	public $displaypg = 9;
	public $default_headers = array(
		'Name' => 'The Name',
		'URI' => 'The URI',
		'Description' => 'Description',
		'Author' => 'Author',
		'AuthorURI' => 'Author URI',
		'Version' => 'Version'
		);
	public static $instance;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getInstance()
	{
		if (self::$instance == NULL) { // If instance is not created yet, will create it.
			self::$instance = new PbController();
		}
		return self::$instance;
	}
	
	//echo 'Your val is $name,and is not exsit in this class!';
	public function __get($property) {
		return $this->$property;
	}
	
	//echo 'Your val is '.$name.'=>'.$value;
	public function __set($property, $value) {
		$this->$property = $value;
	}		
	
	function generateList($result)
	{
		$return  = null;
		foreach ($result as $key=>$val) {
			$return[$val['OptionId']] = $val['OptionName'];
		}
		return $return;
	}

	function stripWhitespace($str) {
		$r = preg_replace('/[\n\r\t]+/', '', $str);
		return preg_replace('/\s{2,}/', ' ', $r);
	}
	
	function getSkinData( $theme_file ) {
		$theme_data = $this->getFileData( $theme_file, $this->default_headers);
		if ( $theme_data['Author'] == '' ) {
			//$theme_data['Author'] = 'Anonymous';
		}
		return $theme_data;
	}
	
	function getFileData( $file, $default_headers) {
		if(file_exists($file)){
			$fp = fopen( $file, 'r' );
			$file_data = fread( $fp, 8192 );
			fclose( $fp );
		}else{
			return false;
		}
		$all_headers = $default_headers;
		foreach ( $all_headers as $field => $regex ) {
			preg_match( '/' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, ${$field});
			if ( !empty( ${$field} ) )
			${$field} = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', ${$field}[1] ));
			else
			${$field} = '';
		}

		$file_data = compact( array_keys( $all_headers ) );

		return $file_data;
	}
	
	/**
	* Return $word in plural form.
	*
	* @param string $word Word in singular
	* @return string Word in plural
	*/
	function pluralize ($word)
	{
		$plural_rules = array(
		'/(s)tatus$/i'             => '\1\2tatuses',
		'/^(ox)$/i'                => '\1\2en',      # ox
		'/([m|l])ouse$/i'          => '\1ice',       # mouse, louse
		'/(matr|vert|ind)ix|ex$/i' =>  '\1ices',     # matrix, vertex, index
		'/(x|ch|ss|sh)$/i'         =>  '\1es',       # search, switch, fix, box, process, address
		'/([^aeiouy]|qu|industr)y$/i'      =>  '\1ies',      # query, ability, agency
		'/(hive)$/i'               =>  '\1s',        # archive, hive
		'/(?:([^f])fe|([lr])f)$/i' =>  '\1\2ves',    # half, safe, wife
		'/sis$/i'                  =>  'ses',        # basis, diagnosis
		'/([ti])um$/i'             =>  '\1a',        # datum, medium
		'/(p)erson$/i'             =>  '\1eople',    # person, salesperson
		'/(m)an$/i'                =>  '\1en',       # man, woman, spokesman
		'/(c)hild$/i'              =>  '\1hildren',  # child
		'/(buffal|tomat)o$/i'      =>  '\1\2oes',    # buffalo, tomato
		'/(bu)s$/i'                =>  '\1\2ses',    # bus
		'/(n)ews$/i'                =>  '\1\2ewses', # news
		'/(alias)/i'               =>  '\1es',       # alias
		'/(octop|vir)us$/i'        =>  '\1i',        # octopus, virus - virus has no defined plural (according to Latin/dictionary.com), but viri is better than viruses/viruss
		'/(ax|cri|test)is$/i'      =>  '\1es',       # axis, crisis
		'/s$/'                     =>  's',          # no change (compatibility)
		'/$/'                      => 's');

		foreach ($plural_rules as $rule => $replacement)
		{
			if (preg_match($rule, $word))
			{
				return preg_replace($rule, $replacement, $word);
			}
		}
		return $word;//false;
	}

	/**
	* Return $word in singular form.
	*
	* @param string $word Word in plural
	* @return string Word in singular
	*/
	function singularize ($word)
	{
		$singular_rules = array(
		'/(s)tatuses$/i'         => '\1\2tatus',
		'/(matr)ices$/i'         =>'\1ix',
		'/(vert|ind)ices$/i'     => '\1ex',
		'/^(ox)en/i'             => '\1',
		'/(alias)es$/i'          => '\1',
		'/([octop|vir])i$/i'     => '\1us',
		'/(cris|ax|test)es$/i'   => '\1is',
		'/(shoe)s$/i'            => '\1',
		'/(o)es$/i'              => '\1',
		'/(bus)es$/i'            => '\1',
		'/([m|l])ice$/i'         => '\1ouse',
		'/(x|ch|ss|sh)es$/i'     => '\1',
		'/(m)ovies$/i'           => '\1\2ovie',
		'/(s)eries$/i'           => '\1\2eries',
		'/([^aeiouy]|qu)ies$/i'  => '\1y',
		'/([lr])ves$/i'          => '\1f',
		'/(tive)s$/i'            => '\1',
		'/(hive)s$/i'            => '\1',
		'/([^f])ves$/i'          => '\1fe',
		'/(^analy)ses$/i'        => '\1sis',
		'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
		'/([ti])a$/i'            => '\1um',
		'/(p)eople$/i'           => '\1\2erson',
		'/(m)en$/i'              => '\1an',
		'/(c)hildren$/i'         => '\1\2hild',
		'/(n)ews$/i'             => '\1\2ewses',
		'/s$/i'                  => '');

		foreach ($singular_rules as $rule => $replacement)
		{
			if (preg_match($rule, $word))
			{
				return preg_replace($rule, $replacement, $word);
			}
		}
		// should not return false is not matched
		return $word;//false;
	}
	
	function convertTextToArray($text_area, $operation = "ENCODE")
	{
		$tmp_str = array();
		if ($operation=="DECODE") {
			return implode("\r\n", $text_area);
		}
		$ids = explode("\r\n", $text_area);
		if (!empty($ids)) {
			foreach ($ids as $val) {
				if(!empty($val)) $tmp_str[] = $val;
			}
		}
		return $tmp_str;
	}
	
	function getMultiId($arr){
		$r = -1;
		//$arr = array_filter($arr);
		krsort($arr);
		while(list($key,$val)=each($arr)){
			if($val>0) {
				$r = $val;
				break;
			}
		}
		return $r;
	}
	
	function array_multi2single($array) {
		static $result_array=array();
		if (!is_array ($array)) {
	    	return false;
	    }
		foreach($array as $key=>$value) {
			if(is_array($value)) {
				$this->array_multi2single($value);
			} else {
				$result_array[$key]=$value;
			}
		}
		return $result_array;
	}
	
	/**
	 * instead of array_multi2single
	 *
	 * @param unknown_type $array
	 * @return unknown
	 */
	function flatten_array($array) {
		static $result_array=array();
		foreach($array as $key=>$value) {
			if(isset($value['child']) && is_array($value['child'])) {
				$this->flatten_array($value['child']);
			} else {
				$result_array[$key]=$value;
			}
		}
		return $result_array;
	}
	
	function url($params)
	{
		if (!function_exists("smarty_function_the_url")) {
			require(SLUGIN_PATH."function.the_url.php");
		}
		return smarty_function_the_url($params);
	}
	
	function loadModel($model_name, $classes_name = '')
	{
		$class_name = strtolower($model_name);
		require_once(LIB_PATH . "core/models/".$class_name. '.php');
		if (empty($classes_name)) {
			$classes_name = $this->pluralize($class_name);
		}
		$this->$class_name = new $classes_name;
	}
	
	//only for site, not for admin & office
	function render($filename = null, $exit = false)
	{
		global $smarty,
		$viewhelper,
		$theme_name,
		$cache_id,
		$default_html_filename;
		$return = false;
		$tmp_themename = '';
		//from begin 5.0
		$smarty->template_dir = PHPB2B_ROOT ."templates".DS."site".DS;
		//:~
		$smarty->assign('position', $viewhelper->getPosition());
		$smarty->assign('page_title', $viewhelper->getTitle());
		//get_class($this);
		$tpl = $theme_name.DS.$filename.$smarty->tpl_ext;
		$smarty->assign('ThemeName', $tmp_themename?$tmp_themename:$theme_name);
		if (!empty($viewhelper->metaDescription)) {
			$smarty->assign("metadescription", $viewhelper->metaDescription);
		}
		if (!empty($viewhelper->metaKeyword)) {
			$smarty->assign("metakeywords", $viewhelper->metaKeyword);
		}elseif (!empty($viewhelper->metaDescription)){
			$viewhelper->setMetaKeyword($viewhelper->metaDescription);
			$smarty->assign("metakeywords", $viewhelper->metaKeyword);
		}
		if ($smarty->caching) {
			$cache_id = $_GET['page']."|".$_GET['id']."|".$_GET['pos'];
		}
		if (defined("SMARTY_CACHE") && SMARTY_CACHE){
			$smarty->caching = 1;
		}
		if ($smarty->caching) {
			$cache_id = substr(md5(pb_getenv('REQUEST_URI').$cache_id), 0, 16);
		}
		$return = $smarty->display($tpl, $cache_id);
		if ($exit) {
			exit;
		}
		return $return;
	}
	
	function normalize($charset) {
		$charset = strtolower(preg_replace("/[\-\_]*/", "", $charset));
		return $charset;
	}
	
	function translate($string, $in_charset, $out_charset='utf-8') {
		if(function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($string, $out_charset, $in_charset);
		} elseif(function_exists('iconv')) { // iconv is flakey
			return iconv($in_charset, $out_charset, $string);
		} elseif(function_exists("html_entity_decode")){
			$converted = htmlentities($string, ENT_NOQUOTES, $in_charset);
			$converted = html_entity_decode($converted, ENT_NOQUOTES, $out_charset);
			return $converted;
		}else{
			return $string;
		} // end else clause
	}
	
	public function toAlphabets($str)
	{
		global $plugin;
		$ret = $plugin->trigger('pinyin', $str);
		return !empty($ret)?$ret:$str;
	}
	
	public function getInitial($str)
	{
		global $plugin;
		$ret = $plugin->trigger('pinyin', $str);
		return !empty($ret)?substr($ret, 0, 1):'';
	}
}
?>