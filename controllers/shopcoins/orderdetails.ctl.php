<?
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';

require $cfg['path'] . '/configs/config_shopcoins.php';

$order_class = new model_order($cfg['db']);
$orderdetails_class = new model_orderdetails($cfg['db']);

$shopcoins = intval(request("shopcoins"));
$pageinfo = request("pageinfo");
$amount = request("amount");

if ($pageinfo=="delete" and $shopcoinsorder) {
	die("delete");
	$shopcoinsorder = intval($shopcoinsorder);
	$sql5 = "select * from `order` where `order`='$shopcoinsorder';";
	$result5 = mysql_query($sql5);
	$rows5 = mysql_fetch_array($result5);
	
	if ($rows5['check']==0) {
		//удаляем позицию из заказа
		$sql = "delete from orderdetails 
		where `order`='".$shopcoinsorder."' and catalog='".$shopcoins."';";
		$result = mysql_query($sql);
		
		$sql = "update shopcoins set reserve='0', reserveorder='0', doubletimereserve=0, userreserve=0  
		where shopcoins='$shopcoins' and reserveorder='$shopcoinsorder';";
		$result = mysql_query($sql);
		$sql = "delete from helpshopcoinsorder where shopcoins='$shopcoins' and reserveorder='$shopcoinsorder';";
		$result = mysql_query($sql);
		Bascet($shopcoinsorder);
	}	
}
if ($amount>0){
	$rows5 = $order_class->getRowByParams(array('`order`'=>$shopcoinsorder));   
	if ($rows5['check']==0) {	
		for ($i=0; $i<=$amount; $i++) {
			${"sqlamount".$i} = request("sqlamount".$i);
			${"amount_".$i} = request("amount_".$i);
			if ((${"sqlamount".$i}!=${"amount_".$i})){
				$rows = $orderdetails_class->getRowByParams(array('`order`'=>$shopcoinsorder,'catalog'=>${"shopcoins".$i},'orderdetails.status'=>0)); 
				die('Надо пересчитать');
				if ($rows){					
					$sql = "select * from shopcoins where shopcoins='".${"shopcoins".$i}."' and (`check` in(1,4,5) ".($cookiesuser==811?"or `check`>3":"").");";
					$result = mysql_query($sql);
					$rows = mysql_fetch_array($result);
					if (${"amount_".$i}>$rows['amount'])
						${"amount_".$i} = $rows['amount'];
					
					$sql = "update orderdetails set amount='".${"amount_".$i}."' 
					where `order`='".$shopcoinsorder."' and catalog='".${"shopcoins".$i}."';";
					$result = mysql_query($sql);
					$ourcoinsorderamount[${"shopcoins".$i}] = ${"amount_".$i};
					Bascet($shopcoinsorder);
				}
			}
		}
	}
}

$clientdiscount = $order_class->getClientdiscount($tpl['user']['user_id'],$shopcoinsorder);
	
if(!$orderdetails = $cache->load("orderdetails_".$clientdiscount.'_'.$shopcoinsorder.'_'.$tpl['user']['user_id'])){
	$orderdetails= $order_class->getDetails($clientdiscount,$shopcoinsorder,$tpl['user']['user_id']);
	$cache->save($orderdetails, "orderdetails_".$clientdiscount.'_'.$shopcoinsorder.'_'.$tpl['user']['user_id']);	
}
$i = 0;
$sum = 0;
$oldmaterialtype = 0;
$tpl['orderdetails']['ArrayShopcoinsInOrder'] = array();
$tpl['orderdetails']['ArrayGroupShopcoins'] = array();
 
$i=0;
foreach ($orderdetails as 	$rows ){	
    
	$tpl['orderdetails']['ArrayShopcoinsInOrder'][$i] = $rows;
	$tpl['orderdetails']['ArrayGroupShopcoins'][] = $rows['group'];
	$sum += $rows["oamount"]*$rows["price"];
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
