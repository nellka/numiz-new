<?

		include($cfg['path'].'/views/shopcoins/item/item.tpl.php');
		$textoneclick = '';
		unset ($details);
		echo "<table border=0 cellpadding=2 cellspacing=0 width=98%>";
		echo "<tr bgcolor=#99CCFF>
		<td class=tboard colspan=2><b>".$rows_main["name"]."</b></td></tr>";
		echo "<tr><td class=tboard>";
		//для яркости - для монет и бон
		include($cfg['path'].'/views/shopcoins/item/imageBig.tpl.php');
		
		echo "</td></form></tr>
		
		<tr><td class=tboard><table border=0 cellpadding=0 cellspacing=0 width=100% class=tboard><tr class=tboard><td class=tboard width=60%> ";
		
		if ($materialtype==1||$materialtype==2||$materialtype==2||$materialtype==4||$materialtype==7 || $materialtype==8 || $materialtype==6)
		{
			$details .= "<br>Страна:  <a href=index.php?group=".$rows_main["group"]."&materialtype=".$rows_main["materialtype"]." title='Посмотреть все ".$rows_main["gname"]."'><strong><font color=blue>".$rows_main["gname"]."</font></strong></a>
			<br>".($materialtype==8||$materialtype==6?"Монета":"Название").": <strong>".$rows_main["name"]."</strong>
			<br>Номер: <strong>".$rows_main["number"]."</strong>";
			$details .= "<br>".($rows_main["oldprice"]>0?($rows_main["materialtype"]==8||$rows_main["materialtype"]==6?"Старая цена":"Старая стоимость").": <strong><s>".round($rows_main["oldprice"],2)." руб.</s></strong>
			<br>".($rows_main["materialtype"]==8||$rows_main["materialtype"]==6?"Новая цена":"Новая стоимость").": <strong><font color=red>".($rows_main["price"]==0?"бесплатно":round($rows_main["price"],2)." руб.")."</font></strong>":
			($rows_main["materialtype"]==8||$rows_main["materialtype"]==6?"Цена":"Стоимость").": <strong><font color=red>".($rows_main["price"]==0?"бесплатно":round($rows_main["price"],2)." руб.")."</font></strong>".($rows_main["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!\")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!' >".($rows_main["materialtype"]==8||$rows_main["materialtype"]==6?"Цена":"Стоимость")."<b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red>".round($rows_main["clientprice"],2)." руб.</font></strong></a>":""))."";
			
			if ($rows_main['price1'] && $rows_main['amount1'] && !$parent) {
				
					$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
					<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>Оптовая цена:
					<td class=tboard>Кол-во<td class=tboard>".$rows_main['amount1']; "</tr>";
					$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>Цена<td class=tboard>".$rows_main['price1'];
					if ($rows_main['price2'] && $rows_main['amount2']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount2'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price2'];
					}
					if ($rows_main['price3'] && $rows_main['amount3']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount3'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price3'];
					}
					if ($rows_main['price4'] && $rows_main['amount4']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount4'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price4'];
					}
					if ($rows_main['price5'] && $rows_main['amount5']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount5'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price5'];
					}
					$details .= $tmpbody1."</tr>".$tmpbody2."</tr></table>"; 
				}
			if (trim($rows_main["metal"])) $details .= "<br>Металл: <strong>".$rows_main["metal"]."</strong>";
			if ($rows_main["width"] && $rows_main["height"]) $details .= "<br>Приблизительный размер: <strong>".$rows_main["width"]."*".$rows_main["height"]." мм.</strong>";
			if ($rows_main["year"]) $details .= "<br>Год: <strong>".$rows_main["year"]."</strong>";
			if ($rows_main["condition"]) $details .= "<br>Состояние: <strong><font color=blue>".$rows_main["condition"]."</font></strong>";
			
			if ($rows["series"])
			{
				$sql_series = "select * from shopcoinsseries where shopcoinsseries='".$rows["series"]."';";
				$result_series = mysql_query($sql_series);
				$rows_series = mysql_fetch_array($result_series);
				$details .= "<br>Серия монет: <a href=$script?series=".$rows["series"]."&group=".$rows["group"].">".$rows_series["name"]."</a>";
			}
			
			unset ($shopcoinstheme);
			$strtheme = decbin($rows_main["theme"]);
			$strthemelen = strlen($strtheme);
			$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
			for ($k=0; $k<$strthemelen; $k++)
			{
				if ($chars[$k]==1)
				{
					$shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
					if (!in_array(($strthemelen-1-$k), $ShopcoinsThemeArray))
						$ShopcoinsThemeArray[] = ($strthemelen-1-$k);
				}
			}
			
			if (sizeof($shopcoinstheme))
				$details .= "<br>Тематика: <strong>".implode(", ", $shopcoinstheme)."</strong>";
			
			if ($rows_main["materialtype"]!=7)
			{
				if (trim($rows_main["details"]))
					$details .= "<br>Описание: ".str_replace("\n","<br>",$rows_main["details"]);
			}
			else
			{
				if (trim($rows_main["details"]))
					$details .= "<br>Описание: <br>".$rows_main["details"];
			}
			$details .= '<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script><br><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none"data-yashareQuickServices="vkontakte,odnoklassniki,yaru,facebook,moimir"></div>';
			// vbhjckfd start
	
			$reservedForSomeUser = (1>2);
			$reservedForSomeGroup =  $rows_main['timereserved'] > time() ; // group, lower priority than personal
			$isInRerservedGroup = null;
			
			if($cookiesuser && $reservedForSomeGroup && !$reservedForSomeUser)
			{
				$isInRerservedGroup = isInRerservedGroup($cookiesuser, $rows_main["shopcoins"]);
			}
			
			$rows_amount = mysql_fetch_array($result_amount);
			$reserveamount = 0;
			$statusshopcoins = 0;
			$reserveuser = 0;
			$reservealluser = 0;
			
			if ( $rows_amount["reserve"] > 0 && ( time() - (int) $rows_amount["reserve"] < $reservetime ) ) { 
						
				$reservedForSomeUser = ( $rows_amount["reserve"] > 0 && ( time() - (int) $rows_amount["reserve"] < $reservetime ) );
				
				if ($reservealluser < $rows_amount["reserve"]) 
					$reservealluser=$rows_amount["reserve"];
				
				$reserveamount++;
				
				if ($rows_amount["reserve"] > 0 and $rows_amount["reserveorder"] == $shopcoinsorder) {
					
					if ($reserveuser < $rows_amount["reserve"]) 
						$reserveuser=$rows_amount["reserve"];
					
					$statusshopcoins = 1;
				}
			}
			
			$statusopt = 0;
						
			if ($rows_main['price1'] && $rows_main['amount1'] && ($rows_main['amount'] -$reserveamount)>=$rows_main['amount1']) 
				$statusopt = 1;
			
			if (!$reserveuser && $reservealluser) $reserveuser=$reservealluser;
			
			if ($statusshopcoins)
				echo "<img src=".$in."images/corz7.gif border=0 alt='Уже в корзине'>";
			elseif ( $reservedForSomeUser || (!$cookiesuser && $reservedForSomeGroup) || (false === $isInRerservedGroup) )
				echo "<img src=".$in."images/corz6.gif border=0 alt='Покупает другой посетитель монету ".$rows_main["gname"]." ".$rows_main["name"]."'>";
			elseif($statusopt>0 && $amountimages==1 && !$parent) {
						
				echo "
						<input type=text name=amount".$rows_main["shopcoins"]." size=4 class=formtxt value='0'> 
						<a href='#coin".$rows_main["shopcoins"]."' onclick='javascript:AddAccessory(".$rows_main["shopcoins"].")' title='Положить в корзину монету ".$rows_main["name"]."'><div id=bascetshopcoins".$rows_main["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='Положить в корзину монету ".$rows_main["name"]."'></div></a></form>
						";
				
			}
			else
			{
				if ($tpl['user']['can_see']) {
					echo "<div id=bascetshopcoin".($i+1)."><div id=bascetshopcoins".$rows_main["shopcoins"]." ><a href='#coin".$rows_main["shopcoins"]."' onclick=\"javascript:AddBascetTwo('".$rows_main["shopcoins"]."','1','".($i+1)."');\" rel=\"nofollow\" title='Купить монету ".$rows_main["gname"]." ".$rows_main["name"]."'><img src=".$in."images/corz1.gif border=0 alt='Купить монету ".$rows_main["gname"]." ".$rows_main["name"]."'></a></div></div>";
					if ($minpriceoneclick<=$rows_main['price']) $textoneclick = " <a href=#coinone".($i+1)." onclick=\"ShowOneClick(".$rows_main["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows_main['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows_main["name"]." ".intval($rows_main["price"]))." руб.',".($i+1).");\"><div  id=oneshopcoins".$rows_main["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><div id=oneshopcoin".($i+1)."></div><a name=coinone".($i+1)."></a>";
				}
				elseif ($rows_main["check"] == 0)
					echo "<strong><font color=red>Продана1</font></strong>";
			}
			
			echo $textoneclick;
				
			if ($reservedForSomeUser)
				echo "<br><font color=gray size=-2>Бронь до ".date("H:i", $reserveuser+$reservetime)."</font>";
			elseif($reservedForSomeGroup) {
				
				if($isInRerservedGroup) {
					echo '<br><font color=#ff0000 size=-2>На монету вы оставили заявку через <a href=../catalognew target=_blank style="font-size:9px;">каталог</a>. Она доступна вам для покупки.</font>';
				}
				else {
					echo '<br><font color=gray size=-2>На '.($rows_main["materialtype"]==8||$rows_main["materialtype"]==6?"монету":($rows_main["materialtype"]==2?"банкнота(бону)":"набор монет")).' была оставлена заявка через <a href=../catalognew target=_blank style="font-size:9px;" title=\'Каталог монет России, Германии, США и других стран\'>каталог</a>. '.($rows["materialtype"]==8||$rows_main["materialtype"]==6?"монету":($rows["materialtype"]==2?"банкноту(бону)":"набор монет")).' до '. date("H:i",$rows_main['timereserved']) .' могут заказать только клиенты, оставившие заявку.</font>';
				}			
			}
					
			
			// vbhjckfd stop
			
			if ($rows_main["dateinsert"]>time()-86400*180)
				$details .= "<br>Добавлено: <strong>".($rows_main["dateinsert"]>time()-86400*14?"<b><font color=red>NEW</font></b>":date("Y-m-d", $rows_main["dateinsert"]))."</strong>";
				
				
		}
		elseif ($materialtype==3) {
			$details .= "
			<script language=JavaScript>
			function AddAccessory(shopcoins)
			{
				var str;
				str = \"document.mainform.amount\" + shopcoins + \".value\";
				document.mainform.shopcoinsorder.value = shopcoins;
				document.mainform.shopcoinsorderamount.value = eval(str);
				//alert (eval(str) + shopcoins);
				if (eval(str) > 0)
				{
					//document.mainform.submit();
					// vbhjckfd1
					process ('addbascet.php?shopcoinsorder=".$shopcoinsorder."&shopcoins=' + shopcoins + '&amount=' + eval(str) + '". cookiesWork() ? '' : '&'.SID ."');
				}
				else
					alert ('Введите количество');
			}
			
			</script>
		
			<form action=index.php?pageinfo=cookies&typeorder=1&pagenum=$pagenum".($search?"&search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]." method=post name=mainform>
			<input type=hidden name=shopcoinsorder value=''>
			<input type=hidden name=page value='$page'>
			<input type=hidden name=catalog value='$catalog'>
			".($searchid?"<input type=hidden name=searchid value='$searchid'>":"")."
			<input type=hidden name=shopcoinsorderamount value=''>
			Группа:  <strong><font color=blue>".$rows_main["gname"]."</font></strong>
			<br>Название: <strong>".$rows_main["name"]."</strong>
			".($rows_main["number"]?"<br>Номер:<strong> ".$rows_main["number"]."</strong>":"")."";
			$details .= "<br>".($rows_main["oldprice"]>0?"Старая цена: <strong><s>".round($rows_main["oldprice"],2)." руб.</s></strong>
			<br>Новая цена: <strong><font color=red>".($rows_main["price"]==0?"бесплатно":round($rows_main["price"],2)." руб.")."</font></strong>":
			"Цена: <strong><font color=red>".($rows_main["price"]==0?"бесплатно":round($rows_main["price"],2)." руб.")."</font></strong>".($rows_main["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!\")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!' >Цена<b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red>".round($rows_main["clientprice"],2)." руб.</font></strong></a>":""));
			
			if ($rows_main['price1'] && $rows_main['amount1']) {
				
					$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
					<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>Оптовая цена:
					<td class=tboard>Кол-во<td class=tboard>".$rows_main['amount1']; "</tr>";
					$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>Цена<td class=tboard>".$rows_main['price1'];
					if ($rows_main['price2'] && $rows_main['amount2']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount2'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price2'];
					}
					if ($rows_main['price3'] && $rows_main['amount3']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount3'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price3'];
					}
					if ($rows_main['price4'] && $rows_main['amount4']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount4'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price4'];
					}
					if ($rows_main['price5'] && $rows_main['amount5']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount5'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price5'];
					}
					$details .= $tmpbody1."</tr>".$tmpbody2."</tr></table>"; 
				}
			
			if (trim($rows_main["accessoryProducer"])) $details .= "<br>Производитель: <strong>".$rows_main["accessoryProducer"]."</strong>";
			if ($rows_main["accessoryColors"]) $details .= "<br>Цвета: <strong>".$rows_main["accessoryColors"]."</strong>";
			if ($rows_main["accessorySize"]) $details .= "<br>Размеры: <strong><font color=blue>".$rows_main["accessorySize"]."</font></strong>";
			if (trim($rows_main["details"]))
				$details .= "<br>Описание: <strong>".str_replace("\n","<br>",$rows_main["details"])."</strong>";
			
			$details .= '<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script><br><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none"data-yashareQuickServices="vkontakte,odnoklassniki,yaru,facebook,moimir"></div>';
			
			$details .= "<br>";
			if (sizeof($ourcoinsorder) and in_array($rows_main["shopcoins"], $ourcoinsorder)){
				$details .= "
				<input type=text name=amount".$rows_main["shopcoins"]." id=amount".$rows_main["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows_main["shopcoins"]]."'> 
				<a href='#'>
				<div id=bascetshopcoins".$rows_main["shopcoins"].">
				<img src=".$in."images/corz7.gif border=0 alt='Уже в корзине' onclick='javascript:AddAccessory(".$rows_main["shopcoins"].")'></div>
				</a>";
			} else {
				if ($tpl['user']['can_see']) {
					$details .= "
					<input type=text name=amount".$rows_main["shopcoins"]." id=amount".$rows_main["shopcoins"]." size=4 class=formtxt value='0'> 
					<div id=bascetshopcoins".$rows_main["shopcoins"]."><a href='#'><img src=".$in."images/corz1.gif border=0 alt='В корзину' onclick='javascript:AddAccessory(".$rows_main["shopcoins"].")'></a></div>
					";
				}
				//javascript:AddAccessory(\"$script?pageinfo=cookies&shopcoinsorder=".$rows_main["shopcoins"]."&typeorder=1&pagenum=$pagenum&pricevalue=$pricevalue&".($search?"search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows_main["shopcoins"]."\"
			}
			
		} elseif ($materialtype==5) {
			$details .= "
			<script language=JavaScript>
			function AddAccessory(shopcoins)
			{
				var str;
				str = \"document.mainform.amount\" + shopcoins + \".value\";
				document.mainform.shopcoinsorder.value = shopcoins;
				document.mainform.shopcoinsorderamount.value = eval(str);
				//alert (eval(str) + shopcoins);
				if (eval(str) > 0)
				{
					//document.mainform.submit();
					process ('addbascet.php?shopcoinsorder=".$shopcoinsorder."&shopcoins=' + shopcoins + '&amount=' + eval(str));
				}
				else
					alert ('Введите количество');
			}
			
			</script>
		
			<form action=index.php?pageinfo=cookies&typeorder=1&pagenum=$pagenum".($search?"&search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]." method=post name=mainform>
			<input type=hidden name=shopcoinsorder value=''>
			<input type=hidden name=page value='$page'>
			<input type=hidden name=catalog value='$catalog'>
			".($searchid?"<input type=hidden name=searchid value='$searchid'>":"")."
			<input type=hidden name=shopcoinsorderamount value=''>
			Группа:  <strong><font color=blue>".$rows_main["gname"]."</font></strong>
			<br>Название: <strong>".$rows_main["name"]."</strong>
			".($rows_main["number"]?"<br>Номер:<strong> ".$rows_main["number"]."</strong>":"")."";
			$details .= "<br>".($rows_main["oldprice"]>0?"Старая цена: <strong><s>".round($rows_main["oldprice"],2)." руб.</s></strong>
			<br>Новая цена: <strong><font color=red>".($rows_main["price"]==0?"бесплатно":round($rows_main["price"],2)." руб.")."</font></strong>":
			"Цена: <strong><font color=red>".($rows_main["price"]==0?"бесплатно":round($rows_main["price"],2)." руб.")."</font></strong>".($rows_main["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!\")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!' >Цена<b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red>".round($rows_main["clientprice"],2)." руб.</font></strong></a>":""));
			
			if ($rows_main['price1'] && $rows_main['amount1']) {
				
					$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
					<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>Оптовая цена:
					<td class=tboard>Кол-во<td class=tboard>".$rows_main['amount1']; "</tr>";
					$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>Цена<td class=tboard>".$rows_main['price1'];
					if ($rows_main['price2'] && $rows_main['amount2']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount2'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price2'];
					}
					if ($rows_main['price3'] && $rows_main['amount3']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount3'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price3'];
					}
					if ($rows_main['price4'] && $rows_main['amount4']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount4'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price4'];
					}
					if ($rows_main['price5'] && $rows_main['amount5']) {
					
						$tmpbody1 .= "<td class=tboard>".$rows_main['amount5'];
						$tmpbody2 .= "<td class=tboard>".$rows_main['price5'];
					}
					$details .= $tmpbody1."</tr>".$tmpbody2."</tr></table>"; 
				}
			
			if (trim($rows_main["accessoryProducer"])) $details .= "<br>ISBN: <strong>".$rows_main["accessoryProducer"]."</strong>";
			if ($rows_main["accessoryColors"]) $details .= "<br>Год выпуска: <strong>".$rows_main["accessoryColors"]."</strong>";
			if ($rows_main["accessorySize"]) $details .= "<br>Количество страниц: <strong><font color=blue>".$rows_main["accessorySize"]."</font></strong>";
			
			$details .= "<br>";
			if (sizeof($ourcoinsorder) and in_array($rows_main["shopcoins"], $ourcoinsorder))
				$details .= "
				<input type=text name=amount".$rows_main["shopcoins"]." id=amount".$rows_main["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows_main["shopcoins"]]."'> 
				<a href='#' onclick='javascript:AddAccessory(".$rows_main["shopcoins"].")'><div id=bascetshopcoins".$rows_main["shopcoins"].">
				<img src=".$in."images/corz7.gif border=0 alt='Уже в корзине'></div>
				</a>";
			else {
				if ($tpl['user']['can_see']) {
					$details .= "
					<input type=text name=amount".$rows_main["shopcoins"]." size=4 class=formtxt value='0'> 
					<div id=bascetshopcoins".$rows_main["shopcoins"]."><a href='#'><img src=".$in."images/corz1.gif border=0 alt='В корзину' onclick='javascript:AddAccessory(".$rows_main["shopcoins"].")'></a></div>
					";
				}
				//javascript:AddAccessory(\"$script?pageinfo=cookies&shopcoinsorder=".$rows_main["shopcoins"]."&typeorder=1&pagenum=$pagenum&pricevalue=$pricevalue&".($search?"search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows_main["shopcoins"]."\"
			}
			if (trim($rows_main["details"]))
				$details .= "<br>Описание: ".str_replace("\n","<br>",$rows_main["details"]);
			
			$details .= '<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script><br><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none"data-yashareQuickServices="vkontakte,odnoklassniki,yaru,facebook,moimir"></div>';
		}
		
		
		echo $details.(($amountimages<2 && !$parent)?$details22:"");
		//echo $ciclelink;

		if($materialtype == 6){
		
			$sqlcicle = "SELECT * FROM shopcoins WHERE materialtype = $materialtype and shopcoins.group = '".$rows_main["group"]."' ORDER BY RAND() LIMIT 3";
			//echo $sqlcicle;
			$resultcicle = mysql_query($sqlcicle);
			$ciclelink = "<br><strong>Похожие позиции в магазине:</strong><br>";

			while ($rowsp = mysql_fetch_array($resultcicle))		{	
					$ciclelink .= 	"<a href=index.php?page=show&group=".$rowsp['group']."&materialtype=".$rowsp['materialtype']."&catalog=".$rowsp['shopcoins'].">Монета – ".$rows_main["gname"]." – ".$rowsp['name'].($rowsp['metal']? " – ".$rowsp['metal'] : '')." – ".$rowsp['year']." год</a><br>";

			}

			$ciclelink .="";
		}

		echo "</td></tr></table>$ciclelink</td></tr>";
		echo "</table>";?>