<?php
$r_url=$tpl['one_series']['url'];
?>
<form id='search-params' method="POST" action="<?=$r_url?>" class="one-series">
<input type="hidden" id='orderby' name='orderby' value='<?=$tpl['orderby']?>'>
<input type="hidden" id='onpage' name='onpage' value='<?=$tpl['onpage']?>'>
<input type="hidden" id='pagenum' name='pagenum' value='<?=$tpl['pagenum']?>'>

<?

//if($tpl['user']['user_id']) include("filter_bydates.tpl.php");

//if(isset($groups_filter)&&$groups_filter){
    ?>
    <div class="filter-block" id='fb-groups'>
		<div class="filter_heading">
			<div style="float:left;">Страны</div>
            <div style="float:left;padding: 0 0 0 40px;">  
             <?/*if($c_en){?>	
                <a class="fc" href="#" onclick="$.cookie('c_en',0);location.reload();return false;">RUS</a> | Krause
            <?} else {?>
                RUS | <a class="fc" href="#" onclick="$.cookie('c_en',1);location.reload();return false;">Krause</a>
            <?}*/?>
            </div>    		
			<div style="float:right;">    			  
				<a class="fc" href="#" onclick="clear_filter('groups');return false;">Сбросить</a>
			</div>
		</div>
		<!--<input type="text" value="" id='group_name' placeholder='Введите название страны' name="group_name" size="30">-->

		<ul class="filter_heading_ul">
			<div id="filter-groupgroup_container" class="filter-groupgroup_container_<?=(count($filter_group['filter'])>13)?1:0?>">
				<?php 
				foreach ($tpl['filter']['groups']['filter'] as $filter) {$checked = "";
						
						if(is_array($groups)&&in_array($filter['filter_id'], $groups)) $checked = "checked";
						
						?>								
						<div class="checkbox">								          
							 <input type="checkbox" name="groups[]" id="groups_<?=$filter['filter_id']?>" value="<?=$filter['filter_id']?>" <?=$checked?>/>
					
							<a href="<?=$r_url?>?groups[]=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
						 				
						</div>
					<?php 				   				
				}?>		
			</div>	
        </ul> 
        <div style="text-align:center;padding: 10px 0 0;">    	
		    <a class="fc" id='group-full-show' href="#" onclick="full_filter('groups');return false;"></a>
        </div> 
    </div>  
 <?//}?>
</form>
<script>function addToFilterDetails(id,name){
	if($("#fb-groups :checkbox[value="+id+"]").prop("checked")){ 	
		var newF = '<div class="left" id="clear_filter_group'+id+'"><a href="#" class="filtr-g-d" onclick="clear_filter_group(\''+id+'\');return false;">'+name+' - X</a></div>';	
 		$('#f-details').html($('#f-details').html()+newF);
 		
 	} else {
 		$("#clear_filter_group"+id).remove(); 	
 		//var last_node = '#yearend';
 		//console.log();
 	}
}</script>




