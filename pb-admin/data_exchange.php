<?php
/**
 *      [PHPB2B] Copyright (C) 2007-2099, Ualink Inc. All Rights Reserved.
 *      The contents of this file are subject to the License; you may not use this file except in compliance with the License. 
 *
 *      @version $Revision: 2075 $
 */
require("../libraries/common.inc.php");
require("session_cp.inc.php");
require(LIB_PATH. "cache.class.php");
require(LIB_PATH. "file.class.php");
require(CACHE_COMMON_PATH."cache_type.php");
$file = new Files();
$cache = new Caches();
$tpl_file = "data_export";
if (isset($_GET['do'])) {
	$so = trim($_GET['do']);
	$tpl_file = "data_".$so;
}
function simplexml_to_array($simplexml_obj, $array_tags=array(), $strip_white=1)
{    
    if( $simplexml_obj )
    {
        if( count($simplexml_obj)==0 )
            return $strip_white?trim((string)$simplexml_obj):(string)$simplexml_obj;
 
        $attr = array();
        foreach ($simplexml_obj as $k=>$val) {
            if( !empty($array_tags) && in_array($k, $array_tags) ) {
                $attr[] = simplexml_to_array($val, $array_tags, $strip_white);
            }else{
                $attr[$k] = simplexml_to_array($val, $array_tags, $strip_white);
            }
        }
        return $attr;
    }
    return FALSE;
}
//Show all files at exchange
$exchange_files = $file->getFiles(DATA_PATH. "exchange".DS);
if (!empty($exchange_files)) {
	foreach ($exchange_files as $key=>$val) {
		//read file
		$xml = simplexml_to_array(simplexml_load_file(DATA_PATH. "exchange".DS.$val['name'],"SimpleXMLElement",LIBXML_NOCDATA));
		$items[str_replace('.xml', '', $val['name'])] = $xml['Title'];
	}
}
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	switch ($do) {
		case "import":
			//upload file.
			uses("attachment");
			$attachment = new Attachment('tb_file');
			if (!empty($_FILES['tb_file']['name'])) {
				$attachment->if_watermark = false;
				$attachment->if_thumb = false;
				$attachment->if_thumb_middle = false;
				$attachment->allowed_file_ext = array(".xls");
				$attachment->rename_file = md5($_FILES['tb_file']['name'].$_FILES['tb_file']['size'].date("Ymd"));
				$attachment->upload_process();
			}
			if (is_file($attachment->out_file_full_path)) {
				require_once(LIB_PATH. "excel_import.class.php");
				$from_sheet = 0;
				$insert_rows = array();
				$data = new Spreadsheet_Excel_Reader(); 
				// Set output Encoding. 
				$data->setOutputEncoding($charset);
				$data->read($attachment->out_file_full_path);
				$read_datas = $data->sheets[$from_sheet];
				//read cols from xml config file
				$xml = simplexml_to_array(simplexml_load_file(DATA_PATH. "exchange".DS.$_POST['tb_name'].".xml","SimpleXMLElement",LIBXML_NOCDATA));
				$items = array_keys($xml['items']);
				$insert_cols = implode(",", $items);
				$table_name = PbController::pluralize($_POST['tb_name']);
				$insert_cols = "INSERT INTO ".$tb_prefix.$table_name." (".$insert_cols.") VALUES ";
				$num_cols = $data->sheets[$from_sheet]['numCols'];
				foreach ($data->sheets[$from_sheet]['cells'] as $key=>$val) {
					$rows = array();
					for ($i=1; $i<=$num_cols; $i++){
						$rows[$i] = "'".$val[$i]."'";
					}
					$insert_rows[] = implode(",", $rows);
				}
				$i=0;
				foreach ($insert_rows as $key=>$val) {
					$sql = $insert_cols."(".$val.");";
					echo $sql;
					$result = $pdb->Execute($sql);
					if($result) $i++;
				}
				if ($i>0) {
					flash("success");
				}else{
					flash();
				}
			}
//			@unlink($attachment->out_file_full_path);
			break;
		case "export":
			require_once(LIB_PATH. "excel_export.class.php");
			$excel = new excel_xml();
			$header_style = array(
			'bold'       => 1,
			'size'       => '10',
			'color'      => '#FFFFFF',
			'bgcolor'    => '#4F81BD'
			);
			$excel->add_style('header', $header_style);
			$table_name = PbController::pluralize($_POST['tb_name']);
			$record_amount = intval($_POST['record_amount']);
			if (empty($record_amount)) {
				$record_amount = 1000;
			}
			$result = $pdb->GetArray("SELECT * FROM ".$tb_prefix.$table_name." ORDER BY id DESC LIMIT {$record_amount};");
			$xml = simplexml_to_array(simplexml_load_file(DATA_PATH. "exchange".DS.$_POST['tb_name'].".xml","SimpleXMLElement",LIBXML_NOCDATA));
			$rows = array_keys($xml['items']);
			$excel->add_row($xml['items'], 'header');
			foreach ($result as $key=>$val) {
				foreach ($rows as $key1=>$val1) {
					$cols[$val1] = htmlspecialchars($val[$val1]);
				}
				$excel->add_row($cols);
			}
			$excel->create_worksheet($_POST['tb_name']);
			$excel->download($_POST['tb_name'].date("YmdH").'.xls');
			break;
		default:
			break;
	}
}
if (!empty($_PB_CACHE['companytype'])) {
	setvar("sorts", implode("\r\n", $_PB_CACHE['companytype']));
}
if (!empty($items)) {
	setvar("FileItems", array_map_recursive("pb_lang_split", $items));
}
template($tpl_file);
?>