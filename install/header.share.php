<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2048 $
 */
$steps = array(
'1'=>$software_intro,
'2'=>$software_license,
'3'=>$env_check,
'4'=>$perm_check,
'5'=>$db_setting,
'6'=>$site_info_setting,
'7'=>$install_complete
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>">
<title><?php echo $steps[$step];?> - <?php echo $software_name;?><?php echo $install_quide;?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script>
<?php
/**
 * some locale js language
 */
$op = "<!--// Created ".date("M j, Y, G:i")." -->\n";
$op .= "var pb_lang = { \n";
if(!empty($arrTemplate)){
	foreach ($arrTemplate as $key=>$val) {
		$val = str_replace("\"", "", $val);
		$op .= "\t".strtoupper(trim($key, '_')).' : "'.$val.'",';
		$op .="\n";
	}
}
$op .="\tEND : ''\n};";
echo $op;
//:~
?>
</script>
<script language="JavaScript" src="../static/scripts/jquery.js"></script>
<script language="JavaScript" src="../static/scripts/install.js" charset="<?php echo $charset;?>"></script>
</head>
<body>
<div id="main">
<div id="ads"><?php echo $software_name.$b2b_market_system." V".PHPB2B_VERSION."(".PHPB2B_RELEASE.")";?></div>
<div id="top"><a href="http://www.phpb2b.com/" target="_blank"><?php echo $official_site;?></a> | <a href="http://bbs.phpb2b.com/" target="_blank"><?php echo $official_community;?></a></div>
	<div id="step-title"><?php echo $install_step;?></div>
  <div id="left">
    <ul>
	<?php
	foreach($steps as $k=>$v)
	{
		$selected = $k == $step ? 'id="now"' : '';
	    echo "<li {$selected}>{$v}</li>";
	}
	?>
    </ul>
  </div>
  <div id="right">
    <h3><span><?php echo $step;?></span><?php echo $steps[$step];?></h3>