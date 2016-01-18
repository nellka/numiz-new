<?
/*$my_ip = ((getenv("REMOTE_ADDR")=="212.233.78.26"||getenv("REMOTE_ADDR")=="127.0.0.1")?1:0);*/

require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';
require $cfg['path'] . '/configs/config_shopcoins.php';

//способ доставки

$delivery = request('delivery');
$payment = request('payment');
if(!$payment) $payment =2;
if (!$delivery) $delivery = 2;
$userfio = request('userfio');
$phone = request('phone');
$fio = request('fio');
$Otheinfo = request('Otheinfo');
$email  = request('email');
$postindex = request('postindex');
$adress = request('postindex');
$my_ip = 0;
/*
function update_order_molotok($order_id, $molotok = 2){
	$sql = "UPDATE `order` set molotok = 2 where `order`.`order`='".intval($order_id)."'";
	$res = mysql_query($sql);
}

if (isset($_COOKIE['yamarket'])) {
	update_order_molotok($shopcoinsorder, 2);
}*/

//нужно посчитать сумму заказа
if (!$tpl['user']['user_id']){
    $tpl['shop']['errors'][] = "Авторизуйтесь!";
    header("location: ".$cfg['site_dir']."shopcoins?page=orderdetails");
    die();	
}

$order_class = new model_order($cfg['db'],$shopcoinsorder,$tpl['user']['user_id']);
$orderdetails_class = new model_orderdetails($cfg['db'],$shopcoinsorder);
//
$user_data =  $user_class->getUserData();

$sumallorder= 1;
$sum_allorder = $order_class->getSumOfOrder();
if($sum_allorder&&intval($sum_allorder["sumallorder"])>0){
    $sumallorder = intval($sum_allorder["sumallorder"]);
}
if(!$postindex){
    preg_match_all('/\d{6}/', $user_data["adress"], $found);
    if ($found&&$found[0]&&$found[0][0]) $postindex = $found[0][0];
}
	
//fio из последнего заказа
if(!$fio&&$user_data['fio'] ){
    $fio = $user_data['fio'];
} elseif (!$fio) {
    $userfio = $order_class->getUserfio();
    $fio = $userfio;
}
if(!$email){
	$email = $user_data["email"];
}
$amount_inoffice = 0;
$sum_inoffice = 0;
$order_inoffice = array();
//хз засем
foreach ((array)$order_class->getInOffice() as $rows_inoffice){
	$amount_inoffice ++;
	$sum_inoffice += $rows_inoffice["sum"];
	$order_inoffice[] = $rows_inoffice["order"];
}

/* по умолчанию надо выбрать офис
	$js .= "parent.document.formorder.in_office.value = 1;
	parent.document.formorder.in_office_str.value = '� ����� �.��������';";
	*/


if (!$adress&&trim($user_data["adress"])){
	$adress = str_replace("\n"," ", trim($user_data["adress"]));
	$adress = str_replace("\t", " ", $adress);
	$adress = str_replace("\r", " ", $adress);
	$adress = str_replace("  ", " ", $adress);
}

if ($tpl['user']['user_id']<>811) {
    
    //купленные ранее
	$arraycoins = array();
	$tpl['orderdetails']['alreadyBye']= $order_class->alreadyByeCoins();

	foreach ($tpl['orderdetails']['alreadyBye'] as $rows) {
		$arraycoins[] = $rows['shopcoins'];
	}
	//без связей
	$result = $order_class->alreadyByeCoins2();
	foreach($result as $rows) {
		$tpl['orderdetails']['alreadyBye'][] = $rows;
	}
}

$tpl['user']['fio'] = $user_data['fio'];
$tpl['user']['phone'] = $user_data['phone'];
$userstatus = $user_data['userstatus'];
$sumlimit = $user_data['sumlimit'];
$timelimit = $user_data['timelimit'];

$tpl['myOrders'] = $order_class->getCurrentOrders();
$tpl['Otheinfo'] = '';

if ($tpl['myOrders']) {
	foreach ($tpl['myOrders'] as $rows) {
		$tpl['Otheinfo'] .= $rows['order']." - ".$rows['ReminderComment']." &nbsp;&nbsp;";
	}
}

$tpl['orderdetails']['coupons'] = array();

$iscoupon = $user_class->getUserCouponType();
$iscoup=0;

if ($iscoupon==2) {

	$couponData = $user_class->getUserCoupon(array('`check`'=>1, 'type'=>2));

	$tpl['orderdetails']['coupons'][2] = $couponData['code'];
	$order_class->tempUseCoupon($couponData['coupon']);

	$iscoup1 = $user_class->getUserCoupon(array('`check`'=>1, 'type'=>1, '`order`'=>0));
	if($iscoup1){
		$iscoup = 1;
		$tpl['orderdetails']['coupons'][1] = $iscoup1['code'];
	}
}

$codetmp_ = $user_class->getFriendCouponCode();
if($codetmp_){
	$tpl['orderdetails']['coupons']['friends'] = explode("-",$codetmp_);
}

$rows = $order_class->OrderSum();
$bascetsum = $rows["mysum"];

if ($rows["mysum"] <= $rows["mysumclient"] || $rows["mysumclient"]<1) 
	$mysumclient = 0;
else
	$mysumclient = $rows["mysumclient"];

if ($bascetsum < 3000) {
	$DeliveryNameDisabled[5] = 1;
	$DeliveryNameDisabled[6] = 1;
}

if ($bascetsum < 1000) {	
	if ($order_class->getDelivery()==0) $DeliveryNameDisabled[3] = 1;
}

$DeliveryNameDisabled[7] = 1;
$DeliveryNameDisabled[5] = 1;
$tpl['resultm'] = $order_class->getMetro();

$arraymetrojava = array();

$arraymetrojava = "MetroPrice = Array(0);";
foreach ($tpl['resultm'] as $rowsm){	
	$arraymetrojava .= "MetroPrice[".$rowsm['metro']."] = '".$rowsm['price']."';";
}

$orderdetails= $orderdetails_class->getDetails($tpl['user']['user_id']);

$oldmaterialtype = 0;
$tpl['orderdetails']['ArrayShopcoinsInOrder'] = array();
$tpl['orderdetails']['ArrayGroupShopcoins'] = array();

$i=0;
foreach ($orderdetails as 	$rows ){

	$tpl['orderdetails']['ArrayShopcoinsInOrder'][$i] = $rows;
	$tpl['orderdetails']['ArrayGroupShopcoins'][] = $rows['group'];
	$tpl['orderdetails']['ArrayShopcoinsInOrder'][$i]['title_materialtype'] = '';

	if ($oldmaterialtype != $rows["materialtype"]) {
		$oldmaterialtype = $rows["materialtype"];
		$tpl['orderdetails']['ArrayShopcoinsInOrder'][$i]['title_materialtype'] = $MaterialTypeArray[$rows["materialtype"]];
	}

	if (trim($rows["details"]))	{
		$text = substr($rows["details"], 0, 250);
		$text = strip_tags($text);
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		$text = str_replace("\r","",$text);
		$tpl['orderdetails']['ArrayShopcoinsInOrder'][$i]["details"] = str_replace("\n","<br>",$text);
	}
	$i++;
}
$can_pay_from_balance = false;
if($bascetsum <= 3000&& $user_class->is_user_has_premissons() && $tpl['user']['balance'] >= $bascetsum){
    $can_pay_from_balance = true;
}
?>

