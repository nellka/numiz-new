<?
$image = (trim($rows_main["image_big"])?$rows_main["image_big"]:$rows_main["image"]);
?>
    
<div id=Image_Big class="main_img_big  center">
	<?=contentHelper::showImage('images/'.$rows_main["image_big"],contentHelper::getImgTitleOne($rows_main),array('alt'=>contentHelper::getAltOne($rows_main)))?>	
</div>	
