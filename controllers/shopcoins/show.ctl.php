<?
require_once($cfg['path'].'/helpers/Paginator.php');
require_once($cfg['path'].'/models/catalogshopcoinsrelation.php');
require_once $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/shopcoinsdetails.php';
$details_class = new model_shopcoins_details($cfg['db']);

$catalogshopcoinsrelation_class = new model_catalogshopcoinsrelation($cfg['db']);
//var_dump($_SERVER);

$page = 'show';
$tpl['show']['lhreg'] = isset($_COOKIE['lhref'])?trim($_COOKIE['lhref']):(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/shopcoins');

if(substr($tpl['show']['lhreg'],-1) =='?' ) $tpl['show']['lhreg'] = substr($tpl['show']['lhreg'],0,strlen($tpl['show']['lhreg'])-1);

$arraynewcoins = Array(1=>date('Y')-2,2=>date('Y')-1,3=>date('Y'));
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
/*
if (($cookiesuserdate && (($cookiesuserdate + 24*60*60) > $timenow)) || !$cookiesuser || $shopkey || $materialtype!=1) $checkuser = 0;
else {

	$checkuser = 1;
	
	if (!$cookiesuserdate) $dateselect = time() - 30*24*60*60;
	else $dateselect = $cookiesuserdate;
	
	$sql = "select count(*)  
		from `shopcoins`, `group`, `clientselectshopcoins` 
		where shopcoins.`group`=`group`.`group` AND shopcoins.`check`='1' 
		and shopcoins.`shopcoins` = clientselectshopcoins.`shopcoins` 
		and clientselectshopcoins.`user` = '$cookiesuser' and clientselectshopcoins.`dateselect` >= '$dateselect';";
	echo $sql;
	
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$num = $rows[0];
	
	setcookie("cookiesuserdate", $timenow, time() + 30*24*60*60, "/shopcoins/", $domain);
	setcookie("cookiesuserdate", $timenow, time() + 30*24*60*60, "/shopcoins/");
	setcookie("cookiesuserdate", $timenow, time() + 30*24*60*60, "/shopcoins/", ".shopcoins.numizmatik.ru");
//	echo 'nnnnnnn = '.$num.' <br>';
}

if ($smallcoinsshow < $timenow && $materialtype==8) {

	$showdetailssmall = 1;
	setcookie("smallcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/", $domain);
	setcookie("smallcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/");
	setcookie("smallcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/", ".shopcoins.numizmatik.ru");
}
else {

	$showdetailssmall = 0;
}

if ($setcoinsshow < $timenow && $materialtype==7) {

	$showdetailsset = 1;
	setcookie("setcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/", $domain);
	setcookie("setcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/");
	setcookie("setcoinsshow", $timenow, time() + 30*24*60*60, "/shopcoins/", ".shopcoins.numizmatik.ru");
}
else {

	$showdetailsset = 0;
}
*/

//$addhref = ($materialtype?"&materialtype=$materialtype":"");
//$addhref .= ($tpl['pagenum']?"&pagenum=".$tpl['pagenum']:"");

$tpl['show']['error'] = false;
//показываем количество страниц

if ($catalog){	
    //стартовая инфа о монете независимо от родитея
    $rows_main = $shopcoins_class ->getItem($catalog,true);

	if ($rows_main) {
		$ourcoinsorder = array();

		if ($shopcoinsorder > 1) {
			$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);
			foreach ($result_ourorder as $rows_ourorder) {
				$ourcoinsorder[] = $rows_ourorder["catalog"];
				$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
			}
		}

		$rows_main['name'] = contentHelper::nominalFormat($rows_main['name']);
		$details = $details_class->getItem($catalog);
		$rows_main['details'] = '';
		if ($details) $rows_main['details'] = $details["details"];
		$rows_main['metal'] = $tpl['metalls'][$rows_main['metal_id']];
		$rows_main['condition'] = $tpl['conditions'][$rows_main['condition_id']];
		$next_coins = $shopcoins_class->getNext($catalog, $materialtype);
		//var_dump($next_coins);
		if ($next_coins) {
			$next_coins['metal'] = $tpl['metalls'][$next_coins['metal_id']];
		}
		$previos_coins = $shopcoins_class->getPrevios($catalog, $materialtype);

		if ($previos_coins) $previos_coins['metal'] = $tpl['metalls'][$previos_coins['metal_id']];
		$tpl['show']['next'] = ($next_coins) ? contentHelper::getRegHref($next_coins, $materialtype, $parent) : null;
		$tpl['show']['previos'] = ($previos_coins) ? contentHelper::getRegHref($previos_coins, $materialtype, $parent) : null;

		$statuses = $shopcoins_class->getBuyStatus($catalog, $tpl['user']['can_see'], $ourcoinsorder, $shopcoinsorder);
		$rows_main['buy_status'] = $statuses['buy_status'];
		$rows_main['reserved_status'] = $statuses['reserved_status'];
		//подключаем ключевые слова для страницы товаров
		include($cfg['path'] . '/configs/show_keywords.php');

		//кто описывал монету
		$tpl['show']['described'] = false;
		$groupselect_v2 = array();

		if ($materialtype == 11 && $tpl['user']['user_id'] && $user_class->is_user_has_premissons() && !$shopcoins_class->is_already_described($catalog)) {

			$tpl['show']['described'] = true;
			$result = $shopcoins_class->getGroupsForDescribe();

			foreach ($result as $rows) {
				$currval = array();
				$currval['label'] = $rows["name"];
				$currval['id'] = $rows['group'];
				array_push($groupselect_v2, $currval);
			}
		}

		$rows_main['mark'] = $shopcoins_class->getMarks($rows_main["shopcoins"]);
		//$tpl['show']['reviews'] = $shopcoins_class->getReviews($rows_main["shopcoins"]);
		if (!$tpl['show']['reviews']['reviewusers'] = $cache->load("Reviews_$catalog")) {
			$tpl['show']['reviews']['reviewusers'] = $shopcoins_class->getAdditionalReviews();
			$cache->save($tpl['show']['reviews']['reviewusers'], "Reviews_$catalog");
		}

		$tpl['show']['rows_main'] = $rows_main;

		//тематика
		$shopcoinstheme = array();
		$ShopcoinsThemeArray = array();
		$strtheme = decbin($rows_main["theme"]);
		$strthemelen = strlen($strtheme);
		$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
		for ($k = 0; $k < $strthemelen; $k++) {
			if ($chars[$k] == 1) {
				$shopcoinstheme[] = $ThemeArray[($strthemelen - 1 - $k)];
				if (!in_array(($strthemelen - 1 - $k), $ShopcoinsThemeArray))
					$ShopcoinsThemeArray[] = ($strthemelen - 1 - $k);
			}
		}
		$tpl['show']['shopcoinstheme'] = $shopcoinstheme;
		//серия
		if ($rows_main["series"]) {
			$rows_main["series_title"] = $shopcoins_class->getSeries($rows_main["series"]);
		}

		$rows_main['year'] = contentHelper::setYearText($rows_main['year'], $materialtype);

	}	else $tpl['show']['error']['no_coins'] = true;

	 if ($rows_main&&$rows_main['check'] == 0) {
		 require_once("show-search.ctl.php");
		 $tpl['task'] = 'showa';
	 } elseif ($rows_main) {
		$tpl['show']['rowscicle'] = $shopcoins_class->getCoinsrecicle($catalog);
		$tpl['show']['resultcicle'] = $shopcoins_class->getPopular(4, array('materialtype' => $materialtype, 'shopcoins.group' => $rows_main["group"]));

		$tmp = Array();
		$LastCatalog10_tmp = "";
		if ($LastCatalog10) {
			$tmp = explode("#", $LastCatalog10);
			$LastCatalog10_tmp = '';
			for ($i = 0; $i < (sizeof($tmp) < 10 ? sizeof($tmp) : 10); $i++) {
				unset ($tmp1);
				$tmp1 = explode("|", $tmp[$i]);
				if ($tmp1[0] != $catalog) {
					$LastCatalog10_tmp .= $tmp[$i] . "#";
				}
			}
		}

		if ($rows_main["shopcoins"]) {
			$LastCatalog10 = $rows_main["shopcoins"] . "|" . $rows_main["gname"] . "|" . $rows_main["group"] . "|" . $rows_main["materialtype"] . "|" . $rows_main["name"] . "|" . $rows_main["price"] . "|" . $rows_main["image_small"] . "|" . $rows_main["image_big"] . "#" . $LastCatalog10_tmp;
		} else {
			$LastCatalog10 = $LastCatalog10_tmp;
		}

		setcookie("LastCatalog10", $LastCatalog10, time() + 86400 * 30, "/", $domain);
		setcookie("LastCatalog10", $LastCatalog10, time() + 86400 * 30, "/shopcoins/");

		$tpl['shop']['related'] = array();
		//сейчас показываем токо для аксессуаров
		if ($materialtype == 3) {
			//показ сопутствующих товаров
			$tpl['shop']['related'] = $shopcoins_class->getRelated($catalog);

			$i = 0;
			$oldmaterialtype = 0;
			if ($tpl['shop']['related']) {
				foreach ($tpl['shop']['related'] as &$rows) {
					$rows['metal'] = $tpl['metalls'][$rows['metal_id']];
					$rows['condition'] = $tpl['conditions'][$rows['condition_id']];
					$tpl['shop']['related'][$i]['additional_title'] = '';
					if ($oldmaterialtype != $rows["materialtype"]) {
						$tpl['shop']['related'][$i]['additional_title'] = $MaterialTypeArray[$rows["materialtype"]];
						$oldmaterialtype = $rows["materialtype"];
					}
					$i++;
				}
			}
		}

		$tpl['shop']['resultp'] = $shopcoins_class->showedWith($catalog, $rows_main);
		//$tpl['shop']['resultp'][$i]['buy_status']=2;

		$tpl['shop']['result_show_relation2'] = array();
		$tpl['shop']['result_show_relation3'] = array();

		if ($rows_main['group'] && $rows_main['name']) {
			$tpl['shop']['result_show_relation2'] = $shopcoins_class->getRelatedByGroup($rows_main['group'], $rows_main['name'], $catalog);
		}

		//с этим товаром покупали так же
		if ($tpl['user']['user_id']) {
			$rows = $user_class->getRowByParams(array('user' => $tpl['user']['user_id']));
			$user = $tpl['user']['user_id'];
			$email = $rows["email"];
			$fio = $rows["fio"];
		}

		$tpl['show']['result_show_relation3'] = array();

		$mycatalog1 = $catalogshopcoinsrelation_class->getOneByParams('catalog', array('shopcoins' => $catalog));

		if ($mycatalog1) {
			$tpl['show']['result_show_relation3'] = $catalogshopcoinsrelation_class->getRelations2($catalog, $mycatalog1);
		}
	}
} else {
	$tpl['show']['error']['no_coins'] = true;
}
?>