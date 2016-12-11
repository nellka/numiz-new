<? 
$tpl['breadcrumbs'][] = array(
	    	'text' => 'Главная',
	    	'href' => $cfg['site_dir'],
	    	'base_href' =>$cfg['site_dir']
);
$tpl['breadcrumbs'][] = array(
	    	'text' => 'Вопрос недели',
	    	'href' => $cfg['site_dir'].'weekquestion',
	    	'base_href' =>'weekquestion'
);
require_once($cfg['path'].'/helpers/Paginator.php');

$weekquestion = (int) request('weekquestion');
$answer = request('answer');
$weekquestionsubmit = request('weekquestionsubmit');

$pagenum = (int) request('pagenum');
	
if(!$pagenum) $pagenum = 1;

$onpage = 15;

$check = 0; 

if (($weekquestion) and ($weekquestionsubmit) and ($answer)){
	if (!$cookiesweekquestion){
		$sql = "update weekquestion set vote".$answer."=vote".$answer." + 1 where weekquestion=$weekquestion and type='$type';";
		$result = mysql_query($sql);
		$day=7-date("w");if ($day==7) $day=0;
		if ($browser!="ie")	{
			setcookie("cookiesweekquestion", $weekquestion, time() + 86400 * $day);
		} else {
			setcookie("cookiesweekquestion", $weekquestion, time() + 86400 * $day, '/', $domain);
		}
		if ($tpl['user']['user_id']) {
			$sql = "update user set star=star+2 where user='$cookiesuser';";
			$result = mysql_query($sql);
		}
		$check = 1;
	} else {
		$check = 2;
	}
}

if ( substr_count($_SERVER['REQUEST_URI'],".html")==0 && $weekquestion>0 && !$weekquestionsubmit) {

	$sql = "select * from weekquestion where weekquestion='".intval($weekquestion)."';";
	$result = mysql_query($sql);
	$rows = $shopcoins_class->getRowSql($sql);
		
	$namehref = contentHelper::strtolower_ru($rows['question'])."_n".$rows['tboard']."_p".$pagenum."_s1.html";
	
	//header('HTTP/1.1 301 Moved Permanently');
	//header('Location: http://'.$_SERVER['HTTP_HOST'].'/weekquestion/'.$namehref);
}


if ($weekquestion) {
	
	$sql = "select question, answer1, answer2, answer3, answer4, answer5 from weekquestion where weekquestion=$weekquestion and type='week';";
	$rows = $shopcoins_class->getRowSql($sql);

	$tpl['breadcrumbs'][] = array(
	    	'text' => $rows['question'],
	    	 'href' => '',
	    	'base_href' =>''
	);

	$name = $rows['question'];
	$keywords = $rows['question'];
	$description = $rows['answer1'].", ".$rows['answer2'].", ".$rows['answer3'].", ".$rows['answer5'].", ".$rows['answer5'];
	
	$tpl['weekquestion']['_Title'] = "".$name." Вопрос недели | Клуб Нумизмат";
	$tpl['weekquestion']['_Keywords'] = $keywords.", нумизматика, фалеристика, бонистика, монеты, каталог, император, государь, ордена, медаль, значки, Петр, антиквариат, коллекция, Николай II, Екатерина II, Романовы, копейка, рубль, деньга, серебро, золото, медь, империя, ОБМЕН ВАЛЮТЫ, рефераты, Великая отечественная война";
	$tpl['weekquestion']['_Description']= $description." Интернет портал включает большой каталог монет царской России с подробным описанием, возможность создать свой нумизматический интернет магазин, доска обьявлений, статьи и публикации по нумизматике и истории России, адреса магазинов торгующих антиквариатом, ссылки на другие сайты.";
} else {
	$tpl['weekquestion']['_Title'] = "".$name." Вопрос недели | Клуб Нумизмат";
	$tpl['weekquestion']['_Keywords'] = "нумизматика, фалеристика, бонистика, монеты, каталог, император, государь, ордена, медаль, значки, Петр, антиквариат, коллекция, Николай II, Екатерина II, Романовы, копейка, рубль, деньга, серебро, золото, медь, империя, ОБМЕН ВАЛЮТЫ, рефераты, Великая отечественная война";
	$tpl['weekquestion']['_Description']= "Интернет портал включает большой каталог монет царской России с подробным описанием, возможность создать свой нумизматический интернет магазин, доска обьявлений, статьи и публикации по нумизматике и истории России, адреса магазинов торгующих антиквариатом, ссылки на другие сайты.";

}

if ($check == 1){
	$tpl['weekquestion']['error'] = "<b>Спасибо, Ваш голос принят!</b><br><br>";
} elseif ($check == 2) {
	$tpl['weekquestion']['error'] = "<b>Извините, но Вы уже голосовали на этой неделе</b><br><br>";
}

//Number of quetions per page.
$questionsPerPage 	= 20;

$sql 		= "SELECT count(*) FROM weekquestion WHERE `check`=1 AND type='week'";
$count = $shopcoins_class->getOneSql($sql);

$tpl['rows']['sum'] = 0;

if ($weekquestion){
	$sql = "select FROM_UNIXTIME(datestart, \"%d.%m.%Y\") as d1, FROM_UNIXTIME(dateend, \"%d.%m.%Y\") as d2,
	question, answer1, answer2, answer3, answer4, answer5, vote1, vote2, vote3, vote4, vote5 
	from weekquestion where weekquestion='$weekquestion' and `check`=1 and datestart<".time()." and type='week' order by dateend desc;";
	$tpl['rows'] = $shopcoins_class->getRowSql($sql);
	$tpl['rows']['sum'] = $tpl['rows']['vote1'] + $tpl['rows']['vote2'] + $tpl['rows']['vote3'] + $tpl['rows']['vote4'] + $tpl['rows']['vote5'];
	if(!$tpl['rows']['sum']) $tpl['weekquestion']['error'] = "Число проголосовавших: 0";
}


$sql = "SELECT weekquestion, question, FROM_UNIXTIME(datestart, \"%d.%m.%Y\") as d1, FROM_UNIXTIME(dateend, \"%d.%m.%Y\") as d2 FROM weekquestion where `check`=1 and `type`='week' and datestart<'".time()."' order by `dateend` desc LIMIT ".($questionsPerPage * ($pagenum-1)).",".$questionsPerPage;  

$tpl['data']  = $shopcoins_class->getDataSql($sql);

$tpl['paginator'] = new Paginator(array(      
				        'url'        => $cfg['site_dir']."weekquestion",
				        'count'      => $count,
				        'per_page'   => $onpage,
				        'page'       => $pagenum,
				        'border'     =>3));
