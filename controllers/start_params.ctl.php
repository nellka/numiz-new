<?
$order = (integer)(isset($_REQUEST['order'])?$_REQUEST['order']:0);
$shopcoins = null;

//если пользователь логинится
$userlogin = isset($_REQUEST['userlogin'])?$_REQUEST['userlogin']:"";
$userpassword = isset($_REQUEST['userpassword'])?$_REQUEST['userpassword']:"";

$shopcoins = isset($_REQUEST['shopcoins'])?$_REQUEST['shopcoins']:0;

$materialtype = (integer)(isset($_REQUEST['materialtype'])?$_REQUEST['materialtype']:'');
$onpage = (integer)(isset($_REQUEST['onpage'])?$_REQUEST['onpage']:'');
$search = isset($_REQUEST['search'])?$_REQUEST['search']:'';
$recoins = isset($_REQUEST['recoins'])?$_REQUEST['recoins']:'';
$member = isset($_REQUEST['member'])?$_REQUEST['member']:'';
$savesearch = isset($_REQUEST['savesearch'])?$_REQUEST['savesearch']:'';
$searchid = isset($_REQUEST['searchid'])?$_REQUEST['searchid']:'';
$smallcoinsshow = isset($_COOKIE['smallcoinsshow'])?$_COOKIE['smallcoinsshow']:'';
$setcoinsshow = isset($_COOKIE['setcoinsshow'])?$_COOKIE['setcoinsshow']:'';
$page= isset($_REQUEST['page'])?$_REQUEST['page']:'';
$parent= isset($_REQUEST['parent'])?$_REQUEST['parent']:'';
$nocheck = isset($_REQUEST['nocheck'])?$_REQUEST['nocheck']:'';
$pricestart  = isset($_REQUEST['pricestart'])?$_REQUEST['pricestart']:'';
$priceend = isset($_REQUEST['priceend'])?$_REQUEST['priceend']:'';
$theme = isset($_REQUEST['theme'])?$_REQUEST['theme']:'';
$series = isset($_REQUEST['series'])?$_REQUEST['series']:'';
$yearstart  = isset($_REQUEST['yearstart'])?$_REQUEST['yearstart']:'';
$yearend  = isset($_REQUEST['yearend'])?$_REQUEST['yearend']:'';
$metal  = isset($_REQUEST['metal'])?$_REQUEST['metal']:'';
$group = isset($_REQUEST['group'])?$_REQUEST['group']:'';
$shopcoinsorderamount = isset($_REQUEST['shopcoinsorderamount'])?$_REQUEST['shopcoinsorderamount']:'';
$condition = isset($_REQUEST['condition'])?$_REQUEST['condition']:'';
$yearsearch = isset($_REQUEST['yearsearch'])?$_REQUEST['yearsearch']:'';
$searchname = isset($_REQUEST['searchname'])?$_REQUEST['searchname']:'';
$shopcoinsmain = isset($_POST['shopcoinsmain'])?$_POST['shopcoinsmain']:'';
$inbascetmain = isset($_POST['inbascetmain'])?$_POST['inbascetmain']:'';

$pagenum = isset($_REQUEST['pagenum'])?$_REQUEST['pagenum']:'';
$orderby = isset($_REQUEST['orderby'])?$_REQUEST['orderby']:'';
$catalog = isset($_REQUEST['catalog'])?$_REQUEST['catalog']:'';
$coinssearch= isset($_REQUEST['coinssearch'])?$_REQUEST['coinssearch']:'';

if ($search && substr_count($_SERVER['HTTP_REFERER'],"yandex")>0)
	$search = iconv("UTF-8", "CP1251//IGNORE", $search);
	/*
if (!$shopcoins && (!isset($_SESSION['shopcoins'])&&!$_SESSION['shopcoins']) && (!isset($_COOKIE['shopcoins'])&&!$_COOKIE['shopcoins'])){
	$shopcoins=0;
}
*/
	$shopcoins=0;
$IsCompletePagePHP = 1;

$arraynewcoins = Array(1=>date('Y')-2,2=>date('Y')-1,3=>date('Y'));

//$show50 = (($cookiesuser == 811 || $user_remote_address  == "94.79.50.94") && $search)?1:0;

$tpl['IsCompletePagePHP'] = 1;
$tpl['show50'] = 0;
$tpl['arraynewcoins'] = $arraynewcoins;
$tpl['shopcoins'] = $shopcoins;

if (!$materialtype && !$searchid && !$search){
	$materialtype = 1;
}

if ($parent && !$catalog) {
	$parent = intval($parent);
	$catalog = $parent;
}
?>