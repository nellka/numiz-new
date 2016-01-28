<?
$checked_array = $rows['checked'];
var_dump($rows['filter']);
?>
<div class="filter-block" id="fb-<?=$rows['filter_group_id_full']?>">
	<div class="filter_heading">
		<div style="float:left;"><?=$rows['name']?></div>
		<div style="float:right;">
			<a style="color:#247bbd;font-size:12px;line-height:12px;text-decoration:underline;"
			href="#" onclick="clear_filter('<?=$rows['filter_group_id_full']?>');return false;">Сбросить</a>
		</div>
	</div>
	<ul class="filter_heading_ul">
		<div id="filter-group<?=$rows['filter_group_id']?>_container">
			<div class="customScrollBox">
				<div class="container">
					<div class="content">
						<?php 
						foreach ($rows['filter'] as $filter) {?>						          
							<div class="checkbox">
							<?php            
							 if (is_array($checked_array)&&in_array($filter['filter_id'], $checked_array)) { ?>
									<input type="checkbox" name="<?=$rows['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />
									   <a href="?materialtype=<?=$rows['materialtype']?>&<?=$rows['filter_group_id']?>=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
										<?php } else { ?>
											<input type="checkbox" name="<?php echo $rows['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />
									   <a href="?materialtype=<?=$rows['materialtype']?>&<?=$rows['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter['filter_id']))?>"> <?=$filter['name'];?></a>
						    <?}?>
							</div>			
						<?}?>  					
					</div>
				</div>
				<div class="dragger_container">
					<div class="dragger"></div>
				</div>
		 </div>
		 
		<a href="#" class="scrollUpBtn"></a> <a href="#" class="scrollDownBtn"></a>
		<? if($rows['filter_group_id_full']=='years'){?>   
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