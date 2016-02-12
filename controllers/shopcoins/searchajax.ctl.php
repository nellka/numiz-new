<?
//'http://numizmatik1.ru/new/shopcoins/moneta-rossiya-5-rublei-medno-nikeli-2014_c968356_m8.html'

require $cfg['path'] . '/configs/config_shopcoins.php';


$search = request('term');

/*
$SearchTemp = explode(' ',$search);
$WhereThemesearch = array();
$SearchTempDigit = array();
$WhereCountryes = array();
$SearchTempStr = array();

$tigger=0;
if (sizeof($SearchTemp)>0) {
	for ($i=0;$i<sizeof($SearchTemp);$i++) {		
		if (!trim($SearchTemp[$i]))
			continue;

		if (intval($SearchTemp[$i]) ) {
			
			if ( (isset($SearchTemp[$i-1]) && intval($SearchTemp[$i-1])) || !$SearchTemp[$i+1] || intval($SearchTemp[$i+1])) { 
				
				$SearchTempMatch[] = $SearchTemp[$i];
				$tigger=0;
			}
			else 
				$tigger=1;
			
			$SearchTempDigit[] = $SearchTemp[$i];
		} else {			
			$SearchTemp[$i] = str_replace("+"," ",$SearchTemp[$i]);			
			$strlen = mb_strlen(trim($SearchTemp[$i]),'UTF-8');
			$substr = mb_substr(trim($SearchTemp[$i]),0,$strlen-1,'UTF-8');
			//var_dump($SearchTemp[$i],$strlen,$substr);
			$SearchTempStr[] = $strlen>4?$substr:trim($SearchTemp[$i]);

			if ($i>0){ 
				$SearchTempMatch[] = ">".($strlen>4?$substr."*":trim($SearchTemp[$i]));
			} else {
				$SearchTempMatch[] = ($strlen>4?$substr."*":trim($SearchTemp[$i]));
			}
		
			if ($tigger==1) {
			
				$SearchTempMatch[] = '"'.$SearchTemp[$i-1]." ".trim($SearchTemp[$i]).'"';
				$SearchTempMatch[] = $SearchTemp[$i-1];
				$tigger=0;
			}
			
		}
		
		foreach ($ThemeArray as $key=>$value) {
			if (mb_stristr($SearchTemp[$i],$value,false,'UTF-8') ) {
				$WhereThemesearch[] = "`theme` & ".pow(2,$key).">0";
			}
		}
	
	}	
}

$result_temp_metal = array();
$result_temp_name = array();
$result_temp_condition = array();

if (sizeof($SearchTempStr)) {
	$result_temp_metal = $shopcoins_class->searchTable('metals',$SearchTempStr);
	$result_temp_name = $shopcoins_class->searchTable('nominals',$SearchTempStr);
	$result_temp_condition = $shopcoins_class->searchTable('conditions',$SearchTempStr);
	
	$result_temp = $shopcoins_class->searchGroups($SearchTempStr);
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

				
$CounterSQL = '';

$CounterSQL = (sizeof($SearchTempMatch)?" MATCH(shopcoins.`name`,shopcoins.details,shopcoins.number) AGAINST('".implode(" ",$SearchTempMatch)." ' IN BOOLEAN MODE) as coefficientcoins, if(`group`.`name` like '%".implode("%' or `group`.`name` like '%",$SearchTempStr)."%', 3,0) as coefficientgroup":"");

if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit)) {
    $CounterSQL .= ($CounterSQL)?",":'';
	$CounterSQL .= "if(".(sizeof($WhereThemesearch)?implode(" or ",$WhereThemesearch).", ".(sizeof($SearchTempDigit)?"if( shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,3,2)":"2").",".(sizeof($SearchTempDigit)?" if( shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,1.5,0)":"0"):" shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,1.5,0").") as counterthemeyear";
}

$wArr =  " ( ".(sizeof($SearchTempStr)?"((shopcoins.details like '%".implode("%' or shopcoins.details like '%",$SearchTempStr)."%') and shopcoins.details<>'')":"")."
".(sizeof($SearchTempDigit)?"or ((shopcoins.details like '".implode("' or shopcoins.details like '",$SearchTempDigit)."') and shopcoins.details<>'')":"")."
".(sizeof($SearchTempStr)?"or shopcoins.number in ('".implode("','",$SearchTemp)."')":"")."
".(sizeof($SearchTempStr)?"or shopcoins.number2 in ('".implode("','",$SearchTemp)."')":"")."
".(sizeof($SearchTempDigit)?"or (shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0)":"");

if($result_temp_metal){
    $wArr .=" or (shopcoins.metal_id in (".implode(",",$result_temp_metal)."))";
}
if($result_temp_condition){
    $wArr .=" or (shopcoins.condition_id in (".implode(",",$result_temp_condition)."))";
}

if($result_temp_name){
    $wArr .=" or (shopcoins.nominal_id in (".implode(",",$result_temp_name)."))";
}

$wArr .=(sizeof($WhereThemesearch)?" or ".implode(" or ",$WhereThemesearch):"")." ".(sizeof($WhereCountryes)>0?" or shopcoins.`group` in (".implode(",",$WhereCountryes).")":"").")";

$WhereArray[] = $wArr;
$OrderByArray = Array();
if (isset($CounterSQL)&&$CounterSQL)
	if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit))
		$OrderByArray[] = " (coefficientcoins+counterthemeyear+coefficientgroup) desc, counterthemeyear desc, (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc  ";
	else
		$OrderByArray[] = " (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc ";

if (sizeof($OrderByArray))
	$orderby = "order by shopcoins.`check` asc,".implode(",",$OrderByArray);

$positive_amount = '';
$where = " where shopcoins.check=1 and ((shopcoins.materialtype in (2,4,7,8,3,5,9)) or (shopcoins.materialtype in(1,10) and shopcoins.amountparent>0) or shopcoins.number='$search' or shopcoins.number2='$search') 
	".(sizeof($WhereArray)?" and ".implode(" and ", $WhereArray):"");

//var_dump($CounterSQL);
$sql = "select shopcoins.*, group.name as gname, group.groupparent ".($CounterSQL?",".$CounterSQL:"")." from shopcoins, `group`
$where ".$positive_amount."and shopcoins.group=group.group  $orderby limit 5;";
//echo 	$sql;	
$data = array();
$res = $shopcoins_class->getDataSql($sql);*/

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
        $years[] = $word;
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
        continue;
    }   
    
    $strings[]= $word;   
}

//var_dump($search);
$sortname = request('sortname');

$tpl['pager']['sorts'] = array('dateinsert'=>'новизне',                     
                      'price'=>'по цене',
                      'year'=>'году');
$tpl['pager']['itemsOnpage'] = array(12=>12,24=>24,48=>48);

if(request('onpage')){
    $tpl['onpage'] = request('onpage');
} else {
    $tpl['onpage'] = 24;
}	

//setcookie('onpage', $tpl['onpage'],  time()+ 86400 * 90,'/',$cfg['domain']);

//сохраняем сортировку элементов на странице в куке
if(request('orderby')){
    $tpl['orderby'] = request('orderby');
} elseif (isset($_COOKIE['orderby'])){
    $tpl['orderby'] =$_COOKIE['orderby'];
}	
if(!isset($tpl['orderby']))	$tpl['orderby'] = "dateinsertdesc";
setcookie('orderby', $tpl['orderby'],  time()+ 86400 * 90,'/',$cfg['domain']);

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

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
/*
if (sizeof($SearchTemp)>0) {
	for ($i=0;$i<sizeof($SearchTemp);$i++) {		
		if (!trim($SearchTemp[$i]))
			continue;

		if (intval($SearchTemp[$i]) ) {
			
			if ( (isset($SearchTemp[$i-1]) && intval($SearchTemp[$i-1])) || !$SearchTemp[$i+1] || intval($SearchTemp[$i+1])) { 
				
				$SearchTempMatch[] = $SearchTemp[$i];
				$tigger=0;
			}
			else 
				$tigger=1;
			
			$SearchTempDigit[] = $SearchTemp[$i];
		} else {
			
			$SearchTemp[$i] = str_replace("+"," ",$SearchTemp[$i]);
			
			$strlen = mb_strlen(trim($SearchTemp[$i]),'UTF-8');
			$substr = mb_substr(trim($SearchTemp[$i]),0,$strlen-1,'UTF-8');
			//var_dump($SearchTemp[$i],$strlen,$substr);
			$SearchTempStr[] = $strlen>4?$substr:trim($SearchTemp[$i]);

			if ($i>0){ 
				$SearchTempMatch[] = ">".($strlen>4?$substr."*":trim($SearchTemp[$i]));
			} else {
				$SearchTempMatch[] = ($strlen>4?$substr."*":trim($SearchTemp[$i]));
			}
		
			if ($tigger==1) {
			
				$SearchTempMatch[] = '"'.$SearchTemp[$i-1]." ".trim($SearchTemp[$i]).'"';
				$SearchTempMatch[] = $SearchTemp[$i-1];
				$tigger=0;
			}
			
		}
		
		foreach ($ThemeArray as $key=>$value) {
			if (mb_stristr($SearchTemp[$i],$value,false,'UTF-8') ) {
				$WhereThemesearch[] = "`theme` & ".pow(2,$key).">0";
			}
		}
	
	}	
}*/

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

if (sizeof($digits)) {  
    $result_temp_name = $shopcoins_class->searchInTable('nominals',$digits);   
}

if (sizeof($strings)) {  
    $result_t_name = $shopcoins_class->searchInTable('nominals',$strings);
    foreach ($result_t_name as $key=>$row){
        $result_temp_name[$key] = $row;
    }    
}

if (sizeof($strings)) {  
   $result_temp_metal = $shopcoins_class->searchTable('metals',$strings);

}
if (sizeof($strings)) {  
	$result_temp_condition = $shopcoins_class->searchTable('conditions',$strings);    
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
    $whereNumber = "number like '%".implode("%' or number like '%",$numbers)."%'";   
    $whereNumber2 = "number2 like '%".implode("%' or number like '%",$numbers)."%'";   
}
if (sizeof($years)) {
    $whereYear = "year in (".implode(",",$years).")";   
}



//var_dump($where);
//var_dump($result_temp_name,$result_temp_metal,$result_temp_condition);

 
				

$CounterSQL_data = array();

if(sizeof($words)){
    $CounterSQL = "MATCH(shopcoins.details) AGAINST('".implode(" ",$words)." ' IN BOOLEAN MODE) as coefficientcoins";
} else {
    $CounterSQL = "0 as coefficientcoins";
}
if($WhereCountryes){
    $CounterSQL .= ", if(shopcoins.group in (".implode(",",$WhereCountryes)."), 5,0) as coefficientgroup";
} else {
     $CounterSQL .= ", 0 as coefficientgroup";
}
//$CounterSQL_data[] ='coefficientcoins';
//$CounterSQL_data[] ='coefficientgroup';
if ($result_temp_name) {
	$CounterSQL .= ", if(shopcoins.nominal_id in (".implode(",",array_keys($result_temp_name))."), 4,0) as coefficientnominal";
} else {
    $CounterSQL .= ", 0 as coefficientnominal";
}

if ($result_temp_metal) {
	$CounterSQL .= ", if(shopcoins.metal_id in (".implode(",",array_keys($result_temp_metal))."), 2,0) as coefficientmetal";
} else {
    $CounterSQL .= ", 0 as coefficientmetal";
}

if ($result_temp_condition) {
	$CounterSQL .= ", if(shopcoins.metal_id in (".implode(",",array_keys($result_temp_condition))."), 1,0) as coefficientcondition";
} else {
    $CounterSQL .= ", 0 as coefficientcondition";
}

if ($years) {
	$CounterSQL .= ", if($whereYear and shopcoins.year<>0,3,0) as coefficientyear";
} else {
    $CounterSQL .= ", 0 as coefficientyear";
}
/*
if (sizeof($WhereThemesearch) || sizeof($years)) {

	$CounterSQL .= ", if(".
	(sizeof($WhereThemesearch)?implode(" or ",$WhereThemesearch).", ".(sizeof($years)?"if( $whereYear and shopcoins.year<>0,3,2)":"2").",".(sizeof($years)?" if( $whereYear and shopcoins.year<>0,1.5,0)":"0"):" $whereYear and shopcoins.year<>0,1.5,0")
	.") as counterthemeyear";
}*/
/*

$CounterSQL = (sizeof($SearchTempMatch)?" MATCH(shopcoins.`name`,shopcoins.details,shopcoins.metal,shopcoins.number,shopcoins.condition) AGAINST('".implode(" ",$SearchTempMatch)." ' IN BOOLEAN MODE) as coefficientcoins, if(`group`.`name` like '%".implode("%' or `group`.`name` like '%",$SearchTempStr)."%', 3,0) as coefficientgroup":"");

if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit)) {

	$CounterSQL .= ", if(".(sizeof($WhereThemesearch)?implode(" or ",$WhereThemesearch).", ".(sizeof($SearchTempDigit)?"if( shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,3,2)":"2").",".(sizeof($SearchTempDigit)?" if( shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,1.5,0)":"0"):" shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,1.5,0").") as counterthemeyear";
}

*/
$WhereArray =(sizeof($words)?"(shopcoins.details like '%".implode("%' or shopcoins.details like '%",$words)."%')":"").
(sizeof($numbers)?" or ($whereNumber) or ($whereNumber2)":"").
(sizeof($years)?" or ($whereYear)":"").
(sizeof($WhereThemesearch)?" or ".implode(" or ",$WhereThemesearch):""). 
(sizeof($WhereCountryes)?" or shopcoins.`group` in (".implode(",",$WhereCountryes).")":"");

if($result_temp_metal){    
    $WhereArray .=" or (shopcoins.metal_id in (".implode(",",array_keys($result_temp_metal))."))";
}
if($result_temp_condition){
    $WhereArray .=" or (shopcoins.condition_id in (".implode(",",array_keys($result_temp_condition))."))";
}

if($result_temp_name){
    $WhereArray .=" or (shopcoins.nominal_id in (".implode(",",array_keys($result_temp_name))."))";
}

$OrderByArray = Array();

/*
if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit))
	$OrderByArray[] = " (coefficientcoins+counterthemeyear+coefficientgroup+coefficientnominal) desc, counterthemeyear desc, (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc  ";
else
	$OrderByArray[] = " (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc ";*/

$OrderByArray[] = "(coefficientcoins+coefficientgroup+coefficientnominal+coefficientyear+coefficientmetal+coefficientcondition) desc, coefficientgroup desc, coefficientcoins desc ";

if (sizeof($OrderByArray))
	$orderby = "order by shopcoins.`check` asc,".implode(",",$OrderByArray);

$positive_amount = '';


$whereMaterialtype  = $materialtype?"and  shopcoins.materialtype=$materialtype or shopcoins.materialtypecross & pow(2,$materialtype)":'';

$where = " where shopcoins.check=1 $whereMaterialtype ".($WhereArray?" and ($WhereArray)":"");
//echo $where;

$sql = "select shopcoins.*, group.name as gname, group.groupparent ".($CounterSQL?",".$CounterSQL:"")." from shopcoins, `group` 
$where ".$positive_amount."and shopcoins.group=group.group  $orderby limit 5";


//echo $sql;
$data = $shopcoins_class->getDataSql($sql);

        

$tpl['shop']['errors'] = array();

$ArrayParent = Array();
$tpl['shop']['MyShowArray'] = Array();
$tpl['shop']['ArrayParent'] = Array();

foreach ($data as &$rows){
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
$data = array();
//var_dump($tpl['shop']['MyShowArray']);
foreach ($tpl['shop']['MyShowArray'] as &$row){
    $row['metal'] = $tpl['metalls'][$row['metal_id']];
	$rehref = "";
	if ($row['gname'])
		$rehref .= $row['gname']." ";
	$rehref .= $row['name'];
	if ($row['metal'])
		$rehref .= " ".$row['metal']; 
		
	$rehref .= " ".contentHelper::setYearText($row['year'],$row['materialtype']);
			
	$currval = array();
    $currval['label'] =  TRIM($rehref)?trim($rehref):$row['name'];
    $currval['image'] = contentHelper::showImage("smallimages/".$row["image_small"],'');
    $currval['id'] = $row['shopcoins'];   
    $data_href = contentHelper::getRegHref($row);
    $currval['href'] = 'shopcoins/'.$data_href['rehref'];
    array_push($data, $currval);
}
$currval = array();
$currval['label'] =  "Показать результаты поиска";
$currval['image'] = "";
$currval['id'] = 0;   
$currval['href'] = $cfg['site_dir']."shopcoins/index.php?search=".$search;
array_push($data, $currval);

echo json_encode($data);
die();
