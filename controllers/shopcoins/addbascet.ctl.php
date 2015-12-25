<?
require $cfg['path'] . '/configs/config_shopcoins.php';

require_once $cfg['path'] . '/models/helpshopcoinsorder.php';
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';

$shopcoins = (integer)request('shopcoins');
$onefio = request('onefio');
$onephone = request('onephone');
$amount = (integer) request('amount');
$basket_type = request('basket_type');


$erroramount = "";
if (!$amount) $amount = 1;

$helpshopcoinsorder_class = new model_helpshopcoinsorder($cfg['db']);
$order_class = new model_order($cfg['db']);
$orderdetails_class = new model_orderdetails($cfg['db']);

$data_result = array();
$data_result['error'] = null;


if (!$shopcoins) $data_result['error'] = "noshopcoins";	

$rows = $shopcoins_class->getRowByParams(array('shopcoins'=>$shopcoins));

if(!$rows){
    $data_result['error'] = "noshopcoins";
} else  {
	$price = $rows['price'];
	$ShopcoinsMaterialtype = $rows["materialtype"];
	
	if ($rows['group'] == 1604)	$data_result['error'] = "notavailable";
	elseif (in_array($ShopcoinsMaterialtype, array(8,6,7,4,2))) {
		$result_amount = $helpshopcoinsorder_class->getAllByParams(array('shopcoins'=>$shopcoins));    	
		
		$amountreserve = 0;
		foreach ($result_amount as $rows_amount) {    	
    		if (time()-$rows_amount["reserve"] < $reservetime ) 
    			$amountreserve++;
    	}    	
    	if (!$rows["amount"]) $rows["amount"] = 1;
    	
    	if ($rows["amount"] <= $amountreserve || $amount > ($rows["amount"] - $amountreserve)) {    	
    		$data_result['error'] = "reserved"; 
    		$erroramount = $rows["amount"];
    	}
	} else {	
		//doublereserve на doubletimereserve
		if ($rows["reserve"]>0 || $rows['doubletimereserve']>time() and  $basket_type != 'ShowSmallBascet__12') {
			//уже кто то забронировал
			if ((time()-$rows["reserve"] < $reservetime and $rows["reserveorder"] != $shopcoinsorder) || ($rows['doubletimereserve']>time() && $rows['userreserve']!=$tpl['user']['user_id'] && $tpl['user']['user_id']>0))
				$data_result['error'] ="reserved";
		}
	}
	
	if (($ShopcoinsMaterialtype==3 || $ShopcoinsMaterialtype==5) and $rows["amount"] < $amount)
	{
		$data_result['error'] ="amount";
		$erroramount = $rows["amount"];
	}
	
	if ($rows["check"] == 0) $data_result['error'] ="notavailable";	
	// сумма всех заказов
	$mysum = $orderdetails_class->getMySum($shopcoinsorder);

	if (($amount?$amount:1)*$price + $mysum > $stopsummax)	$data_result['error'] ="stopsummax";

	if ($shopcoinsorder) {	
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

	  if (!$data_result['error'])	{
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
		$rows_info = $orderdetails_class->getRowByParams(array('`order`'=>$shopcoinsorder,'catalog'=>$shopcoins)); 
		
		if ($rows_info){			
			if ($ShopcoinsMaterialtype==8 || $ShopcoinsMaterialtype==6 || $ShopcoinsMaterialtype==7 || $ShopcoinsMaterialtype==4 || $ShopcoinsMaterialtype==2) {
			
				$amountsql = $rows_info['amount']+($amount?$amount:1);
			}
			else $amountsql = $amount;
			
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
		if ($ShopcoinsMaterialtype == 1 || $ShopcoinsMaterialtype == 10 || $ShopcoinsMaterialtype == 11 || $ShopcoinsMaterialtype == 9 || $ShopcoinsMaterialtype == 12) {
			$data_update = array('reserveorder'=>$shopcoinsorder, 
    		                     'reserve' => time(), 
    		                     'doubletimereserve'=>0, 
    		                     'userreserve'=>0);  	
    		                     
    	    $shopcoins_class->updateRow($data_update,"shopcoins='$shopcoins'");           
			$_SESSION["shopcoinsorderamount"] =  intval($shopcoinsorderamount)+1;
			
		} elseif ($ShopcoinsMaterialtype == 4 || $ShopcoinsMaterialtype == 7 || $ShopcoinsMaterialtype == 8 || $ShopcoinsMaterialtype == 6 || $ShopcoinsMaterialtype==2) {
			for ($i=0;$i<$amount;$i++) {
				$data_insert = array('reserveorder'=>$shopcoinsorder, 
    		                     'reserve' => time(), 
    		                     'shopcoins'=>$shopcoins, 
    		                     'helpshopcoinsorder'=>0);  	
				$helpshopcoinsorder_class->addNewRecord($data_insert);			
			}
			$_SESSION["shopcoinsorderamount"] =  intval($shopcoinsorderamount)+($amount?$amount:1);
		} else {
		    $_SESSION["shopcoinsorderamount"] =  intval($shopcoinsorderamount)+($amount?$amount:1);
		}

		//пересчет карзины
		$clientdiscount = $order_class->getClientdiscount($tpl['user']['user_id'],$shopcoinsorder);
		$dataBasket = $order_class->forBasket($clientdiscount,$shopcoinsorder);
		$cache->save($dataBasket, "bascet_".$shopcoinsorder);	

		$bascetsum = $dataBasket["mysum"];
		$_SESSION['bascetsum'] = $bascetsum;
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
		
		//$sql2 = "select shopcoins.*, group.name as gname from shopcoins,orderdetails,`group` where shopcoins.shopcoins=orderdetails.catalog and shopcoins.group=group.group and orderdetails.order='".$shopcoinsorder."'  and orderdetails.status=0";
		//$result2 = mysql_query($sql2);
	}
}

if (!$data_result['error']){
	//$xml .= "<error>none</error>
	$data_result['shopcoinsorder'] = $shopcoinsorder;
	$data_result['bascetshopcoins'] = $shopcoins;
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
	$data_result['erroramount'] = $erroramount;
	$data_result['bascetshopcoins'] = $shopcoins?$shopcoins:"none";	
}

echo json_encode($data_result);

die();

function Bascet($shopcoinsorder)
{
	
	//$shopcoinsorder = intval($shopcoinsorder);
	global $reservetime, $PostZone, $PostZone1, $PackageAddition, $mintime;
	global $bascetsum, $bascetsumclient, $bascetweight, $bascetinsurance, $bascetamount, $orderstarttime;
	global $bascetreservetime, $bascetpostweightmin, $bascetpostweightmax, $WeightCoins,$clientdiscount,$textbascet2;
	//$clientdiscount = intval($clientdiscount);
}
	/*$textbascet2 = "<table width=100% border=0>";
	$numc = 0;
	while ($rows2 = mysql_fetch_array($result2)){
	
		$detailsb = '';
		if ($rows2['materialtype']!=3) {
			if ($rows2["gname"])
				$detailsb .=  ($rows2["materialtype"]==9?"Группа":"Страна").":  <strong><font color=blue']=$rows2["gname"]."</font></strong>";
			if ($rows2["name"])
				$detailsb .= "<br>Название: <strong']=$rows2["name"]."</strong>";
			$detailsb .= "<br>Номер: <strong']=$rows2["number"]."</strong><br>".($rows2["materialtype"]==8||$rows2["materialtype"]==6?"Цена":"Стоимость").": <strong><font color=red>".($rows2["price"]==0?"бесплатно":round($rows2["price"],2)." руб.")."</font></strong>";
			
			$detailsb .= (trim($rows2["metal"])?"<br>Металл: <strong']=$rows2["metal"]."</strong>":"").($rows2["width"]&&$rows2["height"]?"<br>Приблизительный размер: <strong']=$rows2["width"]."*".$rows2["height"]." мм.</strong>":"").($rows2["weight"]>0?"<br>Вес: <strong']=$rows2["weight"]." гр.</strong>":"").($rows2["year"]?"<br>Год: <strong']=$rows2["year"]."</strong>":"").(trim($rows2["condition"])?"<br>Состояние: <strong><font color=blue']=$rows2["condition"]."</font></strong>":"");
		}
		else
			$detailsb = "Группа:  <strong><font color=blue']=$rows2["gname"]."</font></strong><br>Название: <strong']=$rows2["name"]."</strong>".($rows2["number"]?"<br>Номер:<strong> ".$rows2["number"]."</strong>":"")."<br>".($rows2["materialtype"]==8||$rows2["materialtype"]==6?"Цена":"Стоимость").": <strong><font color=red>".($rows2["price"]==0?"бесплатно":round($rows2["price"],2)." руб.")."</font></strong>".($rows2["accessoryProducer"]?"<br>Производитель:<strong> ".$rows2["accessoryProducer"]."</strong>":"").($rows2["accessoryColors"]?"<br>Цвета:<strong> ".$rows2["accessoryColors"]."</strong>":"").($rows2["accessorySize"]?"<br>Размеры:<strong> ".$rows2["accessorySize"]."</strong>":"");
		
		if (trim($rows2["details"]))
		{
			$text = substr($rows2["details"], 0, 250);
			$text = strip_tags($text);
			$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
			$text = str_replace("\r","",$text);
			$detailsb .= "<br>Описание: ".str_replace("\n","<br>",$text)."";
		}
		
		$textbascet2 .= "<tr class=tboard><td valign=top id=imageb$numc><img src=./smallimages/".$rows2['image_small']." border=1 style=\"border-color:black\" onMouseOver=\"javascript:ShowMainCoins('b$numc','<img src=./images/".$rows2['image_big']." border=1>','".htmlspecialchars($detailsb)."');\" onMouseOut=\"javascript:NotShowMainCoina('b$numc');\"><td valign=top><div id=showb$numc></div><a href=index.php?page=show&group=".$rows2['group']."&materialtype=".$rows2['materialtype']."&catalog=".$rows2['shopcoins']."   style=\"font-weight:200\"><font color=black']=$rows2['gname']."<br']=$rows2['name']."</font><br><font color=red>".intval($rows2['price'])." р.</font></a></td></tr>";
	}
	$textbascet2 .= "</table>";
	
	//сохранение корзины через template
	$content = join("\n", file("bascet.tpl"));
	
	$content = str_replace("___shopcoinsorder___", $shopcoinsorder, $content);
	$content = str_replace("___bascetamount___", $bascetamount, $content);
	$content = str_replace("___bascetsum___", $bascetsum, $content);
	if ($bascetsumclient>0) 
		$bascetsumclient2 = "
<br><b>Для постоянных клиентов:</b> ".$bascetsumclient." р.";
	else
		$bascetsumclient2 = '';
	$content = str_replace("___bascetsumclient___", $bascetsumclient2, $content);
	$content = str_replace("___bascetweight___", $bascetweight, $content);
	$content = str_replace("___bascetreservetime___", $bascetreservetimestr, $content);
	$content = str_replace("___bascetpostweightmin___", $bascetpostweightmin, $content);
	$content = str_replace("___bascetpostweightmax___", $bascetpostweightmax, $content);
	$content = str_replace("___bascetinsurance___", $bascetinsurance, $content);
	$content = str_replace("___ShowWarningTimeValue___", $ShowWarningTimeValue, $content);
	$content = str_replace("___inbascet___", $textbascet2, $content);
	
	$f = fopen("bascet/".$shopcoinsorder.".php", "w");
	$input = fwrite($f, $content);
	fclose($f);
}

/*
<table border=0 cellpadding=3 cellspacing=0 style='border:thin solid 1px #000000' id=tableshopcoinsorder width=180>
<tr class=tboard bgcolor=#006699>
<td><b><font color=white>Корзина:</font></b></td>
</tr>
	
<tr class=tboard bgcolor=#ffcc66>
<td class=tboard align=top>
<b>Заказ №</b> ___shopcoinsorder___
<br><b>Товаров:</b> ___bascetamount___
<br><b>На сумму:</b> ___bascetsum___ р.___bascetsumclient___
<br><b>Вес ~</b> ___bascetweight___ гр.
<br><b>Бронь на:</b> ___bascetreservetime___ 
<center><a href=index.php?page=orderdetails><img src=../../images/basket.gif border=0></a></center>
</td>
</tr>

<tr class=tboard bgcolor=#006699>
<td><b><font color=white>Доставка:</font></b></td>
</tr>

<tr class=tboard bgcolor=#ffcc66>
<td class=tboard align=top>
<b>Москва:</b>
<br><b>Кольцевые станции:</b> бесплатно
<br><b>В офис:</b> от 170 р.
<br>
<br><b>Почта России</b>
<br><b>Сбор по весу:</b> от ___bascetpostweightmin___ до ___bascetpostweightmax___ р.
<br><b>Страховка 4%:</b> ___bascetinsurance___ руб.
<br><b>Упаковка:</b> 10 р. за конверт / ящик.
</td>
</tr>
<tr class=tboard bgcolor=#ffcc66><td><div style="display:none" id=showbascet2>___inbascet___
<center><a href=index.php?page=orderdetails><img src=../../images/basket.gif border=0></a></center></div>
</td></tr>
<tr class=tboard bgcolor=#EBE4D4><td align=center><img src=../images/windowsmaximize.gif onclick="ShowBascet2();" alt='Посмотреть содержимое'/></td></tr>	
</table>
<div id=ShowWarningTime style='position:absolute;top=0;left=0'></div>
<script language="JavaScript">
var ShowWarningTimeValue = ___ShowWarningTimeValue___;
var str = '';
if (ShowWarningTimeValue == 1)
{
	myDiv = document.getElementById("ShowWarningTime");
	str += '<table border=0 cellpadding=3 cellspacing=0 style="border:thin solid 1px #000000" id=tablesearchbytheme width=750 height=60>';
	str += '<tr class=tboard bgcolor=#ffcc66 valign=middle align=center><td>';
	str += '<b>Уважаемый пользователь!</b><br>Время бронирования истекает. Просим вас сделать заказ<br>';
	str += 'Если вы не успели все интересное сложить в корзину ';
	str += '<br> - проделайте это следующим заказом<br><br><a href=index.php?page=orderdetails><img src=../../images/basket.gif border=0></a>';
	str += '</td></tr></table>';
	myDiv.innerHTML = str;
}

</script>


*/
?>