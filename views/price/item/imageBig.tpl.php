<?
$image = (trim($rows_main["image_big"])?$rows_main["image_big"]:$rows_main["image"]);
?>
    
<div id=Image_Big class="main_img_big">
	<?=contentHelper::showImage('images/'.$rows_main["image_big"],$rows_main["gname"]." - ".$rows_main["name"])?>	
</div>	
