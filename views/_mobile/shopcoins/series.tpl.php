<div id='series' class="series">

<?if(!count($tpl['series'])){?>
	<font color="red">Серий монет не найдено</font>
<?} else {
    $i=0;
    	
    foreach ($tpl['series']['data'] as $s){
       // var_dump($s);
        ?> 
    	<div class="blockshop">
	    	<div class="blockshop-full">
		    	<div class="ceries-top">
		    	
		    	<a href="<?=$cfg['site_dir']?>shopcoins/series/<?=$s["id"]?>" class="image-s left">			    	
			    	<? if($s["image"]){    	
			    	   echo contentHelper::showImage('/seriesimages/'.$s["image"],$s["name"]);    	
			    	} else {?>
			    		<img src="<?=$cfg['site_dir']?>images/net_izobrajenia.jpg" width="200" />
			    	<?}?>
			    	</a>
			    	<a href="<?=$cfg['site_dir']?>shopcoins/series/<?=$s["id"]?>" class="image-t">
			    	<?=$s["name"]?>	
			    	</a> / <a href="<?=$cfg['site_dir']?>shopcoins/series/<?=$s["id"]?>" class="s-group"><?=$s['groupname']?></a><br><br>
		    	</div>
		    	<div class="descr">		    	
		    	<?=$s["details"]?>
		    	</div>
	    	</div>
    	</div>    	
    <?}?>   
    	<!--<br style="clear: both;"> -->	
   <? 
} ?>
<br style="clear: both;">
    