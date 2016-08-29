<?
require_once($cfg['path'].'/helpers/Paginator.php');
require_once($cfg['path'].'/models/catalogshopcoinsrelation.php');
require_once $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/shopcoinsdetails.php';
$details_class = new model_shopcoins_details($db_class);

$catalogshopcoinsrelation_class = new model_catalogshopcoinsrelation($db_class);

require_once $cfg['path'] . '/models/stats.php';
$stats_class = new stats($db_class,$tpl['user']['user_id'],session_id());


$page = 'show';

$show50 = 0;

$catalog=(integer)request('catalog');
$materialtype=(integer)request('materialtype');
$parent=(integer)request('parent');

if (!$materialtype) 	$materialtype = 1;
if ($parent && !$catalog) 	$catalog = $parent;

$orderusernow = 0;
$ciclelink = "";

$num = 0;
$checkuser = 0;

$arraykeyword = array();

$tpl['show']['error'] = false;
//показываем количество страниц

if ($catalog){	
	$stats_class->saveCoins($catalog);
    //стартовая инфа о монете независимо от родитея
	$rows_main = $shopcoins_class ->getItem($catalog,true);	
	$rows_main['name'] = contentHelper::nominalFormat($rows_main['name']);
	$details = $details_class->getItem($catalog);
	$rows_main["details"] =  '';
	if($details) $rows_main["details"] = $details["details"];
	
	$rows_main['metal'] = $tpl['metalls'][$rows_main['metal_id']];
	$rows_main['condition'] = $tpl['conditions'][$rows_main['condition_id']];	
	
	//подключаем ключевые слова для страницы товаров
    include($cfg['path'].'/configs/show_keywords.php');		
	
	$rows_main['mark'] = $shopcoins_class->getMarks($rows_main["shopcoins"]);
	//$tpl['show']['reviews'] = $shopcoins_class->getReviews($rows_main["shopcoins"]);
	if(!$tpl['show']['reviews']['reviewusers'] = $cache->load("Reviews_$catalog")) {	   
	    $tpl['show']['reviews']['reviewusers'] =  $shopcoins_class->getAdditionalReviews();	 
	    $cache->save($tpl['show']['reviews']['reviewusers'], "Reviews_$catalog");	 
	} 	

	$tpl['show']['rows_main'] = $rows_main;
	
	
	//тематика
	$shopcoinstheme = array();
	$ShopcoinsThemeArray = array();
	$strtheme = decbin($rows_main["theme"]);
	$strthemelen = strlen($strtheme);
	$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
	for ($k=0; $k<$strthemelen; $k++)
	{
		if ($chars[$k]==1)
		{
			$shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
			if (!in_array(($strthemelen-1-$k), $ShopcoinsThemeArray))
				$ShopcoinsThemeArray[] = ($strthemelen-1-$k);
		}
	}
	$tpl['show']['shopcoinstheme']  = $shopcoinstheme;
	//серия
	if ($rows_main["series"]){
		$rows_main["series_title"] = $shopcoins_class->getSeries($rows_main["series"]);
	}
	
	$rows_main['year'] = contentHelper::setYearText($rows_main['year'],$materialtype);
	$tpl['shop']['related'] = array();
	//сейчас показываем токо для аксессуаров
	
	
	$ourcoinsorder = array();
	if ($materialtype==3 || $materialtype==5) {
		if ($shopcoinsorder > 1) {
			$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);	
			foreach ( $result_ourorder as $rows_ourorder){
				$ourcoinsorder[] = $rows_ourorder["catalog"];
				$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
			}
		}
	}

	$statuses = $shopcoins_class->getBuyStatus($catalog,$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
	$rows_main['buy_status'] = $statuses['buy_status'];
	$rows_main['reserved_status'] = $statuses['reserved_status'];	
	$rows_main = array_merge($rows_main, contentHelper::getRegHref($rows_main));	
} else {
	$tpl['show']['error']['no_coins'] = true;
}

?>