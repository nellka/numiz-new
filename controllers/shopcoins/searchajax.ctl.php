<?
//'http://numizmatik1.ru/new/shopcoins/moneta-rossiya-5-rublei-medno-nikeli-2014_c968356_m8.html'

require $cfg['path'] . '/configs/config_shopcoins.php';
$search = request('term');

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

if (sizeof($SearchTempStr)) {
	/*$sql_temp = "select distinct `group`.`name`, `group`.* from `group`, `shopcoins` 
				where ((".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") ".($show50?"or shopcoins.check=50":"").") and shopcoins.`group`=`group`.`group`
				and (".(sizeof($SearchTempStr)?"`group`.`name` like '%".implode("%' or `group`.`name` like '%",$SearchTempStr)."%')":"").";";
	//echo $sql_temp."<br>";
	$result_temp = mysql_query($sql_temp);
//		unset($WhereCountryes);
//		$WhereCountryes = Array();*/
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
/*
$sql_tmp = "select distinct `group`.`group` from `group`, shopcoins where (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and shopcoins.`group`=`group`.`group` and `group`.groupparent='".$rows_temp['group']."';";
			$result_tmp = mysql_query($sql_tmp);
			//echo $sql_tmp."<br>";
			while ($rows_tmp = mysql_fetch_array($result_tmp)) {
				$WhereCountryes[] = $rows_tmp['group'];
			}
*/

				
$CounterSQL = '';
$CounterSQL = (sizeof($SearchTempMatch)?" MATCH(shopcoins.`name`,shopcoins.details,shopcoins.metal,shopcoins.number,shopcoins.condition) AGAINST('".implode(" ",$SearchTempMatch)." ' IN BOOLEAN MODE) as coefficientcoins, if(`group`.`name` like '%".implode("%' or `group`.`name` like '%",$SearchTempStr)."%', 3,0) as coefficientgroup":"");

if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit)) {

	$CounterSQL .= ", if(".(sizeof($WhereThemesearch)?implode(" or ",$WhereThemesearch).", ".(sizeof($SearchTempDigit)?"if( shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,3,2)":"2").",".(sizeof($SearchTempDigit)?" if( shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,1.5,0)":"0"):" shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,1.5,0").") as counterthemeyear";
}

$WhereArray[] = " ( ".(sizeof($SearchTempStr)?"((shopcoins.details like '%".implode("%' or shopcoins.details like '%",$SearchTempStr)."%') and shopcoins.details<>'')":"")."
".(sizeof($SearchTempDigit)?"or ((shopcoins.details like '".implode("' or shopcoins.details like '",$SearchTempDigit)."') and shopcoins.details<>'')":"")."
".(sizeof($SearchTempStr)?"or shopcoins.number in ('".implode("','",$SearchTemp)."')":"")."
".(sizeof($SearchTempStr)?"or shopcoins.number2 in ('".implode("','",$SearchTemp)."')":"")."
".(sizeof($SearchTempDigit)?"or (shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0)":"")."
".(sizeof($SearchTempStr)?"or ((shopcoins.metal like '%".implode("%' or shopcoins.metal like '%",$SearchTempStr)."%') and shopcoins.metal<>'')":"")."
".(sizeof($SearchTempStr)?"or ((shopcoins.condition like '%".implode("%' or shopcoins.condition like '%",$SearchTempStr)."%') and shopcoins.condition<>'')":"")."
".(sizeof($SearchTempStr)?"or (shopcoins.`name` like '%".implode("%' or shopcoins.`name` like '%",$SearchTempStr)."%')":"")." 
".(sizeof($WhereThemesearch)?" or ".implode(" or ",$WhereThemesearch):"")." ".(sizeof($WhereCountryes)>0?" or shopcoins.`group` in (".implode(",",$WhereCountryes).")":"").")";


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


$sql = "select shopcoins.*, group.name as gname, group.groupparent ".($CounterSQL?",".$CounterSQL:"")." from shopcoins, `group` 
$where ".$positive_amount."and shopcoins.group=group.group  $orderby limit 5;";
		
$data = array();

foreach ($shopcoins_class->getDataSql($sql) as $row){

	/*if ($row['materialtype']==1) $rehref = "Монета ";
	if ($row['materialtype']==8) $rehref = "Монета ";
	if ($row['materialtype']==7) $rehref = "Набор монет ";
	if ($row['materialtype']==2) $rehref = "Банкнота ";
	if ($row['materialtype']==4) $rehref = "Набор монет ";
	if ($row['materialtype']==5) $rehref = "Книга ";
	if ($row['materialtype']==9)	$rehref = "Лот монет ";
	if ($row['materialtype']==10)	$rehref = "Нотгельд ";
	if ($row['materialtype']==11)	$rehref = "Монета ";	*/
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
    $currval['href'] = contentHelper::getRegHref($row);
    array_push($data, $currval);
}
//$data = $shopcoins_class->getItemsByParams(null,array(),$yearsearch,1,5,$orderby);



echo json_encode($data);
die();
/*
require($cfg['path'].'/helpers/Paginator.php');
require $cfg['path'] . '/configs/config_shopcoins.php';

$search = request('search');
$group = request('group');
$materialtype = request('materialtype')?request('materialtype'):1;


require($cfg['path'].'/controllers/filters.ctl.php');


$tpl['shop']['errors'] = array();

$sortname = request('sortname');

$tpl['pager']['sorts'] = array('dateinsert'=>'новизне',                     
                      'price'=>'по цене',
                      'year'=>'году');
$tpl['pager']['itemsOnpage'] = array(9=>9,16=>16,36=>36,33=>33,'all'=>'Все');

//сохраняем количество элементов на странице в куке
if(request('onpage')){
    $tpl['onpage'] = request('onpage');
} elseif (isset($_COOKIE['onpage'])){
    $tpl['onpage'] =$_COOKIE['onpage'];
}	
if(!isset($tpl['onpage']))	$tpl['onpage'] = 9;
setcookie('onpage', $tpl['onpage'],  time()+ 86400 * 90,'/',$cfg['domain']);

//сохраняем сортировку элементов на странице в куке
if(request('orderby')){
    $tpl['orderby'] = request('orderby');
} elseif (isset($_COOKIE['orderby'])){
    $tpl['orderby'] =$_COOKIE['orderby'];
}	
if(!isset($tpl['orderby']))	$tpl['orderby'] = "dateinsertdesc";
setcookie('orderby', $tpl['orderby'],  time()+ 86400 * 90,'/',$cfg['domain']);

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

$theme_data = array();
$metal_data = array();
$year_data = array();
$group_data = array();
$condition_data  = array(); 
$years_data  = array(); 

$pricestart =request('pricestart');
$priceend =request('priceend');
$searchid = request('searchid');
$yearsearch= request('yearsearch');
//если границы цены дефолтные то убираем их из выборки78
if($priceend==$tpl['filter']['price']['max']||$priceend<=0){
	$priceend='';
}

$yearstart  =request('yearstart');
$yearend  =request('yearend');

if($yearend==date('Y',time())){
	$yearend='';
}
	//var_dump($_REQUEST);
//var_dump($pricestart,$priceend);
$searchname = request('searchname');
$coinssearch = request('coinssearch');

$years  = (array)request('years');
krsort($years);

$i=0;
foreach ($years as $val){
   if($i>0){   
        //если совпадают концы интервалов
        if(($years_data[$i-1][1]+1)==$yearsArray[$val]['data'][0]) {           
             $years_data[$i-1][1]=$yearsArray[$val]['data'][1];
        } else {
            $years_data[] = $yearsArray[$val]['data'];
            $i++;
        }
   } else {
	   $years_data[] = $yearsArray[$val]['data'];
	   	$i++;
   }
}

//формируем массив интервалов
if($yearstart||$yearend){    
	$years_data[] = array($yearstart,$yearend);
}

$theme  =request('theme');
$themes  =request('theme');
$metal  = iconv("cp1251",'utf8',request('metal'));
$metals  =request('metals');
$groups = request('groups');

//это старые данные в виде строки
$condition = iconv("cp1251",'utf8',request('condition'));
//это новые данные в виде массива
$conditions = (array)request('conditions');
//на случай если парамет передали из прямой ссылки надо поддержать и такой формат
if ($groups) $group_data =$groups;
elseif($group) $group_data =  array($group);

if ($metals) $metal_data =$metals;
elseif($metal) $metal_data =  array($metal);

if ($conditions) $condition_data =$conditions;
elseif($condition) $condition_data =  array($condition);

if ($themes) $theme_data =$themes;
elseif($theme) $theme_data =  array($theme);
if ($themes) $theme_data =$themes;
elseif($theme) $theme_data =  array($theme);

$checkuser = 0;
$CounterSQL = "";

$WhereParams = Array();

$page_string = "";

$mycoins = 0;
$ourcoinsorder = Array();
$ourcoinsorderamount = Array();

if($pricestart>0) $WhereParams['pricestart'] = $pricestart;
if($priceend>0) $WhereParams['priceend'] = $priceend;
if($theme_data) $WhereParams['theme'] = $theme_data;
if($metal_data) $WhereParams['metal'] = $metal_data;
if($years_data) $WhereParams['year'] = $years_data;
if($condition_data) $WhereParams['condition'] = $condition_data;
if($group_data) $WhereParams['group'] = $group_data;
if($coinssearch) $WhereParams['coinssearch'] = $coinssearch;

if($searchname) {
    //так как ссылки были вида cp1251
    $WhereParams['searchname'] = str_replace("'","",iconv("cp1251",'utf8',$searchname));
}

$dateinsert_orderby = "dateinsert";

//end - потом не забыть подключить
$addhref = ($yearstart?"&yearstart=".$yearstart:"").
($yearend?"&yearend=".$yearend:"").
($metal?"&metal=".urlencode($metal):"").
($search?"&search=".urlencode($search):"").
($pricestart?"&pricestart=".$pricestart:"").
($priceend?"&priceend=".$priceend:"").
($searchid?"&searchid=".$searchid:"").
($group?"&group=$group":"").
($materialtype?"&materialtype=$materialtype":"").
($theme?"&theme=".$theme:"").
($condition?"&condition=".$condition:"").
($nocheck?"&nocheck=".$nocheck:"")
.($searchname?"&searchname=".urlencode($searchname):"");

foreach ((array)$conditions as $c){
    $addhref .="&conditions[]=".urlencode($c);
}
foreach ((array)$metals as $m){
    $addhref .="&metals[]=".urlencode($m);
}
foreach ((array)$groups as $g){
    $addhref .="&groups[]=$g";
}
foreach ((array)$years as $y){
    $addhref .="&years[]=$y";
}
foreach ((array)$themes as $th){
    $addhref .="&themes[]=$th";
}


if ($searchid)
	$MainText .= "<p class=txt><b><font color=red>��������� ������������. �������� ������� ������������ ������.</font></b>
	<br>����� ��������� ����������� ����� - ������� <a href=$script>�����</a>.</p>";


if ($search == 'newcoins') {
	$WhereParams['newcoins'] = true;
} elseif ($search == 'revaluation') {
	$WhereParams['revaluation'] = true;
}
	
$countpubs = $shopcoins_class->countallByParams($materialtype,$WhereParams,$yearsearch);

if($addhref) $addhref = substr($addhref,1);  
$tpl['paginator'] = new Paginator(array(
        'url'        => $cfg['site_dir']."shopcoins/index.php?".$addhref,
        'count'      => $countpubs,
        'per_page'   => ($tpl['onpage']=='all')?$countpubs:$tpl['onpage'],
        'page'       => $tpl['pagenum'],
        'border'     =>4));
    

$OrderByArray = Array();

if (isset($CounterSQL)&&$CounterSQL)
	if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit))
		$OrderByArray[] = " (coefficientcoins+counterthemeyear+coefficientgroup) desc, counterthemeyear desc, (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc  ";
	else
		$OrderByArray[] = " (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc ";




//if ($group)	$OrderByArray[] = " ABS(shopcoins.group-".$group.") ";

if (($materialtype==3||$materialtype==5) and $group) $OrderByArray[] = " shopcoins.name desc";

if ($materialtype==5) $OrderByArray[] = " shopcoins.name desc";

	
if ($tpl['orderby']=="dateinsertdesc"){
	$OrderByArray[] = " if (shopcoins.dateupdate > shopcoins.dateinsert, shopcoins.dateupdate, shopcoins.dateinsert) desc";
	$OrderByArray[] = "shopcoins.dateinsert desc";
	$OrderByArray[] = "shopcoins.price desc";
} elseif ($tpl['orderby']=="dateinsertasc"){
	$OrderByArray[] = " shopcoins.dateinsert asc";
	$OrderByArray[] = "shopcoins.price desc";
} elseif ($tpl['orderby']=="priceasc"){
	$OrderByArray[] = "shopcoins.price asc";
	$OrderByArray[] = "shopcoins.dateinsert desc ";
} elseif ($tpl['orderby']=="pricedesc"){
	$OrderByArray[] = "shopcoins.price desc";
	$OrderByArray[] = "shopcoins.dateinsert desc";
} elseif ($tpl['orderby']=="yearasc"){
	$OrderByArray[] = "shopcoins.year asc";
	$OrderByArray[] ="shopcoins.dateinsert desc";
} elseif ($tpl['orderby']=="yeardesc"){
	$OrderByArray[] = "shopcoins.year desc";
	$OrderByArray[] = "shopcoins.dateinsert desc ";
} elseif($materialtype==12){
	$OrderByArray[] = "shopcoins.year desc";
	$OrderByArray[] = "shopcoins.name desc ";
} 

if ($materialtype==1||$materialtype==2||$materialtype==10||$materialtype==4||$materialtype==7||$materialtype==8||$materialtype==6||$materialtype==11||$search=='newcoins'){
	$OrderByArray[] = $dateinsert_orderby." desc";
	$OrderByArray[] = "shopcoins.price desc";
}

if ($search === 'revaluation') {
	$OrderByArray[] = "shopcoins.datereprice desc";
	$OrderByArray[] = "shopcoins.price desc";
	$OrderByArray[] = "shopcoins.".$dateinsert_orderby." desc";
}

if (sizeof($OrderByArray)){
	$orderby = array_merge(array("shopcoins.check ASC"),$OrderByArray);
}

if ($checkuser && $tpl['user']['user_id'] && ($num > 3) ) {

	if ($num > 50) $num=50;

		$positive_amount = '';
	if($materialtype == 2)
	$positive_amount = ' shopcoins.amount > 0 and ';
	
	$sql = "select shopcoins.*, `group`.`name` as gname 
		from `shopcoins`, `group`, `clientselectshopcoins` 
		where ".$positive_amount."shopcoins.`group`=`group`.`group` AND (".($tpl['user']['user_id']==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").")  and (shopcoins.materialtype = 1 or shopcoins.materialtypecross & pow(2,1)) and shopcoins.amountparent>0
		and shopcoins.`shopcoins` = clientselectshopcoins.`shopcoins` 
		and clientselectshopcoins.`user` = '".$tpl['user']['user_id']."' and clientselectshopcoins.`dateselect` >= '$dateselect' order by shopcoins.".$dateinsert_orderby." desc,shopcoins.price desc limit 0,$num;";
} elseif ($page == "recommendation" && $tpl['user']['user_id']) {

			$positive_amount = '';
	if($materialtype == 2)
	$positive_amount = ' shopcoins.amount > 0 and ';

	$sql = "select shopcoins.*, `group`.`name` as gname 
		from `shopcoins`, `group`, `clientselectshopcoins` 
		where ".$positive_amount."shopcoins.`group`=`group`.`group` AND (".($tpl['user']['user_id']==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and shopcoins.`shopcoins` = clientselectshopcoins.`shopcoins` 
		and clientselectshopcoins.`user` = '{$tpl['user']['user_id']}' 
		and shopcoins.shopcoins not in(".(sizeof($arraycatalpgshopcoins)?implode(",",$arraycatalpgshopcoins):"1").")
		order by shopcoins.".$dateinsert_orderby." desc, shopcoins.price desc limit ".($pagenum-1)*$onpage.", $onpage;";

} else {
	
	$data = $shopcoins_class->getItemsByParams($materialtype,$WhereParams,$yearsearch,$tpl['pagenum'],$tpl['onpage'],$orderby);
	
}


//$result_search = mysql_query($sql);
$ArrayParent = Array();
$tpl['shop']['MyShowArray'] = Array();
$tpl['shop']['ArrayParent'] = Array();
foreach ($data as $rows){
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

if ($materialtype==3 || $materialtype==5) {
	if ($shopcoinsorder > 1) {
		$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);	
		foreach ( $result_ourorder as $rows_ourorder){
			$ourcoinsorder[] = $rows_ourorder["catalog"];
			$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
		}
	}
}

$ShopcoinsThemeArray = Array();
$ShopcoinsGroupArray = Array();

$tpl['task'] = 'catalog_base';	

if (sizeof($tpl['shop']['MyShowArray'])==0){
	$tpl['shop']['errors'][] = "<br><p class=txt><strong><font color=red>Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</font></strong><br><br>";
} else {	
	$amountsearch = count($tpl['shop']['MyShowArray']);	
	
	foreach ($tpl['shop']['MyShowArray'] as $i=>$rows) {
	    
		//формируем картинки "подобные"
		$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'] = array();
		if (isset($tpl['shop']['ImageParent'][$rows["parent"]])&&$tpl['shop']['ImageParent'][$rows["parent"]]>0 && !$mycoins) {	
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
    $shopcoins_class->addSearchStatistic();
	
}		
*/


?>