<? 
$mail_text = '<table border="0" cellpadding="0" cellspacing="0" width="650">';
$mail_text .='<tr><td colspan="2" style="font-size:14px;font-weight:bold;"><br>';
$mail_text .='Добрый день, уважаемый (ая) '.$user_data['fio']?$user_data['fio']:$user_data['email'].'.<br>';
$mail_text .= 'Вы получили письмо, так как оставили заявку на монету(ы) через наш каталог. ';
$mail_text .= 'Письмо получают все клиенты, оставившие заявку на такую же монету(ы). Первый по списку, оставивший заявку имеет приоритет покупки данной позиции. В этом случае монета(ы) бронируется, т.е. переходит в его корзину. Вы можете самостоятельно отслеживать на нашем сайте время окончания брони. Если время вышло, и клиент ее не купил, она становится свободной для покупки. Так как желающих приобрести данную позицию может быть несколько, важно сразу же после окончания брони положить монету в корзину и далее <b>оформить заказ</b>. ';
$mail_text .= '<br><br>Если монету(ы) уже купил первый по списку клиент, <b>монеты не будет на сайте</b>. При следующей поставке данной позиции приоритет переходит второму по списку клиенту, и т. д. Всем остальным, оставившим заявку, поступит данное письмо. Отправка такого письма будет производиться 3 раза в случае появления зарезервированных позиций в нашей монетной лавке. ';

$mail_text .= '<br>Вы можете связаться с менеджером по адресу <a href=mailto:administrator@numizmatik.ru>administrator@numizmatik.ru</a> в рабочие дни или по телефону 8-800-333-14-77 (бесплатный звонок по России) (<b><font color=red>+3 GMT MSK</font></b>).';
$mail_text .= '<br>Если Вы хотите подписаться / отписаться на какую-то монету, зайдите в каталог и выберите позиции, которые Вас интересуют. ';
$mail_text .='<br><br><b>Ссылка для просмотра данных монет</b>';
$mail_text .='<br><a target="_blank" href="http://www.numizmatik.ru/shopcoins/?page=viporder&id='.$viporder_id.'">http://www.numizmatik.ru/shopcoins/?page=viporder&id='.$viporder_id.'</a><br>';

$mail_text .= '</td></tr></table>';
$mail_text .= '<table border="0" cellpadding="0" cellspacing="0" width="650" style="border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;">';
$mail_text .= '<tr><td  style="border:1px solid #cccccc;padding:10px;">Наименование</td><td style="border:1px solid #cccccc;padding:10px;">Группа (страна)</td><td style="border:1px solid #cccccc;padding:10px;">Год</td><td style="border:1px solid #cccccc;padding:10px;">Номер</td><td  style="border:1px solid #cccccc;padding:10px;">Цена</td><td  style="border:1px solid #cccccc;padding:10px;">Количество</td><td style="border:1px solid #cccccc;padding:10px;" >Сумма</td></tr>' ;
foreach ( $tpl['viporder']['result'] as $rows){
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
