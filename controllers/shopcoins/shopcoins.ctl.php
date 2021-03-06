<?php
 
$tpl['current_page'] = '';
    
require_once($cfg['path'].'/helpers/Paginator.php');
require_once($cfg['path'].'/helpers/urlBuilder.php');
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/stats.php';

/*              
    'yearstart' => '',
    'yearend' => '',
    'mycoins' => '',
   // 'searchid' => '',?
   // 'searchname' => '',?
   // 'catalognewstr'=>'',?
    '' => '',
    'priceend' => '',*/
    	
$stats_class = new stats($db_class,$tpl['user']['user_id'],session_id());

$correct_url = urlBuild::correctedUrl($_SERVER["REQUEST_URI"],$tpl['user']['user_id']);
 
if($correct_url!=$_SERVER["REQUEST_URI"]){
     header("HTTP/1.1 301 Moved Permanently"); 
     header("location: http://www.numizmatik.ru".$correct_url);
     die();
}   

$data_filter = array();
$urlParams = array();

$materialtype = request('materialtype')?(int)request('materialtype'):1;

$tpl['show_short'] = false;
$tpl['show_short_button'] = false;

if(in_array($_SERVER['REMOTE_ADDR'],$admin_ips)&&$tpl['user']['user_id']){
	$tpl['show_short_button'] = true;
	//var_dump($_COOKIE['sshort']);
	$tpl['show_short'] = (isset($_COOKIE['sshort'])&&$_COOKIE['sshort'])?true:false;
}
if($tpl['show_short']){
    $shopcoins_class->setShortShow(1);
}

if(isset($materialsRule[request('materialtype')])) $materialtype = $materialsRule[request('materialtype')];
require_once $cfg['path'] . '/models/shopcoinsdetails.php';

$details_class = new model_shopcoins_details($db_class);

$mycoins = 0;
$arraykeyword = array();
//до конца недели
$bydate = 0;
//$bydate = $tpl['user']['user_id']?(int)request('bydate'):0;

if(!isset($byDates[$bydate])) $bydate = 0;
$urlParams['bydate'] = $bydate;

$nocheck = request('nocheck');
$urlParams['nocheck'] = $nocheck;


if(isset($_REQUEST['mycoins_php'])){
    $mycoins = 1;
} else $mycoins = request('mycoins');

$urlParams['mycoins'] = $mycoins;

if($mycoins&&!$tpl['user']['user_id']){
    header("location: http://www.numizmatik.ru/shopcoins");
    die();
}
$group = (int)request('group');

$catalognewstr = request('catalognewstr');
$urlParams['catalognewstr'] = $catalognewstr;

$GroupNameMain = '';
$GroupNameID = '';
$GroupName = ''; 
$metalTitle = '';


if(contentHelper::get_encoding($search)=='windows-1251'){
	$search = iconv( "CP1251//TRANSLIT//IGNORE","UTF8", $search);
}

if ($search == 'newcoins'||$materialtype=='newcoins') {
    $shopcoins_class->setCategoryType(model_shopcoins::NEWCOINS);
    $materialtype='newcoins';
} elseif ($search == 'revaluation'||$materialtype == 'revaluation') {
    $materialtype='revaluation';
	$shopcoins_class->setCategoryType(model_shopcoins::REVALUATION);
} else {
    $shopcoins_class->setMaterialtype($materialtype);
    $shopcoins_class->setCategoryType(0);
	$arraykeyword[] = strip_tags($MaterialTypeArray[$materialtype]);
}

$urlParams['materialtype'] = $materialtype;

$r_url='';

if($_SERVER["REDIRECT_URL"]=='/shopcoins/prodaza_banknot_i_bon.html'){
   $r_url=$cfg['site_dir'].'shopcoins/banknoti';
   $data_filter['materialtype'] = 'banknoti';
} else {
    if($materialtype!=1){
    	$r_url = $cfg['site_dir'].'shopcoins/'.$materialIDsRule[$materialtype];
    } else $r_url = $cfg['site_dir'].'shopcoins';   
    
    
    $data_filter['materialtype'] = $materialtype;
    
    if($search) $r_url .= '/'.$search;
    $r_url = str_replace('shopcoins//','shopcoins/',$r_url);
}

if($materialtype!=1){
	$tpl['breadcrumbs'][] = array(
	    	'text' => 'Магазин монет',
	    	'href' => $cfg['site_dir'].'shopcoins',
	    	'base_href' =>'shopcoins'
);
}    

    
if($materialtype=='newcoins'){
   $tpl['breadcrumbs'][] = array(
    	'text' => "Новинки",
    	'href' => $r_url,
    	'base_href' =>$r_url    );
} elseif($materialtype=='revaluation'){
    $tpl['breadcrumbs'][] = array(
    	'text' => "Распродажа монет",
    	'href' => $r_url,
    	'base_href' =>$r_url    );
} else {
    $tpl['breadcrumbs'][] = array(
    	'text' => contentHelper::$menu[$materialtype],
    	'href' => $r_url,
    	'base_href' =>$r_url    );
}

$H1 = "Магазин ".contentHelper::$h1_materialtype[$materialtype];
$H1_sub = "";

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

setcookie('onpage', $tpl['onpage'],  time()+ 86400 * 90,'/');

//сохраняем сортировку элементов на странице в куке
if(request('orderby')){
    $tpl['orderby'] = request('orderby');
} elseif (isset($_COOKIE['orderby'])){
    $tpl['orderby'] =$_COOKIE['orderby'];
}

if(!isset($tpl['orderby']))	$tpl['orderby'] = "dateinsertdesc";
setcookie('orderby', $tpl['orderby'],  time()+ 86400 * 90,'/');

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

$tpl['breadcrumbsMini'] = array();
    
$theme_data = array();
$price_data  = array();
$metal_data = array();
$year_data = array();
$group_data = array();
$condition_data  = array(); 
$years_data  = array(); 
$years_p_data = array(); 
$nominal_data = array(); 

$pricestart = $urlParams['pricestart'] = request('pricestart');
$priceend = $urlParams['priceend'] = request('priceend');

$groups = (array)request('groups');



foreach ($groups as $k=>$v){
    $groups[$k] = (int)$v;
}

$nominals = (array)request('nominals');
$nominal = request('nominal');

//если границы цены дефолтные то убираем их из выборки78
if($priceend<=0){
	$priceend='';
}

$yearstart  = $urlParams['yearstart'] = request('yearstart');
$yearend  = $urlParams['yearend'] = request('yearend');


$theme  =request('theme');
$themes  =request('themes');
$metal  = iconv("cp1251",'utf8',request('metal'));
$metals  =request('metals');


$searchname = request('searchname');
$coinssearch = request('coinssearch');
$years  = (array)request('years');
$years_p = (array)request('years_p');

if($years_p&&!is_array($years_p)){
    $years_p = array($years_p);
}

if($materialtype==2){
    $yearsArray[3] = array('name' => 'до 1900','data'=>array(0,1900));
}

//это старые данные в виде строки
$condition = iconv("cp1251",'utf8',request('condition'));
//это новые данные в виде массива
$conditions = (array)request('conditions');

if($metals&&!is_array($metals)){
    $metals = array($metals);
}

$price = request('price');
$prices = request('prices');

if($prices) {
	$price_data = $prices;	
} elseif($price){
	$prices = $price_data = array($price);	
}

$urlParams['price'] = $price_data;

if ($metals) $metal_data = $metals;
elseif($metal) {	
	if((int)$metal==0){
		//для старых запросов
		$metal = array_search($metal,$tpl['metalls']);
	}
	
	if($metal){
		$metal_data =  array($metal);
		$metals =  array($metal);
	}
}


if ($groups)  $group_data =$groups;
elseif($group) {
	$group_data =  array($group);
	$groups =  array($group);
}


if ($groups)  $group_data =$groups;
elseif($group) {
	$group_data =  array($group);
	$groups =  array($group);
}

//if($groups){
if ($nominals) $nominal_data =$nominals;
elseif($nominal) {
	$nominal_data =  array($nominal);
	$nominals =  array($nominal);
}
//}

if($materialtype=='newcoins'){
	$years_data = $years;
} elseif(($nominals&&$groups)||($groups&&$materialtype==4)){
    $years_data = $years;
} else {
    
    if($yearend==date('Y',time())){
    	$yearend='';
    }
    
    krsort($years_p);
    
    $i=0;
    foreach ($years_p as $key=>$val){  
       if(!isset($yearsArray[$val])) {
       	  unset($years_p[$key]);
       	  continue;     
       }
       
       if($i>0){   
            //если совпадают концы интервалов
            if(($years_p_data[$i-1][1]+1)==$yearsArray[$val]['data'][0]) {           
                 $years_p_data[$i-1][1]=$yearsArray[$val]['data'][1];
            } else {
                $years_p_data[] = $yearsArray[$val]['data'];
                $i++;
            }
       } else {
    	   $years_p_data[] = $yearsArray[$val]['data'];
    	   	$i++;
       }

    }    
    //формируем массив интервалов
    if($yearstart||$yearend){    
    	$years_p_data[] = array($yearstart,$yearend);
    }
}

$tpl['shop']['OtherMaterialData'] = array();

$OtherMaterial =  array();

$groupHref = "";
$groupMain = 0;

if(count($group_data)==1){
    $groupMain = $GroupNameID = $group_data[0];
    $data_filter['group_id'] = $groupMain;
    
    $groupData = $shopcoins_class->getGroupItem($group_data[0]);
	//получаем дочерние элементы    
	$childs = $shopcoins_class->getParrentGroupsIds($group_data[0]);

	$i=1;
	foreach ($childs as $child){
	    $group_data[$i] = $child;
	    $groups[$i] = $child;
	    $i++;
	}	
		
	$GroupName = $groupData["name"];
	
	$H1_sub .=" $GroupName";
	
	$urlParams['group'] = array($groupMain=>$GroupName);
	 
	$tpl['breadcrumbs'][] = array(
        	'text' => $GroupName,
        	'href' => $r_url.contentHelper::groupUrl($GroupName,$groupMain),
        	'base_href' =>$r_url.contentHelper::groupUrl($GroupName,$groupMain)
    );
        
	$groupHref = contentHelper::groupUrl($GroupName,$groupMain);
	
	//$grouphref = strtolower_ru($GroupName)."_gn".$groupData['group'];
	$arraykeyword[] = $groupData["name"];

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
		    $rows['metal'] = isset($tpl['metalls'][$rows['metal_id']])?$tpl['metalls'][$rows['metal_id']]:'';
		    $rows['condition'] = isset($tpl['conditions'][$rows['condition_id']])?$tpl['conditions'][$rows['condition_id']]:'';
		    $tpl['shop']['related'][$i]['additional_title'] = '';
			if ($oldmaterialtype != $rows["materialtype"]) {
				$tpl['shop']['related'][$i]['additional_title'] = $MaterialTypeArray[$rows["materialtype"]];
				$oldmaterialtype = $rows["materialtype"];
			}
			$i++;
		}
	}	
} else $urlParams['group'] = $group_data;

if ($conditions) $condition_data =$conditions;
elseif($condition) {
    if((int)$condition==0){
		//для старых запросов
		$condition = array_search($condition,$tpl['conditions']);
	}
	
	if($condition){
		$condition_data =  array($condition);
	   $conditions =  array($condition);
	}	
}

if ($themes) $theme_data =$themes;
elseif($theme) {
	$theme_data =  array($theme);
	$themes =  array($theme);
}

if($mycoins) {
    $shopcoins_class->setMycoins($mycoins);
}

require($cfg['path'].'/controllers/filters.ctl.php');


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
if($price_data) $WhereParams['price'] = $price_data;


if($condition_data) $WhereParams['condition'] = $condition_data;
if($group_data) $WhereParams['group'] = $group_data;
if($coinssearch) $WhereParams['coinssearch'] = $coinssearch;
if($nominal_data)  $WhereParams['nominals'] = $nominal_data;


if($catalognewstr) $WhereParams['catalognewstr'] = $catalognewstr;
if($bydate&&$tpl['user']['user_id']) $WhereParams['bydate'] = $bydate;

if(contentHelper::get_encoding($searchname)=='windows-1251'){
	$searchname = iconv( "CP1251//TRANSLIT//IGNORE","UTF8", $searchname);	
}

if($searchname) {
    //так как ссылки были вида cp1251
    $WhereParams['searchname'] = str_replace("'","",$searchname);
}

$dateinsert_orderby = "dateinsert";

/*$r_url_paginator = $r_url;

//end - потом не забыть подключить

$addhref = ($yearstart?"&yearstart=".$yearstart:"").
($catalognewstr?"&catalognewstr=$catalognewstr":"").
($mycoins?"&mycoins=$mycoins":"").
($yearend?"&yearend=".$yearend:"").
($pricestart?"&pricestart=".$pricestart:"").
($priceend?"&priceend=".$priceend:"").
($nocheck?"&nocheck=".$nocheck:"").
($bydate?"&bydate=".$bydate:"")
.($searchname?"&searchname=".urlencode($searchname):"");


if($groupMain){
    $r_url_paginator .= $groupHref;
} else {
    foreach ((array)$groups as $g){
        $addhref .="&groups[]=$g";
    }
}*/

/*$urlParams['years_p'] = $years_p_data;
$urlParams['years'] = $years_data;*/

$nominalMain = 0;
$nominalMainTitle = '';

if(count($nominal_data)==1&&$nominal_data[0]){
    $nominalMain = $nominal_data[0];
    $data_filter['nominal_id'] = $nominalMain;
    $nominalMainTitle = $shopcoins_class->getNominal($nominal_data[0]);
    
    $H1_sub .=" $nominalMainTitle";
    
    $urlParams['nominal'] = array($nominalMain=>$nominalMainTitle); 
    
    $tpl['breadcrumbs'][] = array(
    	'text' => $nominalMainTitle,
    	'href' => $r_url.($groupHref?$groupHref:'').contentHelper::nominalUrl($nominalMainTitle,$nominalMain),
    	'base_href' =>$r_url.($groupHref?$groupHref:'').contentHelper::nominalUrl($nominalMainTitle,$nominalMain)
    );
   // $r_url_paginator .= contentHelper::nominalUrl($nominalMainTitle,$nominalMain);
    
} else{    
    /*foreach ((array)$nominals as $th){
        $addhref .="&nominals[]=$th";
    }*/
    
    $urlParams['nominal'] = $nominal_data;
}


if(count($years)==1&&$years[0]){    
    $data_filter['years'] = $years;
    $urlParams['years'] = $years;
    $tpl['breadcrumbs'][] = array(
    	'text' => $years[0],
    	'href' => $r_url.($groupHref?$groupHref:'').'/years_'.$years[0],
    	'base_href' =>$r_url.($groupHref?$groupHref:'').'/years_'.$years[0]
    );
    //$r_url_paginator .= '/y_ysp'.$years_p[0];
    $H1_sub .=" $years[0]";
} else {
    foreach ((array)$years as $y){
       // $addhref .="&years_p[]=$y";
        $urlParams['years'][] = $y;
    }
}

if(count($years_p)==1&&$years_p[0]){    
    $data_filter['years_p'] = $years_p;
    $urlParams['years_p'] = $years_p;
    $tpl['breadcrumbs'][] = array(
    	'text' => $yearsArray[$years_p[0]]['name'],
    	'href' => $r_url.($groupHref?$groupHref:'').'/y_ysp'.$years_p[0],
    	'base_href' =>$r_url.($groupHref?$groupHref:'').'/y_ysp'.$years_p[0]
    );
    //$r_url_paginator .= '/y_ysp'.$years_p[0];
    $H1_sub .=" ".$yearsArray[$years_p[0]]['name'];
} else {
    foreach ((array)$years_p as $y){
       // $addhref .="&years_p[]=$y";
        $urlParams['years_p'][] = $y;
    }
}

$metalMain = 0;
$metalMainTitle = '';

if(count($metal_data)==1){
    $metalMain = $metal_data[0];
    $data_filter['metal_id'] = $metalMain;
    $metalMainTitle = $tpl['metalls'][$metalMain];
    $H1_sub .=" $metalMainTitle";
    $urlParams['metal'] = array($metalMain=>$metalMainTitle); 
    
    $tpl['breadcrumbs'][] = array(
    	'text' => $metalMainTitle,
    	'href' => $r_url.($groupHref?$groupHref:'').contentHelper::metalUrl($metalMainTitle,$metalMain),
    	'base_href' =>$r_url.($groupHref?$groupHref:'').contentHelper::metalUrl($metalMainTitle,$metalMain)
    );
    
    //$r_url_paginator .= contentHelper::metalUrl($metalMainTitle,$metalMain);
    
} else {
    foreach ((array)$metals as $m){
       // $addhref .="&metals[]=".urlencode($m);
        $arraykeyword[] = urlencode($m);
    }
    
    $urlParams['metal'] = $metal_data;
}


$themeMain = 0;
$themeMainTitle = '';

if(count($theme_data)==1){
    $themeMain = $theme_data[0]; 
    $data_filter['theme_id'] = $themeMain;   
    $themeMainTitle = $ThemeArray[$theme_data[0]];  
    $H1_sub .=" $themeMainTitle";
    $urlParams['theme'] = array($themeMain=>$themeMainTitle); 
     
   // $r_url_paginator .= contentHelper::themeUrl($themeMainTitle,$themeMain);    
} else {
    foreach ((array)$themes as $th){
        $arraykeyword[] = $ThemeArray[$th];
       // $addhref .="&themes[]=$th";
    }
    
    $urlParams['theme'] = $theme_data;
}

$conditionMain = 0;
$conditionMainTitle = '';

if(count($condition_data)==1){
    $conditionMain = $condition_data[0]; 
    $data_filter['condition_id'] = $conditionMain;   
    $conditionMainTitle = $tpl['conditions'][$conditionMain]; 
    $H1_sub .=" $conditionMainTitle";  
   // $r_url_paginator .= contentHelper::conditionUrl($conditionMainTitle,$conditionMain);       
    $urlParams['condition'] = array($conditionMain=>$conditionMainTitle);
     
} else {    
    foreach ((array)$conditions as $c){
        //$addhref .="&conditions[]=".urlencode($c);
    }
    
    $urlParams['condition'] = $condition_data;
}

if($tpl['pagenum']>1) $urlParams['pagenum']=$tpl['pagenum'] ;

$countpubs = $shopcoins_class->countallByParams($WhereParams);

//if($tpl['user']['user_id']==352480){   
	
	$correct_url = urlBuild::makePrettyUrl($urlParams);
	if($tpl['user']['user_id']==352480){  
	
	}

	//die();
	//var_dump($correct_url,"http://www.numizmatik.ru".$_SERVER["REQUEST_URI"]);
	if(($tpl['datatype']!='text_html')&&($correct_url!="http://www.numizmatik.ru".$_SERVER["REQUEST_URI"])){
	     header("HTTP/1.1 301 Moved Permanently"); 
	     header("location: ".$correct_url);
	     die();
	}  
	
//}

//if($addhref) $addhref = substr($addhref,1); 
 
$data_filter_old = urlBuild::parseUrl($_COOKIE['lhref'],$materialsRule);

if($tpl['pagenum']==1&&(count(array_diff($data_filter,$data_filter_old))||count(array_diff($data_filter_old,$data_filter)))){
    $stats_class->saveFilter($data_filter);
    
}
unset($urlParams['pagenum']);
$correct_url_for_paginator = urlBuild::makePrettyUrl($urlParams);

$tpl['paginator'] = new Paginator(array(
       // 'url'        => $r_url_paginator.($addhref?("?".$addhref):""),
        'url'        => $correct_url_for_paginator,
        'count'      => $countpubs,
        'per_page'   => ($tpl['onpage']=='all')?$countpubs:$tpl['onpage'],
        'page'       => $tpl['pagenum'],
        'border'     =>3));


     
      
//setcookie("lhref", $r_url_paginator."/?".$addhref.(($tpl['pagenum']>1)?'&pagenum='.$tpl['pagenum']:''), time() + 3600, "/");  
setcookie("lhref", $correct_url, time() + 3600, "/");  
/*if (!$page){  
	if($materialtype == 12){
		require_once('build_table/build_table.php');
		$MainText .= $table_data;
	}
}*/

$OrderByArray = Array();
$OrderByArray[] ="novelty desc";
/*if ($coinssearch)
	$OrderByArray[] = " shopcoins.shopcoins=".intval($coinssearch)." desc ";*/


//if ($group)	$OrderByArray[] = " ABS(shopcoins.group-".$group.") ";

if (($materialtype==3||$materialtype==5) and $group) $OrderByArray[] = " shopcoins.name desc";

if ($materialtype==5) $OrderByArray[] = " shopcoins.name desc";

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

//получаем уже просмотренные новинки
$viewed_novelty = $stats_class->getViewdNovelty(10);

$WhereParams['viewed_novelty'] = $viewed_novelty;


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
    $data = $shopcoins_class->getItemsByParams($WhereParams,$tpl['pagenum'],$tpl['onpage'],$orderby);	
}

//$result_search = mysql_query($sql);
$ArrayParent = Array();
$tpl['shop']['MyShowArray'] = Array();
$tpl['shop']['ArrayParent'] = Array();
$tpl['shop']['ArrayShopcoins'] = $itemsShopcoins = Array();

$tpl['shop']['items'] = Array();
if($data){
	foreach ($data as $rows){
		$tpl['shop']['ArrayShopcoins'][] = $rows["shopcoins"];
		$tpl['shop']['ArrayParent'][] = $rows["parent"];
		$tpl['shop']['MyShowArray'][] = $rows;
	}
}

if($tpl['shop']['ArrayShopcoins']) $itemsShopcoins = $shopcoins_class->findByIds($tpl['shop']['ArrayShopcoins']);

//полные данные о выборках
foreach ($itemsShopcoins as $item){
	unset($item['dateinsert']);
	$tpl['shop']['items'][$item["shopcoins"]] = $item;
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
		
		if($rows['novelty']){
			$stats_class->saveCoinsNovelty($rows["shopcoins"]);
		}

		$rows = array_merge($rows,$tpl['shop']['items'][$rows["shopcoins"]]);
        $tpl['shop']['MyShowArray'][$i] = array_merge($rows,$tpl['shop']['items'][$rows["shopcoins"]]);
	    $tpl['shop']['MyShowArray'][$i]['condition'] = isset($tpl['conditions'][$rows['condition_id']])?$tpl['conditions'][$rows['condition_id']]:'';		    
	    $tpl['shop']['MyShowArray'][$i]['metal'] = isset($tpl['metalls'][$rows['metal_id']])?$tpl['metalls'][$rows['metal_id']]:'';
	    $details = $details_class->getItem($rows["shopcoins"]);
	    $tpl['shop']['MyShowArray'][$i]["details"] =  '';
	    if($details) $tpl['shop']['MyShowArray'][$i]["details"] = $details["details"];

		//формируем картинки "подобные"
		$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'] = array();
		
		if (($rows["materialtype"] ==1)&&isset($tpl['shop']['ImageParent'][$rows["parent"]])&&$tpl['shop']['ImageParent'][$rows["parent"]]>0 && !$mycoins) {	
			$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'][] =contentHelper::showImage("smallimages/".$rows["image_small"],"Монета ".$rows["gname"]." | ".$rows["name"]);
			$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'][] =contentHelper::showImage("smallimages/".$tpl['shop']['ImageParent'][$rows["parent"]][0],"Монета ".$rows["gname"]." | ".$rows["name"]);
		}

		$tpl['shop']['MyShowArray'][$i]['name'] = contentHelper::nominalFormat($tpl['shop']['MyShowArray'][$i]['name']);
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
			$statuses = $shopcoins_class->getBuyStatus($rows['shopcoins'],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder,$rows);
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

$tpl['seo_data'] = '';


require $cfg['path'] . '/configs/shopcoins_keywords.php';

$tpl['shopcoins']['seo'] = $tpl['seo_data'] = $shopcoins_class->getSeo($materialtype,$groupMain,$nominalMain,$metalMain,$year_data[0]);
if($tpl['user']['user_id']==811) {
	//var_dump($tpl['seo_data'],$materialtype,$groupMain,$nominalMain,$metalMain,$year_data[0]);
}
if($tpl['seo_data']["pagetitle"]){    		
        $tpl['shopcoins']['_Title'] = $tpl['seo_data']["pagetitle"];
}

if($tpl['seo_data']["description"]){
        $tpl['shopcoins']['_Description'] = $tpl['seo_data']["description"] ;
}       

if($tpl['pagenum']&&$tpl['pagenum']!=1){
	$tpl['shopcoins']['seo'] = '';
}
	
$tmp = explode("#", $LastCatalog10);
$k = 0;
$ids = array();
for ($i=0; $i<sizeof($tmp); $i++) {
	$tmp1 = explode("|", $tmp[$i]);
	if($tmp1[0]){
	   $ids[] = (int)$tmp1[0];
	}
	
}	

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
}


$arraykeyword[] = "монеты";

$tpl['infotext'] = '';

if ($materialtype==7){
    $tpl['infotext'] = "Изображение предоставлено для данного типа монет. Все наборы не из обращения, но могут быть иногда банковские царапины, патина, налет и прочие дефекты хранения. В некоторых случаях может быть несовпадение года.";
}

if ($materialtype==8){
    $tpl['infotext'] = "Изображение предоставлено для данного типа монет приблизительно одного состояния (+/- 0.5 по шкале F VF XF UNC). Могут быть отклонения по состоянию как в большую так и в меньшую сторону. Proof в эту категорию не входит, поскольку, предполагается, что Proof - это идеальное зеркальное состояние без царапин, заляпин и т.п. Предложения типа - выберите мне полуше - будут отрезаться на корню. В некоторых случаях может быть несовпадение года.";
}
//$show_cookie = ( isset($_COOKIE['show_ussa']) ) ? $_COOKIE['show_ussa'] : 1;    
if ($materialtype==12){
    $tpl['infotext'] = "Состояние монет данного раздела от VF до XF - т.е. представлены мелочовка ходячка монет СССР, которые были в обращении. Это именно те монеты, которые мало чего стоят и основанная цена складывается за счет трудозатрат на них. Будьте внимательны при выборе. Предложения типа - выберите мне полуше - будут отрезаться на корню.";
}
//if($show_cookie < time() and $materialtype == 12)

//setcookie("show_ussa", time()+60*60*24*30, time() + 30*24*60*60, "/shopcoins/");

foreach ($tpl['breadcrumbs'] as $key=>$value){
	//$base_href = "/".str_replace($cfg['site_dir'],'',$value["base_href"]);
	
	if($value["base_href"]==$correct_url){
		$tpl['breadcrumbs'][$key]['base_href'] = '';
	}
}

?>