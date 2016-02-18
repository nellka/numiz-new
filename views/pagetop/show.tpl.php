<? include('shortmenu.tpl.php')?>

<div style='float:left; margin-left:30px;line-height:23px;'>
<a style="text-decoration:none;" href="<?=$tpl['show']['lhreg']?>">Вернуться к подбору товара</a>
</div>

<div style='float:left; margin-left:30px;'>
<?php include $cfg['path'] . '/views/common/breadcrumb.tpl.php'; ?> 
</div>	
    
<div style='float:right;line-height:23px;'>

<?if($tpl['show']['previos']){
	?> <a style="text-decoration:none;color:#000000;" href="<?=$cfg['site_dir']?>shopcoins/<?=$tpl['show']['previos']["rehref"]?>">< Предыдущий</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?}
if($tpl['show']['next']){?>
 <a style="text-decoration:none;color:#000000;" href="<?=$cfg['site_dir']?>shopcoins/<?=$tpl['show']['next']["rehref"]?>">Следующий ></a>
 <?}?>
</div>		

</div >
<br style="clear: both;">