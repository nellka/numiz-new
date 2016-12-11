<?
$is_new = "";
if ($rows["novelty"]){
    $is_new = '<div class="new">Новинка</div>';
} elseif ($rows["dateinsert"]>time()-86400*180 && !$mycoins){
	$is_new = '<div class="new_red">NEW '.date('m-d',$rows["dateinsert"]).'</div>'; 
}?>

<div class="item_nabor_image">
    <?=$is_new?>
<? if($rows["materialtype"]==3){?>

	<a href='<?=$cfg['site_dir']?>/shopcoins<?=$rows["rehref"]?>' title='<?=$rows['namecoins']?>'  class="primage" >
		<img src='<?=$cfg['site_dir']?>images/<?=$rows["image"]?>' alt='<?=$rows['namecoins']?>'>
	</a>	
   
<? } elseif ($rows["materialtype"]==5){?>
	<a href='<?=$rows["rehref"]?>' title='Подробнее о книге <?=$rows["name"]?>' class="primage">
		<img src='<?=$cfg['site_dir']?>images/<?=$rows["image"]?>' alt='<?=$rows["name"]?>' >
	</a>   
<?}	else {
	   $title = contentHelper::setHrefTitle($rows["name"],$rows["materialtype"],$rows['gname']).' - подробная информация';?>
       <a href='<?=$rows['rehref']?>' title='<?=$title?>' class="primage">		
			<?=contentHelper::showImage('images/'.$rows["image"],'Подробная информация о '.contentHelper::setWordAbout($rows["materialtype"])." ".$rows["gname"]." ".$rows["name"])?>			
		</a>		
<?}?>

</div>

<div class="info_block">
    <? if($rows["materialtype"]==3){?>
     <a name=coin<?=$rows["shopcoins"]?> title='<?=$rows["name"]?>'></a><strong><?=$rows['namecoins']?></strong>
    <? } elseif ($rows["materialtype"]==5){?>
     <a name=coin<?=$rows["shopcoins"]?> title='<?=$rows["name"]?>'></a><strong><?=$rows["name"]?></strong>
    <?} else {?>
    <a name=coin<?=$rows["shopcoins"]?> title='<?=contentHelper::setHrefTitle($rows["name"],$rows["materialtype"],$rows["gname"])?>'></a>
    <?}?>
	<strong><?=$rows['namecoins']?></strong> 
	<div id='info'>	
		<?		
		if ($rows["gname"]){
			$groupItemParams = array();	    
			$groupItemParams['materialtype'] = $rows['materialtype'];
			$groupItemParams['group'] = array($rows["group"]=>$rows["gname"]);
			?>
		Страна: <a href=<?=urlBuild::makePrettyUrl($groupItemParams)?> title='Посмотреть <?=contentHelper::setWordThat($rows["materialtype"])?> <?=$rows["gname"]?>'>
		<?=$rows["gname"]?>
		</a><br>
		<?}?>
		<?= ($rows["year"]?"Год: <strong>".$rows["year"]."</strong><br>":"")?>
		<?= (trim($rows["metal"])?"Металл: <strong>".$rows["metal"]."</strong><br>":"")?>
		<?=(trim($rows["condition"])?"Состояние: <strong><font color=blue>".$rows["condition"]."</font></strong>":"")?>

	</div>
	<? echo contentHelper::render('shopcoins/price/markitem',$rows['mark']); ?>

	
		
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
		$text = mb_substr($rows["details"], 0, 250, 'UTF-8');
		//var_dump($text);
		$text = strip_tags($text);
		if(mb_strlen($text,'UTF-8')<mb_strlen($rows["details"],'UTF-8')){
			$text .= '...';
		}
		//$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		
		$text = str_replace($rows['name'],"<strong>".$rows['name']."</strong>",$text);
		$text = str_replace($rows['gname'],"<strong>".$rows['gname']."</strong>",$text);
		$text = str_replace(" монет ","<strong> монет </strong>",$text);
		$text = str_replace(" монета ","<strong> монета </strong>",$text);
		$text = str_replace(" монеты ","<strong> монеты </strong>",$text);
		$text = str_replace(" монетам ","<strong> монетам </strong>",$text);
		echo "<br><b>Описание:</b> ".str_replace("\n","<br>",$text)."";
	}?>	
	</div>		
	</div>	
	
	<div class="info_block">
	
	<? echo contentHelper::render('shopcoins/price/prices',$rows);?>
	<?echo contentHelper::render('shopcoins/price/buy_button',$rows);?>
	<?
	if(($rows['buy_status']==7||$rows['buy_status']==6)&&($minpriceoneclick<=$rows['price'])) {
	?>
		<div class="div_onecl">
	<?php
		echo contentHelper::render('_mobile/shopcoins/price/oneclick',$rows);
	?>
		</div>
	<?php
	}?>

	<?
	echo contentHelper::render('shopcoins/price/reserved',$rows);
		?>
	</div>
<?php	
$rand = rand(1,2);

if ($mycoins) {
	
	include('mycoins.tpl.php');
}
?>