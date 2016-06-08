<?php

require_once $cfg['path'] . '/models/news.php';
require_once $cfg['path'] . '/configs/config_news.php';
require($cfg['path'].'/helpers/Paginator.php');


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

  /*
  $text = '';
  $title = '';
  $date = date('d/m/Y');
  $source_type = 1;
  $source_link = '';
  $author = '';
  $author_email = '';
  $mode = 'add';
  $shopcoins = '';

  $groups = array();
  $themes = array();


if(isset($_GET['edit']) AND !empty($_GET['edit'])){
  $id = $_GET['edit'];


  $sql = "select * from news LEFT JOIN newsshopcoinsrelation on newsshopcoinsrelation.news = news.news WHERE news.news = ". $id;
  $result = mysql_query($sql);
  $news = mysql_fetch_all($result);

  if(count($news) < 1) die('Нет новости с таким id');
  foreach ($news as $key => $value) {
    if($value['group'])
        $groups[] = $value['group'];
    if($value['theme'])
        $themes[] = $value['theme']; 
  }

  //print_r($groups); die();

  $text = $news[0]['text'];
  $title = $news[0]['name'];
  $date = date('d/m/Y', $news[0]['date']);
  $source_type = $news[0]['typesource'];
  $source_link = $news[0]['source'];
  $author = $news[0]['author'];
  $author_email = $news[0]['email'];
  $shopcoins = $news[0]['shopcoins'];
  $mode = 'edit';

}   */
           
if ($sp) $sp_s[] = $sp;

foreach ($sp_s as $key){
    if(!isset($tpl['filters']['search'][$key])) unset($sp_s[$key]);
}

if(empty($sp_s))   $sp_s = array(1,2,3);

$serach_data = $sp_s;

if(!in_array($year ,$tpl['filters']['years'] )) $year = null;

if ($years)  $years_data = $years;
elseif($year) {
    $years_data = $years =  array($year);
}


$WhereParams = Array();

if($years_data) $WhereParams['years'] = $years_data;
if($theme_data) $WhereParams['theme'] = $theme_data;
if($group_data) $WhereParams['group'] = $group_data;

if($text)  {
    $WhereParams['text'] = $text;
    $WhereParams['sp_s'] = $sp_s;
}

//var_dump($years);
$news_class = new model_news($cfg['db'],$tpl['user']['user_id']);
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

$countpubs = $news_class->countallByParams($WhereParams);
if(!$countpubs) $tpl['news']['errors'][] = "Новостей нет";


$Meta = $news_class->getMeta("keywords", "text", "`check`=1", "date desc", $tpl['pagenum'],$tpl['onpage']);

$tpl['news']['_Description'] = 'Последние новости нумизматики.'.$Meta[1];
$tpl['news']['_Title'] = "Новости нумизматики - выпуск юбилейных и памятных монет банков мира | Клуб Нумизмат";

$tpl['news']['data'] = $news_class->getItemsByParams($WhereParams,$tpl['pagenum'],$tpl['onpage']);

foreach ($tpl['news']['data'] as $key=>$rows){
    $tpl['news']['data'][$key]['img'] = $news_class->getImg($rows['news']);
    $news_text = mb_substr($rows['text'], 0, 350,'utf-8');

    while(substr_count($news_text,"<<<"))
    {
        $text=mb_substr($news_text,0,strpos($news_text,"<<<")).substr(strstr($news_text,">>>"),3,'utf-8');
    }

    $tpl['news']['data'][$key]['text'] = strip_tags($news_text);
    $tpl['news']['data'][$key]['namehref'] = contentHelper::strtolower_ru($rows["name"])."_n".$rows["news"].".html";
}

$tpl['paginator'] = new Paginator(array(
    'url'        => $cfg['site_dir'].'/news/',
    'count'      => $countpubs,
    'per_page'   => ($tpl['onpage']=='all')?$countpubs:$tpl['onpage'],
    'page'       => $tpl['pagenum'],
    'border'     =>4));

require_once 'filters.ctl.php';