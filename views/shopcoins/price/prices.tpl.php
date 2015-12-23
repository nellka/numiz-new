<div id=prices>
<?
	$price_text_old = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"Старая цена":"Старая стоимость";
	$price_text_new = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"Новая цена":"Новая стоимость";
	$price_text = ($rows["materialtype"]==8||$rows["materialtype"]==6)?"Цена":"Стоимость";
			
				
	if($rows["oldprice"]>0){?>
	    <?=$price_text_old?>: <strong><s><?=round($rows["oldprice"],2)?> руб.</s></strong><br>
	    <?=$price_text_new?>: <strong><font color=red><?=($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")?></font></strong>
	 <?}?>
        <?=$price_text?>: <strong><font color=red><?=($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")?></font></strong>
        <?if($rows["clientprice"]>0){?>
          <br><a href=# onclick='javascript:alert("Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!' >
             <?=$price_text?> <b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red><?=round($rows["clientprice"],2)?> руб.</font></strong></a>
        <?}

if ($rows['price1'] && $rows['amount1']) {

	$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
	<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>Оптовая цена:
	<td class=tboard>Кол-во<td class=tboard>".$rows['amount1']; "</tr>";
	$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>Цена<td class=tboard>".$rows['price1'];
	if ($rows['price2'] && $rows['amount2']) {
	
		$tmpbody1 .= "<td class=tboard>".$rows['amount2'];
		$tmpbody2 .= "<td class=tboard>".$rows['price2'];
	}
	if ($rows['price3'] && $rows['amount3']) {
	
		$tmpbody1 .= "<td class=tboard>".$rows['amount3'];
		$tmpbody2 .= "<td class=tboard>".$rows['price3'];
	}
	if ($rows['price4'] && $rows['amount4']) {
	
		$tmpbody1 .= "<td class=tboard>".$rows['amount4'];
		$tmpbody2 .= "<td class=tboard>".$rows['price4'];
	}
	if ($rows['price5'] && $rows['amount5']) {
	
		$tmpbody1 .= "<td class=tboard>".$rows['amount5'];
		$tmpbody2 .= "<td class=tboard>".$rows['price5'];
	}
	echo $tmpbody1."</tr>".$tmpbody2."</tr></table>"; 
}?>
</div>