<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
function smarty_function_formhash($params, &$smarty)
{
    $output = '<input type="hidden" name="formhash" value="'.formhash().'" id="FormHash">';
    return $output;
}
?>