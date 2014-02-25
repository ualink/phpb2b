<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class JS extends PbObject
{
    function __construct(){}
    
    function Back($step = -1)
    {
        $msg = "history.go(".$step.");";
        JS::_Write($msg);
        JS::FreeResource();
        exit;
    }

    function Alert($msg)
    {
        $msg = "alert(\"".$msg."\");";
        JS::_Write($msg);
    }

    function _Write($msg)
    {
        echo "<script language=\"javascript\">\n";
        echo $msg;
        echo "\n<\/script>";
    }

    function Reload()
    {
        $msg = "location.reload();";
        JS::FreeResource();
        JS::_Write($msg);
        exit;
    }

	function ReloadOpener()
    {
        $msg = "if (opener)    opener.location.reload();";
        JS::_Write($msg);
    }

    function jsGoTo($url)
    {
        $msg = "window.location = '$url';";
        JS::FreeResource();
        JS::_Write($msg);
        exit;
    }

     function Close()
     {
         $msg = "window.opener=null;window.close()";
        JS::FreeResource();
        JS::_Write($msg);
        exit;
        
     }

    function Submit($frm)
    {
        $msg = $frm.".submit();";
        JS::_Write($msg);
    }
}
?>