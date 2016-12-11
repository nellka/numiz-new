<? //if($tpl['filter']['price']['max']&&$tpl['filter']['price']['max']!=$tpl['filter']['price']['min']){?>
    <div id='filter-price' class="filter-block">	
        <div class="filter_heading">	
    		<div class="left"><b>Цена</b></div> 
    		<div class="right"><a class="fc" href="#" onclick="clear_filter('price');return false;">Сбросить</a></div>
			
    		<!--<div class="right">	<a style="color:#247bbd;font-size:12px;line-height:12px;text-decoration:underline;" href="">Сбросить</a></div> 		-->
		</div>		
		<div>
    		От <input type="text" id="amount-price0" name="fields_filter[amount-price0][0]" value="<?=$pricestart?$pricestart:$tpl['filter']['price']['min']?>" size="5"/>
    		до <input type="text" id="amount-price1" name="fields_filter[amount-price1][1]" value=" <?=$priceend?$priceend:$tpl['filter']['price']['max']?>" size="5"/> руб.
    		<a href="#" onclick="sendData(null,null,'<?=$tpl['filter']['price']['min']?>','<?=$tpl['filter']['price']['max']?>','<?=$tpl['filter']['yearstart']?>','<?=date("Y",time())?>');return false;" class="right button25">OK</a>
		</div>				
   </div>	
<?//}?>