  <center><?=contentHelper::showImage("images/".$rows["image"],$rows["gname"]." | ".$rows["name"]);?></center><br>
  <a href=index.php?catalog=<?=$rows["shopcoins"]?>&page=show&materialtype=<?=$rows["materialtype"]?>><?=$rows['name']?></a><br>
  <b>Страна:</b> <?=$rows['gname']?><br>
  <b>Год:</b> <?=$rows['year']?><br>
  <b>Металл:</b> <?=$rows['metal']?><br>
  <b>Состояние:</b> <?=$rows['condition']?><br><br>
  <? if ($rows["dateinsert"]>time()-86400*180){
	echo "<br>Добавлено: <strong>".($rows["dateinsert"]>time()-86400*14?"<font color=red>NEW</font> ".date("Y-m-d", $rows["dateinsert"]):date("Y-m-d", $rows["dateinsert"]))."</strong>";
}?> 
<?

if(isset($row['buy_status'])) echo contentHelper::render('shopcoins/price/prices',$rows);
if(isset($rows['reserved_status'])) echo contentHelper::render('shopcoins/price/buy_button',$rows);

echo contentHelper::render('shopcoins/price/markitem',$rows['mark']);


?>