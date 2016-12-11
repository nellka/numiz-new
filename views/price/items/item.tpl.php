<?
include(START_PATH."/config.php");

$rehref = "Монета ";					
			
if ($rows['gname'])
	$rehref .= $rows['gname']." ";
$rehref .= $rows['aname'];
if ($rows['ametal'])
	$rehref .= " ".$rows['ametal']; 
if ($rows['year'])
	$rehref .= " ".$rows['year'];
$namecoins = $rehref;
$rehref = contentHelper::strtolower_ru($rehref)."_cpc".$rows['priceshopcoins']."_cpr".$rows['parent']."_pcn0.html";
			
?>						

<div class="center">		
	<a href='<?=$cfg['site_dir']?>price/<?=$rehref?>' title='<?=$title?>' class="borderimage primage">
		<img  src='<?=$cfg['site_dir']?>price/images/<?=$rows["image"]?>' title='<?=$title?>' alt="<?=$title?>">
	</a>
</div>		
		
<div class="coinname">
	<a name=coin<?=$rows["shopcoins"]?> title='Подробнее о стоимости на монету <?=$rows["gname"]?> <?=$rows["aname"]?>' href="<?=$rehref?>">
	<strong><?=$namecoins?></strong> 
	</a>
	<div id=mysubscribecatalog<?=$rows["catalog"]?>></div>	
</div>


<div id='info'>	
	<?	
	if ($rows["gname"]){
		$groupItemParams['group'] = array($rows["group"]=>$rows["gname"]);		
		?>
		<b>Страна:</b> <a class="group_href" href="<?=urlBuild::makePrettyUrl($groupItemParams,"http://www.numizmatik.ru/price")?>" title='Посмотреть цены на монеты <?=$rows["gname"]?>'><?=$rows["gname"]?></a>
	<?}?>
	Название: <strong><?=$rows["aname"]?></strong>
	<?=trim($rows["metal"])?"<br>Металл: <strong>".$rows["ametal"]."</strong>":""?>
	<?=$rows["year"]?"<br>Год:&nbsp;<strong>".$rows["year"]."</strong>":""?>
	<?=$rows["simbols"]?"<br>Символы: <strong>".$rows["asimbols"]."</strong>":""?>
	<?=trim($rows["condition"])?"<br>Состояние: <strong><span class=blue>".$rows["acondition"]."</span></strong>":""?>
	
</div>

<div id=subinfo>
<?php
if (trim($rows["details"]))
{
	$text = substr($rows["details"], 0, 250);
	$text = strip_tags($text);
	$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		echo "<br>Описание: ".str_replace("\n","<br>",$text)."";
}

?>
</div>	