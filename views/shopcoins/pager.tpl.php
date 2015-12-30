<div id='pager' class="pager<?=isset($prefix)?$prefix:""?>">
	<div id='order' class="orderby" style="padding-left:5px;">
		<div style="float:left;"><b>Сортировать по:</b></div>
		<?php 

		foreach ($tpl['pager']['sorts'] as $key=>$sort) { 
			echo '<div style="float:left;padding-left:10px;">'.$sort.'</div>';
			foreach (array('asc','desc') as $v){
				$orderBy = $key.$v;
				if($orderBy==$tpl['orderby']){?>
					<div style="float:left;">
						<img style="cursor:pointer;" onclick="sendData('orderby','<?=$orderBy?>')" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedowncolor.jpg':'priceupcolor.jpg'?>" border="">				  
					</div>
				<?} else {?>
					<div style="float:left;">
						<img style="cursor:pointer;" onclick="sendData('orderby','<?=$orderBy?>')" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedown.jpg':'priceup.jpg'?>" border="">
					</div>
				<?}
			}
		} ?>       
	</div>
	<div id='pages' class="pages">Товаров на странице:
		 <?foreach ($tpl['pager']['itemsOnpage'] as $k=>$v){
			if($k==$tpl['onpage']){?>
				<button type="button" class="button15active" onclick="sendData('onpage','<?=$k?>')"><?=$v?></button>
			<?} else { ?>
				<button type="button" class="button15" onclick="sendData('onpage','<?=$k?>')"><?=$v?></button>
			<?}
		 }?>   
    	<div class="pages">
    		 <?php echo $tpl['paginator']->printPager(); ?>
    	</div>	
	</div>
	<!--<div class="orderby" style="padding-top:12px;">
		<input type="checkbox" id="avilable"/><label for="avilable"><b>Только в наличии</b></label> 

	</div>-->
	
</div>
