<?

include(START_PATH."/config.php");
if ($rows["novelty"]){?>
    <div class="new">Новинка</div>
<?} elseif ($rows["dateinsert"]>time()-86400*180 && !$mycoins){
?>
	<div class="new_red">NEW <?=date('m-d',$rows["dateinsert"])?></div>
<?php 
 }

?>
  <div class="center">
  <a class="borderimage primage"  href='<?=$cfg['site_dir']?>shopcoins/<?=$rows["rehref"]?>' title='<?=$cfg['site_dir']?>/shopcoins<?=$rows['namecoins']?>' >
  <?=contentHelper::showImage("images/".$rows["image"],$rows["gname"]." | ".$rows["name"],array('alt'=>contentHelper::getAlt($rows)));?>
  </a>
  <? if(!isset($rows['is_mobile'])||!$rows['is_mobile']) {?>
  <a onclick="showWin('<?=$cfg['site_dir']?>shopcoins/?module=shopcoins&task=showsmall&catalog=<?=$rows["shopcoins"]?>&ajax=1',1100);return false;" href='#' class="qwk">Быстрый просмотр</a>
  <?}?>
  </div>
<div class="info-block">
  <a href="<?=$cfg['site_dir']?>shopcoins/<?=$rows["rehref"]?>"><?=$rows['name']?></a><br>
  <span clas="country-c"><?=$rows['gname']?></span><br>
  <b>Год:</b> <?=$rows['year']?$rows['year']:"Без указания года"?><br>
  <b>Металл:</b> <?=$rows['metal']?><br>
   <?if($rows['condition']){?>
  <b>Состояние:</b> <?=$rows['condition']?><br>
  <?}?>
</div>
<?
$rows['for_mini'] = true;
if(isset($rows['buy_status'])) echo contentHelper::render('shopcoins/price/prices',$rows);
if(isset($rows['reserved_status'])) echo contentHelper::render('shopcoins/price/buy_button',$rows);
if(($rows['buy_status']==7||$rows['buy_status']==6)&&($minpriceoneclick<=$rows['price'])) {
    $rows['is_mini'] = true;
	echo contentHelper::render('shopcoins/price/oneclick',$rows);
}
