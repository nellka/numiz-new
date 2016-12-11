<? 

$tpl['rating']['_Title'] = "Клуб Нумизмат | Рейтинг";
$tpl['rating']['_Keywords'] = "Новости, нумизматика, фалеристика, бонистика, монеты, каталог, император, государь, ордена, медаль, значки, Петр, антиквариат, коллекция, Николай II, Екатерина II, Романовы, копейка, рубль, деньга, серебро, золото, медь, империя, ОБМЕН ВАЛЮТЫ, рефераты, Великая отечественная война";
$tpl['rating']['_Description'] = "Интернет портал включает большой каталог монет царской России с подробным описанием, возможность создать свой нумизматический интернет магазин, доска обьявлений, статьи и публикации по нумизматике и истории России, адреса магазинов торгующих антиквариатом, ссылки на другие сайты.";

$sql = "select `group`, name from `group` where `type`='rating';";

$tpl['groups'] = $shopcoins_class->getDataSql($sql);

$users = array();

foreach ($_REQUEST as $key=>$value){
	$dk = explode("ratinguser", $key);
	if(count($dk)==2) $users[] = $dk[1];
	
}
$user_string ='';
//разбор переменных
foreach ($users AS $i=>$user){	
	$user_string .='&users[]='.$user;
	//сразу строим запрос на максимум
	if ($i>0) {
		$max_q  .= " or ratinguser=".$user." ";
	} else {
		$max_q = "ratinguser=".$user." ";
	}
}

if (COUNT($users)) {
	$sql = "Select ratinguser, name, url, description from ratinguser where ".$max_q.";";	
	$tpl['result'] = $shopcoins_class->getDataSql($sql);
}