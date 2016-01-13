<? if($tpl['submitorder']['error_auth']){?>    
    <div class="error">
    <p><b>Для оформления заказа необходимо авторизироваться на сайте или возможно у Вас истекло время отведенное на оформление заказа.</b></p>
    </div>
<?} elseif($tpl['submitorder']['error_userstatus']) {?>
     <div class="error">
    <p><b>Вам поставлен запрет на новые заказы. Для уточнения свяжитесь с администрацией.</b></p>
    </div>
<?} elseif($tpl['submitorder']['error_userstatus']) {?>
    
<?} elseif($tpl['submitorder']['error']) {?>
    <div class="error">
    <p> Некорректные параметры заказа. Пройдите процедуру оформления заказа заново!
       <form name=resultform method=post id=resultorderform action="<?=$cfg['site_dir']?>shopcoins/submitorder.php">
        <input type="submit"  class="button25" value="Вернутся к оформлению заказа">
        <input type="hidden" value="<?=$fio?>" name="<?=$fio?>" id="<?=$fio?>">
        <input type="hidden" value="<?=$userfio?>" name="<?=$userfio?>" id="<?=$userfio?>">
        <input type="hidden" value="<?=$phone?>" name="<?=$phone?>" id="<?=$phone?>">
        <input type="hidden" value="<?=$email?>" name="<?=$email?>" id="<?=$email?>">
        <input type="hidden" value="<?=$delivery?>" name="<?=$delivery?>" id="<?=$delivery?>">
        <input type="hidden" value="<?=$metro?>" name="<?=$metro?>" id="<?=$metro?>">
        <input type="hidden" value="<?=$meetingdate?>" name="<?=$meetingdate?>" id="<?=$meetingdate?>">
        <input type="hidden" value="<?=$meetingfromtime?>" name="<?=$meetingfromtime?>" id="<?=$meetingfromtime?>">
        <input type="hidden" value="<?=$meetingtotime?>" name="<?=$meetingtotime?>" id="<?=$meetingtotime?>">
        <input type="hidden" value="<?=$postindex?>" name="<?=$postindex?>" id="<?=$postindex?>">
        <input type="hidden" value="<?=$adress?>" name="<?=$adress?>" id="<?=$adress?>">
        <input type="hidden" value="<?=$payment?>" name="<?=$payment?>" id="<?=$payment?>">
        <input type="hidden" value="<?=$OtherInformation?>" name="<?=$OtherInformation?>" id="<?=$OtherInformation?>">       
       </form>    
    </p>
    </div>
<?} else {?>
<div id='user-compare-block' style="display:none">

    <? if($tpl['submitorder']['compare']) {?>
    <div class="error">На постоянную работу удаленно требуется нумизмат для описания монет в административном интерфейсе. </b><br>Оплата сдельная - 2 рубля за монету, оплата через вебмани либо яндекс-деньги, либо бартер на заказы монет. Обращаться к администратору. E-mail: <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a>, тел.: +7-903-006-00-44,  +7-915-00-2222-3.
    </div>
    
   <?}?>
		$user_order_details0 = "<tr bgcolor=\"#006699\"><td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td>
		<td width=498><p><b><font color=white>Монетная лавка Клуба Нумизмат</font></b></td>
		<td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td></tr>
		<tr>
		<td width=498 bgcolor=\"#fff8e8\">";
		
		$_GoogleStatUser = 'UTM:T|';
		$_GoogleStatOrder = '';
		
		$user_order_details .= "Уважаемый покупатель!<br>Ваш заказ принят к рассмотрению. 
		Вы можете связаться с менеджером по адресу <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в рабочие дни 
		или по телефону 8-800-333-14-77 (бесплатный звонок по России) (<b><font color=red>+3 GMT MSK</font></b>).
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
			<td class=tboard>".round($rows['price'],2)." руб.</td>
			<td class=tboard align=center>".($rows["oamount"]?$rows["oamount"]:1)."</td>";
			$sum += ($rows["oamount"]?$rows["oamount"]:1)*round($rows['price'],2);
			$sumamountprice += ($rows["oamount"]?$rows["oamount"]:1)*round($rows['priceamount'],2);
			if ($rows['materialtype']==12)
				$vipcoinssum += $rows['oamount']*round($rows['price'],2);
			$user_order_details .= "</tr>";
		}
		
		if ($sum>=1000) 
			$needcallingorder2 = 1;
		
		$sql = "select * from coupon where `user`='$cookiesuser' and `user`<>811 and `check`=1 and `type`=2 order by dateinsert desc limit 1;";
		$result = mysql_query($sql);
		$rows = @mysql_fetch_array($result);
		if ($rows['type'])
			$iscoupon = $rows['type'];
		else 
			$iscoupon =0;
		
		if ($iscoupon==2) {
		
			$sql_tmp = "select * from ordercoupon where coupon='".$rows['coupon']."' and `order`='".$shopcoinsorder."';";
			$result_tmp = mysql_query($sql_tmp);
			//echo $sql_tmp;
			if (@mysql_num_rows($result_tmp)==0) {
				
				$sql_ins = "insert into ordercoupon (`ordercoupon`,`coupon`,`order`,`dateinsert`,`check`) 
					values (NULL, '".$rows['coupon']."','$shopcoinsorder','".time()."','1');";
				$result_ins = mysql_query($sql_ins);
				
			}
		}
		
		$sql = "select coupon.* from coupon,ordercoupon where ordercoupon.order='".$shopcoinsorder."' and ordercoupon.`check`=1 and coupon.coupon=ordercoupon.coupon and coupon.check=1 group by coupon.coupon order by coupon.type desc, coupon.dateinsert desc;";
		$result = mysql_query($sql);
		$discountcoupon = 0;
		$typecoupon = 0;
		if (@mysql_num_rows($result)>0) {
		
			$typec = 1;
			while ($rows = mysql_fetch_array($result)) {
			
				if ($rows['type']==2 && $typec==1) {
				
					$discountcoupon = floor(($sum-$sumamountprice-$vipcoinssum)*$rows['sum']/100);
					$typecoupon = 2;
					$typec = 2;
				}
				elseif ($rows['type']==1 && ($typec==1 || ($typec==2 && $rows['order']==0))) {
				
					if ($rows['dateend']>=time())
						$discountcoupon += $rows['sum'];
						
					if (!$typecoupon)
						$typecoupon = 1;
					
					$ArrayCouponNull[] = $rows['coupon'];
				}
			}
			
			
		}
		if ($discountcoupon<0)
			$discountcoupon = 0;
		
		if ( $sum < $discountcoupon) {
			
			$discountcoupon = $sum;
		}
		
		if (sizeof($ArrayCouponNull)) {
		
			$sql_up = "update coupon set `check`=0 where `coupon` in('".implode("'",$ArrayCouponNull)."') and `check`=1 and type=1;";
			mysql_query($sql_up);
		}
		$sum = $sum - $discountcoupon;
		$_GoogleStatUser .= 'x|'; // добавление поставщика
		
		if ($discountcoupon) {
			
			$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right colspan=4><b>Сумма заказа:</b></td>
			<td class=tboard>".($sum+$discountcoupon)." руб. </td><td>&nbsp;</td></tr>";
			$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right colspan=4><b>Скидка по купону(ам):</b></td>
			<td class=tboard>".$discountcoupon." руб. </td><td>&nbsp;</td></tr>";
		}
		$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right colspan=4><b>Итого:</b></td>
		<td class=tboard>".$sum." руб. </td><td>&nbsp;</td></tr>";
		
		$_GoogleStatUser .= round($sum).'|'; // добавление суммы заказа
		
		if ($paymentvalue == 1 || $paymentvalue == 5) { // для разного типа оплаты считаем сумму налога
			$_GoogleStatUser .= round($sum * 0.06,2).'|';
		} else {
			$_GoogleStatUser .= '0|';
		}
		
		PostSum ($postindex, $shopcoinsorder, $clientdiscount);
		
		//показываем массу
		if ($deliveryvalue==4 || $deliveryvalue==5 || $deliveryvalue==6 || $deliveryvalue==7)
			$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Вес (с учетом упаковки) ~</b> </font></td>
			<td class=tboard>".$bascetpostweight." грамм</td><td>&nbsp;</td></tr>";
		
		if ($deliveryvalue==6) {
		
			if ($bascetpostweight < 1000) 
				$sumEMC = 650;
			else {
			
				$sumEMC = 650;
				$sumEMC += ceil(($bascetpostweight - 1000)/500)*50;
			}
			
			$FinalSum = ($sum+$PriceLatter+10+$sumEMC);

			include_once $_SERVER["DOCUMENT_ROOT"]."/shopcoins/coin_function.php";
			if($_POST['from_ubb'] == 'yes' AND $sum <= 3000 AND is_user_has_premissons(intval($_COOKIE['cookiesuser'])) AND ($ub = get_user_balance(intval($_COOKIE['cookiesuser']))) >= $sum)
			{
				$paymentvalue = 2;
				$FinalSum = $FinalSum - $sum;
				decrement_user_balance(intval($_COOKIE['cookiesuser']), $sum);
				user_order_bonus_log(intval($_COOKIE['cookiesuser']), $shopcoinsorder,$sum);
				$bonus_comment = TRUE;
				//add_admin_comment($shopcoinsorder, '');
			}


			$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Почтовые услуги:</b> </font></td>
			<td class=tboard>".$sumEMC." руб.</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Страховка 1%:</b> </font></td>
			<td class=tboard>".(10)." руб.</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Конверт:</b> </font></td>
			<td class=tboard>".$PriceLatter." руб.</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>ИТОГО:</b> </font></td>
			<td class=tboard><font color=red><b>".$FinalSum." руб.</b></font></td><td>&nbsp;</td></tr>
			";
		}
		elseif ($deliveryvalue==4 and $postindex)
		{
			
			$FinalSum = ($PostZonePrice+$sum+$PriceLatter+($suminsurance>0?$suminsurance*0.04:$bascetinsurance));

			include_once $_SERVER["DOCUMENT_ROOT"]."/shopcoins/coin_function.php";
			if($_POST['from_ubb'] == 'yes' AND $sum <= 3000 AND is_user_has_premissons(intval($_COOKIE['cookiesuser'])) AND ($ub = get_user_balance(intval($_COOKIE['cookiesuser']))) >= $sum)
			{
				$paymentvalue = 2;
				$FinalSum = $FinalSum - $sum;
				decrement_user_balance(intval($_COOKIE['cookiesuser']), $sum);
				user_order_bonus_log(intval($_COOKIE['cookiesuser']), $shopcoinsorder,$sum);
				$bonus_comment = TRUE;
				//add_admin_comment($shopcoinsorder, '');
			}


			$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Почтовые услуги:</b> </font></td>
			<td class=tboard>".$PostZonePrice." руб.</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Страховка 4%:</b> </font></td>
			<td class=tboard>".($suminsurance>0?$suminsurance*0.04:$bascetinsurance)." руб. <br>(В связи с участившимися случаями пропаж и вскрытия отправлений на Почте России<br> во всех почтовыех отправлениях страхование производится на полную их стоимость)</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Конверт:</b> </font></td>
			<td class=tboard>".$PriceLatter." руб.</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>ИТОГО:</b> </font></td>
			<td class=tboard><font color=red><b>".$FinalSum." руб.</b></font></td><td>&nbsp;</td></tr>
			";
		}
		else {

			include_once $_SERVER["DOCUMENT_ROOT"]."/shopcoins/coin_function.php";
			if($_POST['from_ubb'] == 'yes' AND $sum <= 3000 AND is_user_has_premissons(intval($_COOKIE['cookiesuser'])) AND ($ub = get_user_balance(intval($_COOKIE['cookiesuser']))) >= $sum)
			{
				$paymentvalue = 2;
				$FinalSum = $FinalSum - $sum;
				decrement_user_balance(intval($_COOKIE['cookiesuser']), $sum);
				user_order_bonus_log(intval($_COOKIE['cookiesuser']), $shopcoinsorder,$sum);
				$bonus_comment = TRUE;
				//add_admin_comment($shopcoinsorder, '');
			}


			$FinalSum = $sum;
		}
		
		if ($paymentvalue != 2 && $needcallingorder1)
			$needcallingorder = 1;
		
		if (($sum <= 0.00 || !$sum || $FinalSum <=0.00 || !$FinalSum) && $discountcoupon<=0) 
			echo "<p class=txt> <font color=red><b>Сумма заказа менее 500.00 руб. </b></font></p>";
		else {
			$_GoogleStatUser .= 'Москва|'; // город клиента
			$_GoogleStatUser .= 'Москва|'; // область клиента
			$_GoogleStatUser .= "Россия\n"; // страна клиента
			
			$_GoogleStat = '<form style="display: none;" name="utmform"><textarea id="utmtrans">';
			$_GoogleStat .= $_GoogleStatUser.$_GoogleStatOrder; // формируем окончательную квитанцию заказа для статистики
			$_GoogleStat .= '</textarea></form>';
			
			unset ($_GoogleStat);
			
			$PayMessage .= "<br>".($paymentvalue==6?"<center><a href='http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=".$shopcoinsorder."&FIO=".urlencode(strip_string($fio))."&ADRESS=".urlencode($adress)."&SUM=".$FinalSum."' target='_blank'><img src=".$in."images/sbrfprint.gif border=1 style='border-color:black' alt='Распечатать квитанцию Сбербанка'></a><br><b>Ссылка:</b> <a href='http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=".$shopcoinsorder."&FIO=".urlencode(strip_string($fio))."&ADRESS=".urlencode($adress)."&SUM=".$FinalSum."' target='_blank'>http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=".$shopcoinsorder."</a></center><br>":"").$SumProperties[$paymentvalue];
			/*if ($paymentvalue == 6)
			{
				$sbrfblank = str_replace("___FIO___", $fio, $sbrfblank);
				$sbrfblank = str_replace("___ADRESS___", $adress, $sbrfblank);
				$sbrfblank = str_replace("___NUMBER___", $order, $sbrfblank);
				$sbrfblank = str_replace("___SUM___", $sum, $sbrfblank);
				$sbrfblank = str_replace("___KOP___", "00", $sbrfblank);
				$PayMessage .= "<br>".$sbrfblank;
			}*/
			
			if ($paymentvalue>1 && 1==2) {
			
				$sertprint = "<br><br><center><div id=sertificate><input type=button value='Использовать подарочный сертификат' onclick=\"javascript:ShowSertificate();\"></div></center>";
			}
			else
				$sertprint = "";
			
			$user_order_details .= "____VISAPAYMENT___</table>$sertprint
			<table border=0 cellpadding=3 cellspacing=1 align=center>
			<tr><td class=tboard>
			<hr size=1 color=#000000>
			".$PayMessage."
			<hr size=1 color=#000000>
			</td></tr>
			</table>";
			
			$sql_cf = "select * from `user` where user='$cookiesuser';";
			$result_cf = mysql_query($sql_cf);
			$rows_cf = mysql_fetch_array($result_cf);
			$codeforfrend = $rows_cf['codeforfrend'];
			if (!$codeforfrend) {
			
				$couponups = 1;
				srand((double) microtime()*1000000);
				while ($couponups==1) {
					
					$code = '';
					for ($i=0;$i<12;$i++) {
					
						$code .= $ArrayForCode[rand(0,15)];
					}
					
					$sql_tmp = "select count(*) from `user` where codeforfrend ='".$code."';";
					$result_tmp = mysql_query($sql_tmp);
					$rows_tmp = mysql_fetch_array($result_tmp);
					if ($rows_tmp[0] == 0) 
						$couponups = 2;
				}
				
				$sql_up = "update `user` set codeforfrend ='".$code."' where `user`='$cookiesuser';";
				mysql_query($sql_up);
				$codeforfrend = $code;
			}
			
			$user_order_details .= "</table>
			<table border=0 cellpadding=3 cellspacing=1 align=center width=80%>
			<tr><td class=tboard>
			<hr size=1 color=#000000>
			<b><font color=red>Внимание!!!</font>  У нас действует акция \"Приведи друга\". <a href=http://www.numizmatik.ru/shopcoins/aboutfrendaction.php target=_blank>Подробнее >>></a></b><br>
			Ваш код для участия в акции: <b><font color=red><big>".strtoupper($codeforfrend)."</big></font></b><br>
			Вы также можетет использовать ссылку для этой акциии на различных веб-ресурсах. <br>
			Текст вашей ссылки для размещения: <b>http://www.numizmatik.ru/user/registration.php?codeforfrend=".$codeforfrend."</b>
			<hr size=1 color=#000000>
			</td></tr>
			</table>";
			
			$user_order_details .= "<p class=txt><b>Информация о покупателе:</b>";
			$user_order_details .= "<table border=0 cellpadding=3 cellspacing=1 align=center>";
			$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right><b>ФИО</b></td><td class=tboard>".strip_string($fio)."</td></tr>";
			$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right><b>Контактный телефон:</b></td><td class=tboard>".strip_string($phone)."</td></tr>";
			
			if ($postindex and $adress)
			{
				preg_match_all('/\d{6}/', $adress, $found);
				if (!trim($found[0][0]))
				{
					$adress = $postindex.", ".$adress;
				}
			}
			
			if (strip_string($adress))
				$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right><b>Адрес доставки:</b></td><td class=tboard>".strip_string($adress)."</td></tr>";
			
			if ($deliveryvalue==2)
			{
				$DeliveryName[$deliveryvalue] = "В офисе (возможность посмотреть материал до выставления)";
			}
			
			$user_order_details .= "<tr><td class=txt bgcolor=#EBE4D4 valign=top width=40% align=right><b>Способ доставки</b></td>
			<td valign=top class=tboard bgcolor=#EBE4D4>".$DeliveryName[$deliveryvalue]."</td></tr>";
			
			if ($deliveryvalue==2)
			{
				$user_order_details .= '
				<tr><td class=txt bgcolor=#EBE4D4 valign=top colspan=2 align=right>
				<br>14-5 минут от метро Тверская.<br>Рабочие дни с 10.00 до 18.00.  <br><br><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2244.680447852284!2d37.609148899999994!3d55.76405310000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54a46b695b08f%3A0x5e895e0d8de444fe!2z0KLQstC10YDRgdC60LDRjyDRg9C7LiwgMTLRgTgsINCc0L7RgdC60LLQsCwgMTI1MDA5!5e0!3m2!1sru!2sru!4v1416476603568" width="600" height="450" frameborder="0" style="border:0"></iframe>
				</td></tr>';
			}
			
			$user_order_details .= "<tr><td class=txt bgcolor=#EBE4D4 valign=top width=40% align=right><b>Способ оплаты</b></td>
			<td valign=top class=tboard bgcolor=#EBE4D4>".$SumName[$paymentvalue]."</td></tr>";
			
			if ($metrovalue and $deliveryvalue == 1)
			{
				$MetroName = $MetroArray[$metrovalue];
			}
			elseif ($metrovalue and ($deliveryvalue == 3))
			{
				$sql = "select * from metro where metro='$metrovalue';";
				$result = mysql_query($sql);
				$rows = mysql_fetch_array($result);
				if ($rows["name"])
					$MetroName = $rows["name"];
			}
			
			if ($MetroName)
				$user_order_details .= "<tr><td class=txt bgcolor=#EBE4D4 valign=top width=40% align=right><b>Метро</b></td>
				<td valign=top class=tboard bgcolor=#EBE4D4>".$MetroName."</td></tr>";
			
			//дата
			if ($meetingdatevalue and ($deliveryvalue == 1 || $deliveryvalue == 2 || $deliveryvalue == 3 || $deliveryvalue == 7))
				$user_order_details .= "<tr><td class=txt bgcolor=#EBE4D4 valign=top width=40% align=right><b>Дата</b></td>
				<td valign=top class=tboard bgcolor=#EBE4D4>".$DaysArray[date("w",$meetingdatevalue)].":".date("d-m-Y", $meetingdatevalue)."</td></tr>";
			
			//время
			if ($meetingfromtimevalue and ($deliveryvalue == 1 || $deliveryvalue == 2 || $deliveryvalue == 3 || $deliveryvalue == 7))
				$user_order_details .= "<tr><td class=txt bgcolor=#EBE4D4 valign=top width=40% align=right><b>Время</b></td>
				<td valign=top class=tboard bgcolor=#EBE4D4>".date("H-i", $timenow + $meetingfromtimevalue)." по ".date("H-i", $timenow + $meetingtotimevalue)."</td></tr>";
			
			//делаем delete с orderdetails, если уже материал был заказан или он отсутствует
			if ($shopcoinsorder)
			{
				$sql = "select o.*, s.*
				from orderdetails as o, shopcoins as s
				where o.`order`='$shopcoinsorder'
				and o.catalog=s.shopcoins
				and s.`check`='0' and o.status=0;";
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
				and o.catalog=s.shopcoins and o.status=0
				and (s.`check`=1 or s.`check`>3)
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
						
						/* 2015-06-11 Молоток уже не работает
						$sql_m = "select * from shopcoinsmolotok where shopcoins='".$rows["catalog"]."' and `status`=1;";
						$result_m = mysql_query($sql_m);
						if (mysql_num_rows($result_m)>0) {
						
							$sql_up = "update shopcoinsmolotok set `status`=4 where shopcoins='".$rows["catalog"]."' and `status`=1;";
							mysql_query($sql_up);
						}
						*/
					}
					elseif ($rows["materialtype"]==4 || $rows["materialtype"]==7 || $rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==2 || $rows["materialtype"]==12)
					{
						
						$sql_update = "update shopcoins 
						set  amount=amount-".$rows["orderamount"]."
						".($rows["orderamount"]>=$rows["samount"]?", `check`=0":"")."
						, reserveorder='".$shopcoinsorder."', dateorder=".time()."
						where shopcoins='".$rows["catalog"]."';";
						
						/*echo $sql_update;
						echo "<br>".$rows["orderamount"];
						$sql_myinfo = "select * from shopcoins where shopcoins='".$rows["catalog"]."';";
						$result_myinfo = mysql_query($sql_myinfo);
						$rows_myinfo = mysql_fetch_array($result_myinfo);
						echo "<br>Amount = ".$rows_myinfo["amount"];*/
						
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
					$sql="SELECT shopcoins.*, if((shopcoins.realization=0 or shopcoins.dateinsert>".($timenow-4*24*3600)."),0,1) as param FROM shopcoins WHERE (shopcoins.check =1 or shopcoins.`check`>3) and parent in (".implode(",", $ParentArray).") GROUP BY shopcoins.parent order by shopcoins.`check` asc, param asc, shopcoins.dateinsert desc;";
					//if ($REMOTE_ADDR == $myip)	echo $sql;
					$result = mysql_query($sql);
					while ($rows = mysql_fetch_array($result))
					{
						$sql_info = "select count(*) from shopcoins where (`check`='1' or `check`>3) and parent='".$rows["parent"]."';";
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
			
			if ($user==811)
				$needcallingorder=3;
			
			$sql22 = "select * from comentorder where  toorder=0 and ((`user`=$user and  `user`!=811) or (`user`=$user and fio='".strip_string($userfio)."'));";
			$result22 = mysql_query($sql22);
			$CommentAdministrator = "";
			if (mysql_num_rows($result22)>0) {
			
				$rows22 = mysql_fetch_array($result22);
				$CommentAdministrator = $rows22['CommentAdministratorF'];
				$sql_up22 = "update comentorder set toorder=$shopcoinsorder where comentorder=".$rows22['comentorder'].";";
				mysql_query($sql_up22);
			}
			
			
			if($bonus_comment === TRUE)
				$CommentAdministrator = $CommentAdministrator . 'Оплата бонусными деньгами.';
				
			//подтверждаем заказ - order -------------------------------------------------------------------------------------------
			$sql = "update `order` set user='".$user."',
			userfio = '".strip_string($userfio)."', 
			phone='".strip_string($phone)."', adress='".strip_string(str_replace("|","/",$adress))."', 
			admincheck=0, sum='".$sum."', delivery='".strip_string($deliveryvalue)."', payment='".strip_string($paymentvalue)."',
			MetroMeeting = '$metrovalue', DateMeeting = '".($meetingdatevalue + $meetingfromtimevalue)."', 
			MeetingFromTime='".$meetingfromtimevalue."', 
			MeetingToTime='".$meetingtotimevalue."', 
			OtherInformation='".strip_string($OtherInformation)."', 
			CommentAdministrator = '".strip_string($CommentAdministrator)."',
			account_number='".strip_string($account_number)."', `check`=1,
			FinalSum = '$FinalSum' , NeedCall = '2', suminsurance='0', CallingOrder='$needcallingorder' ".(intval($idadmin)>0?",addidadmin=".intval($idadmin):"")."
			where `order`='".$shopcoinsorder."';";
			$result = mysql_query($sql);
			echo mysql_error();
			
			if ($cookiesuser>0 && $cookiesuser!=811 && ($deliveryvalue == 1 || $deliveryvalue == 3 || $deliveryvalue == 4 || $deliveryvalue == 6 || $deliveryvalue == 7)) {
			
				$sql9 = "select sms from `user` where `user`=".intval($cookiesuser).";";
				$result9 = mysql_query($sql9);
				$rows9 = mysql_fetch_array($result9);
				
				if ($rows9['sms']==1) {
				
					$sqls = "SHOW TABLE STATUS LIKE 'smssend';";
					$results = mysql_query($sqls);
					$rowss = mysql_fetch_array($results);
					$transactionsms = $rowss['Auto_increment'];
					
					include_once $in."smsconfig.php";
					
					$textsms1 = "";
					
					switch ($deliveryvalue) {
					
						case 1:
							$textsms1 = $MetroName." ".date('d.m.Y',$meetingdatevalue);
							break;
						case 3:
							$textsms1 = $MetroName." ".date('d.m.Y',$meetingdatevalue);
							break;
						case 4:
							$textsms1 = $MetroName." ".$paymentsms[$paymentvalue];
							break;
						case 7:
							$textsms1 = date('d.m.Y',$meetingdatevalue);
							break;
						
					}
					$textsms1 = str_replace("___1___",$textsms1,$arraytextsms2[$deliveryvalue]);
					/*$testingsms = sendsms2 (1,'9851703993',$shopcoinsorder,($FinalSum>0?$FinalSum:$sum),$textsms1);
					if ($testingsms !== false && sizeof($testingsms)>0 && ($testingsms[0] == 11 || $testingsms[0] == 0)) {
					
						$sqlsms = "insert into `smssend` (`smssend`,`user`,`phone`,`text`,`status`,`order`,`type`,`dateinsert`)
								values (NULL,$cookiesuser,'".$testingsms[1]."','".$testingsms[2]."',1,$shopcoinsorder,1,".time().");";
						mysql_query($sqlsms);
						$transactionsms++;
					}
					*/
					$testingsms = sendsms2 (1,$phone,$shopcoinsorder,($FinalSum>0?$FinalSum:$sum),$textsms1);
					//echo " nnn=".$testingsms[0]." === ".$testingsms[1]." === ".$testingsms[2];
					//if ($testingsms !== false && sizeof($testingsms)>0 && ($testingsms[0] == 11 || $testingsms[0] == 0)) {
					
						$sqlsms = "insert into `smssend` (`smssend`,`user`,`phone`,`text`,`status`,`order`,`type`,`dateinsert`)
								values (NULL,$cookiesuser,'".$testingsms[1]."','".$testingsms[2]."',1,$shopcoinsorder,1,".time().");";
						mysql_query($sqlsms);
					//}
				}
				
			}
			
			$sql = "update `order` set markadmincheck=3 where `user`='$cookiesuser' and `user`<>811 and `check`=1 and markadmincheck=2 and mark=2;";
			$result = mysql_query($sql);
			
			$sql = "select * from coupon where `user`='$cookiesuser' and `check`=1 and type=2;";
			$result = mysql_query($sql);
			if (mysql_num_rows($result)==0) {
				
				$couponup = 0;
				if (($sum-$sumamountprice - $vipcoinssum)>$bigsumcoupon && $typecoupon<2 && $user!=811) {
				
					$dis = ceil(($sum-$sumamountprice - $vipcoinssum)*$bigsumcoupondis/100);
					$couponup=1;
				}
				elseif (($sum-$sumamountprice - $vipcoinssum)>$smallsumcoupon && $typecoupon<2 && $user!=811) {
				
					$dis = ceil(($sum-$sumamountprice - $vipcoinssum)*$smallsumcoupondis/100);
					$couponup=1;
				}
				
				if ($couponup==1) {
				
					srand((double) microtime()*1000000);
					while ($couponup==1) {
						
						$code = '';
						for ($i=0;$i<16;$i++) {
						
							if ($i==4 || $i == 8 || $i==12)
								$code .= "-";
								
							$code .= $ArrayForCode[rand(0,9)];
						}
						
						$sql = "select count(*) from coupon where code='".$code."';";
						$result = mysql_query($sql);
						$rows = mysql_fetch_array($result);
						if ($rows[0] == 0) 
							$couponup = 2;
					}
					
					if ($deliveryvalue==1 || $deliveryvalue==2 || $deliveryvalue==3 || $deliveryvalue==7)
						$dateend = $timenow + 31*24*60*60 -1;
					elseif ($deliveryvalue==5 || $deliveryvalue==6 || ($deliveryvalue==4 && $paymentvalue!=1))
						$dateend = $timenow + (46+$PostZone2[$PostZoneNumber])*24*60*60 -1;
					elseif ($deliveryvalue==4 && $paymentvalue==1)
						$dateend = $timenow + (31+$PostZone2[$PostZoneNumber])*24*60*60 -1;
					else
						$dateend = $timenow + 31*24*60*60 -1;
					
					$sql_ins = "insert into coupon (`coupon`,`user`,`order`,`sum`,`code`,`dateend`,`type`,`dateinsert`,`check`)
							values(NULL,'".$user."','".$shopcoinsorder."','".$dis."','".$code."','".$dateend."','1','".time()."','1');";
					//echo $sql_ins;
					mysql_query($sql_ins);
				}
			}
			
			
			
			$resultsum = ($FinalSum>0?$FinalSum:$sum);
			//рассылаем письма------------------------------------------------------------------------------------------------------
			$crcode  = md5("numizmatikru:".sprintf ("%01.2f",round($resultsum*$krobokassa,2)).":".$shopcoinsorder.":$robokassapasword1:Shp_idu=$cookiesuser");
			$culture = "ru";
			$in_curr = "BANKOCEAN2R";
			
			$user_order_details2 .= "</td>
			</tr>";
			
			$user_order_details .= "</tr></table>
			<br>$AdvertiseText
			<br><br>Спасибо за покупку в нашем магазине !
			<br>Клуб Нумизмат - <a href=http://www.numizmatik.ru>www.numizmatik.ru</a><br><br>";
			
			$user_order_details5 = '';
			
			if ($paymentvalue==8)
				$user_order_details5 = "<tr><td colspan=6><form action='".$urlrobokassa."/Index.aspx' method=POST>".
   "<input type=hidden name=MrchLogin value='numizmatikru'>".
   "<input id=OutSum".$shopcoinsorder." type=hidden name=OutSum value='".sprintf ("%01.2f",round($resultsum*$krobokassa,2))."'>".
   "<input type=hidden name=InvId value='".$shopcoinsorder."'>".
   "<input type=hidden name=Desc value='Оплата предметов нумизматики'>".
   "<input id=SignatureValue".$shopcoinsorder." type=hidden name=SignatureValue value='$crcode'>".
   "<input type=hidden name=Shp_idu value='$cookiesuser'>".
   "<input type=hidden name=IncCurrLabel value='$in_curr'>".
   "<input type=hidden name=Culture value='$culture'>".
   "<input type=submit value='Оплатить VISA, MasterCard'> - <div id=info".$shopcoinsorder.">".sprintf ("%01.2f",round($resultsum*$krobokassa,2))." руб.</div> (При оплате банковскими картами комиссия 4%).".
   "</form></tr>";
			
			
			
			$user_order_details = str_replace('____VISAPAYMENT___',$user_order_details5,$user_order_details);
			echo $user_order_details;
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
			fclose($file);
			
			$recipient = "bodka@mail.ru";
			$subject = "Монетная лавка | Клуб Нумизмат";
			$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
			mail($recipient, $subject, $message.$order."\t".time()."\t".$HTTP_SERVER_VARS["HTTP_USER_AGENT"]."\n".$REMOTE_ADDR, $headers); //админу сайта
			
			$recipient = $email;
			mail($recipient, $subject, $message, $headers); //покупателю
			
			//изменение информации о пользователе
			if ($cookiesuser != 811) {
				
				$sql = "update user set 
				".(strip_string($email)?"email='".strip_string($email)."',":"")." phone='".strip_string($phone)."'".(trim($adress)?" , adress='".strip_string($adress)."' ":"")."
				where userlogin='$cookiesuserlogin' and userpassword='$cookiesuserpassword' and user='$cookiesuser';";
				
				$result = mysql_query($sql);
				echo mysql_error();
			}
			
			if ($paymentvalue>1 && 1==2) {
			
				?>
				<script type="text/javascript" src="ajax.php" language="JavaScript"></script>
				<script>
				
				function ShowSertificate() {
				
					var str = '<form class=formtxt name=FormCoupon><table width=90% cellpadding=2 cellspacing=1 border=0 align=center>';
					str += '<tr><td class=txt bgcolor=#EBE4D4 colspan=2> Если у Вас есть подарочные сертификаты и Вы желаете их использовать для оплаты данного заказа, введите номер и код в нижеприведенной форме. В ином случае оставьте поле пустым.</td></tr>';
					str += '<tr><td class=txt width=40% bgcolor=#EBE4D4 align=right><strong>Номер сертификата (полностью):</strong><td class=txt bgcolor=#EBE4D4><input type=text name="numbersert" value="" size=6 maxlength=6 class=formtxt></td></tr>';
					str += '<tr><td class=txt width=40% bgcolor=#EBE4D4 align=right><strong>Код сертификата:</strong><td class=txt bgcolor=#EBE4D4><input type=text name="code1" value="" size=4 maxlength=4 class=formtxt> - <input type=text name="code2" value="" size=4 maxlength=4 class=formtxt> - <input type=text name="code3" value="" size=4 maxlength=4 class=formtxt> - <input type=text name="code4" value="" size=4 maxlength=4 class=formtxt></td></tr>';
					str += '<tr><td class=txt width=40% bgcolor=#EBE4D4> <div id="CouponInfo" name="CouponInfo">&nbsp;</div></td><td class=txt bgcolor=#EBE4D4><input type=button class=formtxt value="Воспользоваться подарочным сертификатом" onClick="javascript:CheckFormCoupon(<? echo ($paymentvalue==8?1:0);?>);"></td></tr>';
					str += '</table></form>';
					myDiv = document.getElementById("sertificate");
					myDiv.innerHTML = str;
				}
				
function CheckFormCoupon(cardis)
{
	var numbersert = document.FormCoupon.numbersert.value;
	var code1 = document.FormCoupon.code1.value;
	var code2 = document.FormCoupon.code2.value;
	var code3 = document.FormCoupon.code3.value;
	var code4 = document.FormCoupon.code4.value;
	
	if (numbersert.length != 6) {
	
		alert ('Неверно введен номер сертификата');
		return 0;
	}

	if (code1.length != 4 || code2.length != 4 || code3.length != 4 || code4.length != 4 ) {
	
		alert ('Неверно введен код сертификата');
		return 0;
	}
	
	myDiv2 = document.getElementById('CouponInfo');
	myDiv2.innerHTML = '<img src=<?echo $in;?>images/wait.gif>';
	
	process ('activsert.php?shopcoinsorder99=<?echo $shopcoinsorder;?>&sert='+numbersert+'&code1=' + code1 + '&code2=' + code2 + '&code3=' + code3 + '&code4=' + code4 + '&cardis=' + cardis);
	
}

function ShowSert() {

	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	
	if (errorvalue == 'none')
	{
		
		var orderin = xmlRoot.getElementsByTagName("orderin").item(0).firstChild.data;
		
		if (orderin == "none") {
		
			alert ('нет заказа либо он уже отправлен или оплачен');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>нет заказа либо он уже отправлен или оплачен</font>';
		
		}
		else {
		
			var dissum = xmlRoot.getElementsByTagName("dissum").item(0).firstChild.data;
			var deltasum = xmlRoot.getElementsByTagName("deltasum").item(0).firstChild.data;
			var crcode = xmlRoot.getElementsByTagName("crcode").item(0).firstChild.data;
			var outsum = xmlRoot.getElementsByTagName("outsum").item(0).firstChild.data;
			var cardis = xmlRoot.getElementsByTagName("cardis").item(0).firstChild.data;
			
			alert ('Сумма '+dissum+' руб. использована в оплате заказа, остаток  на сертификате ' + deltasum + ' руб.');
			
			document.FormCoupon.code1.value = '';
			document.FormCoupon.code2.value = '';
			document.FormCoupon.code3.value = '';
			document.FormCoupon.code4.value = '';
			
			myDiv2 = document.getElementById('CouponInfo');
			
			myDiv2.innerHTML = 'Сумма '+dissum+' руб. использована в оплате заказа, остаток  на сертификате ' + deltasum + ' руб.';
			
			if (cardis != "none") {
			
				if (crcode != "none" && outsum != "none") {
				
					document.getElementById('SignatureValue'+orderin).value = crcode;
					document.getElementById('OutSum'+orderin).value = outsum;
					
					myDiv5 = document.getElementById('info'+orderin);
			
					myDiv5.innerHTML = outsum + ' руб.';
				
				}
				else {
				
					myDiv5 = document.getElementById('info'+orderin);
			
					myDiv5.innerHTML = 'Произошел сбой в системе - оплату картами VISA и MaterCard Вы можете произвести в "Ваши заказы"';
				}
				
			}
		}
	}
	else 
	{
		//alert('3');
		if (errorvalue == "error0") {
		
			alert ('Неверно введен код либо номер сертификата');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Неверно введен код либо номер сертификата</font>';
		}
		else if (errorvalue == "error1") {
		
			alert ('Неверно введен номер сертификата');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Неверно введен номер сертификата</font>';
		}
		else if (errorvalue == "error2") {
		
			alert ('неверные символы в коде');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>неверные символы в коде</font>';
		}
		else if (errorvalue == "error3") {
		
			alert ('нет заказа либо он уже отправлен или оплачен');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>нет заказа либо он уже отправлен или оплачен</font>';
		}
		else if (errorvalue == "error4") {
		
			alert ('Неверно введен код');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Неверно введен код</font>';
		}
		else if (errorvalue == "error5") {
		
			alert ('Сертификат уже был полностью использован.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Сертификат уже был полностью использован.</font>';
		}
		else if (errorvalue == "error6") {
		
			alert ('Время действия купона истекло.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Время действия купона истекло.</font>';
		}
		else if (errorvalue == "error7") {
		
			alert ('Сумма заказа равна 0 руб.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Купон не принят, так как сумма заказа уже равна 0 руб.</font>';
		}
		else if (errorvalue == "error8") {
		
			alert ('Сертификат уже был полностью использован.');
	
			myDiv2 = document.getElementById('CouponInfo');
			myDiv2.innerHTML = '<font color=red>Сертификат уже был полностью использован.</font>';
		}
		
	}
}
</div>
<?}