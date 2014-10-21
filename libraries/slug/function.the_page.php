<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 * 		http://e-mats.org/2009/02/a-plugin-for-paginating-in-smarty/
 *
 *      @version $Revision: 2075 $
 */
function smarty_function_the_page($params, &$smarty)
{  
	$separator = ' &middot;&middot;&middot; ';
    $page_separator = '&nbsp;';
    $offset = 0;
    $total_count = $last_record = 0;
    $hits = 20;
    $url = $prev = $next = $middle = '';
    $current_page = 1;
    $pages = 1;
    $title_prefix = 'Page ';
    $class_active = 'paginator_active';
    $class_inactive = 'paginator_inactive';
    $url_argument_separator = '?';
    $hits_parameter_name = 'limit';
    $offset_parameter_name = 'start';
    $page_parameter_name = 'page';
    $next_page_title = 'Next page';
    $previous_page_title = 'Previous page';

    if (isset($params['offset']))
    {
        $offset = (int) $params['offset'];
    }

    if (isset($_GET[$offset_parameter_name]))
    {
        $offset = (int) $_GET[$offset_parameter_name];
    }
	if (isset($params['limit'])) {
		$params['hits'] = (int) $params['limit'];
	}
    if (isset($params['hits']))
    {
        $hits = (int) $params['hits'];
    }
    
    $link = pb_getenv("REQUEST_URI");
    $len  = strlen( $link );
    $substr = substr( $link, $len - 1 );
    if ( '&' == $substr )
    {
    	$link = substr( $link, 0, $len - 1 );
    }
    $pos = strpos( $link, '?' );
    if ( $pos )
    {
    	$link = substr( $link, 0, $pos );
    }
    if ( !empty( $_GET ) )
    {
    	$link .= '?';
    	foreach ( $_GET as $k=>$v )
    	{
    		if ( !in_array(strtolower( $k ) , array($offset_parameter_name, $hits_parameter_name, $page_parameter_name)))
    		{
    			$link .= $k.'='.$v.'&';
    		}
    	}
    	$len  = strlen( $link );
    	$substr = substr( $link, $len - 1 );
    	if ( '&' == $substr )
    	{
    		$link = substr( $link, 0, $len - 1);
    	}
    	$url = $link;
    }

    if (isset($params['url']))
    {
        $url = $params['url'];
    }
    
    if (!empty($params['rowcount'])) {
    	$params['total_hits'] = intval($params['rowcount']);
    }
    
    if (!empty($params['total_hits']))
    {
        $pages = ceil($params['total_hits'] / $hits);
        $current_page = floor($offset / $hits) + 1;
    }

    if (!empty($params['pages']))
    {
        $pages = max(1, (int) $params['pages']);
    }

    if (!empty($params['current']))
    {
        $current_page = max(1, (int) $params['current']);
    }

    if (!empty($params['title']))
    {
        $title_prefix = htmlspecialchars($params['title'], ENT_QUOTES);
    }

    if (!empty($params['hits_parameter_name']))
    {
        $hits_parameter_name = $params['hits_parameter_name'];
    }

    if (!empty($params['offset_parameter_name']))
    {
        $offset_parameter_name = $params['offset_parameter_name'];
    }

    if (isset($params['title_prefix']))
    {
        $title_prefix = $params['title_prefix'];
    }

    if (isset($params['next_page_title']))
    {
        $next_page_title = $params['next_page_title'];
    }

    if (isset($params['previous_page_title']))
    {
        $previous_page_title = $params['previous_page_title'];
    }

    if (isset($params['class_inactive']))
    {
        $class_inactive = $params['class_inactive'];
    }

    if (isset($params['class_active']))
    {
        $class_active = $params['class_active'];
    }
    if (strpos($url, '?') !== false)
    {
    	if(substr($url, -1, 1)=='?')
        $url_argument_separator = '';
    	else
        $url_argument_separator = '&';
    }

    $str = $str_middle = '';

    $printed_start_middle_separator = false;
    $printed_middle_end_separator = false;
    if($pages>0){
    for($i = 0; $i < $pages; $i++)
    {
        $middle = false;

        // if we're somewhere in the middle..
        if (($i > 2) && ($current_page > 2))
        {
            // if we've not printed the start, do it now..
            if (!$printed_start_middle_separator && ($current_page > 5))
            {
                $printed_start_middle_separator = true;
                $str .= $page_separator . $separator;
            }

            // check if we're printing an ending here...
            if (!$printed_middle_end_separator && ($current_page > ($pages - 5)))
            {
                $printed_middle_end_separator = true;
                //$str .= $page_separator . $separator;
            }

            // jump to the middle position if we've not been there already..
            $i = max($i, $current_page - 2);

            if ($i < ($current_page + 1))
            {
                // just so we can handle it below if we're in the middle of our middle-thingie..
                $middle = true;
            }
        }

        if (($i > 2) && !$middle)
        {
            // if we've come to the end without printing the separator...
            if (!$printed_middle_end_separator)
            {
                $printed_middle_end_separator = true;
                $str .= $page_separator . $separator;
            }

            // jump to the last three if we've not already done so..
            $i = max($i, $pages-1);
        }

        if ($i > 0)
        {
            $str .= $page_separator;
        }

        // the offset for this page..
        $this_start = $hits * $i;

        // initialize the links..
        $link_preface = '';
        $link_postface = '';

        // check if we're linking this page..
        if (($i+1) != $current_page)
        {
            $link_preface = "<a href='" . $url . $url_argument_separator . $offset_parameter_name . "=" . $this_start . "&" . $hits_parameter_name . "=" . $hits . "&".$page_parameter_name."=".($i+1)."' title='" . $title_prefix . " " . ($i + 1) . "' class='" . $class_inactive . "'>";
            $link_postface = "</a>";
        }
        else
        {
            $link_preface = "<a class='" . $class_active . "'>";
            $link_postface = "</a>";
            $page = $i+1;
        }

        $str .= $link_preface . ($i + 1) . $link_postface;
    }
    }
    
    $smarty->assign("middle", $str);

    if ($current_page > 1)
    {
    	$prev = "<a href='" . $url . $url_argument_separator . $offset_parameter_name . "=" . (($current_page-2)*$hits) . "&" . $hits_parameter_name . "=" . $hits . "&".$page_parameter_name."=".($page-1)."' title='" . $previous_page_title . "'>&laquo;</a>" . $page_separator;
        $str = $prev. $str;
    }
    $smarty->assign("prev", $prev);
    if ($current_page < $pages)
    {
    	$next = $page_separator . "<a href='" . $url . $url_argument_separator . $offset_parameter_name . "=" . ($current_page*$hits) . "&" . $hits_parameter_name . "=" . $hits . "&".$page_parameter_name."=".($page+1)."' title='" . $next_page_title . "'>&raquo;</a>";
        $str .= $next;
    }
    $smarty->assign("next", $next);
    if (isset($params['total_hits'])) {
    	$total_count = $params['total_hits'];
    }
    if(isset($_GET['page'])) $smarty->assign("current", intval($_GET['page']));
    $last_record = $offset+$hits;
    $last_record = $last_record>$total_count?$total_count:$last_record;
    $smarty->assign("pages", $pages);
    $smarty->assign("count", $total_count);
    $smarty->assign("start", $offset+1);
    $smarty->assign("end", $last_record);
	if (isset($params['echo'])) {
		return $str;
	}else{
		if(!isset($params['tpl']))
		$smarty->display("element.pages".$smarty->tpl_ext);
		else
		$smarty->display($params['tpl'].$smarty->tpl_ext);
	}
    
}
?>