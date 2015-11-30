<?

if ($materialtype)
	$arraykeyword[] = strip_tags($MaterialTypeArray[$materialtype]);

if ($page=="recommendation" && $cookiesuser) {
	$module = 'recommendation';
}

if ($cookiesuser == 309236) //нижний новгород опт
	$WhereArray[] = " shopcoins.realization = 0 ";


if ($materialtype == 3 or $materialtype == 5)
	$WhereArray[] = " shopcoins.`amount` > '0' ";

if ($pricestart) {
	$pricestart = floatval($pricestart);
	$WhereArray[] = " shopcoins.`price` >= '".$pricestart."'";
}
if ($priceend) {
	$priceend = floatval($priceend);
	$WhereArray[] = " shopcoins.`price` <= '".$priceend."'";
}
if ($theme) {
	$theme = intval($theme);
	$WhereArray[] .= " (shopcoins.theme='".pow(2,$theme)."' or shopcoins.theme & ".pow(2,$theme).">0)";
}

if ($series)
{
	$series = intval($series);
	$WhereArray[] = " shopcoins.series = '".$series."' ";
}

if ($yearstart) {
	$yearstart = intval($yearstart);
	$WhereArray[] = " shopcoins.`year` >= '".$yearstart."'";
}
if ($yearend) {
	$yearend = intval($yearend);
	$WhereArray[] = " shopcoins.`year` <= '".$yearend."'";
}
if ($metal) {
	$metal = str_replace("'","",$metal);
	$WhereArray[] = " shopcoins.`metal` = '".$metal."'";
}
if ($condition) {
	$condition = str_replace("'","",$condition);
	$WhereArray[] = " shopcoins.`condition` = '".$condition."'"; 
}
if ($search) {
	$search = str_replace("'","",$search);
	
	if ($search == 'revaluation') {
	
		$WhereArray[] = " (oldprice>0 )";
	}
	elseif ($search == 'newcoins') {
	
		$WhereArray[] = " shopcoins.materialtype in(1,4,7,8) and shopcoins.year in(".implode(",",$arraynewcoins).")";
	}
	else { 
	
		$SearchTemp = explode(' ',urldecode($search));
		unset($SearchTempDigit);
		unset($SearchTempStr);
		unset($SearchTempMatch);
		unset($WhereThemesearch);
		$tigger=0;
		if (sizeof($SearchTemp)>0) {
			for ($i=0;$i<sizeof($SearchTemp);$i++) {
				
				if (!trim($SearchTemp[$i]))
					continue;
				
				if (intval($SearchTemp[$i]) ) {
					
					if ( ($SearchTemp[$i-1] && intval($SearchTemp[$i-1])) || !$SearchTemp[$i+1] || intval($SearchTemp[$i+1])) { 
						
						$SearchTempMatch[] = $SearchTemp[$i];
						$tigger=0;
					}
					else 
						$tigger=1;
					
					$SearchTempDigit[] = $SearchTemp[$i];
				}
				else {
					
					$SearchTemp[$i] = str_replace("+"," ",$SearchTemp[$i]);
					$SearchTempStr[] = (strlen(trim($SearchTemp[$i]))>4?substr(trim($SearchTemp[$i]),0,strlen(trim($SearchTemp[$i]))-1):trim($SearchTemp[$i]));
					if ($i>0)
						$SearchTempMatch[] = ">".(strlen(trim($SearchTemp[$i]))>4?substr(trim($SearchTemp[$i]),0,strlen(trim($SearchTemp[$i]))-1)."*":trim($SearchTemp[$i]));
					else
						$SearchTempMatch[] = (strlen(trim($SearchTemp[$i]))>4?substr(trim($SearchTemp[$i]),0,strlen(trim($SearchTemp[$i]))-1)."*":trim($SearchTemp[$i]));
					
					if ($tigger==1) {
					
						$SearchTempMatch[] = '"'.$SearchTemp[$i-1]." ".trim($SearchTemp[$i]).'"';
						$SearchTempMatch[] = $SearchTemp[$i-1];
						$tigger=0;
					}
					
				}
				
				foreach ($ThemeArray as $key=>$value) {
					if (stristr($SearchTemp[$i],$value) ) {
						$WhereThemesearch[] = "`theme` & ".pow(2,$key).">0";
					}
				}
			
			}	
		}
		
		if (sizeof($SearchTempStr)) {
		
			$sql_temp = "select distinct `group`.`name`, `group`.* from `group`, `shopcoins` 
						where ((".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") ".($show50?"or shopcoins.check=50":"").") and shopcoins.`group`=`group`.`group`
						and (".(sizeof($SearchTempStr)?"`group`.`name` like '%".implode("%' or `group`.`name` like '%",$SearchTempStr)."%')":"").";";
			//echo $sql_temp."<br>";
			$result_temp = mysql_query($sql_temp);
	//		unset($WhereCountryes);
	//		$WhereCountryes = Array();
			while ($rows_temp = mysql_fetch_array($result_temp)) {
			
				$WhereCountryes[] = $rows_temp['group'];
				if ($rows_temp['groupparent']==0) {
					
					$sql_tmp = "select distinct `group`.`group` from `group`, shopcoins where (".($cookiesuser==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and shopcoins.`group`=`group`.`group` and `group`.groupparent='".$rows_temp['group']."';";
					$result_tmp = mysql_query($sql_tmp);
					//echo $sql_tmp."<br>";
					while ($rows_tmp = mysql_fetch_array($result_tmp)) {
						$WhereCountryes[] = $rows_tmp['group'];
					}
				}
			}
		}
		
		$CounterSQL = '';
		$CounterSQL = (sizeof($SearchTempMatch)?" MATCH(shopcoins.`name`,shopcoins.details,shopcoins.metal,shopcoins.number,shopcoins.condition) AGAINST('".implode(" ",$SearchTempMatch)." ' IN BOOLEAN MODE) as coefficientcoins, if(`group`.`name` like '%".implode("%' or `group`.`name` like '%",$SearchTempStr)."%', 3,0) as coefficientgroup":"");
		
		if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit)) {
		
			$CounterSQL .= ", if(".(sizeof($WhereThemesearch)?implode(" or ",$WhereThemesearch).", ".(sizeof($SearchTempDigit)?"if( shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,3,2)":"2").",".(sizeof($SearchTempDigit)?" if( shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,1.5,0)":"0"):" shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0,1.5,0").") as counterthemeyear";
		}
		
		$WhereArray[] = " ( ".(sizeof($SearchTempStr)?"((shopcoins.details like '%".implode("%' or shopcoins.details like '%",$SearchTempStr)."%') and shopcoins.details<>'')":"")."
		".(sizeof($SearchTempDigit)?"or ((shopcoins.details like '".implode("' or shopcoins.details like '",$SearchTempDigit)."') and shopcoins.details<>'')":"")."
		".(sizeof($SearchTempStr)?"or shopcoins.number in ('".implode("','",$SearchTemp)."')":"")."
		".(sizeof($SearchTempStr)?"or shopcoins.number2 in ('".implode("','",$SearchTemp)."')":"")."
		".(sizeof($SearchTempDigit)?"or (shopcoins.year in ('".implode("','",$SearchTempDigit)."') and shopcoins.year<>0)":"")."
		".(sizeof($SearchTempStr)?"or ((shopcoins.metal like '%".implode("%' or shopcoins.metal like '%",$SearchTempStr)."%') and shopcoins.metal<>'')":"")."
		".(sizeof($SearchTempStr)?"or ((shopcoins.condition like '%".implode("%' or shopcoins.condition like '%",$SearchTempStr)."%') and shopcoins.condition<>'')":"")."
		".(sizeof($SearchTempStr)?"or (shopcoins.`name` like '%".implode("%' or shopcoins.`name` like '%",$SearchTempStr)."%')":"")." 
		".(sizeof($WhereThemesearch)?" or ".implode(" or ",$WhereThemesearch):"")." ".(sizeof($WhereCountryes)>0?" or shopcoins.`group` in (".implode(",",$WhereCountryes).")":"").")";
	}
	
}
if ($group and !$page){
	$group = intval($group);
	
	$sql = "select * from `group` where `group`='$group';";
	$result = mysql_query($sql);
	$rows = mysql_fetch_array($result);
	$GroupName = $rows["name"];
	$GroupNameMain = '';
	$grouphref = strtolower_ru($GroupName)."_gn".$rows['group'];
	
	if ($rows["groupparent"] != 0 && $rows["groupparent"] != $rows["group"]) {
	
		$sql_g = "select name from `group` where `group`='".$rows["groupparent"]."';";
		$result_g = mysql_query($sql_g);
		$rows_g = mysql_fetch_array($result_g);
		$GroupNameMain = $rows_g['name'];
	}
	
	if (trim($rows["description"]))
	{
		$text = substr($rows["description"], 0, 650);
		$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
		
		$pic = Array();
		if ($rows["flagsmall"]) {
			$pic[] = $in."group/smallimages/".$rows["flagsmall"];
			if (file_exists($in."group/smallimages/".$rows["flagsmall"]))
				$flag[] = 1;
		}
		if ($rows["emblemsmall"]) {
			$pic[] = $in."group/smallimages/".$rows["emblemsmall"];
			if (file_exists($in."group/smallimages/".$rows["emblemsmall"]))
				$flag[] = 1;
		}
		if ($rows["mapsmall"]){
			$pic[] = $in."group/smallimages/".$rows["mapsmall"];
			if (file_exists($in."group/smallimages/".$rows["mapsmall"]))
				$flag[] = 1;
		}
		
		$sesId = cookiesWork() ? '' : '&'.SID;
			
		//vbhjckfd
		if (sizeof($flag) > 1)
			$GroupDescription = "<table border=0 cellpadding=2 cellspacing=1 align=center>
			<tr class=tboard bgcolor=#EBE4D4>
			<td valign=top><img src=".$pic[0]." border=1></td>
			<td valign=top>".str_replace("\n","<br>",$text)."
			<!--<br><a href=# onclick=\"javascript:window.open('groupdescription.php?group=$group$sesId','groupdescription$group','width=600,height=700,top=0,left=0,status=no,menubar=no,scrollbars=yes,resizable=no');\">Далее >>></a>--></td>
			<td valign=top><img src=".$pic[1]." border=1></td>
			</tr>
			</table>";
		elseif (sizeof($flag) == 1)
			$GroupDescription = "<table border=0 cellpadding=2 cellspacing=1 align=center>
			<tr class=tboard bgcolor=#EBE4D4>
			<td valign=top><img src=".$pic[0]." border=1></td>
			<td valign=top>".str_replace("\n","<br>",$text)."
			<!--<br><a href=# onclick=\"javascript:window.open('groupdescription.php?group=$group$sesId','groupdescription$group','width=600,height=700,top=0,left=0,status=no,menubar=no,scrollbars=yes,resizable=no');\">Далее >>></a>--></td>
			</tr>
			</table>";
		else
			$GroupDescription = "<table border=0 cellpadding=2 cellspacing=1 align=center>
			<tr class=tboard bgcolor=#EBE4D4>
			<td valign=top>".str_replace("\n","<br>",$text)."
			<!--<br><a href=# onclick=\"javascript:window.open('groupdescription.php?group=$group$sesId','groupdescription$group','width=600,height=700,top=0,left=0,status=no,menubar=no,scrollbars=yes,resizable=no');\">Далее >>></a>--></td>
			</tr>
			</table>";
		
		
		unset ($text);
		unset ($pic);
	}
	
	$sql_group = "select * from `group` where groupparent = '$group';";
	$result_group = mysql_query($sql_group);
	if (mysql_num_rows($result_group))
	{
		$GroupArray = Array();
		while ($rows_group = mysql_fetch_array($result_group))
			$GroupArray[] = $rows_group[0];
	}
	
	$WhereArray[] = " (shopcoins.`group` = '$group' 
	".(sizeof($GroupArray)>0?"or shopcoins.`group` in (".implode(",",$GroupArray).")":"").")";
}


if ($group and !$page and $materialtype!=12)
{
	$sql = "select distinct `name` $groupselect from shopcoins $where order by param2,param1,`name`;";
	$sql = "select distinct `name` from shopcoins $where order by `name`;";
	$result = mysql_query($sql);
	if (mysql_num_rows($result))
	{
		//if ($GroupDescription)
		//	$MainText .= "<br>".$GroupDescription."<br>";
			
		$MainText .= "<table border=0 cellpadding=3 cellspacing=1 align=center width=99%>";
		//$nanananna = "<tr bgcolor=#ffcc66 class=tboard><td><b>".(!$sortname?"<a href=$script?pagenum=$pagenum"."$addhref"."&sortname=1>Сортировать по номиналу</a>":"<a href=$script?pagenum=$pagenum"."$addhref".">Сортировать по дате</a>")." <span style='background:#ffcc66;'>$GroupName</span> :</b> ";
		$i=0;
		$colspan = 8;
		$proctd = floor(100/$colspan)."%";
		while ($rows = mysql_fetch_array($result)) {
			
			if ($i%$colspan == 0) 
				$MainText .= ($i>0?"</tr>":"")."<tr bgcolor=#ffcc66 class=tboard>";
			
			$MainText .= " <td width=$proctd valign=top><a href=$script?searchname=".urlencode($rows["name"]).$addhref." title='Показать только ".$rows["name"]."'>".($searchname==$rows["name"]?"<span style='background:#fff8e8'>".$rows["name"]."</span>":$rows["name"])."</a></td>";
			//$NameArray[] = " &nbsp;<a href=$script?searchname=".urlencode($rows["name"]).$addhref." title='Показать только ".$rows["name"]."'>".($searchname==$rows["name"]?"<span style='background:#fff8e8'>".$rows["name"]."</span>":$rows["name"])."</a>";
			$i++;
		}
		if ($i>$colspan && $i%$colspan>0)
			for ($j=$i%$colspan;$j<$colspan;$j++) 
				$MainText .= "<td width=$proctd>&nbsp;</td>";
		//$MainText .= implode($NameArray,",");
		$MainText .= "</tr></table>";
		
		if ($materialtype!=3 && $materialtype!=5 && $searchname) {
		
			$sqlyear = "select distinct `year` from shopcoins $where and shopcoins.name='".str_replace("'","",$searchname)."' and shopcoins.year>0 order by `year`;";
			$resultyear = mysql_query($sqlyear);
			if (mysql_num_rows($resultyear))
			{
			
				$MainText .= "<br><table border=0 cellpadding=3 cellspacing=1 align=center width=99%>";
				$i=0;
				$colspan = 16;
				$proctd = floor(100/$colspan)."%";
				while ($rowsyear = mysql_fetch_array($resultyear)) {
					
					if ($i%$colspan == 0) 
						$MainText .= ($i>0?"</tr>":"")."<tr bgcolor=#ffcc66 class=tboard>";
					
					$MainText .= " <td width=$proctd valign=top><a href=$script?searchname=".urlencode($searchname)."&yearsearch=".$rowsyear["year"].$addhref." title='Показать только ".$rowsyear["year"]."'>".($yearsearch==$rowsyear["year"]?"<span style='background:#fff8e8'>".$rowsyear["year"]."</span>":$rowsyear["year"])."</a></td>";
					$i++;
				}
				if ($i>$colspan && $i%$colspan>0)
					for ($j=$i%$colspan;$j<$colspan;$j++) 
						$MainText .= "<td width=$proctd>&nbsp;</td>";
				$MainText .= "</tr></table>";
			}
		}
	}
	
	$sql_series = "select distinct series from shopcoins where (`check`=1 or `check`>=4) and materialtype='$materialtype';";
	$result_series = mysql_query($sql_series);
	while ($rows_series = mysql_fetch_array($result_series))
		$series_array[] = $rows_series["series"];
	
	if (sizeof($series_array))
	{
		$sql_series = "select * from shopcoinsseries where `group`='$group' and shopcoinsseries in (".implode(",", $series_array).");";
		//echo $sql_series;
		$result_series = mysql_query($sql_series);
		if (mysql_num_rows($result_series)>=1)
		{
			$MainText .= "<br><table border=0 cellpadding=3 cellspacing=1 align=center width=99%>";
			while ($rows_series = mysql_fetch_array($result_series))
			{
				//if (!$series)
					$series_name[$rows_series["shopcoinsseries"]] = $rows_series["name"];
				
				$MainText .= "<tr><td bgcolor=#ffcc66 class=tboard>";
				if ($rows_series["shopcoinsseries"]==$series)
					$MainText .= "<b>".$rows_series["name"]."</b>";
				else
					$MainText .= "<a href=$script?series=".$rows_series["shopcoinsseries"].$addhref.">".$rows_series["name"]."</a>";
						
				$MainText .= "</td></tr>";
			}
			$MainText .= "</table>";
		}
		
	}
	if ($series)
		$addhref .= "&series=".$series;
}

?>