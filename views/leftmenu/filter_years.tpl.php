<? 
if($tpl['filter']['years']){
    $ahref .= $ahref_nominals;
    ?>

	<div id='fb-<?=$tpl['filter']['years']['filter_group_id_full']?>' class="filter-block">
		<div class="filter_heading">
			<div class="left">Года</div>  
			<div style="text-align:center;padding: 0 0 0 20px;" class="left">    	
    			 <a class="fc" id='years_p-full-show' href="#" onclick="full_filter('years_p');return false;"></a>
    		</div>       

			<div class="right"><a class="fc" href="#" onclick="clear_filter('<?=$tpl['filter']['years']['filter_group_id_full']?>');return false;">Сбросить</a></div>
		</div>    
		
		<ul class="filter_heading_ul">
			<div id="filter-group<?=$tpl['filter']['years']['filter_group_id']?>_container" class="filter-group<?=$tpl['filter']['years']['filter_group_id']?>_container_1">	     
				<?php 
				$count_for_column = ceil(count($tpl['filter']['years']['filter'])/3);
				$i=0;								
				foreach ($tpl['filter']['years']['filter'] as $filter) {				
					?>    				
    				<?if($tpl['filter']['years']['filter_group_id_full']=='years'){
    					//подключаем отдельный вид фильтра  ?>		
    					<div class="checkbox">				
    					<input type="checkbox" name="years[]" value="<?=$filter['filter_id']?>" <?=(is_array($years)&&in_array($filter['filter_id'], $years))?"checked":""?> />                        
    					<a href="<?=$r_url?>?materialtype=<?=$materialtype?><?=$ahref?>&years[]=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
    					</div>											
    				<?} else {
    					if($i==0){echo "<div class='left yc'>";}
    					?> 
    					<div class="checkbox">           
                        <input type="checkbox" name="years_p[]" value="<?=$filter['filter_id']?>" <?=(is_array($years_p)&&in_array($filter['filter_id'], $years_p))?"checked":""?> />
    					<a href="<?=$r_url?>?materialtype=<?=$materialtype?><?=$ahref?>&years_p=<?=$filter['filter_id']?>"> <?=$filter['name'];?></a>
    					</div>						
    				<?
    					$i++;
    					if($i==$count_for_column){
    						echo "</div>";
    						$i=0;
    					}
    				}?> 
		      <?}
		      if($i>0) echo "</div>";
		      
		      if($tpl['filter']['years']['filter_group_id_full']=='years'){?>   
				<div style="font-weight:bold;padding: 15px 0;">
					От <input type="text" id="amount-years0" name="fields_filter[amount-years0][0]" value="<?=$yearstart?$yearstart:$tpl['filter']['yearstart']?>" size="6"/>
					до <input type="text" id="amount-years1" name="fields_filter[amount-years1][1]" value=" <?=$yearend?$yearend:$tpl['filter']['yearend']?>" size="6"/>
					<a href="#" onclick="sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');return false" class="right button25">OK</a>
				</div> 
    		  <?}?>       
		  </div>
	   </ul> 	
	</div>   
<?}?>