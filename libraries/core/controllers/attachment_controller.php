<?php
class Attachment extends PbController {
	var $name = "Attachment";
 	var $width = 0;
 	var $height = 0;
    var $upload_form_field = 'userfile';
   	var $allowed_file_ext = array('.jpg', '.jpeg', '.gif', '.png', '.bmp', '.swf', '.flv');
   	var $imgext  = array('.jpg', '.jpeg', '.gif', '.png', '.bmp');
    var $out_file_name;
    var $out_file_path;
    var $out_file_full_path;
    var $max_file_size    = 1024000;
    var $upload_url;
    var $file_full_url;
    var $upload_dir;
    var $file_size;
    var $if_watermark;
    var $is_water_image;
    var $is_water_text;
    var $water_text_color;
    var $if_thumb = true;
    var $if_thumb_middle = true;
    var $if_thumb_large = false;
    var $rename_file;
    var $if_orignal = false;
    var $orignal_file_ext = '.orignal';
    var $attachment_dir;
    var $insert_new = true;
    var $seperator = "*";
    var $small_scale = "80*80";
    var $middle_scale = "220*220";
    var $large_scale = "800*600";
    var $is_image = 0;
    var $title;
    var $description;
    var $id;
	
	function __construct($user_file = '')
	{
		global $attachment_dir, $G;
		if (!empty($user_file)) {
			$this->upload_form_field = $user_file;
		}
		$this->attachment_dir = $attachment_dir;
		$this->if_watermark = $G['setting']['watermark'];
		if(isset($G['setting']['waterimage'])) $this->is_water_image = $G['setting']['waterimage'];
		$this->is_water_text = $G['setting']['watertext'];
		$this->water_text_color = $G['setting']['watercolor'];
		if (isset($G['setting']['thumb_save_orignal'])) {
			$this->if_orignal = $G['setting']['thumb_save_orignal'];
		}
		if (!empty($G['setting']['thumb_small'])) {
			$this->small_scale = $G['setting']['thumb_small'];
		}
		if (!empty($G['setting']['thumb_middle'])) {
			$this->middle_scale = $G['setting']['thumb_middle'];
		}
		if (!empty($G['setting']['thumb_large'])) {
			$this->large_scale = $G['setting']['thumb_large'];
			$this->if_thumb_large = true;
		}
		if (!empty($G['setting']['waterface']) && file_exists(DATA_PATH. "fonts".DS.$G['setting']['waterface'])) {
			$this->fontFace = $G['setting']['waterface'];
		}
//		if(empty($this->upload_dir)) $this->upload_dir = gmdate("Y").DS.gmdate("m").DS.gmdate("d");
//		$this->out_file_path = PHPB2B_ROOT. $this->attachment_dir.DS.$this->upload_dir.DS;
//		$this->upload_url = str_replace(array(DS, "\\", "\'"), "/", $this->upload_dir).'/'; 	
 	}
 	
 	function getScale($type = "small")
 	{
 		$scale = $type."_scale";
 		return explode($this->seperator, $this->$scale);
 	}
 	
 	function upload_process($type_id = '0')
 	{
 		$attach_info = array();
 		//init moved here
		if(empty($this->upload_dir)) $this->upload_dir = gmdate("Y").DS.gmdate("m").DS.gmdate("d");
		$this->out_file_path = PHPB2B_ROOT. $this->attachment_dir.DS.$this->upload_dir.DS;
		$this->upload_url = str_replace(array(DS, "\\", "\'"), "/", $this->upload_dir).'/';
 		if (isset($_FILES) && $_FILES[$this->upload_form_field]['size']>0) {
 			$mimetype = new mimetype();
 			$file_ext = strtolower(fileext($_FILES[$this->upload_form_field]['name']));
 			if ($this->is_image) {
 				//check image format
 				if (!in_array($file_ext, $this->imgext)){
 					return L("format_not_support");
 				}
 			}
	 		if (in_array($file_ext, $this->imgext)){
	 			$this->is_image = 1;
	 			$this->allowed_file_ext = $this->imgext;
	 			if (!in_array($_FILES[$this->upload_form_field]['type'], $mimetype->image_mimes)) {
	 				flash("format_not_support", '', 0);
	 			}
	 		}
 			require(LIB_PATH. "upload.class.php");
	 		$upload = new FileUploads;
			$upload->upload_dir = $this->out_file_path;
			$upload->extensions = $this->allowed_file_ext;
			$upload->max_file_size = $this->max_file_size;
			$upload->the_temp_file = $_FILES[$this->upload_form_field]['tmp_name'];
			$upload->the_file = $_FILES[$this->upload_form_field]['name'];
			$upload->http_error = $_FILES[$this->upload_form_field]['error'];
	 		if ($_FILES[$this->upload_form_field]['size']>$this->max_file_size) {
	 			flash("file_too_big", '', 0, implode(",", $this->allowed_file_ext));
	 		}
			$isuploaded = $upload->upload($this->rename_file);
			if (!$isuploaded) {
				flash("file_too_big", '', 0, implode(",", $this->allowed_file_ext));
			}
			//insert into db.
			//$_this = & Attachments::getInstance();
			$_this = Attachments::getInstance();
			$this->file_full_url = $this->upload_url.$upload->file_copy;
			$this->file_size = $_FILES[$this->upload_form_field]['size'];
			$this->out_file_name = $upload->file_copy;
	        $this->out_file_full_path = $this->out_file_path.$this->out_file_name;
	        if ($this->is_image) {
		        list($width, $height) = @getimagesize($this->out_file_full_path);
		        $this->width = intval($width);
		        $this->height = intval($height);
		        if ($this->if_orignal) {
		        	copy($this->out_file_full_path, $this->out_file_path.$this->rename_file.$this->orignal_file_ext.$upload->file_extension);
		        }
		        if($this->if_thumb){
			        require(LIB_PATH. "thumb.class.php");
			        if ($this->if_orignal) {
			        	$img = new Image($this->out_file_path.$this->rename_file.$this->orignal_file_ext.$upload->file_extension, $this->out_file_full_path);
			        }else{
			        	$img = new Image($this->out_file_path.$this->rename_file.$upload->file_extension, $this->out_file_full_path);
			        }
			        if($this->if_thumb_middle) {
			        	list($width, $height) = $this->getScale("middle");
			        	$img->Thumb($width, $height, '.middle.jpg');
			        }
			        list($width, $height) = $this->getScale("small");
			        $img->Thumb($width, $height);
		        }
		        if($this->if_watermark){
		        	$markimg = new ImageWatermark($file_name = $this->out_file_path.$this->rename_file.$upload->file_extension);
		        	$markimg->fontFile = DATA_PATH."fonts/".$this->fontFace;
		        	if($this->is_water_image){
		        		$markimg->appendImageMark(PHPB2B_ROOT.STATICURL.'images/watermark.png');
		        	}else{
			        	$waterText = (!empty($this->is_water_text))?$this->is_water_text:pb_getenv('HTTP_HOST');
			        	$markimg->color = (!empty($this->water_text_color))?$this->water_text_color:'#FF0000';
			        	$markimg->angle = 0;//rotate for textwatermark.
			        	$markimg->appendTextMark($waterText);
		        	}
		        	if($this->width>150 || $this->height>150) $markimg->write($file_name);
		        }
			    if($this->if_thumb_large){
			        list($width, $height) = $this->getScale("large");
			    	$img->Thumb($width, $height, null);
			    }
			}
	 		//save
	 		if ($this->insert_new) {
		 		$attach_info['attachment'] = $this->file_full_url;
		 		$attach_info['created'] = $attach_info['modified'] = $_this->timestamp;
		 		$attach_info['title'] = (empty($this->title))?reset(explode(".", $upload->the_file)):$this->title;
		 		$attach_info['description'] = $this->description;
		 		$attach_info['file_name'] = $upload->the_file;
		 		$attach_info['file_name'] = $this->is_image;
		 		$attach_info['file_size'] = $_FILES[$this->upload_form_field]['size'];
		 		$attach_info['file_type'] = $_FILES[$this->upload_form_field]['type'];
		 		$attach_info['attachmenttype_id'] = $type_id;
		 		if (!empty($GLOBALS['pb_user'])) {
		 			$attach_info['member_id'] = intval($GLOBALS['pb_user']['pb_userid']);
		 		}
	 			$this->id = $_this->Add($attach_info);
	 		}
 		}
 	}
 	
 	function deleteBySource($src)
 	{
 		@unlink(PHPB2B_ROOT. $this->attachment_dir.DS.$src);
 		@unlink(PHPB2B_ROOT. $this->attachment_dir.DS.$src.".middle.jpg");
 		@unlink(PHPB2B_ROOT. $this->attachment_dir.DS.$src.".small.jpg");
 		$file_ext = fileext($src);
 		$orignal_filename = str_replace($file_ext, ".orignal".$file_ext, $src);
 		@unlink(PHPB2B_ROOT. $this->attachment_dir.DS.$orignal_filename);
 	}
 	
 	function deleteById($attachment_id)
 	{
 		
 	}
}

class ImageWatermark{
    var $markPosType = 9;
    var $fontFile = '';
    var $color = '#FF0000';
    var $fontSize = 24;
    var $angle = 0;
    var $markPos = array();
    var $markImageFile = null, $destImageFile = null;
    var $mark_res = null, $mark_width = 0, $mark_height = 0, $mark_type = null;
    var $dest_res = null, $dest_width = 0, $dest_height = 0, $dest_type = null;

    function ImageWatermark($destImage){
        if(!file_exists($destImage)) return false;
        $this->destImageFile=$destImage;
        $imageInfo = getimagesize($this->destImageFile);
        $this->dest_width = $imageInfo[0];$this->dest_height = $imageInfo[1];$this->dest_type = $imageInfo[2];
        $this->dest_res = $this->getImageResource($this->destImageFile,$this->dest_type);
		if(version_compare(PHP_VERSION,"5.0.0","<")){
            register_shutdown_function(array($this,"__destruct"));          
         }
    }

    function __destruct(){
        imagedestroy($this->dest_res);
    }

    function appendTextMark($markText){
        if($markText==null) return false;
        $box = imagettfbbox($this->fontSize,$this->angle,$this->fontFile,$markText);
        $this->mark_width = $box[2]-$box[6];
        $this->mark_height = $box[3]-$box[7];
        $pos = ($this->markPos!=null)?$this->markPos:$this->getMarkPosition($this->markPosType);
        $pos[1]+=$this->mark_height;
        $RGB=$this->colorHexRgb($this->color);
        $imageColor=imagecolorallocate($this->dest_res,$RGB[0],$RGB[1],$RGB[2]);
        imagettftext($this->dest_res,$this->fontSize,$this->angle,$pos[0],$pos[1],$imageColor,$this->fontFile,$markText);
    }

    function appendImageMark($markImage){
        if(!file_exists($markImage)) return false;
        $this->markImageFile=$markImage;
        $imageInfo = getimagesize($this->markImageFile);
        $this->mark_width = $imageInfo[0];$this->mark_height = $imageInfo[1];$this->mark_type = $imageInfo[2];
        $this->mark_res = $this->getImageResource($this->markImageFile,$this->mark_type);
        $pos = ($this->markPos!=null)?$this->markPos:$this->getMarkPosition($this->markPosType);
        imagealphablending($this->dest_res, true);
        imagecopy($this->dest_res,$this->mark_res,$pos[0],$pos[1],0,0,$this->mark_width,$this->mark_height);
        imagedestroy($this->mark_res);
    }

    function write($filename=null){
        $this->writeImage($this->dest_res,$filename,$this->dest_type);
    }

    function setMarkPos($x,$y){
        $this->markPos[0]=$x; $this->markPos[1]=$y;
    }

   function colorHexRgb($color){
        $color = preg_replace('/#/','',$color);
        $R=hexdec($color[0].$color[1]);
        $G=hexdec($color[2].$color[3]);
        $B=hexdec($color[4].$color[5]);
        return array($R,$G,$B);
    }

    function getMarkPosition($type=0){
        switch($type){
            case 0: $x = rand(0,$this->dest_width-$this->mark_width);
                    $y = rand(0,$this->dest_height-$this->mark_height);
                    break;//random
            case 1: $x = 0;
                    $y = 0;
                    break;//topleft
            case 2: $x = ($this->dest_width-$this->mark_width)/2;
                    $y = 0;
                    break; //topcenter
            case 3: $x = $this->dest_width-$this->mark_width;
                    $y = 0;
                    break;// topright
            case 4: $x = 0;
                    $y = ($this->dest_height-$this->mark_height)/2;
                    break;//middleleft
            case 5: $x = ($this->dest_width-$this->mark_width)/2;
                    $y = ($this->dest_height-$this->mark_height)/2;
                    break;//middlecenter
            case 6: $x = $this->dest_width-$this->mark_width;
                    $y = ($this->dest_height-$this->mark_height)/2;
                    break;//middleright
            case 7: $x = 0; $y = $this->dest_height-$this->mark_height;
                    break;//bottomleft
            case 8: $x = ($this->dest_width-$this->mark_width)/2;
                    $y = $this->dest_height-$this->mark_height;
                    break;//bottomcenter
            case 9: $x = $this->dest_width-$this->mark_width;
                    $y = $this->dest_height-$this->mark_height;
                    break;//bottomright

            default:$x = rand(0,$this->dest_width-$this->mark_width);
                    $y = rand(0,$this->dest_height-$this->mark_height);
                    break;
        }
        return array($x,$y);
    }

    function getImageResource($filename,$type=0){
        switch($type){
            case 1:return imagecreatefromgif($filename);break;
            case 2:return imagecreatefromjpeg($filename);break;
            case 3:return imagecreatefrompng($filename);break;
            default:return null;
        }
    }
    
    function writeImage($ImageRes,$filename=null,$type=0){
        switch($type) {
            case 1:imagegif($ImageRes,$filename);break;
            case 2:imagejpeg($ImageRes,$filename);break;
            case 3:imagepng($ImageRes,$filename);break;
            default:return null;
        }
        return true;
    }
}

class mimetype { 
	var $image_mimes = array("image/pjpeg", "image/jpeg", "image/gif", "image/x-png", "image/png", "image/vnd.wap.wbmp", "image/bmp");
	var $filename;
	
	function getType($filename) {
		$filename = basename($filename);
		$filename = explode('.', $filename);
		$filename = $filename[count($filename)-1];
		return $this->privFindType($filename);
	}
	
	function checkTypes($files_type, $checked_type)
	{
		if (strcasecmp($files_type, $checked_type)!=0) {
			return false;
		}else{
			return true;
		}
	}

	function privFindType($ext) {
		$mimetypes = $this->privBuildMimeArray();
		if (isset($mimetypes[$ext])) {
			return $mimetypes[$ext];
		} else {
			return 'application/octet-stream';
		}

	}

	function privBuildMimeArray() {
		return array(
		"ez" => "application/andrew-inset",
		"hqx" => "application/mac-binhex40",
		"cpt" => "application/mac-compactpro",
		"doc" => "application/msword",
		"bin" => "application/octet-stream",
		"dms" => "application/octet-stream",
		"lha" => "application/octet-stream",
		"lzh" => "application/octet-stream",
		"exe" => "application/octet-stream",
		"class" => "application/octet-stream",
		"so" => "application/octet-stream",
		"dll" => "application/octet-stream",
		"oda" => "application/oda",
		"pdf" => "application/pdf",
		"ai" => "application/postscript",
		"eps" => "application/postscript",
		"ps" => "application/postscript",
		"smi" => "application/smil",
		"smil" => "application/smil",
		"wbxml" => "application/vnd.wap.wbxml",
		"wmlc" => "application/vnd.wap.wmlc",
		"wmlsc" => "application/vnd.wap.wmlscriptc",
		"bcpio" => "application/x-bcpio",
		"vcd" => "application/x-cdlink",
		"pgn" => "application/x-chess-pgn",
		"cpio" => "application/x-cpio",
		"csh" => "application/x-csh",
		"dcr" => "application/x-director",
		"dir" => "application/x-director",
		"dxr" => "application/x-director",
		"dvi" => "application/x-dvi",
		"spl" => "application/x-futuresplash",
		"gtar" => "application/x-gtar",
		"hdf" => "application/x-hdf",
		"js" => "application/x-javascript",
		"skp" => "application/x-koan",
		"skd" => "application/x-koan",
		"skt" => "application/x-koan",
		"skm" => "application/x-koan",
		"latex" => "application/x-latex",
		"nc" => "application/x-netcdf",
		"cdf" => "application/x-netcdf",
		"sh" => "application/x-sh",
		"shar" => "application/x-shar",
		"swf" => "application/x-shockwave-flash",
		"sit" => "application/x-stuffit",
		"sv4cpio" => "application/x-sv4cpio",
		"sv4crc" => "application/x-sv4crc",
		"tar" => "application/x-tar",
		"tcl" => "application/x-tcl",
		"tex" => "application/x-tex",
		"texinfo" => "application/x-texinfo",
		"texi" => "application/x-texinfo",
		"t" => "application/x-troff",
		"tr" => "application/x-troff",
		"roff" => "application/x-troff",
		"man" => "application/x-troff-man",
		"me" => "application/x-troff-me",
		"ms" => "application/x-troff-ms",
		"ustar" => "application/x-ustar",
		"src" => "application/x-wais-source",
		"xhtml" => "application/xhtml+xml",
		"xht" => "application/xhtml+xml",
		"zip" => "application/zip",
		"au" => "audio/basic",
		"snd" => "audio/basic",
		"mid" => "audio/midi",
		"midi" => "audio/midi",
		"kar" => "audio/midi",
		"mpga" => "audio/mpeg",
		"mp2" => "audio/mpeg",
		"mp3" => "audio/mpeg",
		"aif" => "audio/x-aiff",
		"aiff" => "audio/x-aiff",
		"aifc" => "audio/x-aiff",
		"m3u" => "audio/x-mpegurl",
		"ram" => "audio/x-pn-realaudio",
		"rm" => "audio/x-pn-realaudio",
		"rpm" => "audio/x-pn-realaudio-plugin",
		"ra" => "audio/x-realaudio",
		"wav" => "audio/x-wav",
		"pdb" => "chemical/x-pdb",
		"xyz" => "chemical/x-xyz",
		"bmp" => "image/bmp",
		"gif" => "image/gif",
		"ief" => "image/ief",
		"jpeg" => "image/jpeg",
		"jpg" => "image/jpeg",
		"jpe" => "image/jpeg",
		"png" => "image/png",
		"tiff" => "image/tiff",
		"tif" => "image/tif",
		"djvu" => "image/vnd.djvu",
		"djv" => "image/vnd.djvu",
		"wbmp" => "image/vnd.wap.wbmp",
		"ras" => "image/x-cmu-raster",
		"pnm" => "image/x-portable-anymap",
		"pbm" => "image/x-portable-bitmap",
		"pgm" => "image/x-portable-graymap",
		"ppm" => "image/x-portable-pixmap",
		"rgb" => "image/x-rgb",
		"xbm" => "image/x-xbitmap",
		"xpm" => "image/x-xpixmap",
		"xwd" => "image/x-windowdump",
		"igs" => "model/iges",
		"iges" => "model/iges",
		"msh" => "model/mesh",
		"mesh" => "model/mesh",
		"silo" => "model/mesh",
		"wrl" => "model/vrml",
		"vrml" => "model/vrml",
		"css" => "text/css",
		"html" => "text/html",
		"htm" => "text/html",
		"asc" => "text/plain",
		"txt" => "text/plain",
		"rtx" => "text/richtext",
		"rtf" => "text/rtf",
		"sgml" => "text/sgml",
		"sgm" => "text/sgml",
		"tsv" => "text/tab-seperated-values",
		"wml" => "text/vnd.wap.wml",
		"wmls" => "text/vnd.wap.wmlscript",
		"etx" => "text/x-setext",
		"xml" => "text/xml",
		"xsl" => "text/xml",
		"mpeg" => "video/mpeg",
		"mpg" => "video/mpeg",
		"mpe" => "video/mpeg",
		"qt" => "video/quicktime",
		"mov" => "video/quicktime",
		"mxu" => "video/vnd.mpegurl",
		"avi" => "video/x-msvideo",
		"movie" => "video/x-sgi-movie",
		"ice" => "x-conference-xcooltalk"
		);
	}
}
?>