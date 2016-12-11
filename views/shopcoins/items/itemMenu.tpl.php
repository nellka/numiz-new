<?php
//новинки
include(START_PATH."/config.php");
if ($rows["novelty"]){?>
    <div class="new">Новинка</div>
<?} elseif ($rows["dateinsert"]>time()-86400*180 ){
?>
	<div class="new_red">NEW <?=date('m-d',$rows["dateinsert"])?></div>
<?php 
 }
?>
<?
if($rows["materialtype"]==3){?>
		<a class="borderimage"  href='<?=$cfg['site_dir']?>shopcoins/<?=$rows["rehref"]?>' title='<?=$cfg['site_dir']?>/shopcoins<?=$rows['namecoins']?>' >
			<?=contentHelper::showImage('images/'.$rows["image"],$rows['namecoins'],array('alt'=>contentHelper::getAlt($rows)))?>
		</a>

<? } elseif ($rows["materialtype"]==5){?>
	<a class="borderimage" href='<?=$cfg['site_dir']?>shopcoins/<?=$rows["rehref"]?>' title='Подробнее о книге <?=$rows["name"]?>'>
		<?=contentHelper::showImage('images/'.$rows["image"],$rows["name"],array('alt'=>contentHelper::getAlt($rows)))?>
	</a>	
<?}	else {

	$title = contentHelper::setHrefTitle($rows["name"],$rows["materialtype"],$rows['gname']).' - подробная информация';?>
		<a href='<?=$cfg['site_dir']?>shopcoins/<?=$rows['rehref']?>' title='<?=$title?>' class="borderimage">
			<?=contentHelper::showImage('images/'.$rows["image"],'Подробная информация о '.contentHelper::setWordAbout($rows["materialtype"])." ".$rows["gname"]." ".$rows["name"],array('alt'=>contentHelper::getAlt($rows)))?>			
		</a>
	
<?}?>
	
<div class='info'>
	<a title='<?=contentHelper::setHrefTitle($rows["name"],$rows["materialtype"],$rows["gname"])?>'><span itemprop="name"><?=$rows['name']?></span></a>
	<meta itemprop="description" content="<?=contentHelper::setHrefTitle($rows["name"],$rows["materialtype"],$rows["gname"])?>">
	<?
	if($rows['year'] == 1990 && $materialtype==12) $rows['year'] = '1990 ЛМД';
	if($rows['year'] == 1991 && $materialtype==12) $rows['year'] = '1991 ЛМД';
	if($rows['year'] == 1992 && $materialtype==12) $rows['year'] = '1991 ММД';
	
	if ($rows["gname"]){?>
	<?=in_array($rows["materialtype"],array(9,3,5))?"Группа":"Страна"?>: <?=$rows["gname"]?>
	<br>
	<?}
	if($rows["year"]){
	?>
	Год:&nbsp;<strong><?=$rows["year"]?></strong><br>
	<?}
	if($rows["metal"]){
	?>
	Металл: <strong><?=$rows["metal"]?></strong><br>
	<?}
	if($rows["condition"]){?>
	Состояние: <strong><span class='blue'><?=$rows["condition"]?></span></strong>
	<?}?>

</div>

<?  echo contentHelper::render('shopcoins/price/prices',$rows);?>

<?//echo contentHelper::render('shopcoins/price/buy_button',$rows);?>
<?
	//echo contentHelper::render('shopcoins/price/reserved',$rows);
	?>
<?/*
if(($rows['buy_status']==7||$rows['buy_status']==6)&&($minpriceoneclick<=$rows['price'])) {
	echo contentHelper::render('shopcoins/price/oneclick',$rows);
}*/?>

<? /*
<div id=subinfo class="subinfo">
Название: <strong><?=$rows["name"]?></strong><br>
Номер: <strong><?=$rows["number"]?></strong><br>
<?
echo ($rows["width"]&&$rows["height"]?"<br>Приблизительный размер: <strong>".$rows["width"]."*".$rows["height"]." мм.</strong>":"")."
".($rows["weight"]>0?"<br>Вес: <strong>".$rows["weight"]." гр.</strong>":"")."
".($rows["series"]&&$group?"<br>Серия монет: <a href=$script?series=".$rows["series"]."&group=".$group."&materialtype=".$materialtype.">".$series_name[$rows["series"]]."</a>":"")."
".($rows["materialtype"]==8&&$materialtype==1&&!$mycoins?"<br><font color=red>МОНЕТА С РАЗДЕЛА МЕЛОЧЬ, см. условия покупки в разделе</font>":"");

if($rows['materialtype']==5){
	if (trim($rows["accessoryProducer"])) echo "<br><b>ISBN: </b>".$rows["accessoryProducer"];
	if ($rows["accessoryColors"])  echo "<br><b>Год выпуска: </b>".$rows["accessoryColors"];
	if ($rows["accessorySize"])  echo "<br><b>Количество страниц: <font color=blue>".$rows["accessorySize"]."</font></b>";
} else {
	if($rows["accessoryProducer"]) echo "<br>Производитель:<strong> ".$rows["accessoryProducer"]."</strong>";
	if($rows["accessoryColors"]) echo "<br>Цвета:<strong> ".$rows["accessoryColors"]."</strong>";
	if($rows["accessorySize"]) echo "<br>Размеры:<strong> ".$rows["accessorySize"]."</strong>";
}

		
//echo "<br>Количество:  <strong>".$rows["amountall"]."</strong>";		
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


</div>	*/
?>