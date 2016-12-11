<?
include_once $DOCUMENT_ROOT."/config.php";
include_once $DOCUMENT_ROOT."/funct.php";
include "config.php";
include_once "funct.php";

include_once $DOCUMENT_ROOT."/new/helpers/contentHelper.php";

function ShowTable ($CoinsArray)
{
  global $in, $year, $js, $materialtype_admin;
  $StrResult = "<table border=1 cellpadding=2 cellspacing=0 width=100%>";
  $i = 0;
  if (!sizeof($CoinsArray))
    return 2323;

  //echo print_r($CoinsArray);
  $j = 0;
  for ($k=0; $k<sizeof($CoinsArray); $k++)
  {
    if ($i%3 == 0 and $i!=0 and ($materialtype_admin==1 or $materialtype_admin==2))
		$StrResult .= "</tr><tr><td height=5 colspan=3></td></tr><tr valign=top>";
	elseif ($i == 0 and ($materialtype_admin==1 or $materialtype_admin==2))
		$StrResult .= "<tr valign=top>";
	elseif ($materialtype_admin!=1 and $materialtype_admin!=2)
		$StrResult .= "<tr valign=top>";
	
    $AddDetails = 0;

    if (trim($CoinsArray[$k]["details"]))
      $AddDetails = 1;

    if ($j == 0 and (($year>=$CoinsArray[$k]["yearstart"] and ($CoinsArray[$k]["yearend"]>0 and $year<=$CoinsArray[$k]["yearend"])) or $year==$CoinsArray[$k]["yearstart"]))
    {
      $js = "InWord(".$CoinsArray[$k]["catalog"].",".$AddDetails.");";
    }

    $StrResult .= "<td class=tboard ";
    if (($year>=$CoinsArray[$k]["yearstart"] and ($CoinsArray[$k]["yearend"]>0 and $year<=$CoinsArray[$k]["yearend"])) or $year==$CoinsArray[$k]["yearstart"])
      $StrResult .= " bgcolor=red ";

    $StrResult .= "width=200>
    <img id=img".$CoinsArray[$k]["catalog"]." src=".$in."catalognew/".$CoinsArray[$k]["image_small_url"]." alt='".$CoinsArray[$k]["name"]."' border=1 onclick=\"ShowInfoBox(event); showBusyLayer(); LoadCoinInfo(". $CoinsArray[$k]["catalog"] .");\">
    </td>
    <td class=tboard width=".($materialtype_admin==1||$materialtype_admin==2?20:100)."%>
    <input type=radio name=parent value='".$CoinsArray[$k]["catalog"]."' onclick=\"InWord(".$CoinsArray[$k]["catalog"].",".$AddDetails.");\" ";

    if ($j==0 and (($year>=$CoinsArray[$k]["yearstart"] and ($CoinsArray[$k]["yearend"]>0 and $year<=$CoinsArray[$k]["yearend"])) or $year==$CoinsArray[$k]["yearstart"]))
      $StrResult .= " checked>";
    else
      $StrResult .= ">";

    if ($j == 0 and (($year>=$CoinsArray[$k]["yearstart"] and ($CoinsArray[$k]["yearend"]>0 and $year<=$CoinsArray[$k]["yearend"])) or $year==$CoinsArray[$k]["yearstart"]))
    {
      $j++;
    }

    $StrResult .= "<br><span id=name". $CoinsArray[$k]["catalog"] ."><b>".$CoinsArray[$k]["name"]."</b></span>";
    if (trim($CoinsArray[$k]["mname"]))
      $StrResult .= "<br><span id=metal". $CoinsArray[$k]["catalog"] ."><b>Металл: </b>".$CoinsArray[$k]["mname"].'</span>';

    if ($CoinsArray[$k]["yearstart"])
      $StrResult .= "<br><span id=year". $CoinsArray[$k]["catalog"] ."><b>Год: </b>".$CoinsArray[$k]["yearstart"].'</span>';


    $display =  ( trim($CoinsArray[$k]["condition"]) ? 'inline' :  'none');
    $StrResult .= "<br style='display: $display'><span style='display: $display' id=status". $CoinsArray[$k]["catalog"] ."><b>Состояние: <font color=blue>".$CoinsArray[$k]["condition"]."</font></b></span>";

    $display =  ( trim($CoinsArray[$k]["details"]) ? 'inline' :  'none');
    {
      $text = substr($CoinsArray[$k]["details"], 0, 250);
      $text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
    }
    $StrResult .= "<br style='display: $display'><span style='display: $display' id=descr". $CoinsArray[$k]["catalog"] ."><b>Описание: </b>".$text.'</span>';
	
	//if (!file_exists($in."catalognew/".$CoinsArray[$k]["image_big_url"])) $StrResult .= "<br style='display: $display'><span style='display: $display' id=bigim". $CoinsArray[$k]["catalog"] ."><b>Прим.: </b>Увеличенное изображение отсутствует и будет добавленно в каталог по умолчанию</span>";


    $StrResult .= "</td>";
    $i++;
  }

	if ($i%3 == 1 and ($materialtype_admin==1 or $materialtype_admin==2))
		$StrResult .= "<td colspan=2></td></tr>";
	elseif ($i%3 == 2 and ($materialtype_admin==1 or $materialtype_admin==2))
		$StrResult .= "<td></td></tr>";
	elseif ($i%2 == 0 and ($materialtype_admin==1 or $materialtype_admin==2))
		$StrResult .= "</tr>";
	elseif ($materialtype_admin!=1 and $materialtype_admin!=2)
		$StrResult .= "</tr>";


  $StrResult .= "</table>";

  return $StrResult;
}

$tpl['error'] = "";

if (!$tpl['user']['user_id']){
	$tpl['error'] = "Вы не авторизованы!";
} else {
	$coins = (int) request('coins');
	if(!in_array($cookiesuser,$ArrayUsers))	$coins = 0;
	
	$user = $tpl['user']['user_id'];
	
	$sql_t = "select count(*) from shopcoinswriteusernum where `user`='$user' and `check`=0 and shopcoinswrite='$coins';";
	$rows_t = $shopcoins_class->getOneSql($sql_t);
	
	if ($rows_t) {
		$tpl['error'] = "Данную монету Вы отметили как неизвестную Вам.<br>";
		$coins = 0;
	}
	
	if (!$coins) {	
		$tpl['error'] =  "Ошибочный запрос";
	} elseif($next) {			
		
		$sql = "insert into `shopcoinswriteusernum` (`shopcoinswriteusernum`,`user`,`amount`,`dateinsert`,`check`,shopcoinswrite)
			value (NULL,'$user','$tig','".time()."','0','$coins');";
		$result = mysql_query($sql);
		
		$sql_up = "update shopcoinswrite set `user`='0', reservetime='0' where shopcoinswrite='$coins';";
		mysql_query($sql_up);
		echo "
					<br><center><b class=tboard>Пропущено. 
					<br><a href=# onclick='javascript:window.opener.location=\"showcoins.php?&pagenum=".$pagenum."\";window.close();'>Закрыть окно</a></b></center>
					</body>
					</html>";
					exit;
	} else {
		
		$sql_main = "select * from shopcoinswrite where shopcoinswrite='".$coins."' and `check`=1 and (reservetime<'".time()."' or `user`='$user');";

		$rows_main = $shopcoins_class->getOneSql($sql_main);
	var_dump($rows_main);
		if (mysql_num_rows($result_main)==1) {
		
			if (mysql_num_rows($result_main)==1 && $user && $user!=811) {
			
				$sql_up = "update shopcoinswrite set `user`='$user', reservetime='".(time()+$reserve)."' where shopcoinswrite='$coins';";
				mysql_query($sql_up);
			}
			
			if (($submit or $submitaftererror) && $testuser == 0 && mysql_num_rows($result_main)==1)
			{
				
				$materialtype = $rows_main['materialtype'];
				
				//проверка на заполнение поле
				
				$tig = 0;
				unset($error);
				
				if ($group) {
					$group = htmlspecialchars(trim($group), ENT_COMPAT,'ISO-8859-1', true);
					$group = str_replace("'","",$group);
					if ($materialtype>1) {
					
						$sql5 = "select count(*) from `group` where trim(`name`)='".$group."' and `type`='shopcoins';";
						$result5 = mysql_query($sql5);
						$rows5 = mysql_fetch_array($result5);
						if ($rows5[0]<1)
							$error[] = "Укажите страну из предложенных Вам в подсказке";
					}
				}
				else {
				
					$error[] = "Не указана страна";
				}	
				$name = trim($name);
				if ($name) {
					$name = htmlspecialchars(trim($name), ENT_COMPAT,'ISO-8859-1', true);
					$name = str_replace("'","",$name);
				}
				else {
				
					$error[] = "Не указан номинал";
				}
					
				$year = trim($year);
				if (!intval($year)){
					$year=0;
					if ($materialtype==4)
						$error[] = "Не указан год";
				}
				else 
					$tig++;
					
				if ($metal) {
					$tig++;
					$metal = $MetalArray[$metal];
				}
				
				if ($condition) {
					$tig++;
					$condition = $ConditionArray[$condition];
				}
				elseif($materialtype==4)
					$error[] = "Не указано состояние";
					
	
				$details = htmlspecialchars(trim($details), ENT_COMPAT,'ISO-8859-1', true);
				$details = str_replace("'"," ",$details);
					
				if ($details) {
					$tig++;
					if ($materialtype==4 && strlen($details)<200)
						$error[] = "Описание короче 200 символов";
					if ($materialtype==7 && strlen($details)<25)
						$error[] = "Описание короче 25 символов";
				}
				elseif ($materialtype==4 || $materialtype==7)
					$error[] = "Нет описания";
				
				
				if ($tig>=1 && !sizeof($error))
				{
					
					$typework=1;
					
					if (intval($_POST['copycoins'])>0) 
						setcookie("copycoins", intval($_POST['copycoins']), time() + 2*3600, "/");
					
					if ($materialtype==4 || $materialtype==7)
						$typework=8;
					
					$sql = "update shopcoinswrite set
					`name`='$name',
					`group`='$group',
					`year`='$year',
					`metal`='$metal',
					`details`='$details',
					`condition`='$condition',
					`user`='$user',
					`datewrite`='".time()."',
					`check`=2
					where shopcoinswrite='$coins';";
					$result = mysql_query($sql);
					//echo $sql;
					
					$sql = "insert into `shopcoinswriteusernum` (`shopcoinswriteusernum`,`user`,`amount`,`dateinsert`,`check`,shopcoinswrite)
						value (NULL,'$user','$tig','".time()."','1','$coins');";
					$result = mysql_query($sql);
					
					$sql = "delete from shopcoinswriteusernum where shopcoinswrite='$coins' and `check`=0;";
					$result = mysql_query($sql);
					
					$sql = "insert into `distantionuser` (`distantionuser`,`user`,`sum`,`dateinsert`,`check`,`type`)
							value (NULL,'$cookiesuser','".$ArrayPriceWork[$typework]."','".time()."',1,$typework);";
					$result = mysql_query($sql);
						
	
					$body =  $top_html."<form action=addparent.php method=post> 
							<p class=txt><img src=images/".$rows_main['image_big']." border=1 align=left> <br>Для связки монеты выберите аналогичную монету в предложенном ниже списке монет  и нажмите \"связать\".<br>
							Если в списке отсутствует подобная Вами описанной монете либо Вы сомневаетесь в аналогичности монеты, то нажмите \"Пропустить\" либо \"Закрыть окно\". <br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <a href=# onclick='javascript:window.opener.location=\"showcoins.php?&pagenum=".$pagenum."\";window.close();'>Закрыть окно</a></b>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=submityes value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Связать&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=submitno value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Пропустить&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'><br><br>
							
							<input type=hidden name=pagenum value=$pagenum>
							<input type=hidden name=coins value=$coins>
							Страна: $group <br>
							Номинал: $name <br>
							Год: $year <br>
							Металл: $metal <br>
							Описание: $details <br><br><br><br><br><br><br>";
					
					$sqlm = "SELECT id as metal, `name` FROM `shopcoins_metal` where name='$metal';";
					//echo $sqlm."<br>";
					$resultm = mysql_query($sqlm);
					$rowsm = mysql_fetch_array($resultm);
					$metal = ($rowsm['metal']>1?$rowsm['metal']:1);
					
					$sqlg = "SELECT `group`, `name` FROM `group` where name='$group' and `type`='shopcoins';";
					//echo $sqlg."<br>";
					$resultg = mysql_query($sqlg);
					$rowsg = mysql_fetch_array($resultg);
					$group = ($rowsg['group']>1?$rowsg['group']:1);
					if ($group and $name)
					{
					  if ($details) {
					  
						//echo "detailsvalues=".$detailsvalues;
						$details = str_replace(","," ",$details);
						$details = str_replace("."," ",$details);
						
						$tmpd = explode(" ",$details);
						$arraydetails = array();
						
						foreach ($tmpd as $key=>$value) {
						
							if (strlen($value)>5)
								$arraydetails[] = substr($value,0,strlen($value)-2);
							elseif(strlen($value)>3)
								$arraydetails[] = substr($value,0,strlen($value)-1);
							else 
								$arraydetails[] = $value;
						}
						
						
					  }
					  $CounterSQL = (sizeof($arraydetails)>0?", MATCH(catalognew.`details`) AGAINST('".implode("* ",$arraydetails)."* ' IN BOOLEAN MODE) as coefficient":", 0 as coefficient");
					  //echo "detailsvalues=".$detailsvalues;
					  
					
					  $k = 0;
					  $coinsexist = Array();
					  //где все совпадает + странаноминал
					
					
					  if ($materialtype==8)
					  	$materialtype_admin=1;
					else 
						$materialtype_admin = $materialtype;
					  //if ($PHP_AUTH_USERID == 1)
					  {
						//echo $metal." - ".$year;
					
						if ($metal and $metal!=1 and $year)
						{
						  $sql = "select catalognew.*, metal.name as mname, catalogyear.yearstart as cyearstart, catalogyear.yearend $CounterSQL, if (catalognew.metal='$metal',1,0) as pmetal
						  from catalogyear, catalognew, shopcoins_metal as metal
						  where
						  catalognew.group = '$group'
						  and catalognew.name = '$name'
						  and agreement > 0
						  ".($materialtype_admin?"and catalognew.materialtype='".$materialtype_admin."'":"")."
						  and catalognew.metal = '$metal'
						  and catalognew.metal = metal.id
						  and catalognew.catalog = catalogyear.catalog
						  and (catalogyear.yearstart='$year' or (catalogyear.yearstart <= '$year'
						  and catalogyear.yearend >= '$year'))
						  order by  
						  ".($year>0?"abs(".$year."-catalogyear.yearstart) asc, pmetal desc, coefficient desc,":" pmetal desc, coefficient desc,catalognew.yearstart desc,")."
						  catalog desc, LENGTH(details) desc, dateinsert desc limit 100;";
						  //echo "<br>".$sql;
						  $result = mysql_query($sql);
						  while ($rows = mysql_fetch_array($result))
						  {
							if (!in_array($rows["catalog"], $coinsexist))
							{
							  //echo "<br>".$k.$rows["image_small_url"];
							  $coinsexist[] = $rows["catalog"];
							  //$CoinsArray[$k] = Array();
							  $CoinsArray[$k]["image_small_url"] = $rows["image_small_url"];
							  $CoinsArray[$k]["image_big_url"] = $rows["image_big_url"];
							  $CoinsArray[$k]["name"] = $rows["name"];
							  $CoinsArray[$k]["catalog"] = $rows["catalog"];
							  $CoinsArray[$k]["mname"] = $rows["mname"];
							  $CoinsArray[$k]["yearstart"] = $rows["cyearstart"];
							  $CoinsArray[$k]["yearend"] = $rows["yearend"];
							  $CoinsArray[$k]["condition"] = $rows["condition"];
							  $CoinsArray[$k]["details"] = $rows["details"];
							  //echo $rows["coefficient"].",";
							  $k++;
							}
						  }
						}
					
						if ($metal and $metal != 1)
						{
						  $sql = "select catalognew.*, metal.name as mname $CounterSQL, if (catalognew.metal='$metal',1,0) as pmetal
						  from catalognew, shopcoins_metal as metal
						  where
						  catalognew.group = '$group'
						  and catalognew.name = '$name'
						  and agreement > 0
						  ".($materialtype_admin?"and catalognew.materialtype='".$materialtype_admin."'":"")."
						  and catalognew.metal = '$metal'
						  and catalognew.metal = metal.id
						  order by  ".($year>0?"abs(".$year."-catalognew.yearstart) asc, pmetal desc, coefficient desc,":" pmetal desc, coefficient desc,catalognew.yearstart desc,")."
						  catalog desc, LENGTH(details) desc, dateinsert desc limit 100;";
					//echo "<br>".$sql;
						  $result = mysql_query($sql);
						  while ($rows = mysql_fetch_array($result))
						  {
							if (!in_array($rows["catalog"], $coinsexist))
							{
							  $coinsexist[] = $rows["catalog"];
							  $CoinsArray[$k]["image_small_url"] = $rows["image_small_url"];
							  $CoinsArray[$k]["image_big_url"] = $rows["image_big_url"];
							  $CoinsArray[$k]["name"] = $rows["name"];
							  $CoinsArray[$k]["catalog"] = $rows["catalog"];
							  $CoinsArray[$k]["mname"] = $rows["mname"];
							  $CoinsArray[$k]["yearstart"] = $rows["yearstart"];
							  $CoinsArray[$k]["condition"] = $rows["condition"];
							  $CoinsArray[$k]["details"] = $rows["details"];
							  //echo $rows["coefficient"].",";
							  $k++;
							}
						  }
						}
					  }
					
					  if ($year)
					  {
						$sql = "select catalognew.*, metal.name as mname, catalogyear.yearstart as cyearstart, catalogyear.yearend $CounterSQL
						from catalogyear, catalognew, shopcoins_metal as metal
						where
						catalognew.group = '$group'
						and catalognew.name = '$name'
						and agreement > 0
						".($materialtype_admin?"and catalognew.materialtype='".$materialtype_admin."'":"")."
						and catalognew.metal = metal.id
						and catalognew.catalog = catalogyear.catalog
						and (catalogyear.yearstart='$year' or (catalogyear.yearstart <= '$year'
						and catalogyear.yearend >= '$year'))
						order by  ".($year>0?"abs(".$year."-catalognew.yearstart) asc,":"catalognew.yearstart desc,")." coefficient desc,
						catalog desc, LENGTH(details) desc, dateinsert desc limit 100;";
						//echo "<br>-".$sql;
						$result = mysql_query($sql);
						while ($rows = mysql_fetch_array($result))
						{
						  if (!in_array($rows["catalog"], $coinsexist))
						  {
							$coinsexist[] = $rows["catalog"];
							$CoinsArray[$k]["image_small_url"] = $rows["image_small_url"];
							$CoinsArray[$k]["image_big_url"] = $rows["image_big_url"];
							$CoinsArray[$k]["name"] = $rows["name"];
							$CoinsArray[$k]["catalog"] = $rows["catalog"];
							$CoinsArray[$k]["mname"] = $rows["mname"];
							$CoinsArray[$k]["yearstart"] = $rows["cyearstart"];
							$CoinsArray[$k]["yearend"] = $rows["yearend"];
							$CoinsArray[$k]["condition"] = $rows["condition"];
							$CoinsArray[$k]["details"] = $rows["details"];
							//echo $rows["coefficient"].",";
							$k++;
						  }
						}
					  }
					
					  //где только странаноминал
					
					  $sql = "select catalognew.*, m.name as mname $CounterSQL
					  from catalognew, shopcoins_metal as m
					  where
					  catalognew.metal = m.id
					  and catalognew.group = '$group'
					  and catalognew.name = '$name'
					  and agreement > 0
					  ".($materialtype_admin?"and catalognew.materialtype='".$materialtype_admin."'":"")."
					  order by  ".($year>0?"abs(".$year."-catalognew.yearstart) asc,":"catalognew.yearstart desc,")."coefficient desc,
					  catalog desc, LENGTH(details) desc, dateinsert desc limit 100;";
					  //echo $sql;
					//echo  $sql;
					  $result = mysql_query($sql);
					//  var_dump($result);
					  //$CoinsArray = Array();
					  while ($rows = mysql_fetch_array($result))
					  {
						if (!in_array($rows["catalog"], $coinsexist))
						{
						  $coinsexist[] = $rows["catalog"];
						  $CoinsArray[$k]["image_small_url"] = $rows["image_small_url"];
						  $CoinsArray[$k]["image_big_url"] = $rows["image_big_url"];
						  $CoinsArray[$k]["name"] = $rows["name"];
						  $CoinsArray[$k]["catalog"] = $rows["catalog"];
						  $CoinsArray[$k]["mname"] = $rows["mname"];
						  $CoinsArray[$k]["yearstart"] = $rows["yearstart"];
						  $CoinsArray[$k]["condition"] = $rows["condition"];
						  $CoinsArray[$k]["details"] = $rows["details"];
						  //echo $rows["coefficient"].",";
						  $k++;
						}
					  }
					
						if(sizeof($CoinsArray)>0) {
					  		
							echo $body;
							echo ShowTable ($CoinsArray);
							echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=submityes value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Связать&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=submitno value='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Пропустить&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'><br></form>";
						}
					  //print_r ($CoinsArray);
					}
					echo "
					<br><center><b class=tboard><a href=# onclick='javascript:window.opener.location=\"showcoins.php?&pagenum=".$pagenum."\";window.close();'>Закрыть окно</a></b></center>
					</body>
					</html>";
					exit;
				}
				elseif($tig<2) 
					$error[] = "Вы указали мало параметров.";
			}
			
			$form .= "<form action=$script name=mainform method=post>
			<table border=1 cellpadding=2 cellspacing=0 align=center width=100%>
			<input type=hidden id=coins name=coins value=\"$coins\">
			<input type=hidden id=copycoins name=copycoins value=\"$copycoins\">
			<input type=hidden name=pagenum value=\"$pagenum\">
			<tr class=tboard valign=top bgcolor=#EBE4D4>
			<td colspan=2 align=center>&nbsp;<img src=images/".$rows_main['image_big']." border=1></td>
			</tr>
			
			";
			
			if (sizeof($error))
				$form .= "<tr class=tboard valign=top bgcolor=#EBE4D4>
				<td colspan=4><font color=red>".implode("<br>",$error)."</font></td>
				</tr>";
			
			$form .= "";
			
			if (!$rows_main['group'] && !$testuser) {
			
				$sql = "select * from `group` where type='shopcoins' and `group` not in (667,937,983,997,1014,1015,1062,1063,1097,1106) group by name;";
				
				$result = mysql_query($sql);
				$i=0;
				while ($rows = mysql_fetch_array($result)) {
					
					$groupselect_v .= ($i!=0?",\"":"\"").str_replace('"','',$rows["name"])."\"";
					$groupselect_v2 .= ($i!=0?",":"").str_replace('"','',$rows["name"])."";
					$i++;
				}
				
				$form .= "<tr class=tboard bgcolor=#EBE4D4>
				<td width=20% align=right><b>Страна: </b></td>
				<td><input type=text class=formtxt id=\"group2\" name=\"group\" size=40 onfocus=\"javascript:AddGroup2();\"/><br>
			";
				
				
				
				
				//$groupselect.$groupselect_f.$groupselect_v
				$form .= ($rows_main['materialtype']>1?" Указывайте страну из предложенных Вам подсказок":"")."</td></tr>";
			}
			else {
			
				$form .= "<tr class=tboard bgcolor=#EBE4D4>
				<td width=20% align=right></a><b>Страна: </b></td>
				<td>&nbsp;".($rows_main['group']?$rows_main['group']:"")."</td></tr>";
			}
			
			if (!$rows_main['name'] && !$testuser) {
				
				$form .= "<tr class=tboard bgcolor=#EBE4D4>
				<td width=20% align=right><b>Номинал: </b></td>
				<td> ";
				$form .= "<input class=formtxt id=\"name2\" name=\"name\" type=text size=40 onfocus=\"process('addname.php?group='+ encodeURI(document.getElementById('group2').value));\"> ".($rows_main['materialtype']==7?" Пример: Австрия 2002-2004":"")."";
				
				$form .= " &nbsp; &nbsp;&nbsp;&nbsp;</td>
				</tr>";
			}
			else {
			
				$form .= "<tr class=tboard bgcolor=#EBE4D4>
				<td width=20% align=right><b>Номинал: </b></td>
				<td> ".($rows_main['name']?$rows_main['name']:"")." &nbsp; &nbsp;&nbsp;&nbsp;</td>
				</tr>";
			}
			
			$form .= "<tr class=tboard bgcolor=#EBE4D4>
			<td width=20% align=right><a name=year></a><b>Год: </b></td>
			<td>
			".($rows_main['year']?$rows_main['year']:"<input class=formtxt id=\"year2\" name=\"year\" size=4/>")." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			</tr>";
			
			
			$form .= "
			<tr class=tboard valign=top bgcolor=#EBE4D4>
			<td width=20% align=right><a name=metal></a><b>Металл: </b></td>
			<td>";
			if ($rows_main['metal'])
				$form .= $rows_main['metal'];
			elseif (!$testuser) {
				$form .= "<select name=metal id=\"metal2\" class=formtxt ><option value=0>Выберите"; 
				foreach ($MetalArray as $key => $value) {
			
					$form .= "<option value=".$key.">".$value;
				}
				$form .= "</select>";
			}
			else 
				$form .= ($rows_user['metal']?$rows_user['metal']:"&nbsp;");
			
			$form .= "
			</td></tr>";
			
			
			
			$form .= "
			<tr class=tboard valign=top bgcolor=#EBE4D4>
			<td width=20% align=right><a name=metal></a><b>Состояние: </b></td>
			<td>";
			if ($rows_main['condition'])
				$form .= $rows_main['condition'];
			elseif (!$testuser) {
				$form .= "<select name=condition id=condition2 class=formtxt ><option value=0>Выберите"; 
				foreach ($ConditionArray as $key => $value) {
			
					$form .= "<option value=".$key.">".$value;
				}
				$form .= "</select>";
			}
			else 
				$form .= ($rows_user['condition']?$rows_user['condition']:"&nbsp;");
			
			$form .= "
			</td></tr>";
			
			if (!$rows_main['details'] && !$testuser) {
				
				$form .= "<tr class=tboard valign=top bgcolor=#EBE4D4>
				<td width=20% align=right><a name=details></a><b>Описание монеты:</b></td>
				<td><textarea name=details id=details2 class=formtxt cols=60 rows=6>".($details?$details:($rows_main['materialtype']!=7?"":"В наборе Х монет номиналом"))."</textarea> &nbsp;&nbsp;&nbsp;</td>
				</tr>";
			}
			else {
			
				$form .= "<tr class=tboard valign=top bgcolor=#EBE4D4>
				<td width=20% align=right><a name=details></a><b>Описание монеты:</b></td>
				<td>".($rows_main['details']?$rows_main['details']:"")."&nbsp;&nbsp;&nbsp;</td>
				</tr>";
			}
			
			$form .= "
			
			</table>
			";
			
			$mainform .= "<table border=0 cellpadding=3 cellspacing=1 width=100%>
			<tr class=tboard valign=top bgcolor=#EBE4D4>
			<td align=center width=35%>
			<input type=submit name=".($submitaftererror?"submitaftererror":"submit")." onclick=\"javascript:return CheckSubmitPrice(0);\" value='Записать' class=formtxt ".(($testuser || !$user)?" disabled=\"disabled\"":"").">
			</td><td align=center>
			<input type=submit name=".($submitaftererror?"submitaftererror":"submit")." onclick=\"javascript:return CheckSubmitPrice(1);\" value='Записать и Сохранить для последующего использования' class=formtxt ".(($testuser || !$user)?" disabled=\"disabled\"":"").">
			</td>";
			if ($copycoins>0) 
				$mainform .= "<td align=center><input type=button name=next value='Использовать сохраненные данные' onclick=\"javascript:UsingCopyCoins($copycoins);\" class=formtxt ".(($testuser || !$user)?" disabled=\"disabled\"":"")."></td>";
			$mainform .= "<td align=center><input type=submit name=next value='Не знаю что за монета. Пропустить' onclick=\"javascript:if(confirm('Желаете пропустить описание этой монеты?')){return true;}else{return false;};\" class=formtxt ".(($testuser || !$user)?" disabled=\"disabled\"":"").">
			</td>
			</tr>
			
			</table>";
			echo $top_html;
			echo $form;
			
			echo $mainform;
		}
		else {
			echo $top_html;
			echo "Ошибочный запроc! Возможно данной монете уже сделал(делает) описание другой пользователь.";
		}
	}
}
?>
<script>
var test3 = 0;
function UsingCopyCoins (copycoins) {

	if (copycoins>0) {
	
		process('writesavedatacoins.php?copycoins='+copycoins);
	}
	else
		alert('not coins');
}

function CheckSubmitPrice (num) {

	if (confirm('Вы не сможете внести изменения после записи. Записать?')) { 
		
		if (num == 1) {
		
			document.getElementById("copycoins").value = document.getElementById("coins").value;
		}
		return true;
	} 
	else {
		return false;
	}

}

function CopyDetails(id) {

	myDiv = document.getElementById("details"+id);
	document.mainform.details.value=myDiv.value;
	
}

function ShowSaveCoins(xmlRoot) {
	
	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	if (errorvalue == "none") {
		var metal = xmlRoot.getElementsByTagName("metal").item(0).firstChild.data;
		document.getElementById("metal2").value = metal;
		var condition = xmlRoot.getElementsByTagName("condition").item(0).firstChild.data;
		document.getElementById("condition2").value = condition;
		var group = xmlRoot.getElementsByTagName("group").item(0).firstChild.data;
		if (group != 'none')
			document.getElementById("group2").value = group;
		var year = xmlRoot.getElementsByTagName("year").item(0).firstChild.data;
		if (year != 'none')
			document.getElementById("year2").value = year;
		var name = xmlRoot.getElementsByTagName("name").item(0).firstChild.data;
		if (name != 'none')
			document.getElementById("name2").value = name;
		var details = xmlRoot.getElementsByTagName("details").item(0).firstChild.data;
		if (details != 'none')
			document.getElementById("details2").value = details;
	}
	else
		alert('not data');
		return;
}

var StatusForm = 1;
var NameStatus = 'text';
NameArray = new Array();
GroupNameArray = new Array(<? echo $groupselect_v;?>);
var MetalNameArray = "";

var xmlHttp = createXmlHttpRequestObject();
var in_office = 0;

function createXmlHttpRequestObject() 
{
	var xmlHttp;
	try
	{
		xmlHttp = new XMLHttpRequest();
	}
	catch(e)
	{
		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
										"MSXML2.XMLHTTP.5.0",
										"MSXML2.XMLHTTP.4.0",
										"MSXML2.XMLHTTP.3.0",
										"MSXML2.XMLHTTP",
										"Microsoft.XMLHTTP");
		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++) 
		{
			try 
			{ 
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			} 
			catch (e) {}
		}
	}
	

	if (!xmlHttp)
		alert("Не возможно создать объект XMLHttpRequest");
	else 
		return xmlHttp;
}

function process(myFile)
{
	//alert (myFile);
	if (myFile)
	{
		if (xmlHttp)
		{
			try
			{
				xmlHttp.open("GET", myFile, true);
				xmlHttp.onreadystatechange = handleRequestStateChange;
				xmlHttp.send(null);
			}
			catch (e)
			{
				alert("Can't connect to server:\n" + e.toString());
			}
		}
	}
	else
		return;
}

function handleRequestStateChange() 
{
	if (xmlHttp.readyState == 4) 
	{
		if (xmlHttp.status == 200) 
		{
			try
			{
				handleServerResponse();
			}
			catch(e)
			{
				alert("Error reading the response: " + e.toString());
			}
		} 
		else
		{
			alert("There was a problem retrieving the data:\n" + 
			xmlHttp.statusText);
		}
	}
}

function handleServerResponse()
{
	var xmlResponse = xmlHttp.responseXML;
	if (!xmlResponse || !xmlResponse.documentElement)
		throw("Invalid XML structure:\n" + xmlHttp.responseText);

	var rootNodeName = xmlResponse.documentElement.nodeName;
	if (rootNodeName == "parsererror") 
		throw("Invalid XML structure:\n" + xmlHttp.responseText);
	
	xmlRoot = xmlResponse.documentElement;
	if (rootNodeName != "response" || !xmlRoot.firstChild)
		throw("Invalid XML structure:\n" + xmlHttp.responseText);
	
	
	scripteval = xmlRoot.getElementsByTagName("scripteval");
	eval (scripteval.item(0).firstChild.data + '(xmlRoot)');
};
var TextGroup = '<? echo $groupselect_v2; ?>';
//this.group = AutoInput('group2',);
//this.name = AutoInput('name2',MetalNameArray);
function AddGroup2() {
	
	// CONFIG 
	
	// this is id of the search field you want to add this script to. 
	// идентификатор котрый мы присвоили полю
	var id = "group2";
	
	// Надпись в поле до клика на него мышкой
	var defaultText = "";	
	
	// set to either true or false
	// when set to true it will generate search suggestions list for search field based on content of variable below
	var suggestion = true;
	
	// static list of suggestion options, separated by comma
	// А здесь через запятую перечислены все возможные варианты
	var suggestionText =  TextGroup;
	
	// END CONFIG (do not edit below this line, well unless you really, really want to change something :) )
	
	// Peace, 
	// Alen

	var field = document.getElementById(id);	
	var classInactive = "sf_inactive";
	var classActive = "sf_active";
	var classText = "sf_text";
	var classSuggestion = "sf_suggestion";
	this.safari = ((parseInt(navigator.productSub)>=20020000)&&(navigator.vendor.indexOf("Apple Computer")!=-1));
	if(field && !safari){
		field.value = defaultText;
		field.c = field.className;		
		field.className = field.c + " " + classInactive;
		field.onfocus = function(){
			this.className = this.c + " "  + classActive;
			this.value = (this.value == "" || this.value == defaultText) ?  "" : this.value;
		};
		field.onblur = function(){
			this.className = (this.value != "" && this.value != defaultText) ? this.c + " " +  classText : this.c + " " +  classInactive;
			this.value = (this.value != "" && this.value != defaultText) ?  this.value : defaultText;
			clearList();
		};
		if (suggestion){
			
			var selectedIndex = 0;
						
			field.setAttribute("autocomplete", "off");
			var div = document.createElement("div");
			var list = document.createElement("ul");
			list.style.display = "none";
			div.className = classSuggestion;
			list.style.width = field.offsetWidth + "px";
			div.appendChild(list);
			field.parentNode.appendChild(div);	

			field.onkeypress = function(e){
				
				var key = getKeyCode(e);
		
				if(key == 13){ // enter
					selectList();
					selectedIndex = 0;
					return false;
				};	
			};
				
			field.onkeyup = function(e){
			
				var key = getKeyCode(e);
		
				switch(key){
				case 13:
					return false;
					break;			
				case 27:  // esc
					field.value = "";
					selectedIndex = 0;
					clearList();
					break;				
				case 38: // up
					navList("up");
					break;
				case 40: // down
					navList("down");		
					break;
				default:
					startList();			
					break;
				};
			};
			
			this.startList = function(){
				var arr = getListItems(field.value);
				if(field.value.length > 0){
					createList(arr);
				} else {
					clearList();
				};	
			};
			
			this.getListItems = function(value){
				var arr = new Array();
				var src = suggestionText;
				var src = src.replace(/, /g, ",");
				var arrSrc = src.split(",");
				for(i=0;i<arrSrc.length;i++){
					if(arrSrc[i].substring(0,value.length).toLowerCase() == value.toLowerCase()){
						arr.push(arrSrc[i]);
					};
				};				
				return arr;
			};
			
			this.createList = function(arr){				
				resetList();			
				if(arr.length > 0) {
					for(i=0;i<arr.length;i++){				
						li = document.createElement("li");
						a = document.createElement("a");
						a.href = "javascript:void(0);";
						a.i = i+1;
						a.innerHTML = arr[i];
						li.i = i+1;
						li.onmouseover = function(){
							navListItem(this.i);
						};
						a.onmousedown = function(){
							selectedIndex = this.i;
							selectList(this.i);		
							return false;
						};					
						li.appendChild(a);
						list.setAttribute("tabindex", "-1");
						list.appendChild(li);	
					};	
					list.style.display = "block";				
				} else {
					clearList();
				};
			};	
			
			this.resetList = function(){
				var li = list.getElementsByTagName("li");
				var len = li.length;
				for(var i=0;i<len;i++){
					list.removeChild(li[0]);
				};
			};
			
			this.navList = function(dir){			
				selectedIndex += (dir == "down") ? 1 : -1;
				li = list.getElementsByTagName("li");
				if (selectedIndex < 1) selectedIndex =  li.length;
				if (selectedIndex > li.length) selectedIndex =  1;
				navListItem(selectedIndex);
			};
			
			this.navListItem = function(index){	
				selectedIndex = index;
				li = list.getElementsByTagName("li");
				for(var i=0;i<li.length;i++){
					li[i].className = (i==(selectedIndex-1)) ? "selected" : "";
				};
			};
			
			this.selectList = function(){
				li = list.getElementsByTagName("li");	
				a = li[selectedIndex-1].getElementsByTagName("a")[0];
				field.value = a.innerHTML;
				clearList();
			};			
			
		};
	};
	
	this.clearList = function(){
		if(list){
			list.style.display = "none";
			selectedIndex = 0;
		};
	};		
	this.getKeyCode = function(e){
		var code;
		if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		return code;
	};
	
};

function AddName2() {
	
	// CONFIG 
	
	// this is id of the search field you want to add this script to. 
	// идентификатор котрый мы присвоили полю
	var id = "name2";
	
	// Надпись в поле до клика на него мышкой
	var defaultText2 = "";	
	
	// set to either true or false
	// when set to true it will generate search suggestions list for search field based on content of variable below
	var suggestion2 = true;
	
	// static list of suggestion options, separated by comma
	// А здесь через запятую перечислены все возможные варианты
	//var suggestionText = MetalNameArray; 
	var suggestionText2 = MetalNameArray;
	
	// END CONFIG (do not edit below this line, well unless you really, really want to change something :) )
	
	// Peace, 
	// Alen

	var field2 = document.getElementById(id);	
	var classInactive = "sf_inactive";
	var classActive = "sf_active";
	var classText = "sf_text";
	var classSuggestion = "sf_suggestion";
	this.safari = ((parseInt(navigator.productSub)>=20020000)&&(navigator.vendor.indexOf("Apple Computer")!=-1));
	if(field2 && !safari){
		field2.value = defaultText2;
		field2.c = field2.className;		
		field2.className = field2.c + " " + classInactive;
		field2.onfocus = function(){
			this.className = this.c + " "  + classActive;
			this.value = (this.value == "" || this.value == defaultText2) ?  "" : this.value;
		};
		field2.onblur = function(){
			this.className = (this.value != "" && this.value != defaultText2) ? this.c + " " +  classText : this.c + " " +  classInactive;
			this.value = (this.value != "" && this.value != defaultText2) ?  this.value : defaultText2;
			clearList();
		};
		if (suggestion2){
			
			var selectedIndex = 0;
						
			field2.setAttribute("autocomplete", "off");
			var div = document.createElement("div");
			var list = document.createElement("ul");
			list.style.display = "none";
			div.className = classSuggestion;
			list.style.width = field2.offsetWidth + "px";
			div.appendChild(list);
			field2.parentNode.appendChild(div);	

			field2.onkeypress = function(e){
				
				var key = getKeyCode(e);
		
				if(key == 13){ // enter
					selectList();
					selectedIndex = 0;
					return false;
				};	
			};
				
			field2.onkeyup = function(e){
			
				var key = getKeyCode(e);
		
				switch(key){
				case 13:
					return false;
					break;			
				case 27:  // esc
					field2.value = "";
					selectedIndex = 0;
					clearList();
					break;				
				case 38: // up
					navList("up");
					break;
				case 40: // down
					navList("down");		
					break;
				default:
					startList();			
					break;
				};
			};
			
			this.startList = function(){
				var arr2 = getListItems(field2.value);
				if(field2.value.length > 0){
					createList(arr2);
				} else {
					clearList();
				};	
			};
			
			this.getListItems = function(value){
				var arr = new Array();
				var src = suggestionText2;
				var src = src.replace(/, /g, ",");
				var arrSrc = src.split(",");
				for(i=0;i<arrSrc.length;i++){
					if(arrSrc[i].substring(0,value.length).toLowerCase() == value.toLowerCase()){
						arr.push(arrSrc[i]);
					};
				};				
				return arr;
			};
			
			this.createList = function(arr){				
				resetList();			
				if(arr.length > 0) {
					for(i=0;i<arr.length;i++){				
						li = document.createElement("li");
						a = document.createElement("a");
						a.href = "javascript:void(0);";
						a.i = i+1;
						a.innerHTML = arr[i];
						li.i = i+1;
						li.onmouseover = function(){
							navListItem(this.i);
						};
						a.onmousedown = function(){
							selectedIndex = this.i;
							selectList(this.i);		
							return false;
						};					
						li.appendChild(a);
						list.setAttribute("tabindex", "-1");
						list.appendChild(li);	
					};	
					list.style.display = "block";				
				} else {
					clearList();
				};
			};	
			
			this.resetList = function(){
				var li = list.getElementsByTagName("li");
				var len = li.length;
				for(var i=0;i<len;i++){
					list.removeChild(li[0]);
				};
			};
			
			this.navList = function(dir){			
				selectedIndex += (dir == "down") ? 1 : -1;
				li = list.getElementsByTagName("li");
				if (selectedIndex < 1) selectedIndex =  li.length;
				if (selectedIndex > li.length) selectedIndex =  1;
				navListItem(selectedIndex);
			};
			
			this.navListItem = function(index){	
				selectedIndex = index;
				li = list.getElementsByTagName("li");
				for(var i=0;i<li.length;i++){
					li[i].className = (i==(selectedIndex-1)) ? "selected" : "";
				};
			};
			
			this.selectList = function(){
				li = list.getElementsByTagName("li");	
				a = li[selectedIndex-1].getElementsByTagName("a")[0];
				field2.value = a.innerHTML;
				clearList();
			};			
			
		};
	};
	
	this.clearList = function(){
		if(list){
			list.style.display = "none";
			selectedIndex = 0;
		};
	};		
	this.getKeyCode = function(e){
		var code;
		if (!e) var e = window.event;
		if (e.keyCode) code = e.keyCode;
		return code;
	};
	
};

// script initiates on page load. 

this.addEvent = function(obj,type,fn){
	if(obj.attachEvent){
		obj['e'+type+fn] = fn;
		obj[type+fn] = function(){obj['e'+type+fn](window.event );}
		obj.attachEvent('on'+type, obj[type+fn]);
	} else {
		obj.addEventListener(type,fn,false);
	};
};
//addEvent(window,"load",group2);
//addEvent(window,"load",group2);

function ShowNameCoins(xmlRoot) {
	
	//alert("1111");
	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	if (errorvalue == "none") {
		var arrayresult = '';	
		arrayresult = xmlRoot.getElementsByTagName("arrayresult").item(0).firstChild.data;
		//alert(arrayresult);
		MetalNameArray = arrayresult;
		AddName2();
	}
	else
		//alert('0');
		return "";
}

</script>

