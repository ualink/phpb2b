<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
function sqldumptable($table, $startfrom = 0, $currsize = 0) {
	global $db, $sizelimit;
	$extendins = 0;
	if(empty($sizelimit)) $sizelimit = 204800;
	$offset = 300;
	$tabledump = '';
	$tablefields = array();
	$query = $db->query("SHOW FULL COLUMNS FROM $table", 'SILENT');
	while($fieldrow = mysql_fetch_array($query)) {
		$tablefields[] = $fieldrow;
	}
	if(!$startfrom) {

		$createtable = $db->query("SHOW CREATE TABLE $table", 'SILENT');

		if(!$db->error()) {
			$tabledump = "DROP TABLE IF EXISTS $table;\n";
		} else {
			return '';
		}
		$create =mysql_fetch_row($createtable);
		if(strpos($table, '.') !== FALSE) {
			$tablename = substr($table, strpos($table, '.') + 1);
			$create[1] = str_replace("CREATE TABLE $tablename", 'CREATE TABLE '.$table, $create[1]);
		}
		$tabledump .= $create[1];

		$rs=$db->query("SHOW TABLE STATUS LIKE '$table'");
		$tablestatus =mysql_fetch_array($rs);
		$tabledump .= ($tablestatus['Auto_increment'] ? " AUTO_INCREMENT=$tablestatus[Auto_increment]" : '').";\n\n";
		if($tablestatus['Auto_increment'] <> '') {
			$temppos = strpos($tabledump, ',');
			$tabledump = substr($tabledump, 0, $temppos).' auto_increment'.substr($tabledump, $temppos);
		}
		if($tablestatus['Engine'] == 'MEMORY') {
			$tabledump = str_replace('TYPE=MEMORY', 'TYPE=HEAP', $tabledump);
		}
	}
	$tabledumped = 0;
	$numrows = $offset;
	$firstfield = $tablefields[0];
	if($extendins == '0') {
		while($currsize + strlen($tabledump) + 500 < $sizelimit * 1000 && $numrows == $offset) {
			if($firstfield['Extra'] == 'auto_increment') {
				$selectsql = "SELECT * FROM $table WHERE $firstfield[Field] > $startfrom LIMIT $offset";
			} else {
				$selectsql = "SELECT * FROM $table LIMIT $startfrom, $offset";
			}
			$tabledumped = 1;
			$rows = $db->query($selectsql);
			$numfields = $db->num_fields($rows);

			$numrows = $db->num_rows($rows);
			while($row = mysql_fetch_row($rows)) {
				$comma = $t = '';
				for($i = 0; $i < $numfields; $i++) {
					$t .= $comma.(!empty($row[$i]) && (strexists($tablefields[$i]['Type'], 'char') || strexists($tablefields[$i]['Type'], 'text')) ? '0x'.bin2hex($row[$i]) : '\''.mysql_escape_string($row[$i]).'\'');
					$comma = ',';
				}
				if(strlen($t) + $currsize + strlen($tabledump) + 500 < $sizelimit * 1000) {
					if($firstfield['Extra'] == 'auto_increment') {
						$startfrom = $row[0];
					} else {
						$startfrom++;
					}
					$tabledump .= "INSERT INTO $table VALUES ($t);\n";
				} else {
					break 2;
				}
			}
		}
	} else {
		while($currsize + strlen($tabledump) + 500 < $sizelimit * 1000 && $numrows == $offset) {
			if($firstfield['Extra'] == 'auto_increment') {
				$selectsql = "SELECT * FROM $table WHERE $firstfield[Field] > $startfrom LIMIT $offset";
			} else {
				$selectsql = "SELECT * FROM $table LIMIT $startfrom, $offset";
			}
			$tabledumped = 1;
			$rows = $db->query($selectsql);
			$numfields = $db->num_fields($rows);

			if($numrows = $db->num_rows($rows)) {
				$t1 = $comma1 = '';
				while($row = $db->fetch_row($rows)) {
					$t2 = $comma2 = '';
					for($i = 0; $i < $numfields; $i++) {
						$t2 .= $comma2.( !empty($row[$i]) && (strexists($tablefields[$i]['Type'], 'char') || strexists($tablefields[$i]['Type'], 'text'))? '0x'.bin2hex($row[$i]) : '\''.mysql_escape_string($row[$i]).'\'');
						$comma2 = ',';
					}
					if(strlen($t1) + $currsize + strlen($tabledump) + 500 < $sizelimit * 1000) {
						if($firstfield['Extra'] == 'auto_increment') {
							$startfrom = $row[0];
						} else {
							$startfrom++;
						}
						$t1 .= "$comma1 ($t2)";
						$comma1 = ',';
					} else {
						$tabledump .= "INSERT INTO $table VALUES $t1;\n";
						break 2;
					}
				}
				$tabledump .= "INSERT INTO $table VALUES $t1;\n";
			}
		}
	}
	$startrow = $startfrom;
	$tabledump .= "\n";
	return $tabledump;
}
function fetchtablelist($tablepre = '') {
	global $db;
	$dbname = '';
	$tablepre = str_replace('_', '\_', $tablepre);
	$sqladd = $dbname ? " FROM $dbname LIKE '$arr[1]%'" : "LIKE '$tablepre%'";
	$tables = $table = array();
	$query = $db->query("SHOW TABLE STATUS $sqladd");
	while($table =mysql_fetch_array($query)) {
		$table['Name'] = ($dbname ? "$dbname." : '').$table['Name'];
		$tables[] = $table;
	}
	return $tables;
}
function splitsql($sql) {
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query) {
		$i = $j = null;
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$i .= $query[0] == "#" ? NULL : $query;
		}
		$ret[$num] = $i;
		$num++;
	}
	return($ret);
}
function arraykeys2($array, $key2) {
	$return = array();
	foreach($array as $val) {
		$return[] = $val[$key2];
	}
	return $return;
}
function strexists($haystack, $needle) {
	return !(strpos($haystack, $needle) === FALSE);
}
function syntablestruct($sql, $version, $dbcharset) {
	if(strpos(trim(substr($sql, 0, 18)), 'CREATE TABLE') === FALSE) {
		return $sql;
	}
	$sqlversion = strpos($sql, 'ENGINE=') === FALSE ? FALSE : TRUE;
	if($sqlversion === $version) {
		return $sqlversion && $dbcharset ? preg_replace(array('/ character set \w+/i', '/ collate \w+/i', "/DEFAULT CHARSET=\w+/is"), array('', '', "DEFAULT CHARSET=$dbcharset"), $sql) : $sql;
	}
	if($version) {
		return preg_replace(array('/TYPE=HEAP/i', '/TYPE=(\w+)/is'), array("ENGINE=MEMORY DEFAULT CHARSET=$dbcharset", "ENGINE=\\1 DEFAULT CHARSET=$dbcharset"), $sql);
	} else {
		return preg_replace(array('/character set \w+/i', '/collate \w+/i', '/ENGINE=MEMORY/i', '/\s*DEFAULT CHARSET=\w+/is', '/\s*COLLATE=\w+/is', '/ENGINE=(\w+)(.*)/is'), array('', '', 'ENGINE=HEAP', '', '', 'TYPE=\\1\\2'), $sql);
	}
}

function filemtimesort($a, $b) {
	if($a['filemtime'] == $b['filemtime']) {
		return 0;
	}
	return ($a['filemtime'] > $b['filemtime']) ? 1 : -1;
}
?>