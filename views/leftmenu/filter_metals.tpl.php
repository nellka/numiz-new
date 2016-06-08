<?if ($tpl['filter']['metals']) {
    $style = '';
    
    if(isset($tpl['filter']['theme'])) $style .="s_t";
    if(isset($tpl['filter']['conditions'])&&$groups) $style .="c";
    
    ?>		
	<div id='fb-metal' class="filter-block">
		<div class="filter_heading" id='metal-header-close' style="display:<?=$show_metal?'none':'block'?>">
			<div class="left">Металл</div> 						
			<div class="right"><a class="fc" href="#" onclick="ShowFilterB('metal',1);return false;">Развернуть</a></div>
		</div> 
		<div class="filter_heading" id='metal-header-open' style="display:<?=$show_metal?'block':'none'?>">
			<div class="left">Металл</div>    
			<div style="text-align:center;padding: 0 0 0 20px;" class="left">    	
    			    <a class="fc" id='metals-full-show' href="#" onclick="full_filter('metals');return false;"></a>
    		</div>       			
			<div class="right"><a class="fc" href="#" onclick="clear_filter('metals');return false;">Сбросить</a></div>
		</div>      
	
		<ul class="filter_heading_ul <?=$style?>" id='ul-metal' style="display:<?=$show_metal?'block':'none'?>">
			<div id="filter-groupmetals_container" class="filter-groupmetals_container_<?=(count($tpl['filter']['metals']['filter'])>13)?1:1?>">
				<?php 
				foreach ($tpl['filter']['metals']['filter'] as $filter) {?>            
					<div class="checkbox">
	                    <input type="checkbox" name="metals[]" value="<?=$filter['filter_id']?>" <?=(is_array($metals)&&in_array($filter['filter_id'], $metals))?"checked":""?>/>
					       <a href="<?=$r_url?>?<?=$ahref?>&metal=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
					</div>
				<?}?>        
			</div>   
	
		</ul> 	
	</div>
<?}?>



