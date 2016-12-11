<?
$nominalUrlParams = array();
$nominalUrlParams['group'] = $urlParams['group'];
$nominalUrlParams['nocheck'] = $urlParams['nocheck'];
$nominalUrlParams['mycoins'] = $urlParams['mycoins'];
$nominalUrlParams['materialtype'] = $urlParams['materialtype'];

?>
<div class="filter-block" id='fb-nominals'>
	<div class="filter_heading">
		<div class="left">Номиналы</div> 
		<div style="text-align:center;padding: 0 0 0 10px;" class="left">    	
			<a class="fc" id='nominals-full-show' href="#" onclick="full_filter('nominals');return false;"></a>
		</div>            			
		<div class="right"><a class="fc" href="#" onclick="clear_filter('nominals');return false;">Сбросить</a></div>
	</div>  
	<? if($tpl['filter']['nominals']){
	    
	    //<?=(count($tpl['filter']['nominals']['filter'])>13)?1:0?>  
	<ul class="filter_heading_ul">
		<div id="filter-groupnominal_container" class="filter-groupnominals_container_1">
		<?php 
		foreach ($tpl['filter']['nominals']['filter'] as $filter) {
			$nominalUrlParams['nominal'] = array($filter['filter_id']=>$filter['name']);
			?>            
			<div class="checkbox">
				   <input type="checkbox" name="nominals[]" value="<?=$filter['filter_id']?>" <?=(is_array($nominals)&&in_array($filter['filter_id'], $nominals))?"checked":""?> /> 
				   <a href="<?=urlBuild::makePrettyUrl($nominalUrlParams)?>"> <?=$filter['name'];?></a>						
			</div>
		 <?}?> 							
		</div>
	</ul>
  <?}?> 
</div>  