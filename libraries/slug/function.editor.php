<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2214 $
 */
function smarty_function_editor($params){
	$output = null;
	if(!isset($params['path'])){
		$base_path = "../";
	}else{
		$base_path = $params['path'];
	}
	switch ($params['type']){
		case "ckeditor":
			$output.="<script type=\"text/javascript\" src=\"{$base_path}".STATICURL."scripts/ckeditor/ckeditor.js\"></script>\n";
			if (isset($params['toolbar'])) {
				$toolbar = $params['toolbar'];
			}else{
				$toolbar = "Basic";
			}
			if(isset($params['element'])) {
				$output.="<script type=\"text/javascript\">
CKEDITOR.replace(\"".$params['element']."\", 
{
	toolbar : \"{$toolbar}\",
	skin: \"kama\", width:600, height:150
});
</script>
";
			}
			break;
		case "auto":
			break;
		case "kindeditor":
			$output = '<script charset="utf-8" src="../'.STATICURL.'scripts/kindeditor/kindeditor.js" type="text/javascript"></script>';
			$output.='<script>
			KE.show({                
			id : "AdminnoteContent",
			cssPath : "./index.css",  
			newlineTag : "br",
			resizeMode : 1  
			});
			</script>';		
			break;
		case "tiny_mce":
			if (!isset($params['mode']) || empty($params['mode'])) {
				$mode = "mode : \"textareas\",";
			}else{
				$mode = "mode : \"specific_textareas\",
editor_selector : \"mceEditor\",
";
			}
			if (isset($params['theme'])) {
				$theme = trim($params['theme']);
			}else{
				$theme = "advanced";
			}
			if (isset($params['language'])) {
				$language = trim($params['language']);
			}else{
				if(preg_match("/(zh-cn)/is", $_SERVER["HTTP_ACCEPT_LANGUAGE"])){
					$language = "cn";
				}else{
					$language = "en";
				}
			}
			$output.="<script type=\"text/javascript\" src=\"{$base_path}".STATICURL."scripts/tiny_mce/tiny_mce.js\"></script>\n";
			$output.="<script>
tinyMCE.init({
{$mode}
theme : \"{$theme}\",
skin : \"o2k7\", 
dialog_type : \"modal\", 
skin_variant : \"silver\",
relative_urls: false,
remove_script_host: false,
plugins : \"advimage,autolink,media,emotions,pagebreak,print\",
theme_advanced_toolbar_location : \"top\",
theme_advanced_toolbar_align : \"left\",
font_size_style_values : \"xx-small,x-small,small,medium,large,x-large,xx-large\",
theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect, | |,forecolor,backcolor, \",
theme_advanced_buttons2 : \"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code\",
theme_advanced_buttons3 : \"hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,visualchars,nonbreaking,template,pagebreak\",
language : \"{$language}\"
});
</script>
";
			break;
		default:
			if (isset($params['language'])) {
				$language = trim($params['language']);
			}else{
				if(preg_match("/(zh-cn)/is", $_SERVER["HTTP_ACCEPT_LANGUAGE"])){
					$language = "zh_CN";
				}elseif(preg_match("/(zh-tw|zh-hk)/is", $_SERVER["HTTP_ACCEPT_LANGUAGE"])){
					$language = "zh_TW";
				}
			}
			$output.="<script type=\"text/javascript\" src=\"{$base_path}".STATICURL."scripts/tinymce/tinymce.min.js\"></script>\n";
			$output.="<script>
tinymce.init({";
	if (!empty($language)) {
		$output.="language : '".$language."',";
	}
	if (!empty($params['mode'])) {
		$output.="selector: 'textarea.mceEditor',";
	}else{
		$output.="selector: 'textarea',";
	}
    $output.="theme: 'modern'
 });
</script>
";
			break;
	}
	echo $output;
}
?>