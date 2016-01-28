<? if(isset($filter_groups)&&$filter_groups){?>
<form id='search-params' method="POST" action="<?=$_SERVER['REQUEST_URI']?>" style="float:left;">
<input type="hidden" id='orderby' name='orderby' value='<?=$tpl['orderby']?>'>
<input type="hidden" id='onpage' name='onpage' value='<?=$tpl['onpage']?>'>
<input type="hidden" id='pagenum' name='pagenum' value='<?=$tpl['pagenum']?>'>
<input type="hidden" id='materialtype' name='materialtype' value='<?=$materialtype?>'>
<input type="hidden" id='pricestart' name='pricestart' value='<?=$pricestart?>'>
<input type="hidden" id='priceend' name='priceend' value='<?=$priceend?>'>
<input type="hidden" id='yearstart' name='yearstart' value='<?=$yearstart?>'>
<input type="hidden" id='yearend' name='yearend' value='<?=$yearend?>'>

<div style="width:280px;">
	
	<div class="box-heading "  style="line-height: 30px;margin-top: 15px;padding: 0 10px;;">
		<div style="float:left;">Фильтр товаров</div>
		<div style="float:right;">
			<a  href="#" onclick="clear_filter();return false;">Очистить</a>
		</div>
	</div>
	<br style="clear: both;">

	<?php   
	//вводим фильтр для стран
	 	foreach ($filter_groups as $filter_group) { 	    
	 	    ?>
	
		<div class="filter-block" id='fb-<?=$filter_group['filter_group_id_full']?>'>
    		<div class="filter_heading">
    			<div style="float:left;"><?=$filter_group['name']?></div>
    			<div style="float:right;">
    				<a style="color:#247bbd;font-size:12px;line-height:12px;text-decoration:underline;"
    				href="#" onclick="clear_filter('<?=$filter_group['filter_group_id_full']?>');return false;">Сбросить</a>
    			</div>
    		</div>
<?
		if($filter_group['filter_group_id']=='group'){?> 		
    		
    		<input type="text" value="" id='group_name' placeholder='Название страны' name="group_name" size="30"><input type="button" value="X" onclick="clear_filter('group_name');return false;" style="float:right">

		<?}
		?>
		<ul class="filter_heading_ul">
			<div id="filter-group<?=$filter_group['filter_group_id']?>_container">
				<div class="customScrollBox">
					<div class="container">
						<div class="content">
							<?php 
							foreach ($filter_group['filter'] as $filter) {
								if($filter_group['filter_group_id_full']=='years'){
									//подключаем отдельный вид фильтра  ?>	
									<div class="checkbox">
										<?php  if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
											<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />
											<a href="?materialtype=<?=$materialtype?>&yearsrart=&yearend"> <?=$filter['name'];?></a>
										<?php } else { ?>
											<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />
											<a href="?materialtype=<?=$materialtype?>&yearsrart=&yearend"> <?=$filter['name'];?></a>
										  <?}?>
									</div>
									
								<?} else {?>            
									<div class="checkbox">
										<?php            
										 if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
											<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />
									   <a href="?materialtype=<?=$materialtype?>&<?=$filter_group['filter_group_id']?>=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
										<?php } else { ?>
											<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />
									   <a href="?materialtype=<?=$materialtype?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter['filter_id']))?>"> <?=$filter['name'];?></a>
										  <?}?>
									</div>
								<?php
									if(isset($filter['child'])){?>
										<div style='margin:5px 20px;'>
										<? foreach ($filter['child'] as $filter_child) {?>
											<div class="checkbox">
												<?php             
												 if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter_child['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
												<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter_child['filter_id']?>" checked="checked" />
												<a href="?materialtype=<?=$materialtype?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter_child['filter_id']))?>"> <?=$filter_child['name'];?></a>
												<?php } else { ?>
												<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?php echo $filter_child['filter_id']; ?>" />
												  <a href="?materialtype=<?=$materialtype?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter_child['filter_id']))?>"> <?=$filter_child['name'];?></a>

												<?php } ?>
											</div>
											<?php     
										   }?>
										</div>
									
									<?}
								}
							}?>  
							
						</div>
					</div>
					<div class="dragger_container">
    					<div class="dragger"></div>
    				</div>
			 </div>
			 
			<a href="#" class="scrollUpBtn"></a> <a href="#" class="scrollDownBtn"></a>						

			<? if($filter_group['filter_group_id_full']=='years'){
			    $i=0;?>   
			    <div id='years-slider'>
    				<div style="font-weight:bold;padding: 15px 0;">
    					От <input type="text" id="amount-years0" name="fields_filter[amount-years0][0]" value="0" size="10" disabled/>
    					до <input type="text" id="amount-years1" name="fields_filter[amount-years1][1]" value="2015" size="10" disabled/>
    				</div>
    				<div style="margin:0 5px;margin-top:5px;" id="slider-range-years"></div>	
				</div>		 
			<?}?>       
			</div>
		</ul> 
		</div>
	<?php 
		//break;
		if($filter_group['filter_group_id']=='group'){?>
		    <div class="filter-block">		
			<? if($tpl['filter']['price']['max']){?>
				<div style="float:left;"><b>Цена</b></div> 	
				<div style="float:right;">
				<a style="color:#247bbd;font-size:12px;line-height:12px;text-decoration:underline;" href="">
				Сбросить
				</a>
				</div> 				
				<br><br>
				<span>
				От <input type="text" id="amount-price0" name="fields_filter[amount-price0][0]" value="0" size="10" disabled/>
				до <input type="text" id="amount-price1" name="fields_filter[amount-price1][1]" value="1000" size="10" disabled/> руб.
				</span>			
			<?}?>					
			<p><div id="slider-range-price" style="margin-left:5px;margin-right:5px;"></div></p>
	       </div>	
		<?}
		
	 	}
	?>
 </div>  
</form>

<?}?>

