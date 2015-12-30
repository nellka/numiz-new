<? 
if($rows["materialtype"]==3){?>
<a href='<?=$cfg['site_dir']?>shopcoins/<?=$rows["rehref"]?>' title='<?=$cfg['site_dir']?>/shopcoins<?=$rows['namecoins']?>' >
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
		<center>
		<a href='<?=$cfg['site_dir']?>shopcoins/<?=$rows['rehref']?>' title='<?=$title?>' class="borderimage">
			<?=contentHelper::showImage('images/'.$rows["image"],'Подробная информация о '.contentHelper::setWordAbout($rows["materialtype"])." ".$rows["gname"]." ".$rows["name"])?>			
		</a>
		</center>		
		<div class="coinname">
		<a name=coin<?=$rows["shopcoins"]?> title='<?=contentHelper::setHrefTitle($rows["name"],$rows["materialtype"],$rows["gname"])?>'></a>
		<strong><?=$rows['namecoins']?></strong> 
	</div>
<?}?>

<div id='info' class="info_ext">	
	<?
	if($rows['year'] == 1990 && $materialtype==12) $rows['year'] = '1990 ЛМД';
	if($rows['year'] == 1991 && $materialtype==12) $rows['year'] = '1991 ЛМД';
	if($rows['year'] == 1992 && $materialtype==12) $rows['year'] = '1991 ММД';
	
	if ($rows["gname"]){?>
	<?=in_array($rows["materialtype"],array(9,3,5))?"Группа":"Страна"?>: 
	<a href=<?=$cfg['site_dir']?>shopcoins?group=<?=$rows['group']?>&materialtype=<?=$rows["materialtype"]?> title='Посмотреть <?=contentHelper::setWordThat($rows["materialtype"])?> <?=$rows["gname"]?>'>
	<strong><font color=blue><?=$rows["gname"]?></font></strong>
	</a><br>
	<?}?>
	<?= ($rows["year"]?"Год: <strong>".$rows["year"]."</strong><br>":"")?>
	<?= (trim($rows["metal"])?"Металл: <strong>".$rows["metal"]."</strong><br>":"")?>
	<?=(trim($rows["condition"])?"Состояние: <strong><font color=blue>".$rows["condition"]."</font></strong>":"")?>

</div>

<? echo contentHelper::render('shopcoins/price/prices',$rows);?>
<?echo contentHelper::render('shopcoins/price/buy_button',$rows);?>
<?
if(($rows['buy_status']==7||$rows['buy_status']==6)&&($minpriceoneclick<=$rows['price'])) {
	//echo contentHelper::render('shopcoins/price/oneclick',$rows);
}?>
<div class="reserv">
	<?
	echo contentHelper::render('shopcoins/price/reserved',$rows);
	?>
</div>
<div class="stars">
	<?php
	echo contentHelper::render('shopcoins/price/markitem',$rows['mark']);	
	?>
</div>
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
<div id='other' class="other">
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