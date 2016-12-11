<?
require_once $cfg['path'] . '/models/crons.php';
require_once($cfg['path'] . '/models/mails.php');

$cron_class = new crons($cfg['db']);


$message = "<table border='0' cellpadding='0' cellspacing='0' width='650' style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>
<tr><td style=\"border:1px solid #cccccc; background-color:#eeeeee;padding:10px;font-size:14px;font-weight:bold;\">Клуб Нумизмат | Заказ №___number___</td></tr>
<tr><td style='padding: 10px;'>
Добрый день уважаемый (ая), ___fio___.<br>
Не сочтите это письмо рекламой.<br>
Вас беспокоит администратор Клуба Нумизмат Мандра Богдан. Ваш заказ № ___number___ был отправлен ___amount_day___ по указанному адресу.<br><br>

<b>Содержимое заказа:</b>
___content___<br>
На сумму: ___sum___ руб.<br><br>

___SendPostBanderoleNumber___<br><br>

Для улучшения качества наших услуг, хочу спросить, был ли получен Вами заказ?<br>
Если он доставлен в полной соответствии с описью, прошу Вас пройти по следующей Web-ссылке<br>
___url_yes1___<br><br>

Если же в полученном заказе содержимое не соответсвовало описи, прошу Вас пройти по следующей ссылке<br>
___url_yes2___<br><br>

В противном случае, прошу сообщить нам об этом<br>
___url_no___<br><br>

Если ссылки не открываются - скопируйте их в строку запроса браузера.<br>

В любом случае, вы можете проследить отправление по номеру посылки на сайте Почты России. http://www.russianpost.ru/<br><br>

Заранее огромное спасибо.<br>
С уважением, администратор Клуба Нумизмат Мандра Богдан.</td></tr></table>";

$timenow = mktime(0,0,0, date("m", time()), date("d", time()), date("Y", time()));

$sql = "select `order`.*, user.fio, user.email from `order` 
		left join user on order.user = user.user 
		where SendPost+ReminderAmountDay*86400>='".($timenow-86400)."' 
		and SendPost+ReminderAmountDay*86400<'".($timenow+84000)."' 
		and Reminder=1 and ReminderAmountDay>0;";
$result = $shopcoins_class->getDataSql($sql);

foreach ($result as $rows){
	$SendPostBanderoleNumber = $rows["SendPostBanderoleNumber"];
	$type = $rows["type"];
	$message_user = $message;
	$message_user = str_replace("___fio___", $rows["fio"], $message_user);
	$message_user = str_replace("___number___", $rows["order"], $message_user);
	$url_yes1 = "http://".$SERVER_NAME."/shopcoins/statusorder.php?user=".$rows["user"]."&ReminderKey=".$rows["ReminderKey"]."&Reminder=3";
	$url_yes2 = "http://".$SERVER_NAME."/shopcoins/statusorder.php?user=".$rows["user"]."&ReminderKey=".$rows["ReminderKey"]."&Reminder=3&complected=1";
	$url_no = "http://".$SERVER_NAME."/shopcoins/statusorder.php?user=".$rows["user"]."&ReminderKey=".$rows["ReminderKey"]."&Reminder=4";
	$SendPostBanderoleNumber = "Вы можете проследить отправление по номеру посылки ".$rows["SendPostBanderoleNumber"]." на сайте Почты России. http://www.russianpost.ru/ ";
	
	$message_user = str_replace("___url_yes1___", $url_yes1, $message_user);
	$message_user = str_replace("___url_yes2___", $url_yes2, $message_user);
	$message_user = str_replace("___url_no___", $url_no, $message_user);
	
	$amount_day = round((time() - $rows["SendPost"])/86400);
	if ($amount_day<7)
	{
		$amount_day = "на прошлой неделе";
	} elseif ($amount_day>=7 and $amount_day<14) {
		$amount_day = "2 недели назад";
	} elseif ($amount_day>=14 and $amount_day<21) {
		$amount_day = "3 недели назад";
	} elseif ($amount_day>=21 and $amount_day<28) {
		$amount_day = "4 недели назад";
	} elseif ($amount_day>=28) {
		$amount_day = "месяц назад";
	}
	$message_user = str_replace("___amount_day___", $amount_day, $message_user);
	
				
	//выбираем содержимое заказа
	if ($type=="shopcoins")	{
		// монеты ----------------------------------------------------------------
		$sql_content = "select o.*, c.name, c.price, c.image, c.metal_id, c.year, c.condition_id,c.image_small,c.shopcoins,
		g.name as gname, o.amount as oamount from `orderdetails` as o 
		left join shopcoins as c on o.catalog = c.shopcoins 
		left join `group` as g on c.group=g.group 
		where o.order='".$rows["order"]."' and o.typeorder=1 order by o.orderdetails;";
		$result_content = $shopcoins_class->getDataSql($sql_content);	
		
		$sum = 0;		
		$content = "<table border=0 cellpadding=0 cellspacing=0 width=630 style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>
				<tr style='background-color:#eeeeee;'>";
        $content .= "<td  style='border:1px solid #cccccc;padding:10px;'></td>";
        $content .= "<td style='border:1px solid #cccccc;padding:10px;'>Группа (страна)</td>";
        $content .= "<td  style='border:1px solid #cccccc;padding:10px;'>Наименование</td>";
        $content .= "<td style='border:1px solid #cccccc;padding:10px;'>Год</td>";  			    
        $content .= "<td style='border:1px solid #cccccc;padding:10px;'>Металл</td>"; 
        $content .= "<td  style='border:1px solid #cccccc;padding:10px;'>Цена</td> ";
        $content .= "<td  style='border:1px solid #cccccc;padding:10px;'>Количество</td> ";
        $content .= "</tr>";
    
		foreach ($result_content as $rows_content) {
		    $content .= '<tr><td  style="border:1px solid #cccccc;padding:10px;"><img style="max-width:100px;border:1px solid #cccccc;" src="http://www.numizmatik.ru/shopcoins/images/'.$rows_content["image_small"].'"/></td>';
    	    $content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows_content["gname"].'</td>';
    	    $content .= '<td  style="border:1px solid #cccccc;padding:10px;"><a href="http://www.numizmatik.ru/shopcoins/show.php?catalog='.$rows_content["shopcoins"].'" target=_blank>'.$rows_content["name"].'</a></td>';    	
        	$content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows_content["year"].'</td>';
        	
        	$content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$tpl['metalls'][$rows_content["metal_id"]].'</td>';
            $content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.round($rows_content['price'],2).' руб.</td>';
            $content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows_content["oamount"].' руб.</td>';
            $content .= '</tr>';
			//$content .= $rows_content["name"]." - ".$rows_content["gname"]." - ".$rows_content["price"]." - ".$rows_content["oamount"]."\n";
			$sum += $rows_content["amount"]*$rows_content["price"];
		}
		$content .= "</table>";	
	} elseif ($type == "Book") {
		//книги - ----------------------------------------------------------------
		$sql_content = "select o.*, c.* from `orderdetails` as o left join Book as c on o.catalog = c.BookID 
		where o.order='".$rows["order"]."' and o.typeorder=1 order by o.orderdetails;";
		$result_content = $shopcoins_class->getDataSql($sql_content);
		
		$content = "<table border=0 cellpadding=0 cellspacing=0 width=630 style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>
				<tr style='background-color:#eeeeee;'>";
        $content .= "<td  style='border:1px solid #cccccc;padding:10px;'>Наименование</td>";
        $contente .= "<td  style='border:1px solid #cccccc;padding:10px;'>Цена</td> ";
        $contente .= "<td  style='border:1px solid #cccccc;padding:10px;'>Количество</td> ";
        $content .= "</tr>";
		
		$sum = 0;		
		$content = "";		
		foreach ($result_content as $rows_content) {
		    $content .= '<tr>';
    	    $content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows_content["BookName"].'</td>';
            $content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.round($rows_content['BookPrice'],2).' руб.</td>';
            $content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows_content["amount"].' руб.</td>';
            $content .= '</tr>';
            
			//$content .= $rows_content["BookName"]." - ".$rows_content["BookPrice"]."\n";
			$sum += $rows_content["amount"]*$rows_content["BookPrice"];
		}
		$content .= "</table>";	
	} elseif ($type == "Album") {
		//аксессуары -------------------------------------------------------------
		$sql_content = "select o.*, c.*	 from `orderdetails` as o left join Album as c on o.catalog = c.AlbumID 
		where o.order='".$rows["order"]."' and o.typeorder=1 order by o.orderdetails;";
		$result_content = $shopcoins_class->getDataSql($sql_content);
		
		$content = "<table border=0 cellpadding=0 cellspacing=0 width=630 style='border:1px solid #cccccc;border-collapse:collapse;margin-top:20px;'>
				<tr style='background-color:#eeeeee;'>";
        $content .= "<td  style='border:1px solid #cccccc;padding:10px;'>Наименование</td>";
        $contente .= "<td  style='border:1px solid #cccccc;padding:10px;'>Цена</td> ";
        $contente .= "<td  style='border:1px solid #cccccc;padding:10px;'>Количество</td> ";
        $content .= "</tr>";		
		$sum = 0;		
			
		foreach ($result_content as $rows_content) {
		    $content .= '<tr>';
    	    $content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows_content["AlbumName"].'</td>';
            $content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.round($rows_content['AlbumPrice'],2).' руб.</td>';
            $content .= '<td  style="border:1px solid #cccccc;padding:10px;">'.$rows_content["amount"].' руб.</td>';
            $content .= '</tr>';
			//$content .= $rows_content["AlbumName"]." - ".$rows_content["AlbumPrice"]."\n";
			$sum += $rows_content["amount"]*$rows_content["AlbumPrice"];
		}
		$content .= "</table>";	
	}
	
	//подставляем содержимое заказа
	
	$message_user = str_replace("___content___", $content, $message_user);
	$message_user = str_replace("___sum___", $sum, $message_user);
	$message_user = str_replace("___SendPostBanderoleNumber___", $SendPostBanderoleNumber, $message_user);
	
	$recipient = $rows["email"];
	//$recipient = 'nel1@mail.ru';
	$subject = "Клуб Нумизмат | Заказ № ".$rows["order"];
	
	$mail_class = new mails();	
	$mail_class->subscriptionLetter($recipient,$subject,$message_user);
	$mail_class = new mails();
	$mail_class->subscriptionLetter("administrator@numizmatik.ru",$subject,$message_user);
	

	/*mail($recipient, $subject, $message_user, $headers);
	$recipient = $email_admin;
	mail($recipient, $subject, $message_user, $headers);
	//echo $message_user;	
	//делаем update
	$sql_update = "update `order` set Reminder=2 where `order`='".$rows["order"]."';";*/
	
	$shopcoins_class->updateTableRow('order',array('Reminder'=>2) ,'`order`='.$rows["order"]);	
	//$result_update = mysql_query($sql_update);
	
	if ($rows['user'] && $rows['user'] != 811) {	
		$cron_class->writeMessagePost($rows['user'], $message_user);
	}
}

die('done');

?>