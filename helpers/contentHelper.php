<?
class contentHelper{    

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
    static function showImage($url,$title){
       return "<img src='http://numizmatik.ru/shopcoins/$url' title='$title'>";
       
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
    static function rehrefdubdle($mtype,$parent,$rows){
       $url =  self::strtolower_ru($rehref)."_c";
       $url .= (($mtype==1 || $mtype==10 || $mtype==12)?$rows['parent']:$rows['shopcoins']);
       $url .="_pc".($parent>0?$parent:(($mtype==7 || $mtype==8 || $mtype==6 || $mtype==4 || $mtype==2) && $rows["amount"]>1?$rows['shopcoins']:(($mtype==1 || $mtype==10)?$rows['parent']:0)))."_m".$rows['materialtype']."_pp1.html";
	   $url .= contentHelper::strtolower_ru($url)."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";
       return;
        
    }
}

?>