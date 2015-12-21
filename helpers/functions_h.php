<?php

function checked_radio($value,$val){
	if($value == $val){
		return "checked='checked'";
	} 
}

function checked_box($value){
	if($value){
		return "checked='checked'";
	} 
}

function chected_box($value){
	if($value){
		return "checked='checked'";
	} 
}

function disabled($value){
	if(!$value){
		return "disabled='disabled'";
	} 
}

function  disabled_new($val){
	
	if($val||$val=='0'){
		return '';
	}
	return 'disabled';
}


function fix($error,$value){
	if($_SERVER['REQUEST_METHOD'] == 'POST'){		
		if($error){
			return '<font color=red><b>!</b></font>';
		}  elseif(!$error&&!$value) {
			
			return '<font color=red><b>!</b></font>';
		} else {
			return "";
		}
	} else {
		return '';
	}
}
function selected($value,$val){
	if($value == $val){
		return "selected='selected'";
	} 
	return "";
}

function request($var){
    if(isset($_REQUEST[$var])) {
        if(is_string($_REQUEST[$var])) return trim($_REQUEST[$var]);
        return $_REQUEST[$var];
    } 
    return  null;
}

function generateString($number){  
$arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s',  
             't','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L',  
             'M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6',  
             '7','8','9','0');  
// Генерируем пароль  
$pass = "";  
for($i = 0; $i < $number; $i++)  
{  
  // Вычисляем случайный индекс массива  
  $index = rand(0, count($arr) - 1);  
  $pass .= $arr[$index];  
}  
return $pass;  
}  
/*
function fix_correct($error,$value){
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if($error){
			return '<font color=red><b>!</b></font>';
		}  elseif(!$error&&!$value) {
			return '<font color=red><b>!</b></font>';
		} else {
			return "";
		}
	} else {
		return '';
	}
}

function formattrim($str){

	return  preg_replace("/[ ]+/", " ", $str);
}


function  reduction($val){
	$val = htmlspecialchars($val,ENT_QUOTES);
	return $val;
}
function md5_pwd($pwd) {
	$pwd_array =  explode("#",trim($pwd));
	$count = count($pwd_array);
	if($pwd_array[$count-1]=='md5_no_salt'){
		unset($pwd_array[$count-1]);
		return implode("#",$pwd_array);
	} elseif ($pwd_array[$count-1]=='raw'){
		unset($pwd_array[$count-1]);
		return md5(implode("#",$pwd_array));
	}			
	return md5($pwd);	
	return false;
}

function WriteLog($text,$path,$name){
	$date = date('Ymd');

    $writer = new Zend_Log_Writer_Stream(
    	$path . '/' . $name 
        . '_' . date('Ymd')  . '_error.txt' 
    );
    $logger = new Zend_Log($writer);
    $logger->log($text."<br><br>\r\n", Zend_Log::ERR); 
}		

function  from_calc_to_unixtime($val){	
	$dateArray = explode('.',$val);
	$year 	= isset($dateArray[2])?$dateArray[2]:"";
	$month 	= isset($dateArray[1])?$dateArray[1]:"";
	$day 	= isset($dateArray[0])?$dateArray[0]:"";
	$ts=new DateTime("$year-$month-$day");
	return $ts->format('U');
}

function  from_unixtime_to_delfi($number){	
	//Число дней прошедших с 1900
	$days = $number/(24*3600)+25569;
	return ceil($days);
}

function _scandir($dir){
 $TPL['files'] = array ();
 if (is_dir($dir)) {
	    if ($dh = scandir($dir)) {
	        foreach ($dh  as $file) {
	        	if( $file == '.' || $file == '..'){
	                continue;
	        	}	        
	        	if(filetype($dir."/".$file) == 'dir'){
	        		$TPL['files'][] = _scandir($dir."/".$file);
				} else {
				  	 $TPL['files'][] = $dir."/".$file;
				}
	        }	       
	    }
	}
	return $TPL['files'];
}
function setFile($data,$base_dir,$value){
	
	foreach ($data as $row){
		
 		if(is_array($row)){ 			
 			setFile($row,$base_dir,$value);
 		} else {
 			$val = trim(str_replace($base_dir,'',$row));
 			if(substr($val,0,1)=='/')	$val = substr($val,1); 
 			//echo "<a href='#' onclick='$(\"attendant\").value=\"$val\"'>$val</a><br>";
 			echo "<option value='$val' ".selected($val,$value).">$val</option>";
 		}
	}
}*/
?>