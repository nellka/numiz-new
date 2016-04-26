<?
require($cfg['path'].'/helpers/Paginator.php');
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/shopcoinsdetails.php';
$details_class = new model_shopcoins_details($cfg['db']);
if($tpl['user']['user_id']==352480){
	echo time()." 1<br>";
}

$mycoins = 0;
$arraykeyword = array();

if(isset($_REQUEST['mycoins_php'])){
    $mycoins = 1;
} else $mycoins = request('mycoins');


$group = request('group');
$catalognewstr = request('catalognewstr');

$GroupNameMain = '';
$GroupName = ''; 
$metalTitle = '';

$materialtype = request('materialtype')?request('materialtype'):1;


if ($search == 'newcoins') {
    $shopcoins_class->setCategoryType(model_shopcoins::NEWCOINS);
} elseif ($search == 'revaluation') {
	$shopcoins_class->setCategoryType(model_shopcoins::REVALUATION);
} else {
    $shopcoins_class->setMaterialtype($materialtype);
    $shopcoins_class->setCategoryType(0);
	$arraykeyword[] = strip_tags($MaterialTypeArray[$materialtype]);
}


$tpl['shop']['errors'] = array();

$sortname = request('sortname');

$tpl['pager']['sorts'] = array('dateinsert'=>'новизне',                     
                      'price'=>'по цене',
                      'year'=>'году');
$tpl['pager']['itemsOnpage'] = array(9=>9,18=>18,36=>36,48=>48,72=>72);

//сохраняем количество элементов на странице в куке
if(request('onpage')){
    $tpl['onpage'] = request('onpage');
} elseif (isset($_COOKIE['onpage'])){
    $tpl['onpage'] =$_COOKIE['onpage'];
}	
if(!isset($tpl['onpage']))	$tpl['onpage'] = 18;

setcookie('onpage', $tpl['onpage'],  time()+ 86400 * 90,'/',$domain);

//сохраняем сортировку элементов на странице в куке
if(request('orderby')){
    $tpl['orderby'] = request('orderby');
} elseif (isset($_COOKIE['orderby'])){
    $tpl['orderby'] =$_COOKIE['orderby'];
}	
if(!isset($tpl['orderby']))	$tpl['orderby'] = "dateinsertdesc";
setcookie('orderby', $tpl['orderby'],  time()+ 86400 * 90,'/',$cfg['domain']);

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

//array(14) {["condition"]=> string(3) "VF-" ["orderby"]=> string(14) "dateinsertdesc" ["onpage"]=> string(2) "32" ["pagenum"]=> string(1) "1" ["pricestart"]=> string(4) "6709" ["priceend"]=> string(5) "71943" ["conditions"]=> array(2) { [0]=> string(3) "XF-" [1]=> string(3) "VF-" } ["metals"]=> array(1) { [0]=> string(12) "Фарфор" } ["years"]=> array(2) { [0]=> string(1) "1" [1]=> string(1) "2" } ["themes"]=> array(1) { [0]=> string(1) "4" } ["groups"]=> array(1) { [0]=> string(3) "518" } } 



$theme_data = array();
$metal_data = array();
$year_data = array();
$group_data = array();
$condition_data  = array(); 
$years_data  = array(); 
$years_p_data = array(); 
$nominal_data = array(); 
$series_data = array(); 

$pricestart =request('pricestart');
$priceend =request('priceend');
$searchid = request('searchid');



$groups = request('groups');
//убираем третий рейх
if($groups){
	$is_uncorrect = array_search(790,$groups);
	
	if($is_uncorrect!==false){
	//var_dump($groups,$is_uncorrect);
		unset($groups[$is_uncorrect]);
	}
}


$nominals = (array)request('nominals');
$nominal = request('nominal');
$group = request('group');
$seriess = request('seriess');

$series = request('series');
//на случай если парамет передали из прямой ссылки надо поддержать и такой формат


//если границы цены дефолтные то убираем их из выборки78
if($priceend<=0){
	$priceend='';
}

$yearstart  =request('yearstart');
$yearend  =request('yearend');

$theme  =request('theme');
$themes  =request('themes');
$metal  = iconv("cp1251",'utf8',request('metal'));
$metals  =request('metals');


$searchname = request('searchname');
$coinssearch = request('coinssearch');
$years  = (array)request('years');
$years_p = (array)request('years_p');

if(($nominals&&$groups)||($groups&&$materialtype==4)){
    $years_p_data = $years_p;
} else {
    
    if($yearend==date('Y',time())){
    	$yearend='';
    }
    
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
}

//это старые данные в виде строки
$condition = iconv("cp1251",'utf8',request('condition'));
//это новые данные в виде массива
$conditions = (array)request('conditions');


if ($metals) $metal_data =$metals;
elseif($metal) {
	$metal_data =  array($metal);
	$metals =  array($metal);
}

if ($groups)  $group_data =$groups;
elseif($group) {
	$group_data =  array($group);
	$groups =  array($group);
}

$tpl['shop']['OtherMaterialData'] = array();

$OtherMaterial =  array();

if(count($group_data)==1){
    
    $groupData = $shopcoins_class->getGroupItem($group_data[0]);
	
	$GroupName = $groupData["name"];
	//$grouphref = strtolower_ru($GroupName)."_gn".$groupData['group'];
	$arraykeyword[] = $groupData["name"];
		//var_dump(mb_detect_encoding($groupData["description"]));
	if (trim($groupData["description"])){
		$text = substr($groupData["description"], 0, 650);
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));

		$text = iconv( "CP1251//TRANSLIT//IGNORE","UTF8", $text);

		//UTF-8
		$pic = '';
		
		if ($groupData["flagsmall"]) {
		    
			$pic = "../group/smallimages/".$groupData["flagsmall"];
		} elseif ($groupData["emblemsmall"]) {
			$pic = "../group/smallimages/".$groupData["emblemsmall"];
		} elseif ($groupData["mapsmall"]){
			$pic = "../group/smallimages/".$groupData["mapsmall"];			
		}
				
		$tpl['GroupDescription'] = "<div class='left' style='padding: 0 10px 0 0;'>".contentHelper::showImage($pic,'')."</div>
			<div>".str_replace("\n","<br>",$text)."</div>";		
		unset ($text);
		unset ($pic);
	}
		
	if ($groupData["groupparent"] != 0 && $groupData["groupparent"] != $groupData["group"]) {	
	    $groupParentData = $shopcoins_class->getGroupItem($groupData["groupparent"]); 		
		$GroupNameMain = $groupParentData['name'];
	}	
	
	$tpl['shop']['OtherMaterialData'] = $shopcoins_class->getOtherMaterialData($group_data[0],$materialtype);	
	if ($tpl['shop']['OtherMaterialData']){		
	    $i = 0;	
	    $oldmaterialtype = 0;
		foreach ($tpl['shop']['OtherMaterialData'] as &$rows){
		    $rows['metal'] = $tpl['metalls'][$rows['metal_id']];
		    $rows['condition'] = $tpl['conditions'][$rows['condition_id']];
		    $tpl['shop']['related'][$i]['additional_title'] = '';
			if ($oldmaterialtype != $rows["materialtype"]) {
				$tpl['shop']['related'][$i]['additional_title'] = $MaterialTypeArray[$rows["materialtype"]];
				$oldmaterialtype = $rows["materialtype"];
			}
			$i++;
		}
	}
	
}

if(count($metal_data)==1){
    $metalTitle = $tpl['metalls'][$metal_data[0]];
}

if($groups){
	if ($nominals) $nominal_data =$nominals;
	elseif($nominal) {
		$nominal_data =  array($nominal);
		$nominals =  array($nominal);
	}
}

if ($conditions) $condition_data =$conditions;
elseif($condition) {
	$condition_data =  array($condition);
	$conditions =  array($condition);
}

if ($seriess)  $series_data = $seriess;
elseif($series) {
	$series_data =  array($series);
	$seriess =  array($series);
}

if ($themes) $theme_data =$themes;
elseif($theme) {
	$theme_data =  array($theme);
	$themes =  array($theme);
}
if($tpl['user']['user_id']==352480){
	echo time()." 3<br>";
}

if($mycoins) {
    $shopcoins_class->setMycoins($mycoins);
}


require($cfg['path'].'/controllers/filters.ctl.php');

if($tpl['user']['user_id']==352480){
	echo time()." 4<br>";
}

$tpl['shopcoins']['filter_groups'] = $filter_groups;
$checkuser = 0;
$CounterSQL = "";

$WhereParams = Array();

$page_string = "";

$ourcoinsorder = Array();
$ourcoinsorderamount = Array();

if($pricestart>0) $WhereParams['pricestart'] = $pricestart;
if($priceend>0) $WhereParams['priceend'] = $priceend;
if($theme_data) $WhereParams['theme'] = $theme_data;
if($metal_data) $WhereParams['metal'] = $metal_data;
if($years_data) $WhereParams['year'] = $years_data;
if($years_p_data) $WhereParams['year_p'] = $years_p_data;

if($condition_data) $WhereParams['condition'] = $condition_data;
if($group_data) $WhereParams['group'] = $group_data;
if($coinssearch) $WhereParams['coinssearch'] = $coinssearch;
if($nominal_data)  $WhereParams['nominals'] = $nominal_data;
if($series_data)  $WhereParams['series'] = $series_data;
if($catalognewstr) $WhereParams['catalognewstr'] = $catalognewstr;


if($searchname) {
    //так как ссылки были вида cp1251
    $WhereParams['searchname'] = str_replace("'","",iconv("cp1251",'utf8',$searchname));
}



$dateinsert_orderby = "dateinsert";

//end - потом не забыть подключить
$addhref = ($yearstart?"&yearstart=".$yearstart:"").
($catalognewstr?"&catalognewstr=$catalognewstr":"").
($mycoins?"&mycoins=$mycoins":"").
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
    $arraykeyword[] = urlencode($m);
}

foreach ((array)$groups as $g){
    $addhref .="&groups[]=$g";
}
foreach ((array)$years as $y){
    $addhref .="&years[]=$y";
}
foreach ((array)$years_p as $y){
    $addhref .="&years_p[]=$y";
}
foreach ((array)$themes as $th){
    $arraykeyword[] = $ThemeArray[$th];
    $addhref .="&themes[]=$th";
}

foreach ((array)$nominals as $th){
    $addhref .="&nominals[]=$th";
}


if ($searchid)
	$MainText .= "<p class=txt><b><font color=red>��������� ������������. �������� ������� ������������ ������.</font></b>
	<br>����� ��������� ����������� ����� - ������� <a href=$script>�����</a>.</p>";

if($tpl['user']['user_id']==352480){
	echo time()." 5<br>";
}

$countpubs = $shopcoins_class->countallByParams($WhereParams);
if($tpl['user']['user_id']==352480){
	echo time()." 6<br>";
}

if($addhref) $addhref = substr($addhref,1);  

setcookie("lhref", $cfg['site_dir']."shopcoins/index.php?".$addhref.(($tpl['pagenum']>1)?'&pagenum='.$tpl['pagenum']:''), time() + 3600, "/");

$tpl['paginator'] = new Paginator(array(
        'url'        => $cfg['site_dir']."shopcoins/index.php?".$addhref,
        'count'      => $countpubs,
        'per_page'   => ($tpl['onpage']=='all')?$countpubs:$tpl['onpage'],
        'page'       => $tpl['pagenum'],
        'border'     =>4));
    
/*if (!$page){  
	if($materialtype == 12){
		require_once('build_table/build_table.php');
		$MainText .= $table_data;
	}
}*/

$OrderByArray = Array();

/*if ($coinssearch)
	$OrderByArray[] = " shopcoins.shopcoins=".intval($coinssearch)." desc ";*/


//if ($group)	$OrderByArray[] = " ABS(shopcoins.group-".$group.") ";

if (($materialtype==3||$materialtype==5) and $group) $OrderByArray[] = " shopcoins.name desc";

if ($materialtype==5) $OrderByArray[] = " shopcoins.name desc";

$OrderByArray[] ="novelty desc";

if ($tpl['orderby']=="dateinsertdesc"){
	//$OrderByArray[] = " if (shopcoins.dateupdate > shopcoins.dateinsert, shopcoins.dateupdate, shopcoins.dateinsert) desc";
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

	
/*if ($wheresearch)
	$where = $wheresearch;

if ($shopkey) {

	$shopkey = str_replace("'","",$shopkey);	
	    $positive_amount = '';
		if($materialtype == 2)
		$positive_amount = ' shopcoins.amount > 0 and ';

		$sql = "select shopcoins.*, `group`.name as gname 
		from `shopcoins`, `group`, clientselectshopcoins
		WHERE ".$positive_amount."shopcoins.`group`=`group`.`group` AND (".($tpl['user']['user_id']==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and (shopcoins.materialtype = 1 or shopcoins.materialtypecross & pow(2,1)) and shopcoins.amountparent>0
		and shopcoins.shopcoins = clientselectshopcoins.shopcoins 
		and clientselectshopcoins.shopkey = '$shopkey' and (shopcoins.`dateinsert`>($timenow-14*24*60*60) or clientselectshopcoins.`dateselect`>1252440000) order by shopcoins.".$dateinsert_orderby." desc,shopcoins.price desc;";
		//echo $sql;
	//}
}
else*/if ($checkuser && $tpl['user']['user_id'] && ($num > 3) ) {

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
	/*$positive_amount = '';
	if($materialtype == 2)	$positive_amount = ' and shopcoins.amount > 0 ';

	$sql = "select shopcoins.*, group.name as gname, group.groupparent ".($CounterSQL?",".$CounterSQL:"")." ".($group>0&&!$page?$groupselect:"")."
	from shopcoins, `group` 
	$where ".$positive_amount."and shopcoins.group=group.group  
	".($group>0&&!$page?($sortname?" order by ".($coinssearch?"shopcoins.shopcoins=".intval($coinssearch)." desc,":"")." groupparent asc,param2,param1,".$dateinsert_orderby." desc":" order by ".($coinssearch?"shopcoins.shopcoins=".intval($coinssearch)." desc,":"")." groupparent asc,".$dateinsert_orderby." desc,price desc, param2,param1"):$orderby)." 
	$limit;";
	echo $sql;*/
	

	$data = $shopcoins_class->getItemsByParams($WhereParams,$tpl['pagenum'],$tpl['onpage'],$orderby);
	
}
if($tpl['user']['user_id']==352480){
	echo time()." 7<br>";
}


//$result_search = mysql_query($sql);
$ArrayParent = Array();
$tpl['shop']['MyShowArray'] = Array();
$tpl['shop']['ArrayParent'] = Array();
if($data){
	foreach ($data as $rows){
		$tpl['shop']['ArrayShopcoins'][] = $rows["shopcoins"];
		$tpl['shop']['ArrayParent'][] = $rows["parent"];
		$tpl['shop']['MyShowArray'][] = $rows;
	}
}
if($tpl['user']['user_id']==352480){
	echo time()." 8<br>";
}

if (sizeof($tpl['shop']['ArrayParent'])) {
    $result_search = $shopcoins_class->getCoinsParents($tpl['shop']['ArrayParent']);
	foreach ($result_search as $rows_search ){	
	    if($rows_search["shopcoins"]==$rows_search["parent"]) continue;
    	/*if($tpl['user']['user_id']==352480){
        	var_dump($rows_search["shopcoins"],$rows_search["parent"]);
        	echo "<br>";
        }*/
		$tpl['shop']['ImageParent'][$rows_search["parent"]][] = $rows_search["image_small"];
	}	
}
if($tpl['user']['user_id']==352480){
	echo time()." 9<br>";
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
if($tpl['user']['user_id']==352480){
	echo time()." 10<br>";
}

$ShopcoinsThemeArray = Array();
$ShopcoinsGroupArray = Array();

$tpl['task'] = 'catalog_base';	

if (sizeof($tpl['shop']['MyShowArray'])==0){
	$tpl['shop']['errors'][] = "<br><p class=txt><strong><font color=red>Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</font></strong><br><br>";
} else {	
	$amountsearch = count($tpl['shop']['MyShowArray']);	

	foreach ($tpl['shop']['MyShowArray'] as $i=>$rows) {

	    $tpl['shop']['MyShowArray'][$i]['condition'] = $tpl['conditions'][$rows['condition_id']];
	    $tpl['shop']['MyShowArray'][$i]['metal'] = $tpl['metalls'][$rows['metal_id']];
	    $details = $details_class->getItem($rows["shopcoins"]);
	    $tpl['shop']['MyShowArray'][$i]["details"] =  '';
	    if($details) $tpl['shop']['MyShowArray'][$i]["details"] = $details["details"];

		//формируем картинки "подобные"
		$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'] = array();
		
		if (($rows["materialtype"] ==1)&&isset($tpl['shop']['ImageParent'][$rows["parent"]])&&$tpl['shop']['ImageParent'][$rows["parent"]]>0 && !$mycoins) {	
			$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'][] =contentHelper::showImage("smallimages/".$rows["image_small"],"Монета ".$rows["gname"]." | ".$rows["name"]);
			$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'][] =contentHelper::showImage("smallimages/".$tpl['shop']['ImageParent'][$rows["parent"]][0],"Монета ".$rows["gname"]." | ".$rows["name"]);
		}


		$tpl['shop']['MyShowArray'][$i] = array_merge($tpl['shop']['MyShowArray'][$i], contentHelper::getRegHref($tpl['shop']['MyShowArray'][$i],$materialtype,$parent));
		
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

if($tpl['user']['user_id']==352480){
	echo time()." 8<br>";
}

$tpl['seo_data'] = $shopcoins_class->getSeo($materialtype,$group_data,$nominal_data);
//записываем статистике по тому, что искали
if ($search && $search != 'revaluation' && $search != 'newcoins'){		
    //$shopcoins_class->addSearchStatistic();	
}		
$tmp = explode("#", $LastCatalog10);
$k = 0;
$ids = array();
for ($i=0; $i<sizeof($tmp); $i++)
{
	$tmp1 = explode("|", $tmp[$i]);
	if($tmp1[0]){
	   $ids[] = $tmp1[0];
	}
	
}	//var_dump($ids);
$tpl['catalog']['lastViews'] = array();		
if($ids){
    $last_products = $shopcoins_class->getLastProducts($ids);
    $d = array();
    foreach ($last_products as &$row){
        $row['condition'] = $tpl['conditions'][$row['condition_id']];
	    $row['metal'] = $tpl['metalls'][$row['metal_id']];
        $d[$row['shopcoins']] =  array_merge($row, contentHelper::getRegHref($row));
        
    }
    $d_order = array();
    
    foreach ($ids as $id){
        if(isset($d[$id])) $d_order[] = $d[$id];
    }
  
    $tpl['catalog']['lastViews'] = $d_order;
    //foreach ()
   // var_dump($last_products);
}

/*
$tmp = explode("#", $LastCatalog10);
		$k = 0;
		for ($i=0; $i<sizeof($tmp); $i++)
		{
			$tmp1 = explode("|", $tmp[$i]);
			if ($tmp1[0])
			{
				if ($catalog != $tmp1[0])
				{
					
					$mtype = $tmp1[3];
			
					if ($mtype==1)
						$rehref = "Монета ";
					elseif ($mtype==8)
						$rehref = "Монета ";
					elseif ($mtype==7)
						$rehref = "Набор монет ";
					elseif ($mtype==2)
						$rehref = "Банкнота ";
					elseif ($mtype==4)
						$rehref = "Набор монет ";
					elseif ($mtype==5)
						$rehref = "Книга ";
					else 
						$rehref = "";
						
					if ($tmp1[1])
						$rehref .= $tmp1[1]." ";
					$rehref .= $rows['name'];
					if ($tmp1[4])
						$rehref .= " ".$tmp1[4]; 
					if ($tmp1[5])
						$rehref .= " ".$tmp1[5];
					if ($tmp1[6])
						$rehref .= " ".$tmp1[6];
						
					$rehref = strtolower_ru($rehref)."_c".$tmp1[0]."_m".$tmp1[3].".html";
					
					echo ($k!=0?"<tr><td colspan=4><hr class=divider size=1>":"<form action=# method=post>")."
					
					<tr>
					<td id=imagel$k><div id=lastcatalogis".$tmp1[0]."> ".($arraystatuslast[$tmp1[0]]==1?"<input type=checkbox id=shopcoinslast$k name=shopcoinslast$k checked=checked value=".$tmp1[0].">":"<input type=checkbox disabled=disabled id=shopcoinslast$k name=shopcoinslast$k value=0>")."</div></td>
					<td valign=top><div id=showl$k></div><img src=smallimages/".$tmp1[6]." width=80 border=1 style='border-color:black' alt='Монета ".$tmp1[1]." ".$tmp1[4]." стоимость ".intval($tmp1[5])." р.' onMouseOver=\"ShowMainCoins('l$k','<img src=images/".$tmp1[7]." border=1>','".htmlspecialchars($arraydetails[$tmp1[0]])."');\" onMouseOut=\"NotShowMainCoina('l$k');\"></td>
					<td width=2></td>
					<td valign=top><a href=index.php?page=show&group=".$tmp1[2]."&materialtype=".$tmp1[3]."&catalog=".$tmp1[0]." class=star title='Монета ".$tmp1[1]." ".$tmp1[4]." цена ".intval($tmp1[5])." р. найти'>".$tmp1[1]."<br>".$tmp1[4]."<br><font color=red>".intval($tmp1[5])." р.</font></a></td>
					</tr>
					";
					$k++;
				}
			}
		}
		
		echo "<tr bgcolor=#dddddd height=20><td colspan=4 align=center><img src=../images/corz1.gif border=0 style=\"cursor:pointer\" onclick=\"javascript:AddBascetLast();\" title=\"Положить все отмеченные монеты из списка в корзину\"></td></tr></table></form></td>
		</tr>
		</table>";
	}*/
if($tpl['user']['user_id']==352480){
	echo time()." 9<br>";
}



require $cfg['path'] . '/configs/shopcoins_keywords.php';


$arraykeyword[] = "монеты";
/*
if (sizeof($arraykeyword)){
	
    $keyword_texts = $shopcoins_class->keywordtexts($arraykeyword);    
	
	while ($rows = mysql_fetch_array($result)) {
	
		$text = substr(trim(strip_tags($rows['text'])),0,600);
		$strpos = strpos(strrev($text),".");
		echo "<p class=txt> &nbsp;&nbsp;&nbsp;<strong>".$rows['name']."</strong><br> &nbsp;&nbsp;&nbsp;".substr($text,0,600-($strpos<200?$strpos:0))."</p><br>";
	}

}*/

?>