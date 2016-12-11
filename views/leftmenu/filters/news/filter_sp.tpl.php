<?
if (isset($tpl['filter']['groups'])) {    
    $spUrlParams = array();	    
    $spUrlParams['text'] = $text;	    
    ?>
	<div class="filter-block" id='fb-groups'>
		<div class="filter_heading">
			<div class="left">Поиск</div>
			<div class="right">    			  
				<a class="fc" href="#" onclick="clear_filter('sp_s');return false;">Сбросить</a>
			</div>			
		</div>
        <input type="text" value="<?=$text?>" id='text'  placeholder='Слово для поиска новости' name="text" size="30">
        <a class="right button25" onclick="$('#search-news').submit();return false" href="#" style="min-width: 25px;">OK</a>
        		
		<ul class="filter_heading_ul">
			<div id="filter-groupsp_container">			
			<?php 
			foreach ($filter_group['filter'] as $filter) {
				$spUrlParams['sp'] = array('0'=>$filter['filter_id']);
				?>            
				<div class="checkbox">
					<?php       
					$checked = ""    ;
					if (is_array($sp_s)&&in_array($filter['filter_id'], $sp_s)){
						$checked = 'checked="checked"';
					}?>
					<input type="checkbox" name="sp_s[]" value="<?=$filter['filter_id']?>" <?=$checked?> />
					<a href="<?=urlBuild::makePrettyUrl($spUrlParams,"http://www.numizmatik.ru/news")?>"> <?=$filter['name'];?></a>			
				</div>
			<?php				
			}?>  
			</div>			
		</ul> 		  
	</div>
   <?php 
}?>
