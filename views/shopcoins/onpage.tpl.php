<div id='pages' class="pages-top">Товаров на странице:
	 <?foreach ($tpl['pager']['itemsOnpage'] as $k=>$v){
		if($k==$tpl['onpage']){?>
			<button type="button" class="button15active" onclick="sendData('onpage','<?=$k?>')"><?=$v?></button>
		<?} else { ?>
			<button type="button" class="button15" onclick="sendData('onpage','<?=$k?>')"><?=$v?></button>
		<?}
	 }?>       	
</div>	
	