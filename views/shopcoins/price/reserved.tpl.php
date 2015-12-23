<?
$status = $rows['reserved_status'];

if ($status ==1) {
	echo "Бронь до ".date("H:i", $rows["reserve"]+model_shopcoins::$reservetime);
} elseif ($status ==2) {
	echo "Бронь до ".date("H:i", $rows["reserve"]+model_shopcoins::$reservetime)."<br>";
	echo "<div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
} elseif ($status ==3) {
	echo 'На монету вы оставили заявку через <a href=../catalognew target=_blank style="font-size:9px;">каталог</a>. Она доступна вам для покупки.</font>';
} elseif ($status ==4) {	
	echo 'На монету '.$rows["gname"].' '.$rows["name"].' была оставлена заявка через <a href=../catalognew target=_blank style="font-size:9px;" title=\'Каталог монет России, Германии, США и других стран\'>каталог</a>. Монету до '. date("H:i",$rows['timereserved']) .' могут купить только клиенты, оставившие заявку.';
			
} elseif ($status ==5) {	
	echo 'На монету '.$rows["gname"].' '.$rows["name"].' была оставлена заявка через <a href=../catalognew target=_blank style="font-size:9px;" title=\'Каталог монет России, Германии, США и других стран\'>каталог</a>. Монету до '. date("H:i",$rows['timereserved']) .' могут купить только клиенты, оставившие заявку.</font>';
	echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
	

} elseif ($status ==6) {
	echo 'Монета '.$rows["gname"].' '.$rows["name"].' была забронирована. Монету до '. date("H:i",$rows['doubletimereserve']) .' может купить только клиент, поставивший бронь.';
	
} elseif ($status ==7) {
	echo 'Монета '.$rows["gname"].' '.$rows["name"].' была забронирована. Монету до '. date("H:i",$rows['doubletimereserve']) .' может купить только клиент, поставивший бронь.';
	echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick='javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');' title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
} elseif ($status ==8) {
	echo "Вы в очереди на монету до ".date("H:i", $rows["doubletimereserve"]);
} elseif ($status ==9) {
	echo "Вы можете купить монету. Ваша бронь до ".date("H:i", $rows["doubletimereserve"]);
} 

?>
