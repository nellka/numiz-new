<?
/*
$mathref = "mm".$materialtype.($nocheck?"-nn1":"");	
	$MainText .= $infotext;

	
	$MainText .= "<br><div id=SearchByCondition></div><div id=SearchByPrice></div><div id=SearchByTheme></div><div id=SearchByYear></div><div id=SearchByMetal></div><div id=SearchByDetails></div>";
	$DivThemeStr = "<form action=".$script.($materialtype?"?materialtype=".$materialtype:"").($nocheck?"?nocheck=".$nocheck:"")." method=post>
	<table border=0 cellpadding=3 cellspacing=0 style='border:thin solid 1px #000000' id=tablesearchbytheme>
	<tr class=tboard bgcolor=#ffcc66><td><b>Поиск по тематикам</b></td>
	<td align=right><a href=# onclick=\"javascript:SearchByThemeClose();\" title='Закрыть поиск монет по тематикам'><img src=".$in."images/windowsclose.gif border=0></a></td>
	</tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2>
	<select name=theme class=formtxt>
	<option value=0>Все тематики";

	foreach ($ThemeArray as $key=>$value) {
		$DivThemeStr .= "<option value=".$key." ".($theme==$key?"selected":"").">".$value;
		if ($theme==$key)
			$arraykeyword[] = $value;
	}
	$DivThemeStr .= "</select>
	</td></tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2>
	<input type=submit class=formtxt value='Показать'>
	</td></tr>
	</table>
	</form>";
	
	//цена
	$DivPriceStr = "<form action=".$script.($materialtype?"?materialtype=".$materialtype:"").($nocheck?"?nocheck=".$nocheck:"")." method=post>
	<table border=0 cellpadding=3 cellspacing=0 style='border:thin solid 1px #000000' id=tablesearchbyprice>
	<tr class=tboard bgcolor=#ffcc66><td><b>Поиск по цене</b></td>
	<td align=right><a href=# onclick=\"javascript:SearchByPriceClose();\" title='Закрыть поиск монет по ценам'><img src=".$in."images/windowsclose.gif border=0></a></td>
	</tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2 nowrap>
	<b>С</b> <input type=text name=pricestart class=formtxt size=4 value='$pricestart'>
	 &nbsp;<b>По</b> <input type=text name=priceend class=formtxt size=4 value='$priceend'>
	</td></tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2>
	<input type=submit class=formtxt value='Показать'>
	</td></tr>
	</table>
	</form>";
	
	
	// --- года
	
	$DivYearStr = "<form action=".$script.($materialtype?"?materialtype=".$materialtype:"").($nocheck?"?nocheck=".$nocheck:"")." method=post>
	<table border=0 cellpadding=3 cellspacing=0 style='border:thin solid 1px #000000' id=tablesearchbyyear>
	<tr class=tboard bgcolor=#ffcc66><td><b>Поиск по годам</b></td>
	<td align=right><a href=# onclick=\"javascript:SearchByYearClose();\" title='Закрыть поиск монет по годам выпуска'><img src=".$in."images/windowsclose.gif border=0></a></td>
	</tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2 nowrap>
	<b>С</b> <input type=text name=yearstart class=formtxt size=4 value='$yearstart'>
	 &nbsp;<b>По</b> <input type=text name=yearend class=formtxt size=4 value='$yearend'>
	</td></tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2>
	<input type=submit class=formtxt value='Показать'>
	</td></tr>
	</table>
	</form>";
	
	$DivMetalStr = "<form action=".$script.($materialtype?"?materialtype=".$materialtype:"").($nocheck?"?nocheck=".$nocheck:"")." method=post>
	<table border=0 cellpadding=3 cellspacing=0 style='border:thin solid 1px #000000' id=tablesearchbymetal>
	<tr class=tboard bgcolor=#ffcc66><td nowrap><b>Поиск по металлам</b></td>
	<td align=right><a href=# onclick=\"javascript:SearchByMetalClose();\" title='Закрыть поиск монет по металлам'><img src=".$in."images/windowsclose.gif border=0></a></td>
	</tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2>
	<select name=metal class=formtxt>
	<option value=0>Все металлы";
	
	if ($metal)
		$arraykeyword[] = urldecode($metal);
	
	if ($cookiesuser==811) {
	
		if ($nocheck) {
		
			if (time() - filemtime("dataadminchecktmetal.php") > 300) {
				
				$sql = "select distinct `metal` from shopcoins where (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and metal<>'' order by metal;";
				$result = mysql_query($sql);
				$SaveDivMetalStr = "";
				$TopMetalStr = "";
				$SaveTopMetalStr ="";
				while ($rows = mysql_fetch_array($result)) {
					
					$DivMetalStr .= "<option value='".$rows["metal"]."' ".($metal==$rows["metal"]?"selected":"").">".$rows["metal"];
					$SaveDivMetalStr .= '$DivMetalStr .= "<option value='."'".$rows["metal"]."' \"".'.($metal=='.'"'.$rows["metal"].'"?"selected":"").">'.$rows["metal"].'"
					;';
					$TopMetalStr .= "<a href=index.php?metal=".urlencode($rows["metal"]).($materialtype?"&materialtype=".$materialtype:"").($group?"&group=".$group:"").($searchid?"&searchid=".$searchid:"")." title='".$MaterialTypeArrayTitle[$materialtype]." ".$value."'>".(urldecode($metal)==$rows["metal"]?"<font color=black>".$rows["metal"]."</font>":$rows["metal"])."</a><br>";
					$SaveTopMetalStr .= "\$TopMetalStr .= \"<a href=index.php?metal=\".urlencode('".$rows['metal']."').(\$materialtype?\"&materialtype=\".\$materialtype:\"\").(\$group?\"&group=\".\$group:\"\").(\$searchid?\"&searchid=\".\$searchid:\"\").\" title='\".\$MaterialTypeArrayTitle[\$materialtype].\" ".$rows['metal']."'>\".(urldecode(\$metal)==\"".$rows['metal']."\"?\"<font color=black>".$rows['metal']."</font>\":'".$rows['metal']."').\"</a><br>\";";
				}
				$fp=fopen("dataadminchecktmetal.php","w");
				if ($fp)
				{
					$SaveDivMetalStr = "<?php 
					".$SaveDivMetalStr.$SaveTopMetalStr."
					?>";
					fwrite($fp,$SaveDivMetalStr);
					fclose($fp);
				}
			}
			else {
				
				include_once "dataadminchecktmetal.php";
			}
		}
		else {
		
			if (time() - filemtime("dataadminmetal.php") > 300) {
				
				$sql = "select distinct `metal` from shopcoins where (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and metal<>'' order by metal;";
				$result = mysql_query($sql);
				$SaveDivMetalStr = "";
				$TopMetalStr = "";
				$SaveTopMetalStr ="";
				while ($rows = mysql_fetch_array($result)) {
					
					$DivMetalStr .= "<option value='".$rows["metal"]."' ".($metal==$rows["metal"]?"selected":"").">".$rows["metal"];
					$SaveDivMetalStr .= '$DivMetalStr .= "<option value='."'".$rows["metal"]."' \"".'.($metal=='.'"'.$rows["metal"].'"?"selected":"").">'.$rows["metal"].'"
					;';
					$TopMetalStr .= "<a href=index.php?metal=".urlencode($rows["metal"]).($materialtype?"&materialtype=".$materialtype:"").($group?"&group=".$group:"").($searchid?"&searchid=".$searchid:"")." title='".$MaterialTypeArrayTitle[$materialtype]." ".$value."'>".(urldecode($metal)==$rows["metal"]?"<font color=black>".$rows["metal"]."</font>":$rows["metal"])."</a><br>";
					$SaveTopMetalStr .= "\$TopMetalStr .= \"<a href=index.php?metal=\".urlencode('".$rows['metal']."').(\$materialtype?\"&materialtype=\".\$materialtype:\"\").(\$group?\"&group=\".\$group:\"\").(\$searchid?\"&searchid=\".\$searchid:\"\").\" title='\".\$MaterialTypeArrayTitle[\$materialtype].\" ".$rows['metal']."'>\".(urldecode(\$metal)==\"".$rows['metal']."\"?\"<font color=black>".$rows['metal']."</font>\":'".$rows['metal']."').\"</a><br>\";";
				}
				$fp=fopen("dataadminmetal.php","w");
				if ($fp)
				{
					$SaveDivMetalStr = "<?php 
					".$SaveDivMetalStr.$SaveTopMetalStr."
					?>";
					fwrite($fp,$SaveDivMetalStr);
					fclose($fp);
				}
			}
			else {
				
				include_once "dataadminmetal.php";
			}
		}
	
	}
	else {
		
		if (time() - filemtime("dataselectmetal.php") > 300) {
				
			$sql = "select distinct `metal` from shopcoins where (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and metal<>'' order by metal;";
			$result = mysql_query($sql);
			$SaveDivMetalStr = "";
			$TopMetalStr = "";
			$SaveTopMetalStr ="";
			var_dump(mysql_error());
			while ($rows = mysql_fetch_array($result)) {
				
				$DivMetalStr .= "<option value='".$rows["metal"]."' ".($metal==$rows["metal"]?"selected":"").">".$rows["metal"];
				$SaveDivMetalStr .= '$DivMetalStr .= "<option value='."'".$rows["metal"]."' \"".'.($metal=='.'"'.$rows["metal"].'"?"selected":"").">'.$rows["metal"].'"
				;';
				$TopMetalStr .= "<a href=index.php?metal=".urlencode($rows["metal"]).($materialtype?"&materialtype=".$materialtype:"").($group?"&group=".$group:"").($searchid?"&searchid=".$searchid:"")." title='".$MaterialTypeArrayTitle[$materialtype]." ".$value."'>".(urldecode($metal)==$rows["metal"]?"<font color=black>".$rows["metal"]."</font>":$rows["metal"])."</a><br>";
				$SaveTopMetalStr .= "\$TopMetalStr .= \"<a href=index.php?metal=\".urlencode('".$rows['metal']."').(\$materialtype?\"&materialtype=\".\$materialtype:\"\").(\$group?\"&group=\".\$group:\"\").(\$searchid?\"&searchid=\".\$searchid:\"\").\" title='\".\$MaterialTypeArrayTitle[\$materialtype].\" ".$rows['metal']."'>\".(urldecode(\$metal)==\"".$rows['metal']."\"?\"<font color=black>".$rows['metal']."</font>\":'".$rows['metal']."').\"</a><br>\";";
			}
			$fp=fopen("dataselectmetal.php","w");
			if ($fp)
			{
				$SaveDivMetalStr = "<?php 
				".$SaveDivMetalStr.$SaveTopMetalStr."
				?>";
				fwrite($fp,$SaveDivMetalStr);
				fclose($fp);
			}
		}
		else {
			
			include_once "dataselectmetal.php";
		}
	}

	$DivMetalStr .= "</select>
	</td></tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2>
	<input type=submit class=formtxt value='Показать'>
	</td></tr>
	</table>
	</form>";
	
	$DivConditionStr = "<form action=".$script.($materialtype?"?materialtype=".$materialtype:"").($nocheck?"?nocheck=".$nocheck:"")." method=post>
	<table border=0 cellpadding=3 cellspacing=0 style='border:thin solid 1px #000000' id=tablesearchbycondition>
	<tr class=tboard bgcolor=#ffcc66><td nowrap><b>Поиск по состоянию</b></td>
	<td align=right><a href=# onclick=\"javascript:SearchByConditionClose();\" title='Закрыть поиск монет по состояниям(кондиции)'><img src=".$in."images/windowsclose.gif border=0></a></td>
	</tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2>
	<select name=condition class=formtxt>
	<option value=0>Все состояния";
	
	if ($cookiesuser==811) {
	
		if ($nocheck) {
		
			if (time() - filemtime("dataadmincheckcondition.php") > 24*3600) {
				
				$sql = "select distinct `condition` from shopcoins where (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and `condition`<>'' order by `condition`;";
				$result = mysql_query($sql);
				$SaveDivConditionStr = "";
				while ($rows = mysql_fetch_array($result)) {
					
					$DivConditionStr .= "<option value='".$rows["condition"]."' ".($condition==$rows["condition"]?"selected":"").">".$rows["condition"];
					$SaveDivConditionStr .= '$DivConditionStr .= "<option value='."'".$rows["condition"]."' \"".'.($condition=='.'"'.$rows["condition"].'"?"selected":"").">'.$rows["condition"].'"
					;';
				}
				$fp=fopen("dataadmincheckcondition.php","w");
				if ($fp)
				{
					$SaveDivConditionStr = "<?php 
					".$SaveDivConditionStr."
					?>";
					fwrite($fp,$SaveDivConditionStr);
					fclose($fp);
				}
			}
			else {
				
				include_once "dataadmincheckcondition.php";
			}
		}
		else {
		
			if (time() - filemtime("dataadmincondition.php") > 24*3600) {
				
				$sql = "select distinct `condition` from shopcoins where (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and `condition`<>'' order by `condition`;";
				$result = mysql_query($sql);
				$SaveDivConditionStr = "";
				while ($rows = mysql_fetch_array($result)) {
					
					$DivConditionStr .= "<option value='".$rows["condition"]."' ".($condition==$rows["condition"]?"selected":"").">".$rows["condition"];
					$SaveDivConditionStr .= '$DivConditionStr .= "<option value='."'".$rows["condition"]."' \"".'.($condition=='.'"'.$rows["condition"].'"?"selected":"").">'.$rows["condition"].'"
					;';
				}
				$fp=fopen("dataadmincondition.php","w");
				if ($fp)
				{
					$SaveDivConditionStr = "<?php 
					".$SaveDivConditionStr."
					?>";
					fwrite($fp,$SaveDivConditionStr);
					fclose($fp);
				}
			}
			else {
				
				include_once "dataadmincondition.php";
			}
		}
	}
	else {
	
		if (time() - filemtime("dataselectcondition.php") > 24*3600) {
				
			$sql = "select distinct `condition` from shopcoins where shopcoins.check=1 and `condition`<>'' order by `condition`;";
			$result = mysql_query($sql);
			$SaveDivConditionStr = "";
			while ($rows = mysql_fetch_array($result)) {
				
				$DivConditionStr .= "<option value='".$rows["condition"]."' ".($condition==$rows["condition"]?"selected":"").">".$rows["condition"];
				$SaveDivConditionStr .= '$DivConditionStr .= "<option value='."'".$rows["condition"]."' \"".'.($condition=='.'"'.$rows["condition"].'"?"selected":"").">'.$rows["condition"].'"
				;';
			}
			$fp=fopen("dataselectcondition.php","w");
			if ($fp)
			{
				$SaveDivConditionStr = "<?php 
				".$SaveDivConditionStr."
				?>";
				fwrite($fp,$SaveDivConditionStr);
				fclose($fp);
			}
		}
		else {
			
			include_once "dataselectcondition.php";
		}
	}
	

	$DivConditionStr .= "</select>
	</td></tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2>
	<input type=submit class=formtxt value='Показать'>
	</td></tr>
	</table>
	</form>";
	
	$DivDetailsStr = "<form action=".$script.($materialtype?"?materialtype=".$materialtype:"").($nocheck?"?nocheck=".$nocheck:"")." method=post>
	<table border=0 cellpadding=3 cellspacing=0 style='border:thin solid 1px #000000' id=tablesearchbydetails>
	<tr class=tboard bgcolor=#ffcc66><td><b>По описаниям</b></td>
	<td align=right><a href=# onclick=\"javascript:SearchByDetailsClose();\" title='Закрыть поиск монет по описаниям'><img src=".$in."images/windowsclose.gif border=0></a></td>
	</tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2>
	<input type=text name=search class=formtxt size=20 value='$search'>
	</td></tr>
	
	<tr class=tboard bgcolor=#EBE4D4>
	<td class=tboard valign=middle align=center colspan=2>
	<input type=submit name=submit class=formtxt value='Показать'>
	</td></tr>
	</table>
	</form>";

	if($materialtype == 2)
	{
		$MainText .= "
		<table border='0' cellpadding='3' cellspacing='1' align='center' width='99%' style='padding-bottom: 10px;'>
<tbody>
<tr bgcolor='#ffcc66' class='tboard'> 
<td style='  font-size: 14px;'>Наш интернет магазин Клуба Нумизмат предлагает широкий выбор банкнот (бон). Каталог банкнот постоянно пополняется и вы всегда можете <b>купить банкноты</b> на любой вкус, как самому себе, так и в подарок. Выбор стран широкий. Номиналы обширны. Так же можете  ознакомиться с ценами на банкноты.
<br>
У нас всегда в наличии банкноты: редкие, коллекционные, СССР, старой России и стран мира.
</td></tr></tbody></table>";
	}
	if($materialtype == 6)
	{
		$MainText .= "
		<table border='0' cellpadding='3' cellspacing='1' align='center' width='99%' style='padding-bottom: 10px;'>
<tbody>
<tr bgcolor='#ffcc66' class='tboard'> 
<td style='  font-size: 14px;'>Наш интернет магазин Клуба Нумизмат предлагает широкий выбор цветных монет. Каталог красивых монет постоянно пополняется и вы всегда можете <b>купить цветные монеты</b> на любой вкус, как самому себе, так и в подарок. Выбор стран широкий. Номиналы обширны. Так же можете  ознакомиться с ценами на цветные монеты.
 <br>
У нас всегда в наличии цветные монеты: СССР, России, США и стран мира.
</td></tr></tbody></table>";
	}


	if($materialtype!=3 && $materialtype!=5)  {
		if($materialtype != 12)
			$MainText .= "<center><table border=0 cellpadding=5 cellspacing=1 id='tablesearch' align=center width=100%>
		<tr bgcolor=#EBE4D4 valign=middle>
		<td class=tboard valign=middle align=center colspan=6>
		<b><a href=search.php target=_blank title='Расширенный поиск монет, банкнот, по странам, годам выпуска, состоянию, ценам, тематикам'>Расширенный поиск </a><font color=red>!</font></b>
		</td></tr>
		
		<tr bgcolor=#EBE4D4 valign=middle><td class=tboard valign=middle align=center id='SearchTdPrice' ".($pricestart||$priceend?"style='border:thin solid 1px #000000' bgcolor=#ffcc66":"").">
		<a href=# onclick=\"javascript:SearchByPrice();\" title='Поиск монет по стоимости(ценам)'><img src=".$in."images/windowsmaximize.gif border=0> Цены ".($pricestart||$priceend?"<font color=black>":"").($pricestart?"от ".$pricestart:"").($priceend?" до ".$priceend:"").($pricestart||$priceend?"</font>":"")."</a>
		</td>
		
		<td class=tboard valign=middle align=center id='SearchTdTheme' ".($theme?"style='border:thin solid 1px #000000' bgcolor=#ffcc66":"").">
		<a href=# onclick=\"javascript:SearchByTheme();\" title='Поиск монет по темам'><img src=".$in."images/windowsmaximize.gif border=0> Тематики ".($theme?"<font color=black>".$ThemeArray[$theme]."</font>":"")."</a>
		</td>
		
		<td class=tboard valign=middle align=center id='SearchTdCondition' ".($condition?"style='border:thin solid 1px #000000' bgcolor=#ffcc66":"").">
		<a href=# onclick=\"javascript:SearchByCondition();\" title='Поиск монет по состоянию(кондиции)'><img src=".$in."images/windowsmaximize.gif border=0> Состояния ".($condition?"<font color=black>".$condition."</font>":"")."</a>
		</td>
		
		<td class=tboard valign=middle align=center id='SearchTdYear' ".($yearstart||$yearend?"style='border:thin solid 1px #000000' bgcolor=#ffcc66":"").">
		<a href=# onclick=\"javascript:SearchByYear();\" title='Поиск монет по периодам, годам выпуска'><img src=".$in."images/windowsmaximize.gif border=0> Года ".($yearstart||$yearend?"<font color=black>":"").($yearstart?"от ".$yearstart:"").($yearend?" до ".$yearend:"").($yearstart||$yearend?"</font>":"")."</a>
		</td>
			
		<td class=tboard valign=middle align=center id='SearchTdMetal' ".($metal?"style='border:thin solid 1px #000000' bgcolor=#ffcc66":"").">
		<a href=# onclick=\"javascript:SearchByMetal();\" title='Поиск монет по металлам'><img src=".$in."images/windowsmaximize.gif border=0> Металлы ".($metal?"<font color=black>".$metal."</font>":"")."</a>
		</td>
			
		<td class=tboard valign=middle align=center id='SearchTdDetails' ".($search?"style='border:thin solid 1px #000000' bgcolor=#ffcc66":"").">
		<a href=# onclick=\"javascript:SearchByDetails();\" title='Поиск монет по другим полям (описаниям)'><img src=".$in."images/windowsmaximize.gif border=0> Описания ".($search?"<font color=black>".$search."</font>":"")."</a>
		</td>
			
		</tr>
		</form>
		</table></center>";
	else
		$MainText .= "<center><table border='0' cellpadding='3' cellspacing='1' align='center' width='99%'>
<tbody>
<tr bgcolor='#ffcc66' class='tboard'> 
<td width='1%' valign='top'><a href='/shopcoins/index.php?searchname=1+%EA%EE%EF%E5%E9%EA%E0&materialtype=12' title='Показать только 1 копейка'>1 копейка</a></td> 
<td width='1%' valign='top'><a href='/shopcoins/index.php?searchname=2+%EA%EE%EF%E5%E9%EA%E8&materialtype=12' title='Показать только 2 копейки'>2 копейки</a></td> 
<td width='1%' valign='top'><a href='/shopcoins/index.php?searchname=3+%EA%EE%EF%E5%E9%EA%E8&materialtype=12' title='Показать только 3 копейки'>3 копейки</a></td> 
<td width='1%' valign='top'><a href='/shopcoins/index.php?searchname=5+%EA%EE%EF%E5%E5%EA&materialtype=12' title='Показать только 5 копеек'>5 копеек</a></td> 
<td width='1%' valign='top'><a href='/shopcoins/index.php?searchname=10+%EA%EE%EF%E5%E5%EA&materialtype=12' title='Показать только 10 копеек'>10 копеек</a></td>  
<td width='1%' valign='top'><a href='/shopcoins/index.php?searchname=15+%EA%EE%EF%E5%E5%EA&materialtype=12' title='Показать только 15 копеек'>15 копеек</a></td> 
<td width='1%' valign='top'><a href='/shopcoins/index.php?searchname=20+%EA%EE%EF%E5%E5%EA&materialtype=12' title='Показать только 20 копеек'>20 копеек</a></td> 
</tr></tbody></table></center>";
	
	$topleft = 0;
	}
	else {
		$MainText .= "<center><table border=0 cellpadding=5 cellspacing=1 id='tablesearch' align=center width=100%>
	
	
	<tr bgcolor=#EBE4D4 valign=middle><td class=tboard valign=middle align=center id='SearchTdDetails' ".($search?"style='border:thin solid 1px #000000' bgcolor=#ffcc66":"").">
	<a href=# onclick=\"javascript:SearchByDetails();\" title='Поиск монет по другим полям (описаниям)'><img src=".$in."images/windowsmaximize.gif border=0> Поиск по описанию ".($search?"<font color=black>".$search."</font>":"")."</a>
	</td>
		
	</tr>
	</form>
	</table></center>";
	$topleft = 350;
	}
	
	if ($materialtype==1||$materialtype==2||$materialtype==3||$materialtype==4||$materialtype==7||$materialtype==8||$materialtype == 12)
	{
		if ($cookiesuser)
		{
			$sql_test = "select count(*) from catalogshopcoinssubscribe 
			where user = '".$cookiesuser."';";
			$result_test = mysql_query($sql_test);
			$rows_test = mysql_fetch_array($result_test);
			if ($rows_test[0]>0)
			{
				$sql_catalog = "select count(*) 
				from `shopcoins`, catalogshopcoinsrelation, catalogshopcoinssubscribe
				WHERE (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") 
				and catalogshopcoinsrelation.shopcoins = shopcoins.shopcoins
				".($materialtype==1?"and shopcoins.amountparent <> 0":"")." 
				and catalogshopcoinssubscribe.`catalog` = catalogshopcoinsrelation.`catalog` 
				and catalogshopcoinssubscribe.`user` = '".$cookiesuser."';";
				
				//if ($user_remote_address  == "194.85.82.223")
					//echo $sql_catalog;
				
				$result_catalog = mysql_query($sql_catalog);
				$rows_catalog = mysql_fetch_array($result_catalog);
		
				$catalogamount = $rows_catalog[0];
			}
			else
			{
				$catalogamount = 0;
			}
		}
		
		$MainText .= "<table border=0 cellpadding=3 cellspacing=1 align=center width=100%>
		<tr bgcolor=#EBE4D4 valign=middle>
		<tr bgcolor=#EBE4D4 valign=middle><td class=tboard valign=middle align=center colspan=2>
		<a href=".$in."catalognew title='Каталог монет, банкнот При появлении монеты в магазине вам будет отправлено уведомление на email...' target='_blank'><font color=red>Что-то не нашли? - Оставьте заявку в каталоге...</font></a>
		&nbsp; | &nbsp; ".($cookiesuser?($catalogamount>0?"<a href=$script?catalognewstr=1&savesearch=1><font color=red>Ответы на оставленные заявки ($catalogamount)</font></a>":"<b>Ответы на оставленные заявки (0)</b>"):"<b>Ответы на оставленные заявки</b> (нужна авторизация)")."
		</td></tr>
		</form>
		</table>";
	}?>
	
	

	
<table class=tablemain width=98% cellpadding=0 cellspacing=0 border=0>
<tr>
<td width=15% valign=top>
<?

//показ всех категорий
foreach ($MaterialTypeArraySort as $key=>$value)
{
	if ($MaterialTypeFolderArray[$value] == 1)
		echo "<img src=".$in."images/folderfor.gif>";
		
	if ($materialtype == $value && $search!="revaluation" && $search!="newcoins")
			if($value == 2)
				echo "<a href=prodaza_banknot_i_bon.html title='".$MaterialTypeArrayTitle[$value]." смотреть'><img src=".$in."images/folder.gif border=0><font color=black> ".$MaterialTypeArray[$value]."</font></a>".($cookiesuser==811&&$value!=3&&$value!=5?"&nbsp;&nbsp;<a href=prodaza_banknot_i_bon.html&nocheck=1>".($nocheck?"<font color=black> ( + ) </font>":" ( + ) ")."</a>":"")."<br>";
			else if($value == 6)
				echo "<a href=prodaza_cvetnih_monet.html title='".$MaterialTypeArrayTitle[$value]." смотреть'><img src=".$in."images/folder.gif border=0><font color=black> ".$MaterialTypeArray[$value]."</font></a>".($cookiesuser==811&&$value!=3&&$value!=5?"&nbsp;&nbsp;<a href=prodaza_cvetnih_monet.html&nocheck=1>".($nocheck?"<font color=black> ( + ) </font>":" ( + ) ")."</a>":"")."<br>";
			else
		echo "<a href=index.php?materialtype=".$value." title='".$MaterialTypeArrayTitle[$value]." смотреть'><img src=".$in."images/folder.gif border=0><font color=black> ".$MaterialTypeArray[$value]."</font></a>".($cookiesuser==811&&$value!=3&&$value!=5?"&nbsp;&nbsp;<a href=index.php?materialtype=".$value."&nocheck=1>".($nocheck?"<font color=black> ( + ) </font>":" ( + ) ")."</a>":"")."<br>";
	else
		if($value == 2)
			echo "<a href=prodaza_banknot_i_bon.html title='".$MaterialTypeArrayTitle[$value]." смотреть'><img src=".$in."images/foldernotopen.gif border=0> ".$MaterialTypeArray[$value]."</a>".($cookiesuser==811&&$value!=3&&$value!=5?"&nbsp;&nbsp;<a href=prodaza_banknot_i_bon.html&nocheck=1> ( + ) </a>":"")."<br>";
		else if($value == 6)
			echo "<a href=prodaza_cvetnih_monet.html title='".$MaterialTypeArrayTitle[$value]." смотреть'><img src=".$in."images/foldernotopen.gif border=0> ".$MaterialTypeArray[$value]."</a>".($cookiesuser==811&&$value!=3&&$value!=5?"&nbsp;&nbsp;<a href=prodaza_cvetnih_monet.html&nocheck=1> ( + ) </a>":"")."<br>";
		else
		    echo "<a href=index.php?materialtype=".$value." title='".$MaterialTypeArrayTitle[$value]." смотреть'><img src=".$in."images/foldernotopen.gif border=0> ".$MaterialTypeArray[$value]."</a>".($cookiesuser==811&&$value!=3&&$value!=5?"&nbsp;&nbsp;<a href=index.php?materialtype=".$value."&nocheck=1> ( + ) </a>":"")."<br>";
}
echo "<a href=index.php?search=revaluation title='Распродажа монет'><img src=".$in."images/".($search=="revaluation"?"folder":"foldernotopen").".gif border=0>".($search=="revaluation"?"<font color=black>":"")." Распродажа монет".($search=="revaluation"?"</font>":"")."</a>".($cookiesuser==811?"&nbsp;&nbsp;<a href=index.php?search=revaluation&nocheck=1>".($nocheck&&$search=="revaluation"?"<font color=black> ( + ) </font>":" ( + ) ")."</a>":"")."<br>
<a href=index.php?search=newcoins title='Новинки ".(date('Y')-2)."-".date('Y')."'><img src=".$in."images/".($search=="newcoins"?"folder":"foldernotopen").".gif border=0>".($search=="newcoins"?"<font color=black>":"")." Новинки ".(date('Y')-2)."-".date('Y')."".($search=="newcoins"?"</font>":"")."</a>".($cookiesuser==811?"&nbsp;&nbsp;<a href=index.php?search=newcoins&nocheck=1>".($nocheck&&$search=="newcoins"?"<font color=black> ( + ) </font>":" ( + ) ")."</a>":"")."<br>";

echo "<a href=".$in."shopinfo.php title='Салон продаж'><img src=".$in."images/folder.gif border=0><font color=red> Салон продаж</font> (Контакты)</a><br>";

if ($cookiesuser)
	echo "<a href=index.php?page=recommendation title='Рекомендации'><img src=".$in."images/".($page=="recommendation"?"folder":"foldernotopen").".gif border=0>".($page=="recommendation"?"<font color=black>":"")." Персональные рекомендации".($page=="recommendation"?"</font>":"")."</a><br>";

echo "<a href=shopcoinsrules.php title='Правила монетной лавки Клуба Нумизмат'><img src=".$in."images/folder.gif border=0> Правила</a><br>
<a href=shopcoinshelp.php title='Помощь для монетной лавки Клуба Нумизмат'><img src=".$in."images/folder.gif border=0> Помощь</a><br>
<a href=aboutdiscont.php title='Система скидок лавки Клуба Нумизмат'><img src=".$in."images/folder.gif border=0> Система скидок</a><br>
<a href=rss.xml target=_blank title='Новые поступления монет,банкнот,аксессуаров - канал RSS'><img src=".$in."images/rss.gif border=0 alt='Новые поступления монет,банкнот,аксессуаров - канал RSS'> Новые поступления</a><br>";

echo "<img src=".$in."images/empty.gif>";

//показ подкатегорий
table_top ("100%", 0, "<img src=".$in."images/folder.gif alt='".($search=='revaluation'?"Распродажа монет":($search=='newcoins'?"Новые монеты":$MaterialTypeArrayTitle[$materialtype]))."'> ".($search=='revaluation'?"Распродажа монет":($search=='newcoins'?"Новинки 2009-2010":$MaterialTypeArrayTitle[$materialtype])).":", 0);
	

	if (!$group)
		echo "[<b> Все </b>]<br>";
	else
		echo "[<a href=index.php?materialtype=$materialtype".($searchid?"&searchid=".$searchid:"")." title='".$MaterialTypeArrayTitle[$materialtype]."'> Все </a>]<br>";
	
	if (!$searchid && $search!='revaluation' && $search!='newcoins')
	{
		if ($cookiesuser==811 && $materialtype!=3 && $materialtype!=5) {
		
			if ($nocheck) {
			
				if (time() - filemtime("topaloneadmincheck".$materialtype.".php") > 300)
					$topalonesave = 1; //нужно записать новый
				else
					$topalonesave = 0; //такой есть
			}
			else {
			
				if (time() - filemtime("topaloneadmin".$materialtype.".php") > 300)
					$topalonesave = 1; //нужно записать новый
				else
					$topalonesave = 0; //такой есть
			}
		}
		else {
		
			if (time() - filemtime("topalone".$materialtype.".php") > 300)
				$topalonesave = 1; //нужно записать новый
			else
				$topalonesave = 0; //такой есть
		}
	}
	elseif ( $search == 'revaluation')
	{
		if ($cookiesuser==811) {
		
			if ($nocheck) {
			
				if (time() - filemtime("topaloneadmincheckrevaluation.php") > 300)
					$topalonesave = 1; //нужно записать новый
				else
					$topalonesave = 0; //такой есть
			}
			else {
			
				if (time() - filemtime("topaloneadminrevaluation.php") > 300)
					$topalonesave = 1; //нужно записать новый
				else
					$topalonesave = 0; //такой есть
			}
		}
		else {
			
			if (time() - filemtime("topalonerevaluation.php") > 300)
				$topalonesave = 1; //нужно записать новый
			else
				$topalonesave = 0;
		}
	}
	elseif ($search=='newcoins') 
	{
		
		if ($cookiesuser==811) {
		
			if ($nocheck) {
			
				if (time() - filemtime("topaloneadminchecknewcoins.php") > 300)
					$topalonesave = 1; //нужно записать новый
				else
					$topalonesave = 0; //такой есть
			}
			else {
			
				if (time() - filemtime("topaloneadminnewcoins.php") > 300)
					$topalonesave = 1; //нужно записать новый
				else
					$topalonesave = 0; //такой есть
			}
		}
		else {
			if (time() - filemtime("topalonenewcoins.php") > 300)
				$topalonesave = 1; //нужно записать новый
			else
				$topalonesave = 0;
		}
	}
	else
	{
		$topalonesave = 2; //нужно ботать с базой
	}
	
	
	
	if ($topalonesave == 0 and !$searchid && $search!='revaluation' && $search!='newcoins')
	{
		if ($cookiesuser==811 && $materialtype!=3 && $materialtype!=5) {
		
			if ($nocheck) {
				include_once "topaloneadmincheck".$materialtype.".php";
			}
			else {
				include_once "topaloneadmin".$materialtype.".php";
			}
		}
		else {
			include_once "topalone".$materialtype.".php";
			if($_GET['materialtype'] == 12)
				echo '<img src="../images/folderfor.gif"> <a href="index.php?searchname=1+копейка&materialtype=12" class="star" title="">1 копейка</a>		<br><img src="../images/folderfor.gif"> <a href="index.php?searchname=2+копейки&materialtype=12" class="star" title="">2 копейки</a><br>					<img src="../images/folderfor.gif"> <a href="index.php?searchname=3+копейки&materialtype=12" class="star" title="">3 копейки</a><br>
<img src="../images/folderfor.gif"> <a href="index.php?searchname=5+копеек&materialtype=12" class="star" title="">5 копеек</a><br>
<img src="../images/folderfor.gif"> <a href="index.php?searchname=10+копеек&materialtype=12" class="star" title="">10 копеек</a><br>
<img src="../images/folderfor.gif"> <a href="index.php?searchname=15+копеек&materialtype=12" class="star" title="">15 копеек</a><br>
<img src="../images/folderfor.gif"> <a href="index.php?searchname=20+копеек&materialtype=12" class="star" title="">20 копеек</a><br>
					';
		}
	}
	elseif ( $topalonesave == 0 && $search == 'revaluation')
	{

		if ($cookiesuser==811) {
		
			if ($nocheck) {
				include_once "topaloneadmincheckrevaluation.php";
			}
			else {
				include_once "topaloneadminrevaluation.php";
			}
		}
		else {
			include_once "topalonerevaluation.php";
		}
	}
	elseif ( $topalonesave == 0 && $search == 'newcoins')
	{

		if ($cookiesuser==811) {
		
			if ($nocheck) {
				include_once "topaloneadminchecknewcoins.php";
			}
			else {
				include_once "topaloneadminnewcoins.php";
			}
		}
		else {
			include_once "topalonenewcoins.php";
		}
	}
	else
	{

		if ($search == 'revaluation') {
		
			$sql = "select distinct `group` 
			from shopcoins 
			where shopcoins.datereprice>0
			and (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or shopcoins.check>3":"shopcoins.check>3"):"shopcoins.check=1").")
			and shopcoins.dateinsert>0;";
		}
		elseif ($search == 'newcoins') {
		
			$sql = "select distinct `group` 
			from shopcoins 
			where shopcoins.year in(".implode(",",$arraynewcoins).")
			and shopcoins.materialtype in(1,4,7,8)
			and (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or shopcoins.check>3":"shopcoins.check>3"):"shopcoins.check=1").") 
			and shopcoins.dateinsert>0;";
		}
		else {
			$sql = "select distinct `group` 
			from shopcoins 
			where (shopcoins.materialtype='".($materialtype?$materialtype:1)."' or shopcoins.materialtypecross & pow(2,".($materialtype?$materialtype:1)."))
			and (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or shopcoins.check>3":"shopcoins.check>3"):"shopcoins.check=1").") 
			and shopcoins.dateinsert>0 
			".$WhereSearch;
		}
		//if ($REMOTE_ADDR=="78.107.149.10")
			//echo "<br>".$sql;
		$group407 = 0;
		$result = mysql_query($sql);
		while ($rows = mysql_fetch_array($result)) {
			
			$Group[] = $rows["group"];
		}
		
		$sql = "select * from `group` where `group` 
		".(sizeof($Group)>0?"in (".implode(",", $Group).")":" = 0 ")."
		order by name;";
		$result = mysql_query($sql);
		//if ($REMOTE_ADDR=="78.107.149.10")
			//echo "<br>".$sql;
		
		while ($rows = mysql_fetch_array($result))
		{
			if ($rows["groupparent"]>0)
			{
				$Group[] = $rows["groupparent"];
				
				if (!is_array($GroupArray[$rows["groupparent"]]))
					$GroupArray[$rows["groupparent"]] = Array();
			
				$GroupArray[$rows["groupparent"]][$rows["group"]] = $rows["name"];
			}
		}
		
		//if ($materialtype==1 || $materialtype==2)
		{
			$sql = "select `group`.* 
			from `group`
			where 
			groupparent='0' 
			".(sizeof($Group)>0?"and `group`.`group` in (".implode(",",$Group).")":" and group.group = 0 ")."
			order by group.name;";
			
			//if ($REMOTE_ADDR=="78.107.149.10")
				//echo "<br>".$sql;
			
			//exit;
			$result = mysql_query($sql);
			while ($rows = mysql_fetch_array($result))
			{
				
				if ($rows["group"] == 407) {
				
					$StrMenu407 .= "<a href=index.php?group=".$rows["group"].($search == 'revaluation'?"&search=revaluation":($search == 'newcoins'?"&search=newcoins":"&materialtype=".$materialtype)).($searchid?"&searchid=".$searchid:"").($nocheck?"&nocheck=".$nocheck:"")." title='".$MaterialTypeArrayTitle[$materialtype]." ".$rows["name"]."'>".($group==$rows["group"]?"<font color=black>".$rows["name"]."</font>":$rows["name"])."</a><br>";
					$StrMenuEval407 .= "echo \"<a href=index.php?group=".$rows["group"].($search == 'revaluation'?"&search=revaluation\"":($search == 'newcoins'?"&search=newcoins\"":"&materialtype=\".\$materialtype")).".(\$searchid?\"&searchid=\".\$searchid:\"\").(\$nocheck?\"&nocheck=\".\$nocheck:\"\").\" title='\".\$MaterialTypeArrayTitle[\$materialtype].\" ".$rows["name"]."'>\".(\$group==".$rows["group"]."?\"<font color=black>".$rows["name"]."</font>\":\"".$rows["name"]."\").\"</a><br>\";";
					
					if (is_array($GroupArray[$rows["group"]]))
					{
						foreach ($GroupArray[$rows["group"]] as $key=>$value)
						{
							$StrMenu407 .= "<img src=".$in."images/folderfor.gif> <a href=index.php?group=".$key.($search == 'revaluation'?"&search=revaluation":($search == 'newcoins'?"&search=newcoins":"&materialtype=".$materialtype)).($searchid?"&searchid=".$searchid:"").($nocheck?"&nocheck=".$nocheck:"")." class=star title='".$MaterialTypeArrayTitle[$materialtype]." ".$value."'>".($group==$key?"<font color=black>".$value."</font>":$value)."</a><br>";
							$StrMenuEval407 .= "echo \"<img src=\".\$in.\"images/folderfor.gif> <a href=index.php?group=".$key.($search == 'revaluation'?"&search=revaluation\"":($search == 'newcoins'?"&search=newcoins\"":"&materialtype=\".\$materialtype")).".(\$searchid?\"&searchid=\".\$searchid:\"\").(\$nocheck?\"&nocheck=\".\$nocheck:\"\").\" class=star title='\".\$MaterialTypeArrayTitle[\$materialtype].\" ".$value."'>\".(\$group==".$key."?\"<font color=black>".$value."</font>\":\"".$value."\").\"</a><br>\";";
						}
					}
				}
				elseif($rows["group"] == 1604) {
					$StrMenu1604 .= "<a href=index.php?group=".$rows["group"].($search == 'revaluation'?"&search=revaluation":($search == 'newcoins'?"&search=newcoins":"&materialtype=".$materialtype)).($searchid?"&searchid=".$searchid:"").($nocheck?"&nocheck=".$nocheck:"")." title='".$MaterialTypeArrayTitle[$materialtype]." ".$rows["name"]."'>".($group==$rows["group"]?"<font color=black>".$rows["name"]."</font>":$rows["name"])."</a><br>";
					$StrMenuEval1604 .= "echo \"<a href=index.php?group=".$rows["group"].($search == 'revaluation'?"&search=revaluation\"":($search == 'newcoins'?"&search=newcoins\"":"&materialtype=\".\$materialtype")).".(\$searchid?\"&searchid=\".\$searchid:\"\").(\$nocheck?\"&nocheck=\".\$nocheck:\"\").\" title='\".\$MaterialTypeArrayTitle[\$materialtype].\" ".$rows["name"]."'>\".(\$group==".$rows["group"]."?\"<font color=black>".$rows["name"]."</font>\":\"".$rows["name"]."\").\"</a><br>\";";
					if (is_array($GroupArray[$rows["group"]]))
					{
						foreach ($GroupArray[$rows["group"]] as $key=>$value)
						{
							$StrMenu1604 .= "<img src=".$in."images/folderfor.gif> <a href=index.php?group=".$key.($search == 'revaluation'?"&search=revaluation":($search == 'newcoins'?"&search=newcoins":"&materialtype=".$materialtype)).($searchid?"&searchid=".$searchid:"").($nocheck?"&nocheck=".$nocheck:"")." class=star title='".$MaterialTypeArrayTitle[$materialtype]." ".$value."'>".($group==$key?"<font color=black>".$value."</font>":$value)."</a><br>";
							$StrMenuEval1604 .= "echo \"<img src=\".\$in.\"images/folderfor.gif> <a href=index.php?group=".$key.($search == 'revaluation'?"&search=revaluation\"":($search == 'newcoins'?"&search=newcoins\"":"&materialtype=\".\$materialtype")).".(\$searchid?\"&searchid=\".\$searchid:\"\").(\$nocheck?\"&nocheck=\".\$nocheck:\"\").\" class=star title='\".\$MaterialTypeArrayTitle[\$materialtype].\" ".$value."'>\".(\$group==".$key."?\"<font color=black>".$value."</font>\":\"".$value."\").\"</a><br>\";";
						}
					}
				}
				else {
					
					if($rows["group"] == 816 && $materialtype==3){
				
						$StrMenu .= "<a href=albom_dlya_monet.html title='".$MaterialTypeArrayTitle[$materialtype]." ".$rows["name"]."'>".($group==$rows["group"]?"<font color=black>".$rows["name"]."</font>":$rows["name"])."</a><br>";
						$StrMenuEval .= "echo \"<a href=albom_dlya_monet.html title='\".\$MaterialTypeArrayTitle[\$materialtype].\" ".$rows["name"]."'>\".(\$group==".$rows["group"]."?\"<font color=black>".$rows["name"]."</font>\":\"".$rows["name"]."\").\"</a><br>\";";
					}
					else {
						$StrMenu .= "<a href=index.php?group=".$rows["group"].($search == 'revaluation'?"&search=revaluation":($search == 'newcoins'?"&search=newcoins":"&materialtype=".$materialtype)).($searchid?"&searchid=".$searchid:"").($nocheck?"&nocheck=".$nocheck:"")." title='".$MaterialTypeArrayTitle[$materialtype]." ".$rows["name"]."'>".($group==$rows["group"]?"<font color=black>".$rows["name"]."</font>":$rows["name"])."</a><br>";
						$StrMenuEval .= "echo \"<a href=index.php?group=".$rows["group"].($search == 'revaluation'?"&search=revaluation\"":($search == 'newcoins'?"&search=newcoins\"":"&materialtype=\".\$materialtype")).".(\$searchid?\"&searchid=\".\$searchid:\"\").(\$nocheck?\"&nocheck=\".\$nocheck:\"\").\" title='\".\$MaterialTypeArrayTitle[\$materialtype].\" ".$rows["name"]."'>\".(\$group==".$rows["group"]."?\"<font color=black>".$rows["name"]."</font>\":\"".$rows["name"]."\").\"</a><br>\";";
					}
					
					if (is_array($GroupArray[$rows["group"]]))
					{
						foreach ($GroupArray[$rows["group"]] as $key=>$value)
						{
							$StrMenu .= "<img src=".$in."images/folderfor.gif> <a href=index.php?group=".$key.($search == 'revaluation'?"&search=revaluation":($search == 'newcoins'?"&search=newcoins":"&materialtype=".$materialtype)).($searchid?"&searchid=".$searchid:"").($nocheck?"&nocheck=".$nocheck:"")." class=star title='".$MaterialTypeArrayTitle[$materialtype]." ".$value."'>".($group==$key?"<font color=black>".$value."</font>":$value)."</a><br>";
							$StrMenuEval .= "echo \"<img src=\".\$in.\"images/folderfor.gif> <a href=index.php?group=".$key.($search == 'revaluation'?"&search=revaluation\"":($search == 'newcoins'?"&search=newcoins\"":"&materialtype=\".\$materialtype")).".(\$searchid?\"&searchid=\".\$searchid:\"\").(\$nocheck?\"&nocheck=\".\$nocheck:\"\").\" class=star title='\".\$MaterialTypeArrayTitle[\$materialtype].\" ".$value."'>\".(\$group==".$key."?\"<font color=black>".$value."</font>\":\"".$value."\").\"</a><br>\";";
						}
					}
				}
			}
			$StrMenu = $StrMenu407.$StrMenu.$StrMenu1604;
			$StrMenuEval = $StrMenuEval407.$StrMenuEval.$StrMenuEval1604;
		}
	}
	
	if ($topalonesave == 1)
	{
		if ( $search == 'newcoins')
			if ($cookiesuser==811) {
		
				if ($nocheck) {
					$fp=fopen("topaloneadminchecknewcoins.php","w");
				}
				else {
					$fp=fopen("topaloneadminnewcoins.php","w");
				}
			}
			else {
				$fp=fopen("topalonenewcoins.php","w");
			}
		elseif ( $search == 'revaluation')
			if ($cookiesuser==811) {
		
				if ($nocheck) {
					$fp=fopen("topaloneadmincheckrevaluation.php","w");
				}
				else {
					$fp=fopen("topaloneadminrevaluation.php","w");
				}
			}
			else {
				$fp=fopen("topalonerevaluation.php","w");
			}
		elseif (intval($materialtype))
			if ($cookiesuser==811 && $materialtype!=3 && $materialtype!=5) {
		
				if ($nocheck) {
					$fp=fopen("topaloneadmincheck".$materialtype.".php","w");
				}
				else {
					$fp=fopen("topaloneadmin".$materialtype.".php","w");
				}
			}
			else {
				$fp=fopen("topalone".$materialtype.".php","w");
			}
		
		if ($fp)
		{
			$StrMenuEval = "<? 
			".$StrMenuEval."
			?>";
			fwrite($fp,$StrMenuEval);
			fclose($fp);
		}
		echo $StrMenu;
	}
	else
	{
		echo $StrMenu;
	}
	//echo $StrMenu;
	
table_down ("1", "100%"); 
if ($materialtype == 1 || $materialtype == 8) {
	
	table_top ("100%", 0, "<img src=".$in."images/folder.gif alt='Монеты по тематикам'> Обзор по тематикам:", 0);
	
	foreach ($ThemeArray as $key=>$value)
			echo "<a href=index.php?theme=".$key.($materialtype?"&materialtype=".$materialtype:"").($group?"&group=".$group:"").($searchid?"&searchid=".$searchid:"").($nocheck?"&nocheck=".$nocheck:"")." title='".$MaterialTypeArrayTitle[$materialtype]." по теме - ".$value."'>".($theme==$key?"<font color=black>".$value."</font>":$value)."</a><br>";
	
	table_down ("1", "100%"); 
}
if ($materialtype != 2 && $materialtype != 3 && $materialtype != 5 && $materialtype != 10) {
	
	table_top ("100%", 0, "<img src=".$in."images/folder.gif alt='Монеты по металлам'> Обзор по металлам:", 0);
		
	echo $TopMetalStr;
		
	table_down ("1", "100%"); 
}

?>

</td>
<td width=15 background=<?=$in?>images/backgr1.gif>&nbsp;</td>
<td valign=top width=86%><div id=showformcall></div>
<?
//
//table_top ("100%", 0, $tmptitle, 1);
echo $MainText;

echo $linkbottomtext."<br>";

if (!$page || $page=="recommendation" || $page=="show") {
	
	echo $textbottompage;
	
}
table_down ("1", "100%");
echo  $tpl['msg'];*/?>
<div class="wraper" style="height:200px">

<h1>Сдесь идет основной контент главной страницы</h1>

</div>

<div class="wraper clearfix central_banner" >
    <?=$tpl['banners']['main_center_1']?>
    <?=$tpl['banners']['main_center_2']?>
</div>