<? 
include(START_PATH."/config.php");

$r_url=$tpl['one_series']['url'];
?>
<form id='search-params' method="POST" action="<?=$r_url?>">
<input type="hidden" id='orderby' name='orderby' value='<?=$tpl['orderby']?>'>
<input type="hidden" id='onpage' name='onpage' value='<?=$tpl['onpage']?>'>
<input type="hidden" id='pagenum' name='pagenum' value='<?=$tpl['pagenum']?>'>
<div class='f-container'>
	
	<div class="box-heading">
		<div class="left">Фильтр товаров</div>
		<div class="clearf">
		<a onclick="clear_filter();return false;" href="#">Очистить</a>
		</div>
		<div class="right">
		<a class="closef" href="#" onclick="$('#search-params-place').hide();return false;"></a>
		</div>		
	</div>
	<br style="clear: both;">
	<?php   
	//вводим фильтр для стран
	 	foreach ($filter_groups as $k=>$filter_group) { 	
	 		if(!$filter_group) continue;
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
    		
		<ul class="filter_heading_ul">
			<div id="filter-group<?=$filter_group['filter_group_id']?>_container" class="filter-group<?=$filter_group['filter_group_id']?>_container_<?=(count($filter_group['filter'])>13)?1:0?>">
					<?php 
					foreach ($filter_group['filter'] as $filter) {
						$checked = "";
						
						if(is_array($$filter_group['filter_group_id_full'])&&in_array($filter['filter_id'], $$filter_group['filter_group_id_full'])) $checked = "checked";
						
						?>								
						<div class="checkbox">								          
							 <input type="checkbox" id=<?=$filter_group['filter_group_id_full']."_".$filter['filter_id']?> name="<?=$filter_group['filter_group_id_full']?>[]" value="<?=$filter['filter_id']?>" <?=$checked?>/>
						     <label for="<?=$filter_group['filter_group_id_full']."_".$filter['filter_id']?>"><?=$filter['name'];?></label>							
						</div>
						<?php
					}?> 
     
			</div>
		</ul> 		 
		</div>
	<?php 		
	 	}
	?>
 </div>  
</form>
<script type="text/javascript">

function  clear_filter(filter){	
	if(filter){
		$('input[name="'+filter+'[]"]:checked').attr('checked',false);
	} else {
		$('#search-news input').attr('checked',false);
		$('#text').val();
	}
    $(".bg_shadow").show();
    $('#search-params').submit();
	return false;
}
	
$(function(){
    $('#search-params input').bind('change',function(){
        $(".bg_shadow").show();
        $('#search-params').submit();				
    });   

});
</script>