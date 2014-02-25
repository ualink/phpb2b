<?php 
class WordSegment
{
    var $result = array();
    var $db;
    var $querytimes = 0;
	var $query_str = null;
    var $enChar = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0","ａ","ｂ","ｃ","ｄ","ｅ","ｆ","ｇ","ｈ","ｉ","ｊ","ｋ","ｌ","ｍ","ｎ","ｏ","ｐ","ｑ","ｒ","ｓ","ｔ","ｕ","ｖ","ｗ","ｘ","ｙ","ｚ","Ａ","Ｂ","Ｃ","Ｄ","Ｅ","Ｆ","Ｇ","Ｈ","Ｉ","Ｊ","Ｋ","Ｌ","Ｍ","Ｎ","Ｏ","Ｐ","Ｑ","Ｒ","Ｓ","Ｔ","Ｕ","Ｖ","Ｗ","Ｘ","Ｙ","Ｚ","０","１","２","３","４","５","６","７","８","９");
    var $keywords;
    var $finded = array();
    var $highfreq = array('我','是','为','了','的','你','他','她','它','们','这','那','在','和','一','不','有','对','中','这','要','上','也','人','等','说', '都', '请', '我们', '他们', '她们', '它们', '你们', '其', '让', '好', '来', '去', '才', '会', '时', '到', 'in', 'once', 'too');
    var
    $sign = array('\r','\n','\t','`','~','!','@','#','$','%','^','&','*','(',')','-','_','+','=','|','\\','\'','"',';',':','/','?','.','>',',','<','[','{',']','}','·','～','！','＠','＃','￥','％','……','＆','×','（','）','—','－','——','＝','＋','＼','｜','【','｛','】','｝','‘','“','”','；','：','、','？','。','》','，','《',' ','　');
    var $res = array();
    var $codes = array();
    
    function openDict() {
        //$this->db= new PDO($pdo['dbType'].':host='.$pdo['dbHost'].';dbname='.$pdo['dbName'], $pdo['dbUser'],$pdo['dbPass']);
        //$this->db->exec('SET NAMES utf8');
    }

    function closeDict() {
        $this->db=null;
    }

    function findinDict($string) {
        global $pdb, $tb_prefix;
        $this->querytimes++;
        $sql = "SELECT id FROM `".$tb_prefix."segmentdicts` WHERE `word`='".$string."'";
        $rs = $pdb->GetOne($sql);
        if (empty($rs) || !$rs)
        return false;
        else
        return true;
    }
    
    function findInDictStr($strings)
    {
        global $pdb, $tb_prefix;
        $return = array();
    	if (empty($strings) || !is_array($strings)) {
    		return;
    	}
    	$query_str = implode("','", $strings);
    	$sql = "SELECT id,word FROM {$tb_prefix}segmentdicts WHERE `word` IN (".$query_str.")";
    	$result = $pdb->GetArray($sql);
    	if (!empty($result)) {
    		foreach ($result as $key=>$val) {
    			$return[] = $val['word'];
    		}
    	}
    }

    function cnSplit($sentence, $minSen, $saveInter) {
        global $charset;
        $len = mb_strlen($sentence,$charset);
        $substring = array();
        $cnTmpStr = "";
        $enTmpStr = "";

        for($i=0;$i<$len;$i++)
        {
            $char = mb_substr($sentence,$i,1,$charset);
            if(in_array($char,$this->sign))
            {
                if($cnTmpStr != "")
                {
                    if(mb_strlen(trim($cnTmpStr),$charset)<=$minSen)
                    $substring[] = array(trim($cnTmpStr),'1');
                    else
                    $substring[] = array(trim($cnTmpStr),'0');
                    $cnTmpStr = "";
                }

                if($enTmpStr != "")
                {
                    $substring[] = array(trim($enTmpStr),'1');
                    $enTmpStr = "";
                }

                if($saveInter)
                $substring[] = array($char,'1');
            }
            else if(in_array($char,$this->enChar))
            {
                if($cnTmpStr != "")
                {
                    if(mb_strlen(trim($cnTmpStr),$charset)<=$minSen)
                    $substring[] = array(trim($cnTmpStr),'1');
                    else
                    $substring[] = array(trim($cnTmpStr),'0');
                    $cnTmpStr = "";
                }

                $enTmpStr .= $char;
            }
            else
            {
                if($enTmpStr != "")
                { 
                    $substring[] = array(trim($enTmpStr),'1');
                    $enTmpStr = "";
                }

                $cnTmpStr .= $char;
            }
        }

        
        if($cnTmpStr != "") {
            if($enTmpStr == "" && mb_strlen(trim($cnTmpStr),$charset)<=$minSen) 
            $substring[] = array(trim($cnTmpStr),'1');
            else
            $substring[] = array(trim($cnTmpStr),'0');
        }
        if($enTmpStr != "") $substring[] = array(trim($enTmpStr),'1');

        return $substring;
    }

    function segment($sentence,$maxlen = 8, $minSen = 3, $saveSingle = false, $saveInter = false) {
        global $charset;
        $this->openDict();
        $this->query_str = trim($sentence);
        $this->result = array();
        $this->querytimes = 0;

        $subSens = $this->cnSplit($sentence, $minSen, $saveInter);
        foreach($subSens as $item)
        {
            if($item[1] == '1')
            {
                $this->result[] = trim($item[0]);
                continue;
            }
            else
            $subSen = $item[0];

            $bFind = false;
            $i = $j = $N = 0; 
            $M = $maxlen; 
            $tmpStr = '';
            $sub_str = '';

            $senLen = mb_strlen($subSen,$charset);

            while($i < $senLen) {
                $N = ($i+$M) < $senLen ? $M : $senLen-$i;
                $bFind = false;
                for($j = $N; $j > 0; $j--) {
                    $sub_str = mb_substr($subSen,$i,$j,$charset);
                    if($this->findinDict($sub_str)) {
                        if(mb_strlen($tmpStr,$charset) < 2 && !$saveSingle)
                        $tmpStr = "";
                        else if($tmpStr != "")
                        {
                            $this->result[] = $tmpStr;
                            $tmpStr = "";
                        }
                        $this->result[] = $sub_str;
                        $bFind = true;
                        $i+=$j;
                        break;
                    }
                }

                if(!$bFind) {
                    if(in_array($sub_str,$this->highfreq)) 
                    {
                        if(mb_strlen($tmpStr,$charset) ==1 && !$saveSingle)
                        $tmpStr = ""; 
                        else if($tmpStr != "")
                        {
                            $this->result[] = $tmpStr; 
                            $tmpStr = "";
                        }

                        if($saveSingle) 
                        $this->result[] = $sub_str;
                    }
                    else
                    $tmpStr .= $sub_str; 
                    $i++;
                }
            }
            if($tmpStr !="" ) $this->result[] = $tmpStr;
        }
        $this->closeDict(); 
        return $this->result;
    }

    function zhcode($sentence,$maxlen = 8, $minSen = 3, $saveSingle = false, $saveInter = false)
    {
        global $charset;
        $val='';
        $arr=$this->segment(trim($sentence),$maxlen,$minSen,$saveSingle,$saveInter,$charset);

        $str=implode(' ',$arr);
        $strlen=mb_strlen($str,$charset);

        for($i=0;$i<$strlen;$i++){
            $tmpstr=mb_substr($str,$i,1,$charset);
            if(strlen($tmpstr)==1){
                $val.=$tmpstr;
            }else{
                $tmpstr = iconv($charset,'GBK'.'//IGNORE',$tmpstr);
                $str_qwm = sprintf("%02d%02d",ord($tmpstr[0])-160,ord($tmpstr[1])-160);
                $val.=$str_qwm;
            }
        }
        $this->codes = $val;
        $this->res = $arr;
    }

    function getResult()
    {
        $return = array();
        $result = array();
        $result = array_unique($this->res);
        $return = array_diff($result, $this->highfreq);
        $this->keywords = implode(" ", $return);
        return $return;
    }
}
?>