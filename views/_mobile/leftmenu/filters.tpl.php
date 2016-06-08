<? 

//$filter_groups = isset($rows['filter_groups'])?$rows['filter_groups']:$tpl['filter_groups'];

if(isset($filter_groups)&&$filter_groups){
    //var_dump($groups,$nominals);
   /* $groups = isset($rows['groups'])?$rows['groups']:array();;
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
    $search = isset($rows['search'])?$rows['search']:'';*/
   // var_dump($priceend,$pricestart);

    $ahref = "";
    $ahref_groups ='';
    $ahref_years_p ='';
    $ahref_years ='';
    $ahref_nominals ='';
    if($GroupNameID) {
    	$ahref_groups .='&group='.$GroupNameID;
    } else {
	    foreach ((array)$groups as $group){
	    	$ahref_groups .='&groups[]='.$group;
	    }
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
<form id='search-params' method="POST" action="?<?=$search?"search=$search":""?>">
<input type="hidden" id='orderby' name='orderby' value='<?=$tpl['orderby']?>'>
<input type="hidden" id='onpage' name='onpage' value='<?=$tpl['onpage']?>'>
<input type="hidden" id='pagenum' name='pagenum' value='<?=$tpl['pagenum']?>'>
<input type="hidden" id='pricestart' name='pricestart' value='<?=$pricestart?>'>
<input type="hidden" id='priceend' name='priceend' value='<?=$priceend?>'>
<input type="hidden" id='yearstart' name='yearstart' value='<?=$yearstart?>'>
<input type="hidden" id='yearend' name='yearend' value='<?=$yearend?>'>

<div class='f-container'>
	
    <div  class="box-heading ">
    	<div class='left'>Фильтр товаров</div>
    	<div class='clearf'>
			<a  href="#" onclick="clear_filter();return false;">Очистить</a>
		</div>
		
    	<div class='right'>
    		<a onclick="$('#search-params-place').hide();return false;" href="#" class="closef"></a>
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
	 		if(in_array($filter_group['filter_group_id_full'],array('years_p'))){
	 			$ahref .= $ahref_nominals;
	 		}	
	 		  
	 	    ?>
	
		<div class="filter-block" id='fb-<?=$filter_group['filter_group_id_full']?>'>
    		<div class="filter_heading">
    			<div style="float:left;"><?=$filter_group['name']?></div>
    			<?if($filter_group['filter_group_id']=='group'){  ?> 
			      <div style="float:left;padding: 0 0 0 40px;">  
                         <?if($c_en){?>	
                            <a class="fc" href="#" onclick="$.cookie('c_en',0);location.reload();return false;">RUS</a> | Krause
                        <?} else {?>
                            RUS | <a class="fc" href="#" onclick="$.cookie('c_en',1);location.reload();return false;">Krause</a>
                        <?}?>
                   </div> 	
	    	   <?}?>
    			<div style="float:right;">    			  
    				<a class="fc" href="#" onclick="clear_filter('<?=$filter_group['filter_group_id_full']?>');return false;">Сбросить</a>
    			</div>
    		</div>
<?

		if($filter_group['filter_group_id']=='group'){?> 
			<div id='f-details' class="f-details-block">		
    		<?
    		foreach ($filter_groups['group_details'] as $group_id=>$group_name){?>
    			<a href="#" class="filtr-g-d" onclick="clear_filter_group('<?=$group_id?>');return false;"><?=$group_name?> - X</a>     
    		<?}?>
    		</div>
    		<div class="f-details-block">
        		<input type="text" value="" id='group_name' placeholder='Название страны' name="group_name" size="30">
        		
        		<input type="button" value="" class="filtr-s-d" onclick="clear_filter('group_name');return false;">          
        		<input type="button" value="" class="filtr-s" onclick="fgroup();return false;">
    		</div>
    	<?}
		?>
		<ul class="filter_heading_ul">
			<div id="filter-group<?=$filter_group['filter_group_id']?>_container" class="filter-group<?=$filter_group['filter_group_id']?>_container_<?=(count($filter_group['filter'])>13)?1:0?>">
			
			<?php 
			foreach ($filter_group['filter'] as $filter) {
				if($filter_group['filter_group_id_full']=='years'){
					//подключаем отдельный вид фильтра  ?>	
					<div class="checkbox">
						<?php  if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
							<a href="?<?=$ahref?>&years_p=<?=$filter['filter_id']?>" class="blue"> <?=$filter['name'];?></a>
						<?php } else { ?>
							<a href="?<?=$ahref?>&years_p=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
						  <?}?>
					</div>					
				<?} else {?>            
					<div class="checkbox">
						<?php            
						 if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
							<!--<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" checked="checked" />-->
					   <a href="?<?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=$filter['filter_id']?>" class="blue"> <?=$filter['name'];?></a>
						<?php } else { ?>
							<!--<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?=$filter['filter_id']?>" />-->
					   <a href="?<?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter['filter_id']))?>"> <?=$filter['name'];?></a>
						  <?}?>
					</div>
				<?php
					if(isset($filter['child'])){?>
						<div style='margin:5px 20px;'>
						<? foreach ($filter['child'] as $filter_child) {?>
							<div class="checkbox">
								<?php             
								 if (is_array($$filter_group['filter_group_id_full'])&&in_array($filter_child['filter_id'], $$filter_group['filter_group_id_full'])) { ?>
								<!--<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter_child['filter_id']?>" checked="checked" />-->
								<a href="?<?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter_child['filter_id']))?>" class="blue"> <?=$filter_child['name'];?></a>
								<?php } else { ?>
								<!--<input type="checkbox" name="<?php echo $filter_group['filter_group_id_full']; ?>[]" value="<?php echo $filter_child['filter_id']; ?>" />-->
								  <a href="?<?=$ahref?>&<?=$filter_group['filter_group_id']?>=<?=urlencode(iconv("utf8","cp1251",$filter_child['filter_id']))?>"> <?=$filter_child['name'];?></a>

								<?php } ?>
							</div>
							<?php     
						   }?>
						</div>
					
					<?}
				}
			}?>  			
			
				<? if($filter_group['filter_group_id_full']=='years'){
			    $i=0;?>   
			    <div style="font-weight:bold;padding: 15px 0;">
					От <input type="text" id="amount-years0" name="fields_filter[amount-years0][0]" value="<?=$yearstart?$yearstart:$tpl['filter']['yearstart']?>" size="10"/>
					до <input type="text" id="amount-years1" name="fields_filter[amount-years1][1]" value=" <?=$yearend?$yearend:$tpl['filter']['yearend']?>" size="10"/>
					<a href="#" onclick="sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');return false" class="right button25">OK</a>
				</div> 
			<?}?>       
			</div>
		</ul> 
		  <?if($filter_group['filter_group_id']=='group'){?> 
		      <div style="text-align:center;padding: 0 0 5px;">    	
    			    <a class="fc" id='group-full-show' href="#" onclick="full_filter('<?=$filter_group['filter_group_id_full']?>');return false;"></a></div>    		
    	   <?}?>
		</div>
	<?php 
		//break;

		if($filter_group['filter_group_id']=='years'){?>
		  <? if($tpl['filter']['price']['max']&&$tpl['filter']['price']['max']!=$tpl['filter']['price']['min']){?>
		    <div class="filter-block" id='filter-price'>		
				<div class="filter_heading">
					<div class="left"><b>Цена</b></div> 	
					<div class="right">
					<a href="#"  class="fc" onclick="clear_filter('price');return false;">Сбросить</a>
					</div> 				
				</div>
				<ul class="filter_heading_ul">
				<div>
				От <input type="text" id="amount-price0" name="fields_filter[amount-price0][0]" value="<?=$pricestart?$pricestart:$tpl['filter']['price']['min']?>" size="10"/>
			    до <input type="text" id="amount-price1" name="fields_filter[amount-price1][1]" value=" <?=$priceend?$priceend:$tpl['filter']['price']['max']?>" size="10"/> руб.
			    <a href="#" onclick="sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');return false;" class="right button25">OK</a>
				</div>			
				</ul>						
	       </div>	
	       <?}?>
		<?}
		
	 	}
	?>
 </div>  
</form>

<?}?>

