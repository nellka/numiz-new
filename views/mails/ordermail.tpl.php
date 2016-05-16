<? $mail_text = '<table border="0" cellpadding="0" cellspacing="0" width="650">';
$mail_text .='<tr><td colspan="2" style="font-size:14px;font-weight:bold;"><br>';
$mail_text .='Уважаемый(ая)'.$fio.', благодарим Вас за то, что воспользовались услугами нашего магазина.<br>';
$mail_text .= 'Номер вашего заказа:<font color="red">'.$shopcoinsorder.' </font>от '.date('d.m.Y',time());
$mail_text .= '<br><br>Вы можете связаться с менеджером по адресу <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в рабочие дни или по телефону 8-800-333-14-77 (бесплатный звонок по России) (<b><font color=red>+3 GMT MSK</font></b>).';
$mail_text .= '</td></tr></table>';
$mail_text .= '<table border="0" cellpadding="0" cellspacing="0" width="650" style="border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;">';
$mail_text .= '<tr><td  style="border:1px solid #cccccc;padding:10px;">Наименование</td><td style="border:1px solid #cccccc;padding:10px;">Группа (страна)</td><td style="border:1px solid #cccccc;padding:10px;">Год</td><td style="border:1px solid #cccccc;padding:10px;">Номер</td><td  style="border:1px solid #cccccc;padding:10px;">Цена</td><td  style="border:1px solid #cccccc;padding:10px;">Количество</td><td style="border:1px solid #cccccc;padding:10px;" >Сумма</td></tr>' ;
foreach ( $tpl['submitorder']['result'] as $rows){
	if ($rows['title_materialtype']){
		$mail_text .= '<td colspan="7" style="border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;">'.$MaterialTypeArray[$rows["materialtype"]].'</td></tr>';
	}
	$mail_text .= '<tr>';
	$mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;"><a href='.$cfg['site_dir'].'shopcoins/show.php?catalog='.$rows["catalog"].' target=_blank>'.$rows["name"].'</a></td>';
	$mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows["gname"].'</td>';
	$mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows["year"].'</td>';
	$mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows["number"].'</td>';
    $mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;">'.round($rows['price'],2).' руб.</td>';
    $mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;">'.($rows["oamount"]?$rows["oamount"]:1).'</td>';
    $mail_text .= '<td  style="border:1px solid #cccccc;padding:10px;">'.round(($rows["oamount"]?$rows["oamount"]:1)*$rows['price'],2).' руб.</td>';
	$mail_text .= '</tr>';
}


$mail_text .= '</table>';
$mail_text .= '<table border="0" cellpadding="0" cellspacing="0" width="650" style="margin-top:20px;">';
$mail_text .= '<tr><td width="50%"></td><td align="left" style="font-size:14px;font-weight:bold;line-height:24px;">';
if ($tpl['submitorder']['discountcoupon']) {
    if($user_data['vip_discoint']) {
	   $mail_text .= 'Сумма заказа: <font color="red">'.($tpl['submitorder']['sum']+$tpl['submitorder']['discountcoupon']).' руб.</font><br>';
	   $mail_text .='Скидка как VIP-клиент:<font color="red"> '.$tpl['submitorder']['vip_discoint'].' %</font><br>';
	   $mail_text .='Размер скидки: <font color="red">'.$tpl['submitorder']['discountcoupon'].' руб.</font><br>';
   } else {
       $mail_text .= 'Сумма заказа: <font color="red">'.($tpl['submitorder']['sum']+$tpl['submitorder']['discountcoupon']).' руб.</font><br>';
	   $mail_text .='Скидка по купону(ам):<font color="red"> '.$tpl['submitorder']['discountcoupon'].' руб.</font><br>';
   }
}
$mail_text .= 'Итого: <font color="red">'.$tpl['submitorder']['sum'].' руб. </font><br>';
$mail_text .= '</td></tr></table>';
$mail_text .= '<table border="0" cellpadding="0" cellspacing="0" width="650" style="margin-top:20px;">';
$mail_text .= '<tr><td align="left" style="font-size:14px;font-weight:bold;line-height:18px;">';
$mail_text .= 'Способ оплаты: '.$SumName[$payment].'<br>';
$mail_text .= 'Способ доставки: '.$DeliveryName[$delivery].'<br>';

if ($delivery==2) $mail_text .= '14-5 минут от метро Тверская.<br>Рабочие дни с 10.00 до 18.00.  <br><br>';
if ($tpl['submitorder']['MetroName']) $mail_text .= 'Метро: '.$tpl['submitorder']['MetroName'].'<br>';

//дата
if ($meetingdate and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7)){
	$mail_text .= 'Дата: '.$DaysArray[date("w",$meetingdate)].": ".date("d-m-Y", $meetingdate).'<br>';
}
//время
if ($meetingfromtime and ($delivery == 1 || $delivery == 2 || $delivery == 3 || $delivery == 7)){
	$mail_text .= 'Время: '.date("H-i", $timenow + $meetingfromtime).' по '.date("H-i", $timenow + $meetingtotime).'<br>';
}

if ($delivery==4 || $delivery==5 || $delivery==6 || $delivery==7){
	$mail_text .= 'Вес (с учетом упаковки) ~'.$tpl['submitorder']['bascetpostweight'].' грамм.<br>';
}

if ($adress) $mail_text .= 'Адрес доставки: '.$adress.'<br>';
if ($delivery==6) {
	$mail_text .= 'Почтовые услуги: '.$tpl['submitorder']['sumEMC'].' руб.<br>';
	$mail_text .= 'Страховка 1%: '.(10).' руб.<br>';
	$mail_text .= 'Конверт:: '.$PriceLatter.' руб.<br>';

}	elseif ($delivery==4 and $postindex) {
	$mail_text .= 'Почтовые услуги: '.$tpl['submitorder']['PostZonePrice'].' руб.<br>';
	$mail_text .= 'Страховка 4%: '.($suminsurance>0?$suminsurance*0.04:$bascetinsurance).' руб. <br>(В связи с участившимися случаями пропаж и вскрытия отправлений на Почте России<br> во всех почтовыех отправлениях страхование производится на полную их стоимость)<br>';
	$mail_text .= 'Конверт:: '.$PriceLatter.' руб.<br>';
}

$mail_text .= '<b>ИТОГО:</b> '. $tpl['submitorder']['FinalSum'].' руб.';

$mail_text .='<br><b>Информация о покупателе:</b><br>';
$mail_text .='ФИО: '.$fio.'<br>';
$mail_text .='Контактный телефон: '.$phone.'<br>';
$mail_text .='</td></tr></table><br><br>';
$mail_text .='<div style="border: 1px solid rgb(204, 204, 204); width: 650px;"><SPAN color="red">Внимание!!!</SPAN>  У нас действует акция "Приведи друга"<br>';
$mail_text .='<a target="_blank" href="http://www.numizmatik.ru/shopcoins/aboutfrendaction.php">Подробнее &gt;</a><br>';
$mail_text .='Ваш код для участия в акции: <b><span  color="red">'.strtoupper($codeforfrend).'</span></b><br><br>';
$mail_text .='Вы также можетет использовать ссылку для этой акциии на различных веб-ресурсах. <br>';
$mail_text .='Текст вашей ссылки для размещения: <b><a href="http://www.numizmatik.ru/user/registration.php?codeforfrend='.$codeforfrend.'">http://www.numizmatik.ru/user/registration.php?codeforfrend='.$codeforfrend.'</a></b><br><br>';
$mail_text .='</div><br><br>Спасибо за покупку в нашем магазине!';