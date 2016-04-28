<div id='series' class="series">

<?if(!count($tpl['series'])){?>
	<font color="red">Серий монет не найдено</font>
<?} else {
    $i=0;
    foreach ($tpl['series'] as $key=>$rows){     
    	echo "<h1>".$rows['group']["name"]."</h1>";
    	foreach ($rows['data'] as $s){?> 
    	<div class="series-grid">
    	<a href="<?=$cfg['site_dir']?>shopcoins/series/<?=$s["id"]?>" class="image-s">
    	
    	<? if($s["image"]){    	
    	   echo contentHelper::showImage('/seriesimages/'.$s["image"],$s["name"]);    	
    	} else {?>
    		<img src="<?=$cfg['site_dir']?>images/net_izobrajenia.jpg" width="200" />
    	<?}?>
    	</a>
    	<a href="<?=$cfg['site_dir']?>shopcoins/series/<?=$s["id"]?>" class="image-t">
    	<?=$s["name"]?>	
    	</a>
    	<?=$s["details"]?>
    	</div>
    	<?
    	$i++;
    	if($i>1){?>
    	<br style="clear: both; ">
    	<?$i++;
    	}?>
    	<?}    	
    }
} ?>
    