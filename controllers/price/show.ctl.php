<?
/*if($tpl['user']['user_id']==352480){
	
}*/
$tpl['breadcrumbs'][] = array(
	    	'text' => 'Главная',
	    	'href' => $cfg['site_dir'],
	    	'base_href' =>$cfg['site_dir']
);

$tpl['breadcrumbs'][] = array(
	    	'text' => 'Стоимость монет',
	    	'href' => $cfg['site_dir'].'price',
	    	'base_href' =>'price'
);

$r_url = $cfg['site_dir']."/price";

$catalog = (int) request('catalog');
$pcondition = (int) request('pcondition');
$parent = (int) request('parent');

$tpl['user']['staruser'] = 0;

$tpl['show']['lhreg'] = isset($_COOKIE['phref'])?trim($_COOKIE['phref']):(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/price');

if(substr($tpl['show']['lhreg'],-1) =='?' ) $tpl['show']['lhreg'] = substr($tpl['show']['lhreg'],0,strlen($tpl['show']['lhreg'])-1);

require_once($cfg['path'].'/models/price.php');

$tpl['price']['errors'] = array();

$price_class = new model_priceshopcoins($db_class);


if ($catalog){
	$rows_main = $price_class->getItem($catalog);
	$tpl['breadcrumbs'][] = array(
		    	'text' => $rows_main["gname"]." | ".$rows_main["aname"]." - ".$rows_main['year']." год",
		    	'href' => "",
		    	'base_href' =>''
	);
	$rehref = urlBuild::priceCoinsUrl($rows_main);
	
	/*if("/price/".$rehref!=urldecode($_SERVER['REQUEST_URI'])){

		header('HTTP/1.1 301 Moved Permanently');
    	header('Location: '.$cfg['site_dir']."price/".$rehref);
    	die();
	}  */
		
	$tpl['price']['_Keywords'] = "нумизматика, монеты, магазин, нумизмат, коллекция, каталог монет, цены на монеты, продажа монет, бонистика, старинные монеты, куплю монеты, монеты России, медь, монеты Германии, proof, монетный двор, чеканка, серебро, альбом для монет, планшет для монет, капсулы для монет, лупы, холдеры, холдеры для монет, аксессуары для монет, аксессуары для коллекционирования, альбом для коллекции, кейс для монет, онеты, моне, деньги, старинные деньги, нумизма, нумизматик, монеты ссср цены, старинные монеты, оценка монет, стоимость монет, нумезматика, манеты, оценка монет, оценка стоимости монет, царские монеты, золотые монеты, сребряные монеты, медные монеты";
	$tpl['price']['_Description'] = "Уникальный интерактивный каталог монет со всего мира: изображения, подробные описания, цены, возможность заказать любую монету из каталога. Монеты России, СССР, Великобритании, США, Германии и многих других стран мира.";
	$tpl['price']['_Title'] = "Стоимость монет".$rows_main["gname"]." | ".$rows_main["aname"]." - ".$rows_main['year']." год | Клуб Нумизмат";

	$WhereParams = array();
	$urlParams  = array();
	$urlParams['group'] = array($rows_main['group'] => $rows_main["gname"]);
	
	$WhereParams['group'] = array($rows_main['group']);
		
	$name = (int)$rows_main['name'];
	$metal = (int)$rows_main['metal'];
	
	$searchname = $rows_mane['aname'];	
	$yearend = (int)$yearstart = $rows_main['year'];
	$simbol = (int)$rows_main['simbols'];
	
	$metals = $price_class->getMetalls($WhereParams);
    $tpl['price']['metals'] = array();
	foreach($metals as $rows){		
		$tpl['price']['metals'][$rows['metal']] = $rows['name'];
	}
		
	if ($tpl['price']['metals']) {			
		foreach ($tpl['price']['metals'] as $key=>$value) {
			$urlParams['metal'] = array($key=>$value);
			$NameArray = array();
			
			$WhereParams['metal'] = array($key);
			$nominals = $price_class->getNominals($WhereParams);			

			foreach ($nominals as $rows){
				$urlParams['nominal'] = array($rows["nominal_id"]=>$rows["name"]);

				$NameArray[] = " &nbsp;<a href=\"".urlBuild::makePrettyUrl($urlParams,$r_url)."\" title='Показать только ".$rows["name"]."'>".$rows["name"]."</a>";
			}
			$tpl['price']['nominals'][$key]['nominals']	 = $NameArray;
		}		
	}	
	
	$WhereParams['metal'] = array($metal);

	$urlParams['metal'] = array($rows_main['metal'] => $rows_main["ametal"]);
	$WhereParams['nominal'] = array($name);
	$urlParams['nominal'] = array($rows_main['name'] => $rows_main["aname"]);
	
	$tpl['back_url'] = urlBuild::makePrettyUrl($urlParams,$r_url);
	
	$years = $price_class->getYears($WhereParams);		
    $tpl['price']['years'] = array();
    
	foreach ($years as $rows) {
		$WhereParams['year'] = array($rows['year']);
		$simbols = $price_class->getSimbols($WhereParams);	

		foreach ($simbols as $rows2) {
			$tpl['price']['years'][$rows['year']][$rows2['simbol']]['name'] = $rows2['name'];
			$urlParams['simbol'] = array($rows2['simbol'] => $rows2['name']);
			$tpl['price']['years'][$rows['year']][$rows2['simbol']]['url'] = urlBuild::makePrettyUrl($urlParams,$r_url);
		}
	}
	
	
	if (!$pcondition) {	
		
		$rehref = "Монета ";				
					
		if ($rows_main['gname'])$rehref .= $rows_main['gname']." ";
		$rehref .= $rows_main['aname'];
		if ($rows['ametal'])$rehref .= " ".$rows_main['ametal']; 
		if ($rows['year'])	$rehref .= " ".$rows_main['year'];
		$namecoins = $rehref;
		
		$tpl['rehref'] = contentHelper::strtolower_ru($rehref)."_cpc".$rows_main['priceshopcoins']."_cpr".$rows_main['parent']."_pcn0.html";
		
		$sql_n = "select priceshopcoins.*, group.name as gname, `pricename`.name as aname, `pricemetal`.metal as ametal, pricesimbols.simbols as asimbols, `pricecondition`.`condition` as acondition 
		from `priceshopcoins`,`pricename`,pricemetal, pricesimbols, pricecondition,`group` 
		where priceshopcoins.parent='$parent' and `priceshopcoins`.`name`=`pricename`.`pricename` and priceshopcoins.metal=pricemetal.pricemetal and priceshopcoins.simbols=pricesimbols.pricesimbols and priceshopcoins.condition=pricecondition.pricecondition and priceshopcoins.group=group.group order by pricecondition.position;";
		//echo $sql_n;
		$tpl['result_n'] = $price_class->getDataSql($sql_n);
		$tpl['arrayprice'] = $tpl['arrayamount'] = $tpl['arraycondition'] = array();
		$condition = 0;
		foreach ($tpl['result_n'] as $rows_n) {		
			if ($condition != $rows_n['condition'] && $condition!=9) {				
				$tpl['arraycondition'][$rows_n['condition']] = $rows_n['acondition'];
				$condition = $rows_n['condition'];
			}
			
			$tpl['arrayprice'][$rows_n['acondition']] += $rows_n['priceend'];
			$tpl['arrayamount'][$rows_n['acondition']] ++;
		}		
		
	} else {
	
		$sql_n = "select priceshopcoins.*, group.name as gname, `pricename`.name as aname, `pricemetal`.metal as ametal, pricesimbols.simbols as asimbols, `pricecondition`.`condition` as acondition 
		from `priceshopcoins`,`pricename`,pricemetal, pricesimbols, pricecondition,`group` 
		where priceshopcoins.parent='$parent' and `priceshopcoins`.`name`=`pricename`.`pricename` and priceshopcoins.metal=pricemetal.pricemetal and priceshopcoins.simbols=pricesimbols.pricesimbols and priceshopcoins.condition=pricecondition.pricecondition and priceshopcoins.group=group.group and pricecondition.pricecondition='$pcondition' and priceshopcoins.check=1 order by priceshopcoins.dateend desc;";

		$tpl['result_catalog'] = $price_class->getDataSql($sql_n);
		$price = 0;
		$amount = 0;
		$pricemin = 0;
		$pricemax = 0;
		
		$tpl['price']['MyShowArray'] = array();
		
		foreach ($tpl['result_catalog'] as $rows) {

			$tpl['price']['MyShowArray'][] = $rows;
			
			if (!$pricemin || $pricemin > $rows['priceend'])
				$pricemin = $rows['priceend'];
			if ($pricemax < $rows['priceend'])
				$pricemax = $rows['priceend'];
			$price += $rows['priceend'];
			$amount ++;
		}
	
		if (sizeof($tpl['price']['MyShowArray'])==0){
			$tpl['show']['error']['no_item'] = true;			
		} else {			
			$tpl['user']['staruser'] = $user_class->getStar();	
			
		}	
	}
} else {
	$tpl['show']['error']['no_item'] = true;
}
