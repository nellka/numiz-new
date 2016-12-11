<?
$searchArray = explode(' ',$rows_main['name'].' '.$rows_main['gname'].' '.$rows_main['year']);

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
$SearchTempDigit = array();
$WhereCountryes = array();
$SearchTempStr = array();
$SearchTempMatch = array();


$where = array();
$result_temp_name = array();
$result_temp_group = array();

$result_temp_name = array();
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

if ($result_temp_name) {
	//$CounterSQL .= ", if(s.nominal_id in (".implode(",",array_keys($result_temp_name))."), 4,0) as coefficientnominal";
	$CounterSQL .= ", if(s.nominal_id in (".implode(",",array_keys($result_temp_name))."), 4,0) as coefficientnominal";
} else {
    $CounterSQL .= ", 0 as coefficientnominal";
}

/*if ($rows_main['metal_id']) {
	$CounterSQL .= ", if(s.metal_id =".$rows_main['metal_id'].", 2,0) as coefficientmetal";
} else*/ {
    $CounterSQL .= ", 0 as coefficientmetal";
}

/*if ($rows_main['condition_id']) {
	$CounterSQL .= ", if(s.condition_id = ".$rows_main['condition_id'].", 1,0) as coefficientcondition";
} else */{
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


$WhereArray .= (sizeof($years)?($WhereArray? " or ($whereYear)":"($whereYear)"):"");

$WhereArray .= sizeof($WhereCountryes)?($WhereArray? (" or s.`group` in (".implode(",",$WhereCountryes).")"):" s.`group` in (".implode(",",$WhereCountryes).")"):"";



//if($rows_main['metal_id']) $WhereArray .=" or (s.metal_id =".$rows_main['metal_id'].")";

//if($rows_main['condition_id'])   $WhereArray .=" or (s.condition_id = ".$rows_main['condition_id'].")";

if($result_temp_name){
    $WhereArray .= $WhereArray?" or (s.nominal_id in (".implode(",",array_keys($result_temp_name))."))":" (s.nominal_id in (".implode(",",array_keys($result_temp_name))."))";
}

$where = " where shopcoins<>".$rows_main['shopcoins']." and s.check=1 ".($WhereArray?" and ($WhereArray)":"");

//$where .= ($rows_main['group']? (" and s.`group` =".$rows_main['group']):"");

$sql_all = "select count(s.shopcoins) from shopcoins_search as s $where ";

$countpubs = $shopcoins_class->countByParams($sql_all);

$sql = "select s.*, group.name as gname, group.groupparent ".($CounterSQL?",".$CounterSQL:"")." from shopcoins_search as s, `group` 
$where and s.group=group.group  order by s.`check` asc, (coefficientcoins+coefficientgroup+coefficientnominal+coefficientyear+coefficientmetal+coefficientcondition) desc, coefficientgroup desc, coefficientcoins desc limit 20";
if($tpl['user']['user_id']==352480){
	echo $sql." 3<br>";
}
//echo $sql;

$data = $shopcoins_class->getDataSql($sql);

$tpl['shop']['errors'] = array();

$ArrayParent = Array();
$tpl['shop']['MyShowArray'] = Array();
$tpl['shop']['ArrayParent'] = Array();

foreach ($data as &$rows){
    $details = $details_class->getItem($rows['shopcoins']);    

    $rows['details'] = '';
    if($details)$rows['details']  = $details['details'];
	
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

$ShopcoinsThemeArray = Array();
$ShopcoinsGroupArray = Array();

if (sizeof($tpl['shop']['MyShowArray'])){	
    
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
		//формируем рейтинги
	    $tpl['shop']['MyShowArray'][$i]['mark'] = $shopcoins_class->getMarks($rows["shopcoins"]);
	
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