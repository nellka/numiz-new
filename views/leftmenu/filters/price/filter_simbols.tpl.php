<?
if (isset($tpl['filter']['simbols'])) {
    $style = '';
    if(isset($tpl['filter']['metals'])&&$groups) $style .="s_m";
    if(isset($tpl['filter']['conditions'])) $style .="t";
    
    $simbolUrlParams = array();
	$simbolUrlParams['group'] = $urlParams['group'];
    $simbolUrlParams['nominal'] = $urlParams['nominal']; 

    ?>
	<div id='fb-simbol' class="filter-block">
		<div class="filter_heading" id='simbol-header-close' style="display:<?=$show_simbol?'none':'block'?>">
			<div class="left">Символы</div>        			
			<div class="right"><a class="fc" href="#" onclick="ShowFilterB('simbol',1);return false;">Развернуть</a></div>
		</div>    
		
		<div class="filter_heading" id='simbol-header-open' style="display:<?=$show_simbol?'block':'none'?>">
			<div class="left">Символы</div>     
			<div style="text-align:center;padding: 0 0 0 10px;" class="left">    	
    			<a class="fc" id='simbol-full-show' href="#" onclick="full_filter('simbol');return false;"></a>
    		</div>        			
			<div class="right"><a class="fc" href="#" onclick="clear_filter('simbol');return false;">Сбросить</a></div>
		</div>    
		
		<ul class="filter_heading_ul <?=$style?>" id='ul-simbol' style="display:<?=$show_simbol?'block':'none'?>">
			<div id="filter-groupsimbol_container" class="filter-groupsimbol_container_1">
				<?php 
				foreach ($tpl['filter']['simbols']['filter'] as $filter) {
					$simbolUrlParams['simbol'] = array($filter['filter_id']=>$filter['name']);
					?>            
					<div class="checkbox">
                        <input type="checkbox" name="simbols[]" value="<?=$filter['filter_id']?>" <?=(is_array($simbols)&&in_array($filter['filter_id'], $simbols))?"checked":""?>/>
					       <a href="<?=urlBuild::makePrettyUrl($simbolUrlParams,$r_url)?>"> <?=$filter['name'];?></a>
					</div>
				<?}?>        
    		</div>
    	</ul> 	
	</div>
   <?php 
}?>
