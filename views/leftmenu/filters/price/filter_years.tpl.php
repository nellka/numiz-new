<? 
if($tpl['filter']['years']){
    $yearsUrlParams = array();
	$yearsUrlParams['group'] = $urlParams['group'];	
    $yearsUrlParams['nominal'] = $urlParams['nominal'];   
    /*
    $yearsUrlParams['usershopcoinssubscribe'] = $urlParams['usershopcoinssubscribe'];
	$yearsUrlParams['usercatalogsubscribe'] = $urlParams['usercatalogsubscribe'];
	$yearsUrlParams['usermycatalog'] = $urlParams['usermycatalog'];
	$yearsUrlParams['usermycatalogchange'] = $urlParams['usermycatalogchange'];
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
    				<?if($tpl['filter']['years']['filter_group_id_full']=='years_p'){
    					$yearsUrlParams['years_p'] = array(0=>$filter['filter_id']);   
    					//подключаем отдельный вид фильтра  ?>		
    					<div class="checkbox">				
    					<input type="checkbox" name="years_p[]" value="<?=$filter['filter_id']?>" <?=(is_array($years_p)&&in_array($filter['filter_id'], $years_p))?"checked":""?> />                        
    					<a href="<?=urlBuild::makePrettyUrl($yearsUrlParams,$r_url)?>"> <?=$filter['name'];?></a>
    					</div>											
    				<?} else {
    					$yearsUrlParams['years'] = array(0=>$filter['filter_id']);   
    					
    					if($i==0){echo "<div class='left yc'>";}
    					?> 
    					<div class="checkbox">           
                        <input type="checkbox" name="years[]" value="<?=$filter['filter_id']?>" <?=(is_array($years)&&in_array($filter['filter_id'], $years))?"checked":""?> />
    					<a href="<?=urlBuild::makePrettyUrl($yearsUrlParams,$r_url)?>"> <?=$filter['name'];?></a>
    					</div>						
    				<?
    					$i++;
    					if($i==$count_for_column){
    						echo "</div>";
    						$i=0;
    					}
    				}?> 
		      <?}
		      if($i>0) echo "</div>";?>
		      
		      
		  </div>
	   </ul> 
				<div style="padding: 15px 0 0;">
					От <input type="text" id="amount-years0" name="fields_filter[amount-years0][0]" value="<?=$yearstart?$yearstart:$tpl['filter']['yearstart']?>" size="6"/>
					до <input type="text" id="amount-years1" name="fields_filter[amount-years1][1]" value=" <?=$yearend?$yearend:$tpl['filter']['yearend']?>" size="6"/>
					<a href="#" onclick="sendDataCatalog(null,null,'<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');return false" class="right button25">OK</a>
				</div> 
  	
	</div>   
<?*/
?>
<div class="clearfix"><br>
<b><?=$nominalMainTitle?></b><br>
<br><table border=0 cellpadding=3 cellspacing=1>
	
<tr bgcolor=#ffcc66 class=tboard><td class="tboard"><strong>Года:</strong></td>



<?	foreach ($tpl['filter']['years']['filter'] as $filter) {
	    unset($yearsUrlParams['simbol']);
	    $yearsUrlParams['years'] = array(0=>$filter["name"]);
	?>
		<td align=center class="tboard">
		<? if(in_array($filter['filter_id'], $years)){?>
			<strong><?= $filter["name"]?></strong>		
		<?} else {?>
			<a href="<?=urlBuild::makePrettyUrl($yearsUrlParams,$r_url)?>"> <?=$filter['name'];?></a>
		<?}?>
		
	<?}?>
	</tr>	
	<tr bgcolor=#ebe4d4 class=tboard><td class="tboard"><strong>Буквы:</strong>	
	<?	foreach ($tpl['filter']['years']['filter'] as $filter) {
		$yearsUrlParams['years'] = array(0=>$filter["name"]);
		?>		
			<td valign=top align=right class="tboard">
			<?foreach ($filter['simbols'] as $value2) {	
				$yearsUrlParams['simbol'] = array($value2['simbol']=>$value2['name']);	
				if(in_array($value2['simbol'], $simbols)&&in_array($filter['filter_id'], $years)){?>
					<strong><?= $value2['name']?></strong>		
				<?} else {?>
					<a href="<?=urlBuild::makePrettyUrl($yearsUrlParams,$r_url)?>"> <?=$value2['name'];?></a>
				<?}
			}?>	
			</td>	
		<?}?>
	</tr>
	</table>
</div>	

<?}?>