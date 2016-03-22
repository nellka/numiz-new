<?
class contentHelper{    

	static  function render($template,$rows) {
		//var_dump(START_PATH);
		
		if (file_exists(DIR_TEMPLATE . $template.'.tpl.php')) {

			extract($rows);

			ob_start();
			require(DIR_TEMPLATE . $template.'.tpl.php');
			$output = ob_get_contents();
			
			ob_end_clean();
			//var_dump($output);
			return $output;
		} else {
			die('Error: Could not load template ' . DIR_TEMPLATE . $template . '!');
			exit();				
		}
	}
	
    static function strtolower_ru($text) 
    { 
    	$text = trim($text);
    	$text = strip_tags($text);
    	$alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю','ї'); 
    	$alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю','Ї'); 
    	$ruskey = Array('ї','а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ',', '.', ' ', '«', '»', '"', ':', '/','?');
    	$engkey = Array ("i","a", "b", "v", "g", "d", "e", "e", "g", "z", "i", "i", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "ch", "sh", "sch", "i", "y", "i", "e", "yu", "ya", "_", "_", '-', '', '', '', '','-','');
    	$text = str_replace($alfavitupper,$alfavitlover,strtolower($text)); 
       	return str_replace($ruskey,$engkey,$text);
    } 
    static function showImage($url,$title,$params=array()){
       //if(!file_exists("/var/www/htdocs/numizmatik.ru/shopcoins/".$url)) return false;
       $on = '';
       foreach ($params as $key=>$value){
            $on.= "$key=$value ";
       }
       return "<img  src='http://numizmatik.ru/shopcoins/$url' title='$title' $on>";
       
    }
    static function urlImage($url){
       if(!trim($url)) return ;
       return "http://numizmatik.ru/shopcoins/images/$url";       
    }
    
    static function setHrefTitle($name,$material,$group){       
        $title = "";
        if(in_array($material,array(8,6,1,11,12))) {
            $title .= "Монета";
        } else if ($material==2){
            $title .="Банкнота(бона)";
        } else if ($material==9){
            $title .="Лот монет";
        } else if ($material==10){
            $title .="Нотгельд";
        } else $title .="Набор монет";        
        $title .=" - ".$group." ".$name;      
        
        return  $title;
    }
    static function setYearText($year,$materialtype){
        if($year == 1990 && $materialtype==12) return '1990 ЛМД';
		if($year == 1991 && $materialtype==12) return '1991 ЛМД';
		if($year == 1992 && $materialtype==12) return '1991 ММД';
		return	$year;
    }
    
    static function setWordWhat($material){       
        $word = "";
       //($rows["materialtype"]==8||$rows["materialtype"]==6?"монет":($rows["materialtype"]==2?"банкнот(бон)":($rows["materialtype"]==10?"нотгельдов":"наборов монет"))
        if(in_array($material,array(8,6,1,11,12))) {
            $word = "монет";
        } else if ($material==2){
            $word ="банкнот(бон)";
        } else if ($material==9){
            $title ="Лот монет";
        } else if ($material==10){
            $word ="нотгельдов";
        } else if ($material==3){
            $word ="книг";
        } else $word ="наборов монет";           
        
        return  $word;
    }
    
   
    static function setWordThat($material){       
        $word = "";
       //($rows["materialtype"]==8||$rows["materialtype"]==6?"монет":($rows["materialtype"]==2?"банкнот(бон)":($rows["materialtype"]==10?"нотгельдов":"наборов монет"))
        if(in_array($material,array(8,6,1,11,12))) {
            $word = "монеты";
        } else if ($material==2){
            $word ="банкноты(боны)";
        } else if ($material==9){
            $title ="Лота монет";
        } else if ($material==10){
            $word ="Нотгельда";
        } else $word ="Набора монетт";           
        
        return  $word;
    }
    
    
    static function setWordOn($material){       
        $word = "";       
        if(in_array($material,array(8,6,1,11,12))) {
            $word = "монету";
        } else if ($material==2){
            $word ="банкнота(бону)";
        } else if ($material==9){
            $title ="Лот монет";
        } else if ($material==10){
            $word ="Нотгельд";
        } else if ($material==3){
            $word ="книгу";
        } else $word ="Набор монет";           
        
        return  $word;
    }
    
    static function setWordAbout($material){       
        $word = "";       
        if(in_array($material,array(8,6,1,11,12))) {
            $word = "монете";
        } else if ($material==2){
            $word ="банкноте(боне)";
        } else if ($material==9){
            $title ="Лоте монет";
        } else if ($material==10){
            $word ="Нотгельде";
        } else if ($material==3){
            $word ="книге";
        } else $word ="Наборе монет";           
        
        return  $word;
    }
    
    
    //функция, котора формирует ссылки для янжекса
    /*
    static function rehrefdubdle($mtype,$parent,$rows){
       $url =  self::strtolower_ru($rehref)."_c";
       $url .= (($mtype==1 || $mtype==10 || $mtype==12)?$rows['parent']:$rows['shopcoins']);
       $url .="_pc".($parent>0?$parent:(($mtype==7 || $mtype==8 || $mtype==6 || $mtype==4 || $mtype==2) && $rows["amount"]>1?$rows['shopcoins']:(($mtype==1 || $mtype==10)?$rows['parent']:0)))."_m".$rows['materialtype']."_pp1.html";
	   $url .= contentHelper::strtolower_ru($url)."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";
       return;
        
    }*/
    
    static function getRegHref($rows,$materialtype=0,$parent=0){  
        $mtype = ($rows['materialtype']>0?$rows['materialtype']:$materialtype);         
        $rows['year'] = contentHelper::setYearText($rows['year'],$rows['materialtype']);        
        $rehref = "";
        if ($materialtype==5||$materialtype==3){
			if ($mtype==1) $rehref = "Монета-";
			if ($mtype==8) $rehref = "Монета-";
			if ($mtype==7) $rehref = "Набор-монет-";
			if ($mtype==2) $rehref = "Банкнота-";
			if ($mtype==4) $rehref = "Набор-монет-";
			if ($mtype==5) $rehref = "книга-";
			if ($mtype==9) $rehref = "Лот монет ";
			
			if ($rows['gname'])
				$rehref .= $rows['gname']."-";
			$rehref .= $rows['name'];
			if ($rows['metal'])
				$rehref .= "-".$rows['metal']; 
			if ($rows['year'])
				$rehref .= "-".$rows['year'];
			$namecoins = $rehref;
			$rehrefdubdle = contentHelper::strtolower_ru($rehref)."_c".($mtype==1?$rows['parent']:$rows['shopcoins'])."_pc".($parent>0?$parent:(($mtype==7 || $mtype==8 || $mtype==6 || $mtype==4 || $mtype==2) && $rows["amount"]>1?$rows['shopcoins']:($mtype==1?$rows['parent']:0)))."_m".$rows['materialtype']."_pp1.html";
			$rehref = contentHelper::strtolower_ru($rehref)."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";	
		} else {
			if ($mtype==1) $rehref = "Монета ";
			if ($mtype==8) $rehref = "Монета ";
			if ($mtype==7) $rehref = "Набор монет ";
			if ($mtype==2) $rehref = "Банкнота ";
			if ($mtype==4) $rehref = "Набор монет ";
			if ($mtype==5) $rehref = "Книга ";
			if ($mtype==9)	$rehref = "Лот монет ";
			if ($mtype==10)	$rehref = "Нотгельд ";
			if ($mtype==11)	$rehref = "Монета ";	
			
			if (in_array($rows["materialtype"],array(2,4,7,8,6)) && $rows['amount']>10) 
				$rows['amount'] = 10;
		
			if ($rows['gname']) $rehref .= $rows['gname']." ";
			$rehref .= $rows['name'];
			if ($rows['metal']) $rehref .= " ".$rows['metal']; 
			if ($rows['year']) $rehref .= " ". $rows['year'];
	
			$namecoins = $rehref;		
			$rehrefdubdle = contentHelper::strtolower_ru($rehref)."_c".(($mtype==1 || $mtype==10 || $mtype==12)?$rows['parent']:$rows['shopcoins'])."_pc".($parent>0?$parent:(($mtype==7 || $mtype==8 || $mtype==6 || $mtype==4 || $mtype==2) && $rows["amount"]>1?$rows['shopcoins']:(($mtype==1 || $mtype==10)?$rows['parent']:0)))."_m".$rows['materialtype']."_pp1.html";
			$rehref = contentHelper::strtolower_ru($rehref)."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";		
		}
		return array('namecoins' => $namecoins, 'rehrefdubdle' => $rehrefdubdle,'rehref'=> $rehref);
    }
    
    function to_cp($str){
   		return iconv("utf-8", "windows-1251", $str);
	}

	function to_utf($str){
   		return iconv("windows-1251","utf-8", $str);
	}
	
    static $menu = array(1=>"Монеты",
                         8=>"Мелочь",
                         6=>"Цветные монеты" ,
                         10=>"Нотгельды",
                         7=>"Наборы монет",
                         9=>"Лоты монет для начинающих нумизматов",
                         2=>"Боны",
                         3=>"Аксессуары для монет",
                         4=>"Подарочные наборы",
                         5=>"Книги о монетах",
                         11=>'Барахолка');
}

?>