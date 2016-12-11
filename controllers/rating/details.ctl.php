<?php 
$ratinguser = (int) request('ratinguser');

$tpl['rating']['_Title'] = "Клуб Нумизмат | Рейтинг сайтов ";
$tpl['rating']['_Keywords'] = "Новости, нумизматика, фалеристика, бонистика, монеты, каталог, император, государь, ордена, медаль, значки, Петр, антиквариат, коллекция, Николай II, Екатерина II, Романовы, копейка, рубль, деньга, серебро, золото, медь, империя, ОБМЕН ВАЛЮТЫ, рефераты, Великая отечественная война";
$tpl['rating']['_Description'] = "Интернет портал включает большой каталог монет царской России с подробным описанием, возможность создать свой нумизматический интернет магазин, доска обьявлений, статьи и публикации по нумизматике и истории России, адреса магазинов торгующих антиквариатом, ссылки на другие сайты.";


if ($ratinguser){	
	
	$sql = "Select ru.name, ru.url, ru.description, rg.group, rg.name as gname, ru.keywords from ratinguser as ru 
		left join `group` as rg on ru.group = rg.group 
		where ru.ratinguser=$ratinguser;";

	$tpl['data'] = $shopcoins_class->getRowSql($sql);
	
	$tpl['rating']['_Keywords'] = $tpl['data']["keywords"]." Новости, нумизматика, фалеристика, бонистика, монеты, каталог, император, государь, ордена, медаль, значки, Петр, антиквариат, коллекция, Николай II, Екатерина II, Романовы, копейка, рубль, деньга, серебро, золото, медь, империя, ОБМЕН ВАЛЮТЫ, рефераты, Великая отечественная война";
	$tpl['rating']['_Description'] = $tpl['data']['description']." Интернет портал включает большой каталог монет царской России с подробным описанием, возможность создать свой нумизматический интернет магазин, доска обьявлений, статьи и публикации по нумизматике и истории России, адреса магазинов торгующих антиквариатом, ссылки на другие сайты.";
	$tpl['rating']['_Title'] = "Клуб Нумизмат | Рейтинг сайтов | ".$tpl['data']['name'];

	$sql = "select `group`, name from `group` where `type`='rating';";
	
	$tpl['groups'] = $shopcoins_class->getDataSql($sql);
	if($tpl['data']){
		//выбираем максимум за месяц
		$timenow = time() - 31*86400;
		$timenow = mktime(0, 0, 0, date("m", $timenow), date("d", $timenow), date("Y", $timenow));
		$sql = "select max(hit) as hit from ratingbydate where ratinguser=".$ratinguser." and date>=$timenow;";
		$max =  $shopcoins_class->getOneSql($sql);
	
		$sql = "select FROM_UNIXTIME(date, \"%d.%m\") as n, host, hit, 
		round(300*host/$max) as size1, 
		(round(300*hit/$max) - round(300*host/$max)) as size2, 
		(300 -  round(300*hit/$max)) as size3
		from ratingbydate where ratinguser=".$ratinguser." and date>=$timenow order by date desc;";
		$tpl['stat'] = $shopcoins_class->getDataSql($sql);
	} else {
		$tpl['rating']['error'] = true;
	}

}