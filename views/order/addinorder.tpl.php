<? if($tpl['addinorder']['error']){?>
	<div class="error">
		<p><b><?=$tpl['addinorder']['error']?></b></p>
	</div>
<?} else {?>
	
	<p><b><font color=white>Монетная лавка Клуба Нумизмат</font></b>
	
	<?if (!$addinordersubmit){?>
		Уважаемый пользователь!<br>Ваш заказ будет объединен с предыдущим. 
		Проверьте пожалуйста информацию о Вашем заказе.
		<br><br><b>Номер заказа: <?=$shopcoinsorder?></b>
    <?} else {?>
		Уважаемый покупатель!<br>Ваш заказ принят к рассмотрению. Он будет объединен с предыдущим заказом 
		Вы можете связаться с менеджером по адресу <a href=mailto:<?=$user_admin_email?>><?=$user_admin_email?></a> по рабочим дням 
		или по телефону 8-915-002-22-23 (<b><font color=red>+3 GMT MSK</font></b>).
		Проверьте пожалуйста информацию о Вашем заказе.
		<br><br><b>Номер заказа: <?=$shopcoinsorder?></b>
	<?}?>
	
	<table>
	<tr>
	<td class=tboard><b>Название</b></td>
	<td class=tboard><b>Группа (страна)</b></td>
	<td class=tboard><b>Год</b></td>
	<td class=tboard><b>Номер</b></td>
	<td class=tboard><b>Цена</b></td>
	<td class=tboard><b>Кол - во</b></td></tr>
	
	<? foreach ($tpl['submitorder']['result'] as $rows ){
		// начинаем заполнять поле товара
				
		if ($tpl['submitorder']['result'][$i]['title_materialtype']){?>	
			<tr>
			<td colspan=7 class="h-cat">

			     <b><?=$tpl['submitorder']['result'][$i]['title_materialtype']?></b>
			</td>
			</tr>

		<?}?>
		<tr valign=top>
		<td class=tboard><a href='<?=$cfg['site_dir']?>shopcoins/show.php?catalog=<?=$rows["shopcoins"]?>' target=_blank><?=$rows["name"]?></a></td>
		<td class=tboard><?=$rows["gname"]?></td>
		<td class=tboard>
		<?if ( $rows["year"]) echo $rows["year"]?>
		</td>
		<td class=tboard><?=$rows["number"]?></td>
		<td class=tboard><?=($clientdiscount==1 && $rows['clientprice']>0?round($rows['clientprice'],2):round($rows['price'],2))?> руб.</td>
		<td class=tboard align=center><?=($rows["orderamount"]?$rows["orderamount"]:1)?></td>		
		</tr>
	<?}?>
	</table>

	<tr bgcolor=#EBE4D4><td class=tboard align=right colspan=4><b>Итого:</b></td>
	<td class=tboard><?=$sum?> руб. </td><td>&nbsp;</td></tr>
	<?
	
	//показываем массу
	if ($rows_temp['delivery']==4 || $rows_temp['delivery']==5 || $rows_temp['delivery']==6 || $rows_temp['delivery']==7){?>
		<div><b>Вес (с учетом упаковки) ~</b>: </font><?=$bascetpostweight?> грамм</div>
	<?}
	if ($rows_temp['delivery']==4 and $postindex){?>
		<div><b>Почтовые услуги:</b> </font> <?=$PostZonePrice?> руб.</div>
		<div><b>Страховка 4%:</b> </font><?=($suminsurance?$suminsurance*0.04:$bascetinsurance)?> руб.</div>
		<div><b>Конверт:</b> </font><?=$PriceLatter?> руб.</div>
		<div><b>ИТОГО:</b> </font><b><?=$FinalSum?> руб.</b></font></div>	
	<?}?>
	</table>
	<?	
		
		if ($addinordersubmit) {?>
			<?if($rows_temp['payment']==6){?>
			<center>
			<a href='http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=<?=$shopcoinsorder?>&FIO=<?=urlencode($fio)?>&ADRESS=<?=urlencode($adress)?>&SUM=<?=$FinalSum?>' target='_blank'><img src=".$in."images/sbrfprint.gif border=1 style='border-color:black' alt='Распечатать квитанцию Сбербанка'></a>
			<br><b>Ссылка:</b> <a href='http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=<?=$shopcoinsorder?>&FIO=<?=urlencode(strip_string($fio))?>&ADRESS=<?=urlencode($adress)?>&SUM=<?=$FinalSum?>' target='_blank'>http://www.numizmatik.ru/shopcoins/sbrf.php?NUMBER=<?=$shopcoinsorder?>&FIO=<?=urlencode($fio)?>&ADRESS=<?=urlencode($adress)?>&SUM=<?=$FinalSum?></a>
			</center><br>
			<?}?>
			<?=$SumProperties[$rows_temp['payment']]?>
		<?}?>		
		
		
		<h5>Информация о покупателе:</h5>
		<div><b>ФИО:</b><?=$rows_temp['userfio']?></div>
		<div><b>Контактный телефон:</b><?=$rows_temp['phone']?></div>
		
		<?if ($rows_temp['adress']){?>
			<div><b>Адрес доставки:</b><?=$rows_temp['adress']?></div>
		<?
		}
		
		if ($rows_temp['delivery']==2) 	$DeliveryName[$rows_temp['delivery']] = "В офисе (возможность посмотреть материал до выставления)";?>

		<div><b>Способ доставки:</b><?=$DeliveryName[$rows_temp['delivery']]?>
		
		<?if ($rows_temp['delivery']==2) {?>
			<br>2-3 минуты от метро Тверская.  <br>Рабочие дни с 10.00 до 18.00.  <br><iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.ru/maps?hl=ru&amp;q=%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0+%D0%BA%D1%80%D0%B0%D1%81%D0%BD%D0%BE%D0%BF%D1%80%D0%BE%D0%BB%D0%B5%D1%82%D0%B0%D1%80%D1%81%D0%BA%D0%B0%D1%8F+16&amp;lr=&amp;ie=UTF8&amp;hq=&amp;hnear=%D0%90%D0%9E+%D0%A6%D0%B5%D0%BD%D1%82%D1%80%D0%B0%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9,+%D0%B3%D0%BE%D1%80%D0%BE%D0%B4+%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0,+%D0%9A%D1%80%D0%B0%D1%81%D0%BD%D0%BE%D0%BF%D1%80%D0%BE%D0%BB%D0%B5%D1%82%D0%B0%D1%80%D1%81%D0%BA%D0%B0%D1%8F+%D1%83%D0%BB.,+16&amp;ll=55.777152,37.607768&amp;spn=0.018102,0.054932&amp;z=15&amp;output=embed"></iframe><br /><small><a href="http://maps.google.ru/maps?hl=ru&amp;q=%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0+%D0%BA%D1%80%D0%B0%D1%81%D0%BD%D0%BE%D0%BF%D1%80%D0%BE%D0%BB%D0%B5%D1%82%D0%B0%D1%80%D1%81%D0%BA%D0%B0%D1%8F+16&amp;lr=&amp;ie=UTF8&amp;hq=&amp;hnear=%D0%90%D0%9E+%D0%A6%D0%B5%D0%BD%D1%82%D1%80%D0%B0%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9,+%D0%B3%D0%BE%D1%80%D0%BE%D0%B4+%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0,+%D0%9A%D1%80%D0%B0%D1%81%D0%BD%D0%BE%D0%BF%D1%80%D0%BE%D0%BB%D0%B5%D1%82%D0%B0%D1%80%D1%81%D0%BA%D0%B0%D1%8F+%D1%83%D0%BB.,+16&amp;ll=55.777152,37.607768&amp;spn=0.018102,0.054932&amp;z=15&amp;source=embed" style="color:#0000FF;text-align:left" target=_blank>Просмотреть увеличенную карту</a></small>
			
		<?}?>
		</div>
		<div><b>Способ оплаты:</b><?=$SumName[$rows_temp['payment']]?>		
		
		<? if ($MetroName){?>
			<div><b>Метро:</b><?=$MetroName?></div>
		<?
		}
		//дата
		if (($rows_temp['DateMeeting']-$rows_temp['MeetingFromTime'])>0 and ($rows_temp['delivery'] == 1 || $rows_temp['delivery'] == 2 || $rows_temp['delivery'] == 3 || $rows_temp['delivery'] == 7)){?>
			<div><b>Дата:</b><?=$DaysArray[date("w",($rows_temp['DateMeeting']-$rows_temp['MeetingFromTime']))].":".date("d-m-Y", ($rows_temp['DateMeeting']-$rows_temp['MeetingFromTime']))?></div>
		<?}
		//время
		if ($rows_temp['MeetingFromTime'] and ($rows_temp['delivery'] == 1 || $rows_temp['delivery'] == 2 || $rows_temp['delivery'] == 3 || $rows_temp['delivery'] == 7)){?>
			<div><b>Время:</b><?=date("H-i", $timenow + $rows_temp['MeetingFromTime'])." по ".date("H-i", $timenow + $rows_temp['MeetingToTime'])?></div>
		<?}
		if(!$addinordersubmit){?>
		<form action='<?=$cfg['site_dir']?>shopcoins/index.php?page=order&page2=2' method=post >
		<input type="submit" name=addinordersubmit id=addinordersubmit class="button25"  value='Добавить к предыдущему заказу' style="width:250px">
		</form>		
		
		<hr size=1 color=#000000>
		<br><?=$SumProperties[$rows_temp['payment']]?>

	<?}
}


?>