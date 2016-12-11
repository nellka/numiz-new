<div id='pages' class='onpage'>Новостей на странице:
	 <?foreach ($tpl['pager']['itemsOnpage'] as $k=>$v){
		if($k==$tpl['onpage']){?>
			<button type="button" class="button15active" onclick="$('#search-news #onpage').val('<?=$k?>');$('#search-news').submit();"><?=$v?></button>
		<?} else { ?>
			<button type="button" class="button15" onclick="$('#search-news #onpage').val('<?=$k?>');$('#search-news').submit();"><?=$v?></button>
		<?}
	 }?>       	
</div>	
	