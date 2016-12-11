<?php

$yearsArray = Array (
		1 => array('name' => '2001-настоящее время','data'=>array(2001,(integer)date('Y',time()))), 
		2 => array('name' => '1901-2000','data'=>array(1901,2000)),
		3 => array('name' => '1801-1900','data'=>array(1801,1900)),
		4 => array('name' => '1701-1800','data'=>array(1701,1800)),
		5 => array('name' => '1601-1700','data'=>array(1601,1700)));
$OrderByArray = array();

require_once($cfg['path'].'/helpers/Paginator.php');
require_once($cfg['path'].'/models/price.php');

$tpl['price']['errors'] = array();

$urlParams = array();
$r_url = $cfg['site_dir']."/price";

$price_class = new model_priceshopcoins($db_class);

$group = (int) request('group');
$nominal = (int)request('nominal');
$year  = (int)request('years');

$yearstart  =(int)request('yearstart');
$yearend  =(int)request('yearend');
$condition = (int)request('condition');

$sortname = request('sortname');
$tpl['breadcrumbs'][] = array(
	    	'text' => 'Главная',
	    	'href' => $cfg['site_dir'],
	    	'base_href' =>$cfg['site_dir']
);
$tpl['breadcrumbs'][] = array(
	    	'text' => 'Стоимость монет',
	    	'href' => $cfg['site_dir'].'price',
	    	'base_href' =>'price'
);

$tpl['pager']['sorts'] = array('dateinsert'=>'новизне',                     
                      'price'=>'по цене',
                      'year'=>'году');
$tpl['pager']['itemsOnpage'] = array(9=>9,18=>18,36=>36,48=>48,72=>72);

$group = (int)request('group');
$GroupNameMain = '';
$GroupNameID = '';
$GroupName = ''; 

$simbols_data = array();
$price_data  = array();
$metal_data = array();
$year_data = array();
$group_data = array();
$condition_data  = array(); 
$years_data  = array(); 
$nominal_data = array(); 

$groups = (array)request('groups');
$years  = (array)request('years');
$metals  = (array)request('metals');
$years_p = (array)request('years_p');
$conditions  = (array)request('conditions');
$simbols  = (array)request('simbols');
$nominals = (array)request('nominals');

$tpl['price']['_Keywords'] = "нумизматика, монеты, магазин, нумизмат, коллекция, каталог монет, цены на монеты, продажа монет, бонистика, старинные монеты, куплю монеты, монеты России, медь, монеты Германии, proof, монетный двор, чеканка, серебро, альбом для монет, планшет для монет, капсулы для монет, лупы, холдеры, холдеры для монет, аксессуары для монет, аксессуары для коллекционирования, альбом для коллекции, кейс для монет, онеты, моне, деньги, старинные деньги, нумизма, нумизматик, монеты ссср цены, старинные монеты, оценка монет, стоимость монет, нумезматика, манеты, оценка монет, оценка стоимости монет, царские монеты, золотые монеты, сребряные монеты, медные монеты";
$tpl['price']['_Description'] = "Уникальный интерактивный каталог монет со всего мира: изображения, подробные описания, цены, возможность заказать любую монету из каталога. Монеты России, СССР, Великобритании, США, Германии и многих других стран мира.";
$tpl['price']['_Title'] = "Стоимость монет СССР и России | Клуб Нумизмат";

foreach ($groups as $k=>$v){
    $groups[$k] = (int)$v;
}

if ($groups)  $group_data =$groups;
elseif($group) {
	$group_data =  array($group);
	$groups =  array($group);
}

$groupHref = "";
$groupMain = 0;

if(count($group_data)==1){
    $groupMain = $GroupNameID = $group_data[0];
    $data_filter['group_id'] = $groupMain;
    
    $groupData = $shopcoins_class->getGroupItem($group_data[0]);
	//получаем дочерние элементы   
	$GroupName = $groupData["name"];
	
	$temp = explode(" ",$GroupName);
	foreach ($temp as $key=>$value) {
		
		if (trim($value) && trim($value)!='-' && trim($value)!='до') $arraykeyword[] = $value;
	}
	
	$tpl['price']['_Title'] = "Стоимость монет | ".$GroupName;
	$H1_sub .=" $GroupName";
	
	$urlParams['group'] = array($groupMain=>$GroupName);
	 
	$tpl['breadcrumbs'][] = array(
        	'text' => $GroupName,
        	'href' => urlBuild::makePrettyUrl($urlParams,$r_url),
        	'base_href' =>urlBuild::makePrettyUrl($urlParams,$r_url)
    );			
} else $urlParams['group'] = $group_data;


if($groups){
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

if ($nominals) $nominal_data =$nominals;
elseif($nominal) {
	$nominal_data =  $nominals = array($nominal);
}
$nominalMain = 0;
$nominalMainTitle = '';

if(count($nominal_data)==1&&$nominal_data[0]){
    $nominalMain = $nominal_data[0];
    $data_filter['nominal_id'] = $nominalMain;
    $ndata = $price_class->getNominal($nominal_data[0]);
    
	$nominalMainTitle = $ndata['name'];
    $H1_sub .=" $nominalMainTitle";
    
    $urlParams['nominal'] = array($nominalMain=>$nominalMainTitle); 
    
    $tpl['breadcrumbs'][] = array(
    	'text' => $nominalMainTitle,
    	'href' => urlBuild::makePrettyUrl($urlParams,$r_url),
    	'base_href' =>urlBuild::makePrettyUrl($urlParams,$r_url),
    );
    
} else $urlParams['nominal'] = $nominal_data;


if(count($years)==1&&$years[0]){    
    $data_filter['years'] = $years;
    $urlParams['years'] = $years;
    $tpl['breadcrumbs'][] = array(
    	'text' => $years[0],
    	'href' => urlBuild::makePrettyUrl($urlParams,$r_url),
    	'base_href' =>urlBuild::makePrettyUrl($urlParams,$r_url),
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
    	'href' => urlBuild::makePrettyUrl($urlParams,$r_url),
    	'base_href' =>urlBuild::makePrettyUrl($urlParams,$r_url),
    );

    $H1_sub .=" $years_p[0]";
} else {
    foreach ((array)$years_p as $y){
       $urlParams['years_p'][] = $y;
    }
}

if($metals&&!is_array($metals)){
    $metals = array($metals);
}

if ($metals) $metal_data = $metals;
elseif($metal){
	$metal_data =  array($metal);
	$metals =  array($metal);
}

$metalMain = 0;
$metalMainTitle = '';

if(count($metal_data)==1){
    $metalMain = $metal_data[0];
    $mdata = $price_class->getMetal($metalMain);
    
    $metalMainTitle = $arraykeyword[] = $mdata['metal'];

    $H1_sub .=" $metalMainTitle";
    $urlParams['metal'] = array($metalMain=>$metalMainTitle); 
    
    $tpl['breadcrumbs'][] = array(
    	'text' => $metalMainTitle,
    	'href' => urlBuild::makePrettyUrl($urlParams,$r_url),
    	'base_href' =>urlBuild::makePrettyUrl($urlParams,$r_url)
    );    
} else $urlParams['metal'] = $metal_data;

if ($conditions) $condition_data =$conditions;
elseif($condition) {
	$condition_data =  $conditions = array($condition);
}

$conditionMain = 0;
$conditionMainTitle = '';

if(count($condition_data)==1){
    $conditionMain = $condition_data[0]; 
    $data_filter['condition_id'] = $conditionMain;   
    $conditionMainTitle = $tpl['conditions'][$conditionMain]; 
    $H1_sub .=" $conditionMainTitle";   
    $urlParams['condition'] = array($conditionMain=>$conditionMainTitle);
     
} else $urlParams['condition'] = $condition_data;

if ($simbols) $simbol_data =$simbols;
elseif($simbol) {
	$simbol_data =  $simbols = array($simbol);
}

$simbolMain = 0;
$simbolMainTitle = '';

if(count($simbol_data)==1){
    $simbolMain = $simbol_data[0]; 
    $simbolMainTitle = $tpl['conditions'][$simbolMain]; 
    $H1_sub .=" $simbolMainTitle";   
    $urlParams['simbol'] = array($simbolMain=>$simbolMainTitle);
     
} else $urlParams['simbol'] = $simbol_data;


//сохраняем количество элементов на странице в куке
if(request('onpage')){
    $tpl['onpage'] = request('onpage');
} elseif (isset($_COOKIE['onpage'])){
    $tpl['onpage'] =$_COOKIE['onpage'];
}	
if(!isset($tpl['onpage']))	$tpl['onpage'] = 18;

setcookie('onpage', $tpl['onpage'],  time()+ 86400 * 90,'/',$domain);

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;


$WhereParams = Array();

if($group_data) $WhereParams['group'] = $group_data;
if($nominal_data) $WhereParams['nominal'] = $nominal_data;
if($years_data) $WhereParams['year'] = $years_data;
if($years_p_data) $WhereParams['year_p'] = $years_p_data;   
if($metal_data) $WhereParams['metal'] = $metal_data;	
if($condition_data) $WhereParams['condition'] = $condition_data;
if($simbol_data) $WhereParams['simbol'] = $simbol_data;

require($cfg['path'].'/controllers/filters/filters_price.ctl.php');

if($tpl['pagenum']>1) $urlParams['pagenum']=$tpl['pagenum'] ;

$countpubs = $price_class->countallByParams($WhereParams);

$correct_url = urlBuild::makePrettyUrl($urlParams,$r_url);


if(($tpl['datatype']!='text_html')&&($correct_url!="http://www.numizmatik.ru".$_SERVER["REQUEST_URI"])){
     header("HTTP/1.1 301 Moved Permanently"); 
     header("location: ".$correct_url);
     die();
}  

unset($urlParams['pagenum']);

$correct_url_for_paginator = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/price");
	
$tpl['paginator'] = new Paginator(array(
        'url'        => $correct_url_for_paginator,
        'count'      => $countpubs,
        'per_page'   => ($tpl['onpage']=='all')?$countpubs:$tpl['onpage'],
        'page'       => $tpl['pagenum'],
        'border'     =>3));
        

if(request('orderby')){
    $tpl['orderby'] = request('orderby');
} elseif (isset($_COOKIE['orderby_p'])){
    $tpl['orderby'] =$_COOKIE['orderby_p'];
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
}  else{
    $OrderByArray = array('pricemetal.position', 'pricename.position asc', 'year', 'dateend');
}

//сохраняем сортировку элементов на странице в куке

if(isset($tpl['orderby'])) setcookie('orderby_p', $tpl['orderby'],  time()+ 86400 * 90,'/',$domain);

$CatalogArray = Array();

$tpl['price']['MyShowArray'] = Array();
$tpl['price']['CatalogArray'] = Array(); 

$data = $price_class->getItemsByParams($WhereParams,$tpl['pagenum'],$tpl['onpage'],$OrderByArray);	

foreach ($data as $rows){
   $tpl['price']['MyShowArray'][] = $rows;
   $tpl['price']['CatalogArray'][] = $rows["catalog"];
}
        
if (sizeof($tpl['price']['MyShowArray'])==0) {
    $tpl['price']['errors'][] = "<br><p class=txt><strong><font color=red>Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</font></strong><br><br>";
}   	

foreach ($tpl['breadcrumbs'] as $key=>$value){	
	if($value["base_href"]==$correct_url){
		$tpl['breadcrumbs'][$key]['base_href'] = '';
	}
}
$tpl['task'] = 'price';

$arraykeyword[] = "россия";

$tpl['price']['seo_left'] = $price_class->getLeftSeo($arraykeyword);

$tpl['price']['seo'] = $tpl['seo_data'] = $price_class->getSeo($group_data,$nominal_data,$metal_data,$year_data);

if($tpl['seo_data']["pagetitle"]){    		
     $tpl['price']['_Title'] = $tpl['seo_data']["pagetitle"];
}

if($tpl['seo_data']["description"]){
     $tpl['price']['_Description'] = $tpl['seo_data']["description"] ;
}       

if($tpl['pagenum']&&$tpl['pagenum']!=1){
	$tpl['shopcoins']['seo'] = '';
}
?>