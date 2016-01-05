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
			${"shopcoins".$i}  = request("shopcoins".$i);
			if ((${"sqlamount".$i}!=${"amount_".$i})){
				$rows = $orderdetails_class->getRowByParams(array('`order`'=>$shopcoinsorder,'catalog'=>${"shopcoins".$i},'orderdetails.status'=>0)); 
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
					$orderdetails_class->updateRow($data_orderdetails,"orderdetails.order=".$shopcoinsorder." and catalog=".${"shopcoins".$i});
					//Bascet($shopcoinsorder);
					
					//пересчет карзины
					$clientdiscount = $order_class->getClientdiscount($tpl['user']['user_id'],$shopcoinsorder);
					
					$dataBasket = $order_class->forBasket($clientdiscount,$shopcoinsorder);
					
					$cache->save($dataBasket, "bascet_".$shopcoinsorder);	
			
					$bascetsum = $dataBasket["mysum"];
					$_SESSION['bascetsum'] = $bascetsum;
					
					$bascetsumclient = $dataBasket["mysumclient"];
					if ($bascetsumclient >= $bascetsum) 
						$bascetsumclient=0;
					$bascetweight = $dataBasket["myweight"];
					$bascetinsurance = $bascetsum * 0.04;
					
					$mymaterialtype =($bascetsum>0)?$dataBasket["mymaterialtype"]:1;
					
					$bascetamount = $orderdetails_class->getCounter($shopcoinsorder);
					$_SESSION["shopcoinsorderamount"] =  $bascetamount;
					//var_dump($bascetamount);
					//DIE();
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
					$cache->remove("orderdetails_".$clientdiscount.'_'.$shopcoinsorder.'_'.$tpl['user']['user_id']);
					
					$tpl['user']['summ'] = $bascetsum;
					$tpl['user']['product_amount'] = $bascetamount;

					//$sql2 = "select shopcoins.*, group.name as gname from shopcoins,orderdetails,`group` where shopcoins.shopcoins=orderdetails.catalog and shopcoins.group=group.group and orderdetails.order='".$shopcoinsorder."'  and orderdetails.status=0";
					//$result2 = mysql_query($sql2);
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
    $tpl['orderdetails']['ArrayShopcoinsInOrder'][$i]['amountAll'] = $shopcoins_class->getItemAmount($rows["catalog"],$shopcoinsorder);
	
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
