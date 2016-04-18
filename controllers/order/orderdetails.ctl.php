<?
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';
require_once $cfg['path'] . '/models/viporder.php';
require $cfg['path'] . '/configs/config_shopcoins.php';

require_once $cfg['path'] . '/models/shopcoinsdetails.php';
$details_class = new model_shopcoins_details($cfg['db']);

$order_class = new model_order($cfg['db'],$shopcoinsorder,$tpl['user']['user_id']);
$orderdetails_class = new model_orderdetails($cfg['db'],$shopcoinsorder);

$tpl['user']['user_data'] = array();

if($tpl['user']['user_id']){
    $tpl['user']['user_data'] = $user_class->getUserData();
}

$shopcoins = intval(request("shopcoins"));
$pageinfo = request("pageinfo");
$amount = request("amount");

$viporder_id = 0;

$viporder= request("viporder");
if($tpl['user']['user_id']!=811){
	$viporder = 0;
}

$ourcoinsorder = array();
$ourcoinsorderamount = array();
if ($shopcoinsorder) {
	$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);	
	foreach ( $result_ourorder as $rows_ourorder){
		$ourcoinsorder[] = $rows_ourorder["catalog"];
		$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
	}
}


if ($pageinfo=="delete" and $shopcoinsorder) {
    
	$rows5 = $order_class->getRowByParams(array('`order`'=>$shopcoinsorder)); 	

	if ($rows5['check']==0) {
		//удаляем позицию из заказа				
		$orderdetails_class->deletePostion($shopcoins);		
		$data = array('reserve'=>0,'reserveorder'=>0,'doubletimereserve'=>0,'userreserve'=>0);  
		$shopcoins_class->updateRow($data,"shopcoins='$shopcoins' and reserveorder='$shopcoinsorder'");				
		$orderdetails_class->deletePostionHelpshopcoinsorder($shopcoins);	
		$orderdetails_class->removeOrderCache($tpl['user']['user_id']);			
	}	 else {
		$order_class->clearOrder();
		$shopcoinsorder =0;
	}
}

if ($amount>0){
	$rows5 = $order_class->getRowByParams(array('`order`'=>$shopcoinsorder));   	
	if ($rows5['check']==0) {	
		for ($i=0; $i<=$amount; $i++) {
			${"sqlamount".$i} = request("sqlamount".$i);
			${"amount_".$i} = request("amount_".$i);
			${"shopcoins".$i}  = request("shopcoins".$i);
			if ((${"sqlamount".$i}!=${"amount_".$i})){
			    $rows = $orderdetails_class->getPostion(${"shopcoins".$i},true); 				
				if ($rows){					
					//$sql = "select * from shopcoins where shopcoins='".${"shopcoins".$i}."' and (`check` in(1,4,5) ".($cookiesuser==811?"or `check`>3":"").");";
					$item = $shopcoins_class->getItem(${"shopcoins".$i});
					//надо еще учитывать зарезервированные
					//"shopcoins".$i
					if((in_array($item['check'],array(1,4,5))
					   ||($tpl['user']['user_id']==811&&$item['check']>3))
					   &&${"amount_".$i}>$item['amount']){					   	
						${"amount_".$i} = $rows['amount'];
					}
					$data_orderdetails = array('amount'=>${"amount_".$i});  
					$orderdetails_class->updateItemCount($data_orderdetails,${"shopcoins".$i});						
				}
			}
		}
		$orderdetails_class->removeOrderCache($tpl['user']['user_id']);		
	}
}

$orderdetails= $orderdetails_class->getDetails($tpl['user']['user_id']);

if($viporder){
	$viporder_class = new model_shopcoinsvipclientanswer($cfg['db']);
	$viporder_id = $viporder_class->getNewViporder();
	foreach ($orderdetails as 	$row ){	
		$viporder_class->addInOrder($viporder_id,$row["catalog"]);		
		//удаляем позицию из заказа				
		$orderdetails_class->deletePostion($row["catalog"]);		
		$data = array('reserve'=>0,'reserveorder'=>0,'doubletimereserve'=>0,'userreserve'=>0);  
		$shopcoins_class->updateRow($data,"shopcoins='{$row["catalog"]}' and reserveorder='$shopcoinsorder'");				
		$orderdetails_class->deletePostionHelpshopcoinsorder($row["catalog"]);			
	}
	$orderdetails_class->removeOrderCache($tpl['user']['user_id']);		
}


$orderdetails= $orderdetails_class->getDetails($tpl['user']['user_id']);
$user_basket = $orderdetails_class->basket($tpl['user']['user_id']);
//на случай пересчета корзины
$tpl['user']['summ'] = $user_basket['bascetsum'];
$tpl['user']['product_amount'] = $user_basket['bascetamount'];

$i = 0;
$sum = 0;
$oldmaterialtype = 0;
$tpl['orderdetails']['ArrayShopcoinsInOrder'] = array();
$tpl['orderdetails']['ArrayGroupShopcoins'] = array();

$i=0;
foreach ($orderdetails as 	$rows ){	
    $rows["details"] = '';
    $details = $details_class->getItem($rows['catalog']);
    if($details) $rows["details"] = $details["details"];

	$tpl['orderdetails']['ArrayShopcoinsInOrder'][$i] = $rows;
	$tpl['orderdetails']['ArrayGroupShopcoins'][] = $rows['group'];

    $tpl['orderdetails']['ArrayShopcoinsInOrder'][$i]['amountAll'] = $shopcoins_class->getItemAmount($rows["catalog"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
	
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


$tpl['orderdetails']['related'] = array();

if (sizeof($tpl['orderdetails']['ArrayShopcoinsInOrder'])) {
	
	if (sizeof($tpl['orderdetails']['ArrayGroupShopcoins'])) {
		$result = $shopcoins_class->getGroupIdsToOrder($tpl['orderdetails']['ArrayGroupShopcoins']);
		foreach ($result as $rows ) {		
			$ArrayGroupIn[] = $rows['group'];
		}
	}
	$tpl['orderdetails']['related'] = $shopcoins_class->relatedByOrder($tpl['orderdetails']['ArrayShopcoinsInOrder'],$ArrayGroupIn);
}

//статусы заказа
 $tpl['can_order'] = false;

if ($sum>$stopsummax) {
  $tpl['order_status'] = 1;
} elseif ((($sum<500 && ($tpl['user']['orderusernow'] == 0 || $blockend > time())) || $sum<=0) && $tpl['user']['user_id'] != 811) {
	$tpl['order_status'] = 2;
} elseif ($sum >= 500 || $tpl['user']['user_id'] == 811) {
	 $tpl['can_order'] = true;
} else {
   $tpl['order_status'] = 3;
}?>
