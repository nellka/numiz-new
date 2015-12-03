<?
//require_once($cfg['path'].'/helpers/mobile_detect.php');
//require $cfg['path'] . '/helpers/cookiesWork.php';

require $cfg['path'] . '/controllers/start_params.ctl.php';

$tmp['addcall']['errors'] = array();
if(request('logout')){
 

	$user_class->logout();
	unset($_POST['logout']);
	//var_dump($user_class->is_logged_in(),$_POST['logout']);
	  // die();
	header("location: ".$_SERVER["REQUEST_URI"]);
}
$user_remote_address = $_SERVER['REMOTE_ADDR'] ;
$tpl['user']['remote_address'] = $user_remote_address;
//проверяем залогинивание пользователя
$tpl['user']['is_logined'] = $user_class->is_logged_in();

//получаем информацию о балансе пользователя пользователя
$tpl['user']['summ'] = 0;
$tpl['user']['product_amount'] = intval($shopcoinsorderamount);

if ($tpl['user']['is_logined']){
	//данный набор о пользователеле нужен в нескольких кусках хода, поэтому чтобы не дублировать выношу в отдельную функцию
	$user_base_data = $user_class->getUserBaseData();
	
	$tpl['user'] = array_merge($tpl['user'],$user_base_data);
} else $tpl['user']['user_id'] = 0;

include_once($cfg['path'] ."/configs/keywordsAdmin.php");
//include $_SERVER["DOCUMENT_ROOT"]."/keywords.php";

if (in_array($_SERVER["HTTP_USER_AGENT"],$black_user_agent_list[0])
|| substr_count($_SERVER["HTTP_USER_AGENT"],"coona")
|| substr_count($_SERVER["HTTP_USER_AGENT"],"Rufus")
|| substr_count($_SERVER["HTTP_USER_AGENT"],"Wget")
|| substr_count($_SERVER["HTTP_USER_AGENT"],"Robot")
|| $order=="87410"
||in_array($user_remote_address,$black_ip_list[0]))
{
    $tpl['error_text'] = 'Скачивание информации запрещено!!!Если Вы считаете что это ошибка, свяжитесь с администратором по телефону 8-926-268-41-10.';
    require $cfg['path'] . '/views/error.tpl.php';
	exit;
} 
$timenow = time();

if ($_SERVER['REQUEST_URI'] == "/?materialtype=8&group=590&search=15+%EA%EE%EF%E5%E5%EA") {    
	$textbottompage = "<p class=txt>Продажа монет СССР для истинных ценителей истории. Всегда в продаже монеты СССР любого достоинства. Звоните и заходите в наш магазин в Москве. </p>";
} else	$textbottompage = "";

$tpl['stat']['users'] = $user_class->countAll();
$tpl['stat']['items'] = $shopcoins_class->countAll();
$tpl['stat']['news'] =  $news_class->countAll();



$controller = $cfg['path'] .  '/controllers/'.$tpl['module'].'.ctl.php';
$static_page = true;

if(file_exists($controller)){
    $static_page = false;
    //для статических страниц контроллера может не быть
    require  $controller;
}
/*
if ($catalog){	
	require_once($cfg['path'] . '/controllers/catalog.ctl.php');
} else {	
// перенесли в admin keywords
	require_once($cfg['path'] . '/controllers/site_titles.ctl.php');
}*/



if($tpl["datatype"]=='json'){
	echo json_encode($tpl[$tpl['task']]);
	die();
}

if($tpl['ajax']){
    require_once $cfg['path'] . '/views/common/smallhead.tpl.php';
    require_once $cfg['path'] .  '/views/'.$tpl['module'].'.tpl.php';
    die();
}
require_once  $cfg['path'] .  '/controllers/breadcrumbs.ctl.php';
require_once  $cfg['path'] .  '/views/template.php';
die();
/*

$orderusernow = 0;
//если пользователь залогинен и запрещено делать заказы, то проверяем те заказы, которые были
//зачем на главное - под вопросом
if ($blockend < time()&& $tpl['is_logined']>0) {
    die('blockend');
		$sql_temp = "select count(*) from `order` where `user`='$cookiesuser' and `check`=1 and SendPost=0 and sum>=500;";
		$result_temp = mysql_query($sql_temp);
		$rows_temp = mysql_fetch_array($result_temp);
		if ($rows_temp[0]>0) 
			$orderusernow = 1;
		else
			$orderusernow = 0;
}

if ($recoins && $member) {
    die('recoins-member');
	$member = intval($_GET['member']);
	$arrayrecoins = explode("d",$_GET['recoins']);
	foreach ($arrayrecoins as $key=>$value) 
		$arrayrecoins[$key] = intval($value);
	
	if (sizeof($arrayrecoins)>0) {
	
		if ($_GET['shopcoinsorder']>0)
			$shopcoinsorder = $_GET['shopcoinsorder'];
		if ($_SESSION['shopcoinsorder']>0)
			$shopcoinsorder = $_SESSION['shopcoinsorder'];
		if ($_POST['shopcoinsorder']>0)
			$shopcoinsorder = $_POST['shopcoinsorder'];
		if ($_COOKIE['shopcoinsorder']>0)
			$shopcoinsorder = $_COOKIE['shopcoinsorder'];

		if ($shopcoinsorder)
			$shopcoinsorder = intval($shopcoinsorder);
			
		if ($shopcoinsorder==0) {
			
			$sql_or = "select * from `order` 
			where `check`=0 and `user`='$member' and `user`!=811 and `user`>0 and `date`>'".(time()-5*60*60)."' limit 1;";
			$result_or = mysql_query($sql_or);
			
			if (mysql_num_rows($result_or)==1) {
			
				$rows_or = mysql_fetch_array($result_or);
				$shopcoinsorder = $rows_or['order'];
				//$timecookies = $rows_or['date'] + $reservetime;
				
				setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/", $domain);
				setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/");
				setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/", ".shopcoins.numizmatik.ru");
				setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/");
				
				
				$_SESSION['shopcoinsorder'] = $shopcoinsorder;
			}
		}
		
		$sql = "select * from shopcoins where shopcoins in (".implode(",",$arrayrecoins).");";
		echo $sql;
		$result = mysql_query($sql);
		$arrayresult = array();
		while($rows = mysql_fetch_array($result)) {
			
			$error2='';
			$price = $rows['price'];
			//монета уже продана - не резервируем
			$ShopcoinsMaterialtype = $rows["materialtype"];
			
			if ($rows['group'] == 1604)
				$error2 = "notavailable";
			elseif ($ShopcoinsMaterialtype==8 || $ShopcoinsMaterialtype==7 || $ShopcoinsMaterialtype==4 || $ShopcoinsMaterialtype==2) {
				
				$sql_amount = "SELECT * FROM helpshopcoinsorder WHERE shopcoins='".$rows['shopcoins']."';";
				$result_amount = mysql_query($sql_amount);
				$amountreserve = 0;
				while ($rows_amount = mysql_fetch_array($result_amount)) {
				
					if (time()-$rows_amount["reserve"] < $reservetime ) 
						$amountreserve++;
				}
				
				if ( !$rows["amount"] ) $rows["amount"] = 1;
				
				if ($rows["amount"] <= $amountreserve || $amount > ($rows["amount"] - $amountreserve)) {
				
					$error2 = "reserved"; 
					//$erroramount = $rows["amount"];
				}
			}
			else {
			
				if ($rows["reserve"]>0 || $rows['doublereserve']>time())
				{
					//уже кто то забронировал
					if ((time()-$rows["reserve"] < $reservetime and $rows["reserveorder"] != $shopcoinsorder) || ($rows['doublereserve']>time() && $rows['userreserve']!=$cookiesuser && $cookiesuser>0))
						$error2 = "reserved";
				}
			}
			
			if (($ShopcoinsMaterialtype==3 || $ShopcoinsMaterialtype==5) and $rows["amount"] < $amount)
			{
				$error2 = "amount";
				//$erroramount = $rows["amount"];
			}
			
			if ($rows["check"] == 0) 
				$error2 = "notavailable";
				
			
				
			if (!$error2) {
				$arrayresult[] = $rows['shopcoins'];
				$ShopcoinsMaterialtypeLast[] = $rows['materialtype'];
			}
		}
	
	}
	
	if (sizeof($arrayresult)>0)
	{
		if (!$shopcoinsorder)
		{
			$sql = "insert into `order` (`order`,".($member>0?" `user`,":"")." date, type, `check`, admincheck, ip) 
			values (0,".($member>0?" '$member',":"")." ".time().", 'shopcoins', '0', '0', '$user_remote_address ');";
			$result = mysql_query($sql);
			$shopcoinsorder = mysql_insert_id();
			
			setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/shopcoins/", $domain);
			setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/shopcoins/");
			setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/shopcoins/", ".shopcoins.numizmatik.ru");
			setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/");
			
			//session_start();
			$_SESSION['shopcoinsorder'] = $shopcoinsorder;
		}
		
		foreach ($arrayresult as $key => $shopcoins) {
		
			$sql_info = "select * from orderdetails where catalog=$shopcoins and `order`='$shopcoinsorder';";
			$result_info = mysql_query($sql_info);
			$rows_info = mysql_fetch_array($result_info);
		
			if ($rows_info[0])
			{
				
				if ($ShopcoinsMaterialtypeLast[$key]==8 || $ShopcoinsMaterialtypeLast[$key]==7 || $ShopcoinsMaterialtypeLast[$key]==4 || $ShopcoinsMaterialtypeLast[$key]==2) {
				
					$amountsql = $rows_info['amount']+($amount?$amount:1);
				}
				else $amountsql = $amount;
				//кто то захотел увеличить уменьшить количество
				$sql = "update orderdetails set amount='$amountsql'
				where `order`='$shopcoinsorder' and catalog=$shopcoins;";
				$result = mysql_query($sql);
			}
			else
			{
				//добавляем первый раз данный продукт в заказ
				$sql = "insert into orderdetails (orderdetails, `order`, `date`, `catalog`, `amount`, typeorder)
				values (0, '".$shopcoinsorder."', '".time()."', '$shopcoins', '".(!$amount?"1":$amount)."', '1');";
				$result = mysql_query($sql);
			}
			
			//для единичных товаров - amount = 1
			if ($ShopcoinsMaterialtypeLast[$key] == 1 || $ShopcoinsMaterialtypeLast[$key] == 10 || $ShopcoinsMaterialtypeLast[$key] == 11 || $ShopcoinsMaterialtypeLast[$key] == 9 || $ShopcoinsMaterialtypeLast[$key] == 12) {
				
				$sql_update = "update shopcoins set reserve='".time()."', reserveorder='$shopcoinsorder', doubletimereserve='0', userreserve='0' 
				where shopcoins=$shopcoins;";
				$result_update = mysql_query($sql_update);
				$_SESSION["shopcoinsorderamount"] =  intval($_SESSION["shopcoinsorderamount"])+1;
			}
			elseif ($ShopcoinsMaterialtypeLast[$key] == 4 || $ShopcoinsMaterialtypeLast[$key] == 7 || $ShopcoinsMaterialtypeLast[$key] == 8 || $ShopcoinsMaterialtypeLast[$key]==2) {
			
				for ($i=0;$i<$amount;$i++) {
					$sql_update = "insert into helpshopcoinsorder set helpshopcoinsorder=0, reserve='".time()."', reserveorder='$shopcoinsorder', shopcoins=$shopcoins;";
					$result_update = mysql_query($sql_update);
				}
				$_SESSION["shopcoinsorderamount"] =  intval($_SESSION["shopcoinsorderamount"])+($amount?$amount:1);
			}
		}
		
		Bascet($shopcoinsorder);
	}
}

if ($shopcoinsmain>0 && $inbascetmain ==1) {
  die('shopcoinsmain-inbascetmain');
	if ($_GET['shopcoinsorder']>0)
		$shopcoinsorder = $_GET['shopcoinsorder'];
	if ($_SESSION['shopcoinsorder']>0)
		$shopcoinsorder = $_SESSION['shopcoinsorder'];
	if ($_POST['shopcoinsorder']>0)
		$shopcoinsorder = $_POST['shopcoinsorder'];
	if ($_COOKIE['shopcoinsorder']>0)
		$shopcoinsorder = $_COOKIE['shopcoinsorder'];
	
	$shopcoinsmain = $_POST["shopcoinsmain"];
	
	if (!$shopcoinsmain)
		$error = "noshopcoins";
	else
		$shopcoinsmain = intval($shopcoinsmain);
		
	if ($amount)
		$amount = intval($amount);
	else
		$amount = 1;
	//проверки
	$sql = "select * from shopcoins where shopcoins='$shopcoinsmain';";
	//echo $sql;
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$price = $rows['price'];
	//монета уже продана - не резервируем
	$ShopcoinsMaterialtype = $rows["materialtype"];
	
	if ($rows['group'] == 1604)
		$error = "notavailable";
	elseif ($ShopcoinsMaterialtype==8 || $ShopcoinsMaterialtype==7 || $ShopcoinsMaterialtype==4 || $ShopcoinsMaterialtype==2) {
		
		$sql_amount = "SELECT * FROM helpshopcoinsorder WHERE shopcoins='".$shopcoinsmain."';";
		$result_amount = mysql_query($sql_amount);
		$amountreserve = 0;
		while ($rows_amount = mysql_fetch_array($result_amount)) {
		
			if (time()-$rows_amount["reserve"] < $reservetime ) 
				$amountreserve++;
		}
		
		if ( !$rows["amount"] ) $rows["amount"] = 1;
		
		if ($rows["amount"] <= $amountreserve || $amount > ($rows["amount"] - $amountreserve)) {
		
			$error = "reserved"; 
			$erroramount = $rows["amount"];
		}
	}
	else {
	
		if ($rows["reserve"]>0 || $rows['doublereserve']>time())
		{
			//уже кто то забронировал
			if ((time()-$rows["reserve"] < $reservetime and $rows["reserveorder"] != $shopcoinsorder) || ($rows['doublereserve']>time() && $rows['userreserve']!=$cookiesuser && $cookiesuser>0))
				$error = "reserved";
		}
	}
	
	if (($ShopcoinsMaterialtype==3 || $ShopcoinsMaterialtype==5) and $rows["amount"] < $amount)
	{
		$error = "amount";
		$erroramount = $rows["amount"];
	}
	
	if ($rows["check"] == 0) 
		$error = "notavailable";
	
	
	
		
	//проверка по сумме заказа
	$sql = "select sum(orderdetails.amount*shopcoins.price) as mysum
	from orderdetails, shopcoins 
	where orderdetails.order='".$shopcoinsorder."' 
	and orderdetails.catalog=shopcoins.shopcoins;";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	if (($amount?$amount:1)*$price + $rows["mysum"] > $stopsummax)
		$error = "stopsummax";
	
	
		
	if ($shopcoinsorder)
	{
		$shopcoinsorder = intval($shopcoinsorder);
		
		$sql = "select * from `order` where `order`='$shopcoinsorder';";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		if ($rows["check"]==1 or time() > $rows["date"] + 5*3600)
		{
			if ($cookiesuser != 811 || $rows["check"]>0)
			{
				unset ($shopcoinsorder);
				//удаляем cookies - заказ уже был сделан, либо недоделан до конца
				setcookie("shopcoinsorder", 0, time(), "/shopcoins/", $domain);
				setcookie("shopcoinsorder", 0, time(), "/shopcoins/");
				setcookie("shopcoinsorder", 0, time(), "/shopcoins/", ".shopcoins.numizmatik.ru");
				setcookie("shopcoinsorder", 0, time(), "/");
				//vbhjckfd
				unset($_SESSION['shopcoinsorder']);
			}
		}
	}
	
	if (!$error)
	{
		if (!$shopcoinsorder)
		{
			$sql = "insert into `order` (`order`,".($cookiesuser>0?" `user`,":"")." date, type, `check`, admincheck, ip) 
			values (0,".($cookiesuser>0?" '$cookiesuser',":"")." ".time().", 'shopcoins', '0', '0', '$user_remote_address ');";
			$result = mysql_query($sql);
			$shopcoinsorder = mysql_insert_id();
			
			setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/shopcoins/", $domain);
			setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/shopcoins/");
			setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/shopcoins/", ".shopcoins.numizmatik.ru");
			setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/");
			
			$_SESSION['shopcoinsorder'] = $shopcoinsorder;
		}
		
		$sql_info = "select * from orderdetails where catalog='$shopcoinsmain' and `order`='$shopcoinsorder';";
		$result_info = mysql_query($sql_info);
		$rows_info = mysql_fetch_array($result_info);
		
		if ($rows_info[0])
		{
			
			if ($ShopcoinsMaterialtype==8 || $ShopcoinsMaterialtype==7 || $ShopcoinsMaterialtype==4 || $ShopcoinsMaterialtype==2) {
			
				$amountsql = $rows_info['amount']+($amount?$amount:1);
			}
			else $amountsql = $amount;
			//кто то захотел увеличить уменьшить количество
			$sql = "update orderdetails set amount='$amountsql'
			where `order`='$shopcoinsorder' and catalog='$shopcoinsmain';";
			$result = mysql_query($sql);
		}
		else
		{
			//добавляем первый раз данный продукт в заказ
			$sql = "insert into orderdetails (orderdetails, `order`, `date`, `catalog`, `amount`, typeorder)
			values (0, '".$shopcoinsorder."', '".time()."', '".$shopcoinsmain."', '".(!$amount?"1":$amount)."', '1');";
			$result = mysql_query($sql);
		}
		
		//для единичных товаров - amount = 1
		if ($ShopcoinsMaterialtype == 1 || $ShopcoinsMaterialtype == 10 || $ShopcoinsMaterialtype == 11 || $ShopcoinsMaterialtype == 9 || $ShopcoinsMaterialtype == 12) {
			
			$sql_update = "update shopcoins set reserve='".time()."', reserveorder='$shopcoinsorder', doubletimereserve='0', userreserve='0' 
			where shopcoins='$shopcoinsmain';";
			$result_update = mysql_query($sql_update);
			$_SESSION["shopcoinsorderamount"] =  intval($_SESSION["shopcoinsorderamount"])+1;
		}
		elseif ($ShopcoinsMaterialtype == 4 || $ShopcoinsMaterialtype == 7 || $ShopcoinsMaterialtype == 8 || $ShopcoinsMaterialtype==2) {
		
			for ($i=0;$i<$amount;$i++) {
				$sql_update = "insert into helpshopcoinsorder set helpshopcoinsorder=0, reserve='".time()."', reserveorder='$shopcoinsorder', shopcoins='$shopcoinsmain';";
				$result_update = mysql_query($sql_update);
			}
			$_SESSION["shopcoinsorderamount"] =  intval($_SESSION["shopcoinsorderamount"])+($amount?$amount:1);
		}
		
		Bascet($shopcoinsorder);
	}
}

if (sizeof($_GET) == 2 && isset($_GET['materialtype'])&& $_GET['materialtype'] == 3 && $_GET['group'] == 816 && substr_count($_SERVER['REQUEST_URI'],".html")==0) {

	header('HTTP/1.1 301 Moved Permanently');
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/shopcoins/albom_dlya_monet.html');
}
elseif ($materialtype==3 && $group==816) {
	$script = "index.php";
}
if ( substr_count($_SERVER['REQUEST_URI'],".html")==0 && $page=="show") {
die('0.html');
	if (intval($parent)>0 || intval($catalog)>0) {
	
		$parent = intval($parent);
		$catalog = intval($catalog);
		
		$sql = "select shopcoins.*, `group`.`name` as gname from shopcoins, `group` where shopcoins.`group`=`group`.`group` and shopcoins='".($parent>0?$parent:$catalog)."' limit 1;";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		
		$mtype = $rows['materialtype'];
			
			if ($mtype==1)
				$rehref = "Монета ";
			elseif ($mtype==8)
				$rehref = "Монета ";
			elseif ($mtype==7)
				$rehref = "Набор монет ";
			elseif ($mtype==2)
				$rehref = "Банкнота ";
			elseif ($mtype==4)
				$rehref = "Набор монет ";
			elseif ($mtype==5)
				$rehref = "Книга ";
			elseif ($mtype==9)
				$rehref = "Лот монет ";
			elseif ($mtype==10)
				$rehref = "Нотгельд ";
			elseif ($mtype==11)
				$rehref = "Монета ";
			elseif ($mtype==12)
				$rehref = "Монета ";
			else 
				$rehref = "";
					
			
			if ($rows['gname'])
				$rehref .= $rows['gname']." ";
			$rehref .= $rows['name'];
			if ($rows['metal'])
				$rehref .= " ".$rows['metal']; 
			if ($rows['year'])
				$rehref .= " ".$rows['year'];
//			$namecoins = $rehref;
			if ($parent>0)
				$rehref = strtolower_ru($rehref)."_c".(($mtype==1 || $mtype==10)?$rows['parent']:$rows['shopcoins'])."_pc".($parent>0?$parent:(($mtype==7 || $mtype==8 || $mtype==6 || $mtype==4 || $mtype==2) && $rows["amount"]>1?$rows['shopcoins']:(($mtype==1 || $mtype==10)?$rows['parent']:0)))."_m".$rows['materialtype']."_pp1.html";
			else 
				$rehref = strtolower_ru($rehref)."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";
				
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: http://'.$_SERVER['HTTP_HOST'].'/shopcoins/'.$rehref);
	}
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

if ($page=="recommendation" && !$cookiesuser) {
	
	$_GET["page"]='';
	$page = "";
}


//по чем производился поиск
$WhereArray = Array();

if ($savesearch)
	include "searchindex.php";

//получаем поисковый запрос
if ($searchid)
{
	
	$searchid = intval($searchid);
	if (!sizeof($WhereArray))
	{
		$fd = fopen ("searchnow.dat", "r");
		$content .= fread ($fd, filesize ("searchnow.dat"));
		$tmp = explode("\n", $content);
		foreach ($tmp as $key=>$value)
		{
			//теперь разделяем по \t
			$tmp1 = explode("\t", $value);
			if ($tmp1[1] == $searchid)
			{
				$WhereArray[] = $tmp1[3];
				break;
			}
		}
		fclose ($fd);
		//echo $WhereSearch;
	}
	
	
	//$where .= $WhereSearch;
}




$groupselect = ", if(LEFT(TRIM(shopcoins.name),1) in('0','1','2','3','4','5','6','7','8','9'),CASE SUBSTRING_INDEX(trim(shopcoins.name),' ',1) WHEN '1/2' THEN 1/2+0.5 WHEN '1/3' THEN 1/3+0.5 WHEN '1/4' THEN 1/4+0.5 WHEN '1/6' THEN 1/6+0.5 WHEN '1/8' THEN 1/8+0.5 WHEN '1/16' THEN 1/16+0.5 WHEN '3/4' THEN 3/4+0.5 WHEN '1/24' THEN 1/24+0.5 WHEN '5/10' THEN 5/10+0.5 WHEN '1/20' THEN 1/20+0.5 WHEN '1/5' THEN 1/5+0.5 WHEN '1/10' THEN 1/10+0.5 WHEN '1/32' THEN 1/32+0.5 WHEN '2/3' THEN 2/3+0.5 WHEN '1/24' THEN 1/24+0.5 WHEN '1/12' THEN 1/12+0.5 WHEN '1/26' THEN 1/26+0.5 WHEN '1/48' THEN 1/48+0.5 WHEN '1/13' THEN 1/13+0.5 WHEN '5/100' THEN 5/100+0.5 WHEN '1/100' THEN 1/100+0.5 WHEN '2/10' THEN 2/10+0.5 WHEN '1/25' THEN 1/25+0.5 WHEN '1/96' THEN 1/96+0.5 WHEN '1/40' THEN 1/40+0.5 WHEN '1/84' THEN 1/84+0.5 WHEN '0,05' THEN 0.05+0.5 WHEN '0,1' THEN 0.1+0.5 ELSE SUBSTRING_INDEX(trim(shopcoins.name),' ',1)+0.5 END ,999999999) as param1, if(LEFT(TRIM(shopcoins.name),1) in('0','1','2','3','4','5','6','7','8','9'),if(LENGTH(SUBSTRING_INDEX(TRIM(shopcoins.name),' ',-1))<4,SUBSTRING_INDEX(TRIM(shopcoins.name),' ',-1),if(LENGTH(SUBSTRING_INDEX(TRIM(shopcoins.name),' ',-1))<=6,MID(SUBSTRING_INDEX(TRIM(shopcoins.name),' ',-1),1,LENGTH(SUBSTRING_INDEX(TRIM(shopcoins.name),' ',-1))-2),MID(SUBSTRING_INDEX(TRIM(shopcoins.name),' ',-1),1,LENGTH(SUBSTRING_INDEX(TRIM(shopcoins.name),' ',-1))-3))),CONCAT('я',ifnull(TRIM(shopcoins.name),''))) as param2 ";



if (!$page) {	
    $module = "index";
}

require_once($cfg['path'] . '/controllers/catalog_all.ctl.php');
require_once($cfg['path'] . '/controllers/pager.ctl.php');
*/
//and shopcoins.dateinsert>0 
//and shopcoins.dateorder=0 
//показ монет


/*$arraytitle = explode("|", $tpl['title']);
if ($catalog) 
	$tmptitle = $arraytitle[1]." | ".$arraytitle[2]." | Монетная лавка";
else 
	$tmptitle = $arraytitle[0]." | Монетная лавка";
	


$time_start = getmicrotime();
//$testorder = intval($testorder);
$nocheck = intval($nocheck);
$WhereSearch="";
$shopcoinsorder = "";

if (($page=="orderdetails" || ($page=="order" && !$_POST['page2'])) && $shopcoinsorder>0) {

	$sql_or = "select * from `order` 
	where `check`=0 and `order`='$shopcoinsorder' and `date`>'".(time()-5*60*60)."' limit 1;";
	$result_or = mysql_query($sql_or);
	if (mysql_num_rows($result_or)==0) {
	
		setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/", $domain);
		setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/");
		setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/", ".shopcoins.numizmatik.ru");
		setcookie("shopcoinsorder", 0, time() + 2, "/", $domain);
		setcookie("shopcoinsorder", 0, time() + 2, "/");		
		
		
		//vbhjckfd
		unset($_SESSION['shopcoinsorder']);
		$shopcoinsorder = 0;
		
	}
} elseif ((!$page || $page=="show") && $shopcoinsorder<1 && $cookiesuser != 811 && $cookiesuser>0 && !$testorder ){//&& $_SERVER['REMOTE_ADD
	//session_start();
	$sql_or = "select * from `order` where `check`=0 and `user`='$cookiesuser' and `user`!=811 and `user`>0 and `date`>'".(time()-5*60*60)."' limit 1;";
	$result_or = mysql_query($sql_or);
	$timecookies = time() + 60;
	
	if (mysql_num_rows($result_or)==1) {
	
		$rows_or = mysql_fetch_array($result_or);
		$shopcoinsorder = $rows_or['order'];
		//$timecookies = $rows_or['date'] + $reservetime;
		
		setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/", $domain);
		setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/");
		setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/", ".shopcoins.numizmatik.ru");
		setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/");
		
		
		$_SESSION['shopcoinsorder'] = $shopcoinsorder;
	}
	
	$testorder = 1;
	
	setcookie("testorder", $testorder, $timecookies, "/shopcoins/", $domain);
	setcookie("testorder", $testorder, $timecookies, "/shopcoins/");
	setcookie("testorder", $testorder, $timecookies, "/shopcoins/", ".shopcoins.numizmatik.ru");
	setcookie("testorder", $testorder, $timecookies, "/");	
}



if ($shopcoinsorder<1) {
	unset($_SESSION['shopcoinsorderamount']);
	$shopcoinsorderamount = 0;
}

if ($materialtype==3 && $group==816) {

	$keywords = "альбомы для монет, монетные альбомы, купить альбом для монет, монеты в альбом.";
	$tpl['title'] = "Альбомы для монет";
	$description = "Клуб Нумизмат- скупка и продажа монет, оценка монет, альбомы для монет. Москва. Телефон – 8(903) 006-00-44.";
}

//$testorder = intval($testorder);
$nocheck = intval($nocheck);
$WhereSearch="";

if (($page=="orderdetails" || ($page=="order" && !$_POST['page2'])) && $shopcoinsorder>0) {

	$sql_or = "select * from `order` 
	where `check`=0 and `order`='$shopcoinsorder' and `date`>'".(time()-5*60*60)."' limit 1;";
	$result_or = mysql_query($sql_or);
	if (mysql_num_rows($result_or)==0) {
	
		setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/", $domain);
		setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/");
		setcookie("shopcoinsorder", 0, time() + 2, "/shopcoins/", ".shopcoins.numizmatik.ru");
		setcookie("shopcoinsorder", 0, time() + 2, "/", $domain);
		setcookie("shopcoinsorder", 0, time() + 2, "/");		
		
		
		//vbhjckfd
		unset($_SESSION['shopcoinsorder']);
		$shopcoinsorder = 0;
		
	}
} elseif ((!$page || $page=="show") && $shopcoinsorder<1 && $cookiesuser != 811 && $cookiesuser>0 && !$testorder ){//&& $_SERVER['REMOTE_ADD
	//session_start();
	$sql_or = "select * from `order` where `check`=0 and `user`='$cookiesuser' and `user`!=811 and `user`>0 and `date`>'".(time()-5*60*60)."' limit 1;";
	$result_or = mysql_query($sql_or);
	$timecookies = time() + 60;
	
	if (mysql_num_rows($result_or)==1) {
	
		$rows_or = mysql_fetch_array($result_or);
		$shopcoinsorder = $rows_or['order'];
		//$timecookies = $rows_or['date'] + $reservetime;
		
		setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/", $domain);
		setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/");
		setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/", ".shopcoins.numizmatik.ru");
		setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/");
		
		
		$_SESSION['shopcoinsorder'] = $shopcoinsorder;
	}
	
	$testorder = 1;
	
	setcookie("testorder", $testorder, $timecookies, "/shopcoins/", $domain);
	setcookie("testorder", $testorder, $timecookies, "/shopcoins/");
	setcookie("testorder", $testorder, $timecookies, "/shopcoins/", ".shopcoins.numizmatik.ru");
	setcookie("testorder", $testorder, $timecookies, "/");	
}

if ($shopcoinsorder<1) {

	unset($_SESSION['shopcoinsorderamount']);
	$shopcoinsorderamount = 0;
}


function getmicrotime(){ 
	list($usec, $sec) = explode(" ",microtime()); 
	return ((float)$usec + (float)$sec); 
} 

if (!$page || $page == "recommendation")
{
  //  $module = 'showall';
    $tpl['msg'] = "<center><a href=".$cfg['site_root']."program/ title='Скачать программное обеспечение для нумизмата совершенно бесплатно'><img src=".$cfg['site_root']."images/banner20.gif border=1 style='border-color:black' alt='Скачать программное обеспечение для нумизмата совершенно бесплатно'></a></center><br>";
	//include "showall.php";
} elseif ($page=="show" and $catalog) {
   // $module = 'show';
	//include "show.php";
} elseif ($page=="orderdetails" || ($page=="order" && !$_POST['page2'])) {
    // $tpl['module'] = 'orderdetails';
	//include "orderdetails.php";
} elseif ($page=="order" && $page2==1) {
     //$tpl['module'] = 'order';
	//include "userorder.php";
} elseif ($page=="order" && $page2==2) {
    // $tpl['module'] = 'addinorder';
	//include "addinorder.php";
} elseif ($page == "submitorder"){
    // $tpl['module'] = 'submitorder';
	//include "submitorder.php";
}



*/


?>