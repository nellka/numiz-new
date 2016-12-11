<?

//((trim($rows_main["bigimage"])&&file_exists("./images/".trim($rows_main["bigimage"])))?$rows_main["bigimage"]:$rows_main["image"])
?>

<div class="mainItemPhoto">
	<div id="Image_Big" class="main_img_big center">
	
	<?if($rows_main["bigimage"]&&file_exists($cfg['oldpath']."/price/images/".$rows_main["bigimage"])){
		$title = "Посмотреть подробное изображение монеты ".$rows_main["gname"]." ".$rows_main["aname"];?>
		<img  src='<?=$cfg['site_dir']?>price/images/<?=$rows_main["bigimage"]?>' title='<?=$title?>' alt="<?=$title?>">
	<?} elseif ($rows_main["image"]&&file_exists($cfg['oldpath']."/price/images/".$rows_main["image"])){
		$title = "Каталог цен на монеты - монета ".$rows_main["gname"]." ".$rows_main["aname"];?>	
		<img  src='<?=$cfg['site_dir']?>price/images/<?=$rows_main["image"]?>' title='<?=$title?>' alt="<?=$title?>">
	<?} else {?>
		<span class="blue">Нет изображения</span>
	<?}?>
      
	</div>
</div>
<div class="detailsItem">
     <h1><?=$rows_main["gname"]." ".$rows_main["aname"]." - ".$rows_main["year"]?></h1>       

     <?
	if ($rows_main["gname"]){?>
	Страна: <a href="<?=$r_url."/".urlBuild::groupUrl($rows_main["gname"],$rows_main["group"])?>" title='Посмотреть цены на монеты <?=$rows_main["gname"]?>'>
	<strong><?=$rows_main["gname"]?></strong>
	</a><br>
	<?}?>
	Название: <strong><?=$rows_main["name"]?></strong><br>
	<?= (trim($rows_main["metal"])?"Металл: <strong>".$rows_main["ametal"]."</strong><br>":"")?>
	<?= ($rows_main["year"]?"Год: <strong>".$rows_main["year"]."</strong><br>":"")?>
	<?= ($rows_main["simbols"])?"Символы: ".$rows_main["asimbols"]."<br>":"";?>	
	<?=($rows_main["condition"]?"Состояние: <strong><span class=blue>".$rows_main["acondition"]."</span></strong>":"")?>
<?
if (trim($rows_main["details"])){
	
	$text = strip_tags($rows_main["details"]);
	echo "<br>Описание: ".str_replace("\n","<br>",$text)."";
}
?>
</div>
	