<div style="font-weight:400;">
<? if(isset($tpl['submitorder']['error_sumlimit'])){?>    
    <div class="error" style="margin-top:140px;margin-bottom:140px;">
    <p><b>Внимание!</b> Вы привысили лимит своих невыкупленных заказов по общей сумме.<br>
		Для выяснения обстоятельств свяжитесь с администрацией по тел. +7-903-006-00-44 или  +7-915-002-22-23. С 10-00 до 18-00 МСК (по рабочим дням).
	</p>
    </div>
<?} elseif(isset($tpl['submitorder']['error_auth'])){?>    
    <div class="error" style="margin-top:140px;margin-bottom:140px;">
    <p><b>Для оформления заказа необходимо авторизироваться на сайте или возможно у Вас истекло время отведенное на оформление заказа.</b></p>
    </div>
<?} elseif(isset($tpl['submitorder']['error_userstatus'])) {?>
     <div class="error"  style="margin-top:140px;margin-bottom:140px;">
    <p><b>Вам поставлен запрет на новые заказы. Для уточнения свяжитесь с администрацией.</b></p>
    </div>
<?}  elseif(isset($tpl['submitorder']["error_already_buy"])){?>
	<div class="error"  style="margin-top:140px;margin-bottom:140px;"><p>Заказ <?=$shopcoinsorder?> уже оформлен Вами. Вы можете его просомтреть в "Ваши заказы"</b></p></div>
<? } else if(isset($tpl['submitorder']['error_little'])){?>
	<div class="error"  style="margin-top:140px;margin-bottom:140px;"><p>Сумма заказа менее 500.00 руб. </p> </div>
<?} elseif(isset($tpl['submitorder']['error'])) {?>
    <div class="error"  style="margin-top:40px;margin-bottom:140px;">
    <p> Некорректные параметры заказа. Пройдите процедуру оформления заказа заново!<br><br>
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

    <? if(isset($tpl['submitorder']['compare'])) {?>
    <div class="error">На постоянную работу удаленно требуется нумизмат для описания монет в административном интерфейсе. </b><br>
	    Оплата сдельная - 2 рубля за монету, оплата через вебмани либо яндекс-деньги, либо бартер на заказы монет.
	    Обращаться к администратору. E-mail: <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a>, тел.: +7-903-006-00-44,  +7-915-00-2222-3.
    </div>
   <?}
   //var_dump($tpl['submitorder']);

   ?>
	<div style="font-weight:400;">
	   <h5 class=error>Поздравляем. Ваш заказ успешно оформлен.</div></h5>
		<h5>Спасибо за покупку!</h5>
		Уважаемый покупатель!<br>Ваш заказ принят к рассмотрению. 
		Вы можете связаться с менеджером по адресу <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в рабочие дни 
		или по телефону 8-800-333-14-77 (бесплатный звонок по России) (<b><font color=red>+3 GMT MSK</font></b>).
		
		<br><br><p>Номер Вашего заказа: <b><?=$tpl['submitorder']['shopcoinsorder']?></b><br>
        <h5>Информация о заказе:</h5>
		<table cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:12px;width:500px;border: 1px solid #cccccc;">
			<tr>
				<td class="tboardtop"><b>Название</b></td>
				<td class="tboardtop"><b>Группа (страна)</b></td>
				<td class="tboardtop"><b>Год</b></td>
				<td class="tboardtop"><b>Номер</b></td>
				<td class="tboardtop"><b>Цена</b></td>
				<td class="tboardtop"><b>Кол - во</b></td>
			</tr>
		
			<?
			foreach ( $tpl['submitorder']['result'] as $rows){			   
				if ($rows['title_materialtype']){?>
					<tr><td colspan=7 class="h-cat" ><b><?=$MaterialTypeArray[$rows["materialtype"]]?></b></td></tr>
				<?}?>

				<tr valign=top>
					<td class="tboardtop"><div id="image<?=$rows['catalog']?>"><a href=<?=$cfg['site_dir']?>shopcoins/show.php?catalog=<?=$rows["catalog"]?> target=_blank><?=$rows["name"]?></a></td>
					<td class="tboardtop"><?=$rows["gname"]?></td>
					<td class="tboardtop"><?=$rows["year"]?></td>
					<td class="tboardtop"><?=$rows["number"]?></td>
					<td class="tboardtop"><?=round($rows['price'],2)?> руб.</td>
					<td  class="tboardtop" align=center><?=($rows["oamount"]?$rows["oamount"]:1)?></td>
				</tr>
			<?}?>
		</table>
		<?  
		if ($tpl['submitorder']['discountcoupon']) {
        	if($tpl['user']['vip_discoint']) {?>
        	    <p><b>Сумма заказа: </b><font color="red"> <?=($tpl['submitorder']['sum']+$tpl['submitorder']['discountcoupon'])?> руб.</font></p>
        	    <p><b>Скидка как VIP-клиент: </b><font color="red"> <?=$tpl['submitorder']['vip_discoint']?> %</font></p>
        	    <p><b>Размер скидки: </b><font color="red"> <?=$tpl['submitorder']['discountcoupon']?> руб.</font></p>
           <?} else {?>
               <p><b>Сумма заказа: </b><font color="red"> <?=($tpl['submitorder']['sum']+$tpl['submitorder']['discountcoupon'])?> руб.</font> </p>
			   <p><b>Скидка по купону: </b><font color="red"><?=$tpl['submitorder']['discountcoupon']?> руб. </font></p>
          <? }			
		}?>
			<p><b>Итого: </b><font color="red"><b><?=$tpl['submitorder']['sum']?> руб. </b></font></p>
		<?		
		//показываем массу
		if ($delivery==4 || $delivery==5 || $delivery==6 || $delivery==7){?>
			<p><b>Вес (с учетом упаковки) ~</b> <?=$tpl['submitorder']['bascetpostweight']?> грамм</p>
        <?}
		if ($delivery==6) {?>
			<p><b>Почтовые услуги:</b> <?=$tpl['submitorder']['sumEMC']?> руб.</p>
			<p><b>Страховка 1%:</b> 10 руб.
			<p><b>Конверт:</b> <?=$PriceLatter?> руб.</p>
			<p><b>ИТОГО: <?=$tpl['submitorder']['FinalSum']?> руб.</b></p>

		<?}	elseif ($delivery==4 and $postindex) {	?>
            <p><b>Почтовые услуги:</b> <?=$tpl['submitorder']['PostZonePrice']?> руб.</p>
			<p><b>Страховка 4%:</b> <?=($tpl['submitorder']['suminsurance']>0?$tpl['submitorder']['suminsurance']*0.04:$bascetinsurance)?> руб.
			 <br>(В связи с участившимися случаями пропаж и вскрытия отправлений на Почте России<br> во всех почтовыех отправлениях страхование производится на полную их
			 стоимость)</p>
			<p><b>Конверт:</b> <?=$PriceLatter?> руб.</p>
			<b>ИТОГО: <?=$tpl['submitorder']['FinalSum']?> руб.</b></p>
		<?}?>
	</div>
			<hr size=1 color=#000000><br>
			<?if($payment==6){?>
			<p><a href='http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=<?=$shopcoinsorder?>&FIO=<?=urlencode(strip_string($fio))?>&ADRESS=<?=urlencode($adress)?>&SUM=<?=$tpl['submitorder']['FinalSum']?>' target='_blank'>
			<img src="http://www.numizmatik.ru/images/sbrfprint.gif" border=1 style='border-color:black' alt='Распечатать квитанцию Сбербанка'>
			</a><br>
			<b>Ссылка:</b> <a href='http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=<?=$shopcoinsorder?>&FIO=<?=urlencode(strip_string($fio))?>&ADRESS=<?=urlencode($adress)?>&SUM=<?=$tpl['submitorder']['FinalSum']?>' target='_blank'>http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=<?=$shopcoinsorder?></a>
			</p>
			<?}?>
			<?=$SumProperties[$payment]?>
			<hr size=1 color=#000000>
			<div><span class="error">Внимание!!!</span>  <br>У нас действует акция "Приведи друга"<br><a href=http://www.numizmatik.ru/shopcoins/aboutfrendaction.php target=_blank>Подробнее >>></a></b><br>
			<br>Ваш код для участия в акции: <b><span class="error"><?=strtoupper($codeforfrend)?></span></b><br>
			Вы также можетет использовать ссылку для этой акциии на различных веб-ресурсах. <br>
			Текст вашей ссылки для размещения: <b>http://www.numizmatik.ru/user/registration.php?codeforfrend=<?=$codeforfrend?></b>
			</div>
			<hr size=1 color=#000000>
			<p class=txt><b>Информация о покупателе:</b>
			<div><b>ФИО: </b><?=$fio?></div><br>
			<div><b>Контактный телефон: </b><?=$phone?></div><br>
			<? 
			if (($delivery==3||$delivery==4||$delivery==6)&&$adress){?>
				<div><b>Адрес доставки: </b><?=$adress?></div><br>
			<?}?>
			<div><b>Способ доставки: </b><?=$DeliveryName[$delivery]?></div><br>

			<?if ($delivery==2){?>
				<div>
				 14-5 минут от метро Тверская.<br>
					Рабочие дни с 10.00 до 18.00.  <br><br>
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2244.680447852284!2d37.609148899999994!3d55.76405310000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54a46b695b08f%3A0x5e895e0d8de444fe!2z0KLQstC10YDRgdC60LDRjyDRg9C7LiwgMTLRgTgsINCc0L7RgdC60LLQsCwgMTI1MDA5!5e0!3m2!1sru!2sru!4v1416476603568" width="600" height="450" frameborder="0" style="border:0"></iframe>
				</div><br>
			<?}?>

			<div><b>Способ оплаты: </b><?=$SumName[$payment]?></div><br>
			
			<?if ($tpl['submitorder']['MetroName']){?>
				<div><b>Метро: </b><?=$tpl['submitorder']['MetroName']?></div><br>
			<?}
			//дата
			if ($meetingdate and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7)){?>
				<div><b>Дата: </b><?=$DaysArray[date("w",$meetingdate)].":".date("d-m-Y", $meetingdate)?></div>
			<?}
			//время
			if ($meetingfromtime and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7)){?>
				<div><b>Время: </b><?=date("H-i", $timenow + $meetingfromtime)." по ".date("H-i", $timenow + $meetingtotime)?></div>
			<?}
			if ($payment==8) {
				echo "<tr><td colspan=6><form action='".$urlrobokassa."/Index.aspx' method=POST>".
   "<input type=hidden name=MrchLogin value='numizmatikru'>".
   "<input id=OutSum".$shopcoinsorder." type=hidden name=OutSum value='".sprintf ("%01.2f",round($resultsum*$krobokassa,2))."'>".
   "<input type=hidden name=InvId value='".$shopcoinsorder."'>".
   "<input type=hidden name=Desc value='Оплата предметов нумизматики'>".
   "<input id=SignatureValue".$shopcoinsorder." type=hidden name=SignatureValue value='$crcode'>".
   "<input type=hidden name=Shp_idu value='".$tpl['user']['user_id']."'>".
   "<input type=hidden name=IncCurrLabel value='$in_curr'>".
   "<input type=hidden name=Culture value='$culture'>".
   "<input type=submit value='Оплатить VISA, MasterCard'> - <div id=info".$shopcoinsorder.">".sprintf ("%01.2f",round($resultsum*$krobokassa,2))." руб.</div> (При оплате банковскими картами комиссия 4%).".
   "</form></tr>";
			}?>
			<br><br>Спасибо за покупку в нашем магазине !
			<br>Клуб Нумизмат - <a href=http://www.numizmatik.ru>www.numizmatik.ru</a><br><br>
<?}?>
</div>		
