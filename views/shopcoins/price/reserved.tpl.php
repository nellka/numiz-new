<?
$status = $rows['reserved_status'];
if (in_array($status,array(1,2,3,4,5,6,7,8,9))) {?>
    <div class="reserv">
<?}

if ($status ==1) {?>
Бронь до <?=date("H:i", $rows["reserve"]+model_shopcoins::$reservetime)?>
<?} elseif ($status ==2) {?>
	Бронь до <?=date("H:i", $rows["reserve"]+model_shopcoins::$reservetime)?><br>
	<div id=mysubscribecatalog<?=$rows["shopcoins"]?>>
	   <a href='#coin<?=$rows["shopcoins"]?>' onclick="WaitSubscribeCatalog(<?=$rows["shopcoins"]?>);" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>
<?} elseif ($status ==3) {?>
	На монету вы оставили заявку через <a href=../catalognew target=_blank style="font-size:9px;">каталог</a>. Она доступна вам для покупки.</font>
<?} elseif ($status ==4) {	?>
	На монету <?=$rows["gname"]?> <?=$rows["name"]?> была оставлена заявка через <a href=../catalognew target=_blank style="font-size:9px;" title='Каталог монет России, Германии, США и других стран'>каталог</a>. 
	Монету до <?=date("H:i",$rows['timereserved'])?> могут купить только клиенты, оставившие заявку.		
<?} elseif ($status ==5) {?>	
	На монету <?=$rows["gname"]?> <?=$rows["name"]?> была оставлена заявка через <a href=../catalognew target=_blank style="font-size:9px;" title='Каталог монет России, Германии, США и других стран'>каталог</a>. Монету до <?=date("H:i",$rows['timereserved'])?> могут купить только клиенты, оставившие заявку.</font>
	<br><div id=mysubscribecatalog<?=$rows["shopcoins"]?>><a href='#coin<?=$rows["shopcoins"]?>' onclick="WaitSubscribeCatalog(<?=$rows["shopcoins"]?>);" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>
<?} elseif ($status ==6) {?>
	Монета <?=$rows["gname"]?> <?=$rows["name"]?> была забронирована. Монету до <?=date("H:i",$rows['doubletimereserve'])?> может купить только клиент, поставивший бронь.	
<?} elseif ($status ==7) {?>
	Монета <?=$rows["gname"]?> <?=$rows["name"]?> была забронирована. Монету до <?=date("H:i",$rows['doubletimereserve'])?> может купить только клиент, поставивший бронь.<br>
	<div id=mysubscribecatalog<?=$rows["shopcoins"]?>>
	   <a href='#coin<?=$rows["shopcoins"]?>' onclick='WaitSubscribeCatalog(<?=$rows["shopcoins"]?>);' title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a>
	</div>
<?} elseif ($status ==8) {?>
	Вы в очереди на монету до <?=date("H:i", $rows["doubletimereserve"])?>
<?} elseif ($status ==9) {?>
	Вы можете купить монету. Ваша бронь до <?=date("H:i", $rows["doubletimereserve"])?>
<?} 
if (in_array($status,array(1,2,3,4,5,6,7,8,9))) {?>
    </div>
<?}
