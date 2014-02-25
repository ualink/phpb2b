<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
function smarty_function_pager($params, &$smarty)
{  
      // START
      $show         = 'page';
      $posvar       = 'pos';
      $limit		= 10;//default you can set 10.
      $separator    = ' &laquo;&laquo; ';
      $class_text   = 'nav';
      $class_num    = 'small';
      $class_numon  = 'big';
      $txt_pos      = 'middle';                    
      $txt_prev     = '&laquo;';                // previous
      $txt_next     = '&raquo;';                // next
      $txt_first    = 'More';             // archive, more articles
      $shift        = 0;

      foreach($params as $key=>$value) {
          $tmps[strtolower($key)] = $value;
          $tmp = strtolower($key);
          if (!(${$tmp} = $value)) {
              ${$tmp} = '';
          }
      }    
      settype($shift, 'integer');
      
      // data check
      //$minVars = array('limit');
      $minVars = array();
      if (!empty($minVars)) {
	      foreach($minVars as $tmp)  {
	          if (empty($params[$tmp])) {
	              $smarty->trigger_error('plugin "pager": missing or empty parameter: "'.$tmp.'"');
	          }
	      }
      }
      // END data check
  
      if ($txt_pos == 'middle') {
          $txt_pos = 'side';
      }
      if (!in_array($txt_pos, array('side', 'top', 'bottom'))) {
              $smarty->trigger_error('plugin "pager": bad value for : "txt_pos"');
      }
  
      // if there is no need for paging at all
      if (is_array($rowcount)) {
          $rowcount = count($rowcount);
      } elseif (!is_int($rowcount))    {
          ceil($rowcount);
      }
      if ($rowcount <= $limit) {
          return '';
      }
      if ($limit < 1)    {
          $limit = $rowcount + 1;
      }
      if (!empty($no_first)) {
          unset($txt_first);
      }
  
      // determine the real position if the diplayed numbers were shifted (eg: showing 1 instead of 0)
      $pos = 0;
      if ($shift > 0) {
          $pos = $_REQUEST[$posvar] - $shift;
          if ($pos < 0) {
              $pos = 0;
          }
      } elseif (isset($_REQUEST[$posvar])) {
          $pos = $_REQUEST[$posvar];
      }
      // END INIT
  
      // remove these vars from the request_uri - only for beauty
      $removeVars = array($posvar, '_rc');
  
      // START remove the unwanted variables from the query string
      parse_str($_SERVER['QUERY_STRING'], $urlVars);
      // add cache total count
      $urlVars['total_count'] = $rowcount;
      // add the forward vars 
      if (!empty($forwardvars) && is_array($forwardvars)) {
          $forwardvars = preg_split('/[,;\s]/', $forwardvars, -1, PREG_SPLIT_NO_EMPTY);
     	  $urlVars = array_merge($urlVars, $forwardvars);
      }
  
      foreach($urlVars as $key=>$value) {
          if (in_array($key, $removeVars)) {
              unset($urlVars[$key]);
          }
      }
      // END remove the unwanted variables from the query string
  
      // START build up the link
      $tmp = '';
      foreach($urlVars as $key=>$value) {
          if (is_array($value)) {
              foreach($value as $val) {
                  $tmp .= '&'.$key.'[]='.urlencode($val);
              }
          } elseif(!empty($value)) {
              $tmp .= '&'.$key.'='.urlencode($value);
          } else {
              $tmp .= '&'.$key;
          }
      }
      
      if (!empty($tmp)) {
          $url = pb_getenv('PHP_SELF').'?'.substr($tmp, 1);;
          $link = '&';
      } else {
          $url = pb_getenv('PHP_SELF');
          $link = '?';
      }
      // END build up the link

      // if there is no position (or 0) prepare the link for the second page
      if ((empty($pos) OR ($pos < 1)) AND ($rowcount > $limit)) {
          if (!empty($firstpos))    {
              $short['first'] .= $url.$link.$posvar.'='.$firstpos;
          } elseif ($pos == -1)    {
              $short['first'] .= $url.$link.$posvar.'='.(1 + $shift);
          } else    {
              $short['first'] = $url.$link.$posvar.'='.($limit+$shift);
          }
      }
      // START create data to print
      if ($rowcount > $limit)  { 
      	for ($i=1; $i < $rowcount+1; $i+=$limit) {
      		$pages[$i] = $url.$link.$posvar.'='.($i - 1 + $shift);
      	}
      	// previous - next stepping
      	if ($pos >= $limit) {
      		$short['prev'] = $url.$link.$posvar.'='.($pos - $limit + $shift);
      	}

      	if ( ($pos) < ($rowcount-$limit)) {
      		$short['next'] = $url.$link.$posvar.'='.($pos + $limit + $shift);
      	}
      }
      // END preparing the arrays to print

      if ($pos >= $limit) {
      	$cache['prev'] = '<a href="'.$short['prev'].'">'.$txt_prev.'</a>';
      } else    {
      	$cache['prev'] = '';
      }
      //  next
      if ($pos < ($rowcount-$limit)) {
      	$cache['next'] = '<a href="'.$short['next'].'">'.$txt_next.'</a>';
      } else {
      	$cache['next'] = '';
      }
      // END prepare the prev and next string/image, make it a link ....
      $pagenav = null;
      // START PRININT
      if ($txt_pos == 'top') {
      	$pagenav.= $cache['prev'].$cache['next']."\n";
      }
      if (($txt_pos == 'side') AND (!empty($cache['prev'])))    {
      	$pagenav.= $cache['prev'];
      }
      $total_record = $rowcount;
      $total_page = ceil($total_record / $limit)-1;// the last page is not the pos section,should out.
      $page = floor($pos/$limit);
      $group_pages = 3;
      if($page>1){                   // show page number for paging left
      	$prev_begin = ($page-$group_pages)<=0?1:($page-$group_pages);
      	$prev_end = ($page-1)<=0?1:($page-1);
      	$prevs = range($prev_begin, $prev_end);
      	if ($prev_begin>1) {
      		$pagenav.="<a href='".$pages[1]."' title='".L('first_page', 'tpl')."'>1</a>... ";
      	}
      	foreach ($prevs as $val) {
      		$pagenav.="<a href='".($pages[$val*$limit+1])."'>$val</a>";
      	}
      }
      if($page>0)
      $pagenav.="<span class='current'>{$page}</span>";
      if($page<$total_page){		// page right
      	$next_begin = ($page+1)>$total_page?$total_page:($page+1);
      	$next_end = ($page+$group_pages)>$total_page?$total_page:($page+$group_pages);
      	$nexts = range($next_begin, $next_end);
      	foreach ($nexts as $val) {
      		$pagenav.="<a href='".($pages[$val*$limit+1])."'>{$val}</a>";
      	}
      }
      if (($txt_pos == 'side') AND (!empty($cache['next']))) {
      	$pagenav.= $cache['next'];
      }
      // END NUMBERS
      // START PREVIOUS, NEXT paging
      if ($txt_pos == 'bottom') {
      	$pagenav.= $cache['prev'].$cache['next']."\n";
      }
      // END PREVIOUS, NEXT paging
      // END DISPLAY
      return $pagenav;
}
?>