<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
function smarty_function_pl($params){
	global $G, $app_lang;
	$_languages = unserialize($G['setting']['languages']);
	extract($params);
	if (!empty($var)) {
		return pb_lang_split($var);
	}
	$output = '';
	$language_nums = count($_languages);
	//make input form
	switch ($frm) {
		case "input":
			if (isset($values)){
				$_values = pb_lang_split($values, true);
			}
			if ($language_nums>1) {
				$output.='<ul>';
				foreach ($_languages as $key=>$val) {
					$output.='<li><a href="#tabs-'.$key.'">'.$val['title'].'</a></li>';
				}
				$output.='</ul>';
			}
			foreach ($_languages as $key=>$val) {
				$output.='<div id="tabs-'.$key.'">';
				$output.= '<input type="text"';
				if(isset($_values[$key])) $output.=' value="'.$_values[$key].'"';
				if (isset($name)) $output.=' name="'.$name.'['.$key.']"';
				else $output.=' name="data[multi]['.$key.']"';
				$output.=' id="dataMulti'.$key.'"';
				if(isset($required)) $output.=' class="required"';
				if(isset($size)) $output.=' size="'.$size.'"';
				if(isset($maxlength)) $output.=' maxlength="'.$maxlength.'"';
				$output.=' />';
				if(isset($title)) $output.="(".$val['title'].")";
				if(isset($sep)) $output.=$sep;else $output.="<br />";
				$output.='</div>';
			}
			break;
		case "textarea":
			if (isset($values)){
				$_values = pb_lang_split($values, true);
			}
			$output.='<ul>';
			foreach ($_languages as $key=>$val) {
				$output.='<li><a href="#tabs-ta-'.$key.'">'.$val['title'].'</a></li>';
			}
			$output.='</ul>';
			foreach ($_languages as $key=>$val) {
				$output.='<div id="tabs-ta-'.$key.'">';
				$output.= '<textarea';
				if (isset($name)) $output.=' name="'.$name.'['.$key.']"';
				else $output.=' name="data[multita]['.$key.']"';
				$output.=' id="dataMultiTA'.$key.'"';
				if(isset($required)) $output.=' class="required"';
				if(isset($rows)) $output.=' rows="'.$rows.'"';
				if(isset($cols)) $output.=' cols="'.$cols.'"';
				if(isset($wrap)) $output.=' wrap="'.$wrap.'"';
				if(isset($style)) $output.=' style="'.$style.'"';
				$output.=' >'.$_values[$key].'</textarea>';
				if(isset($title)) $output.="(".$val['title'].")";
				//					if(isset($sep)) $output.=$sep;else $output.="<br />";
				$output.='</div>';
			}
			break;
		default:
			break;
	}
	return $output;
}
?>