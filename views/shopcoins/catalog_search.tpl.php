<div id='products'>
<div id='pager' class="pager_s">
	<div id='order' class="orderby" style="padding-left:5px;">
		<div style="float:left;">
		Товаров на странице:
		 <?foreach ($tpl['pager']['itemsOnpage'] as $k=>$v){
			if($k==$tpl['onpage']){?>
				<button type="button" class="button15active" onclick="sendData('onpage','<?=$k?>')"><?=$v?></button>
			<?} else { ?>
				<button type="button" class="button15" onclick="sendData('onpage','<?=$k?>')"><?=$v?></button>
			<?}
		 }?>   
		</div>
    </div>
	<div id='pages' class="pages">
    	<div class="pages">
    		 <?php echo $tpl['paginator']->printPager(); ?>
    	</div>	
	</div>
</div>
<?

if($tpl['shop']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['shop']['errors'])?></font>
<?} else {
?>
<br style="clear: both;">
<div class="product-grid">
<?
    $i=1;
    foreach ($tpl['shop']['MyShowArray'] as $key=>$rows){		
    		echo "<div class='blockshop'>";
    		include('items/item.tpl.php');
    		echo "</div>";
    	$i++;	
    }?>
</div>
<?}?>

<div id='pages' class="pages">
    	<div class="pages">
    		 <?php echo $tpl['paginator']->printPager(); ?>
    	</div>	
	</div>
</div>