<? 
$tpl['collector']['_Keywords'] = "нумизматика, монеты, магазин, нумизмат, коллекция, каталог монет, цены на монеты, продажа монет, бонистика, старинные монеты, куплю монеты, монеты России, медь, монеты Германии, proof, монетный двор, чеканка, серебро, альбом для монет, планшет для монет, капсулы для монет, лупы, холдеры, холдеры для монет, аксессуары для монет, аксессуары для коллекционирования, альбом для коллекции, кейс для монет, онеты, моне, деньги, старинные деньги, нумизма, нумизматик, монеты ссср цены, старинные монеты, оценка монет, стоимость монет, нумезматика, манеты, оценка монет, оценка стоимости монет, царские монеты, золотые монеты, сребряные монеты, медные монеты";
$tpl['collector']['_Description'] = "Найдите коллекционеров в своем городе, узнайте кто еще интересуется монетами на вашу тематику.";
$tpl['collector']['_Title'] = "Клуб Нумизмат | Клуб Нумизмат ";


require_once($cfg['path'].'/helpers/Paginator.php');
require_once($cfg['path'].'/models/collector.php');

$tpl['price']['errors'] = array();

$collectors_class = new model_collectors($db_class);

$city = (int)request('city');
$interest = (int)request('interest');


$tpl['pagenum'] = (int) request('pagenum');
if (!$tpl['pagenum'] ) $pagenum  = 1;
$tpl['onpage'] = 3;

/*ошибки
		-1 - не заполнено ФИО
		-2 - не заполнен email
		-3 - не заполнен текст
		-4 - не заполнен адрес
		-5 - нет пользователя
		-6 - нет пользователя в базе
		-7 - ошибка в базе данных при записи информации о пользователе
		-8 - ошибка в базе данных при записи информации как коллекционера
		>0 - все хорошо
		*/
/*ошибки
		-9 - не существует пользователя
		-10 - не существует такого пользователя
		*//*ошибки
		-1 		- не заполнено ФИО
		-2 		- не заполнене email
		-3 		- не заполнен текст
		-4 		- не заполнен адрес
		-5 		- нет пользователя
		-6 		- нет пользователя в базе
		-7 		- ошибка в базе данных при записи информации о пользователе
		-8 		- ошибка в базе данных при записи информации как коллекционера
		-111 	- ошибка при загрузке фотографии
		>0 - все хорошо
		/*ошибки
		-11 - нет номера коллекционера
		-12 - ошибка при обращении к таблицам
		-13 - нет данных
		*/
		/*ошибки
		-14 - нет данных, о пользователе
		*/
	
$whereParam['city'] = $city;
$whereParam['interest'] = $interest;

$countpubs =  $collectors_class->count_all($whereParam);
$tpl['data'] = $collectors_class->getData($whereParam,$tpl['pagenum'],$tpl['onpage']);

$addhref = "";
$sep = "?";
foreach ($whereParam as $key=>$val){
	if($val){		
		$addhref .= $sep.$key."=$val";
		$sep = "&";
	}
}

$tpl['paginator'] = new Paginator(array(
'url'        => $cfg['site_dir'].'collector/'.$addhref,
'count'      => $countpubs,
'per_page'   => ($tpl['onpage']=='all')?$countpubs:$tpl['onpage'],
'page'       => $tpl['pagenum'],
'border'     =>3));


foreach ($tpl['data'] as $i=>$rows){
	$tpl['data'][$i]['stars'] = 2;
	if ($rows["star"]>0 and $rows["star"]<=10){
		$tpl['data'][$i]['stars'] = $rows["star"];
	} elseif ($rows["star"]>10) $tpl['data'][$i]['stars'] = 10;

	$tpl['data'][$i]['city'] = $rows["city"];
	//if (ereg("^\-{0,1}[0-9]{1,}$", $rows["city"]))
	if (preg_match ('/^\-{0,1}[0-9]{1,}/',$rows["city"])){
		$tpl['data'][$i]['city'] = $city_array[$rows["city"]];
	} 
	$tpl['data'][$i]['photo'] = "";

	for ($k=1; $k<=sizeof($interests); $k++){
		$tpl['data'][$i]['intereses'][] = $k;
	}
}
?>