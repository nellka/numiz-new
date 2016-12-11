	<?
	$show_condition = true;
?>

<div id='fb-condition' class="filter-block">
		<div class="filter_heading" id='condition-header-close' style="display:<?=$show_condition?'none':'block'?>">
			<div class="left">Состояние</div>        			
			<div class="right"><a class="fc" href="#" onclick="ShowFilterB('condition',1);return false;">Развернуть</a></div>
		</div> 
		<div class="filter_heading" id='condition-header-open' style="display:<?=$show_condition?'block':'none'?>">
			<div class="left">Состояние</div>  
			<div style="text-align:center;padding: 0 0 0 10px;" class="left">    	
    			<a class="fc" id='condition-full-show' href="#" onclick="full_filter('condition');return false;"></a>
    		</div>          			
			<div class="right"><a class="fc" href="#" onclick="clear_filter('conditions');return false;">Сбросить</a></div>
		</div>    
		
		<ul class="filter_heading_ul <?=$style?>" id="ul-condition" style="display:<?=$show_condition?'block':'none'?>">
			<div id="filter-groupconditions_container" class="filter-groupcondition_container_1">
				<?php 
				foreach ($tpl['filter']['conditions']['filter'] as $filter) {
					$condUrlParams['condition'] = array($filter['filter_id']=>$filter['name']);  
					?>            
					<div class="checkbox">
                        <input type="checkbox" name="conditions[]" value="<?=$filter['filter_id']?>" <?=(is_array($conditions)&&in_array($filter['filter_id'], $conditions))?"checked":""?>/>
					       <a href="<?=$r_url?>?conditions[]=<?=$filter['filter_id']?><?=$a_href_group?><?=$a_href_nominal?>"> <?=$filter['name'];?></a>
					</div>
				<?}?>        
    		</div>
    	</ul> 	
	</div>