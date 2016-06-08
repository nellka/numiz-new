<div id='products'>
<div id='pager' class="pager_s">
	<div id='order' class="orderby" style="padding-left:5px;">
			<div style="float:left;line-height:40px">
			Товаров на странице:
			 <?foreach ($tpl['pager']['itemsOnpage'] as $k=>$v){
			 	
				if($k==$tpl['onpage']){?>
					<button type="button" class="button15active" onclick="location='<?=$base_url?>&onpage=<?=$k?>'"><?=$v?></button>
				<?} else { ?>
					<button type="button" class="button15" onclick="location='<?=$base_url?>&onpage=<?=$k?>'"><?=$v?></button>
				<?}
			 }?>   
			</div>
	    </div>
	    <div id='order' class="orderby" style="padding-left:5px;">
			<div class="sort" style="padding-left:15px;"><b>Сортировать по:</b>
			<?php 
	
			foreach ($tpl['pager']['sorts'] as $key=>$sort) { 
				echo '<span>'.$sort;
				foreach (array('asc','desc') as $v){
					$orderBy = $key.$v;
					if($orderBy==$tpl['orderby']){?>
						<img style="cursor:pointer;" onclick="location='<?=$base_url?>&orderby=<?=$orderBy?>'" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedowncolor.jpg':'priceupcolor.jpg'?>" border="">				  
					<?} else {?>
						<img style="cursor:pointer;" onclick="location='<?=$base_url?>&orderby=<?=$orderBy?>'" src="<?=$cfg['site_dir']?>images/static_images/<?=($v=='desc')?'pricedown.jpg':'priceup.jpg'?>" border="">
					<?}
				}
				echo '</span>';
			} ?>       
		</div>
    </div>
	<div id='pages' class="pages_search right">
    	<?php echo $tpl['paginator']->printPager(); ?>
	</div>
</div>
<?

if($tpl['shop']['errors']){?>
	<font color="red"><?=implode("<br>",$tpl['shop']['errors'])?></font>
<?} else {
?>
<br style="clear: both;">
<div class="product-grid search-div">
<?
    $i=1;
    foreach ($tpl['shop']['MyShowArray'] as $key=>$rows){		
    		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
    		<div class='blockshop-full'>";
    		include('items/item.tpl.php');
    		echo "</div>
    		</div>";
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

<script type="text/javascript">

 jQuery(document).ready(function() {    
 	$(".blockshop").on("hover", function(e) {
	    if (e.type == "mouseenter") {
	    	if($(this).find(".qwk")) $(this).find(".qwk").show();
	    } else { // mouseleave
	        if($(this).find(".qwk")) $(this).find(".qwk").hide();
	    }
	});
});
</script>