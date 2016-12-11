<?php

require_once $cfg['path'] . '/models/news.php';
require_once $cfg['path'] . '/configs/config_news.php';
require_once($cfg['path'].'/helpers/Paginator.php');
require_once($cfg['path'].'/helpers/imageMini.php');

$urlParams = array();

$tpl['breadcrumbs'][] = array(
	'text' => 'Новости нумизматики',
	'href' => $cfg['site_dir'].'news',
	'base_href' =>'news'
);

$tpl['current_page'] = '';
	
$years = (array) request('years');
$sp_s = (array) request('sp_s');

$year = request('year');
$sp = request('sp');
$group = request('group');
$theme  =request('theme');
$themes  =request('themes');

$theme_data = array();
$group_data = array();
$years_data = array();
$serach_data = array();

$text = strip_tags(request('text'));
$urlParams['text'] = $text;

$groups = (array)request('groups');

foreach ($groups as $k=>$v){
    $groups[$k] = (int)$v;
}
	
if ($groups)  $group_data =$groups;
elseif($group) {
	$group_data =  array($group);
	$groups =  array($group);
}


if ($themes) $theme_data =$themes;
elseif($theme) {
	$theme_data =  array($theme);
	$themes =  array($theme);
}

//$addhref = ($text?"&text=".$text:"");

foreach ((array)$groups as $g){
   // $addhref .="&groups[]=$g";
}
foreach ((array)$themes as $th){
    //$addhref .="&themes[]=$th";
}
foreach ((array)$years as $y){
   // $addhref .="&years[]=$y";
}
           
if ($sp) $sp_s[] = $sp;

foreach ($sp_s as $key){
    if(!isset($tpl['filters']['search'][$key])) unset($sp_s[$key]);
}

if($sp_s!=array(1,2,3)){
    foreach ((array)$sp_s as $s){
       // $addhref .="&sp_s[]=$s";
    }
    $urlParams['sp'] = $sp_s;
}

if(empty($sp_s))   $sp_s = array(1,2,3);

$serach_data = $sp_s;

if(!in_array($year ,$tpl['filters']['years'] )) $year = null;

if ($years)  $years_data = $years;
elseif($year) {
    $years_data = $years = array($year);
}

$r_url = "http://www.numizmatik.ru/news";

if(count($group_data)==1){
    $groupMain = $GroupNameID = $group_data[0];   
    $groupData = $shopcoins_class->getGroupItem($group_data[0]);			
	$GroupName = $groupData["name"];	
	$urlParams['group'] = array($groupMain=>$GroupName);
	 
	$tpl['breadcrumbs'][] = array(
        	'text' => $GroupName,
        	'href' => $r_url.contentHelper::groupUrl($GroupName,$groupMain),
        	'base_href' =>$r_url.contentHelper::groupUrl($GroupName,$groupMain)
    );	
} else $urlParams['group'] = $group_data;

$WhereParams = Array();

if($years_data) $WhereParams['years'] = $years_data;
if($theme_data) $WhereParams['theme'] = $theme_data;
if($group_data) $WhereParams['group'] = $group_data;

$urlParams['theme'] = $theme_data;

if($text)  {
    $WhereParams['text'] = $text;
    $WhereParams['sp_s'] = $sp_s;
}

if(count($years_data)==1&&$years_data[0]){    
    $urlParams['years'] = $years_data;
    $tpl['breadcrumbs'][] = array(
    	'text' => $years[0],
    	'href' => $r_url.'/years_'.$years_data[0],
    	'base_href' =>$r_url.'/years_'.$years_data[0]
    );
} else $urlParams['years'] = $years_data;

if(count($theme_data)==1){
    $themeMain = $theme_data[0]; 
    $data_filter['theme_id'] = $themeMain;   
    $themeMainTitle = $ThemeArray[$theme_data[0]];  
    $urlParams['theme'] = array($themeMain=>$themeMainTitle);    
} else $urlParams['theme'] = $theme_data;


//var_dump($years);
$news_class = new model_news($db_class,$tpl['user']['user_id']);
$tpl['news']['errors'] = false;

//сохраняем количество элементов на странице в куке
if(request('onpage')){
    $tpl['onpage'] = request('onpage');
} elseif (isset($_COOKIE['onpage'])){
    $tpl['onpage'] =$_COOKIE['onpage'];
}
if(!isset($tpl['onpage']))	$tpl['onpage'] = 18;
$tpl['pager']['itemsOnpage'] = array(9=>9,18=>18,36=>36,48=>48,72=>72);
setcookie('onpage', $tpl['onpage'],  time()+ 86400 * 90,'/',$domain);

//сохраняем сортировку элементов на странице в куке
if(request('orderby')){
    $tpl['orderby'] = request('orderby');
} elseif (isset($_COOKIE['orderby'])){
    $tpl['orderby'] =$_COOKIE['orderby'];
}
if(!isset($tpl['orderby']))	$tpl['orderby'] = "dateinsertdesc";
setcookie('orderby', $tpl['orderby'],  time()+ 86400 * 90,'/',$domain);

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

if($tpl['pagenum']>1) $urlParams['pagenum'] = $tpl['pagenum'];

$countpubs = $news_class->countallByParams($WhereParams);
if(!$countpubs) $tpl['news']['errors'][] = "Новостей нет";


$Meta = $news_class->getMeta("keywords", "text", "`check`=1", "date desc", $tpl['pagenum'],$tpl['onpage']);

$tpl['news']['_Description'] = 'Последние новости нумизматики.'.$Meta[1];
$tpl['news']['_Title'] = "Новости нумизматики - выпуск юбилейных и памятных монет банков мира | Клуб Нумизмат";

$tpl['news']['data'] = $news_class->getItemsByParams($WhereParams,$tpl['pagenum'],$tpl['onpage']);

foreach ($tpl['news']['data'] as $key=>$rows){
    $tpl['news']['data'][$key]['img'] = $news_class->getImg($rows['news'],$rows['text']);
    //if($tpl['user']['user_id']==352480){
    if($tpl['user']['user_id']==352480){    	
    	//var_dump($tpl['news']['data'][$key]['img']);
    	//echo "<br>";
    }
    
    $tpl['news']['data'][$key]['img'] = imageMini::getMini($tpl['news']['data'][$key]['img'],"news_img/");      
    //} 
   
    $news_text = $rows['text'];
    $news_text = str_replace("</h1>","</h1>. ",$news_text); 
    
    $news_text = strip_tags($news_text);
    
    while(substr_count($news_text,"<<<"))
    {
        $news_text=mb_substr($news_text,0,strpos($news_text,"<<<")).substr(strstr($news_text,">>>"),3,'utf-8');
    }
    
    $news_text = mb_substr($news_text, 0, 320,'utf-8').'...';    

    $tpl['news']['data'][$key]['text'] = strip_tags($news_text);
    $tpl['news']['data'][$key]['namehref'] = contentHelper::strtolower_ru($rows["name"])."_n".$rows["news"].".html";
}

//if($addhref) $addhref = substr($addhref,1); 
	
$correct_url = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/news");

if(($tpl['datatype']!='text_html')&&($correct_url!=urldecode("http://www.numizmatik.ru".$_SERVER["REQUEST_URI"]))&&($_SERVER["REQUEST_URI"]!=='/news/')){
     header("HTTP/1.1 301 Moved Permanently"); 
     header("location: ".$correct_url);
     die();
}  

unset($urlParams['pagenum']);

$correct_url_for_paginator = urlBuild::makePrettyUrl($urlParams,"http://www.numizmatik.ru/news");

$tpl['paginator'] = new Paginator(array(
    'url'        => $correct_url_for_paginator,
    'count'      => $countpubs,
    'per_page'   => ($tpl['onpage']=='all')?$countpubs:$tpl['onpage'],
    'page'       => $tpl['pagenum'],
    'border'     =>4));
    
foreach ($tpl['breadcrumbs'] as $key=>$value){	
	if($value["base_href"]==$correct_url){
		$tpl['breadcrumbs'][$key]['base_href'] = '';
	}
}

require_once 'filters.ctl.php';