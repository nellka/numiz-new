<?

if (!$IsCompletePagePHP)
	exit();

if ($blockend > time()) 
	$error = "Возможность добавления к предыдущим заказам приостановлена до ".date('H:i', $blockend);

if (!$error) {

	$sql90 = "select * from `order` where `order`=".intval($shopcoinsorder);
	$result90 = mysql_query($sql90);
	$rows90 = mysql_fetch_array($result90);
	if ($rows90['check']==1) 
		$error = "Заказ $shopcoinsorder уже оформлен Вами. Вы можете его просомтреть в \"Ваши заказы\"";
}

if ($cookiesuser>0 && !$error) {

	$sql_temp = "select * from `order` where `user`='$cookiesuser' and `check`=1 and SendPost=0 and sum>=500 order by `date` desc limit 1;";
	$result_temp = @mysql_query($sql_temp);
	$rows_temp = @mysql_fetch_array($result_temp);
	if (@mysql_num_rows($result_temp)==0) 
		$error = "У вас нет заказов для объединения с этим, поэтому минимальная сумма заказа должна быть 500 руб.";
}
else
	$error = "Нужна авторизация на сайте для оформления этого заказа.";

if (!$error) {

	$sql = "select `userstatus`,`sumlimit`,`timelimit`, email from `user` where userlogin='$cookiesuserlogin' and user='$cookiesuser';";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	if (!$rows['userstatus']) $userstatus = 0;
	else $userstatus = $rows['userstatus'];
	if (!$rows['sumlimit']) $sumlimit = 0;
	else $sumlimit = $rows['sumlimit'];
	if (!$rows['timelimit']) $timelimit = 0;
	else $timelimit = $rows['timelimit'];
	
	$email = $rows['email'];
	
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
	
	$sql = "select sum(orderdetails.amount*shopcoins.price) as sumallorder from `order`,orderdetails,shopcoins where ((`order`.`user`='$cookiesuser' and `order`.`user`<>811 and `order`.`check`=1 and `order`.ReceiptMoney=0) or (`order`.`order`=".$shopcoinsorder.")) and orderdetails.`order`=`order`.`order` and orderdetails.catalog=shopcoins.shopcoins and orderdetails.status=0;";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	//echo $sql."<br>";
	if ($rows[sumallorder])
		$sumallorder = $rows[sumallorder];
	else
		$sumallorder = 1;
		
	if ($sumallorder > $sumlimit && $sumlimit>0)
		$error = "Вы превысили общую сумму невыкупленных заказов.";
	
	//проверка по сумме заказа
	$sql = "select sum(orderdetails.amount*shopcoins.price) as mysum
	from orderdetails, shopcoins 
	where orderdetails.order in('".$shopcoinsorder."','".$rows_temp['order']."')  
	and orderdetails.catalog=shopcoins.shopcoins and orderdetails.status=0;";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	if ($rows["mysum"] > $stopsummax)
		$error = "Вы превысили максимальную сумму для одного заказа.";

}

if (!$error && !$addinordersubmit)
{
	
	$sql = "update `order` set user='".$rows_temp['user']."',
		userfio = '".$rows_temp['userfio']."', 
		phone='".strip_string($rows_temp['phone'])."', adress='".strip_string($rows_temp['adress'])."', 
		admincheck=0, delivery='".$rows_temp['delivery']."', payment='".strip_string($rows_temp['payment'])."',
		MetroMeeting = '".$rows_temp['MetroMeeting']."', DateMeeting = '".$rows_temp['DateMeeting']."', 
		MeetingFromTime='".$rows_temp['MeetingFromTime']."', 
		MeetingToTime='".$rows_temp['MeetingToTime']."', 
		OtherInformation='".strip_string($rows_temp['OtherInformation'])."', 
		account_number='".strip_string($rows_temp['account_number'])."', 
		NeedCall = '2'
		where `order`='".$shopcoinsorder."';";
	$result = mysql_query($sql);
	
	
}

if (!$error) { 
	
	
	$clientdiscount = 0;
	$sql_tmp = "select count(*) from `order` where `user`='".$user."' and `user`<>811 and `check`=1 and `order`<>'$shopcoinsorder' and `date`>(".time()."-365*24*60*60);";
	$result_tmp = mysql_query($sql_tmp);
	$rows_tmp = mysql_fetch_array($result_tmp);
	if ($rows_tmp[0]>=3)
		$clientdiscount = 1;
	else
		$clientdiscount = 0;
	
	//делаем проверку на все товары из магазина и показ отчета --------------------------------------------------------------
	$sql = "select o.*, o.amount as oamount, s.*, g.name as gname 
	from orderdetails as o, shopcoins as s, `group` as g 
	where o.`order`='$shopcoinsorder'
	and o.catalog=s.shopcoins
	and (s.`check`='1' or s.`check`>3)
	and o.status=0
	and s.`group` = g.`group`
	order by s.materialtype;";
	$result = mysql_query($sql);
	
	$user_order_details0 = "<tr bgcolor=\"#006699\"><td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td>
	<td width=498><p><b><font color=white>Монетная лавка Клуба Нумизмат</font></b></td>
	<td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td></tr>
	<tr>
	<td width=498 bgcolor=\"#fff8e8\">";
	
	$_GoogleStatUser = 'UTM:T|';
	$_GoogleStatOrder = '';
	
	if (!$addinordersubmit)
			$user_order_details .= "Уважаемый пользователь!<br>Ваш заказ будет объединен с предыдущим. 
		Проверьте пожалуйста информацию о Вашем заказе.
		<br><br><b>Номер заказа: $shopcoinsorder</b>";
	else
		$user_order_details .= "Уважаемый покупатель!<br>Ваш заказ принят к рассмотрению. Он будет объединен с предыдущим заказом 
		Вы можете связаться с менеджером по адресу <a href=mailto:$user_admin_email>$user_admin_email</a> по рабочим дням 
		или по телефону 8-915-002-22-23 (<b><font color=red>+3 GMT MSK</font></b>).
		Проверьте пожалуйста информацию о Вашем заказе.
		<br><br><b>Номер заказа: $shopcoinsorder</b>";
	
	$_GoogleStatUser .= $shopcoinsorder.'|'; // добавляем номер заказа
	
	$user_order_details .= "<table border=0 cellpadding=3 cellspacing=1 align=center>";
	$user_order_details .= "<tr bgcolor=#ffcc66>
	<td class=tboard><b>Название</b></td>
	<td class=tboard><b>Группа (страна)</b></td>
	<td class=tboard><b>Год</b></td>
	<td class=tboard><b>Номер</b></td>
	<td class=tboard><b>Цена</b></td>
	<td class=tboard><b>Кол - во</b></td></tr>";
	
	while ($rows = mysql_fetch_array($result))
	{
		// начинаем заполнять поле товара
		$_GoogleStatOrder .= 'UTM:I|'.$shopcoinsorder.'|'; // присоединяем номер заказа
		$_GoogleStatOrder .= $rows['number'].'|'; // присоединяем артикул товара
		if ($rows['materialtype'] == 3) {
			$_GoogleStatOrder .= $rows['name'].'|';
		} else {
			$_GoogleStatOrder .= $rows['name'].':'.$rows['gname'].'|'; // компонуем название для монет, бон и подарочных наборов
		}
		$_GoogleStatOrder .= $rows['gname'].'|'; // добавляем категорию
		$_GoogleStatOrder .= ($clientdiscount==1 && $rows['clientprice']>0?round($rows['clientprice'],2):round($rows['price'],2)).'|'; // добавляем цену
		$_GoogleStatOrder .= ($rows['oamount']?$rows['oamount']:1)."\n"; // добавляем количество и переносим строку
		
		if ($oldmaterialtype != $rows["materialtype"])
		{
			$user_order_details .= "<tr bgcolor=#EBE4D4 valign=top><td colspan=7 class=tboard bgcolor=#99CCFF><b>".$MaterialTypeArray[$rows["materialtype"]]."</b></td></tr>";
			$oldmaterialtype = $rows["materialtype"];
		}
		$user_order_details .= "
		<tr bgcolor=#EBE4D4 valign=top>
		<td class=tboard><a href=http://".$SERVER_NAME."/shopcoins/show.php?catalog=".$rows["shopcoins"]."&from=email target=_blank>".$rows["name"]."</a></td>
		<td class=tboard>".$rows["gname"]."</td>
		<td class=tboard>";
		if ( $rows["year"]) $user_order_details .=  $rows["year"];
		else $user_order_details .=  '&nbsp;';
		$user_order_details .=  "</td>
		<td class=tboard>".$rows["number"]."</td>
		<td class=tboard>".($clientdiscount==1 && $rows['clientprice']>0?round($rows['clientprice'],2):round($rows['price'],2))." руб.</td>
		<td class=tboard align=center>".($rows["oamount"]?$rows["oamount"]:1)."</td>";
		$sum += ($rows["oamount"]?$rows["oamount"]:1)*($clientdiscount==1 && $rows['clientprice']>0?round($rows['clientprice'],2):round($rows['price'],2));
		$user_order_details .= "</tr>";
	}
	
	$sql7 = "select * from coupon where `user`='$cookiesuser' and `user`<>811 and `check`=1 and `type`=2 order by dateinsert desc limit 1;";
	$result7 = mysql_query($sql7);
	$rows7 = @mysql_fetch_array($result7);
	if ($rows7['type'])
		$iscoupon = $rows7['type'];
	else 
		$iscoupon =0;
	
	if ($iscoupon==2) {
	
		$sql_tmp7 = "select * from ordercoupon where coupon='".$rows7['coupon']."' and `order`='".$shopcoinsorder."';";
		$result_tmp7 = mysql_query($sql_tmp7);
		//echo $sql_tmp;
		if (@mysql_num_rows($result_tmp7)==0) {
			
			$sql_ins7 = "insert into ordercoupon (`ordercoupon`,`coupon`,`order`,`dateinsert`,`check`) 
				values (NULL, '".$rows['coupon']."','$shopcoinsorder','".time()."','1');";
			$result_ins7 = mysql_query($sql_ins7);
			
		}
	}
	
	$_GoogleStatUser .= 'x|'; // добавление поставщика
	
	$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right colspan=4><b>Итого:</b></td>
	<td class=tboard>".$sum." руб. </td><td>&nbsp;</td></tr>";
	
	$_GoogleStatUser .= round($sum).'|'; // добавление суммы заказа
	
	if ($rows_temp['payment'] == 1 || $rows_temp['payment'] == 5) { // для разного типа оплаты считаем сумму налога
		$_GoogleStatUser .= round($sum * 0.06,2).'|';
	} else {
		$_GoogleStatUser .= '0|';
	}
	
	preg_match_all('/\d{6}/', $rows_temp['adress'], $found);
	if (trim($found[0][0]))
	{
		$postindex = $found[0][0];
	}
	else
		$postindex = 0;
	
	PostSum ($postindex, $shopcoinsorder, $clientdiscount);
	
	//показываем массу
	if ($rows_temp[delivery]==4 || $rows_temp[delivery]==5 || $rows_temp[delivery]==6 || $rows_temp[delivery]==7)
		$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard colspan=3 align=right><b>Вес (с учетом упаковки) ~</b> </font></td>
		<td class=tboard>".$bascetpostweight." грамм</td><td>&nbsp;</td></tr>";
	
	if ($rows_temp[delivery]==4 and $postindex)
	{
		$FinalSum = ($PostZonePrice+$sum+$PriceLatter+($suminsurance?$suminsurance*0.04:$bascetinsurance));
		$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard colspan=3 align=right><b>Почтовые услуги:</b> </font></td>
		<td class=tboard>".$PostZonePrice." руб.</td><td>&nbsp;</td></tr>
		<tr bgcolor=#EBE4D4><td class=tboard colspan=3 align=right><b>Страховка 4%:</b> </font></td>
		<td class=tboard>".($suminsurance?$suminsurance*0.04:$bascetinsurance)." руб.</td><td>&nbsp;</td></tr>
		<tr bgcolor=#EBE4D4><td class=tboard colspan=3 align=right><b>Конверт:</b> </font></td>
		<td class=tboard>".$PriceLatter." руб.</td><td>&nbsp;</td></tr>
		<tr bgcolor=#EBE4D4><td class=tboard colspan=3 align=right><b>ИТОГО:</b> </font></td>
		<td class=tboard><font color=red><b>".$FinalSum." руб.</b></font></td><td>&nbsp;</td></tr>
		";
	}
	else {
		$FinalSum = $sum;
	}
	//if ($_SERVER['REMOTE_ADDR'] == '212.233.78.26') echo "sum=$sum FinalSum = $finalSum";
	if ($sum <= 0.00 || !$sum || $FinalSum <=0.00 || !$FinalSum) 
		echo "<p class=txt> <font color=red><b>Сумма заказа менее 500.00 руб. </b></font></p>";
	else {
		$_GoogleStatUser .= 'Москва|'; // город клиента
		$_GoogleStatUser .= 'Москва|'; // область клиента
		$_GoogleStatUser .= "Россия\n"; // страна клиента
		
		$_GoogleStat = '<form style="display: none;" name="utmform"><textarea id="utmtrans">';
		$_GoogleStat .= $_GoogleStatUser.$_GoogleStatOrder; // формируем окончательную квитанцию заказа для статистики
		$_GoogleStat .= '</textarea></form>';
		
		unset ($_GoogleStat);
		
		if (!$addinordersubmit) {
			$user_order_details .= "<tr><td align=center colspan=6><form action=index.php?page=order method=post >
			<input type=hidden name=page2 value=2>
			<input type=submit name=addinordersubmit onclick=\"javascript:alert('Не забудьте вовремя выкупить предыдущие заказы.');this.submit();\" value='Добавить к предыдущему заказу' class=formtxt>
			</form></tr>";
			
			$PayMessage .= "<br>".$SumProperties[$rows_temp['payment']];
		}
		else	
			$PayMessage .= "<br>".($rows_temp['payment']==6?"<center><a href='http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=".$shopcoinsorder."&FIO=".urlencode(strip_string($fio))."&ADRESS=".urlencode($adress)."&SUM=".$FinalSum."' target='_blank'><img src=".$in."images/sbrfprint.gif border=1 style='border-color:black' alt='Распечатать квитанцию Сбербанка'></a><br><b>Ссылка:</b> <a href='http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=".$shopcoinsorder."&FIO=".urlencode(strip_string($fio))."&ADRESS=".urlencode($adress)."&SUM=".$FinalSum."' target='_blank'>http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=".$shopcoinsorder."&FIO=".urlencode(strip_string($fio))."&ADRESS=".urlencode($adress)."&SUM=".$FinalSum."</a></center><br>":"").$SumProperties[$rows_temp['payment']];
		/*if ($rows_temp[payment] == 6)
		{
			$sbrfblank = str_replace("___FIO___", $fio, $sbrfblank);
			$sbrfblank = str_replace("___ADRESS___", $adress, $sbrfblank);
			$sbrfblank = str_replace("___NUMBER___", $order, $sbrfblank);
			$sbrfblank = str_replace("___SUM___", $sum, $sbrfblank);
			$sbrfblank = str_replace("___KOP___", "00", $sbrfblank);
			$PayMessage .= "<br>".$sbrfblank;
		}*/
		
		$user_order_details .= "</table>
		<table border=0 cellpadding=3 cellspacing=1 align=center>
		<tr><td class=tboard>
		<hr size=1 color=#000000>
		".$PayMessage."
		<hr size=1 color=#000000>
		</td></tr>
		</table>
		
		<br><b>Информация о покупателе:</b>";
		$user_order_details .= "<table border=0 cellpadding=3 cellspacing=1 align=center>";
		$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right><b>ФИО</b></td><td class=tboard>".strip_string($rows_temp['userfio'])."</td></tr>";
		$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right><b>Контактный телефон:</b></td><td class=tboard>".strip_string($rows_temp['phone'])."</td></tr>";
		
		if (strip_string($rows_temp['adress']))
			$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right><b>Адрес доставки:</b></td><td class=tboard>".strip_string($rows_temp['adress'])."</td></tr>";
		
		if ($rows_temp['delivery']==2)
		{
			$DeliveryName[$rows_temp['delivery']] = "В офисе (возможность посмотреть материал до выставления)";
		}
		
		$user_order_details .= "<tr><td class=txt bgcolor=#EBE4D4 valign=top width=40% align=right><b>Способ доставки</b></td>
		<td valign=top class=tboard bgcolor=#EBE4D4>".$DeliveryName[$rows_temp['delivery']]."</td></tr>";
		
		if ($rows_temp[delivery]==2)
		{
			$user_order_details .= '
			<tr><td class=txt bgcolor=#EBE4D4 valign=top colspan=2 align=right>
			<br>2-3 минуты от метро Тверская.  <br>Рабочие дни с 10.00 до 18.00.  <br><iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.ru/maps?hl=ru&amp;q=%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0+%D0%BA%D1%80%D0%B0%D1%81%D0%BD%D0%BE%D0%BF%D1%80%D0%BE%D0%BB%D0%B5%D1%82%D0%B0%D1%80%D1%81%D0%BA%D0%B0%D1%8F+16&amp;lr=&amp;ie=UTF8&amp;hq=&amp;hnear=%D0%90%D0%9E+%D0%A6%D0%B5%D0%BD%D1%82%D1%80%D0%B0%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9,+%D0%B3%D0%BE%D1%80%D0%BE%D0%B4+%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0,+%D0%9A%D1%80%D0%B0%D1%81%D0%BD%D0%BE%D0%BF%D1%80%D0%BE%D0%BB%D0%B5%D1%82%D0%B0%D1%80%D1%81%D0%BA%D0%B0%D1%8F+%D1%83%D0%BB.,+16&amp;ll=55.777152,37.607768&amp;spn=0.018102,0.054932&amp;z=15&amp;output=embed"></iframe><br /><small><a href="http://maps.google.ru/maps?hl=ru&amp;q=%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0+%D0%BA%D1%80%D0%B0%D1%81%D0%BD%D0%BE%D0%BF%D1%80%D0%BE%D0%BB%D0%B5%D1%82%D0%B0%D1%80%D1%81%D0%BA%D0%B0%D1%8F+16&amp;lr=&amp;ie=UTF8&amp;hq=&amp;hnear=%D0%90%D0%9E+%D0%A6%D0%B5%D0%BD%D1%82%D1%80%D0%B0%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9,+%D0%B3%D0%BE%D1%80%D0%BE%D0%B4+%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0,+%D0%9A%D1%80%D0%B0%D1%81%D0%BD%D0%BE%D0%BF%D1%80%D0%BE%D0%BB%D0%B5%D1%82%D0%B0%D1%80%D1%81%D0%BA%D0%B0%D1%8F+%D1%83%D0%BB.,+16&amp;ll=55.777152,37.607768&amp;spn=0.018102,0.054932&amp;z=15&amp;source=embed" style="color:#0000FF;text-align:left" target=_blank>Просмотреть увеличенную карту</a></small>
			</td></tr>';
		}
		
		$user_order_details .= "<tr><td class=txt bgcolor=#EBE4D4 valign=top width=40% align=right><b>Способ оплаты</b></td>
		<td valign=top class=tboard bgcolor=#EBE4D4>".$SumName[$rows_temp['payment']]."</td></tr>";
		
		if ($rows_temp['MetroMeeting'] and $rows_temp['delivery'] == 1)
		{
			$MetroName = $MetroArray[$rows_temp['MetroMeeting']];
		}
		elseif ($rows_temp['MetroMeeting'] and ($rows_temp['delivery'] == 3))
		{
			$sql = "select * from metro where metro='$rows_temp[MetroMeeting]';";
			$result = mysql_query($sql);
			$rows = mysql_fetch_array($result);
			if ($rows["name"])
				$MetroName = $rows["name"];
		}
		
		if ($MetroName)
			$user_order_details .= "<tr><td class=txt bgcolor=#EBE4D4 valign=top width=40% align=right><b>Метро</b></td>
			<td valign=top class=tboard bgcolor=#EBE4D4>".$MetroName."</td></tr>";
		
		//дата
		if (($rows_temp['DateMeeting']-$rows_temp['MeetingFromTime'])>0 and ($rows_temp['delivery'] == 1 || $rows_temp['delivery'] == 2 || $rows_temp['delivery'] == 3 || $rows_temp['delivery'] == 7))
			$user_order_details .= "<tr><td class=txt bgcolor=#EBE4D4 valign=top width=40% align=right><b>Дата</b></td>
			<td valign=top class=tboard bgcolor=#EBE4D4>".$DaysArray[date("w",($rows_temp['DateMeeting']-$rows_temp['MeetingFromTime']))].":".date("d-m-Y", ($rows_temp['DateMeeting']-$rows_temp['MeetingFromTime']))."</td></tr>";
		
		//время
		if ($rows_temp['MeetingFromTime'] and ($rows_temp['delivery'] == 1 || $rows_temp['delivery'] == 2 || $rows_temp['delivery'] == 3 || $rows_temp['delivery'] == 7))
			$user_order_details .= "<tr><td class=txt bgcolor=#EBE4D4 valign=top width=40% align=right><b>Время</b></td>
			<td valign=top class=tboard bgcolor=#EBE4D4>".date("H-i", $timenow + $rows_temp['MeetingFromTime'])." по ".date("H-i", $timenow + $rows_temp['MeetingToTime'])."</td></tr>";
		
		if (!$addinordersubmit)
			$user_order_details .= "<tr><td colspan=2 align=center><form action=index.php?page=order method=post>
			<input type=hidden name=page2 value=2>
			<input type=submit name=addinordersubmit onclick=\"javascript:alert('Не забудьте вовремя выкупить предыдущие заказы.');this.submit();\" value='Добавить к предыдущему заказу' class=formtxt>
			</form></tr>";
		//рассылаем письма------------------------------------------------------------------------------------------------------
		$user_order_details .= "</table>
		<br>$AdvertiseText
		<br><br>";
		
		echo $user_order_details;
		
		if ($addinordersubmit) {
		
			//делаем delete с orderdetails, если уже материал был заказан или он отсутствует
			if ($shopcoinsorder)
			{
				$sql = "select o.*, s.*
				from orderdetails as o, shopcoins as s
				where o.`order`='$shopcoinsorder'
				and o.catalog=s.shopcoins
				 and o.status=0
				and s.`check`='0';";
				$result = mysql_query($sql);
				while ($rows = mysql_fetch_array($result))
				{
					$sql_delete = "delete from `orderdetails` 
					where `order` = '$shopcoinsorder' and catalog='".$rows["catalog"]."';";
					$result_delete = mysql_query($sql_delete);
					//echo $sql_delete;
				}
			}
			
			//делаем update единичный товаров и изменение количества у других------------------------------------------------------
			if ($shopcoinsorder)
			{
				$sql = "select o.*, s.*, g.name as gname, o.amount as orderamount, s.amount as samount 
				from orderdetails as o, shopcoins as s, `group` as g 
				where o.`order`='$shopcoinsorder'
				and o.catalog=s.shopcoins
				and (s.`check`='1' or s.`check`>3)
				 and o.status=0
				and s.`group` = g.`group`;";
				$result = mysql_query($sql);
				
				$ParentArray = Array();
				
				while ($rows = mysql_fetch_array($result))
				{
					$ParentArray[] = $rows["parent"];
					
					if ($rows["materialtype"]==1)
					//монеты, боны, подарочные наборы
					{
						
						if ($rows['relationcatalog']>0 && $rows['userreserve']>0 && $rows['userreserve'] != $user) {
						
							$sql_info = "select shopcoins.*, catalogshopcoinssubscribe.catalog 
							from `shopcoins`, catalogshopcoinsrelation, catalogshopcoinssubscribe  
							WHERE shopcoins.shopcoins = '".$rows['shopcoins']."' and shopcoins.materialtype = '1'
							and catalogshopcoinsrelation.shopcoins = shopcoins.shopcoins
							and catalogshopcoinssubscribe.catalog = catalogshopcoinsrelation.catalog 
							and catalogshopcoinssubscribe.user = '".$rows['userreserve']."';";
							
							$result_info = mysql_query($sql_info);
							
							if (mysql_num_rows($result_info) == 0) {
							
								$rows_info = mysql_fetch_array($result_info);
								
								$sql_ins = "insert into catalogshopcoinssubscribe 
									(user, catalog, dateinsert,
									datesend, amountdatesend, buy,amount)
									values
									('".$rows['userreserve']."', '".$rows_info['catalog']."', '".time()."',
									'0', '0', '0','1');";
								mysql_query($sql_ins);
							}
						}
						
						$sql_update = "update shopcoins 
						set 
						`check`=0, reserveorder='".$shopcoinsorder."', reserve = 0, amount = 0, doubletimereserve=0, userreserve=0, dateorder=".time()." 
						where shopcoins='".$rows["catalog"]."';";
						$result_update = mysql_query($sql_update);
					}
					elseif ($rows["materialtype"]==4 || $rows["materialtype"]==7 || $rows["materialtype"]==8 || $rows["materialtype"]==2 )
					{
						$sql_update = "update shopcoins 
						set  amount=amount-".$rows["orderamount"]."
						".($rows["orderamount"]>=$rows["samount"]?", `check`=0":"")."
						, reserveorder='".$shopcoinsorder."', dateorder=".time()."
						where shopcoins='".$rows["catalog"]."';";
						$result_update = mysql_query($sql_update);
						mysql_query("delete from helpshopcoinsorder where shopcoins='".$rows["catalog"]."' and reserveorder='".$shopcoinsorder."'");
					}
					//аксессуары, книги
					elseif ($rows["materialtype"]==3 || $rows["materialtype"]==5)
					{
						$sql_update = "update shopcoins set amount=amount-".$rows["orderamount"]."
						".($rows["orderamount"]>=$rows["samount"]?", `check`=0":"").", dateorder=".time()."
						where shopcoins='".$rows["catalog"]."';";
						$result_update = mysql_query($sql_update);
						
						if ($rows["orderamount"]>=$rows["samount"])
						{
							$sql_amount = "insert into shopcoinsend 
							(shopcoinsend, shopcoins, insertdate, updatedate, check)
							values 
							(0, '".$rows["catalog"]."', '".$timenow."', '0', 1);";
							$result_amount = mysql_query($sql_amount);
						}
					}
					// другое
					else
					{
						$sql_update = "update shopcoins 
						set 
						`check`=0, reserveorder='".$shopcoinsorder."', dateorder=".time()."
						where shopcoins='".$rows["catalog"]."';";
						$result_update = mysql_query($sql_update);
					}
					
					if ($deletesubscribecoins && $user && $rows["catalog"]) {
					
						$sql_del = "delete from catalogshopcoinssubscribe where `user`='$user' and `catalog`='".$rows["catalog"]."';";
						$result_del = mysql_query($sql_del);
					}
				}
				
				if (sizeof($ParentArray)>0)
				{
					$sql="SELECT shopcoins.* FROM shopcoins WHERE shopcoins.check =1 and parent in (".implode(",", $ParentArray).") GROUP BY shopcoins.parent;";
					if ($REMOTE_ADDR == $myip)	echo $sql;
					$result = mysql_query($sql);
					while ($rows = mysql_fetch_array($result))
					{
						$sql_info = "select count(*) from shopcoins where `check`='1' and parent='".$rows["parent"]."';";
						$result_info = mysql_query($sql_info);
						$rows_info = mysql_fetch_array($result_info);
						if ($REMOTE_ADDR == $myip) echo "<br>".$sql_info;
						
						//сам update
						$sql_update = "update shopcoins set amountparent='".$rows_info[0]."' where shopcoins='".$rows["shopcoins"]."';";
						$result_update = mysql_query($sql_update);
						if ($REMOTE_ADDR == $myip) echo "<br>".$sql_update;
						//exit;
					}
				}
			}
			
			//проставляем amountparent
			
			
			
			//подтверждаем заказ - order -------------------------------------------------------------------------------------------
			$sql = "update `order` set sum='".$sum."', FinalSum = '$FinalSum' , NeedCall = '2', suminsurance='0', `check`=1
			where `order`='".$shopcoinsorder."';";
			$result = mysql_query($sql);
			echo mysql_error();
			
			$user_order_details2 .= "</td>
			</tr>";
			
			$user_order_details = $user_order_details0.$user_order_details.$user_order_details2;
		
			$file = fopen($in."mail/top.html", "r");
			while (!feof ($file)) 
			{
					$message .= fgets ($file, 1024);
				}
			fclose($file);
			
			$message = $message.$user_order_details;
			
			$file = fopen($in."mail/bottom.html", "r");
			while (!feof ($file)) 
			{
					$message .= fgets ($file, 1024);
			}
			
			$recipient = "bodka@rt.mipt.ru";
			$subject = "Монетная лавка | Клуб Нумизмат";
			$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
			mail($recipient, $subject, $message.$order."\t".time()."\t".$HTTP_SERVER_VARS["HTTP_USER_AGENT"]."\n".$REMOTE_ADDR, $headers); //админу сайта
			
			$recipient = $email;
			mail($recipient, $subject, $message, $headers); //покупателю
			
			//удаляем cookies пользователя------------------------------------------------------------------------------------------
			echo "<img src=deleteusercookies.php width=0 height=0>
			<img src=inleft/index.php width=0 height=0>";
		}
		
	}
}
else 
	echo "<p class=txt> $error</p>";


?>