<div  class="box-heading ">
	<div class='left'>Сортировка</div>
	<div class='right'>
		<a onclick="$('#sorting-place').hide();return false;" href="#" class="closef"></a>
	</div>
</div>


<div id='order' class="orderby content">
	<div class="sort"><b>Сортировать по:</b>
		<?php 

		foreach ($tpl['pager']['sorts'] as $key=>$sort) { 
			echo '<div>'.$sort;
			foreach (array('asc','desc') as $v){
				$orderBy = $key.$v;
				if($orderBy==$tpl['orderby']){?>
					<img style="cursor:pointer;" onclick="sendData('orderby','<?=$orderBy?>')" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedowncolor_m.jpg':'priceupcolor_m.jpg'?>" border="">				  
				<?} else {?>
					<img style="cursor:pointer;" onclick="sendData('orderby','<?=$orderBy?>')" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedown_m.jpg':'priceup_m.jpg'?>" border="">
				<?}
			}
			echo '</div>';
		} ?>       
	</div>
</div>
