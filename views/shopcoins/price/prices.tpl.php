<div class='center' itemprop="offers" itemscope itemtype="http://schema.org/Offer">
<meta itemprop="priceCurrency" content="RUB">
<?
	$price_text_old = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"":"";
	$price_text_new = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"":"";
	$price_text = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"":"";
	$diff_price = round($rows["oldprice"],2) - $rows["price"];		

    $price_value = $rows["price"]==0?"бесплатно":round($rows["price"],2);
	if($rows["oldprice"]>0&&$diff_price>0){?>
	   <span class='oldprice'><s><?=round($rows["oldprice"],2)?></s></span>
	   
	 <?}?>
		<?php
		
		if($diff_price>0){
		?>
			<span class="n-price" itemprop="price">
				<?=$price_value.((!isset($rows['for_mini'])||!$rows['for_mini'])?" руб.":'')?>
			</span>
		<?php
		}else{
		?>
			<span class="n-price">
				<span itemprop="price"><?=$price_value?></span> руб.
			</span>
		<?php
		}
		?>

		<?php
		if($diff_price>0){
		?>
			 <span style="font-size:11px;font-weight:bold;background-color:#fffee7;color:#666666;padding: 5px; ">
				 <?=(!isset($rows['for_mini'])||!$rows['for_mini'])?"Выгода:":''?>
				 <?=$diff_price?> </span>
        <?
		}
		if($rows["clientprice"]>0&&(!isset($rows['for_mini'])||!$rows['for_mini'])){?>
           <?=$price_text?> <span style="font-size:11px;font-weight:bold;background-color:#fffee7;color:#666666;padding: 5px 0; " title="Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!"><?=round($rows["clientprice"],2)?> руб.</span>
        <?}?>
</div>