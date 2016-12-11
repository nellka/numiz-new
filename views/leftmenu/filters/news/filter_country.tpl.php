<?

if (isset($tpl['filter']['groups'])) {    
    $groupUrlParams = array();	    
    $groupUrlParams['text'] = $text;	    
    ?>
	<div class="filter-block" id='fb-groups'>
		<div class="filter_heading">
			<div class="left">Страны</div>
			<div class="right">    			  
				<a class="fc" href="#" onclick="clear_filter('groups');return false;">Сбросить</a>
			</div>
		</div>
       
		<ul class="filter_heading_ul">
			<div id="filter-groupgroup_container">			
			<?php 
			foreach ($filter_group['filter'] as $filter) {
				$groupUrlParams['group'] = array($filter['filter_id']=>$filter['name']);
				?>            
				<div class="checkbox">
					<?php       
					$checked = ""    ;
					if (is_array($groups)&&in_array($filter['filter_id'], $groups)){
						$checked = 'checked="checked"';
					}?>
					<input type="checkbox" name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" <?=$checked?> />
					<a href="<?=urlBuild::makePrettyUrl($groupUrlParams,"http://www.numizmatik.ru/news")?>"> <?=$filter['name'];?></a>			
				</div>
			<?php				
			}?>  
			</div>			
		</ul> 		  
	</div>
   <?php 
}?>
