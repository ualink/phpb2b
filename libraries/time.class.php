<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
class Times extends PbObject
{
    var $time_stamp;

	function getSepDays($date1,$date2)
	{
		$tmp = $date2 - $date1;
		$days = round($tmp/3600/24);
		return $days;
	}
	
	function getPassedDays($olddate, $exp = '-')
	{
		global $time_stamp;
		$oldtime = strtotime($olddate);
		$passtime = $time_stamp-$oldtime;
		return floor($passtime/(24*60*60));
	}

	function dateChecker($ymd, $sep='-') {
		if(!empty($ymd)) {
			list($year, $month, $day) = explode($sep, $ymd);
			return checkdate($month, $day, $year);
		} else {
			return false;
		}
	}

	function dateConvert($access_date, $ds = "-")
	{
		if (!strpos("-", $access_date)) {
			$access_date.="-01-01";
		}
		$date_elements = explode($ds, $access_date);
		$s_time = @mktime(0, 0, 0, $date_elements [1], $date_elements[2], $date_elements [0]);
		return $s_time;
	}
	
	function units($time){
		$year   = floor($time / 60 / 60 / 24 / 365);
		$time  -= $year * 60 * 60 * 24 * 365;
		$month  = floor($time / 60 / 60 / 24 / 30);
		$time  -= $month * 60 * 60 * 24 * 30;
		$week   = floor($time / 60 / 60 / 24 / 7);
		$time  -= $week * 60 * 60 * 24 * 7;
		$day    = floor($time / 60 / 60 / 24);
		$time  -= $day * 60 * 60 * 24;
		$hour   = floor($time / 60 / 60);
		$time  -= $hour * 60 * 60;
		$minute = floor($time / 60);
		$time  -= $minute * 60;
		$second = $time;
		$elapse = '';

		$unitArr = array('年'  =>'year', '个月'=>'month',  '周'=>'week', '天'=>'day',
		'小时'=>'hour', '分钟'=>'minute', '秒'=>'second'
		);

		foreach ( $unitArr as $cn => $u )  {
			if ( $$u > 0 )      {
				$elapse = $$u . $cn;
				break;
			}
		}

		return $elapse;
	}

	/**
	 * $past = '2009-12-24 16:49:00';
	 * echo stamp($past);   
	 *
	 * @param unknown_type $past
	 * @return unknown
	 */
	function stamp($past){
		date_default_timezone_set("America/New_York");

		$year    =(int)substr($past,0,4);
		$month   =(int)substr($past,5,2);
		$day     =(int)substr($past,8,2);

		$hour    =(int)substr($past,11,2);
		$minutes =(int)substr($past,14,2);
		$second  =(int)substr($past,17,2);

		$past = mktime($hour,$minutes,$second,$month,$day,$year);
		$now  = time();
		$diff = $now - $past;

		return '发表于' . units($diff) . '前';
	}
}
?>