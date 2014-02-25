<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
function sql_run($sql, $default_table_prefix = 'pb_') {
    global $dbcharset, $tb_prefix;
	$return = false;
    if(mysql_get_server_info() > '4.1' && $dbcharset) {
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM)( DEFAULT CHARSET=[^; ]+)?/", "TYPE=\\1 DEFAULT CHARSET=".$dbcharset,$sql);
    }
    if($tb_prefix != $default_table_prefix) {
    	$sql = str_replace($default_table_prefix, $tb_prefix, $sql);
    }
    $sql = str_replace("\r", "\n", $sql);
    $sql_content = array();
    $intRunTimes = 0;
    $arrQuery = explode(";\n", trim($sql));
    unset($sql);
    foreach($arrQuery as $query) {
        $sql_content[$intRunTimes] = '';
        $tmpQuery = explode("\n", trim($query));
        $tmpQuery = array_filter($tmpQuery);
        foreach($tmpQuery as $query) {
            $str1 = substr($query, 0, 1);
            if($str1 != '#' && $str1 != '-') $sql_content[$intRunTimes] .= $query;
        }
        $intRunTimes++;
    }
    if(is_array($sql_content) && !empty($sql_content)) {
        foreach($sql_content as $sql) {
            if(trim($sql) != '') {
                if(substr($sql, 0, 12) == 'CREATE TABLE') {
                    $name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $sql);
                    $return = mysql_query(createtable(stripslashes(trim($sql)), $dbcharset));
                }else{
                   $return = mysql_query(stripslashes(trim($sql)));
                }
            }
        }
    } else {
        $return = mysql_query(stripslashes($sql_content));
    }
    return $return;
}

function createtable($sql, $dbcharset) {
    $type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
    $type = in_array($type, array('MYISAM', 'HEAP', 'INNODB')) ? $type : 'MYISAM';
    return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
    (mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET={$dbcharset}" : " TYPE=$type");
}

function table2sql($table){
	global $db;
	$tabledump = "DROP TABLE IF EXISTS $table;\n";
	$createtable = $db->query("SHOW CREATE TABLE $table");
	if($db->next_record()){
		$create = $db->f("Create Table");
		$tabledump.= $create.";\n\n";
	}
	return $tabledump;
}

function data2sql($table)
{
	global $db;
	$tabledump = table2sql($table);

	$rows = $db->query("SELECT * FROM $table");
	$nums = $db->affected_rows();
	$numfields = $db->num_fields();
	while ($row = mysql_fetch_row($rows))
	{
		$comma = "";
		$tabledump .= "INSERT INTO $table VALUES(";
		for($i = 0; $i < $numfields; $i++)
		{
			$tabledump .= $comma."'".mysql_escape_string($row[$i])."'";
			$comma = ",";
		}
		$tabledump .= ");\n";
	}
	$tabledump .= "\n";
	return $tabledump;
}
?>