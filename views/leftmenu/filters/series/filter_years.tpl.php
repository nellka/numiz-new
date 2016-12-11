<?
$a_href_nominal = '';
foreach ($nominals as $value) {
	$a_href_nominal.='&nominals[]='.$value;
}?>

<div id='fb-<?=$tpl['filter']['years']['filter_group_id_full']?>' class="filter-block">
	<div class="filter_heading">
		<div class="left">Года</div>  
		<div style="text-align:center;padding: 0 0 0 20px;" class="left">    	
			 <a class="fc" id='years_p-full-show' href="#" onclick="full_filter('years_p');return false;"></a>
		</div>       

		<div class="right"><a class="fc" href="#" onclick="clear_filter('<?=$tpl['filter']['years']['filter_group_id_full']?>');return false;">Сбросить</a></div>
	</div>    
	
	<ul class="filter_heading_ul">
		<div id="filter-groupyears_p_container" class="filter-groupyears_p_container_1">	     
			<?php 
			$i=0;								
			foreach ($tpl['filter']['years']['filter'] as $filter) {				
				?>    			

					<div class="checkbox">				
					<input type="checkbox" name="years[]" value="<?=$filter['filter_id']?>" <?=(is_array($years)&&in_array($filter['filter_id'], $years))?"checked":""?> />                        
					<a href="<?=$r_url?>?years[]=<?=$filter['filter_id']?><?=$a_href_group?><?=$a_href_nominal?>"> <?=$filter['name'];?></a>
					</div>	
	      <?}
	      ?>
	     
	  </div>
   </ul> 	
</div>   
