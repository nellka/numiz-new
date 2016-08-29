<div class="wraper clearfix showitem" >  
<?
if($tpl['show']['error']['no_coins']){?>
    <div class="error">К сожалению, указанного товара не существует</div>
<?} else {
	$rows_main['notSRB'] = true;
    include($cfg['path'].'/views/shopcoins/item/item-showsmall.tpl.php');  
   
}?>
<br style="clear: both;">
<center><a class="qwk button24" href='<?=$cfg['site_dir']?>shopcoins/<?=$rows_main['rehref']?>' style="width:300px;margin: 20px 0 0;color: white;">Полная информация о монете</a></center>
</div>