<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("room.share.php");
$tplname = "invite";
$invitecode = authcode($the_memberid.$time_stamp.pb_radom(6));
setvar("InviteCode", $invitecode);
vtemplate($tplname);
?>