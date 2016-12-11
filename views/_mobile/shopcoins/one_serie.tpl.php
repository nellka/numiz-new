<div id='serie-<?=$id?>' class="series-one">
<div class=ftl>
<a href="#" onclick="showInvis('search-params-place');return false;" class="left">Фильтры <span class="l-f">(страны, номиналы, года)</span></a>
<a href="#" onclick="showInvis('sorting-place');return false;" class="right">Сортировка</a>
</div>

<div id='search-params-place'  class="search-params-place">

<?require(DIR_TEMPLATE.'_mobile/leftmenu/filters_series.tpl.php');?>

</div>


<div id='sorting-place' class="sorting-place">
<?include(DIR_TEMPLATE.'_mobile/shopcoins/nav_catalog.tpl.php');?>
</div>
<!--
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
    		 
	</div>
</div>-->
<?if(!count($tpl['one_series']['data'])){?>
	<font color="red">Серия монет не найдена</font>
<?} else {?>    
	<br style="clear: both;">
      
    <div class="product-grid search-div">

<?   foreach ($tpl['one_series']['data'] as $key=>$rows){	      
	    
		echo "<div class='blockshop' id='item".$rows['shopcoins']."'>
		<div class='blockshop-full'>";
		include('items/item.tpl.php');
		echo "</div>
		</div>";
    }?>
    </div>
    <div class="pager">
	    <div class="right">
		<?php echo $tpl['paginator']->printPager(); ?>
		</div>
	</div>
    <br style="clear: both;">
    <div class="seo" class="clearfix">
    <h5><?=$tpl['one_series']['group']['name']?> -  <?=$tpl['one_series']['name']?></h5>
    <?=$tpl['one_series']['details']?>
    </div>

  
<? } ?>
</div>