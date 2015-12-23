<?
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/helpshopcoinsorder.php';
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';

$helpshopcoinsorder_class = new model_helpshopcoinsorder($cfg['db']);
$orderdetails_class = new model_orderdetails($cfg['db']);
$order_class = new model_order($cfg['db']);

$shopcoinslast =request("shopcoinslast");
$data_result = array();
$data_result['error'] = null;

if (!$shopcoinslast) $data_result['error'] = "noshopcoins";

$amount = 1;

$arrayshopcoins = explode("d",substr($shopcoinslast,0,strlen($shopcoinslast)-1));

if (!$shopcoinslast || !sizeof($arrayshopcoins))
	$data_result['error'] = "noshopcoins";

if (!$data_result['error']) {	
	foreach ($arrayshopcoins as $key=>$value) 
		$arrayshopcoins[$key] = intval($value);

	$result = $shopcoins_class->findByIds($arrayshopcoins);	
	$arrayresult = array();

	foreach ($result as $rows) {	
		$price = $rows['price'];
		$ShopcoinsMaterialtype = $rows["materialtype"];
		
		if ($rows['group'] == 1604)
			$data_result['error'] = "notavailable";		
		elseif (in_array($ShopcoinsMaterialtype,array(8,7,4,2,6))) {
			
			$result_amount = $helpshopcoinsorder_class->getAllByParams(array('shopcoins'=>$rows['shopcoins']));   	
			
			$amountreserve = 0;
			foreach ($result_amount as $rows_amount) {  		
				if (time()-$rows_amount["reserve"] < $reservetime ) 
					$amountreserve++;
			}
			
			if ( !$rows["amount"] ) $rows["amount"] = 1;
			
			if ($rows["amount"] <= $amountreserve || $amount > ($rows["amount"] - $amountreserve)) {		
				$data_result['error'] = "reserved"; 
			}
		} else {	
			if ($rows["reserve"]>0 || $rows['doubletimereserve']>time()){
				if ((time()-$rows["reserve"] < $reservetime and $rows["reserveorder"] != $shopcoinsorder) || ($rows['doubletimereserve']>time() && $rows['userreserve']!=$cookiesuser && $cookiesuser>0))
					$data_result['error'] = "reserved";
			}
		}
		
		if (($ShopcoinsMaterialtype==3 || $ShopcoinsMaterialtype==5) and $rows["amount"] < $amount) {
			$data_result['error'] = "amount";
		}
		
		if ($rows["check"] == 0) $data_result['error'] = "notavailable";		
			
		if (!$data_result['error']) {
			$arrayresult[] = $rows['shopcoins'];
			$ShopcoinsMaterialtypeLast[] = $rows['materialtype'];
		}
	}	

	if ($shopcoinsorder){
		$rows = $order_class->getRowByParams(array('`order`'=>$shopcoinsorder));   
		
		if ($rows["check"]==1 or time() > $rows["date"] + 5*3600) {
			if ($tpl['user']['user_id'] != 811 || $rows["check"]>0) {
				$shopcoinsorder =0;
				//удаляем cookies - заказ уже был сделан, либо недоделан до конца
				setcookie("shopcoinsorder", 0, time(), "/shopcoins/", $domain);
				setcookie("shopcoinsorder", 0, time(), "/shopcoins/");
				setcookie("shopcoinsorder", 0, time(), "/shopcoins/", ".shopcoins.numizmatik.ru");
				setcookie("shopcoinsorder", 0, time(), "/");
				if(isset($_SESSION['shopcoinsorder']))unset($_SESSION['shopcoinsorder']);
			}
		}	else $_SESSION['shopcoinsorder'] = $shopcoinsorder;	
	}

}

if (sizeof($arrayresult)>0) {
	if (!$shopcoinsorder) {
		$data_order = array('date'=>time(),
                             'type'=>'shopcoins',
                             'check'=>0,
                             'ip'=>$user_remote_address,
                             'admincheck'=>0);  
     	if($tpl['user']['user_id']) $data_order['user'] = $tpl['user']['user_id'];
    	$shopcoinsorder = $order_class->addNewRecord($data_order);			
		
		setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/shopcoins/", $domain);
		setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/shopcoins/");
		setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/shopcoins/", ".shopcoins.numizmatik.ru");
		setcookie("shopcoinsorder", $shopcoinsorder, time() + $reservetime, "/");
					
		$_SESSION['shopcoinsorder'] = $shopcoinsorder;		
	}
	
	foreach ($arrayresult as $key => $shopcoins) {
		$rows_info = $orderdetails_class->getRowByParams(array('`order`'=>$shopcoinsorder,'catalog'=>$shopcoins)); 
		
		if ($rows_info){			
			if (in_array($ShopcoinsMaterialtypeLast[$key],array(8,6,7,4,2))){
				$amountsql = $rows_info['amount']+($amount?$amount:1);
			} else $amountsql = $amount;
			
			//кто то захотел увеличить уменьшить количество
			$orderdetails_class->updateRow(array( 'amount'=>$amountsql),"`order`='$shopcoinsorder' and catalog='$shopcoins'");			
		} else {
			$data_orderdetails = array('order'=>$shopcoinsorder,
                             'date'=>time(),
                             'catalog'=>$shopcoins,
                             'amount'=>$amount,
                             'typeorder'=>1);  
       
       		$orderdetails_class->addNewRecord($data_orderdetails);
		}

		//для единичных товаров - amount = 1
		if (in_array($ShopcoinsMaterialtypeLast[$key],array(1,10,11,9,12))){
			$data_update = array('reserveorder'=>$shopcoinsorder, 
    		                     'reserve' => time(), 
    		                     'doubletimereserve'=>0, 
    		                     'userreserve'=>0);  	
    		                     
    	    $shopcoins_class->updateRow($data_update,"shopcoins='$shopcoins'");           
			$_SESSION["shopcoinsorderamount"] =  intval($shopcoinsorderamount)+1;
		} elseif (in_array($ShopcoinsMaterialtypeLast[$key],array(4,7,8,6,2))){
			for ($i=0;$i<$amount;$i++) {
				$data_insert = array('reserveorder'=>$shopcoinsorder, 
    		                     'reserve' => time(), 
    		                     'shopcoins'=>$shopcoins, 
    		                     'helpshopcoinsorder'=>0);  	
				$helpshopcoinsorder_class->addNewRecord($data_insert);			
			}
			$_SESSION["shopcoinsorderamount"] =  intval($shopcoinsorderamount)+($amount?$amount:1);
		}		
	}
	//пересчет карзины
	$clientdiscount = $order_class->getClientdiscount($tpl['user']['user_id'],$shopcoinsorder);
	if(!$dataBasket = $cache->load("bascet_".$shopcoinsorder)){
		$dataBasket = $order_class->forBasket($clientdiscount,$shopcoinsorder);
		$cache->save($dataBasket, "bascet_".$shopcoinsorder);	
	}
	$bascetsum = $dataBasket["mysum"];
	$_SESSION['bascetsum'] = $bascetsum;
	
	$bascetsumclient = $dataBasket["mysumclient"];
	if ($bascetsumclient >= $bascetsum) 
		$bascetsumclient=0;
	$bascetweight = $dataBasket["myweight"];
	$bascetinsurance = $bascetsum * 0.04;
	
	$mymaterialtype =($bascetsum>0)?$dataBasket["mymaterialtype"]:1;
	
	$bascetamount = $orderdetails_class->getCounter($shopcoinsorder);
	$orderstarttime = $orderdetails_class->getMinDate($shopcoinsorder);
	
	/*$bascetreservetimestr = "<? echo (floor((".($reservetime+$orderstarttime)."-time())/3600)>=1?floor((".($reservetime+$orderstarttime)."-time())/3600).\" ч. \":\"\").
	//(floor((".($reservetime+$orderstarttime)."-time()-floor((".($reservetime+$orderstarttime)."-time())/3600)*3600)/60).\" мин.\"); ?>";
	//$ShowWarningTimeValue = "<? echo (".($reservetime+$orderstarttime)."-time()<".$mintime."?1:0);?>";*/
	
	$bascetreservetime = (floor(($reservetime+$orderstarttime-time())/3600)>=1?floor(($reservetime+$orderstarttime-time())/3600)." ч. ":"").
	(floor(($reservetime+$orderstarttime-time()-floor(($reservetime+$orderstarttime-time())/3600)*3600)/60)." мин.");
	
	//расчет почтового сбора
	if ($mymaterialtype!=0)	{
		$bascetpostweightmin = $PostZone[1] + $PackageAddition[1]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
		$bascetpostweightmax = $PostZone[5] + $PackageAddition[5]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
	} else {
		$bascetpostweightmin = $PostZone1[1] + $PackageAddition[1]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
		$bascetpostweightmax = $PostZone1[5] + $PackageAddition[5]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
	}	
}
if (!$data_result['error']&&sizeof($arrayresult)){
	//$xml .= "<error>none</error>
	$data_result['shopcoinsorder'] = $shopcoinsorder;
	$data_result['bascetshopcoins'] = implode("d",$arrayshopcoins);
	$data_result['bascetamount'] = $bascetamount;
	$data_result['bascetsum'] = $bascetsum;
	$data_result['bascetsumclient']=$bascetsumclient;
	$data_result['bascetweight']=$bascetweight;
	$data_result['bascetreservetime']=$bascetreservetime;
	$data_result['bascetpostweightmin']=$bascetpostweightmin;
	$data_result['bascetpostweightmax']=$bascetpostweightmax;
	$data_result['bascetinsurance']=$bascetinsurance;
	//$data_result['textbascet'] = $textbascet2?htmlspecialchars($textbascet2):"none")."</textbascet2>
} else {
	$data_result['error'] = $data_result['error'];
	$data_result['erroramount'] = '';
	$data_result['bascetshopcoins'] = (sizeof($arrayshopcoins)>0?implode("d",$arrayshopcoins):"none");		
}

echo json_encode($data_result);

die();	
?>