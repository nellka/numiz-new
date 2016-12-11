<?
class imageMini
{	
    static $DIR_PATH = "/var/www/htdocs/numizmatik.ru/";
    static $SITE_PATH = "http://numizmatik.ru/";
    
	static function getMini($src,$base_path,$recreate=false) {
	    $src = str_replace('http://numizmatik.ru/','',$src);
	    $sub = str_replace($base_path,'',$src);
	    
	    $path_to_file = self::$DIR_PATH.$base_path.$sub;
	    $path_to_mini = self::$DIR_PATH.$base_path.'mini/'.$sub;
	    
	    if(!file_exists(self::$DIR_PATH.$base_path.'mini/'.$sub)||$recreate){
	        return imageMini::createMini($path_to_file,$path_to_mini);
	    }
	    
	    return str_replace(self::$DIR_PATH,self::$SITE_PATH,$path_to_mini);
	}	
	
	static function createMini($source,$path_to_mini,$nw=125,$nh=125) {
		
        if(!file_exists($source)) return "";
        $pathinfo =  pathinfo($source);
        // $stype = explode(".", $source);
        $stype =  $pathinfo["extension"];
        
        $size = getimagesize($source);
        $w = $size[0];    // Ширина изображения        
        $h = $size[1];    // Высота изображения
        
        switch(strtolower($stype)) {
            case 'gif':
            $simg = imagecreatefromgif($source);
            break;
            case 'jpg':
            $simg = imagecreatefromjpeg($source);
             case 'jpeg':
            $simg = imagecreatefromjpeg($source);
            break;
            case 'png':
            $simg = imagecreatefrompng($source);
            break;
        }
        
        $dimg = imagecreatetruecolor($nw, $nh);
        
        $wm = $w/$nw;
        $hm = $h/$nh;
        $h_height = $nh/2;
        $w_height = $nw/2;
         
        if($w > $h) {
            $adjusted_width = $w / $hm;
            $half_width = $adjusted_width / 2;
            $int_width = $half_width - $w_height;
            imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
        } elseif(($w < $h) || ($w == $h)) {     
    		$adjusted_height = $h / $wm;
    		$half_height = $adjusted_height / 2;
    		$int_height = $half_height - $h_height;
    		imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
    	} else {     
    		imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h); 
    	}     
    	//echo "<!--$dimg $path_to_mini 333-->";
        $res = imagejpeg($dimg,$path_to_mini,80);
        
    	//echo "<!-$res 333-->";
        return str_replace(self::$DIR_PATH,self::$SITE_PATH,$path_to_mini);
	}	
	
	static function SaveSmallImage ($FolderIn, $FolderOut, $Image, $xsize, $ysize,$wwm = true) {
    	$mFolderIn = $FolderIn;
    	$mFolderOut = $FolderOut;
    	$mImage = $Image;
    	$mxsize = $xsize;
    	$mysize = $ysize;    	
    	
    	if (!$Image)return;	
    
    	if (!filesize($FolderIn.$Image) or filesize($FolderIn.$Image)>500000) return; 
    	
    	$TypeImage = explode(".", $Image);
    	
    	if (!$xsize)
    		$xsize = 400;
    	
    	if (!$ysize)
    		$ysize = 400;
    	
    	unset ($size);
    	$size = GetImageSize($FolderIn.$Image);
    	$k = $size[0]/$size[1];
    	
    	$tmp = explode("_", $Image);
    	
    	//echo "<!--".var_dump($tmp)."-->";
    	$tmp2 = explode(".", $tmp[1]);
    	$tmp = explode(".", $tmp[0]);
    	//$ImageNew = $tmp[0].".".$tmp2[1];
    	$ImageNew = $tmp[0].".jpg";
    	
    	if (strtolower($TypeImage[1])!="gif") {
    		if ($size[0] <= $xsize) {    			
    			$xsize = $size[0];
    			$ysize = $size[0];
    		}
    	} else {
    		if ($size[0] <= $xsize) {    			
    			$xsize = $size[0];
    			$ysize = $size[0];
    		}
    	}
    	
    	$return = array('size0'=>$size[0], 'size1'=>$size[1], 'folder'=>$FolderIn.$Image, 'k'=>$k, 'TypeImage'=>$TypeImage[1]);    	
    	
    	if ($k==1)
    		$im = ImageCreateTrueColor ($xsize, $ysize);
    	else
    		$im = ImageCreateTrueColor ($xsize, $ysize/$k);

    	if (strtolower($TypeImage[1])=="gif"){
    		$im_in = ImageCreateFromGIF($FolderIn.$Image);
    	} else {
    		$im_in = ImageCreateFromJPEG($FolderIn.$Image);
    	}
    	
    	if ($k==1){
    		ImageCopyResampled($im, $im_in, 0, 0, 0, 0, $xsize, $ysize, $size[0], $size[1]);
    	} else ImageCopyResampled($im, $im_in, 0, 0, 0, 0, $xsize, $ysize/$k, $size[0], $size[1]);
    	
    	$textcolor = imagecolorallocate($im, 0, 0, 0);
    	if ($ysize/$k < 130) {    		
    		$ysize = $ysize/(2*$k)+50;
    		$fontnum = imageloadfont('../fonts/arial10.gdf');
    	}  else {    	
    		$ysize = $ysize/(2*$k)+70;
    		$fontnum = imageloadfont('../fonts/arial.gdf');
    	}
    	
    	if($wwm) imagestringup($im, $fontnum, $xsize/2-8, $ysize, "Numizmatik.Ru", $textcolor);  
    	//var_dump($FolderOut.$ImageNew);  	
    	imagejpeg($im, $FolderOut.$ImageNew);
    	chmod($FolderOut.$ImageNew, 0775);
    	    	
    	return $return;   	
    } 

}
?>