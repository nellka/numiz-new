<?php

require_once $cfg['path'] . '/models/news.php';
require_once $cfg['path'] . '/configs/config_news.php';

$id = request('id');
//для инициализации фильтров

$text = '';
$years = array();
$sp_s = array();
$themes = array();
$groups = array();

$news_class = new model_news($cfg['db'],$tpl['user']['user_id']);
$tpl['news']['errors'] = false;

$Meta = $news_class->getMeta("keywords", "text", "news='$id'", "", 0,0);


$tpl['news']['_Description'] = 'Последние новости нумизматики.'.$Meta[1];
$tpl['news']['data'] = $news_class->getItem($id);
$tpl['news']['_Title'] = $tpl['news']['data']["name"]." | Новости нумизматики - выпуск юбилейных и памятных монет центральных банков мира | Клуб Нумизмат";

$tpl['news']['data']['text'] = preg_replace_callback('#(<img\s(?>(?!src=)[^>])*?src=")(.*?)("[^>]*>)#', "data_to_img", $tpl['news']['data']['text']);
$tpl['news']['data']['text'] = str_replace('</div><div class="sep"></div><div class="news-img">','</div><div class="news-img">',$tpl['news']['data']['text']);
$keywords = $tpl['news']['data']['keywords'];

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
				<img title="СССР | 1 рубль" src="'.$src.'">
				</div>
		   </div><div class="sep"></div>';
}


$tpl['news']['byTheme'] = $news_class->getNewsByKeywords($keywords,$id);

$tpl['news']['byBiblio'] = $news_class->getBiblioByKeywords($keywords,$id);

require_once 'filters.ctl.php';