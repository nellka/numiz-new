<div id=prices style="text-align:center;">
<?
	$price_text_old = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"":"";
	$price_text_new = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"":"";
	$price_text = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"":"";
			
				
	if($rows["oldprice"]>0){?>
	   <span style="font-size:11px;font-weight:bold;#666666"><s><?=round($rows["oldprice"],2)?></s></span>
	   
	 <?}?>
		<?php
		$diff_price = round($rows["oldprice"],2) - $rows["price"];
		if($diff_price>0){
		?>
			<span class="n-price">
				<?=($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")?>
			</span>
		<?php
		}else{
		?>
			<span class="n-price">
				<?=($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")?>
			</span>
		<?php
		}
		?>

		<?php
		if($diff_price>0){
		?>
			 <span style="font-size:11px;font-weight:bold;background-color:#fffee7;color:#666666;padding: 5px; ">Выгода: <?=$diff_price?> </span>
        <?
		}
		if($rows["clientprice"]>0){?>
           <?=$price_text?> <span style="font-size:11px;font-weight:bold;background-color:#fffee7;color:#666666;padding: 5px; " title="Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!"><?=round($rows["clientprice"],2)?> руб.</span>
        <?}

if ($rows['price1'] && $rows['amount1']) {

	$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
	<tr bgcolor=#fff8e8><td rowspan=2 width=25%>Оптовая цена:
	<td>Кол-во<td>".$rows['amount1']; "</tr>";
	$tmpbody2 = "<tr bgcolor=#fff8e8><td>Цена<td>".$rows['price1'];
	if ($rows['price2'] && $rows['amount2']) {
	
		$tmpbody1 .= "<td>".$rows['amount2'];
		$tmpbody2 .= "<td>".$rows['price2'];
	}
	if ($rows['price3'] && $rows['amount3']) {
	
		$tmpbody1 .= "<td>".$rows['amount3'];
		$tmpbody2 .= "<td>".$rows['price3'];
	}
	if ($rows['price4'] && $rows['amount4']) {
	
		$tmpbody1 .= "<td>".$rows['amount4'];
		$tmpbody2 .= "<td>".$rows['price4'];
	}
	if ($rows['price5'] && $rows['amount5']) {
	
		$tmpbody1 .= "<td>".$rows['amount5'];
		$tmpbody2 .= "<td>".$rows['price5'];
	}
	echo $tmpbody1."</tr>".$tmpbody2."</tr></table>"; 
}?>
</div>