<?
if (isset($tpl['filter']['theme'])) {  
    $themeUrlParams = array();
	//$themeUrlParams['group'] = $urlParams['group'];
	    
    ?>
	<div id='fb-theme' class="filter-block">
		
		<div class="filter_heading">
			<div class="left">Тематика</div>     
			<div style="text-align:center;padding: 0 0 0 10px;" class="left">    	
    			<a class="fc" id='theme-full-show' href="#" onclick="full_filter('theme');return false;"></a>
    		</div>        			
			<div class="right"><a class="fc" href="#" onclick="clear_filter('theme');return false;">Сбросить</a></div>
		</div>    
		
		<ul class="filter_heading_ul <?=$style?>" id='ul-theme'>
			<div id="filter-grouptheme_container" class="filter-grouptheme_container_1">
				<?php 
				foreach ($tpl['filter']['theme']['filter'] as $filter) {
					$themeUrlParams['theme'] = array($filter['filter_id']=>$filter['name']);
					?>            
					<div class="checkbox">
                        <input type="checkbox" name="themes[]" value="<?=$filter['filter_id']?>" <?=(is_array($themes)&&in_array($filter['filter_id'], $themes))?"checked":""?>/>
					       <a href="<?=urlBuild::makePrettyUrl($themeUrlParams,"http://www.numizmatik.ru/news")?>"> <?=$filter['name'];?></a>
					</div>
				<?}?>        
    		</div>
    	</ul> 	
	</div>
   <?php 
}?>
