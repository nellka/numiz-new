<? 
if($tpl['filter']['years']){
    $yearsUrlParams = array();
	//$yearsUrlParams['group'] = $urlParams['group'];	
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
				$count_for_column = ceil(count($tpl['filter']['years']['filter'])/4);
				$i=0;								
				foreach ($tpl['filter']['years']['filter'] as $filter) {				
				 	$yearsUrlParams['years'] = array(0=>$filter['filter_id']);     					
					if($i==0){echo "<div class='left yc'>";}
					?> 
					<div class="checkbox">           
                    <input type="checkbox" name="years[]" value="<?=$filter['filter_id']?>" <?=(is_array($years)&&in_array($filter['filter_id'], $years))?"checked":""?> />
					<a href="<?=urlBuild::makePrettyUrl($yearsUrlParams,"http://www.numizmatik.ru/news")?>"> <?=$filter['name'];?></a>
					</div>						
					<?
					$i++;
					if($i==$count_for_column){
						echo "</div>";
						$i=0;
					}
    			}
		      if($i>0) echo "</div>";?>   
		  </div>
	   </ul> 	
	</div>   
<?}?>