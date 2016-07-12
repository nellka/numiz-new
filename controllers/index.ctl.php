<?
require_once $cfg['path'] . '/models/news.php';
require_once $cfg['path'] . '/models/forum.php';
require_once $cfg['path'] . '/configs/config_news.php';
$tpl['index']['_Title'] = "Клуб Нумизмат: монеты, нумизматика монеты россии и ссср монеты из драгоценных металлов золотые монеты каталог монет куплю монеты купить монеты юбилейные монеты старинные монеты продажа монет стоимость монет монетный двор продать монеты нумизматика монеты цены  на монеты альбом  для монет покупка монет царские монеты манеты монеты мира нумизматика цены российские монеты редкие монеты";
$tpl['banners']['main_center_1'] = '<img border="0" src="'.$cfg['site_root'].'/images/banners/main_center_1.jpg">';
$tpl['banners']['main_center_2'] = '<img border="0" src="'.$cfg['site_root'].'/images/banners/main_center_2.jpg">';


//$tpl['coins']['populars_in_category'] = $shopcoins_class->getPopular(4);

$news_class = new model_news($cfg['db'],$tpl['user']['user_id']);
$forum_class = new model_forum($cfg['db'],$tpl['user']['user_id']);

$tpl['lastNews'] = $news_class->getItemsByParams(array(),1,10);

foreach ($tpl['lastNews'] as $key=>$rows){
    $tpl['lastNews'][$key]['img'] = $news_class->getImg($rows['news']);
    if(!$tpl['lastNews'][$key]['img']){       
        preg_match('#(<img\s(?>(?!src=)[^>])*?src=")(.*?)("[^>]*>)#',$rows['text'],$res);
	    $tpl['lastNews'][$key]['img'] = $res[2];
    }

	$text = mb_substr($rows['text'], 0, 350,'utf-8');

	while(substr_count($text,"<<<"))
	{
		$text=mb_substr($text,0,strpos($text,"<<<")).substr(strstr($text,">>>"),3,'utf-8');
	}

	$tpl['lastNews'][$key]['text'] = strip_tags($text);

    $tpl['lastNews'][$key]['namehref'] = $cfg['site_dir']."news/".contentHelper::strtolower_ru($rows["name"])."_n".$rows["news"].".html";
}

$tpl['lastForumNews'] = $forum_class->getLast();

foreach ($tpl['lastForumNews'] as $key=>$thread_get) {    
	$tid = $thread_get['threadid'];
	$getp = $forum_class->getPost($tid);

	$tpl['lastForumNews'][$key]['getp'] = $getp;
	$tpl['lastForumNews'][$key]["pagetext"] = str_replace("[","<",$getp["pagetext"]);
	$tpl['lastForumNews'][$key]["pagetext"] = str_replace("]",">",$tpl['lastForumNews'][$key]["pagetext"]);

}	

$sql_video = "select * from video where `check`=1 order by rand() limit 3;";
$tpl['video'] = $shopcoins_class->getDataSql($sql_video);

?>