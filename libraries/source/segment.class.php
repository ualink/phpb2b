<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2195 $
 */
class Segments
{
	var $use_split_search = false;
	
	function __construct()
	{
		
	}
	
	function Split($str)
	{
		global $charset, $app_lang;
		if (empty($str) || !$this->use_split_search) {
			return false;
		}
		if ($app_lang=="zh-cn") {
			require_once(LOCALE_PATH."cn_split_word.php");
			$sp = new CnSplitWord();
			$result = $sp->SplitRMM($str);
			$sp->Clear();
			if (!empty($result)) {
				$return = explode(" ", $result);
				foreach($return   as   $key=>$val){
					if(empty($return[$key]))
					unset($return[$key]);
				}
			}
		}else{
			//$return = explode(" ", $str);
			$return = false;
		}
		return $return;
	}
}//End Class
?>