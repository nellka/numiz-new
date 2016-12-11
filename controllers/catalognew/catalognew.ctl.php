<?php
$OrderByArray = array();

require_once($cfg['path'].'/helpers/Paginator.php');
require_once($cfg['path'].'/models/catalognew.php');
require_once($cfg['path'].'/configs/config_catalognew.php');
if($tpl['user']['user_id']==352480){  
		//var_dump($_REQUEST,$cfg['site_dir']."catalognew/".$correct_links["rehref"]);
    	//die();
	}
$tpl['catalognew']['errors'] = array();
$tpl['catalognew']['OtherMaterialData'] = array();

$urlParams = array();

$materialtype = (int) request('materialtype');

if(!$materialtype) $materialtype = 1;
    
if ($materialtype==1 ) {
    $tpl['catalognew']['_Title'] = "Каталог монет ".($GroupName?" ".$GroupName:"")." | Клуб Нумизмат";
} elseif ($materialtype==8) {
    $tpl['catalognew']['_Title'] = "Каталог дешевых монет ".($GroupName?" ".$GroupName:"")." | Клуб Нумизмат";
}  elseif ($materialtype==7)  {
    $tpl['catalognew']['_Title'] = "Каталог наборов монет ".($GroupName?" ".$GroupName:"")." | Клуб Нумизмат";
}  elseif ($materialtype==4)  {
    $tpl['catalognew']['_Title'] = "Каталог подарочных наборов монет ".($GroupName?" ".$GroupName:"")." | Клуб Нумизмат";
}  elseif ($materialtype==2)  {
    $tpl['catalognew']['_Title'] = "Каталог банкнот(бон) ".($GroupName?" ".$GroupName:"")." | Клуб Нумизмат";
}   else   {
    $tpl['catalognew']['_Title'] = "Каталог монет".($GroupName?" | ".$GroupName:"")." | Клуб Нумизмат";
}

$tpl['catalognew']['_Keywords'] = "нумизматика, монеты, магазин, нумизмат, коллекция, каталог монет, цены на монеты, продажа монет, бонистика, старинные монеты, куплю монеты, монеты России, медь, монеты Германии, proof, монетный двор, чеканка, серебро, альбом для монет, планшет для монет, капсулы для монет, лупы, холдеры, холдеры для монет, аксессуары для монет, аксессуары для коллекционирования, альбом для коллекции, кейс для монет, онеты, моне, деньги, старинные деньги, нумизма, нумизматик, монеты ссср цены, старинные монеты, оценка монет, стоимость монет, нумезматика, манеты, оценка монет, оценка стоимости монет, царские монеты, золотые монеты, сребряные монеты, медные монеты";
$tpl['catalognew']['_Description'] = "Уникальный интерактивный каталог монет со всего мира: изображения, подробные описания, цены, возможность заказать любую монету из каталога. Монеты России, СССР, Великобритании, США, Германии и многих других стран мира.";


$urlParams['materialtype'] = $materialtype;

$tpl['task'] = 'catalognew';

$catalognew_class = new model_catalognew($db_class);

$tpl['breadcrumbs'][] = array(
    	'text' => 'Каталог монет',
    	'href' => $cfg['site_dir'].'catalognew',
    	'base_href' =>'catalognew'
);  

$tpl['breadcrumbs'][] = array(
    	'text' => contentHelper::$menu[$materialtype],
    	'href' => urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/catalognew"),
    	'base_href' =>urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/catalognew")
    	);
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
$tpl['c_hi'] = (isset($_COOKIE['c_hi'])&&$_COOKIE['c_hi'])?0:1;

setcookie('c_hi', 1,  time()+ 24*3600,'/',$domain);

//сохраняем сортировку элементов на странице в куке
if(request('orderby')){
    $tpl['orderby'] = request('orderby');
} elseif (isset($_COOKIE['orderby'])){
    $tpl['orderby'] =$_COOKIE['orderby'];
}	
if(!isset($tpl['orderby']))	$tpl['orderby'] = "dateinsertdesc";
setcookie('orderby', $tpl['orderby'],  time()+ 86400 * 90,'/',$domain);

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;
    	
//коллекция пользователя http://www.numizmatik.ru/catalognew/index.php?1&usershopcoinssubscribe=355338
$usershopcoinssubscribe = $urlParams['usershopcoinssubscribe'] = (int)request('usershopcoinssubscribe') ;

$usercatalogsubscribe  = $urlParams['usercatalogsubscribe'] = (int)request('usercatalogsubscribe');
$usermycatalog  = $urlParams['usermycatalog'] = (int)request('usermycatalog');
$usermycatalogchange  = $urlParams['usermycatalogchange'] = (int)request('usermycatalogchange');


$theme  = (int)request('theme');
$themes  =request('themes');
$nominal  = (int)request('nominal');
$nominals  =request('nominals');
$metals  =request('metals');
$metal  = (int)request('metal');

$years  = (array)request('years');
$years_p = (array)request('years_p');

$yearstart  =request('yearstart');
$yearend  =request('yearend');

$GroupNameMain = '';
$GroupNameID = '';
$GroupName = ''; 


$WhereParams = array();
$group = (int)request('group');

$theme_data = array();
$metal_data = array();
$year_data = array();
$group_data = array();
$years_data  = array(); 
$years_p_data = array(); 
$nominal_data = array(); 

$groups = (array)request('groups');


$groupHref = "";
$groupMain = 0;

if ($groups)  $group_data = $groups;
elseif($group) {
	$group_data =  array($group);
	$groups =  array($group);
}

if($metals&&!is_array($metals)){
    $metals = array($metals);
}

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

if ($themes) $theme_data =$themes;
elseif($theme) {
    $theme_data =  array($theme);
    $themes =  array($theme);
}
	
if ($groups)  $group_data =$groups;
elseif($group) {
	$group_data =  array($group);
	$groups =  array($group);
}

if($groups){
	if ($nominals) $nominal_data =$nominals;
	elseif($nominal) {
		$nominal_data =  array($nominal);
		$nominals =  array($nominal);
	}
}

if(($nominals&&$groups)||($groups&&$materialtype==4)){
    $years_data = $years;
} else {
    
    if($yearend==date('Y',time())){
    	$yearend='';
    }
    
    krsort($years_p);
    
    $i=0;
    foreach ($years_p as $val){       
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


$WhereParams['materialtype'] = $materialtype;

$catalognew_class->setMaterialtype($materialtype);


$tpl['catalognew']['user_id'] = $tpl['user']['user_id'];

if ($usershopcoinssubscribe) {
    if ($usershopcoinssubscribe == $tpl['user']['user_id']){
        $tpl['catalognew']['user_id'] = $tpl['user']['user_id'];
   } else $tpl['catalognew']['user_id'] = $usershopcoinssubscribe;
   
   $tpl['catalognew']['href'] = $cfg["site_dir"]."catalognew/index.php?usershopcoinssubscribe=".$usershopcoinssubscribe.'&materialtype='.$materialtype;

} elseif ($usercatalogsubscribe) {
        
    if ($usercatalogsubscribe == $tpl['user']['user_id']){
        $tpl['catalognew']['user_id'] = $tpl['user']['user_id'];
    } else $tpl['catalognew']['user_id'] = $usercatalogsubscribe;
       
} elseif ($usermycatalog) {
    
    if ($usermycatalog == $tpl['user']['user_id']){
        $tpl['catalognew']['user_id'] = $tpl['user']['user_id'];
    } else $tpl['catalognew']['user_id'] = $usermycatalog;
    
    $tpl['catalognew']['href'] = $cfg["site_dir"]."catalognew/index.php?usermycatalog=".$usermycatalog.'&materialtype='.$materialtype;
    
} elseif ($usermycatalogchange) {    
    if ($usermycatalogchange == $tpl['user']['user_id']){
        $tpl['catalognew']['user_id'] = $tpl['user']['user_id'];
    } else $tpl['catalognew']['user_id'] = $usermycatalogchange;
    
    $tpl['catalognew']['href'] = $cfg["site_dir"]."catalognew/index.php?usermycatalogchange=".$usermycatalogchange.'&materialtype='.$materialtype;

}

if(($tpl['user']['user_id']!=$tpl['catalognew']['user_id'])&&$tpl['catalognew']['user_id']){
    $tpl['catalognew']['user'] = $user_class->getUserDataByID($tpl['catalognew']['user_id']);
}


$arraygroupbottom = array();

if ($materialtype==3) {

    $arraygroupbottom[] = 816;
    $sql5 = "select `group` from `group` where groupparent=816;";
    $result5 = mysql_query($sql5);
    while ($rows5 = mysql_fetch_array($result5))
        $arraygroupbottom[] = $rows5[0];
}



    if($group_data) $WhereParams['group'] = $group_data;
    if($metal_data) $WhereParams['metal'] = $metal_data;

    if($theme_data) $WhereParams['theme'] = $theme_data;
    if($years_data) $WhereParams['year'] = $years_data;
    if($years_p_data) $WhereParams['year_p'] = $years_p_data;   
    if($nominal_data)  $WhereParams['nominals'] = $nominal_data;
    
    if ($usershopcoinssubscribe) { //подписка пользователя
        $WhereParams['catalogshopcoinssubscribe'] = $usershopcoinssubscribe;       
    }  elseif ($usercatalogsubscribe) { //подписка пользователя
        $WhereParams['usercatalogsubscribe'] = $usercatalogsubscribe;       
    } elseif ($usermycatalog or $usermycatalogchange)  {
        //моя коллекция        
        if ($usermycatalog){
            $WhereParams['catalognewmycatalog_usermycatalog'] = $usermycatalog;            
        }
        
        if ($usermycatalogchange) {
            $WhereParams['catalognewmycatalog_usermycatalogchange'] = $usermycatalogchange;            
        }
    }
    
$r_url = $cfg['site_dir'].'catalognew/?materialtype='.$materialtype;

$tpl['is_Subscribe_for_group'] = false;
$tpl['seo_data'] = '';

if(!$tpl['pagenum']||$tpl['pagenum']==1){
    $tpl['seo_data'] = $catalognew_class->getSeo($materialtype,$group_data,$nominal_data);    
	
	if($tpl['seo_data']["pagetitle"]){    		
		$tpl['catalognew']['_Title'] = $tpl['seo_data']["pagetitle"];
	}
	
	if($tpl['seo_data']["description"]){
		$tpl['catalognew']['_Description'] = $tpl['seo_data']["description"] ;
	}
}
if(count($group_data)==1){
    $groupMain = $GroupNameID = $group_data[0];

    $data_filter['group_id'] = $groupMain;
    $groupData = $shopcoins_class->getGroupItem($group_data[0]);
	//получаем дочерние элементы    
	$childs = $catalognew_class->getParrentGroupsIds($group_data[0],$WhereParams);
    //Проверяем, подписаны или нет
    
    $tpl['is_Subscribe_for_group'] = $catalognew_class->getMyGroupSubscribe($tpl['user']['user_id'],$group_data[0]);
    
	$i=1;
	foreach ($childs as $child){
	    $group_data[$i] = $child;
	    $groups[$i] = $child;
	    $i++;
	}	
		
	$GroupName = $groupData["name"];
	$urlParams['group'] = array($group_data[0]=>$GroupName);
	
	$tpl['breadcrumbs'][] = array(
        	'text' => $GroupName,
        	'href' => urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'group'=>array($group_data[0]=>$GroupName)),"http://www.numizmatik.ru/catalognew"),
        	'base_href' =>urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'group'=>array($group_data[0]=>$GroupName)),"http://www.numizmatik.ru/catalognew"),
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
	
	$tpl['catalognew']['OtherMaterialData'] = $catalognew_class->getOtherMaterialData($group_data[0],$materialtype);		

	if ($tpl['catalognew']['OtherMaterialData']){		
	    $oldmaterialtype = 0;
		foreach ($tpl['catalognew']['OtherMaterialData'] as $key=>$rows){
		    
		    $tpl['catalognew']['OtherMaterialData'][$key]['metal'] = isset($tpl['metalls'][$rows['metal_id']])?$tpl['metalls'][$rows['metal_id']]:'';
		    $tpl['catalognew']['OtherMaterialData'][$key]['condition'] = isset($tpl['conditions'][$rows['condition_id']])?$tpl['conditions'][$rows['condition_id']]:'';
		    $tpl['catalognew']['OtherMaterialData'][$key]['gname'] = $GroupName;
		    
		    /*$tpl['catalognew']['related'][$i]['additional_title'] = '';
			if ($oldmaterialtype != $rows["materialtype"]) {
				$tpl['catalognew']['related'][$i]['additional_title'] = $MaterialTypeArray[$rows["materialtype"]];
				$oldmaterialtype = $rows["materialtype"];
			}*/
		}
	}	
}

if($groups){
	if ($nominals) $nominal_data =$nominals;
	elseif($nominal) {
		$nominal_data =  array($nominal);
		$nominals =  array($nominal);
	}
}

require($cfg['path'].'/controllers/filters/filters_catalog.ctl.php');

if($tpl['pagenum']>1) $urlParams['pagenum']=$tpl['pagenum'] ;

$countpubs = $catalognew_class->countallByParams($WhereParams);

//end - потом не забыть подключить

$nominalMain = 0;
$nominalMainTitle = '';

if(count($nominal_data)==1&&$nominal_data[0]){
   
    $nominalMain = $nominal_data[0];
    $data_filter['nominal_id'] = $nominalMain;
    $nominalMainTitle = $shopcoins_class->getNominal($nominal_data[0]);
    $urlParams['nominal'] = array($nominalMain=>$nominalMainTitle); 
    $tpl['breadcrumbs'][] = array(
    	'text' => $nominalMainTitle,
    	'href' => urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'group'=>array($group_data[0]=>$GroupName),'nominal' => array($nominalMain=>$nominalMainTitle)),"http://www.numizmatik.ru/catalognew"),
    	'base_href' =>urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'group'=>array($group_data[0]=>$GroupName),'nominal' => array($nominalMain=>$nominalMainTitle)),"http://www.numizmatik.ru/catalognew"),
    );    
} else{   
   $urlParams['nominal'] = $nominal_data;
}

if(count($years)==1&&$years[0]){    
    $data_filter['years'] = $years;
    $urlParams['years'] = $years;
    $tpl['breadcrumbs'][] = array(
    	'text' => $years[0],
    	'href' => urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'group'=>array($group_data[0]=>$GroupName),'years' => $years),"http://www.numizmatik.ru/catalognew"),
    	'base_href' =>urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'group'=>array($group_data[0]=>$GroupName),'years' => $years),"http://www.numizmatik.ru/catalognew"),
    );
    $H1_sub .=" $years[0]";
} else {
    foreach ((array)$years as $y){
        $urlParams['years'][] = $y;
    }
}

if(count($years_p)==1&&$years_p[0]){    
    $data_filter['year'] = $years_p[0];
    $urlParams['years_p'] = $years_p;
    $tpl['breadcrumbs'][] = array(
    	'text' => $years_p[0],
    	'href' => urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'group'=>array($group_data[0]=>$GroupName),'years_p' => $years_p),"http://www.numizmatik.ru/catalognew"),
    	'base_href' =>urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'group'=>array($group_data[0]=>$GroupName),'years_p' => $years_p),"http://www.numizmatik.ru/catalognew"),
    );

    $H1_sub .=" $years_p[0]";
} else {
    foreach ((array)$years_p as $y){
       $urlParams['years_p'][] = $y;
    }
}

$metalMain = 0;
$metalMainTitle = '';

if(count($metal_data)==1){
    $metalMain = $metal_data[0];
    $data_filter['metal_id'] = $metalMain;
    $metalMainTitle = $tpl['metalls'][$metalMain];
    
    $tpl['breadcrumbs'][] = array(
    	'text' => $metalMainTitle,
    	'href' => urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'metal' => array($metalMain=>$metalMainTitle),'group'=>array($group_data[0]=>$GroupName),'years_p' => $years_p),"http://www.numizmatik.ru/catalognew"),
    	'base_href' =>urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'metal' => array($metalMain=>$metalMainTitle),'group'=>array($group_data[0]=>$GroupName),'years_p' => $years_p),"http://www.numizmatik.ru/catalognew")
    );
    
    $urlParams['metal'] = array($metalMain=>$metalMainTitle); 
} else {
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
} else {
     $urlParams['theme'] = $theme_data;
}

$correct_url = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/catalognew");

if($tpl['user']['user_id']==352480){  
	//var_dump($correct_url,$WhereParams);
	//die();
}
	if(($tpl['datatype']!='text_html')&&($correct_url!="http://www.numizmatik.ru".$_SERVER["REQUEST_URI"])){
	     header("HTTP/1.1 301 Moved Permanently"); 
	     header("location: ".$correct_url);
	     die();
	}  
//}

unset($urlParams['pagenum']);
$correct_url_for_paginator = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/catalognew");
	
$tpl['paginator'] = new Paginator(array(
        'url'        => $correct_url_for_paginator,
        'count'      => $countpubs,
        'per_page'   => ($tpl['onpage']=='all')?$countpubs:$tpl['onpage'],
        'page'       => $tpl['pagenum'],
        'border'     =>3));
        

$tpl['catalognew']['testaddcoins'] =0;

if ($tpl['user']['user_id']) {
    $userData = $user_class->getUserData();
    if ($userData["star"] > 10 || $clientdiscount){
        $tpl['catalognew']['testaddcoins'] =1;
    } 
}

if ($tpl['orderby']=="dateinsertdesc"){
	$OrderByArray[] = "s.dateinsert desc";
} elseif ($tpl['orderby']=="dateinsertasc"){
	$OrderByArray[] = " s.dateinsert asc";
} elseif ($tpl['orderby']=="yearasc"){
	$OrderByArray[] = "s.yearstart asc";
	$OrderByArray[] ="s.dateinsert desc";
} elseif ($tpl['orderby']=="yeardesc"){
	$OrderByArray[] = "s.yearstart desc";
	$OrderByArray[] = "s.dateinsert desc ";
} elseif($materialtype==3){
	$OrderByArray[] = "s.name";
} else{
    $OrderByArray[] = "s.dateinsert desc ";
}


if ($materialtype==3 and $group)
    $orderby = "order by s.name ";

if ($orderby=="dateinsertdesc")
    $orderby = "order by s.dateinsert desc";
elseif ($orderby=="dateinsertasc")
    $orderby = "order by s.dateinsert asc";


if (!$orderby)
    $orderby = "order by s.dateinsert desc";



$CatalogArray = Array();

$tpl['catalognew']['MyShowArray'] = Array();
$tpl['catalognew']['CatalogArray'] = Array(); 

$data = $catalognew_class->getItemsByParams($WhereParams,$tpl['pagenum'],$tpl['onpage'],$OrderByArray);	

foreach ($data as $rows){
   $tpl['catalognew']['MyShowArray'][] = $rows;
   $tpl['catalognew']['CatalogArray'][] = $rows["catalog"];
}

$tpl['catalognew']['shopcoinssubbscribe'] = Array();
$tpl['catalognew']['shopcoinssubbscribedate'] = Array();

$tpl['catalognew']['catalognewmycatalog'] = Array();
$tpl['catalognew']['detailsmycatalog'] = Array();
$tpl['catalognew']['typemycatalog'] = Array();
    
//выбираем на что мы хотим подписываться
if ($tpl['catalognew']['user_id'] and sizeof($tpl['catalognew']['CatalogArray'])>0) {
    
    $result_shopcoinssubbscribe = $catalognew_class->getShopcoinssubbscribe($tpl['catalognew']['user_id'],$tpl['catalognew']['CatalogArray']);
   
    foreach ($result_shopcoinssubbscribe as $rows) {
        $tpl['catalognew']['shopcoinssubbscribe'][$rows["catalog"]] = ($rows["amount"]>1?$rows["amount"]:1);
        $tpl['catalognew']['shopcoinssubbscribedate'][$rows["catalog"]] = $rows["dateinsert"];
    }
   
    $catalognewmycatalog = Array();
    
    $result_shopcoinssubbscribe = $catalognew_class->getCatalognewmycatalog($tpl['catalognew']['user_id'],$tpl['catalognew']['CatalogArray']);

    foreach ($result_shopcoinssubbscribe as $rows) {
        $tpl['catalognew']['catalognewmycatalog'][$rows["catalog"]] = $rows["catalognewmycatalog"];
        $tpl['catalognew']['detailsmycatalog'][$rows["catalog"]] = $rows["detailschange"];
        $tpl['catalognew']['typemycatalog'][$rows["catalog"]] = $rows["type"];
    }        
}

$tpl['catalognew']['catalogshopcoins'] = Array();

if (sizeof($tpl['catalognew']['CatalogArray'])>0) {
    
    $result_shopcoins = $catalognew_class->getCatalogshopcoinsrelation($tpl['catalognew']['CatalogArray']);      

    foreach ($result_shopcoins as $rows_shopcoins){
       // var_dump($rows_shopcoins);
       // echo "<br>";
        $tpl['catalognew']['catalogshopcoins'][$rows_shopcoins["catalog"]] =  $rows_shopcoins["shopcoins"];
    }
}

if (sizeof($tpl['catalognew']['MyShowArray'])==0) {
    $tpl['catalognew']['errors'][] = "<br><p class=txt><strong><font color=red>Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</font></strong><br><br>";
} else {       

    foreach ($tpl['catalognew']['MyShowArray'] as $key=>$rows) {

        $tpl['catalognew']['MyShowArray'][$key]['metal'] = $tpl['metalls'][$rows['metal']];
        $tpl['catalognew']['MyShowArray'][$key]['shopcoins'] = $rows['catalog'];
        $tpl['catalognew']['MyShowArray'][$key]['year'] = $rows['yearstart'];
        
      // moneta-severnaya-koreya-1-von-alyuminii-2001_c67404_m1.html
      // moneta-severnaya-koreya-1-von-alyuminii-2001_c_pc_m1_pp1.html
        $tpl['catalognew']['MyShowArray'][$key] = array_merge($tpl['catalognew']['MyShowArray'][$key], contentHelper::getRegHref($tpl['catalognew']['MyShowArray'][$key],$materialtype));
        $tpl['catalognew']['MyShowArray'][$key]['show_in_shop'] = false;
        if (isset($tpl['catalognew']['catalogshopcoins'][$rows["catalog"]]))  {
            $tpl['catalognew']['MyShowArray'][$key]['show_in_shop'] = true; 
            $tpl['catalognew']['MyShowArray'][$key]['show_in_shop_id'] = $tpl['catalognew']['catalogshopcoins'][$rows["catalog"]];            
        }
        $shopcoinstheme = array();
        $strtheme = decbin($rows["theme"]);
        $strthemelen = strlen($strtheme);
        $chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
        for ($k=0; $k<$strthemelen; $k++)
        {
            if ($chars[$k]==1)
                $shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
        }
        $tpl['catalognew']['MyShowArray'][$key]["theme"] = implode(',',$shopcoinstheme );
        if($rows["condition"]) $tpl['catalognew']['MyShowArray'][$key]["condition"] = $ConditionMintArray[$rows["condition"]];

        $text = '';
        if (trim($rows["details"])) {
            $text = substr($rows["details"], 0, 250);
            $text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
        }

        $tpl['catalognew']['MyShowArray'][$key]["details"] = str_replace("\n","<br>",$text);   

        
        //мои заявки
        $tpl['catalognew']['MyShowArray'][$key]["in_request"] = false;
        $tpl['catalognew']['MyShowArray'][$key]["in_collection"] = false;
        $tpl['catalognew']['MyShowArray'][$key]["can_delete_subscribe"] = false;
        $tpl['catalognew']['MyShowArray'][$key]["show_list"] = false;
        
        if (isset($tpl['catalognew']['shopcoinssubbscribe'][$rows["catalog"]])&&($tpl['catalognew']['shopcoinssubbscribe'][$rows["catalog"]]>=1)){
            $tpl['catalognew']['MyShowArray'][$key]["in_request"] = true;
        
            if ($materialtype == 3) {    
                $tpl['catalognew']['MyShowArray'][$key]["in_request"] = true;
                $tpl['catalognew']['MyShowArray'][$key]["request_date"] = $tpl['catalognew']['shopcoinssubbscribedate'][$rows["catalog"]];
                $tpl['catalognew']['MyShowArray'][$key]["shopcoins_subbscribe"] = $tpl['catalognew']['shopcoinssubbscribe'][$rows["catalog"]];
            }
        
            if ($usershopcoinssubscribe == $tpl['user']['user_id']) {            
                $tpl['catalognew']['MyShowArray'][$key]["can_delete_subscribe"] = true;
            }
        }
        
        if ($rows['materialtype'] != 3) {
            if (isset($tpl['catalognew']['catalognewmycatalog'][$rows["catalog"]])&&($tpl['catalognew']['catalognewmycatalog'][$rows["catalog"]] > 0)) {
                $tpl['catalognew']['MyShowArray'][$key]["in_collection"] = true;
                
                if ($usermycatalog == $tpl['user']['user_id'] or $usermycatalogchange == $tpl['user']['user_id']) {
                    $tpl['catalognew']['MyShowArray'][$key]["show_list"] = true;
                    $tpl['catalognew']['MyShowArray'][$key]["show_list_id"] = $tpl['catalognew']['catalognewmycatalog'][$rows["catalog"]];
                }
            }
        }       
    }       
}


foreach ($tpl['breadcrumbs'] as $key=>$value){
//$base_href = "/".str_replace($cfg['site_dir'],'',$value["base_href"]);

	if($value["base_href"]==$correct_url){
		$tpl['breadcrumbs'][$key]['base_href'] = '';
	}
}
