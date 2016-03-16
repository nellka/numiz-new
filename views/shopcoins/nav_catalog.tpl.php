<div id='pager' class="pager<?=isset($prefix)?$prefix:""?>">
	<div id='order' class="orderby" style="padding-left:5px;">
		<div class="sort"><b>Сортировать по:</b>
		<?php 

		foreach ($tpl['pager']['sorts'] as $key=>$sort) { 
			echo '<span>'.$sort;
			foreach (array('asc','desc') as $v){
				$orderBy = $key.$v;
				if($orderBy==$tpl['orderby']){?>
					<img style="cursor:pointer;" onclick="sendData('orderby','<?=$orderBy?>')" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedowncolor.jpg':'priceupcolor.jpg'?>" border="">				  
				<?} else {?>
					<img style="cursor:pointer;" onclick="sendData('orderby','<?=$orderBy?>')" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedown.jpg':'priceup.jpg'?>" border="">
				<?}
			}
			echo '</span>';
		} ?>       
	</div>
	<?include('pager.tpl.php');?>
</div>
</div>
