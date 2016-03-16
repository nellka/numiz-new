<? include('shortmenu.tpl.php')?>

<div style='margin-left:10px;' class="breadcrumb left">
<a style="text-decoration:none;" href="<?=$tpl['show']['lhreg']?>">Вернуться назад</a>
</div>

<div style='float:left; margin-left:10px;'>
<?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
</div>	
    
<div  class="breadcrumb right">

<?if($tpl['show']['previos']){
	?> <a style="text-decoration:none;color:#000000;" href="<?=$cfg['site_dir']?>shopcoins/<?=$tpl['show']['previos']["rehref"]?>">< Предыдущий</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?}
if($tpl['show']['next']){?>
 <a style="text-decoration:none;color:#000000;" href="<?=$cfg['site_dir']?>shopcoins/<?=$tpl['show']['next']["rehref"]?>">Следующий ></a>
 <?}?>
</div>		

</div >
<br style="clear: both;">