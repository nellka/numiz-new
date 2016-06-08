<?
include(START_PATH."/config.php");

?>
  <div class="center">
  <a class="borderimage primage"  href='<?=$cfg['site_dir']?>shopcoins/<?=$rows["rehref"]?>' title='<?=$cfg['site_dir']?>/shopcoins<?=$rows['namecoins']?>' >
  <?=contentHelper::showImage("images/".$rows["image"],$rows["gname"]." | ".$rows["name"],array('alt'=>contentHelper::getAlt($rows)));?>
  </a>
  </div>
  <a href=<?=$cfg['site_dir']?>shopcoins/index.php?catalog=<?=$rows["shopcoins"]?>&page=show&materialtype=<?=$rows["materialtype"]?>><?=$rows['name']?></a><br>
  <b>Страна:</b> <?=$rows['gname']?><br>
  <b>Год:</b> <?=$rows['year']?$rows['year']:"Без указания года"?><br>
  <b>Металл:</b> <?=$rows['metal']?><br>
   <?if($rows['condition']){?>
  <b>Состояние:</b> <?=$rows['condition']?><br>
  <?}?> <br>
<?

if(isset($rows['buy_status'])) echo contentHelper::render('shopcoins/price/prices',$rows);
if(isset($rows['reserved_status'])) echo contentHelper::render('shopcoins/price/buy_button',$rows);
