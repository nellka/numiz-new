<?
if (isset($tpl['filter']['theme'])) {
    $style = '';
    if(isset($tpl['filter']['metals'])&&$groups) $style .="s_m";
    if(isset($tpl['filter']['conditions'])) $style .="t";
    
    
    ?>
	<div id='fb-theme' class="filter-block">
		<div class="filter_heading" id='theme-header-close' style="display:<?=$show_theme?'none':'block'?>">
			<div class="left">Тематика</div>        			
			<div class="right"><a class="fc" href="#" onclick="ShowFilterB('theme',1);return false;">Развернуть</a></div>
		</div>    
		
		<div class="filter_heading" id='theme-header-open' style="display:<?=$show_theme?'block':'none'?>">
			<div class="left">Тематика</div>     
			<div style="text-align:center;padding: 0 0 0 10px;" class="left">    	
    			<a class="fc" id='theme-full-show' href="#" onclick="full_filter('theme');return false;"></a>
    		</div>        			
			<div class="right"><a class="fc" href="#" onclick="clear_filter('theme');return false;">Сбросить</a></div>
		</div>    
		
		<ul class="filter_heading_ul <?=$style?>" id='ul-theme' style="display:<?=$show_theme?'block':'none'?>">
			<div id="filter-grouptheme_container" class="filter-grouptheme_container_1">
				<?php 
				foreach ($tpl['filter']['theme']['filter'] as $filter) {?>            
					<div class="checkbox">
                        <input type="checkbox" name="theme[]" value="<?=$filter['filter_id']?>" <?=(is_array($themes)&&in_array($filter['filter_id'], $theme))?"checked":""?>/>
					       <a href="<?=$r_url?>?materialtype=<?=$materialtype?><?=$ahref?>&theme=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
					</div>
				<?}?>        
    		</div>
    	</ul> 	
	</div>
   <?php 
}?>
