<?
if ($rows["novelty"]){?>
    <div class="new">Новинка</div>
<?} elseif ($rows["dateinsert"]>time()-86400*180){?>
	<div class="new_red">NEW</div>
<?}?>
  <center><?=contentHelper::showImage("images/".$rows["image"],$rows["gname"]." | ".$rows["name"]);?></center><br>
  <a href=index.php?catalog=<?=$rows["shopcoins"]?>&page=show&materialtype=<?=$rows["materialtype"]?>><?=$rows['name']?></a><br>
  <b>Страна:</b> <?=$rows['gname']?><br>
  <b>Год:</b> <?=$rows['year']?$rows['year']:"Без указания года"?><br>
  <b>Металл:</b> <?=$rows['metal']?><br>
  <?if($rows['condition']){?>
  <b>Состояние:</b> <?=$rows['condition']?><br>
  <?}?> 
 
<?

if(isset($row['buy_status'])) echo contentHelper::render('shopcoins/price/prices',$rows);
if(isset($rows['reserved_status'])) echo contentHelper::render('shopcoins/price/buy_button',$rows);

echo contentHelper::render('shopcoins/price/markitem',$rows['mark']);


?>