<?php
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/orderdetails.php';
require_once $cfg['path'] . '/models/order.php';
$data_result = array();
$data_result['error'] = null;

$coupon_count = (integer)request('coupon_count');
$coupons = array();

$timenow = mktime(0, 0, 0, date("m", time()), date("d", time()), date("Y", time()));

if(!$tpl['user']['user_id']) $data_result['error'] = "noauth";

$shopcoins = (integer)request('shopcoins');

$orderdetails_class = new model_orderdetails($db_class,$shopcoinsorder);
$order_class = new model_order($db_class,$shopcoinsorder,$tpl['user']['user_id']);

$order = request('order');
$postindex = request('postindex');
$delivery = request('delivery');
$postindex = str_replace("'","",$postindex);
$clientdiscount = $orderdetails_class->getClientdiscount($tpl['user']['user_id']);
$meetingtotime  = request('meetingtotime');
$payment  = request('payment');
$metro   = request('metro');
$metrovalue   = request('metrovalue');
$meetingdate   = request('meetingdate');
$meetingfromtime  = request('meetingfromtime');
$rows = $orderdetails_class->forBasket($clientdiscount);

$bascetsum = $rows["mysum"];
$amountbascetsum = $rows['mysumamount'];
$vipcoinssum = $rows['vipcoinssum'];

$discountcoupon = 0;

$sumEMC = 0;


$tpl['user']['vip_discoint'] = 0;

$user_data =  $user_class->getUserData();

if($tpl['user']['user_id']){
	if($user_data['vip_discoint']&&(!$user_data['vip_discoint_date_end']||$user_data['vip_discoint_date_end']>time())){
		$tpl['user']['vip_discoint'] = $user_data['vip_discoint'];
	}
}

if($user_data['vip_discoint']) {
    $discountcoupon = floor(($bascetsum-$amountbascetsum-$vipcoinssum)*$tpl['user']['vip_discoint']/100);

} else if ($coupon_count && $tpl['user']['user_id'] && $tpl['user']['user_id']<>811 && $shopcoinsorder) {
    //проверяем купон
	//получаем данные о введенном купоне
	
	for ($i=0;$i<$coupon_count;$i++){
        $code = request('coupon'.$i);

        if($code) {        	
            if (!preg_match("/[^-0-9a-zA-Z]{19}/",$code)){
        		$couponData = $user_class->getUserCoupon(array('code'=>$code ,'type'=>1));
        		
        		if($couponData){
        		    $couponData = $couponData[0];
        		    //var_dump($couponData['check']);
        		    if(($couponData['check'] != 0)&&($couponData['dateend']>time())) {        		   
            			$discountcoupon += $couponData['sum'];
            			$coupons[] = $code;
            		}
        		}
        	}
        }
    }
}

//echo $sql;
/*
$sql2 = "select coupon.* from ordercoupon, coupon where ".(sizeof($shopcoinsorder)>1?"ordercoupon.order in (".implode(",",$shopcoinsorder).")":"ordercoupon.order='".$shopcoinsorder."'")." and ordercoupon.order>0 and ordercoupon.`check`=1 and coupon.coupon=ordercoupon.coupon group by coupon.coupon order by coupon.type desc, coupon.dateinsert desc;";
//echo $sql." = ".$checking;
$result2 = mysql_query($sql2);
$discountcoupon = 0;
unset($arraycoupcode);
if (@mysql_num_rows($result2)>0) {

	$typec = 1;
	while ($rows2 = mysql_fetch_array($result2)) {


		if ($rows2['type']==2 && $typec==1) {

			$discountcoupon = floor(($bascetsum-$amountbascetsum-$vipcoinssum)*$rows2['sum']/100);
			$typec = 2;
			$arraycoupcode[] = "VIP";
		}
		elseif ($rows2['type']==1 && ($typec==1 || ($typec==2 && $rows2['order']==0))) {

			$discountcoupon += $rows2['sum'];
			$arraycoupcode[] = strtoupper($rows2['code']);
		}
	}
}*/
if ($discountcoupon<0)	$discountcoupon = 0;
$bascetsum = $bascetsum - $discountcoupon;

if ($bascetsum<0) $bascetsum = 0;
$bascetweight = $rows["myweight"];

$mymaterialtype = ($bascetsum>0)?$rows["mymaterialtype"]:1;

//вычисляем для страховок
$suminsurance = $order_class->getSuminsurance();
if ($suminsurance>0){
	$bascetinsurance = $suminsurance * 0.04;
} else {
	$bascetinsurance = $bascetsum * 0.04;
}

$bascetamount = $orderdetails_class->getCounter();
$postcounter = $orderdetails_class->getPaking();
if ($postcounter)
	$bascetpostweight = $bascetweight + $WeightPostBox;
else
	$bascetpostweight = $bascetweight + $WeightPostLatter;

$PostZoneNumber = 0;
$rows = $orderdetails_class->getPost($postindex);

if($postindex){
	//тариф по зоне обслуживания
	//select * from Post where PostIndex='600023';
	$PostZoneNumber = $rows["PostZone"];
	$PostRegion = ($rows["Region"]?$rows["Region"]:$rows["Autonom"]);
	$PostCity = ($rows["City"]?$rows["City"]:$PostRegion);
}

if (!$PostZoneNumber)	$PostZoneNumber = 5;
if ($mymaterialtype!=0){
	$PostZonePrice = $orderdetails_class::$PostZone[$PostZoneNumber] + $orderdetails_class::$PackageAddition[$PostZoneNumber]*($bascetpostweight<500?0:ceil(($bascetpostweight-500)/500));
}else {
	$PostZonePrice = $orderdetails_class::$PostZone1[$PostZoneNumber] + $orderdetails_class::$PackageAddition[$PostZoneNumber]*($bascetpostweight<500?0:ceil(($bascetpostweight-500)/500));
}
$PostAllPrice = $PostZonePrice + $PriceLatter + $bascetinsurance + $bascetsum;



/*
if ($checking)
{
	$sql = "select s.* from orderdetails as o, shopcoins as s
	where ".(sizeof($shopcoinsorder)>1?"o.order in (".implode(",", $shopcoinsorder).")":"o.order='".$shopcoinsorder."'")."
	and o.catalog=s.shopcoins and o.status=0;";
	$result = mysql_query($sql);
	$BascetNameArray = Array();
	while ($rows = mysql_fetch_array($result))
		$BascetNameArray[] = $rows["name"];

	$BascetName = implode(", ", $BascetNameArray);
}*/

$FinalSum  = 0;

if ($delivery==6) {		
	if ($bascetpostweight < 1000) {
    	$sumEMC = 650;
    } else {			
    	$sumEMC = 650;
    	$sumEMC += ceil(($bascetpostweight - 1000)/500)*50;
    }
    
    $FinalSum = ($bascetsum+$PriceLatter+10+$sumEMC);
} elseif ($delivery==4 && $postindex) {
    $FinalSum = ($PostZonePrice+$bascetsum+$PriceLatter+($suminsurance>0?$suminsurance*0.04:$bascetinsurance));
} else {
    $FinalSum = $bascetsum;
}


if($tpl['user']['user_id']==811)	{
	//var_dump($bascetsum,$PriceLatter,10,$sumEMC,$FinalSum);
}	
//echo $discountcoupon;


if ($delivery==4 && $postindex){	
    $data_result['bascetinsurance']=$bascetinsurance;
	$data_result['bascetpostindex']=$postindex;
	$data_result['bascetpostweight']=$bascetpostweight;
	$data_result['PostZoneNumber']=$PostZoneNumber;
	$data_result['PostRegion']=trim($PostRegion);
	$data_result['PostZonePrice']=$PostZonePrice;
	$data_result['PostZoneLatter']=$PriceLatter;
	$data_result['PostAllPrice']=$PostAllPrice;
}

if ($delivery==2) 	$DeliveryName[2] = $DeliveryName[$delivery] = "В офисе (возможность посмотреть материал до выставления)";

$data_result['DeliveryName'] = $DeliveryName[$delivery];
$data_result['SumName'] = $SumName[$payment];

$data_result['discountcoupon'] = $discountcoupon;

$data_result['coupons'] = implode("<br>",$coupons);
$data_result['metro'] ="";
$data_result['metroprice']="";

if ($metro and $delivery == 1) {
	$data_result['metro'] = $MetroArray[$metro];
} elseif ($metro and ($delivery == 3)) {
    $metro_data = $order_class->getMetroName($metro);
	$data_result['metro'] = $metro_data['name'];
	$data_result['metroprice'] = $metro_data['price'];
	$FinalSum +=$metro_data['price'];
}
if($tpl['user']['user_id']==352480){
    //var_dump(date("w",$meetingdate),date("d-m-Y", $meetingdate));
	//var_dump($PostZoneNumber, $orderdetails_class::$PostZone1[$PostZoneNumber]);
}
$data_result['meetingdate'] = "";
if ($meetingdate and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7))
	$data_result['meetingdate'] = $DaysArray[date("w",$meetingdate)].":".date("d-m-Y", $meetingdate);

$data_result['meetingfromtime'] = '';
if ($meetingfromtime and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7))
	$data_result['meetingfromtime'] =date("H-i", $timenow + $meetingfromtime);


$data_result['meetingtotime'] = "";

if ($meetingtotime and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7)) {
	$data_result['meetingtotime'] = date("H-i", $timenow + $meetingtotime);
}


$data_result['FinalSum']=$FinalSum;
$data_result['sumEMC'] = $sumEMC;
$data_result['bascetamount']=$bascetamount;
$data_result['bascetsum']=$bascetsum;
$data_result['bascetweight']=$bascetweight;
if(!($delivery==4&&$postindex)) $data_result['error'] ='NotPost';
$data_result['shopcoinsorder'] = $shopcoinsorder;

echo json_encode($data_result);
die();
