<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require(LIB_PATH .'page.class.php');
require("session_cp.inc.php");
uses("order");
$order = new Orders();
$tpl_file = "pay.history";
$page = new Pages();
$amount = $pdb->GetOne("SELECT count(id) AS amount FROM ".$tb_prefix."payhistories");
$page->setPagenav($amount);
$result = $pdb->GetArray("SELECT ph.*,m.username,mf.first_name,mf.last_name as true_name FROm ".$tb_prefix."payhistories ph LEFT JOIN {$tb_prefix}members m ON m.id=ph.member_id LEFT JOIN {$tb_prefix}memberfields mf ON mf.member_id=ph.member_id ORDER BY ph.id DESC limit ".$page->firstcount.",".$page->displaypg);
setvar("Items",$result);
setvar("ByPages",$page->pagenav);
template($tpl_file);
?>