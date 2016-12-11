<?php

require_once $cfg['path'] . '/models/news.php';
require_once $cfg['path'] . '/configs/config_news.php';



$id = request('id');
//для инициализации фильтров

$text = '';
$years = array();
$sp_s = array();


	
$news_class = new model_news($db_class,$tpl['user']['user_id']);
$tpl['news']['errors'] = false;

$Meta = $news_class->getMeta("keywords", "text", "news='$id'", "", 0,0);


$tpl['news']['data'] = $news_class->getItem($id);

$correct_links = contentHelper::strtolower_ru($tpl['news']['data']["name"])."_n".$tpl['news']['data']["news"].".html";
if("/news/".$correct_links!=urldecode($_SERVER['REQUEST_URI'])){	
	//header('HTTP/1.1 301 Moved Permanently');
	//header('Location: '.$cfg['site_dir']."news/".$correct_links);
	//die();
} 

if($tpl['news']['data']['pagetitle']) {
	$tpl['news']['_Title'] = $tpl['news']['data']['pagetitle'];
} else {
	$tpl['news']['_Title'] = $tpl['news']['data']["name"]." | Новости нумизматики - выпуск юбилейных и памятных монет центральных банков мира | Клуб Нумизмат";
}
if($tpl['news']['data']['description']){
	$tpl['news']['_Description'] = $tpl['news']['data']['description'];
} else {
	$tpl['news']['_Description'] = 'Последние новости нумизматики.'.$Meta[1];
}

$tpl['news']['data']['text'] = preg_replace_callback('#(<img\s(?>(?!src=)[^>])*?src=")(.*?)("[^>]*>)#', "data_to_img", $tpl['news']['data']['text']);
$tpl['news']['data']['text'] = str_replace('</div><div class="sep"></div><div class="news-img">','</div><div class="news-img">',$tpl['news']['data']['text']);
$tpl['news']['data']['text'] = str_replace('<img ','<img alt="Фото '.mb_substr($tpl['news']['data']['name'],0,20,'utf8').'" title="Изображение '.mb_substr($tpl['news']['data']['name'],0,60,'utf8').'" ',$tpl['news']['data']['text']);
//$tpl['news']['data']['text'] = str_replace('<img src=','<img alt="Фото '.mb_substr($tpl['news']['data']['name'],0,20,'utf8').'" title="Изображение '.mb_substr($tpl['news']['data']['name'],0,60,'utf8').'" src=>',$tpl['news']['data']['text']);


$keywords = $tpl['news']['data']['keywords'];

$tpl['news']['data']["group"] = 0;
$tpl['news']['data']["group_title"] ="";
$groups = $news_class->getGroups($id); 

if($groups){
    $tpl['news']['data']["group"] = $groups[0]['group'];
    $tpl['news']['data']["group_title"] = $groups[0]['name'];
}
  
$source =  parse_url($tpl['news']['data']['source']);
if($source&&$source['host'])  $tpl['news']['data']['source'] = 'http://'.$source['host'];

/*var_dump(preg_match('#(<img\s(?>(?!src=)[^>])*?src=")(.*?)("[^>]*>)#',$tpl['news']['data']['text'],$res),$res);

$name = $rows["name"];
$date = $rows["date"];
$text = $rows["text"];
$typesource = $rows["typesource"];
$source = $rows["source"];
$author = $rows["author"];
$email = $rows["email"];*/

$MainFolderMenuX=4; 
$MainFolderMenuY=2;
$page=2;
if ( substr_count($_SERVER['REQUEST_URI'],".html")==0 && $news>0) {

	$namehref = strtolower_ru($name)."_n".$news.".html";
	
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: http://news.numizmatik.ru/'.$namehref);
}


function data_to_img($match){
/*<div class="image_block">
<div id="image723515" class="imageBig" style="position: absolute; display: none;">
<img class="img_hover" src="http://www.numizmatik.ru/shopcoins/images/484/484442b.jpg">
</div>
<img title="СССР | 1 рубль" src="http://www.numizmatik.ru/shopcoins/smallimages/484/484442.jpg">
</div>*/

   list(, $img, $src, $end) = $match;
   $id = str_replace('http://numizmatik.ru/news_img/', '', $src);
   $id = str_replace('.jpeg', '', $id);
  // var_dump($src);
   /*
   $path = str_replace('http://www.numizmatik.ru', '..', $src);
   list($width, $height, $type, $attr) = getimagesize($path);//local file would be pretty fast as well, probasbly need to parse real file
   $s_width = 0;
   if(preg_match_all('/width: ([0-9]{1,})/', $end, $mat))
   		$s_width = $mat[1][0];
   if($width > $s_width)
	   return $img.$src.'" id="img_01" data-zoom-image="'.$src."$end";
	else
	  return $img.$src.'"'.$end;*/
   return '<div class="news-img">
   		     <div class="image_block">
				<div id="image'.$id.'" class="imageBig" style="position: absolute; display: none">
				<img class="img_hover" src="'.$src.'">
				</div>
				<img src="'.$src.'" itemprop="image">
				</div>
		   </div><div class="sep"></div>';
}


$tpl['news']['linked'] = array();

$WhereParams = Array();
$lineked_news = array();
$themes = array();

$params = $news_class->getParams($id); 
foreach ($params as $row){
	if($row['theme']) $themes[] = $row['theme'];
}
	
if($groups) {
	foreach ($groups as $gr){		
		$WhereParams['group'][] = $gr['group'];
	}
	$news_by_group = $news_class->getItemsByParams($WhereParams,1,4);
	
	unset($WhereParams['group']);
	$i = 0;
	foreach ($news_by_group as $n){
		if($n["news"]==$id) continue;
		if(in_array($n["news"],$lineked_news)) continue;
		$tpl['news']['linked'][] = $n;
		$lineked_news[] = $n["news"];
		$i++;
		if($i==3) break;
	}  
}

if($themes) {
	foreach ($themes as $th){		
		$WhereParams['theme'][] = $th;
	}
	$news_by_theme = $news_class->getItemsByParams($WhereParams,1,5);
	
	unset($WhereParams['theme']);
}

$i = 0;
foreach ($news_by_theme as $n){
	if($n["news"]==$id) continue;
	if(in_array($n["news"],$lineked_news)) continue;
	$tpl['news']['linked'][] = $n;
	$lineked_news[] = $n["news"];
	$i++;
	if($i==2) break;
}  
$newsbyKeywords = $news_class->getNewsByKeywords($keywords,$id);

$i=0;
foreach ($newsbyKeywords as $n){
	if($n["news"]==$id) continue;
	if(in_array($n["news"],$lineked_news)) continue;
	$tpl['news']['linked'][] = $n;
	$lineked_news[] = $n["news"];
	$i++;
	if($i==2) break;
} 

$tpl['news']['byBiblio'] = $news_class->getBiblioByKeywords($keywords,$id);

$tpl['news']['show_relation'] = array();
$itemsShopcoins = array();

$shopcoins_name = '';
$shopcoins_id = 0;
    
if($tpl['news']['data']["shopcoins"]) $itemsShopcoins = $shopcoins_class->findByIds(explode(',',$tpl['news']['data']["shopcoins"]));

foreach ($itemsShopcoins as $item){
   $tpl['news']['show_relation'][] = $item;
   $shopcoins_name = $item['name'];
   $shopcoins_id = $item['shopcoins'];
}
if ($tpl['news']['data']["group"]) { 
    $relations = $shopcoins_class->getRelatedByGroup($tpl['news']['data']["group"],$shopcoins_name,$shopcoins_id);	
    foreach ($relations as $item){
       $tpl['news']['show_relation'][] = $item;
    }
}

require_once 'filters.ctl.php';