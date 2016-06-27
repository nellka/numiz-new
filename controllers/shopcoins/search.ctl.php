<?
require($cfg['path'].'/helpers/Paginator.php');
require $cfg['path'] . '/configs/config_shopcoins.php';
//require $cfg['path'] . '/models/search.php';

require_once $cfg['path'] . '/models/shopcoinsdetails.php';
$details_class = new model_shopcoins_details($cfg['db']);

//$serach_class = new search($cfg['db'],$tpl['user']['user_id'],$nocheck);
 
$search = request('search');

if(contentHelper::get_encoding($search)=='windows-1251'){
	$search = iconv( "CP1251//TRANSLIT//IGNORE","UTF8", $search);
}


$searchArray = explode(' ',$search);

$words = array();

foreach ($searchArray as $s){
    if(trim($s)){
        $words[] = trim($s);
    }
}

$numbers =  array();
$years =  array();
$digits =  array();
$strings =  array();

$reg_n="/([A-Za-z0-9-]*)/isu";
$reg_y="/^19[0-9]{2}$|^20[0-9]{2}|^18[0-9]{2}|^17[0-9]{2}|^16[0-9]{2}|^15[0-9]{2}$/";
$reg_d="/^[0-9]*$/";

$i=0;
$k = 0;

foreach ($words as $word){

    $i++;
    if($k==$i) continue;
    $word = trim($word);
    
    preg_match($reg_y,$word,$d);
    if($d&&$d[0]){
        $years[] = (int)$word;
        continue;
    } 

    preg_match($reg_d,$word,$d);

    if($d&&$d[0]){
        $digit = $word;
        if(isset($words[$i])){
            $digit .= ' '.trim($words[$i]);
            $k=$i+1;
            
        }
        $digits[] = $digit;        
        continue;
    }   
    
    preg_match($reg_n,$word,$d);
    
    if($d&&$d[0]){
        $numbers[] = $word;
        $strings[] = $word;
        continue;
    }   
    
    $strings[]= $word;   
}

$tpl['pager']['sorts'] = array('dateinsert'=>'новизне',                     
                      'price'=>'по цене',
                      'year'=>'году');
$tpl['pager']['itemsOnpage'] = array(12=>12,24=>24,48=>48);

$orderby_param = '';
if(request('orderby')){
    $tpl['orderby'] = request('orderby');  
    $orderby_param = '&orderby='.  $tpl['orderby'];
}	


//if(!isset($tpl['orderby']))	$tpl['orderby'] = "yeardesc";

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

if(request('onpage')){
    $tpl['onpage'] = request('onpage');
} else {
    $tpl['onpage'] = 24;
}	


//setcookie('onpage', $tpl['onpage'],  time()+ 86400 * 90,'/',$cfg['domain']);

$mycoins = 0;
$ourcoinsorder = Array();
$ourcoinsorderamount = Array();


//$SearchTemp = explode(' ',$search);
$WhereThemesearch = array();
$SearchTempDigit = array();
$WhereCountryes = array();
$SearchTempStr = array();
$SearchTempMatch = array();

$tigger=0;

//var_dump($numbers,$years,$digits,$strings);
$where = array();
$result_temp_name = array();
$result_temp_group = array();

foreach ($strings as &$string){
    if(mb_strtolower($string)=='англия'){
        $string = 'Великобритания';
        continue;
    }
    if(mb_strtolower($string)=='cu-ni'){
        $string = 'медно-никель';
        continue;
    }
    if(mb_strtolower($string)=='ag'){
        $string = 'серебро';
        continue;
    }
    if(mb_strtolower($string)=='au'){
        $string = 'золото';
        continue;
    }
    if(mb_strtolower($string)=='cu'){
        $string = 'медь';
        continue;
    }
}

$result_temp_name = array();
$result_temp_metal = array();
$result_temp_condition = array();
$result_temp_details = array();

if (sizeof($digits)) {  
    $result_temp_name = $shopcoins_class->searchInTable('shopcoins_search_name',$digits);   
}

if (sizeof($strings)) {  
    $result_t_name = $shopcoins_class->searchInTable('shopcoins_search_name',$strings);

    foreach ($result_t_name as $key=>$row){
        $result_temp_name[$key] = $row;
    }    
}

if (sizeof($digits)) {  
    $result_temp_details = $details_class->search($digits,'shopcoins_search_details');   
}

if (sizeof($strings)) {  
    $result_t_details = $details_class->search($strings,'shopcoins_search_details');
    foreach ($result_t_details as $key=>$row){
        $result_temp_details[$key] = $row;
    }    
}


if (sizeof($strings)) {  
   $result_temp_metal = $shopcoins_class->searchTable('shopcoins_metal',$strings);

}

if (sizeof($strings)||sizeof($numbers)) {  
    $array_for_conditions = $strings;
    foreach ($numbers as $number){
        $array_for_conditions[] = $number;
    }
	$result_temp_condition = $shopcoins_class->searchTable('shopcoins_condition',$array_for_conditions);    
}

if (sizeof($strings)) { 
    $result_temp = $shopcoins_class->searchGroups($strings);
    $parents = array();
	foreach ($result_temp as $rows_temp) {	
		$WhereCountryes[] = $rows_temp['group'];
		if ($rows_temp['groupparent']==0) {			
			$parents[] = $rows_temp['group'];
		}
	}
	if($parents){
		$p_groups = $shopcoins_class->searchParrentGroups($parents);
		foreach ($p_groups as $gr){
			if(!in_array($gr["group"],$WhereCountryes)) $WhereCountryes[] = $gr["group"];
		}		
	}
}


if (sizeof($numbers)) {
    $whereNumber = "number like '".implode("%' or number like '%",$numbers)."%'";   
    
    //$whereNumber2 = "number2 like '".implode("%' or number like '%",$numbers)."%'";   
}
if (sizeof($years)) {
    $whereYear = "year in (".implode(",",$years).")";   
}

$CounterSQL_data = array();

if ($result_temp_details) {
	$CounterSQL = "if(s.shopcoins in (".implode(",",array_keys($result_temp_details))."), 1,0) as coefficientcoins";
} else {
    $CounterSQL = "0 as coefficientcoins";
}

if($WhereCountryes){
    $CounterSQL .= ", if(s.group in (".implode(",",$WhereCountryes)."), 5,0) as coefficientgroup";
} else {
     $CounterSQL .= ", 0 as coefficientgroup";
}
//$CounterSQL_data[] ='coefficientcoins';
//$CounterSQL_data[] ='coefficientgroup';
if ($result_temp_name) {
	$CounterSQL .= ", if(s.nominal_id in (".implode(",",array_keys($result_temp_name))."), 4,0) as coefficientnominal";
} else {
    $CounterSQL .= ", 0 as coefficientnominal";
}

if ($result_temp_metal) {
	$CounterSQL .= ", if(s.metal_id in (".implode(",",$result_temp_metal)."), 2,0) as coefficientmetal";
} else {
    $CounterSQL .= ", 0 as coefficientmetal";
}

if ($result_temp_condition) {
	$CounterSQL .= ", if(s.condition_id in (".implode(",",array_keys($result_temp_condition))."), 1,0) as coefficientcondition";
} else {
    $CounterSQL .= ", 0 as coefficientcondition";
}

if ($years) {
	$CounterSQL .= ", if($whereYear and s.year<>0,3,0) as coefficientyear";
} else {
    $CounterSQL .= ", 0 as coefficientyear";
}

$WhereArray = '';
if($result_temp_details){
    $WhereArray =" (s.shopcoins in (".implode(",",array_keys($result_temp_details))."))";
}

$WhereArray .=/*(sizeof($words)?"(shopcoins.details like '%".implode("%' or shopcoins.details like '%",$words)."%')":"").*/
//(sizeof($numbers)? ($WhereArray? "(or $whereNumber or $whereNumber2)":"($whereNumber or $whereNumber2)"):"");
(sizeof($numbers)? ($WhereArray? " or ($whereNumber)":"$whereNumber"):"");

$WhereArray .= (sizeof($years)?($WhereArray? " or ($whereYear)":"($whereYear)"):"").
(sizeof($WhereThemesearch)?" or ".implode(" or ",$WhereThemesearch):"");

$WhereArray .= sizeof($WhereCountryes)?($WhereArray? (" or s.`group` in (".implode(",",$WhereCountryes).")"):" s.`group` in (".implode(",",$WhereCountryes).")"):"";

if($result_temp_metal){    
    $WhereArray .=" or (s.metal_id in (".implode(",",$result_temp_metal)."))";
}
if($result_temp_condition){
    $WhereArray .=" or (s.condition_id in (".implode(",",$result_temp_condition)."))";
}

if($result_temp_name){
    $WhereArray .= $WhereArray?" or (s.nominal_id in (".implode(",",array_keys($result_temp_name))."))":" (s.nominal_id in (".implode(",",array_keys($result_temp_name))."))";
}

$OrderByArray = Array();
if(!request('orderby')){    
    $OrderByArray[] = "(coefficientcoins+coefficientgroup+coefficientnominal+coefficientyear+coefficientmetal+coefficientcondition) desc, coefficientgroup desc, coefficientcoins desc ";  
    if (sizeof($OrderByArray))	$orderby = "order by s.`check` asc,".implode(",",$OrderByArray);
} else {    
    if ($tpl['orderby']=="dateinsertdesc"){
    	$OrderByArray[] = "s.dateinsert desc";
    	$OrderByArray[] = "s.price desc";
    } elseif ($tpl['orderby']=="dateinsertasc"){
    	$OrderByArray[] = " s.dateinsert asc";
    	$OrderByArray[] = "s.price desc";
    } elseif ($tpl['orderby']=="priceasc"){
    	$OrderByArray[] = "s.price asc";
    	$OrderByArray[] = "s.dateinsert desc ";
    } elseif ($tpl['orderby']=="pricedesc"){
    	$OrderByArray[] = "s.price desc";
    	$OrderByArray[] = "s.dateinsert desc";
    } elseif ($tpl['orderby']=="yearasc"){
    	$OrderByArray[] = "s.year asc";
    	$OrderByArray[] ="s.dateinsert desc";
    } elseif ($tpl['orderby']=="yeardesc"){
    	$OrderByArray[] = "s.year desc";
    	$OrderByArray[] = "s.dateinsert desc ";
    } elseif($materialtype==12){
    	$OrderByArray[] = "s.year desc";
    	$OrderByArray[] = "s.name desc ";
    } 
    
    $OrderByArray[] = "s.price desc";
    
    if (sizeof($OrderByArray)){
    	$orderby = "order by s.`check` asc,".implode(",",$OrderByArray);    
    }
	
}


$positive_amount = '';


if($tpl['user']['user_id']==811){
    $where = " where s.check>0 ".($WhereArray?" and ($WhereArray)":"");
} else {
    $where = " where s.check=1 ".($WhereArray?" and ($WhereArray)":"");
}


$sql_all = "select count(s.shopcoins) from shopcoins_search as s $where ".$positive_amount;

if($tpl['user']['user_id']==352480){
//var_dump($WhereArray,$where,$sql_all);
}

$countpubs = $shopcoins_class->countByParams($sql_all);

if($countpubs<($tpl['pagenum']-1)*$tpl['onpage']) $tpl['pagenum']=1;

//var_dump($countpubs );
//echo "<br><br>";
$sql = "select s.*, group.name as gname, group.groupparent ".($CounterSQL?",".$CounterSQL:"")." from shopcoins_search as s, `group` 
$where ".$positive_amount."and s.group=group.group  $orderby limit ".($tpl['pagenum']-1)*$tpl['onpage'].",".$tpl['onpage'];
//s.group<>"790"
$addhref = "?search=".$search."&pagenum=".$tpl['pagenum'];

$base_url = $cfg['site_dir']."shopcoins/index.php?search=".$search;

if($tpl['user']['user_id']==352480){
    echo $sql;
    echo "<br><br>";
}

$data = $shopcoins_class->getDataSql($sql);

if($addhref) $addhref = substr($addhref,1);  
$tpl['paginator'] = new Paginator(array(
        'url'        => $cfg['site_dir']."shopcoins/index.php?search=".$search.$orderby_param,
        'count'      => $countpubs,
        'per_page'   => $tpl['onpage'],
        'page'       => $tpl['pagenum'],
        'border'     =>4));
        

$tpl['shop']['errors'] = array();

$ArrayParent = Array();
$tpl['shop']['MyShowArray'] = Array();
$tpl['shop']['ArrayParent'] = Array();

foreach ($data as &$rows){
    $details = $details_class->getItem($rows['shopcoins']);    

    $rows['details'] = '';
    if($details)$rows['details']  = $details['details'];
	
   // var_dump($rows['coefficientcoins'],$rows['coefficientgroup'],$rows['coefficientnominal'],$rows['coefficientyear'],$rows['coefficientmetal'],$rows['coefficientcondition'],$rows['year'],$rows['group']);
   // echo "<br><br>";
    $rows['metal'] = $tpl['metalls'][$rows['metal_id']];
    $rows['condition'] = $tpl['conditions'][$rows['condition_id']];
	$tpl['shop']['ArrayShopcoins'][] = $rows["shopcoins"];
	$tpl['shop']['ArrayParent'][] = $rows["parent"];
	$tpl['shop']['MyShowArray'][] = $rows;
}

if (sizeof($tpl['shop']['ArrayParent'])) {
    $result_search = $shopcoins_class->getCoinsParents($tpl['shop']['ArrayParent']);
	foreach ($result_search as $rows_search ){		
		$tpl['shop']['ImageParent'][$rows_search["parent"]][] = $rows_search["image_small"];
	}	
}


if ($shopcoinsorder > 1) {
	$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);	
	foreach ( $result_ourorder as $rows_ourorder){
		$ourcoinsorder[] = $rows_ourorder["catalog"];
		$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
	}
}


$ShopcoinsThemeArray = Array();
$ShopcoinsGroupArray = Array();

$tpl['task'] = 'catalog_search';	

if (sizeof($tpl['shop']['MyShowArray'])==0){
	$tpl['shop']['errors'][] = "<br><p class=txt><strong><font color=red>Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</font></strong><br><br>";
} else {	
	$amountsearch = count($tpl['shop']['MyShowArray']);	
	
	foreach ($tpl['shop']['MyShowArray'] as $i=>$rows) {
	    $item = $shopcoins_class->getItem($rows['shopcoins']);
	   
        $rows = array_merge($rows,$item);
        $tpl['shop']['MyShowArray'][$i] = $rows;
        
       // var_dump($rows["image_small"]);
		//формируем картинки "подобные"
		$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'] = array();
		if (($rows["materialtype"] ==1)&&isset($tpl['shop']['ImageParent'][$rows["parent"]])&&$tpl['shop']['ImageParent'][$rows["parent"]]>0 && !$mycoins) {	
			$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'][] =contentHelper::showImage("smallimages/".$rows["image_small"],"Монета ".$rows["gname"]." | ".$rows["name"]);
			$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'][] =contentHelper::showImage("smallimages/".$tpl['shop']['ImageParent'][$rows["parent"]][0],"Монета ".$rows["gname"]." | ".$rows["name"]);
		}


		$tpl['shop']['MyShowArray'][$i] = array_merge($tpl['shop']['MyShowArray'][$i], contentHelper::getRegHref($rows,$materialtype,$parent));
		
		 if ($materialtype==5||$materialtype==3){			
			$tpl['shop']['MyShowArray'][$i]['amountall'] = ( !$rows["amount"])?1:$rows["amount"];		
		} else {			
			
			if (in_array($rows["materialtype"],array(2,4,7,8,6)) && $rows['amount']>10) 
				$rows['amount'] = 10;
			
		    $amountall = $rows['amount'];
			if (in_array($rows["materialtype"],array(8,6,7,2,4))) {		
				$amountall = ( !$rows["amount"])?1:$rows["amount"];				
			}			
			$tpl['shop']['MyShowArray'][$i]['amountall'] = $amountall;				
		}
		/*$tpl['shop']['MyShowArray'][$i]['namecoins'] = $namecoins;
		$tpl['shop']['MyShowArray'][$i]['rehrefdubdle'] = $rehrefdubdle ;
		$tpl['shop']['MyShowArray'][$i]['rehref'] = $rehref ;	*/
		//формируем рейтинги
	    $tpl['shop']['MyShowArray'][$i]['mark'] = $shopcoins_class->getMarks($rows["shopcoins"]);

		
		$coefficient = 0;
		$sumcoefficient = 0 ;
		$maxcoefficient = 0 ;
		if(!isset($rows['coefficientcoins'])) $rows['coefficientcoins'] = 0;
		if(!isset($rows['coefficientgroup'])) $rows['coefficientgroup'] = 0;
		if(!isset($rows['counterthemeyear'])) $rows['counterthemeyear'] = 0;
		if ($rows['coefficientcoins']) 
			$coefficient = $coefficient+$rows['coefficientcoins'];
		if ($rows['coefficientgroup']) 
			$coefficient = $coefficient+$rows['coefficientgroup']*2;
		if ($rows['counterthemeyear']) 
			$coefficient = $coefficient+$rows['counterthemeyear'];
			
		if ($coefficient>0) {		
			if ($coefficient>$maxcoefficient)
				$maxcoefficient = $coefficient;			
			$sumcoefficient = $sumcoefficient+$coefficient;
		}
				
			
		$tpl['shop']['MyShowArray'][$i]['coefficient'] = $coefficient;
		$tpl['shop']['MyShowArray'][$i]['sumcoefficient'] = $sumcoefficient ;
		$tpl['shop']['MyShowArray'][$i]['maxcoefficient'] = $maxcoefficient ;	
		
		$tpl['shop']['MyShowArray'][$i]['buy_status'] = 0 ;		
		$textoneclick='';	
		
		if(!$mycoins) {			
			$statuses = $shopcoins_class->getBuyStatus($rows['shopcoins'],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
			$tpl['shop']['MyShowArray'][$i]['buy_status'] = $statuses['buy_status'];
			$tpl['shop']['MyShowArray'][$i]['reserved_status'] = $statuses['reserved_status'];	
		}						
						
		$shopcoinstheme = array();
		$strtheme = decbin($rows["theme"]);
		$strthemelen = strlen($strtheme);
		
		$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
		for ($k=0; $k<$strthemelen; $k++) {
			if ($chars[$k]==1)
			{
				$shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
				if (!in_array(($strthemelen-1-$k), $ShopcoinsThemeArray))
					$ShopcoinsThemeArray[] = ($strthemelen-1-$k);
			}
		}
		
		if (!in_array($rows["group"], $ShopcoinsGroupArray))
			$ShopcoinsGroupArray[] = $rows["group"];
		
		$tpl['shop']['MyShowArray'][$i]['shopcoinstheme'] = $shopcoinstheme;	
		$i++;	
	}
	
}

//записываем статистике по тому, что искали
if ($search && $search != 'revaluation' && $search != 'newcoins'){	
    //пока откладываем	
   // $shopcoins_class->addSearchStatistic();	   
}		



/*
if ($GroupDescription)
			echo "<br>".$GroupDescription."<br>";

if ($materialtype==1 && !$mycoins)
{
	echo $AdvertiseText;
}*/

//if ($group)	include_once "othermaterialid.php";

?>