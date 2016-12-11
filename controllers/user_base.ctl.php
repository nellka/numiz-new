<?
$ciclelink = "";
$checkuser = 0;

/*

if(isset($_COOKIE['cookiesuserdate'])) $cookiesuserdate = $_COOKIE['cookiesuserdate'];
else $cookiesuserdate = 0;
if(isset($_COOKIE['cookiesuser'])) $cookiesuser = $_COOKIE['cookiesuser'];
else $cookiesuser = 0;
$arraykeyword = array();

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

}

if (isset($_GET['shopcoinsorder'])&&$_GET['shopcoinsorder']>0)
	$shopcoinsorder = $_GET['shopcoinsorder'];
if (isset($_SESSION['shopcoinsorder'])&&$_SESSION['shopcoinsorder']>0)
	$shopcoinsorder = $_SESSION['shopcoinsorder'];
if (isset($_POST['shopcoinsorder'])&&$_POST['shopcoinsorder']>0)
	$shopcoinsorder = $_POST['shopcoinsorder'];
if (isset($_COOKIE['shopcoinsorder'])&&$_COOKIE['shopcoinsorder']>0)
	$shopcoinsorder = $_COOKIE['shopcoinsorder'];

$user_remote_address = $_SERVER['REMOTE_ADDR'] ;
$user_class = new model_user($cfg['db']);
$news_class = new model_news($cfg['db']);
//получаем информацию о балансе пользователя пользователя
$tpl['user']['is_logined'] = $user_class->is_logged_in();
$tpl['user']['user_id'] = $user_class->user_id;
$tpl['user']['remote_address'] = $user_remote_address;
$tpl['user']['username'] = $user_class->username;
//$cookiesuser = intval($cookiesuser);
$cookiesuser = $user_class->user_id;
$tmp['user']['summ'] = 0;
$tmp['user']['product_amount'] = intval($shopcoinsorderamount);
if ($tpl['user']['user_id'] && $tpl['user']['is_logined']){
    $tpl['user']['balance'] = $shopcoins->getUserBalance($tpl['user']['user_id']);
    $tmp['user']['summ'] = $tpl['user']['balance'];
	
}*/

?>