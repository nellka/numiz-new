<?
$MainText = "";
$GroupNameMain = "";
$GroupName = "";

if ($materialtype==1 || $materialtype==12){
		$tpl['title'] = "Монеты".($GroupNameMain?" $GroupNameMain":"").($GroupName?" $GroupName ":" со всего мира ")." ".($searchname?urldecode($searchname):"").($metal?" ".urldecode($metal):"")."".($theme?" ".$ThemeArray[$theme]:"")." стоимость | Магазин монет: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты, монеты".($GroupName?" $GroupName ":" России ")."| Монетная лавка | Клуб Нумизмат";
	}elseif ($materialtype==10 )	{
		$tpl['title'] = "Нотгельды".($GroupNameMain?" $GroupNameMain":"").($GroupName?" $GroupName ":" со всего мира ")." ".($searchname?urldecode($searchname):"").($metal?" ".urldecode($metal):"")."".($theme?" ".$ThemeArray[$theme]:"")." стоимость | Магазин монет: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты, монеты".($GroupName?" $GroupName ":" России ")."| Монетная лавка | Клуб Нумизмат";
	}
	elseif ($materialtype==8)
	{
		$tpl['title'] = "Дешевые монеты".($GroupNameMain?" $GroupNameMain":"").($GroupName?" $GroupName":" со всего мира ")." ".($searchname?urldecode($searchname):"").($metal?" ".urldecode($metal):"")."".($theme?" ".$ThemeArray[$theme]:"")." цены | Магазин монет: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты, монеты".($GroupName?" $GroupName ":" России ")."| Монетная лавка | Клуб Нумизмат";	
	}
	elseif ($materialtype==7)
	{
		$tpl['title'] = "Наборы монет".($GroupNameMain?" $GroupNameMain":"").($GroupName?" $GroupName":" со всего мира ")." ".($searchname?urldecode($searchname):"").($metal?" ".urldecode($metal):"")."".($theme?" ".$ThemeArray[$theme]:"")." стоимость | Магазин монет: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты,".($GroupName?" $GroupName ":" России ")."| Монетная лавка | Клуб Нумизмат";	
	}
	elseif ($materialtype==4)
	{
		$tpl['title'] = "Подарочные наборы монет".($GroupNameMain?" $GroupNameMain":"").($GroupName?" $GroupName":" со всего мира ")." ".($searchname?urldecode($searchname):"").($metal?" ".urldecode($metal):"")."".($theme?" ".$ThemeArray[$theme]:"")." стоимость | Магазин монет: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты, монеты".($GroupName?" $GroupName ":" России ")."| Монетная лавка | Клуб Нумизмат";	
	}
	elseif ($materialtype==2)
	{
		$tpl['title'] = "Купить банкноты (боны) со всего мира,   стоимость | Магазин банкнот: продажа монет, бон (банкнот), купить банкноты старой России | Монетная лавка | Банкноты продажа | Клуб Нумизмат";
		$_DescriptionShopcoins = 'Продажа банкнот и бон со всего мира. Купить банкноты в нашем интернете магазине. Каталог. Цены.';
		$_Keywords = 'купить банкноты, купить боны, продажа банкнот и бон. Купить банкноты интернет магазин. Магазин банкнот.';
		//$tpl['title'] = "Банкноты(боны)".($GroupNameMain?" $GroupNameMain":"").($GroupName?" $GroupName":" со всего мира ")." ".($searchname?urldecode($searchname):"").($theme?" ".$ThemeArray[$theme]:"")." стоимость | Магазин банкнот: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты, монеты".($GroupName?" $GroupName ":" России ")."| Монетная лавка | Клуб Нумизмат";	
	}
	elseif ($materialtype==6)
	{
		$tpl['title'] = "Купить цветные монеты со всего мира,   стоимость | Магазин цветных монет: продажа цветных монет России | Монетная лавка | Цветные монеты продажа | Клуб Нумизмат";
		$_DescriptionShopcoins = 'Продажа цветных монет со всего мира. Купить цветные монеты в нашем интернете магазине. Каталог. Цены.';
		$_Keywords = 'купить цветные монеты, продажа цветных красивых монет. Купить цветные монеты интернет магазин. Магазин цветных монет.';
		//$tpl['title'] = "Банкноты(боны)".($GroupNameMain?" $GroupNameMain":"").($GroupName?" $GroupName":" со всего мира ")." ".($searchname?urldecode($searchname):"").($theme?" ".$ThemeArray[$theme]:"")." стоимость | Магазин банкнот: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты, монеты".($GroupName?" $GroupName ":" России ")."| Монетная лавка | Клуб Нумизмат";	
	}
	elseif ($materialtype==5)
	{
		$tpl['title'] = "Книги по нумизматике стоимость | Магазин нумизмата: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты, монеты России | Монетная лавка | Клуб Нумизмат";	
	}
	elseif ($materialtype==3)
	{
		$tpl['title'] = "Аксессуары для нумизмата ".($GroupName?"- $GroupName -":"")." ".($searchname?"- ".urldecode($searchname)." - "." ":"")."стоимость | Магазин коллекционера: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты, монеты России | Монетная лавка | Клуб Нумизмат";	
	}
	elseif ($search == 'revaluation')
	{
		$tpl['title'] = "Распродажа монет | Монетная лавка | Клуб Нумизмат ";
	}
	elseif ($search == 'newcoins')
	{
		$tpl['title'] = "Новинки 2009-2010 | Монетная лавка | Клуб Нумизмат ";
	}
	elseif ($materialtype==9)
	{
		$tpl['title'] = "Лоты монет".($GroupNameMain?" $GroupNameMain":"").($GroupName?" $GroupName":" со всего мира ")." ".($searchname?urldecode($searchname):"").($theme?" ".$ThemeArray[$theme]:"")." стоимость | Магазин монет: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты, монеты".($GroupName?" $GroupName ":" России ")."| Монетная лавка | Клуб Нумизмат";	
	}
	else
	{
		$tpl['title'] = "Клуб Нумизмат | Монетная лавка".($GroupName?" | ".$GroupName:"");
	}
	
	if ($_SERVER['REQUEST_URI'] == "/shopcoins/?materialtype=8&group=590&search=15+%EA%EE%EF%E5%E5%EA") {
	
		$tpl['title'] = "Дешевые монеты Россия СССР  цены, продажа монет СССР | Магазин монет: продажа монет, бон(банкнот), купить монеты, золотые монеты, серебряные монеты, монеты СССР | Монетная лавка | Клуб Нумизмат";
	}
	
	if ($GroupName){
		$arraykeyword[] = $GroupName;
    }
    
	$arraykeyword[] = "монеты";
	/*
    if (sizeof($arraykeyword) && $page!="orderdetails" && $page != "submitorder" && $page!="order"){    	
    	$sql = "select *,match(`keywords`,`name`,`text`) against('".implode(" ",$arraykeyword)."' in boolean mode) as `coefficient` from shopcoinsbiblio where match(`keywords`,`name`,`text`) against('".implode(" ",$arraykeyword)."' in boolean mode) order by `coefficient` desc, shopcoinsbiblio asc limit 3;";
    	//echo $sql;
    	$result = mysql_query($sql);
    	while ($rows = mysql_fetch_array($result)) {
    	
    		$text = substr(trim(strip_tags($rows['text'])),0,600);
    		$strpos = strpos(strrev($text),".");
    		echo "<p class=txt> &nbsp;&nbsp;&nbsp;<strong>".$rows['name']."</strong><br> &nbsp;&nbsp;&nbsp;".substr($text,0,600-($strpos<200?$strpos:0))."</p><br>";
    	}
    	
    }*/
    
    
if ($materialtype==3 && $group==816) {

	$tpl['_Keywords'] = "альбомы для монет, монетные альбомы, купить альбом для монет, монеты в альбом.";
	$tpl['title'] = "Альбомы для монет";
	$tpl['_DescriptionShopcoins'] = "Клуб Нумизмат- скупка и продажа монет, оценка монет, альбомы для монет. Москва. Телефон – 8(903) 006-00-44.";
}

?>
