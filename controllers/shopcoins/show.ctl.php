<?
require($cfg['path'].'/helpers/Paginator.php');
require($cfg['path'].'/models/catalogshopcoinsrelation.php');

$catalogshopcoinsrelation_class = new model_catalogshopcoinsrelation($cfg['db']);

$page = 'show';

$arraynewcoins = Array(1=>date('Y')-2,2=>date('Y')-1,3=>date('Y'));
$show50 = 0;

$catalog=(integer)request('catalog');
$materialtype=(integer)request('materialtype');
$parent=(integer)request('parent');

if (!$materialtype) 	$materialtype = 1;
if ($parent && !$catalog) 	$catalog = $parent;

$orderusernow = 0;
$ciclelink = "";

$num = 0;
$checkuser = 0;

$arraykeyword = array();
/*
if (($cookiesuserdate && (($cookiesuserdate + 24*60*60) > $timenow)) || !$cookiesuser || $shopkey || $materialtype!=1) $checkuser = 0;
else {

	$checkuser = 1;
	
	if (!$cookiesuserdate) $dateselect = time() - 30*24*60*60;
	else $dateselect = $cookiesuserdate;
	
	$sql = "select count(*)  
		from `shopcoins`, `group`, `clientselectshopcoins` 
		where shopcoins.`group`=`group`.`group` AND shopcoins.`check`='1' 
		and shopcoins.`shopcoins` = clientselectshopcoins.`shopcoins` 
		and clientselectshopcoins.`user` = '$cookiesuser' and clientselectshopcoins.`dateselect` >= '$dateselect';";
	echo $sql;
	
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$num = $rows[0];
	
	setcookie("cookiesuserdate", $timenow, time() + 30*24*60*60, "/shopcoins/", $domain);
	setcookie("cookiesuserdate", $timenow, time() + 30*24*60*60, "/shopcoins/");
	setcookie("cookiesuserdate", $timenow, time() + 30*24*60*60, "/shopcoins/", ".shopcoins.numizmatik.ru");
//	echo 'nnnnnnn = '.$num.' <br>';
}

if ($smallcoinsshow < $timenow && $materialtype==8) {

	$showdetailssmall = 1;
	setcookie("smallcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/", $domain);
	setcookie("smallcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/");
	setcookie("smallcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/", ".shopcoins.numizmatik.ru");
}
else {

	$showdetailssmall = 0;
}

if ($setcoinsshow < $timenow && $materialtype==7) {

	$showdetailsset = 1;
	setcookie("setcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/", $domain);
	setcookie("setcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/");
	setcookie("setcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/", ".shopcoins.numizmatik.ru");
}
else {

	$showdetailsset = 0;
}
*/

//$addhref = ($materialtype?"&materialtype=$materialtype":"");
//$addhref .= ($tpl['pagenum']?"&pagenum=".$tpl['pagenum']:"");

$tpl['show']['error'] = false;
//показываем количество страниц
if ($catalog){	
    //стартовая инфа о монете независимо от родитея
	$rows_main = $shopcoins_class ->getItem($catalog,true);	
	
	$next_coins = $shopcoins_class->getNext($catalog,$materialtype);
	$previos_coins = $shopcoins_class->getPrevios($catalog,$materialtype);
	$tpl['show']['next'] = contentHelper::getRegHref($next_coins,$materialtype,$parent);
	$tpl['show']['previos'] = contentHelper::getRegHref($previos_coins,$materialtype,$parent);	

	if ($rows_main&&$rows_main['check']==0) {	
   		// die('check=0');
    /*
	
		
		$sql_r = "select s.*, g.name as gname, g.groupparent as ggroup from shopcoins as s, `group` as g where 
		s.parent='".$rows_main['parent']."' and (".($cookiesuser==811?(!$nocheck?"s.check=1 or s.check>3":"s.check>3"):"s.check=1").") and g.`group` = s.`group` limit 1;";
		$result_r = mysql_query($sql_r);
		if (mysql_num_rows($result_r)==1) {
			
			$rows_main = mysql_fetch_array($result_r);
			$result_info1 = $sql_r;
			$tpl['show']['parentinfo'] = 0;
		}
		else {
			
			$sql_c = "select catalog from catalogshopcoinsrelation where shopcoins='$catalog';";
			$result_c = mysql_query($sql_c);
			if (mysql_num_rows($result_c)>0) {
			
				$rows_c = mysql_fetch_array($result_c);
				
				$sql_s = "select s.*, g.name as gname, g.groupparent as ggroup from shopcoins as s, `group` as g, catalogshopcoinsrelation as c where 
				c.catalog='".$rows_c[0]."' and c.shopcoins=s.shopcoins and (".($cookiesuser==811?(!$nocheck?"s.check=1 or s.check>3":"s.check>3"):"s.check=1").") and g.`group` = s.`group` limit 1;";
				$result_s = mysql_query($sql_s);
				if (mysql_num_rows($result_s)==1) {
				
					$rows_main = mysql_fetch_array($result_s);
					$result_info1 = $sql_s;
					$tpl['show']['parentinfo'] = 0;
				}
			}
		}*/
		}
		
		$tpl['show']['rowscicle'] = $shopcoins_class->getCoinsrecicle($catalog);
		$tpl['show']['resultcicle'] = $shopcoins_class->getPopular(3,array('materialtype' => $materialtype, 'shopcoins.group' => $rows_main["group"]));
	
		$tmp = Array();
		$LastCatalog10_tmp = "";
		if ($LastCatalog10)	{
			$tmp = explode("#", $LastCatalog10);
			$LastCatalog10_tmp ='';
			for ($i=0; $i < (sizeof($tmp)<10?sizeof($tmp):10); $i++)
			{
				unset ($tmp1);
				$tmp1 = explode("|", $tmp[$i]);
				if ($tmp1[0] != $catalog)
				{
					$LastCatalog10_tmp .= $tmp[$i]."#";
				}
			}
		}
		
		if ($rows_main["shopcoins"]) {
			$LastCatalog10 = $rows_main["shopcoins"]."|".$rows_main["gname"]."|".$rows_main["group"]."|".$rows_main["materialtype"]."|".$rows_main["name"]."|".$rows_main["price"]."|".$rows_main["image_small"]."|".$rows_main["image_big"]."#".$LastCatalog10_tmp;
		} else {
			$LastCatalog10 = $LastCatalog10_tmp;
	
			setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/shopcoins/", $domain);
			setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/shopcoins/");
			setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/shopcoins/", ".shopcoins.numizmatik.ru");
			setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/");	
		}
		//подключаем ключевые слова для страницы товаров
	    include($cfg['path'].'/configs/show_keywords.php');	
		/*if ($parent){		
			if (!$pagenumparent) $pagenumparent = 1;				
		    $rows_main = $shopcoins_class->coinsWithParentDetails($catalog,$materialtype,$pagenumparent, $tpl['onpage']);		
		} else {
		    $details = $shopcoins_class->getItem($catalog,true);
		    $rows_main =array($details);
		}*/
		
	$tpl['show']['described'] = false;
	//кто описывал монету
	if ($catalog && $tpl['user']['user_id'] ) {  
		//if(	$materialtype == 11 &&$tpl['user']['user_id'] &&$user_class->is_user_has_premissons() && !$shopcoins_class->is_already_described($catalog)
		//){ // if user has 5 orders && !is_locked && item not have description
	
	        $tpl['show']['described'] = true;			
			$result = $shopcoins_class->getGroupsForDescribe();
            $groupselect_v = array();
            $groupselect_v2 = "";
			$i=0;
			foreach ($result as $rows){
			    $groupselect_v[] = $rows["name"];
			     $groupselect_v2[] = $rows["name"];
				//$groupselect_v .= ($i!=0?",\"":"\"").str_replace('"','',$rows["name"])."\"";
				//$groupselect_v2 .= ($i!=0?",":"").str_replace('"','',$rows["name"])."";
				//$i++;
			}
			
		//}
	}	
	
	//выбираем что он из аксессуаров/книг заказал
	if (($materialtype==3 || $materialtype==5) || $rows_main["materialtype"]==3 || $rows_main["materialtype"]==5){
		die('$shopcoinsorder');
		if ($shopcoinsorder > 1){
			$sql_ourorder = "select * from orderdetails where `order`='$shopcoinsorder' and date > ".(time() - $reservetime).";";
			//echo $sql_ourorder;
			$result_ourorder = mysql_query($sql_ourorder);
		
			$ourcoinsorder = Array();
			$ourcoinsorderamount = Array();
		
			while ($rows_ourorder = mysql_fetch_array($result_ourorder))
			{
				$ourcoinsorder[] = $rows_ourorder["catalog"];
				$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
				//echo "<br>".$rows_ourorder["catalog"]." - ".$rows_ourorder["amount"];
			}
		}
	}	
	$tpl['show'][$rows_main["shopcoins"]]['mark'] = $shopcoins_class->getMarks($rows_main["shopcoins"]);
	$tpl['show']['reviews'] = $shopcoins_class->getReviews($rows_main["shopcoins"]);
	//var_dump($tpl['show']['reviews']);
	if(count($tpl['show']['reviews']['reviewusers'])<5) $additional_reviews =  $shopcoins_class->getAdditionalReviews();
	foreach ($additional_reviews as $review){
	 	$tpl['show']['reviews']['reviewusers'][] = $review;
	} 
	//var_dump($tpl['show']['reviews']);
	$tpl['show']['rows_main'] = $rows_main;
	
	//тематика
	$shopcoinstheme = array();
	$ShopcoinsThemeArray = array();
	$strtheme = decbin($rows_main["theme"]);
	$strthemelen = strlen($strtheme);
	$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
	for ($k=0; $k<$strthemelen; $k++)
	{
		if ($chars[$k]==1)
		{
			$shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
			if (!in_array(($strthemelen-1-$k), $ShopcoinsThemeArray))
				$ShopcoinsThemeArray[] = ($strthemelen-1-$k);
		}
	}
	$tpl['show']['shopcoinstheme']  = $shopcoinstheme;
	//серия
	if ($rows_main["series"]){
		$rows_main["series_title"] = $shopcoins_class->getSeries($rows_main["series"]);
	}
	
	$rows_main['year'] = contentHelper::setYearText($rows_main['year'],$materialtype);
	$tpl['shop']['related'] = array();
	//сейчас показываем токо для аксессуаров
	if ($materialtype==3){
		//показ сопутствующих товаров
		$tpl['shop']['related'] = $shopcoins_class->getRelated($catalog);
		$i = 0;
		$oldmaterialtype = 0;
		if ($tpl['shop']['related']){		
			foreach ($tpl['shop']['related'] as $rows){
			    $tpl['shop']['related'][$i]['additional_title'] = '';
				if ($oldmaterialtype != $rows["materialtype"]) {
					$tpl['shop']['related'][$i]['additional_title'] = $MaterialTypeArray[$rows["materialtype"]];
					$oldmaterialtype = $rows["materialtype"];
				}		
				$i++;
			}
		}
	}	
	
	$tpl['shop']['resultp'] = $shopcoins_class->showedWith($catalog, $rows_main);
	//$tpl['shop']['resultp'][$i]['buy_status']=2;
		
	$tpl['shop']['result_show_relation2'] = array();
	$tpl['shop']['result_show_relation3'] = array();
	
	if ($rows_main['group']&&$rows_main['name']) { 
		$tpl['shop']['result_show_relation2'] = $shopcoins_class->getRelatedByGroup($rows_main['group'],$rows_main['name'],$catalog);		
	}
	
	//с этим товаром покупали так же
	if ($tpl['user']['user_id']){
	    $rows = $user_class->getRowByParams(array('user'=>$tpl['user']['user_id']));   
		$user = $tpl['user']['user_id'];
		$email = $rows["email"];
		$fio = $rows["fio"];
	}
	
	/*if (!$tpl['shop']['result_show_relation2']) { 	
		$tpl['show']['result_show_relation2'] = $shopcoins_class->fromCatalogByGname($rows_main);
		if(!$tpl['show']['result_show_relation2']){
			$tpl['show']['parentinfo'] = 0;
		}
	}*/
	$tpl['show']['result_show_relation3'] = array();
		
	//if (!$tpl['shop']['result_show_relation2'] ) {
		$mycatalog1 = $catalogshopcoinsrelation_class->getOneByParams('catalog',array('shopcoins'=>$catalog));
	
		if ($mycatalog1) {		
			$tpl['show']['result_show_relation3'] = $catalogshopcoinsrelation_class->getRelations2($catalog,$mycatalog1);		
		}
	//}
	$ourcoinsorder = array();
	if ($materialtype==3 || $materialtype==5) {
		if ($shopcoinsorder > 1) {
			$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);	
			foreach ( $result_ourorder as $rows_ourorder){
				$ourcoinsorder[] = $rows_ourorder["catalog"];
				$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
			}
		}
	}

	$statuses = $shopcoins_class->getBuyStatus($catalog,$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
	$rows_main['buy_status'] = $statuses['buy_status'];
	$rows_main['reserved_status'] = $statuses['reserved_status'];		
} else {
	$tpl['show']['error']['no_coins'] = true;
}



?>