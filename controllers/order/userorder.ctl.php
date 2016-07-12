<?
/*$my_ip = ((getenv("REMOTE_ADDR")=="212.233.78.26"||getenv("REMOTE_ADDR")=="127.0.0.1")?1:0);*/

require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/shopcoinsdetails.php';
$details_class = new model_shopcoins_details($cfg['db']);
//способ доставки

$delivery = request('delivery');
$idadmin = 0;
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

$tpl['user']['vip_discoint'] = 0;

if($tpl['user']['user_id']){
	if($user_data['vip_discoint']&&(!$user_data['vip_discoint_date_end']||$user_data['vip_discoint_date_end']>time())){
		$tpl['user']['vip_discoint'] = $user_data['vip_discoint'];
	}
}

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
    if($fio=='??????????'){
        $fio='';
    }
}
if (!$userfio) {
    $userfio = $order_class->getUserfio();
    if(!$userfio )  $userfio = $fio;
}

if(!$phone){
	$phone = $user_data['phone'];
}
if(!$email){
	$email = $user_data["email"];
}
$amount_inoffice = 0;
$sum_inoffice = 0;
$order_inoffice = array();
//хз зачем

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

$tpl['orderdetails']['admins'] = array();

$tpl['orderdetails']['alreadyBye'] = array();

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

if (!$tpl['user']['vip_discoint']) {

	$iscoup1 = $user_class->getUserCoupon(array('`check`'=>1, 'type'=>1),true);

	if($iscoup1){
		$iscoup = 1;
		foreach ($iscoup1 as $coup1){
			$tpl['orderdetails']['coupons'][1][] = $coup1['code'];
		}
	}
	
	$codetmp_ = $user_class->getFriendCouponCode();
    if($codetmp_){
    	$tpl['orderdetails']['coupons']['friends'] = explode("-",$codetmp_);
    }
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

$ids_in_order = array();

$i=0;
foreach ($orderdetails as 	$rows ){
    $ids_in_order[] = $rows['catalog'];
    
    $details = $details_class->getItem($rows['catalog']);
	$rows["details"] =  '';
	if($details) $rows["details"] = $details["details"];
	
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

if($tpl['user']['my_ip']||$tpl['user']['user_id']==811){	
	$sqlt = "select * from TimeTableUser where 	`check`=1 order by Fio;";
	$tpl['orderdetails']['admins'] = $shopcoins_class->getDataSql($sqlt);	
} else {
    
    //проверяем, что монеты из предзаказа
    if($tpl['user']['user_id']==352480){
       require_once $cfg['path'] . '/models/viporder.php';
       $viporder_class = new model_shopcoinsvipclientanswer($cfg['db']);
       $idadmin = $viporder_class->getAdminInCoins($ids_in_order);
    }
}

$can_pay_from_balance = false;
if($bascetsum <= 3000&& $user_class->is_user_has_premissons() && $tpl['user']['balance'] >= $bascetsum){
    $can_pay_from_balance = true;
}
?>

