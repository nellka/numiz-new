<?
header("Access-Control-Allow-Origin:*");
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/shopcoinsdetails.php';

$details_class = new model_shopcoins_details($cfg['db']);

$search = request('term');

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
    $WhereArray .=$WhereArray?" or (s.nominal_id in (".implode(",",array_keys($result_temp_name))."))":"(s.nominal_id in (".implode(",",array_keys($result_temp_name))."))";
}

$OrderByArray = Array();

/*
if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit))
	$OrderByArray[] = " (coefficientcoins+counterthemeyear+coefficientgroup+coefficientnominal) desc, counterthemeyear desc, (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc  ";
else
	$OrderByArray[] = " (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc ";*/

$OrderByArray[] = "(coefficientcoins+coefficientgroup+coefficientnominal+coefficientyear+coefficientmetal+coefficientcondition) desc, coefficientgroup desc, coefficientcoins desc ";

if (sizeof($OrderByArray))
	$orderby = "order by s.`check` asc,".implode(",",$OrderByArray);

$positive_amount = '';


/*$whereMaterialtype  = $materialtype?"and  shopcoins.materialtype=$materialtype or shopcoins.materialtypecross & pow(2,$materialtype)":'';*/
$whereMaterialtype  ='';
$where = " where s.check=1 $whereMaterialtype ".($WhereArray?" and ($WhereArray)":"");
//echo $where;

$sql = "select s.*, group.name as gname, group.groupparent ".($CounterSQL?",".$CounterSQL:"")." from shopcoins_search as s, `group` 
$where ".$positive_amount."and s.group=group.group $orderby limit 5";
//->where('s.group<>"790"')
if($tpl['user']['user_id']==352480){
 //echo $sql."<br><br>";
}

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
    $item = $shopcoins_class->getItem($row['shopcoins']);
    $row = array_merge($row,$item);
    
    $row['metal'] = $tpl['metalls'][$row['metal_id']];
	$rehref = "";
	if ($row['gname'])
		$rehref .= $row['gname']." ";
	$rehref .= $row['name'];
	if($row['year']) $rehref .= " ".contentHelper::setYearText($row['year'],$row['materialtype']);
	if ($row['metal'])
		$rehref .= " ".$row['metal']; 
		
	if($row['price']) $rehref .= " <font color=red>".ceil($row['price'])." руб.</font>";	
	
	$image = contentHelper::showImage("smallimages/".$row["image_small"],'');;	
	$currval = array();
    $currval['label'] =  TRIM($rehref)?trim($rehref):$row['name'];
    $currval['image'] = $image?$image:'';
    $currval['id'] = $row['shopcoins'];   
    $data_href = contentHelper::getRegHref($row);
    $currval['href'] = $cfg['site_dir'].'shopcoins/'.$data_href['rehref'];
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
