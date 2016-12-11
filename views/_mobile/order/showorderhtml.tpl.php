<html>
		<head>
		<title>Отчет</title>
		<meta name="keywords">
		<meta name="description">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<link rel=stylesheet type=text/css href='<?=$cfg['site_dir']?>css/main.css'>
		<link rel="SHORTCUT ICON" href="<?=$cfg['site_dir']?>favicon.ico"> 
		</head>
		<body bgcolor=white bottommargin=10 leftmargin=10 rightmargin=10 marginheight=10 topmargin=10 marginwidth=10>
		
		<h5>Номер заказа <?=$order?></h5>
		<table>
		<tr><td class=tboard bgcolor=silver colspan=2>
		<b>Информация о покупателе</b> [<a href=# onclick="window.open('shopcoinsuseredit.php?order=<?=$order?>','shopcoinsuseredit<?=$order?>','width=400,height=300, scrollbars=1');">Редактировать</a>]
		</td></tr>		
		<tr>
    		<td class=tboard>ФИО:</td>
    		<td class=tboard>&nbsp;<?=$tpl['order']["fio"]?></td>
		</tr>
		<tr>
		  <td class=tboard>Город:</td>
		  <td class=tboard>&nbsp<?=$tpl['order']["fio"]?></td>
		</tr>
		<tr>
		  <td class=tboard>Контактный телефон:</td>
		  <td class=tboard><?=$tpl['order']["phone"]?></td>
		</tr>
		<tr>
		  <td class=tboard>E-mail:</td>
		  <td class=tboard>&nbsp;<a href=mailto:<?=$tpl['order']["email"]?>><?=$tpl['order']["email"]?></a></td>
		</tr>
		<?if ($tpl['order']["adress"]){?>
		<tr>
			 <td class=tboard>Адрес доставки:</td>
			 <td class=tboard><?=str_replace("\n", "<br>", $tpl['order']["adress"])?></td>
		</tr>
		<?}?>
		<tr>
		  <td class=tboard>Тип оплаты:</td>
		  <td class=tboard><?=$SumName[$tpl['order']["payment"]]?></td>
		</tr>
		<?if ($tpl['order']["payment"] == 2) {
			if ($tpl['order']["MetroMeeting"])
				echo "<tr><td class=tboard>Метро:</td><td class=tboard>".$MetroArray[$tpl['order']["MetroMeeting"]]."</td></tr>";
			if ($tpl['order']["DateMeeting"])
				echo "<tr><td class=tboard>Время встречи:</td><td class=tboard>".date("Y-m-d H:i", $tpl['order']["DateMeeting"])."</td></tr>";
	   }
	   if ($tpl['order']["OtherInformation"]){?>
			<tr><td class=tboard>Другая информация:</td>
			<td class=tboard><?=str_replace("\n", "<br>", $tpl['order']["OtherInformation"])?></td></tr>
	   <?}?>
			
		</table>
		<?
		
		//монеты		
		if ($type =="shopcoins"){?>		
			<h5>Содержимое заказа:</h5>
			<table width="1000" cellspacing="0" cellpadding="0" style="border: 1px solid #cccccc; border-collapse: collapse;">
<thead>
<tr>
<td class="tboardtop" nowrap="">Фото товара</td>
<td class="tboardtop" width="200">Наименование</td>
<td class="tboardtop">Группа(страна)</td>
<td class="tboardtop">Год</td>
<td class="tboardtop">Номер</td>
<td class="tboardtop">Состояние</td>
<td class="tboardtop">Цена</td>
<td class="tboardtop">Количество</td>
<td class="tboardtop">Описание</td>
</tr>
</thead>
<tbody>
			<?	
			foreach ($tpl['order_results'] as $rows){	
				if ($rows['title']){?>				
					<tr><td class="h-cat" colspan="9"><b><?=$rows['title']?></b></td></tr>									
				<?}?>
				<tr>				
				<td class=tboard width=220>
				    <?=contentHelper::showImage('images/'.$rows["image"],$rows["name"])?>
				</td>
				<td class=tboard><?=$rows["name"]?>	</td>	
				<td class=tboard><?=$rows["gname"]?></td>	
				<td class=tboard><?=$rows["year"]?></td>	
				<td class=tboard><?=$rows["number"]?></td>
				<td class=tboard><?=$rows["condition"];?></td>
				<td class=tboard><?=round($rows["price"],2)?> руб.</td>
				<td class=tboard><font color=blue><?=($rows["amount"]?$rows["amount"]:"1")?></font></td>
				<td class=tboard><?=str_replace("\n","<br>",$rows["details"]);?></td>
				<?					
			}
			
			$what = substr($what, 0, -2);
			
			if ($discountcoupon>0) {?>			
				<tr>
				    <td colspan=2 class=tboard><b><?=$MaterialTypeArray[$oldmaterialtype]?>: <?=$k?></b></td>
				    <td class=tboard align=right><b>Сумма заказа:</b></td><td class=tboard><?=$sum?> руб.</td>
				</tr>
				<tr bgcolor=#ddaaee>
				    <td colspan=2 class=tboard><b>&nbsp;</b></td>
				    <td class=tboard align=right><b>Скидка по купону(ам):</b></td>
				    <td class=tboard><?=$discountcoupon?> руб.</td>
				</tr>
				<tr>
				    <td colspan=2 class=tboard><b>&nbsp;</b></td>
				    <td class=tboard align=right><b>Итого:</b></td>
				    <td class=tboard><?=(($sum-$discountcoupon<0)?0:$sum-$discountcoupon)?> руб.</td>
				</tr>
			<?}	else {?>
				<tr>
				    <td colspan=9 class=tboard><b>Итого:&nbsp;&nbsp;</b><?=$tpl['order']['sum']?> руб.</td>
			     </tr>
			<?}?>
			</tbody>
			</table>
			
		<?} elseif ($type=="Book") {
			$BookImagesFolder = $in."book/images";
			//сначала о пользователе
			
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
		}?>
		
		
<center>[<img src=http://numizmatik.ru/images/printer.gif> <a href=# onclick="window.print();">Распечатать</a>]</center>
</body>
</html>
