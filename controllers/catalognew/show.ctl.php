<?php
require_once($cfg['path'].'/configs/config_catalognew.php');
require_once($cfg['path'].'/models/catalognew.php');
$catalognew_class = new model_catalognew($db_class);

$catalog=(int)request('catalog');
$submitcataloghistory = request('submitcataloghistory');

$tpl['catalognew']['subscribe'] = false;
$tpl['catalognew']['error'] = array();
$tpl['catalognew']['error']['no_coins'] = false;
$tpl['catalognew']['error']['text'] = false;

$tpl['catalognew']['show']['offers'] =  array();

if ($catalog){
	$rows_main = $catalognew_class ->getItem($catalog,true);
	if($catalognew_class->getMyCatalogSubscribe($tpl['user']['user_id'],$catalog)){
		$tpl['catalognew']['subscribe'] = true;
	}
}
$userData = $user_class->getUserData();

if($catalog&&$rows_main){
	 //стартовая инфа о монете независимо от родитея	   
    $rows_main['year'] = $rows_main['yearstart'];
    $rows_main['shopcoins'] = $rows_main['catalog'];

	$correct_links = contentHelper::getRegHref($rows_main);
    $materialtype = $rows_main['materialtype'];
    
    if($tpl['user']['user_id']==352480){  
		//var_dump($cfg['site_dir']."catalognew/".$correct_links["rehref"]);
    	//die();
	}
	
    if($cfg['site_dir']."catalognew/".$correct_links["rehref"]!='http://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']){    		
		header('HTTP/1.1 301 Moved Permanently');
    	header('Location: '.$cfg['site_dir']."catalognew/".$correct_links["rehref"]);
    	die();
	}   


    if ($materialtype==1 ) {
        $title = "Каталог монет - заказать монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")." | Клуб Нумизмат";
        if ($rows_main['group']==407)
            $tpl['catalognew']['show']['textocenka'] = "<p class=txt>Есть такая монета ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."? <a href=../ocenka-stoimost-monet title=\"Оценим монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."\">Оценка этой монеты</a> в Москве бесплатно!";
        elseif ($rows_main['group']==399)
            $tpl['catalognew']['show']['textocenka'] = "<p class=txt>Интересует монета ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."? Нужно узнать <a href=../ocenka-stoimost-monet title=\"Оценим монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."\">стоимость монеты</a>? Звоните!";
        elseif ($rows_main['group']==409)
            $tpl['catalognew']['show']['textocenka'] = "<p class=txt>Желаете оценить монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."? <a href=../ocenka-stoimost-monet title=\"Оценим монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."\">Оценка стоимости данной монеты</a> в Москве.";
        elseif ($rows_main['group']==384)
            $tpl['catalognew']['show']['textocenka'] = "<p class=txt>Есть такая монета ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."? <a href=../ocenka-stoimost-monet title=\"Оценим монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."\">Оценка стоимости данной монеты</a> бесплатно в Москве!";
        elseif ($rows_main['group']==387)
            $tpl['catalognew']['show']['textocenka'] = "<p class=txt>Нужно продать монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."? <a href=../ocenka-stoimost-monet title=\"Оценим монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."\">Оценим монету</a> и купим у вас по оптимальной цене.";
        else
            $tpl['catalognew']['show']['textocenka'] = "<p class=txt>Есть такая монета ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."? Вам нужна <a href=../ocenka-stoimost-monet title=\"Оценим монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."\">оценка монеты</a> в Москве? Звоните.";
    }    elseif ($materialtype==8)  {
        $title = "Каталог дешевых монет - заказать монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")." | Клуб Нумизмат";
        $tpl['catalognew']['show']['textocenka'] = "<br><p class=txt>Есть такая монета ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."? <a href=../ocenka-stoimost-monet title=\"Оценим монету ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")."\">Оценка данной монеты</a> в Москве?";
    }    elseif ($materialtype==7)    {
        $title = "Каталог наборов монет - заказать набор монет ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")." | Клуб Нумизмат";
        $tpl['catalognew']['show']['textocenka'] = "<br><p class=txt><a href=../ocenka-stoimost-monet title=\"Оценка монет в Москве\">Оценка монет</a> в Москве";
    }    elseif ($materialtype==4)   {
        $title = "Каталог подарочных наборов монет - заказать подарочный набор монет ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")." | Клуб Нумизмат";
        $tpl['catalognew']['show']['textocenka'] = "<br><p class=txt>Есть такой подарочный набор монет? Оценим и купим. Оценка монет производится в нащем магазине салоне. <a href=../ocenka-stoimost-monet title=\"Оценка монет бесплатно\">Оценка монет</a> бесплатно!";
    }   elseif ($materialtype==2)  {
        $title = "Каталог банкнот(бон) - выбрать банкноту(бону) ".$rows_main["gname"]." ".$rows_main["name"].($rows_main["year"]?" ".$rows_main["year"]." год":"")." | Клуб Нумизмат";
    }   else    {
        $title = "".$rows_main["gname"]." ".$rows_main["name"]." | Клуб Нумизмат";
    }

   
	$tpl['catalognew']['_Title'] = $title;
	$tpl['catalognew']['_Keywords'] = "нумизматика, монеты, магазин, нумизмат, коллекция, каталог монет, цены на монеты, продажа монет, бонистика, старинные монеты, куплю монеты, монеты России, медь, монеты Германии, proof, монетный двор, чеканка, серебро, альбом для монет, планшет для монет, капсулы для монет, лупы, холдеры, холдеры для монет, аксессуары для монет, аксессуары для коллекционирования, альбом для коллекции, кейс для монет, онеты, моне, деньги, старинные деньги, нумизма, нумизматик, монеты ссср цены, старинные монеты, оценка монет, стоимость монет, нумезматика, манеты, оценка монет, оценка стоимости монет, царские монеты, золотые монеты, сребряные монеты, медные монеты";
	$tpl['catalognew']['_Description'] = "Уникальный интерактивный каталог монет со всего мира: изображения, подробные описания, цены, возможность заказать любую монету из каталога. Монеты России, СССР, Великобритании, США, Германии и многих других стран мира."; 

	
	$ciclelink = '';
	$rowscicle = $catalognew_class->getResultcicle($catalog);

	if ($rowscicle) {	
		$ciclelink = "<p class=txt> <strong>Похожие позиции в каталоге:</strong><br>
		<a href=./".$rowscicle['reff1']." title='".$rowscicle['title1']."'>".$rowscicle['title1']."</a> <br><a href=./".$rowscicle['reff2']." title='".$rowscicle['title2']."'>".$rowscicle['title2']."</a> <br><a href=./".$rowscicle['reff3']." title='".$rowscicle['title3']."'>".$rowscicle['title3']."</a>   </p>";
	}
			
	//Просмотр пользователей, кто работал над монетой
    $tpl['catalognew']['show']['userwork'] = array();
  
	if ($rows_main["user"]) {
		$rows_user = $user_class->getUserDataByID($rows_main["user"]);
		if ($rows_user){
			$data_user = array('user_id'   => $rows_user["user"],
							   'userlogin' => $rows_user["userlogin"],
							   'star'      => $rows_user["star"],
							   'action'    => 'Добавление в каталог');
			
			$tpl['catalognew']['show']['userwork'][] = $data_user;
		}
	}
    //показываем всех пользователей, кто работал над монетой
	$users_in_work = $catalognew_class->getUsersInWork($catalog);


	//$details .= "<br><b>Редактировали:</b> ";
	foreach ($users_in_work as $rows_user){
		$data_user = array('user_id'   => $rows_user["user"],
							   'userlogin' => $rows_user["userlogin"],
							   'star'      => $rows_user["star"],
							   'action'    => 'Редактирование');
			
		$tpl['catalognew']['show']['userwork'][] = $data_user;	
	}
	
	$users_in_cataloghistory = $catalognew_class->getUsersInCataloghistory($catalog);


	//$details .= "<br><b>Подтверждали: </b> ";
	foreach ($users_in_cataloghistory as $rows_user){
		$data_user = array('user_id'   => $rows_user["user"],
							   'userlogin' => $rows_user["userlogin"],
							   'star'      => $rows_user["star"],
							   'action'    => 'Редактирование');
			
		$tpl['catalognew']['show']['userwork'][] = $data_user;	
	}
	
	
	if ($submitcataloghistory &&$_SERVER['REQUEST_METHOD']=='POST'){
	
	    $answercataloghistory =(array)request('answercataloghistory');
	
		if (!$tpl['user']['user_id']){
		    $tpl['submitcataloghistory']['error'][] = "Только авторизованные пользователи могут делать записи";
		} elseif (!sizeof($answercataloghistory)){
		    $tpl['submitcataloghistory']['error'][] = "Выберите значения!";
		} else {
			foreach ($answercataloghistory as $key=>$value) {
				$rows = $catalognew_class->getUserFromHistory($key);
				$useradd = $rows["user"];
				
				$agreevalue = $value;
				
				if ($useradd != $tpl['user']['user_id']) {
				    $rows = $catalognew_class->getCataloghistoryagreement($key,$tpl['user']['user_id']);
					
					if (!$rows) {
					    $catalognew_class->addCataloghistoryagreement($key,$value,$tpl['user']['user_id']); 					
						$cataloghistory = $key;
						$CatalogHistoryAgreement = $catalognew_class->CatalogHistoryAgreement($catalog,$cataloghistory,$useradd);
						if($CatalogHistoryAgreement) $tpl['submitcataloghistory']['error'][] = "Информация успешно записана";
						
					} else {
						$tpl['submitcataloghistory']['error'][] =  "Вы уже голосовали";
					}
				} else {
					$tpl['submitcataloghistory']['error'][] =  "Пользователь, отредактировавший поле не имеет право голоса";
				}
			}
		}
	} 
	
	$tpl['catalognew']['show']['is_shopcoinssubbscribe'] = false;
	$tpl['catalognew']['show']['is_catalognewmycatalog'] = false;
	
	if ($tpl['user']['user_id']) {		
	    
	    	
		$rows_shopcoinssubbscribe = $catalognew_class->getMyCatalogshopcoinssubscribeItem($tpl['user']['user_id'],$catalog);	
		
		if($rows_shopcoinssubbscribe){
			$tpl['catalognew']['show']['is_shopcoinssubbscribe'] = true;
			$tpl['catalognew']['show']['shopcoinssubbscribe'] = ($rows_shopcoinssubbscribe["amount"]>1?$rows_shopcoinssubbscribe["amount"]:1);
			$tpl['catalognew']['show']['shopcoinssubbscribedate'] = $rows_shopcoinssubbscribe["dateinsert"];
		}
				
		$rows_catalognewmycatalog = $catalognew_class->getCatalognewmycatalogItem($tpl['user']['user_id'],$catalog);		

		if ($rows_catalognewmycatalog) {
		    $tpl['catalognew']['show']['is_catalognewmycatalog'] = true;
			$tpl['catalognew']['show']['catalognewmycatalog'] = $catalognewmycatalog[$rows_shopcoinssubbscribe["catalog"]] = $rows_catalognewmycatalog["catalognewmycatalog"];
		}
	}	

	$tpl['catalognew']['show']['can_edit'] = false;
	
	if ($rows_main["agreement"]==0)	{
		$tpl['catalognew']['show']['rows_agreement_catalog'] = $catalognew_class->getAgreementCatalog($catalog);
				
	} elseif ($materialtype!=3) $tpl['catalognew']['show']['can_edit'] = true;
		
	
	if ($rows_main["theme"]>0) {
		$strtheme = decbin($rows_main["theme"]);
		$strthemelen = strlen($strtheme);
		$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
		for ($k=0; $k<$strthemelen; $k++)
		{
			if ($chars[$k]==1)
				$shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
		}
		
		if (sizeof($shopcoinstheme)){
		    $rows_main["theme"] = implode(", ", $shopcoinstheme);
		}			
	}
	
	if (trim($rows_main["details"])){
		$rows_main["details"] = str_replace("\n","<br>",$rows_main["details"]);
	}
		
	//выбираем пероды чеканки
	
	$result_year = $catalognew_class->getCatalogyear($catalog);
	//$yearperiod = Array();
	$year = Array();
	$tpl['catalognew']['show']['yearperiod'] = '';
	
	foreach ($result_year as $rows_year) {
		if ($rows_year["yearstart"]>0 and $rows_year["yearstart"]>0) {
			if ($rows_year["yearend"]>$rows_year["yearstart"]) {
				$year[] = $rows_year["yearstart"]." - ".$rows_year["yearend"];
			} else {
				$year[] = $rows_year["yearstart"];
			}
		} elseif ($rows_year["yearstart"]>0) {
			$year[] = $rows_year["yearstart"];
		} elseif ($rows_year["yearend"]>0) {
			$year[] = $rows_year["yearend"];
		}
	}
	
	if (sizeof($year)>0) {
		$tpl['catalognew']['show']['yearperiod'] = implode(", ", $year);
	}
	
	$tpl['catalognew']['show']['result_price'] = $catalognew_class->getPrices($catalog);	
	
	//кто работал над монетой
	
	
	if ($rows_main["agreement"]!=0)	{
	    $tpl['catalognew']['show']['offers'] = $catalognew_class->getOffers($catalog);	
		//теперь показываем предложения
		
		if ($tpl['catalognew']['show']['offers']){		
			
			$tpl['catalognew']['show']['cataloghistoryArray'] = Array();
			
			foreach ($tpl['catalognew']['show']['offers'] as $key=>$rows){	
			    	
				if ($rows["field"]=="group") {				
					$sql_info = "select * from `group` where `group` in ('".$rows["fieldoldvalue"]."','".$rows["fieldnowvalue"]."');";
					$result_info = $shopcoins_class->getDataSql($sql_info);
					$GroupArray = array();
					foreach ($result_info as $rows_info){
						$GroupArray[$rows_info["group"]] = $rows_info["name"];
					}
					
					$tpl['catalognew']['show']['offers'][$key]['GroupArray'] = $GroupArray;					
					continue;
				} elseif ($rows["field"]=="metal"){
					$MetalArray = array();
					/*$sql_info = "select * from `shopmetal` where `metal` in ('".$rows["fieldoldvalue"]."','".$rows["fieldnowvalue"]."');";
					$result_info = $shopcoins_class->getDataSql($sql_info);					
					foreach ($result_info as $rows_info){
						$MetalArray[$rows_info["metal"]] = $rows_info["name"];
					}	*/
					if($tpl['metalls'][$rows["fieldoldvalue"]])	$MetalArray[$rows["fieldoldvalue"]] = $tpl['metalls'][$rows["fieldoldvalue"]];
					if($tpl['metalls'][$rows["fieldnowvalue"]])	$MetalArray[$rows["fieldnowvalue"]] = $tpl['metalls'][$rows["fieldnowvalue"]];
					if($tpl['user']['user_id']==352480){  
						//var_dump($rows["fieldoldvalue"],$rows["fieldnowvalue"]);
					}
					$tpl['catalognew']['show']['offers'][$key]['MetalArray'] = $MetalArray;						
					continue;
				}  elseif ($rows["field"]=="theme"){
					$shopcoinstheme = '';
					$strtheme = decbin($rows["fieldoldvalue"]);
					$strthemelen = strlen($strtheme);
					$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
					for ($k=0; $k<$strthemelen; $k++)
					{
						if ($chars[$k]==1)
							$shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
					}
					
					$tpl['catalognew']['show']['offers'][$key]['shopcoinstheme'] = implode(", ", $shopcoinstheme);		
					
					$strtheme = decbin($rows["fieldnowvalue"]);
					$strthemelen = strlen($strtheme);
					$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
					for ($k=0; $k<$strthemelen; $k++) {
						if ($chars[$k]==1)
							$shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
					}					
					$tpl['catalognew']['show']['offers'][$key]['shopcoinstheme1'] = implode(", ", $shopcoinstheme);		
					continue;
				}				
			}	
		} 
	}	
	
	//показываем 10 последних отзывов о монете
	
	$tpl['catalognew']['reviews']['reviewusers'] = $catalognew_class->getReviews($catalog);	
	//$tpl['show']['lhreg'] = "/shopcoins";
	
	$tpl['show']['lhreg'] = isset($_COOKIE['chref'])?trim($_COOKIE['chref']):(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/catalognew');

	if(substr($tpl['show']['lhreg'],-1) =='?' ) $tpl['show']['lhreg'] = substr($tpl['show']['lhreg'],0,strlen($tpl['show']['lhreg'])-1);

  $tpl['breadcrumbs'][] = array(
    	'text' => 'Каталог монет',
    	'href' => $cfg['site_dir'].'catalognew',
    	'base_href' =>'catalognew'
    );    
    $tpl['current_page'] = '';
    
    $tpl['breadcrumbs'][] = array(
    	'text' => contentHelper::$menu[$materialtype],
    	//'href' => $cfg['site_dir']."shopcoins/index.php?materialtype=$materialtype",
		'href' => urlBuild::makePrettyUrl(array('materialtype'=>$materialtype),$cfg['site_dir'].'catalognew'),
    	'base_href' =>urlBuild::makePrettyUrl(array('materialtype'=>$materialtype),$cfg['site_dir'].'catalognew')
    );
    
    if($rows_main["gname"]&&$rows_main["group"]){
         $tpl['breadcrumbs'][] = array(
        	'text' => $rows_main["gname"],
        	//'href' => $cfg['site_dir']."shopcoins/index.php?materialtype=$materialtype&group=".$rows_main["group"],
			//'href' =>urlBuild::makePrettyOfferUrl(array('materialtype'=>$materialtype,'group_id'=>$rows_main["group"]),$materialIDsRule,$ThemeArray,$tpl,$shopcoins_class),
        	//'base_href' =>"shopcoins/index.php?materialtype=$materialtype&group=".$rows_main["group"]
        	'href' => urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'group'=>array($rows_main['group']=>$rows_main['gname'])),$cfg['site_dir'].'catalognew'),
    	    'base_href' =>urlBuild::makePrettyUrl(array('materialtype'=>$materialtype,'group'=>array($rows_main['group']=>$rows_main['gname'])),$cfg['site_dir'].'catalognew')

        );
    }
    $tpl['breadcrumbs'][] = array(
    	'text' => $rows_main["name"],
    	'href' => '',
    	'base_href' =>''
    );
    
    $tpl['current_page'] = '';
    
} else {
	$tpl['catalognew']['error']['no_coins'] = true;
}

//if($errorcataloghistory) $tpl['catalognew']['error']['text'] = implode("<br>",$errorcataloghistory);

