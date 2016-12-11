<? 
$tpl['biblio']['_Description'] = 'Нумизматическая библиотека - интересные для любого коллекционера статьи, обзоры и аналитические материалы. '.$Meta[1];
$tpl['biblio']['_Title'] = "Клуб Нумизмат | Распознавание монет";
$tpl['biblio']['_Keywords'] = "нумизматика, монеты, магазин, нумизмат, коллекция, каталог монет, цены на монеты, продажа монет, бонистика, старинные монеты, куплю монеты, монеты России, медь, монеты Германии, proof, монетный двор, чеканка, серебро, альбом для монет, планшет для монет, капсулы для монет, лупы, холдеры, холдеры для монет, аксессуары для монет, аксессуары для коллекционирования, альбом для коллекции, кейс для монет, онеты, моне, деньги, старинные деньги, нумизма, нумизматик, монеты ссср цены, старинные монеты, оценка монет, стоимость монет, нумезматика, манеты, оценка монет, оценка стоимости монет, царские монеты, золотые монеты, сребряные монеты, медные монеты";

//показ всех тагов
$sql = "desc classificator;";

$result = $shopcoins_class->getDataSql($sql);

foreach ($result as $rows){
	if (strpos($rows["Field"], "tag") !== false)
	{
		$sumTag[] = $rows["Field"];
		$TagSumStr[] = "sum(".$rows["Field"].") as Sum".$rows["Field"];
	}
}

if (sizeof($sumTag)>0)
{
	$sql = "select ".implode(",", $TagSumStr)." from classificator where `check`='1';";
	$TagF = $shopcoins_class->getRowSql($sql);
	
	$TagFA = array();
	foreach ($sumTag as $key=>$value){
		
		if (!in_array($TagF["Sum".$value], $TagFA) and $TagF["Sum".$value]!=0)
			$TagFA[] = $TagF["Sum".$value];
	}

	if (sizeof($TagFA)){
		
		rsort($TagFA);
		
		$FontTag = Array();
		$i = 0;
		foreach ($TagFA as $key=>$value) {
			$FontTag[$value] = $i;
			$i++;
		}
		
		$maxT = $TagFA[0];
		$minT = $TagFA[sizeof($TagFA)-1];
		
		if ($maxT != $minT)
			$step = 150/($maxT-$minT);
		else
			$step = 0;
		
		//выбираем 5 штук
		//вычисляем количество повторений для размера
		$MaxFont = $i + 12;
	}
}

//выборка всех тагов
$sql = "select * from classificatortag order by name;";
$result = $shopcoins_class->getDataSql($sql);
$tpl['classificator']['script_tags'] = array();

foreach ($result as $rows ){
	//сразу заганяем в массив Javascript
	if ($TagF["Sumtag".$rows["classificatortag"]]!=0){
		$tpl['classificator']['script_tags'][$rows["classificatortag"]] = str_replace('"', '', $rows["name"]);
	}
}

$addhref = "?";