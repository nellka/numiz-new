<?
$priceUrlParams = array();
$priceUrlParams['group'] = $urlParams['group'];
$priceUrlParams['nocheck'] = $urlParams['nocheck'];
$priceUrlParams['mycoins'] = $urlParams['mycoins'];
$priceUrlParams['materialtype'] = $urlParams['materialtype'];
?>
<div class="filter-block" id='fb-prices'>
	<div class="filter_heading">
		<div class="left">Цены</div> 
		<div style="text-align:center;padding: 0 0 0 10px;" class="left">    	
			<a class="fc" id='nominals-full-show' href="#" onclick="full_filter('prices');return false;"></a>
		</div>            			
		<div class="right"><a class="fc" href="#" onclick="clear_filter('prices');return false;">Сбросить</a></div>
	</div>  
	<? if($tpl['filter']['prices']){?>
	<ul class="filter_heading_ul">
		<div id="filter-groupprices_container" class="filter-groupprices_container_1">
		<?php 
		foreach ($tpl['filter']['prices']['filter'] as $filter) {
			$priceUrlParams['price'] = array(0=>$filter['filter_id']);
			
			?>            
			<div class="checkbox">
				   <input type="checkbox" name="prices[]" value="<?=$filter['filter_id']?>" <?=(is_array($prices)&&in_array($filter['filter_id'], $prices))?"checked":""?> /> 
				   <a href="<?=urlBuild::makePrettyUrl($priceUrlParams)?>"> <?=$filter['name'];?> руб.</a>						
			</div>
		 <?}?> 							
		</div>
	</ul>
  <?}?> 
</div>  