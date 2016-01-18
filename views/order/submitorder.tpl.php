<? if(isset($tpl['submitorder']['error_auth'])){?>    
    <div class="error">
    <p><b>Для оформления заказа необходимо авторизироваться на сайте или возможно у Вас истекло время отведенное на оформление заказа.</b></p>
    </div>
<?} elseif(isset($tpl['submitorder']['error_userstatus'])) {?>
     <div class="error">
    <p><b>Вам поставлен запрет на новые заказы. Для уточнения свяжитесь с администрацией.</b></p>
    </div>
<?} elseif(isset($tpl['submitorder']['error_userstatus'])) {?>
    
<?} elseif(isset($tpl['submitorder']['error'])) {?>
    <div class="error">
    <p> Некорректные параметры заказа. Пройдите процедуру оформления заказа заново!
       <form name=resultform method=post id=resultorderform action="<?=$cfg['site_dir']?>shopcoins/page=order&page2=1">
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
<div>

    <? if(isset($tpl['submitorder']['compare'])) {?>
    <div class="error">На постоянную работу удаленно требуется нумизмат для описания монет в административном интерфейсе. </b><br>Оплата сдельная - 2 рубля за монету, оплата через вебмани либо яндекс-деньги, либо бартер на заказы монет. Обращаться к администратору. E-mail: <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a>, тел.: +7-903-006-00-44,  +7-915-00-2222-3.
    </div>
    
   <?}?>
		<div><h5>Спасибо за покупку!</h5>
				
		Уважаемый покупатель!<br>Ваш заказ принят к рассмотрению. 
		Вы можете связаться с менеджером по адресу <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в рабочие дни 
		или по телефону 8-800-333-14-77 (бесплатный звонок по России) (<b><font color=red>+3 GMT MSK</font></b>).
		
		<br><br><b>Номер Вашего заказа: <b><?=$shopcoinsorder?></b><br>
        Информация о заказе:
		<table border=0 cellpadding=3 cellspacing=1 align=center>
		<tr bgcolor=#ffcc66>
		<td class=tboard><b>Название</b></td>
		<td class=tboard><b>Группа (страна)</b></td>
		<td class=tboard><b>Год</b></td>
		<td class=tboard><b>Номер</b></td>
		<td class=tboard><b>Цена</b></td>
		<td class=tboard><b>Кол - во</b></td></tr>
		
		<?
var_dump($tpl['submitorder'],$delivery);
		foreach ( $tpl['submitorder']['result'] as $rows){
			
			if ($tpl['submitorder']['result'][$i]['title_materialtype']){?>
				<tr bgcolor=#EBE4D4 valign=top><td colspan=7 class=tboard bgcolor=#99CCFF><b><?=$MaterialTypeArray[$rows["materialtype"]]?></b></td></tr>
			<?}?>

			<tr bgcolor=#EBE4D4 valign=top>
			<td class=tboard><a href=<?=$cfg['site_dir']?>shopcoins/show.php?catalog=<?=$rows["shopcoins"]?>&from=email target=_blank><?=$rows["name"]?></a></td>
			<td class=tboard><?=$rows["gname"]?></td>
			<td class=tboard><?=$rows["year"]?></td>
			<td class=tboard><?=$rows["number"]?></td>
			<td class=tboard><?=round($rows['price'],2)?> руб.</td>
			<td class=tboard align=center><?=($rows["oamount"]?$rows["oamount"]:1)?></td>
			</tr>
		<?}
		
		if ($discountcoupon) {?>
			<tr bgcolor=#EBE4D4><td class=tboard align=right colspan=4><b>Сумма заказа:</b></td>
			<td class=tboard><?=($sum+$discountcoupon)?> руб. </td><td>&nbsp;</td></tr>
			$user_order_details .= "<tr bgcolor=#EBE4D4><td class=tboard align=right colspan=4><b>Скидка по купону(ам):</b></td>
			<td class=tboard><?=$discountcoupon?> руб. </td><td>&nbsp;</td></tr>";
		<?}?>
		<tr bgcolor=#EBE4D4><td class=tboard align=right colspan=4><b>Итого:</b></td>
		<td class=tboard><?=$sum?> руб. </td><td></td></tr>
		
		<?		
		//показываем массу
		if ($delivery==4 || $delivery==5 || $delivery==6 || $delivery==7){?>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Вес (с учетом упаковки) ~</b> </font></td>
			<td class=tboard><?=$bascetpostweight?> грамм</td><td>&nbsp;</td></tr>
        <?}
		if ($delivery==6) {?>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Почтовые услуги:</b> </font></td>
			<td class=tboard><?=$tpl['submitorder']['sumEMC']?> руб.</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Страховка 1%:</b> </font></td>
			<td class=tboard>".(10)." руб.</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Конверт:</b> </font></td>
			<td class=tboard><?=$PriceLatter?> руб.</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>ИТОГО:</b> </font></td>
			<td class=tboard><font color=red><b><?=$tpl['submitorder']['FinalSum']?> руб.</b></font></td><td>&nbsp;</td></tr>

		<?}	elseif ($delivery==4 and $postindex) {	?>
            <tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Почтовые услуги:</b> </font></td>
			<td class=tboard><?=$PostZonePrice?> руб.</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Страховка 4%:</b> </font></td>
			<td class=tboard>".<?=($suminsurance>0?$suminsurance*0.04:$bascetinsurance)?> руб. <br>(В связи с участившимися случаями пропаж и вскрытия отправлений на Почте России<br> во всех почтовыех отправлениях страхование производится на полную их стоимость)</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>Конверт:</b> </font></td>
			<td class=tboard><?=$PriceLatter?> руб.</td><td>&nbsp;</td></tr>
			<tr bgcolor=#EBE4D4><td class=tboard colspan=4 align=right><b>ИТОГО:</b> </font></td>
			<td class=tboard><font color=red><b><?=$tpl['submitorder']['FinalSum']?> руб.</b></font></td><td>&nbsp;</td></tr>
			";
		<?}		
				
		if (($sum <= 0.00 || !$sum || $tpl['submitorder']['FinalSum'] <=0.00 || !$tpl['submitorder']['FinalSum']) && $discountcoupon<=0){
			echo "<p class=txt> <font color=red><b>Сумма заказа менее 500.00 руб. </b></font></p>";
		} else {	?>
			____VISAPAYMENT___</table><?=$sertprint?>
			<table border=0 cellpadding=3 cellspacing=1 align=center>
			<tr><td class=tboard>
			<hr size=1 color=#000000><br>
					<?if($payment==6){?>
				<center><a href='http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=".$shopcoinsorder."&FIO=".urlencode(strip_string($fio))."&ADRESS=".urlencode($adress)."&SUM=".$tpl['submitorder']['FinalSum']."' target='_blank'><img src=".$in."images/sbrfprint.gif border=1 style='border-color:black' alt='Распечатать квитанцию Сбербанка'></a><br><b>Ссылка:</b> <a href='http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=".$shopcoinsorder."&FIO=".urlencode(strip_string($fio))."&ADRESS=".urlencode($adress)."&SUM=".$tpl['submitorder']['FinalSum']."' target='_blank'>http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=".$shopcoinsorder."</a></center><br>"
})<?}?>
				<?=$SumProperties[$payment]?>
			<hr size=1 color=#000000>
			</td></tr>
			</table>
			</table>
			<div><font color=red>Внимание!!!</font>  У нас действует акция "Приведи друга".<a href=http://www.numizmatik.ru/shopcoins/aboutfrendaction.php target=_blank>Подробнее >>></a></b><br>
			Ваш код для участия в акции: <b><font color=red><big><?=strtoupper($codeforfrend)?></big></font></b><br>
			Вы также можетет использовать ссылку для этой акциии на различных веб-ресурсах. <br>
			Текст вашей ссылки для размещения: <b>http://www.numizmatik.ru/user/registration.php?codeforfrend=<?=$codeforfrend?></b>
			</div>
			<hr size=1 color=#000000>
			<p class=txt><b>Информация о покупателе:</b>
			<div><b>ФИО:</b><?=$fio?></div>
			<div><b>Контактный телефон:</b><?=$phone?></div>
			<? if ($adress){?>
				<div><b>Адрес доставки:</b><?=$adress?></div>
			<?}
			if ($delivery==2)	{$DeliveryName[$delivery] = "В офисе (возможность посмотреть материал до выставления)";}
			?>
			<div><b>Способ доставки:</b><?=$DeliveryName[$delivery]?></div>

			<?if ($delivery==2){?>
				<div>
				 14-5 минут от метро Тверская.<br>
					Рабочие дни с 10.00 до 18.00.  <br><br>
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2244.680447852284!2d37.609148899999994!3d55.76405310000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54a46b695b08f%3A0x5e895e0d8de444fe!2z0KLQstC10YDRgdC60LDRjyDRg9C7LiwgMTLRgTgsINCc0L7RgdC60LLQsCwgMTI1MDA5!5e0!3m2!1sru!2sru!4v1416476603568" width="600" height="450" frameborder="0" style="border:0"></iframe>
				</div>
			<?}?>

			<div><b>Способ оплаты:</b><?=$SumName[$paymentvalue]?></div>
			
			<?if ($tpl['submitorder']['MetroName']){?>
				<div><b>Метро:</b><?=$tpl['submitorder']['MetroName']?></div>
			<?}
			//дата
			if ($meetingdate and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7)){?>
				<div><b>Дата:</b><?=$DaysArray[date("w",$meetingda)].":".date("d-m-Y", $meetingdate)?>"</div>
			<?}
			//время
			if ($meetingfromtime and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7)){?>
				<div><b>Время:</b><?=date("H-i", $timenow + $meetingfromtime)." по ".date("H-i", $timenow + $meetingtotime)?></div>
			<?}?>

			<br><br>Спасибо за покупку в нашем магазине !
			<br>Клуб Нумизмат - <a href=http://www.numizmatik.ru>www.numizmatik.ru</a><br><br>
	<?}
}
			
