<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2218 $
 */
function pb_sendmail($to_users = array(), $subject, $template = null, $body = null, $redirect_url = null)
{
    global $charset, $smarty, $theme_name, $G, $arrTemplate, $viewhelper;
    require_once(LIB_PATH. "phpmailer/class.phpmailer.php");
    $content = null;
    $mail = new PHPMailer();
    $smarty->configLoad('default.conf', 'emails');
    $result = false;
    $logdata['created'] = time();
    if (!empty($G['setting']['mail'])) {
    	extract(unserialize($G['setting']['mail']));
    }
    if ($send_mail == 2) {
    	$mail->IsSMTP();
    	$mail->Host       = $smtp_server;
    	$mail->Port       = $smtp_port;
    	if($smtp_auth) {
    		$mail->SMTPAuth = true;
    	}
    	if (!empty($auth_protocol)) {
    		$mail->SMTPSecure = $auth_protocol;
    	}
    	$mail->Username = $auth_username;
    	$mail->Password = $auth_password;
    }else{
        $mail->IsMail();
    }
    $mail->IsHTML(true);
	$mail->CharSet = $charset;
	$mail->Encoding = "base64";
	$mail->From     = $mail_from;
	$mail->FromName = (empty($mail_fromwho))? $G['setting']['site_name'] : $mail_fromwho;
	$mail->Subject = $subject;
	$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$tpl_file = "emails/".$template.$smarty->tpl_ext;
	if (!empty($template) && $viewhelper->tpl_exists($tpl_file)) {
//		if (!empty($arrTemplate)) {
//			uaAssign($arrTemplate);
//		}
		uaAssign($G['setting']);
		$content = $smarty->fetch($tpl_file);
	}elseif (!empty($body)){
		$content = $body;
	}
	$mail->MsgHTML($content);
	if (!empty($to_users)) {
		if (!is_array($to_users[0])) {
			$mail->AddAddress($to_users[0], $to_users[1]);
			$result = $mail->Send();
		}elseif(is_array($to_users[0])) {
			foreach ($to_users as $key=>$val) {
				$mail->AddAddress($val[0], $val[1]);
				$result = $mail->Send();
				$mail->ClearAddresses();
			}
		}
	}
	if ($mail->error_count>0) {
		if (class_exists("Logs")) {
			$log = new Logs();
		}else{
		    uses("log");
		    $log = new Logs();
		}
		$logdata['handle_type'] = "error";
		$logdata['source_module'] = "sendmail";
		$logdata['description'] = $mail->ErrorInfo;
		$log->Add($logdata);
		return false;
	}
	if(!empty($redirect_url)){
	    pheader("Location:".$redirect_url);
	}else{
	    return $result;
	}
}

?>