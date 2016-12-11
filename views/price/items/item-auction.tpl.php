<?
include(START_PATH."/config.php");

$rehref = urlBuild::priceCoinsUrl($rows['priceshopcoins']);
	
$namecoins = "Монета ";					
if ($rows['gname'])	$namecoins .= $rows['gname']." ";
$namecoins .= $rows['aname'];
if ($rows['ametal'])	$namecoins .= " ".$rows['ametal']; 
if ($rows['year'])	$namecoins .= " ".$rows['year'];

?>
<div class="center">		
	<a href='<?=$cfg['site_dir']?>price/<?=$rehref?>' title='<?=$title?>' class="borderimage primage">
	
	<?
	if(file_exists($cfg['oldpath']."/price/images/".$rows["bigimage"])){
		$title = "Посмотреть подробное изображение монеты ".$rows["gname"]." ".$rows["aname"];?>
		<img  src='<?=$cfg['site_dir']?>price/images/<?=$rows["image"]?>' title='<?=$title?>' alt="<?=$title?>">
	<?} elseif (file_exists($cfg['oldpath']."/price/images/".$rows["image"])){
		$title = "Каталог цен на монеты - монета ".$rows["gname"]." ".$rows["aname"];?>	
		<img  src='<?=$cfg['site_dir']?>price/images/<?=$rows["image"]?>' title='<?=$title?>' alt="<?=$title?>">
	<?} else {?>
		<span class="blue">Нет изображения</span>
	<?}?>
		
	</a>
</div>		
		
<div class="coinname">
	<a name=coin<?=$rows["shopcoins"]?> title='Подробнее о цене на монету <?=$rows["gname"]?> <?=$rows["aname"]?>' href="<?=$rehref?>">
	<strong><?=$namecoins?></strong> 
	</a>
</div>


<div id='info'>	
				
	<b>Цена:</b> <span class=blue><?=$tpl['user']["user_id"]?$rows["priceend"]." руб.":"Стоимость доступна авторизованным пользователям"?></span></strong><br>
	<b>Дата прохода:</b> <?=date('d-M-Y',$rows["dateend"])?>	
	<?if($tpl['user']["user_id"]){
		if ($rows['auction'] == 1){
			echo "<br><noindex><a rel=\"nofollow\" href='http://www.wolmar.ru/auction/".$rows['anumber']."/".$rows['number']."' target=_blank>Ссылка на лот >>> </a></noindex>";
		} elseif ($rows['auction'] == 2){
		echo "<br><noindex><a href='http://auction.conros.ru/lotinf.php?id=".$rows['number']."&st=".$rows['numberimage']."&aid=".$rows['anumber']."' target=_blank rel=\"nofollow\">Ссылка на лот >>> </a></noindex>";
		}
		
		if (!$rows['checkuser'] && $tpl['user']['staruser']>=10){?>
			<br><br><div id='usercheck<?=$rows['priceshopcoins']?>' class="center" title='Если Вы заметили несоотвествия в металле, номинале, годе выпуска и т.п., то сообщите нам об этом нажав на эту кнопку'>
			<input type=button class=button25 value='Есть несоответствия?' onclick="if(confirm('Вы уверены?')){CheckUser('<?=$rows['priceshopcoins']?>');};" ></div>
		<?}
	}
	?>
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
				