<? 
if($rows["materialtype"]==3){?>
<a href='<?=$rows["rehref"]?>' title='<?=$rows['namecoins']?>'>
	<?=contentHelper::showImage('images/'.$rows["image"],$rows['namecoins'])?>
</a>
<a name=coin<?=$rows["shopcoins"]?> title='<?=$rows["name"]?>'></a><strong><?=$rows['namecoins']?></strong>
<? } elseif ($rows["materialtype"]==5){?>

<a href='<?=$rows["rehref"]?>' title='Подробнее о книге <?=$rows["name"]?>'>
	<?=contentHelper::showImage('images/'.$rows["image"],$rows["name"])?>
</a>
<a name=coin<?=$rows["shopcoins"]?> title='<?=$rows["name"]?>'></a><strong><?=$rows["name"]?></strong>
<?}	else {
	$title = contentHelper::setHrefTitle($rows["name"],$rows["materialtype"],$rows['gname']).' - подробная информация';?>
	<a href='<?=$rows['rehref']?>' title='<?=$title?>'>
		<?=contentHelper::showImage('images/'.$rows["image"],'Подробная информация о '.contentHelper::setWordAbout($rows["materialtype"])." ".$rows["gname"]." ".$rows["name"])?>			
	</a>	
	<a name=coin<?=$rows["shopcoins"]?> title='<?=contentHelper::setHrefTitle($rows["name"],$rows["materialtype"],$rows["gname"])?>'></a>
	<strong><?=$rows['namecoins']?></strong> 
<?}?>

<div id='info'>	
	<?
	if($rows['year'] == 1990 && $materialtype==12) $rows['year'] = '1990 ЛМД';
	if($rows['year'] == 1991 && $materialtype==12) $rows['year'] = '1991 ЛМД';
	if($rows['year'] == 1992 && $materialtype==12) $rows['year'] = '1991 ММД';
	
	if ($rows["gname"]){?>
	<?=in_array($rows["materialtype"],array(9,3,5))?"Группа":"Страна"?>: 
	<a href=<?=$cfg['site_dir']?>/shopcoins?group=<?=$rows['group']?>&materialtype=<?=$rows["materialtype"]?> title='Посмотреть <?=contentHelper::setWordThat($rows["materialtype"])?> <?=$rows["gname"]?>'>
	<strong><font color=blue><?=$rows["gname"]?></font></strong>
	</a><br>
	<?}?>
	<?= ($rows["year"]?"Год: <strong>".$rows["year"]."</strong><br>":"")?>
	<?= (trim($rows["metal"])?"Металл: <strong>".$rows["metal"]."</strong><br>":"")?>
	<?=(trim($rows["condition"])?"Состояние: <strong><font color=blue>".$rows["condition"]."</font></strong>":"")?>

</div>

<div id=prices>
<?
	$price_text_old = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"Старая цена":"Старая стоимость";
	$price_text_new = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"Новая цена":"Новая стоимость";
	$price_text = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"Цена":"Стоимость";
			
				
	if($rows["oldprice"]>0){?>
	    <?=$price_text_old?>: <strong><s><?=round($rows["oldprice"],2)?> руб.</s></strong><br>
	    <?=$price_text_new?>: <strong><font color=red><?=($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")?></font></strong>
	 <?}?>
        <?=$price_text?>: <strong><font color=red><?=($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")?></font></strong>
        <?if($rows["clientprice"]>0){?>
          <br><a href=# onclick='javascript:alert("Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!' >
             <?=$price_text?> <b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red><?=round($rows["clientprice"],2)?> руб.</font></strong></a>
        <?}

if ($rows['price1'] && $rows['amount1']) {

	$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
	<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>Оптовая цена:
	<td class=tboard>Кол-во<td class=tboard>".$rows['amount1']; "</tr>";
	$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>Цена<td class=tboard>".$rows['price1'];
	if ($rows['price2'] && $rows['amount2']) {
	
		$tmpbody1 .= "<td class=tboard>".$rows['amount2'];
		$tmpbody2 .= "<td class=tboard>".$rows['price2'];
	}
	if ($rows['price3'] && $rows['amount3']) {
	
		$tmpbody1 .= "<td class=tboard>".$rows['amount3'];
		$tmpbody2 .= "<td class=tboard>".$rows['price3'];
	}
	if ($rows['price4'] && $rows['amount4']) {
	
		$tmpbody1 .= "<td class=tboard>".$rows['amount4'];
		$tmpbody2 .= "<td class=tboard>".$rows['price4'];
	}
	if ($rows['price5'] && $rows['amount5']) {
	
		$tmpbody1 .= "<td class=tboard>".$rows['amount5'];
		$tmpbody2 .= "<td class=tboard>".$rows['price5'];
	}
	echo $tmpbody1."</tr>".$tmpbody2."</tr></table>"; 
}?>
</div>

<?
$Accessory_type=($rows["materialtype"]==3||$rows["materialtype"]==5)?'_3':"";
//кнопки в корзину, резервирует и тд
if($rows['buy_status']==2){?>
	<img src='<?=$cfg['site_dir']?>images/corz7.gif' alt='Уже в вашей корзине'>
<?} else if($rows['buy_status']==3){?>
	<img src='<?=$cfg['site_dir']?>images/corz6.gif' alt='Покупает другой посетитель <?=contentHelper::setWordWhat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>		
<?} elseif ($rows['buy_status']==4){?>
	<img src='<?=$cfg['site_dir']?>images/corz6.gif' alt='Покупает другой посетитель <?=contentHelper::setWordWhat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>
	<img src='<?=$cfg['site_dir']?>images/corz77.gif' alt='Вы в очереди на покупку <?=contentHelper::setWordThat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>		
<?} elseif ($rows['buy_status']==5){?>
	<img src='<?=$cfg['site_dir']?>images/corz6.gif' alt='Покупает другой посетитель <?=contentHelper::setWordWhat($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>
	<div id=bascetshop<?=$rows["shopcoins"]?>>
	  <a href='#coin<?=$rows["shopcoins"]?>' onclick="javascript:AddNext('<?=$rows["shopcoins"]?>','1');" rel="nofollow" title='Стать в очередь на <?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>
	      <img src='<?=$cfg['site_dir']?>images/corz11.gif' alt='<?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["gname"]?> <?=$rows["name"]?>'>
	  </a>
	</div>
<?} elseif ($rows['buy_status']==8){
	
	?>
    <input type=text name=amount<?=$rows["shopcoins"]?> id=amount<?=$rows["shopcoins"]?> size=4 value='<?=$ourcoinsorderamount[$rows["shopcoins"]]?>'> 
	  <a href='#coin<?=$rows["shopcoins"]?>' onclick='javascript:AddAccessory<?=$Accessory_type?>'(<?=$rows["shopcoins"]?>,<?=$rows["materialtype"]?>)' title='<?=$rows["name"]?>'>
	  <div id=bascetshopcoins<?=$rows["shopcoins"]?>><img src=<?=$cfg['site_dir']?>images/corz7.gif alt='Уже в корзине'></div>
	 </a>
<?} else if ($rows['buy_status']==6){?>			
	<div id=bascetshopcoins<?=$rows["shopcoins"]?>>					
    	<input type=text name=amount<?=$rows["shopcoins"]?> id=amount<?=$rows["shopcoins"]?> size=4 value='1' style="float:left"> 
		<a class="button25" href='#coin<?=$rows["shopcoins"]?>' onclick='javascript:AddAccessory<?=$Accessory_type?>(<?=$rows["shopcoins"]?>)' title='Положить в корзину <?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["name"]?>'>Купить</a>
	</div>	
<?} elseif ($rows['buy_status']==7) {?>
    <div id=bascetshopcoins<?=$rows["shopcoins"]?>>
   		<a class="button25" href='#coin<?=$rows["shopcoins"]?>' onclick='javascript:AddAccessory<?=$Accessory_type?>(<?=$rows["shopcoins"]?>)' title='Положить в корзину <?=contentHelper::setWordOn($rows["materialtype"])?> <?=$rows["name"]?>'>Купить</a>
    </div>
	</a>
	  
<?}

if(($rows['buy_status']==7||$rows['buy_status']==6)&&($minpriceoneclick<=$rows['price'])) {
	include('oneclick.tpl.php');
}?>

<?
if ($rows["reservedForSomeUser"]) {
	echo "<br><font color=gray size=-2>Бронь до ".date("H:i", $rows["reserve"]+$reservetime)."</font>";
	
	if (time() - (int) $rows["reserve"] >= $reservetime  || $rows["reserveorder"] != $shopcoinsorder  && $rows['relationcatalog']>0 && $tpl['user']['user_id']>0)
		echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
} elseif($rows["reservedForSomeGroup"]) {
	if($rows["isInRerservedGroup"]) {
		echo '<br><font color=#ff0000 size=-2>На монету вы оставили заявку через <a href=../catalognew target=_blank style="font-size:9px;">каталог</a>. Она доступна вам для покупки.</font>';
	}	else {
		echo '<br><font color=gray size=-2>На монету '.$rows["gname"].' '.$rows["name"].' была оставлена заявка через <a href=../catalognew target=_blank style="font-size:9px;" title=\'Каталог монет России, Германии, США и других стран\'>каталог</a>. Монету до '. date("H:i",$rows['timereserved']) .' могут купить только клиенты, оставившие заявку.</font>';
		if ( $rows['relationcatalog']>0 && $tpl['user']['user_id']>0)
			echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
	}			
} elseif ($rows['doubletimereserve'] > time() && $tpl['user']['user_id'] != $rows['userreserve']) {
	echo '<br><font color=gray size=-2>Монета '.$rows["gname"].' '.$rows["name"].' была забронирована. Монету до '. date("H:i",$rows['doubletimereserve']) .' может купить только клиент, поставивший бронь.</font>';
	if ( $rows['relationcatalog']>0 && $tpl['user']['user_id']>0)
			echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick='javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');' title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
}
if ($rows['doubletimereserve'] > time() && $tpl['user']['user_id'] == $rows['userreserve'] && $tpl['user']['user_id']>0)
	if ( $rows['reservedForSomeUser'] || (!$tpl['user']['user_id'] && $rows['reservedForSomeGroup']) || (false === $rows['isInRerservedGroup']) ){ echo "<font color=red size=-2>Вы в очереди на монету до ".date("H:i", $rows["doubletimereserve"])."</font>";
} else echo "<br><font color=red size=-2>Вы можете купить монету. Ваша бронь до ".date("H:i", $rows["doubletimereserve"])."</font>";
?>
	
<div id=subinfo>
Название: <strong><?=$rows["name"]?></strong><br>
Номер: <strong><?=$rows["number"]?></strong><br>
<?
echo ($rows["width"]&&$rows["height"]?"<br>Приблизительный размер: <strong>".$rows["width"]."*".$rows["height"]." мм.</strong>":"")."
".($rows["weight"]>0?"<br>Вес: <strong>".$rows["weight"]." гр.</strong>":"")."
".($rows["series"]&&$group?"<br>Серия монет: <a href=$script?series=".$rows["series"]."&group=".$group."&materialtype=".$materialtype.">".$series_name[$rows["series"]]."</a>":"")."
".($rows["materialtype"]==8&&$materialtype==1&&!$mycoins?"<br><font color=red>МОНЕТА С РАЗДЕЛА МЕЛОЧЬ, см. условия покупки в разделе</font>":"")."
".($rows["accessoryProducer"]?"<br>Производитель:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
".($rows["accessoryColors"]?"<br>Цвета:<strong> ".$rows["accessoryColors"]."</strong>":"")."
".($rows["accessorySize"]?"<br>Размеры:<strong> ".$rows["accessorySize"]."</strong>":"");

		
				
echo "<br>Количество:  <strong>".$rows["amountall"]."</strong>";		
if (sizeof($rows['shopcoinstheme']))
	echo "<br>Тематика: <strong>".implode(", ", $rows['shopcoinstheme'])."</strong>";

if (trim($rows["details"]))
{
	$text = substr($rows["details"], 0, 250);
	$text = strip_tags($text);
	$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
	$text = str_replace($rows['name'],"<strong>".$rows['name']."</strong>",$text);
	$text = str_replace($rows['gname'],"<strong>".$rows['gname']."</strong>",$text);
	$text = str_replace(" монет ","<strong> монет </strong>",$text);
	$text = str_replace(" монета ","<strong> монета </strong>",$text);
	$text = str_replace(" монеты ","<strong> монеты </strong>",$text);
	$text = str_replace(" монетам ","<strong> монетам </strong>",$text);
	echo "<br>Описание: ".str_replace("\n","<br>",$text)."";
}

if ($rows["dateinsert"]>time()-86400*180 && !$mycoins){
	echo "<br>Добавлено: <strong>".($rows["dateinsert"]>time()-86400*14?"<font color=red>NEW</font> ".date("Y-m-d", $rows["dateinsert"]):date("Y-m-d", $rows["dateinsert"]))."</strong>";
}?>
</div>		
<?
if($rows['tmpsmallimage']){?>
<!-- блок подобные-->	
<div id='other'>
    <a href='<?=$rows['rehrefdubdle']?>' title='Посмотреть список подобных <?=contentHelper::setWordWhat($rows["materialtype"])?> - <?=$rows["gname"]?> <?=$rows["name"]?>'>
     <? foreach ($rows['tmpsmallimage'] as $img){
     	echo $img;
     }?>

    <img src='<?=$cfg['site_dir']?>images/corz13.gif' alt='Посмотреть список подобных <?=contentHelper::setWordWhat($rows["materialtype"])?> - <?=$rows["gname"]?> <?=$rows["name"]?>'></a>
</div>

<!-- конец блок подобные-->
<?}
			
$rand = rand(1,2);

if ($mycoins) {
	
	include('mycoins.tpl.php');
}
?>