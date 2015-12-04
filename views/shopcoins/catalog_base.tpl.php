<?	echo $tpl['paginator']->printPager(); 


echo "
		
		<div id='ShowShopcoinsm'></div>
		<form action=$script?pageinfo=cookies&typeorder=1&pagenum=$pagenum".($search?"&search=".urlencode($search):"").($searchid?"&searchid=".$searchid:"")."&group=$group".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")." method=post name=mainform>
		<input type=hidden name=shopcoinsorder value=''>
		<input type=hidden name=shopcoinsorderamount value=''>
		<input type=hidden name=materialtype value=''>
		";
		echo "<table border=0 cellpadding=3 cellspacing=0 width=100%>";
		
		$i=1;
		foreach ($tpl['shop']['MyShowArray'] as $key=>$rows)
		//while ($rows = mysql_fetch_array($result))
		{
			
			$textoneclick = '';
			
			if (($rows['materialtype']==2 || $rows['materialtype']==4 || $rows['materialtype']==7 || $rows['materialtype']==8 || $rows['materialtype']==6) && $rows['amount']>10) 
				$rows['amount'] = 10;
			
			$mtype = ($rows['materialtype']>0?$rows['materialtype']:$materialtype);
			
			if ($mtype==1)
				$rehref = "Монета ";
			elseif ($mtype==8)
				$rehref = "Монета ";
			elseif ($mtype==7)
				$rehref = "Набор монет ";
			elseif ($mtype==2)
				$rehref = "Банкнота ";
			elseif ($mtype==4)
				$rehref = "Набор монет ";
			elseif ($mtype==5)
				$rehref = "Книга ";
			elseif ($mtype==9)
				$rehref = "Лот монет ";
			elseif ($mtype==10)
				$rehref = "Нотгельд ";
			elseif ($mtype==11)
				$rehref = "Монета ";
			else 
				$rehref = "";
					
			
			if ($rows['gname'])
				$rehref .= $rows['gname']." ";
			$rehref .= $rows['name'];
			if ($rows['metal'])
				$rehref .= " ".$rows['metal']; 
			if ($rows['year'])
			{
			    $tmp_year_name = $rows['year'];
			    if($rows['year'] == 1990 && $rows['materialtype']==12) $tmp_year_name = '1990 ЛМД';
			    if($rows['year'] == 1991 && $rows['materialtype']==12) $tmp_year_name = '1991 ЛМД';
			    if($rows['year'] == 1992 && $rows['materialtype']==12) $tmp_year_name = '1991 ММД';
				$rehref .= " ". $tmp_year_name;
			}
			$namecoins = $rehref;
			$rehrefdubdle = $rehref."_c".(($mtype==1 || $mtype==10 || $mtype==12)?$rows['parent']:$rows['shopcoins'])."_pc".($parent>0?$parent:(($mtype==7 || $mtype==8 || $mtype==6 || $mtype==4 || $mtype==2) && $rows["amount"]>1?$rows['shopcoins']:(($mtype==1 || $mtype==10)?$rows['parent']:0)))."_m".$rows['materialtype']."_pp1.html";
			$rehref = $rehref."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";
			 
//			echo $rehref."<br>";
			
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
			
			if (($rows["materialtype"]!=7 && $rows["materialtype"]!=4 && $rows["materialtype"]!=9) || ($rows["materialtypecross"] & pow(2,1) && $materialtype==1) || ($rows["materialtypecross"] & pow(2,8) && $materialtype==8) || ($rows["materialtypecross"] & pow(2,6) && $materialtype==6))
			{
				if ($i%2==1)
					echo "<tr><td class=tboard valign=top width=50%>";
				elseif ($i%2==0)
					echo "<td class=tboard valign=top width=50%>";
			}
			else
				echo "<tr><td class=tboard valign=top colspan=2 width=100%>";
			if ($rows['materialtype']==3 ) {
			
				echo "<table border=0 cellpadding=2 cellspacing=0 width=100%>";
				echo "<tr bgcolor=#99CCFF>
				<td class=tboard width=80% colspan=2><a name=coin".$rows["shopcoins"]." title='".$rows["name"]."'></a><strong>".$namecoins."</strong> </td></tr>";
				echo "<tr>
				<td class=tboard>
				<a href='$rehref' title='".$namecoins."'>
				<img src=images/".$rows["image"]." alt='".$namecoins."' border=1 style='border-color:black'>
				</a>
				</td></tr><td class=tboard>";
				
				if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder))
					echo "
					<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows["shopcoins"]]."'> 
					<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].",".$rows["materialtype"].")'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz7.gif border=0 alt='Уже есть в вашей корзине'></div></a>";
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
						<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='В корзину'></div></a>
						";
						if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." руб.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
						//javascript:AddAccessory(\"$script?pageinfo=cookies&shopcoinsorder=".$rows["shopcoins"]."&typeorder=1&pagenum=$pagenum&pricevalue=$pricevalue&".($search?"search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]."\"
				}
				if (strlen($rows["details"])>250 or $rows["image_big"]) {
//					echo "&nbsp;<a href='index.php?page=show&catalog=".$rows["shopcoins"].(substr_count($addhref,'materialtype')>0?"":"&materialtype=".$rows["materialtype"]).$addhref."' title=\"Подробнее о ".$rows['name']."\"><img src=".$in."images/corz3.gif border=0 alt='Подробнее о ".$rows['name']."'></a>";
					echo "&nbsp;<a href='$rehref' title=\"Подробнее о ".$rows['name']."\"><img src=".$in."images/corz3.gif border=0 alt='Подробнее о ".$rows['name']."'></a>";
				}
				
				echo $textoneclick;
				
				if ( !$rows["amount"]) 
					$amountall = 1;
				else 
					$amountall = $rows["amount"];
					
				echo "<br>Количество:  <strong>".$amountall."</strong>";
						
				echo "<br>Группа:  <a href=$script?group=".$rows['group']."&materialtype=".$rows['materialtype']." title='Посмотреть все ".$rows["gname"]."'><strong><font color=blue>".$rows["gname"]."</font></strong></a>
				<br>Название: <strong>".$rows["name"]."</strong>
				".($rows["number"]?"<br>Номер:<strong> ".$rows["number"]."</strong>":"")."
				".($rows["accessoryProducer"]?"<br>Производитель:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
				".($rows["accessoryColors"]?"<br>Цвета:<strong> ".$rows["accessoryColors"]."</strong>":"")."
				".($rows["accessorySize"]?"<br>Размеры:<strong> ".$rows["accessorySize"]."</strong>":"")."
				
				<br>".($rows["oldprice"]>0?"Старая цена: <strong><s>".round($rows["oldprice"],2)." руб.</s></strong>
				<br>Новая цена: <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>":
				"Цена: <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!\")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!' >Цена<b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." руб.</font></strong></a>":""));
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
					echo "<br>Описание: ".str_replace("\n","<br>",$text);
				}
				echo "</td></tr></table>";
			}
			elseif ($rows['materialtype']==5) {
			
				echo "<table border=0 cellpadding=2 cellspacing=0 width=100%>";
				echo "<tr bgcolor=#99CCFF>
				<td class=tboard width=80% colspan=2><a name=coin".$rows["shopcoins"]." title='".$rows["name"]."'></a><strong>".$namecoins."</strong></td></tr>";
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
					<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].",".$rows["materialtype"].")' title='".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz7.gif border=0 alt='Уже в корзине'></div></a>";
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
						<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")' title='Положить в корзину книгу ".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='Положить в корзину книгу ".$rows["name"]."'></div></a>
						";
						if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." руб.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
						//javascript:AddAccessory(\"$script?pageinfo=cookies&shopcoinsorder=".$rows["shopcoins"]."&typeorder=1&pagenum=$pagenum&pricevalue=$pricevalue&".($search?"search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]."\"
				}
				echo $textoneclick;
				if (strlen($rows["details"])>250 or $rows["image_big"]) {
//					echo "&nbsp;<a href='index.php?page=show&catalog=".$rows["shopcoins"]."&pagenum=$pagenum&pricevalue=$pricevalue".($search?"&search=".urlencode($search):"").($searchid?"&searchid=".$searchid:"")."&group=$group&materialtype=".$rows['materialtype'].($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"").($theme?"&theme=".$theme:"")."' title='Подробнее о книге ".$rows["name"]."'><img src=".$in."images/corz3.gif border=0 alt='Подробнее о книге ".$rows["name"]."'></a>";
					echo "&nbsp;<a href='$rehref' title=\"Подробнее о ".$rows['name']."\"><img src=".$in."images/corz3.gif border=0 alt='Подробнее о ".$rows['name']."'></a>";
				}
					
				echo "<br>Группа:  <a href=$script?group=".$rows['group']."&materialtype=".$rows['materialtype']." title='Посмотреть все ".$rows["gname"]."'><strong><font color=blue>".$rows["gname"]."</font></strong></a>
				<br>Название: <strong>".$rows["name"]."</strong>
				".($rows["number"]?"<br>Номер:<strong> ".$rows["number"]."</strong>":"")."
				".($rows["accessoryProducer"]?"<br>ISBN:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
				".($rows["accessoryColors"]?"<br>Год выпуска:<strong> ".$rows["accessoryColors"]."</strong>":"")."
				".($rows["accessorySize"]?"<br>Количество страниц:<strong> ".$rows["accessorySize"]."</strong>":"")."
				<br>".($rows["oldprice"]>0?"Старая цена: <strong><s>".round($rows["oldprice"],2)." руб.</s></strong>
				<br>Новая цена: <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>":
				"Цена: <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!\")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!' >Цена<b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." руб.</font></strong></a>":""));
				
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
			}
			else {
				echo "<table border=0 cellpadding=2 cellspacing=0 width=100%>
				<tr bgcolor=#99CCFF>
				<td class=tboard width=80% colspan=2><a name=coin".$rows["shopcoins"]." title='".($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==1 || $rows["materialtype"]==11?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":"Набор монет"))." - ".$rows["gname"]." ".$rows["name"]."'></a><strong>".$namecoins."</strong> </td></tr>
				
				<tr><td class=tboard>";
				//if ($REMOTE_ADDR == $myip)
					//echo (getmicrotime()-$time_start);
				if ($ImageParent[$rows["parent"]]>0 && ($rows["materialtype"]==1 ||$rows["materialtype"]==10 ||$rows["materialtype"]==12) && !$mycoins)
				{
					echo "<table border=0 cellpadding=1 cellspacing=0 width=294>
					<tr><td valign=top><a href='$rehref' title='Монета - ".$rows["gname"]." ".$rows["name"]." - подробная информация'>
					".(file_exists("./images/".$rows["image"]."")?"<img src=images/".$rows["image"]." alt='Монета - ".$rows["gname"]." ".$rows["name"]." - подробная информация' border=1 style='border-color:black'>":"<font color=blue size=+2>Нет изображения</font>")."
					</a></td>
					<td valign=top align=center>";
					unset ($tmpsmallimage);
					$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='Монета ".$rows["gname"]." | ".$rows["name"]."' width=80  style='border-color:black'>";
					$tmpsmallimage[] = "<img src=smallimages/".$ImageParent[$rows["parent"]][0]." border=1 alt='Монета ".$rows["gname"]." | ".$rows["name"]."' width=80  style='border-color:black'>";
					
					echo "<a href='$rehrefdubdle'>".implode("<br>",$tmpsmallimage)."<br><img src=".$in."images/corz13.gif border=0 alt='Посмотреть список подобных монет ".$rows["gname"]." | ".$rows["name"]."'></a></td></tr>
					</table>";
				}
				elseif (($rows["materialtype"]==7 || $rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==2) && $rows["amount"] > 1 && !$mycoins) 
				{
					$width_image = 0;
					$height_image = 0;
					if (file_exists("./images/".$rows["image"]) and $rows["materialtype"]==2)
					{
						$size_image = GetImageSize("./images/".$rows["image"]);
						if ($size_image[0] > 223)
						{
							$width_image = 223;
							$height_image = round((223*$size_image[1])/$size_image[0]);
						}
					}
					
					echo "<table border=0 cellpadding=1 cellspacing=0 width=294>
					<tr><td valign=top><a href='$rehref' title='".($rows["materialtype"]==8||$rows["materialtype"]==6?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":"Набор монет"))." - ".$rows["gname"]." ".$rows["name"]." - подробная информация'>
					".(file_exists("./images/".$rows["image"]."")?"<img src=images/".$rows["image"]." ".($width_image&&$height_image?"width='$width_image' height='$height_image'":"")." alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":"Набор монет"))." - ".$rows["gname"]." ".$rows["name"]." - подробная информация' border=1 style='border-color:black'>":"<font color=blue size=+2>Нет изображения</font>")."
					</a></td>
					<td valign=top align=center>";
					unset ($tmpsmallimage);
					$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":($rows["materialtype"]==10?"Нотгельд":"Набор монет")))." - ".$rows["gname"]." ".$rows["name"]."' width=80  style='border-color:black'>";
					$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":"Набор монет"))." - ".$rows["gname"]." ".$rows["name"]."' width=80  style='border-color:black'>";
					
					echo "<a href='$rehrefdubdle' title='Посмотреть список подобных ".($rows["materialtype"]==8||$rows["materialtype"]==6?"монет":($rows["materialtype"]==2?"банкнот(бон)":($rows["materialtype"]==10?"нотгельдов":"наборов монет")))." - ".$rows["gname"]." ".$rows["name"]."'>".implode("<br>",$tmpsmallimage)."<br><img src=".$in."images/corz13.gif border=0 alt='Посмотреть список подобных ".($rows["materialtype"]==8||$rows["materialtype"]==6?"монет":($rows["materialtype"]==2?"банкнот(бон)":($rows["materialtype"]==10?"нотгельдов":"наборов монет")))." - ".$rows["gname"]." ".$rows["name"]."'></a></td></tr>
					</table>";
				}
				elseif ($rows["materialtype"]==4 && $rows["amount"] > 1 && !$mycoins) {
				
					echo "<table border=0 cellpadding=1 cellspacing=0 width=294>
					<tr><td valign=top><a href='$rehref' title='".($rows["materialtype"]==8||$rows["materialtype"]==6?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":"Набор монет"))." - ".$rows["gname"]." ".$rows["name"]." - подробная информация'>
					".(file_exists("./images/".$rows["image"]."")?"<img ".(($rows["materialtypecross"] & pow(2,1)||$rows["materialtypecross"] & pow(2,8))&&($materialtype==1||$materialtype==8||$materialtype==6)?"width=206 src=images/".$rows["image_big"]."":"src=images/".$rows["image"]."")." alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":"Набор монет"))." - ".$rows["gname"]." ".$rows["name"]." - подробная информация' border=1 style='border-color:black'>":"<font color=blue size=+2>Нет изображения</font>")."
					</a></td>
					<td valign=top align=center>";
					unset ($tmpsmallimage);
					$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":($rows["materialtype"]==10?"Нотгельд":"Набор монет")))." - ".$rows["gname"]." ".$rows["name"]."' width=80  style='border-color:black'>";
					$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":"Набор монет"))." - ".$rows["gname"]." ".$rows["name"]."' width=80  style='border-color:black'>";
					
					echo "<a href='$rehrefdubdle' title='Посмотреть список подобных ".($rows["materialtype"]==8||$rows["materialtype"]==6?"монет":($rows["materialtype"]==2?"банкнот(бон)":($rows["materialtype"]==10?"нотгельдов":"наборов монет")))." - ".$rows["gname"]." ".$rows["name"]."'>".implode("<br>",$tmpsmallimage)."<br><img src=".$in."images/corz13.gif border=0 alt='Посмотреть список подобных ".($rows["materialtype"]==8||$rows["materialtype"]==6?"монет":($rows["materialtype"]==2?"банкнот(бон)":($rows["materialtype"]==10?"нотгельдов":"наборов монет")))." - ".$rows["gname"]." ".$rows["name"]."'></a></td></tr>
					</table>";
				}
				elseif ($rows["materialtype"]==4) {
					echo "<a href='$rehref' title='".(($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==1 || $rows["materialtype"]==11)?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." - ".$rows["gname"]." ".$rows["name"]." - подробная информация'>
					".(file_exists("./images/".$rows["image"]."")?"<img ".(($rows["materialtypecross"] & pow(2,1)||$rows["materialtypecross"] & pow(2,8))&&($materialtype==1||$materialtype==8||$materialtype==6)?"width=206 src=images/".$rows["image_big"]."":"src=images/".$rows["image"]."")." alt='".(($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==1 || $rows["materialtype"]==11)?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." - ".$rows["gname"]." ".$rows["name"]." - подробная информация' border=1 style='border-color:black'>":"<font color=blue size=+2>Нет изображения</font>")."
					</a><br>";
				}
				else {
				
					echo "<a href='$rehref' title='".(($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." - ".$rows["gname"]." ".$rows["name"]." - подробная информация'>
					".(file_exists("./images/".$rows["image"]."")?"<img src=images/".$rows["image"]." alt='".(($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"Монета":($rows["materialtype"]==2?"Банкнота(бона)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." - ".$rows["gname"]." ".$rows["name"]." - подробная информация' border=1 style='border-color:black'>":"<font color=blue size=+2>Нет изображения</font>")."
					</a><br>";
				}
				
				if(!$mycoins) {
					// vbhjckfd start
					
					$reservedForSomeUser = (1>2);
					$reservedForSomeGroup =  $rows['timereserved'] > time() ; // group, lower priority than personal
					$isInRerservedGroup = null;
					
					if($cookiesuser && $reservedForSomeGroup && !$reservedForSomeUser)
					{
						$isInRerservedGroup = isInRerservedGroup($cookiesuser, $rows["shopcoins"]);
					}
					
					if ($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==7 || $rows["materialtype"]==2 || $rows["materialtype"]==4) {
				
						
						
						$sql_amount = "SELECT * FROM helpshopcoinsorder WHERE shopcoins='".$rows["shopcoins"]."' AND reserve > '".(time() - $reservetime)."';";
						$result_amount = mysql_query($sql_amount);
						$reserveamount = 0;
						$statusshopcoins = 0;
						$reserveuser = 0;
						$reservealluser = 0;
						while ($rows_amount = mysql_fetch_array($result_amount)) {
						
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
						}
						
						$statusopt = 0;
						
						if ($rows['price1'] && $rows['amount1'] && ($rows['amount'] -$reserveamount)>=$rows['amount1']) 
							$statusopt = 1;
						
						if (!$reserveuser && $reservealluser) $reserveuser=$reservealluser;
						
						if ($statusshopcoins)
							echo "<img src=".$in."images/corz7.gif border=0 alt='Уже в вашей корзине'>";
						elseif ( ($reservedForSomeUser || (!$cookiesuser && $reservedForSomeGroup) || (false === $isInRerservedGroup)) && $reserveamount>=$rows["amount"])
							echo "<img src=".$in."images/corz6.gif border=0 alt='Покупает другой посетитель ".($rows["materialtype"]==8 || $rows["materialtype"]==6?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." ".$rows["gname"]." ".$rows["name"]."'>";
						elseif($statusopt>0) {
						
							if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder))
							{
								echo "
								<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows["shopcoins"]]."'> 
								<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].",".$rows["materialtype"].")' title='".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz7.gif border=0 alt='Уже в корзине'></div></a>";
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
									<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")' title='Положить в корзину книгу ".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='Положить в корзину книгу ".$rows["name"]."'></div></a>
									";
									if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." руб.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
									//javascript:AddAccessory(\"$script?pageinfo=cookies&shopcoinsorder=".$rows["shopcoins"]."&typeorder=1&pagenum=$pagenum&pricevalue=$pricevalue&".($search?"search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]."\"
							}
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
							and ($rows["check"] == 1 || $rows["check"] == 50 || ($cookiesuser==811 && $rows["check"] >3))
							)
								echo "<div id=bascetshopcoins".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddBascet('".$rows["shopcoins"]."','1');\" rel=\"nofollow\" title='Положить в корзину ".($rows["materialtype"]==8||$rows["materialtype"]==6?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==10?"Нотгельд":"Набор монет")))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz1.gif border=0 alt='Положить в корзину ".($rows["materialtype"]==8||$rows["materialtype"]==6?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==10?"Нотгельд":"Набор монет")))." ".$rows["gname"]." ".$rows["name"]."'></a></div>";
								if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." руб.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
						}
						
						if (strlen($rows["details"])>250 or $rows["image_big"])
							echo "&nbsp;<a href='$rehref' title='Подробная информация о ".($rows["materialtype"]==8||$rows["materialtype"]==6?"монете":($rows["materialtype"]==2?"банкноте(боне)":($rows["materialtype"]==10?"Нотгельде":"Наборе монет")))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz3.gif border=0 alt='Подробная информация о ".($rows["materialtype"]==8||$rows["materialtype"]==6?"монете":($rows["materialtype"]==2?"банкноте(боне)":($rows["materialtype"]==10?"Нотгельде":"Наборе монет")))." ".$rows["gname"]." ".$rows["name"]."'></a>";
							echo $textoneclick;
						
						if ($reservedForSomeUser && $reserveamount>=$rows["amount"]) {
							echo "<br><font color=gray size=-2>Бронь до ".date("H:i", $reserveuser+$reservetime)."</font>";
							
							if (!$statusshopcoins && false === $isInRerservedGroup && $rows['relationcatalog']>0 && $cookiesuser>0)
								echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
						}
						elseif($reservedForSomeGroup && $reserveamount>=$rows["amount"]) {
							if($isInRerservedGroup) {
								echo '<br><font color=#ff0000 size=-2>На '.($rows["materialtype"]==8||$rows["materialtype"]==6?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет")))).' вы оставили заявку через <a href=../catalognew target=_blank style="font-size:9px;">каталог</a>. Она доступна вам для покупки.</font>';
							}
							else {
								echo '<br><font color=gray size=-2>На '.($rows["materialtype"]==8||$rows["materialtype"]==6?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет")))).' была оставлена заявка через <a href=../catalognew target=_blank style="font-size:9px;" title=\'Каталог монет России, Германии, США и других стран\'>каталог</a>. '.($rows["materialtype"]==8||$rows["materialtype"]==6?"монету":($rows["materialtype"]==2?"банкноту(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет")))).' до '. date("H:i",$rows['timereserved']) .' могут купить только клиенты, оставившие заявку.</font>';
								if ( $rows['relationcatalog']>0 && $cookiesuser>0)
									echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
							}			
						}
					}
					else {
						
						$reservinfo = '';
						$reservedForSomeUser = ( $rows["reserve"] > 0 && ( time() - (int) $rows["reserve"] < $reservetime )); //personal
						
						if (time() - (int) $rows["reserve"] < $reservetime  and $rows["reserveorder"] == $shopcoinsorder)
							echo "<img src=".$in."images/corz7.gif border=0 alt='Уже в вашей корзине'>";
						elseif ( $reservedForSomeUser || (!$cookiesuser && $reservedForSomeGroup) || (false === $isInRerservedGroup) ) {
							
							echo "<img src=".$in."images/corz6.gif border=0 alt='Покупает другой посетитель ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." ".$rows["gname"]." ".$rows["name"]."'>";
							
							if ($rows['doubletimereserve'] > time() && $cookiesuser>0 && $cookiesuser == $rows['userreserve']) {
							
								$reservinfo = "<img src=".$in."images/corz77.gif border=0 alt='Вы в очереди на покупку ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"монеты":($rows["materialtype"]==2?"банкноты(боны)":($rows["materialtype"]==9?"Лота монет":($rows["materialtype"]==10?"Нотгельда":"Набора монет"))))." ".$rows["gname"]." ".$rows["name"]."'>";
							}
							elseif($rows['timereserved']>$rows['reserve'] && $isInRerservedGroup && $rows['reserve']>0 && $rows['doubletimereserve'] < time()) {
							
								if ($REMOTE_ADDR!="213.180.194.162" 
								and $REMOTE_ADDR!="213.180.194.133" 
								and $REMOTE_ADDR!="213.180.194.164" 
								and $REMOTE_ADDR!="213.180.210.2" 
								and $REMOTE_ADDR!="83.149.237.18"
								and $REMOTE_ADDR!="83.237.234.171"
								and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"ia_archiver")
								and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"coona")
								and ($rows["check"] == 1 || $rows["check"] == 50 || ($cookiesuser==811 && $rows["check"] >3))
								)
									$reservinfo = "<div id=bascetshop".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddNext('".$rows["shopcoins"]."','1');\" rel=\"nofollow\" title='Стать в очередь на ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz11.gif border=0 alt='Стать в очередь на ".($rows["materialtype"]==1?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." ".$rows["gname"]." ".$rows["name"]."'></a></div>";
							}
							elseif ($rows['timereserved']<$rows['reserve'] && $rows['doubletimereserve'] < time() && $cookiesuser>0) {
							
								$reservinfo = "<div id=bascetshop".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddNext('".$rows["shopcoins"]."','1');\" rel=\"nofollow\" title='Стать в очередь на ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz11.gif border=0 alt='Стать в очередь на ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." ".$rows["gname"]." ".$rows["name"]."'></a></div>";
							}
						}
						elseif ($rows['doubletimereserve'] > time() && $cookiesuser != $rows['userreserve'])
							echo "<img src=".$in."images/corz6.gif border=0 alt='Покупает другой посетитель ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." ".$rows["gname"]." ".$rows["name"]."'>";
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
							and ($rows["check"] == 1 || $rows["check"] == 50 || ($cookiesuser==811 && $rows["check"] >3))
							)
								echo "<div id=bascetshopcoins".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddBascet('".$rows["shopcoins"]."','1');\" rel=\"nofollow\" title='Положить в корзину ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz1.gif border=0 alt='Положить в корзину ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"монету":($rows["materialtype"]==2?"банкнота(бону)":($rows["materialtype"]==9?"Лот монет":($rows["materialtype"]==10?"Нотгельд":"Набор монет"))))." ".$rows["gname"]." ".$rows["name"]."'></a></div>";
								if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." руб.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
						}
						
						if (strlen($rows["details"])>250 or $rows["image_big"])
							echo "&nbsp;<a href='$rehref' title='Подробная информация о ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"монете":($rows["materialtype"]==2?"банкноте(боне)":"наборе монет"))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz3.gif border=0 alt='Подробная информация о ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"монете":($rows["materialtype"]==2?"банкноте(боне)":($rows["materialtype"]==10?"Нотгельде":"Наборе монет")))." ".$rows["gname"]." ".$rows["name"]."'></a>";
						
						echo $textoneclick;
						
						if ($reservinfo)
							echo "<br>".$reservinfo;
						
						if ($reservedForSomeUser) {
							echo "<br><font color=gray size=-2>Бронь до ".date("H:i", $rows["reserve"]+$reservetime)."</font>";
							
							if (time() - (int) $rows["reserve"] >= $reservetime  || $rows["reserveorder"] != $shopcoinsorder  && $rows['relationcatalog']>0 && $cookiesuser>0)
								echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
						}
						elseif($reservedForSomeGroup) {
							if($isInRerservedGroup) {
								echo '<br><font color=#ff0000 size=-2>На монету вы оставили заявку через <a href=../catalognew target=_blank style="font-size:9px;">каталог</a>. Она доступна вам для покупки.</font>';
							}
							else {
								echo '<br><font color=gray size=-2>На монету '.$rows["gname"].' '.$rows["name"].' была оставлена заявка через <a href=../catalognew target=_blank style="font-size:9px;" title=\'Каталог монет России, Германии, США и других стран\'>каталог</a>. Монету до '. date("H:i",$rows['timereserved']) .' могут купить только клиенты, оставившие заявку.</font>';
								if ( $rows['relationcatalog']>0 && $cookiesuser>0)
									echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
							}			
						}
						elseif ($rows['doubletimereserve'] > time() && $cookiesuser != $rows['userreserve']) {
							echo '<br><font color=gray size=-2>Монета '.$rows["gname"].' '.$rows["name"].' была забронирована. Монету до '. date("H:i",$rows['doubletimereserve']) .' может купить только клиент, поставивший бронь.</font>';
							if ( $rows['relationcatalog']>0 && $cookiesuser>0)
									echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='При следующем появлении данного типа монеты в магазине вам будет отправлено уведомление на email...'>Оставить заявку через каталог</a></div>";
						}
						if ($rows['doubletimereserve'] > time() && $cookiesuser == $rows['userreserve'] && $cookiesuser>0)
							if ( $reservedForSomeUser || (!$cookiesuser && $reservedForSomeGroup) || (false === $isInRerservedGroup) )
								echo "<font color=red size=-2>Вы в очереди на монету до ".date("H:i", $rows["doubletimereserve"])."</font>";
							else
								echo "<br><font color=red size=-2>Вы можете купить монету. Ваша бронь до ".date("H:i", $rows["doubletimereserve"])."</font>";
					}
		
					// vbhjckfd stop
					
					
					if ($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==7 || $rows["materialtype"]==2 || $rows["materialtype"]==4) {
				
						if ( !$rows["amount"]) $amountall = 1;
						else $amountall = $rows["amount"];
						echo "<br>Количество:  <strong>".$amountall."</strong>";
					}
				
				}
				
				if ($rows["gname"])
					echo (!$mycoins?"<br>":"").($rows["materialtype"]==9?"Группа":"Страна").":  <a href=$script?group=".$rows['group']."&materialtype=".$rows["materialtype"]." title='Посмотреть ".($rows["materialtype"]==1 || $rows["materialtype"]==8 || $rows["materialtype"]==6?"монеты":($rows["materialtype"]==2?"банкноты(боны)":($rows["materialtype"]==9?"Лот монет":"Набор монет")))." ".$rows["gname"]."'><strong><font color=blue>".$rows["gname"]."</font></strong></a>";
				if ($rows["name"])
					echo "<br>Название: <strong>".$rows["name"]."</strong>";
				
				echo "<br>Номер: <strong>".$rows["number"]."</strong>
				<br>".($rows["oldprice"]>0?($rows["materialtype"]==8||$rows["materialtype"]==6?"Старая цена":"Старая стоимость").": <strong><s>".round($rows["oldprice"],2)." руб.</s></strong>
				<br>".($rows["materialtype"]==8||$rows["materialtype"]==6?"Новая цена":"Новая стоимость").": <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>":
				($rows["materialtype"]==8||$rows["materialtype"]==6?"Цена":"Стоимость").": <strong><font color=red>".($rows["price"]==0?"бесплатно":round($rows["price"],2)." руб.")."</font></strong>".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!\")' title='Цена со скидкой только для постоянных клиентов - сделавших не менее 3-х заказов за год под своим логином!' >".($rows["materialtype"]==8||$rows["materialtype"]==6?"Цена":"Стоимость")."<b><sup><font color=blue>для постоянных клиентов</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." руб.</font></strong></a>":""));
				
				//if ($rows["materialtype"]==12) 
					//echo " На данную позицию скидки не распространяются.";
				
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
				
				if($rows['year'] == 1990 && $materialtype==12) $rows['year'] = '1990 ЛМД';
				if($rows['year'] == 1991 && $materialtype==12) $rows['year'] = '1991 ЛМД';
				if($rows['year'] == 1992 && $materialtype==12) $rows['year'] = '1991 ММД';
				
				echo (trim($rows["metal"])?"<br>Металл: <strong>".$rows["metal"]."</strong>":"")."
				".($rows["width"]&&$rows["height"]?"<br>Приблизительный размер: <strong>".$rows["width"]."*".$rows["height"]." мм.</strong>":"")."
				".($rows["weight"]>0?"<br>Вес: <strong>".$rows["weight"]." гр.</strong>":"")."
				".($rows["year"]?"<br>Год: <strong>".$rows["year"]."</strong>":"")."
				".(trim($rows["condition"])?"<br>Состояние: <strong><font color=blue>".$rows["condition"]."</font></strong>":"")."
				".($rows["series"]&&$group?"<br>Серия монет: <a href=$script?series=".$rows["series"]."&group=".$group."&materialtype=".$materialtype.">".$series_name[$rows["series"]]."</a>":"")."
				".($rows["materialtype"]==8&&$materialtype==1&&!$mycoins?"<br><font color=red>МОНЕТА С РАЗДЕЛА МЕЛОЧЬ, см. условия покупки в разделе</font>":"");
				
				
				unset ($shopcoinstheme);
				$strtheme = decbin($rows["theme"]);
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
				
				if (!in_array($rows["group"], $ShopcoinsGroupArray))
					$ShopcoinsGroupArray[] = $rows["group"];
				//print_r($chars);
				
				if (sizeof($shopcoinstheme))
					echo "<br>Тематика: <strong>".implode(", ", $shopcoinstheme)."</strong>";
				
				if (trim($rows["details"]))
				{
					$text = substr($rows["details"], 0, 250);
					$text = strip_tags($text);
					$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
					$text = str_replace($rows['name'],"<strong>".$rows['name']."</strong>",$text);
					$text = str_replace($rows['gname'],"<strong>".$rows['gname']."</strong>",$text);
					$text = str_replace(" монет ","<strong> монет </strong>",$text);
					$text = str_replace(" монета ","<strong> монета </strong>",$text);
					$text = str_replace(" монеты ","<strong> монеты </strong>",$text);
					$text = str_replace(" монетам ","<strong> монетам </strong>",$text);
					echo "<br>Описание: ".str_replace("\n","<br>",$text)."";
				}
				
				if ($rows["dateinsert"]>time()-86400*180 && !$mycoins)
					echo "<br>Добавлено: <strong>".($rows["dateinsert"]>time()-86400*14?"<font color=red>NEW</font> ".date("Y-m-d", $rows["dateinsert"]):date("Y-m-d", $rows["dateinsert"]))."</strong>";
					
					
					if(	$materialtype == 11 AND is_user_has_premissons(intval($_COOKIE['cookiesuser'])) AND is_logged_in(intval($_COOKIE['cookiesuser'])) != FALSE AND !is_already_described($rows['shopcoins']))
					{ 
	echo "<a href=# style='float:right;' onClick=\"window.open('/shopcoins/change_coin.php?modal=1&coin=".$rows['shopcoins']."','Catalog','status=no,menubar=no,scrollbars=yes,resizable=no,width=670,height=650');return false;\">Описать (Заработать рубль на покупку)</a> ";
				}
					
				$rand = rand(1,2);
				/*if ($rows["realization"]&&!$mycoins)
						echo ($rand==1?"<br>Монета на реализации":"<br>Монета на комиссии");*/
				
				echo "</td>";
				if ($mycoins) {
					
					if ($rows['check']=1 && $rows['materialtype']!=1 && $rows['amount']>0) {
					
						echo "<td width=40% valign=top> <br> <a href=#  onclick=\"javascript:ShowShopcoinsStart2();process('showshopcoins.php?shopcoins=".$rows["shopcoins"]."')\" title='Посмотреть подобную позицию в магазине'>Посмотреть подобную позицию в магазине</a>";
					}
					else {
					
						$sql_c = "select `catalog` from catalogshopcoinsrelation where shopcoins=".$rows['shopcoins'].";";
						$result_c = mysql_query($sql_c);
						@$rows_c = mysql_fetch_array($result_c);
						if ($rows_c['catalog']>0) {
						
							$sql_m = "select shopcoins.* from shopcoins,catalogshopcoinsrelation where shopcoins.shopcoins=catalogshopcoinsrelation.shopcoins and shopcoins.`check`=1 and shopcoins.amount>0 and shopcoins.shopcoins!=".$rows['shopcoins']." and catalogshopcoinsrelation.catalog=".$rows_c['catalog']." order by ".$dateinsert_orderby." desc limit 1;";
							$result_m = mysql_query($sql_m);
							@$rows_m = mysql_fetch_array($result_m);
							if ($rows_m[0]>0) {
							
								echo "<td width=40% valign=top> <br><a href=#  onclick=\"javascript:ShowShopcoinsStart2();process('showshopcoins.php?shopcoins=".$rows_m["shopcoins"]."')\" title='Посмотреть подобную позицию в магазине'>Посмотреть подобную позицию в магазине</a>";
							}
						}
					}
				}
				echo "</tr>
				</table>";
			}
			if (($rows["materialtype"]!=7 && $rows["materialtype"]!=4 && $rows["materialtype"]!=9) || ($rows["materialtypecross"] & pow(2,1) && $materialtype==1) || ($rows["materialtypecross"] & pow(2,8) && $materialtype==8) || ($rows["materialtypecross"] & pow(2,6) && $materialtype==6))
			{
				if ($i%2==1) 
					echo "</td>";
				elseif ($i%2==0)
					echo "</td></tr>";
			}
			else
				echo "</td></tr>";
			
			$i++;
			
		}
		
		if ($i%2==0 && $rows["materialtype"]!=7) 
			echo "<td>&nbsp;</td></tr>";
		
		if (sizeof($ShopcoinsThemeArray) or sizeof($ShopcoinsGroupArray))
		{
			$sql = "select shopcoinsbiblio.shopcoinsbiblio from shopcoinsbiblio, shopcoinsbibliorelationgroup 
			where (
			".(sizeof($ShopcoinsGroupArray)?"(shopcoinsbibliorelationgroup.value in (".implode(",", $ShopcoinsGroupArray).") and type='group')":"")." 
			".(sizeof($ShopcoinsThemeArray)?" or (shopcoinsbibliorelationgroup.value in (".implode(",", $ShopcoinsThemeArray).")  and type='theme') ":"")." 
			)
			and shopcoinsbiblio.shopcoinsbiblio = shopcoinsbibliorelationgroup.shopcoinsbiblio
			group by shopcoinsbiblio.shopcoinsbiblio";
			
			//if ($REMOTE_ADDR=="194.85.82.223")
				//echo $sql;
		}
		
		echo "</form></table>";
		echo $page_print."<br>";
?>