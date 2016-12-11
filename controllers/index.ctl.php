<?	
require_once $cfg['path'] . '/models/news.php';
require_once $cfg['path'] . '/models/forum.php';
require_once $cfg['path'] . '/configs/config_news.php';
require_once($cfg['path'].'/helpers/imageMini.php');
$tpl['index']['_Title'] = "Клуб Нумизмат. Купить и продать монеты, банкноты и альбомы на любой вкус";
$tpl['index']['_Description'] = "Крупнейший портал коллекционеров. Магазин, оценка стоимости монет, библиотека, уникальный каталог монет, последние новости и форум. Заходите скорее монеты ждут вас!";
$tpl['banners']['main_center_1'] = '<img border="0" src="'.$cfg['site_root'].'/images/banners/main_center_1.jpg">';
$tpl['banners']['main_center_2'] = '<img border="0" src="'.$cfg['site_root'].'/images/banners/main_center_2.jpg">';


//$tpl['coins']['populars_in_category'] = $shopcoins_class->getPopular(4);

$news_class = new model_news($db_class,$tpl['user']['user_id']);
$forum_class = new model_forum($db_class,$tpl['user']['user_id']);

$tpl['lastNews'] = $news_class->getItemsByParams(array(),1,10);

foreach ($tpl['lastNews'] as $key=>$rows){
    $tpl['lastNews'][$key]['img'] = $news_class->getImg($rows['news'],$rows['text']);
    
    
    if($tpl['user']['user_id']==352480){
    	//var_dump($rows['news'],$tpl['lastNews'][$key]['img']);
    	//echo "<br>";
    }
    
    $tpl['lastNews'][$key]['img'] = imageMini::getMini($tpl['lastNews'][$key]['img'],"news_img/");   
    
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