<?
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';
require $cfg['path'] . '/configs/config_shopcoins.php';

$order = request("order");
$action = request("action");
$page2 = request("page2");

$type = Array ("rusichbank"=>'Монеты',
				"shopcoins"=>'Монеты',
				"Album"=>'Аксессуары',
				"Book"=>'Книги',
				"programs"=>'Программы');
				


if (!$tpl['user']['user_id']){
    $tpl['shop']['errors'][] = "Авторизуйтесь!";
    header("location: ".$cfg['site_dir']."shopcoins?page=orderdetails");
    die();	
} else {
    $order_class = new model_order($cfg['db'],$shopcoinsorder,$tpl['user']['user_id']);
    $orderdetails_class = new model_orderdetails($cfg['db']);
	if (!$action) $action = "showorders";
	
	if ($action=="postreceipt" && $parent) {
	    die("postreceipt");
		$mark = intval($mark);
		$parent = intval($parent);
		$Reminder = intval($Reminder);
		if ($Reminder==3) {
			$complected = intval($complected);
		} else	$complected = 0;
		
		$sql_up = "UPDATE `order` SET ".($Reminder==3?"`PhonePostReceipt`='1',":"")." Reminder='$Reminder', ReminderComment='".strip_string($ReminderComment)."', 
	ReminderCommentDate = '".time()."', mark='".$mark."',complected=$complected WHERE `order`='".$parent."'";
		$result_up = mysql_query($sql_up);
		//echo $sql_up;
		$action = "showorders";
	}
	
	$tpl['task'] = $action;
	
	if ($action=="showorders")	{		
		$tpl['orders'] = $order_class->getLastOrders();		
		$i = 0;
		foreach ($tpl['orders'] as $rows) {				    	
			$dissert = 0;
						
			if (!$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"]) {
				$result9 = $order_class->getOrdergiftcertificate($rows["order"]);
				foreach ((array)$result9 as $rows9)
					$dissert += $rows9['sum'];
				
			}
			$tpl['orders'][$i]['dissert'] = $dissert;
			/*if (!$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"] && $rows["ParentOrder"]==0 && (($rows['payment'] !=1 && $rows['payment'] !=2 && ($rows['delivery']==4 || $rows['delivery']==6)) || (($rows['delivery']==10 || $rows['delivery']==2) && ($ipmyshop==$_SERVER['REMOTE_ADDR'] || $_SERVER['REMOTE_ADDR']=="127.0.0.1")))) */
			{
			
				$resultsum = ($rows['SumAll']>0?$rows['SumAll']:($rows['FinalSum']>0?$rows['FinalSum']:$rows['sum']))-$dissert;
				
				if ($rows["delivery"] == 4 || $rows["delivery"] == 6 || $rows['delivery']==10 || $rows['delivery']==2) {
	
					$shopcoinsorders = array();
					
					$shopcoinsorders[] = $rows['order'];
					$result3 = $order_class->getAllByParams(array('ParentOrder'=>$rows['order']));

					foreach ($result3 as $rows3) {					
						$shopcoinsorders[] = $rows3['order'];
					}
					
					if (sizeof($shopcoinsorders)<2)
						$shopcoinsorder = $shopcoinsorders[0];
					
					$clientdiscount = $orderdetails_class->getClientdiscount($tpl['user']['user_id']);
					$postindex = 0;
					
					preg_match_all('/\d{6}/', $rows["adress"], $found);		
	
    				if ($found&&isset($found[0])&&isset($found[0][0])){
    					$postindex = trim($found[0][0]);
    				}			
					
					$checking = 1;
					
					//unset ($PostAllPrice);
					//unset ($suminsurance);
					
					if (!$postindex) $postindex = "690000";
					
					//if ($postindex){
						$bastet_details = $orderdetails_class->PostSum($postindex, $clientdiscount, $shopcoinsorder);
						$bascetsum = $bastet_details['bascetsum'];
						$bascetpostweight = $bastet_details['bascetpostweight'];	
						$PostAllPrice = $bastet_details['PostAllPrice'];				
					//}
				
					if ($rows["delivery"] == 6) {
					
						if ($bascetpostweight < 1000) 
							$sumEMC = 650;
						else {
						
							$sumEMC = 650;
							$sumEMC += ceil(($bascetpostweight - 1000)/500)*50;
						}
						
						$resultsum = ($bascetsum+$PriceLatter+10+$sumEMC);
					} elseif($rows['delivery']==10 || $rows['delivery']==2)
						$resultsum = $bascetsum;
					else	
						$resultsum = $PostAllPrice;
				}

				
				$tpl['orders'][$i]['crcode']  = md5("numizmatikru:".sprintf ("%01.2f",round($resultsum*$krobokassa-$dissert,2)).":".$rows['order'].":$robokassapasword1:Shp_idu=".$tpl['user']['user_id']);				
				
				$tpl['orders'][$i]['OutSum'] = sprintf ("%01.2f",round($resultsum*$krobokassa-$dissert,2));				
			}
			
			if (!$rows["SendPost"] && !$rows["ReceiptMoney"] && !$rows["SendPostBanderoleNumber"] && $rows["ParentOrder"]==0 && $rows['payment'] !=1 && 1==2) {
			
				echo "<div id=sertificate".$rows["order"]."><input type=button value='Использовать подарочный сертификат' onclick=\"javascript:ShowSertificate(".($rows["delivery"] == 4 || $rows["delivery"] == 6?1:0).",".$rows["order"].");\"></div>";
			}
			
			echo "</nobr></td>
			<td class=tboard>".($rows["SendPostBanderoleNumber"]?$rows["SendPostBanderoleNumber"]."<br><center>[ <form action=\"http://www.russianpost.ru/resp_engine.aspx?Path=rp/servise/ru/home/postuslug/trackingpo\" method=post target=_blank>
			<input type=hidden name=searchsign value=1>
			<input type=hidden name=BarCode value=".$rows["SendPostBanderoleNumber"].">
			<input type=submit name=submit value=Отследить class=formtxt ></form> ]</center>":"-")."</td>
			<td class=tboard>".($rows["weight"]>0?$rows["weight"]." кг.":"")."</td>
			<td class=tboard>".round($rows["sum"])."</td>
			<td class=tboard>".($rows["FinalSum"]>0 && date("Y", $rows["date"])>=2008?$rows["FinalSum"]:"-")."</td>
			<td class=tboard><a name=order".$rows["order"]."></a><div id=PhonePostReceipt".$rows["order"].">".($rows["Reminder"]==3?"<b>Получен</b>":"<a href=#order".$rows["order"]." onclick=\"javascript:ShowFormPhonePostReceipt('".$rows["order"]."','".$rows["Reminder"]."','".$rows['mark']."','".$rows['complected']."');\">Сообщить</a></div>")." ".($rows["ReminderComment"]?"<br>".$rows["ReminderComment"]:"")."</td>
			<td class=tboard align=center>";
			if ($rows["ParentOrder"]==0 && ($rows["payment"]==3 || $rows["payment"]==4 || $rows["payment"]==5 || $rows["payment"]==6) )
			{
				echo ($rows["ReceiptMoney"]>0?date("y-m-d",$rows["ReceiptMoney"]):"<b><font color=red>НЕТ</font></b>");
			}
			else
			{
				echo "-";
			}
			$i++;			
		}

	} elseif ($action=="showorderhtml" and $order) {
		//формируем отчет в html
		//монеты
		$order = intval($order);
		echo "<html>
		<head>
		<title>Отчет</title>
		<meta name=\"keywords\">
		<meta name=\"description\">
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">
		</head>
		<link rel=stylesheet type=text/css href=".$in."bodka.css>
		<link rel=\"SHORTCUT ICON\" href=\"".$in."favicon.ico\"> 
		<body bgcolor=white bottommargin=10 leftmargin=10 rightmargin=10 marginheight=10 topmargin=10 marginwidth=10>";
		
		echo "<br><b class=txt>Номер заказа $order</b>";
		$sql = "select o.*, o.admincheck, u.fio, u.email from `order` as o left join user as u on o.user=u.user
		where o.order='".$order."';";
		$result = mysql_query($sql);
		echo "<table border=1 cellpadding=1 cellspacing=0>
		<tr><td class=tboard bgcolor=silver colspan=2><b>Информация о покупателе</b> [<a href=# onclick=\"javascitp:window.open('shopcoinsuseredit.php?order=$order','shopcoinsuseredit".$order."','width=400,height=300, scrollbars=1');\">Редактировать</a>]</td></tr>";
		while ($rows = mysql_fetch_array($result))
		{
			echo "<tr><td class=tboard>ФИО:</td><td class=tboard>&nbsp;".$rows["fio"]."</td></tr>";
			echo "<tr><td class=tboard>Город:</td><td class=tboard>&nbsp;";
			if (!ereg("^\-{0,1}[0-9]{1,}$", $rows["city"])) {$city = $rows["city"];} else {$city = $city_array[$rows["city"]];}
			echo "$city</td></tr>";
			echo "<tr><td class=tboard>Контактный телефон:</td><td class=tboard>".$rows["phone"]."</td></tr>";
			echo "<tr><td class=tboard>E-mail:</td><td class=tboard>&nbsp;<a href=mailto:".$rows["email"].">".$rows["email"]."</a></td></tr>";
			if ($rows["adress"])
				echo "<tr><td class=tboard>Адрес доставки:</td><td class=tboard>".str_replace("\n", "<br>", $rows["adress"])."</td></tr>";
			echo "<tr><td class=tboard>Тип оплаты:</td><td class=tboard>".$SumName[$rows["payment"]]."</td></tr>";
			
			if ($rows["payment"] == 2)
			{
				if ($rows["MetroMeeting"])
					echo "<tr><td class=tboard>Метро:</td><td class=tboard>".$MetroArray[$rows["MetroMeeting"]]."</td></tr>";
				if ($rows["DateMeeting"])
					echo "<tr><td class=tboard>Время встречи:</td><td class=tboard>".date("Y-m-d H:i", $rows["DateMeeting"])."</td></tr>";
			}
			if ($rows["OtherInformation"])
				echo "<tr><td class=tboard>Другая информация:</td><td class=tboard>".str_replace("\n", "<br>", $rows["OtherInformation"])."</td></tr>";
			
			$admincheck = $rows["admincheck"];
			$adress_recipient = $city.", ".str_replace("\n", " ", $rows["adress"]);
			$fio_recipient = $rows["fio"];
			$type = $rows["type"];
			$checking = 1;
			if (!$postindex) 
				$postindex = intval(substr(trim($rows['adress']),0,6));
			$shopcoinsorder = $rows['order'];
			PostSum ($postindex, $shopcoinsorder,$clientdiscount);
		}
		echo "</table>";
		
		//echo $type;
		
		//монеты
		if ($type =="shopcoins")
		{
			
			$sql_tmp = "select count(*) from `order` where `user`='".$authorization."' and `user`<>811 and `check`=1 and `order`<'".$order."' and `date`>(".time()."-365*24*60*60);";
			$result_tmp = mysql_query($sql_tmp);
			$rows_tmp = mysql_fetch_array($result_tmp);
			if ($rows_tmp[0]>=3)
				$clientdiscount = 1;
			else
				$clientdiscount = 0;
			//echo "<br>".$sql_tmp."<br>";
			//содержимое заказа
			$order_doc .= "<b class=txt>Содержимое заказа:</b>";
			$order_doc .= "<table border=0 cellpadding=3 cellspacing=1 align=center width=100% >
			<tr bgcolor=#ffcc66>
			<td class=tboard><b>Изображение</b></td>
			<td class=tboard><b>Описание</b></td>
			<td class=tboard><b>Изображение</b></td>
			<td class=tboard><b>Описание</b></td>
			</tr>";
			$sql = "select o.*, c.name, if(o.amount>=c.amount5 and c.price5>0,c.price5,
				if 
				(o.amount>=c.amount4 and c.price4>0,c.price4,
					if
					(o.amount>=c.amount3 and c.price3>0,c.price3,
						if
						(o.amount>=c.amount2 and c.price2>0,c.price2,
							if
							(o.amount>=c.amount1 and c.price1>0,c.price1,".($clientdiscount==1?"if(c.clientprice>0, c.clientprice, c.price)":"c.price").")
						)
					)
				)
			) as price, c.image, c.metal, c.year, 
			c.condition, c.number, c.shopcoins, g.name as gname, c.materialtype, c.details
			 from `orderdetails` as o left join shopcoins as c 
			on o.catalog = c.shopcoins 
			left join `group` as g on c.group=g.group 
			where o.order='".$order."' and o.typeorder=1 and o.status=0 order by c.materialtype, c.number;";
			$result = mysql_query($sql);
			$sum = 0;
			$what = "";
			$k=0;
			$oldmaterialtype = 0;
			
			while (	$rows = mysql_fetch_array($result))
			{
				
				if ($oldmaterialtype != $rows["materialtype"])
				{
					
					if ($k%2 == 0 and $k!= 0)
						$order_doc .= "</tr>";
					elseif ($k%2 == 0 and $k==0)
					{}
					else
						$order_doc .= "<td>&nbsp;</td><td>&nbsp;</td></tr>";
					
					if ($k!=0)
						$order_doc .= "<tr><td colspan=4 class=tboard><b>".$MaterialTypeArray[$oldmaterialtype].": $k</b></td></tr>";
					
					$order_doc .= "<tr><td colspan=4 class=tboard bgcolor=#99CCFF><b>".$MaterialTypeArray[$rows["materialtype"]]."</b></td></tr>";
					$oldmaterialtype = $rows["materialtype"];
					$k = 0;
				}
				
				if ($k%2==0)
				{
					if ($k!=0)
						$order_doc .= "</tr><tr bgcolor=#EBE4D4 valign=top>";
					else
						$order_doc .= "<tr bgcolor=#EBE4D4 valign=top>";
				}
				$what .= $rows["name"].", ";
				$temp = GetImageSize($DOCUMENT_ROOT."/shopcoins/images/".$rows["image"]);
				$imagewidth = $temp[0];
				$order_doc .= "<td class=tboard width=220>
				<img src=../shopcoins/images/".$rows["image"]." border=1 width=\"$imagewidth\" WIDTH='$imagewidth' width=$imagewidth WIDTH=$imagewidth>
				</td>";
				
				if ($rows["materialtype"]==1 || $rows["materialtype"]==10 || $rows["materialtype"]==12)
				{
				
					$order_doc .= "<td class=tboard valign=top width=200>
					<a name=s".$rows["shopcoins"]."></a>";
					$order_doc .= "<br>".$rows["name"];
					if ($rows["year"]) $order_doc .= "<br><b>Год: </b>".$rows["year"];
					if (trim($rows["metal"])) $order_doc .= "<br><b>Метал: </b>".$rows["metal"];
					if ($rows["gname"]) $order_doc .=  "<br><b>Страна: </b>".$rows["gname"];
					if ($rows["number"]) $order_doc .=  "<br><b>Каталог: </b>".$rows["number"];
					if ($rows["condition"]) $order_doc .=  "<br><b>Состояние: </b>".$rows["condition"];
					if ($rows["details"]) $order_doc .=  "<br><b>Описание: </b>".str_replace("\n","<br>",$rows["details"]);
					$order_doc .= "<br><b>Цена: </b> ".round($rows["price"],2)." руб.
					</td>";
				}
				elseif ($rows["materialtype"]==8  or $rows["materialtype"]==7 or $rows["materialtype"]==4 or $rows["materialtype"]==2)
				{
					$order_doc .= "<td class=tboard valign=top width=200>
					<a name=s".$rows["shopcoins"]."></a>";
					$order_doc .= "<br>".$rows["name"];
					$order_doc .= "<br><b>Количество: <font color=blue>".($rows["amount"]?$rows["amount"]:"1")."</font></b>";
					if ($rows["year"]) $order_doc .= "<br><b>Год: </b>".$rows["year"];
					if (trim($rows["metal"])) $order_doc .= "<br><b>Метал: </b>".$rows["metal"];
					if ($rows["gname"]) $order_doc .=  "<br><b>Страна: </b>".$rows["gname"];
					if ($rows["number"]) $order_doc .=  "<br><b>Каталог: </b>".$rows["number"];
					if ($rows["condition"]) $order_doc .=  "<br><b>Состояние: </b>".$rows["condition"];
					if ($rows["details"]) $order_doc .=  "<br><b>Описание: </b>".str_replace("\n","<br>",$rows["details"]);
					$order_doc .= "<br><b>Цена: </b> ".round($rows["price"],2)." руб.
					</td>";
				}
				elseif ($rows["materialtype"]==3)
				{
					$order_doc .= "<td class=tboard valign=top width=200>
					<a name=s".$rows["shopcoins"]."></a>";
					$order_doc .= "<br>".$rows["name"];
					$order_doc .= "<br><b>Количество: <font color=blue>".$rows["amount"]."</font></b>";
					if ($rows["gname"]) $order_doc .=  "<br><b>Группа: </b>".$rows["gname"];
					if ($rows["accessoryProducer"]) $order_doc .= "<br><b>Изготовитель: </b>".$rows["accessoryProducer"];
					if (trim($rows["accessoryColors"])) $order_doc .= "<br><b>Цвета: </b>".$rows["accessoryColors"];
					if ($rows["accessorySize"]) $order_doc .=  "<br><b>Размеры: </b>".$rows["accessorySize"];
					if ($rows["details"]) $order_doc .=  "<br><b>Описание: </b>".str_replace("\n","<br>",$rows["details"]);
					$order_doc .= "<br><b>Цена: </b> ".round($rows["price"],2)." руб.
					</td>";
				}
				$shopcoins_string .= "#".$rows["shopcoins"];
				//$order_doc .=  "</td><td class=tboard>".$rows["price"]." руб.</td>";
				$sum += $rows["amount"]*$rows["price"];
				$k++;
			}
			if ($k%2 == 0)
				$order_doc .= "</tr>";
			else
				$order_doc .= "<td>&nbsp;</td><td>&nbsp;</td></tr>";
			$what = substr($what, 0, -2);
			
			if ($discountcoupon>0) {
			
				$order_doc .=  "<tr><td colspan=2 class=tboard><b>".$MaterialTypeArray[$oldmaterialtype].": $k</b></td><td class=tboard align=right><b>Сумма заказа:</b></td><td class=tboard>".$sum." руб.</td></tr>";
				$order_doc .=  "<tr bgcolor=#ddaaee><td colspan=2 class=tboard><b>&nbsp;</b></td><td class=tboard align=right><b>Скидка по купону(ам):</b></td><td class=tboard>".$discountcoupon." руб.</td></tr>";
				$order_doc .=  "<tr><td colspan=2 class=tboard><b>&nbsp;</b></td><td class=tboard align=right><b>Итого:</b></td><td class=tboard>".(($sum-$discountcoupon<0)?0:$sum-$discountcoupon)." руб.</td></tr>";
			}
			else
				$order_doc .=  "<tr><td colspan=2 class=tboard><b>".$MaterialTypeArray[$oldmaterialtype].": $k</b></td><td class=tboard align=right><b>Итого:</b></td><td class=tboard>".$sum." руб.</td></tr>";
			
			$order_doc .=  "</table>";
			
			echo $order_doc;
			
		} elseif ($type=="Book") {
			$BookImagesFolder = $in."book/images";
			//сначала о пользователе
			/*
			echo "<a href=$script?frame=$frame&pagenum=$pagenum&search=$search&sendposttype=$sendposttype&PaymentOrder=$PaymentOrder&type=".$_GET["type"]."><img src=".$in."images/back.gif alt='Назад' border=0></a>";
			echo "<br><b class=txt>Номер заказа $order</b>";
			$sql = "select o.*, o.admincheck, u.fio, u.email from `order` as o left join user as u on o.user=u.user
			where o.order='".$order."' and type='$type';";
			$result = mysql_query($sql);
			echo "<table border=1 cellpadding=3 cellspacing=0>
			<tr><td class=tboard bgcolor=silver colspan=2><b>Информация о покупателе</b></td></tr>";
			while ($rows = mysql_fetch_array($result))
			{
				echo "<tr><td class=tboard>ФИО:</td><td class=tboard>&nbsp;".$rows["fio"]."</td></tr>";
				echo "<tr><td class=tboard>Город:</td><td class=tboard>";
				if (!ereg("^\-{0,1}[0-9]{1,}$", $rows["city"])) {$city = $rows["city"];} else {$city = $city_array[$rows["city"]];}
				echo "$city</td></tr>";
				echo "<tr><td class=tboard>Контактный телефон:</td><td class=tboard>".$rows["phone"]."</td></tr>";
				echo "<tr><td class=tboard>E-mail:</td><td class=tboard>&nbsp;<a href=mailto:".$rows["email"].">".$rows["email"]."</a></td></tr>";
				echo "<tr><td class=tboard>Адрес доставки:</td><td class=tboard>".str_replace("\n", "<br>", $rows["adress"])."</td></tr>";
				echo "<tr><td class=tboard>Тип оплаты:</td><td class=tboard>";
				if ($rows["payment"]=="webmoney") {
					echo "Webmoney";
					$discount = 0.1;
				} elseif ($rows["payment"]=="yandexmoney") {
					echo "Yandex.Деньги";
					$discount = 0.1;
				} else {
					echo "Наложенный платеж";
					$discount = 0;
				}
				echo "</td></tr>";
				$admincheck = $rows["admincheck"];
				$adress_recipient = $city.", ".str_replace("\n", " ", $rows["adress"]);
				$fio_recipient = $rows["fio"];
			}
			echo "</table>";
			*/
			//содержимое заказа
			echo "<br><b class=txt>Содержимое заказа:</b>";
			echo "<table border=0 cellpadding=3 cellspacing=1>
			<tr bgcolor=#ffcc66>
			<td class=tboard><b>Изображение</b></td>
			<td class=tboard><b>Описание</b></td>
			<td class=tboard><b>Цена</b></td>
			<td class=tboard><b>Количество</b></td>
			</tr>";
			$sql = "select o.*, c.*
			 from `orderdetails` as o left join Book as c 
			on o.catalog = c.BookID where o.order='".$order."' and o.typeorder=1 and o.status=0 order by o.orderdetails;";
			$result = mysql_query($sql);
			$sum = 0;
			$what = "";
			while (	$rows = mysql_fetch_array($result))
			{
				echo "<tr bgcolor=#EBE4D4 valign=top>
				<td class=tboard width=300><a href=".$server_name."/book/index.php?catalog=".$rows["Book"]."&PaymentOrder=$PaymentOrder&type=$type target=_blank>".$rows["BookName"]."</a>
				<br><img src=$BookImagesFolder/".$rows["BookImage"].">
				</td><td class=tboard valign=top>";
				if ($rows["BookDetails"]) echo "<br><b>Подробнее: </b>".str_replace("\n", "<br>", $rows["BookDetails"]);
				echo "</td><td class=tboard>".round($rows["BookPrice"], 2)." руб.</td>
				<td class=tboard align=center>".$rows["amount"]."</td>";
				$sum += $rows["amount"]*$rows["BookPrice"];
				echo "</tr>";
				$what .= $rows["BookName"];
				if ($rows["amount"]>1)
					$what .= "(".$rows["amount"]." шт.)";
				$what .= ", ";
			}
			echo "<tr><td>&nbsp;</td><td class=tboard align=right><b>Итого:</b></td><td class=tboard>".round((1-$discount)*$sum, 2)." руб.</td><td class=tboard>&nbsp;</td></tr>";
			echo "</table>";
		} elseif ($type=="Album") {
			$AlbumImagesFolder = $in."album/images";
			/*
			//сначала о пользователе
			echo "<a href=$script?frame=$frame&pagenum=$pagenum&search=$search&PaymentOrder=$PaymentOrder&type=".$_GET["type"]."><img src=".$in."images/back.gif alt='Назад' border=0></a>";
			echo "<br><b class=txt>Номер заказа $order</b>";
			$sql = "select o.*, o.admincheck, u.fio, u.email from `order` as o left join user as u on o.user=u.user
			where o.order='".$order."' and type='$type';";
			$result = mysql_query($sql);
			echo "<table border=1 cellpadding=3 cellspacing=0>
			<tr><td class=tboard bgcolor=silver colspan=2><b>Информация о покупателе</b></td></tr>";
			while ($rows = mysql_fetch_array($result))
			{
				echo "<tr><td class=tboard>ФИО:</td><td class=tboard>&nbsp;".$rows["fio"]."</td></tr>";
				echo "<tr><td class=tboard>Город:</td><td class=tboard>";
				if (!ereg("^\-{0,1}[0-9]{1,}$", $rows["city"])) {$city = $rows["city"];} else {$city = $city_array[$rows["city"]];}
				echo "$city</td></tr>";
				echo "<tr><td class=tboard>Контактный телефон:</td><td class=tboard>".$rows["phone"]."</td></tr>";
				echo "<tr><td class=tboard>E-mail:</td><td class=tboard>&nbsp;<a href=mailto:".$rows["email"].">".$rows["email"]."</a></td></tr>";
				echo "<tr><td class=tboard>Адрес доставки:</td><td class=tboard>".str_replace("\n", "<br>", $rows["adress"])."</td></tr>";
				echo "<tr><td class=tboard>Тип оплаты:</td><td class=tboard>";
				if ($rows["payment"]=="webmoney") {
					echo "Webmoney";
					$discount = 0.1;
				} elseif ($rows["payment"]=="yandexmoney") {
					echo "Yandex.Деньги";
					$discount = 0.1;
				} else {
					echo "Наложенный платеж";
					$discount = 0;
				}
				echo "</td></tr>";
				$admincheck = $rows["admincheck"];
				$adress_recipient = $city.", ".str_replace("\n", " ", $rows["adress"]);
				$fio_recipient = $rows["fio"];
			}
			echo "</table>";
			*/
			//содержимое заказа
			echo "<br><b class=txt>Содержимое заказа:</b>";
			echo "<table border=0 cellpadding=3 cellspacing=1>
			<tr bgcolor=#ffcc66>
			<td class=tboard><b>Изображение</b></td>
			<td class=tboard><b>Описание</b></td>
			<td class=tboard><b>Цена</b></td>
			<td class=tboard><b>Количество</b></td>
			</tr>";
			$sql = "select o.*, 
			c.*
			 from `orderdetails` as o left join Album as c 
			on o.catalog = c.AlbumID where o.order='".$order."' and o.typeorder=1 and o.status=0 order by o.orderdetails;";
			$result = mysql_query($sql);
			$sum = 0;
			$what = "";
			while (	$rows = mysql_fetch_array($result))
			{
				echo "<tr bgcolor=#EBE4D4 valign=top>
				<td class=tboard width=300><a href=".$server_name."/album/index.php?catalog=".$rows["Album"]."&PaymentOrder=$PaymentOrder&type=$type target=_blank>".$rows["AlbumName"]."</a>
				<br><img src=$AlbumImagesFolder/".$rows["AlbumImage"].">
				</td><td class=tboard valign=top>";
				if ($rows["AlbumDetails"]) echo "<br><b>Подробнее: </b>".str_replace("\n", "<br>", $rows["AlbumDetails"]);
				echo "</td><td class=tboard>".round($rows["AlbumPrice"], 2)." руб.</td>
				<td class=tboard align=center>".$rows["amount"]."</td>";
				$sum += $rows["amount"]*$rows["AlbumPrice"];
				echo "</tr>";
				$what .= $rows["AlbumName"];
				if ($rows["amount"]>1)
					$what .= "(".$rows["amount"]." шт.)";
				$what .= ", ";
			}
			echo "<tr><td>&nbsp;</td><td class=tboard align=right><b>Итого:</b></td><td class=tboard>".round((1-$discount)*$sum, 2)." руб.</td><td class=tboard>&nbsp;</td></tr>";
			echo "</table>";
		}
		
		
		echo "<center>[<img src=../images/printer.gif> <a href=# onclick=\"window.print();\">Распечатать</a>]</center>";
		echo "</body>
		</html>";
	} else {
		echo "Для просмотра заказов необходима авторизация!";
		echo form_login($error);
	}
}


?>
