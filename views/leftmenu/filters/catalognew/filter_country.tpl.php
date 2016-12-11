<? 
$groupUrlParams = array();	    
$groupUrlParams['materialtype'] = $urlParams['materialtype'];
$groupUrlParams['usershopcoinssubscribe'] = $urlParams['usershopcoinssubscribe'];
$groupUrlParams['usercatalogsubscribe'] = $urlParams['usercatalogsubscribe'];
$groupUrlParams['usermycatalog'] = $urlParams['usermycatalog'];
$groupUrlParams['usermycatalogchange'] = $urlParams['usermycatalogchange'];
?>
<form id='search-params' method="POST" action="<?=$r_url?>" class="left">
<input type="hidden" id='orderby' name='orderby' value='<?=$tpl['orderby']?>'>
<input type="hidden" id='onpage' name='onpage' value='<?=$tpl['onpage']?>'>
<input type="hidden" id='pagenum' name='pagenum' value='<?=$tpl['pagenum']?>'>
<input type="hidden" id='yearstart' name='yearstart' value='<?=$yearstart?>'>
<input type="hidden" id='yearend' name='yearend' value='<?=$yearend?>'>
<input type="hidden" id='search_catalog' name='search_catalog' value='<?=$search_catalog?>'>
<input type="hidden" id="usershopcoinssubscribe" name="usershopcoinssubscribe" value="<?=$usershopcoinssubscribe?>">
<input type="hidden" id="usercatalogsubscribe" name="usercatalogsubscribe" value="<?=$usercatalogsubscribe?>">
<input type="hidden" id="usermycatalog" name="usermycatalog" value="<?=$usermycatalog?>">

<?

//if($tpl['user']['user_id']) include("filter_bydates.tpl.php");

if(isset($groups_filter)&&$groups_filter){
    ?>
    <div class="filter-block" id='fb-groups'>
		<div class="filter_heading">
			<div style="float:left;"><?=$groups_filter['name']?></div>
            <div style="float:left;padding: 0 0 0 40px;">  
             <?if($c_en){?>	
                <a class="fc" href="#" onclick="$.cookie('c_en',0);location.reload();return false;">RUS</a> | Krause
            <?} else {?>
                RUS | <a class="fc" href="#" onclick="$.cookie('c_en',1);location.reload();return false;">Krause</a>
            <?}?>
            </div>    		
			<div style="float:right;">    			  
				<a class="fc" href="#" onclick="clear_filter('groups');return false;">Сбросить</a>
			</div>
		</div>

		<div id='f-details' class="filter-groupdetails_container_<?=(count($filter_groups['group_details'])>5)?1:0?>">		
    		<?foreach ($filter_groups['group_details'] as $group_id=>$group_name){?>
    			<div class="left" id='clear_filter_group<?=$group_id.$search_href?>'><a href="#" class="filtr-g-d" onclick="clear_filter_group('<?=$group_id?>');return false;"><?=$group_name?> - X</a></div>     
    		<?}?>
		</div>
		<input type="text" value="" id='group_name' placeholder='Введите название страны' name="group_name" size="30">

		<ul class="filter_heading_ul">
			<div id="filter-groupgroup_container" class="filter-groupgroup_container_<?=(count($filter_group['filter'])>13)?1:0?>">
				<?php 
				foreach ($groups_filter['filter'] as $filter) {
					$groupUrlParams['group'] = array($filter['filter_id']=>$filter['name']);
					$checked = (is_array($groups)&&in_array($filter['filter_id'], $groups)) ? "checked":"";        
					?>            
					<div class="checkbox">						
						<input type="checkbox" <?=$checked?> name="groups[]" value="<?=$filter['filter_id']?>" onclick="addToFilterDetails('<?=$filter['filter_id']?>','<?=$filter['name'];?>')" />
					    <a href="<?=urlBuild::makePrettyUrl($groupUrlParams,"http://www.numizmatik.ru/catalognew")?>"> <?=$filter['name'];?></a>
						
					</div>
				    <?php
					if(isset($filter['child'])){?>
						<div style='margin:5px 20px;'>
						<?foreach ($filter['child'] as $filter_child) {
							$groupUrlParams['group'] = array($filter_child['filter_id']=>$filter_child['name']);
							$checked = (is_array($groups)&&in_array($filter_child['filter_id'], $groups)) ? "checked":"";   
					        ?>
							<div class="checkbox">    
								<input type="checkbox" <?=$checked?> name="groups[]" value="<?php echo $filter_child['filter_id']; ?>" onclick="addToFilterDetails('<?=$filter_child['filter_id']?>','<?=$filter_child['name'];?>')" />
							    <a href="<?=urlBuild::makePrettyUrl($groupUrlParams,"http://www.numizmatik.ru/catalognew")?>"> <?=$filter_child['name'];?></a>
							</div>
						<?}?>
						</div>					
				   <?}
				}?>		
			</div>	
        </ul> 
        <div style="text-align:center;padding: 10px 0 0;">    	
		    <a class="fc" id='group-full-show' href="#" onclick="full_filter('groups');return false;"></a>
        </div> 
    </div>  
 <?}?>
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




