<?	echo $tpl['paginator']->printPager(); 

//echo $shopcoinsorder."-".$amount;
		echo "		
		<table border=0 cellpadding=3 cellspacing=0 width=100%>
		<form action=$script?pageinfo=cookies&typeorder=1&pagenum=$pagenum".($search?"&search=".urlencode($search):"").($searchid?"&searchid=".$searchid:"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]." method=post name=mainform>
		<input type=hidden name=shopcoinsorder value=''>
		<input type=hidden name=shopcoinsorderamount value=''>
		";
		$i=1;
		//while ($rows = mysql_fetch_array($result))
		foreach ($MyShowArray as $key=>$rows)
		{
			$textoneclick='';
			
			$mtype = ($materialtype>0?$materialtype:$rows['materialtype']);
						
			if ($rows['gname'])
				$rehref .= $rows['gname']."-";
			$rehref .= $rows['name'];
			if ($rows['metal'])
				$rehref .= "-".$rows['metal']; 
			if ($rows['year'])
				$rehref .= "-".$rows['year'];
			$rehrefdubdle = strtolower_ru($rehref)."_c".($mtype==1?$rows['parent']:$rows['shopcoins'])."_pc".($parent>0?$parent:(($mtype==7 || $mtype==8 || $mtype==6 || $mtype==4 || $mtype==2) && $rows["amount"]>1?$rows['shopcoins']:($mtype==1?$rows['parent']:0)))."_m".$rows['materialtype']."_pp1.html";
			$rehref = strtolower_ru($rehref)."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";
			//echo $rehref."<br>";
			
			if ($i%2==1) {
				echo "<tr><td class=tboard valign=top width=50%>";
			} elseif ($i%2==0) {
				echo "<td class=tboard valign=top width=50%>";
			}
			
			$coefficient = 0;
			if ($rows['coefficientcoins']) 
				$coefficient = $coefficient+$rows['coefficientcoins'];
			if ($rows['coefficientgroup']) 
				$coefficient = $coefficient+$rows['coefficientgroup']*2;
			if ($rows['counterthemeyear']) 
				$coefficient = $coefficient+$rows['counterthemeyear'];
				
			if ($coefficient>0) {
			
				if ( $coefficient>$maxcoefficient)
					$maxcoefficient = $coefficient;
				
				$sumcoefficient = $sumcoefficient+$coefficient;
			}
			
			echo "<table border=0 cellpadding=2 cellspacing=0 width=100%>";
			echo "<tr bgcolor=#99CCFF>
			<td class=tboard width=80% colspan=2><a name=coin".$rows["shopcoins"]." title='".$rows["name"]."'></a><strong>".$rows["name"]."</strong></td></tr>";
			echo "<tr>
			<td class=tboard>
			<a href='$rehref' title='Подробнее о книге ".$rows["name"]."'>
			<img src=images/".$rows["image"]." alt='".$rows["name"]."' border=1 style='border-color:black'>
			</a>
			</td></tr><td class=tboard>";
			
			if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder))
			{
				echo "
				<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows["shopcoins"]]."'> 
				<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory_3_3(".$rows["shopcoins"].")' title='".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz7.gif border=0 alt='Уже в корзине'></div></a>";
			}
			else
			{
				if ($REMOTE_ADDR!="213.180.194.162" 
				and $REMOTE_ADDR!="213.180.194.133" 
				and $REMOTE_ADDR!="213.180.194.164" 
				and $REMOTE_ADDR!="213.180.210.2" 
				and $REMOTE_ADDR!="83.149.237.18"
				and $REMOTE_ADDR!="83.237.234.171"
				and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"ia_archiver")
				and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"coona")
				)
					echo "
					<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='0'> 
					<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory_3(".$rows["shopcoins"].")' title='Положить в корзину книгу ".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='Положить в корзину книгу ".$rows["name"]."'></div></a>
					";
					if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." руб.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
					//javascript:AddAccessory_3(\"$script?pageinfo=cookies&shopcoinsorder=".$rows["shopcoins"]."&typeorder=1&pagenum=$pagenum&pricevalue=$pricevalue&".($search?"search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]."\"
			}
			
			if (strlen($rows["details"])>250 or $rows["image_big"])
				echo "&nbsp;<a href='$rehref' title='Подробнее о книге ".$rows["name"]."'><img src=".$in."images/corz3.gif border=0 alt='Подробнее о книге ".$rows["name"]."'></a>";
			echo $textoneclick;	
			echo "<br>Группа:  <a href=$script?group=".$rows['group']."&materialtype=".$rows['materialtype']." title='Посмотреть все ".$rows["gname"]."'><strong><font color=blue>".$rows["gname"]."</font></strong></a>
			<br>Название: <strong>".$rows["name"]."</strong>
			".($rows["number"]?"<br>Номер:<strong> ".$rows["number"]."</strong>":"")."
			".($rows["accessoryProducer"]?"<br>ISBN:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
			".($rows["accessoryColors"]?"<br>Год выпуска:<strong> ".$rows["accessoryColors"]."</strong>":"")."
			".($rows["accessorySize"]?"<br>Количество страниц:<strong> ".$rows["accessorySize"]."</strong>":"")."
			<br>".($rows["oldprice"]>0?"Старая цена: <strong><s>".round($rows["oldprice"],2)." руб.</s></strong>
			<br>Новая цена: <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>":
			"Цена: <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год!\")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год!' >Цена<b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." руб.</font></strong></a>":""));
			
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
				}
			
			if (trim($rows["details"]))
			{
				$text = substr($rows["details"], 0, 250);
				$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
				$text = str_replace($rows['name'],"<strong>".$rows['name']."</strong>",$text);
				$text = str_replace($rows['gname'],"<strong>".$rows['gname']."</strong>",$text);
				$text = str_replace(" монет ","<strong> монет </strong>",$text);
				$text = str_replace(" монета ","<strong> монета </strong>",$text);
				$text = str_replace(" монеты ","<strong> монеты </strong>",$text);
				$text = str_replace(" монетам ","<strong> монетам </strong>",$text);
				$text = str_replace(" монет.","<strong> монет.</strong>",$text);
				$text = str_replace(" монета.","<strong> монета.</strong>",$text);
				$text = str_replace(" монеты.","<strong> монеты.</strong>",$text);
				$text = str_replace(" монетам.","<strong> монетам.</strong>",$text);
				echo "<br>Описание: ".str_replace("\n","<br>",$text);
			}
			echo "</td></tr>";
			echo "</table>";
			if ($i%2==1) {
				echo "</td>";
			} elseif ($i%2==0) {
				echo "</td></tr>";
			}
			$i++;
		}
		if ($i%2==0) 
			echo "<td>&nbsp;</td></tr>";
		
		echo "</form>
		</table>";
		echo $page_print."<br>";