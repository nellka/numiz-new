<? 
include(START_PATH."/config.php");
$filter_groups = isset($rows['filter_groups'])?$rows['filter_groups']:$tpl['filter_groups'];

if(isset($filter_groups)&&$filter_groups){
    //var_dump($groups,$nominals);
    $groups = isset($rows['groups'])?$rows['groups']:array();;
    $years_p = isset($rows['years_p'])?$rows['years_p']:array();
    $years = isset($rows['years'])?$rows['years']:array();
    $nominals = isset($rows['nominals'])?$rows['nominals']:array();
    $tpl = isset($rows['tpl'])?$rows['tpl']:array();
    $metals = isset($rows['metals'])?$rows['metals']:array();
    $conditions = isset($rows['conditions'])?$rows['conditions']:array();
    $themes = isset($rows['themes'])?$rows['themes']:array();
    $materialtype = isset($rows['materialtype'])?$rows['materialtype']:'';
    $pricestart = isset($rows['pricestart'])?$rows['pricestart']:'';
    $priceend = isset($rows['priceend'])?$rows['priceend']:'';
    $yearstart = isset($rows['yearstart'])?$rows['yearstart']:'';
    $yearend = isset($rows['yearend'])?$rows['yearend']:'';
    $seriess = isset($rows['seriess'])?$rows['seriess']:array();
    $search = isset($rows['search'])?$rows['search']:'';
   // var_dump($priceend,$pricestart);

    $ahref = "";
    $ahref_groups ='';
    $ahref_years_p ='';
    $ahref_years ='';
    $ahref_nominals ='';
    
    foreach ((array)$groups as $group){
    	$ahref_groups ='&groups[]='.$group;
    }
    foreach ((array)$years_p as $year_p){
    	$ahref_years_p ='&years_p[]='.$year_p;
    }
    foreach ((array)$years as $year){
    	$ahref_years ='&years[]='.$year;
    }
    foreach ((array)$nominals as $nominal){
    	$ahref_nominals ='&nominals[]='.$nominal;
    }
        //var_dump($ahref);
    ?>
<form id='search-params' method="POST" action="<?=$cfg['site_dir']?>shopcoins?<?=$search?"search=$search":"materialtype=$materialtype"?>" style="float:left;">
<input type="hidden" id='orderby' name='orderby' value='<?=$tpl['orderby']?>'>
<input type="hidden" id='onpage' name='onpage' value='<?=$tpl['onpage']?>'>
<input type="hidden" id='pagenum' name='pagenum' value='<?=$tpl['pagenum']?>'>
<input type="hidden" id='materialtype' name='materialtype' value='<?=$materialtype?>'>
<input type="hidden" id='pricestart' name='pricestart' value='<?=$pricestart?>'>
<input type="hidden" id='priceend' name='priceend' value='<?=$priceend?>'>
<input type="hidden" id='yearstart' name='yearstart' value='<?=$yearstart?>'>
<input type="hidden" id='yearend' name='yearend' value='<?=$yearend?>'>

<div class='f-container'>
	
	<div class="box-heading "  style="line-height: 30px;margin-top: 15px;padding: 0 10px;;">
		<div style="float:left;">Фильтр товаров</div>
		<div style="float:right;">
			<a  href="#" onclick="clear_filter();return false;">Очистить</a>
		</div>
	</div>
	<br style="clear: both;">

	<?php   
	//вводим фильтр для стран
	 	foreach ($filter_groups as $k=>$filter_group) { 
	 		if(!isset($filter_group['filter_group_id_full'])) continue;

	 		if(in_array($filter_group['filter_group_id_full'],array('nominals','years','years_p'))){
	 			$ahref = $ahref_groups;
	 		}
	 			
	 		//var_dump();    
	 	    ?>
	
		<div class="filter-block" id='fb-<?=$filter_group['filter_group_id_full']?>'>
    		<div class="filter_heading">
    			<div style="float:left;"><?=$filter_group['name']?></div>
    			<?if($filter_group['filter_group_id']=='group'){?> 
			      <div style="float:left;padding: 0 40px;">    	
	    		   <a class="fc" id='group-full-show-top' href="#" onclick="full_filter('<?=$filter_group['filter_group_id_full']?>');return false;"></a>
	    		  </div>    		
	    	   <?}?>
    			<div style="float:right;">    			  
    				<a class="fc" href="#" onclick="clear_filter('<?=$filter_group['filter_group_id_full']?>');return false;">Сбросить</a>
    			</div>
    		</div>
<?

		if($filter_group['filter_group_id']=='group'){?> 
			<div id='f-details'>		
    		<?
    		foreach ($filter_groups['group_details'] as $group_id=>$group_name){?>
    			<a href="#" class="filtr-g-d" onclick="clear_filter_group('<?=$group_id?>');return false;"><?=$group_name?> - X</a>     
    		<?}?>
    		</div>
    		<input type="text" value="" id='group_name' placeholder='Название страны' name="group_name" size="30">
    		
    		<input type="button" value="" class="filtr-s-d" onclick="clear_filter('group_name');return false;">          
    		<input type="button" value="" class="filtr-s" onclick="fgroup();return false;">
    		

		<?}
		?>
		<ul class="filter_heading_ul">
			<div id="filter-group<?=$filter_group['filter_group_id']?>_container" class="filter-group<?=$filter_group['filter_group_id']?>_container_<?=(count($filter_group['filter'])>13)?1:0?>">
			<?if(count($filter_group['filter'])>13){/*?>
				<div class="customScrollBox">
					<div class="container">
						<div class="content">
						<?*/}?>
							<?php 
							foreach ($filter_group['filter'] as $filter) {
								if($filter_group['filter_group_id_full']=='years'){
									//подключаем отдельный вид фильтра  ?>	
									<div class="checkbox">
										<?php  if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
											<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />
											<a href="?materialtype=<?=$materialtype?><?=$ahref?>&yearsrart=&yearend"> <?=$filter['name'];?></a>
										<?php } else { ?>
											<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />
											<a href="?materialtype=<?=$materialtype?><?=$ahref?>&yearsrart=&yearend"> <?=$filter['name'];?></a>
										  <?}?>
									</div>
									
								<?} else {?>            
									<div class="checkbox">
										<?php            
										 if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
											<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />
									   <a href="?materialtype=<?=$materialtype?><?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
										<?php } else { ?>
											<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />
									   <a href="?materialtype=<?=$materialtype?><?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter['filter_id']))?>"> <?=$filter['name'];?></a>
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
												<a href="?materialtype=<?=$materialtype?><?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter_child['filter_id']))?>"> <?=$filter_child['name'];?></a>
												<?php } else { ?>
												<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?php echo $filter_child['filter_id']; ?>" />
												  <a href="?materialtype=<?=$materialtype?><?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter_child['filter_id']))?>"> <?=$filter_child['name'];?></a>

												<?php } ?>
											</div>
											<?php     
										   }?>
										</div>
									
									<?}
								}
							}?>  
							
						<?if(count($filter_group['filter'])>13){/*?></div>
					</div>
					<div class="dragger_container">
    					<div class="dragger"></div>
    				</div>
			 </div>
			 
			 
			<a href="#" class="scrollUpBtn"></a> <a href="#" class="scrollDownBtn"></a>						
				<?*/}?>
							
				
				
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
		  <?if($filter_group['filter_group_id']=='group'){?> 
		      <div style="text-align:center;padding: 10px 0 0;">    	
    			    <a class="fc" id='group-full-show' href="#" onclick="full_filter('<?=$filter_group['filter_group_id_full']?>');return false;"></a></div>    		
    	   <?}?>
		</div>
	<?php 
		//break;

		if($filter_group['filter_group_id']=='years'){?>
		  <? if($tpl['filter']['price']['max']&&$tpl['filter']['price']['max']!=$tpl['filter']['price']['min']){?>
		    <div class="filter-block" id='filter-price'>		
			
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
								
			<p><div id="slider-range-price" style="margin-left:5px;margin-right:5px;"></div></p>
	       </div>	
	       <?}?>
		<?}
		
	 	}
	?>
 </div>  
</form>

<?}?>

