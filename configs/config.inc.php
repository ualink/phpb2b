<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2048 $
 */
/**
 * db server info
 */
$dbhost = 'localhost';					// database server
$dbuser = 'root';						// database user
$dbpasswd = '';					// database user password
$dbname = 'phpb2b';				// database user
$pconnect = 0;							// if database long connect

/**
 * set cookie
 */
$cookiepre = 'mVG_';					// cookie prefix
$cookiedomain = ''; 					// cookie domain
$cookiepath = '/';						// cookie path

/**
 * database table prefix
 */
$tb_prefix = 'pb_';

/**
 * database and codec
 */
$database = 'mysql';					// database type
$dbcharset = 'utf8';					// MySQL charset, 'gbk', 'big5', 'utf8', 'latin1'

/**
 * site and codec
 */
$charset = 'utf-8';						// site charset, 'gbk', 'big5', 'utf-8'
$headercharset = 0;						// php header charset

/**
 * system administrator
 */
$admin_email = 'administrator@yourdomain.com';
$administrator_id = '1';

/**
 * domain and url config
 */
$absolute_uri = 'http://www.host.com/';
$gzipcompress = false; 			// use GZIP output buffering if possible (true|false)
$admin_runquery = false;			// if allow admin to run sql
$subdomain_support = 0;			// 是否支持二级域名,如果允许的话,空间主页链接则变为二级域名
$topleveldomain_support = 0;		// 是否支持顶级域名,如果支持的话,企业访问时会考虑解析顶级域名库[比较耗费资源,请解析到space目录]
$rewrite_able = 0;					// 是否支持网址静态化
$rewrite_compatible = 0;			// 是否支持URL中的中文字符，如果支持，则不会调用urlencode
$attachment_url = 'attachment/';
$staticurl = 'static/';
$attachment_dir = 'attachment';

/**
 * for install, and control panel
 */
$app_lang = 'en-us';
$cfg_checkip = 0;
//$debug=4;