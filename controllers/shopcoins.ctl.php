<?
$search = (integer) request('pagestart');
$onpage = (integer)(request('onpage')?request('onpage'):30);
$dateinsert_orderby = "dateinsert";
$limit = " limit ".($pagenum-1)*$onpage.",$onpage";
//start - потом не забыть подключить
$checkuser = 0;
$CounterSQL = "";
$WhereArray = Array();
$page_string = "";
//end - потом не забыть подключить

if ($searchid) {

	$where = " where 
	(".($tpl['user']['user_id']==811||$tpl['user']['user_id']==309236?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") 
	".(sizeof($WhereArray)?" and ".implode(" and ", $WhereArray):"");
} elseif ($search) {

	$where = " where 
	((".($tpl['user']['user_id']==811||$tpl['user']['user_id']==309236?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") ".($show50?"or shopcoins.check=50":"").") and ((shopcoins.materialtype in (2,4,7,8,3,5,9)) or (shopcoins.materialtype in(1,10) and shopcoins.amountparent>0) or shopcoins.number='$search' or shopcoins.number2='$search') 
	".(sizeof($WhereArray)?" and ".implode(" and ", $WhereArray):"");
} else {
	$where = " where 
	(".($tpl['user']['user_id']==811||$tpl['user']['user_id']==309236?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") 
	".(($materialtype==1 || $materialtype==10)?"and ((shopcoins.materialtype='".$materialtype."' ".(($materialtype==1 || $materialtype==10)&&!$searchid&&!$yearsearch&&!$searchname?"and shopcoins.amountparent > 0":"").") or shopcoins.materialtypecross & pow(2,".$materialtype.")".($group?" or shopcoins.materialtype='8' or shopcoins.materialtypecross & pow(2,8)":"").") ":"and (shopcoins.materialtype='".$materialtype."' or shopcoins.materialtypecross & pow(2,".$materialtype.")) ")."
	".(sizeof($WhereArray)?" and ".implode(" and ", $WhereArray):"");
	//echo $where;
	//exit;
}

$addhref = ($yearstart?"&yearstart=".$yearstart:"").
($yearend?"&yearend=".$yearend:"").
($metal?"&metal=".urlencode($metal):"").
($search?"&search=".urlencode($search):"").
($pricestart?"&pricestart=".$pricestart:"").
($priceend?"&priceend=".$priceend:"").
($searchid?"&searchid=".$searchid:"").
($group?"&group=$group":"").
($materialtype?"&materialtype=$materialtype":"").
($theme?"&theme=".$theme:"").
($condition?"&condition=".$condition:"").
($nocheck?"&nocheck=".$nocheck:"");

//�������� ����� � ������



if ($searchid)
	$MainText .= "<p class=txt><b><font color=red>��������� ������������. �������� ������� ������������ ������.</font></b>
	<br>����� ��������� ����������� ����� - ������� <a href=$script>�����</a>.</p>";

if ($searchname) {
	$searchname = str_replace("'","",$searchname);
	$where .= " and shopcoins.name='".$searchname."' ";
}	
$addhref .= ($searchname?"&searchname=".urlencode($searchname):"");

if ($yearsearch >0) {
	$where .= " and shopcoins.year='".$yearsearch."' ";
}


if (!$page)
{
    $countpubs = $shopcoins_class->countByParams($where);
	//�������
	
	//echo '$countpubs'.$countpubs."<br>".'$sql='.$sql."<br>";
	// or $materialtype==4
	if (!$onpage) $onpage=8;
	$numpages=15;
	$pagenum=round($pagenum);
	$pages=ceil($countpubs/$onpage);if (!$pages) $pages=1;
	if ($pagenum<1) 
		$pagenum=1; 
	if ($pagenum>$pages) 
		$pagenum=$pages;
	$i=1;
	
	$frompage=floor(($pagenum-1)/$numpages)*$numpages+1;
	$topage=ceil($pagenum/$numpages)*$numpages;if ($topage>$pages) $topage=$pages;
	
	if ($pagenum>2*$numpages) $page_string .= "<a href='$script?pagenum=1".$addhref.($sortname?"&sortname=1":"").($yearsearch?"&yearsearch=$yearsearch":"")."'>[� ������]</a> | ";
	if ($frompage>$numpages) $page_string .= "<a href='$script?pagenum=".($frompage-1).$addhref.($sortname?"&sortname=1":"").($yearsearch?"&yearsearch=$yearsearch":"")."'><<����</a> | ";
	for ($i=$frompage;$i<=$topage;$i++)
		{
		if ($i==$pagenum) $page_string .= "<b>$i</b>";
		else $page_string .= "<a href='$script?pagenum=$i".$addhref.($sortname?"&sortname=1":"").($yearsearch?"&yearsearch=$yearsearch":"")."'>$i</a>";
		if ($i<$topage) $page_string .= " | ";
		}      
	if ($pages>$topage) $page_string .= " | <a href='$script?pagenum=$i".$addhref.($sortname?"&sortname=1":"").($yearsearch?"&yearsearch=$yearsearch":"")."'>�����>></a>";
	if($materialtype == 12){
			require_once('build_table/build_table.php');
			$MainText .= $table_data;
	}
}

$addhref .= ($pagenum?"&pagenum=".$pagenum:"");

//����������
$OrderByArray = Array();

if ($coinssearch)
	$OrderByArray[] = " shopcoins.shopcoins=".intval($coinssearch)." desc ";

if (isset($CounterSQL)&&$CounterSQL)
	if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit))
		$OrderByArray[] = " (coefficientcoins+counterthemeyear+coefficientgroup) desc, counterthemeyear desc, (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc  ";
	else
		$OrderByArray[] = " (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc ";

if ($search == 'revaluation') 
	$OrderByArray[] = " shopcoins.datereprice desc, shopcoins.price desc, shopcoins.".$dateinsert_orderby." desc";

if ($group)
	$OrderByArray[] = " ABS(shopcoins.group-".$group.") ";

if (($materialtype==3||$materialtype==5) and $group)
	$OrderByArray[] = " shopcoins.name ";

if ($materialtype==5)
	$OrderByArray[] = " shopcoins.name ";


if ($orderby=="dateinsertdesc")
	$OrderByArray[] = " if (shopcoins.dateupdate > shopcoins.dateinsert, shopcoins.dateupdate, shopcoins.dateinsert) desc, shopcoins.dateinsert desc, shopcoins.price desc";
elseif ($orderby=="dateinsertasc")
	$OrderByArray[] = " shopcoins.dateinsert asc, shopcoins.price desc";
elseif ($orderby=="priceasc")
	$OrderByArray[] = " shopcoins.price asc, shopcoins.dateinsert desc ";
elseif ($orderby=="pricedesc")
	$OrderByArray[] = " shopcoins.price desc, shopcoins.dateinsert desc ";
elseif ($orderby=="yearasc")
	$OrderByArray[] = " shopcoins.year asc, shopcoins.dateinsert desc ";
elseif ($orderby=="yeardesc")
	$OrderByArray[] = " shopcoins.year desc, shopcoins.dateinsert desc ";
elseif($materialtype==12)
	$OrderByArray[] = " shopcoins.year desc, shopcoins.name desc ";
elseif ($materialtype==1||$materialtype==2||$materialtype==10||$materialtype==4||$materialtype==7||$materialtype==8||$materialtype==6||$materialtype==11||$search=='newcoins')
	//$OrderByArray[] = " if (shopcoins.dateupdate > shopcoins.dateinsert, shopcoins.dateupdate, shopcoins.dateinsert) desc, shopcoins.dateinsert desc, shopcoins.price desc";
	$OrderByArray[] = $dateinsert_orderby." desc, shopcoins.price desc";

if (sizeof($OrderByArray))
	$orderby = "order by shopcoins.`check` asc,".implode(",",$OrderByArray);

	
	
/*if ($wheresearch)
	$where = $wheresearch;

if ($shopkey) {

	$shopkey = str_replace("'","",$shopkey);	
	    $positive_amount = '';
		if($materialtype == 2)
		$positive_amount = ' shopcoins.amount > 0 and ';

		$sql = "select shopcoins.*, `group`.name as gname 
		from `shopcoins`, `group`, clientselectshopcoins
		WHERE ".$positive_amount."shopcoins.`group`=`group`.`group` AND (".($tpl['user']['user_id']==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and (shopcoins.materialtype = 1 or shopcoins.materialtypecross & pow(2,1)) and shopcoins.amountparent>0
		and shopcoins.shopcoins = clientselectshopcoins.shopcoins 
		and clientselectshopcoins.shopkey = '$shopkey' and (shopcoins.`dateinsert`>($timenow-14*24*60*60) or clientselectshopcoins.`dateselect`>1252440000) order by shopcoins.".$dateinsert_orderby." desc,shopcoins.price desc;";
		//echo $sql;
	//}
}
else*/if ($checkuser && $tpl['user']['user_id'] && ($num > 3) ) {

	if ($num > 50) $num=50;

		$positive_amount = '';
	if($materialtype == 2)
	$positive_amount = ' shopcoins.amount > 0 and ';
	
	$sql = "select shopcoins.*, `group`.`name` as gname 
		from `shopcoins`, `group`, `clientselectshopcoins` 
		where ".$positive_amount."shopcoins.`group`=`group`.`group` AND (".($tpl['user']['user_id']==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").")  and (shopcoins.materialtype = 1 or shopcoins.materialtypecross & pow(2,1)) and shopcoins.amountparent>0
		and shopcoins.`shopcoins` = clientselectshopcoins.`shopcoins` 
		and clientselectshopcoins.`user` = '".$tpl['user']['user_id']."' and clientselectshopcoins.`dateselect` >= '$dateselect' order by shopcoins.".$dateinsert_orderby." desc,shopcoins.price desc limit 0,$num;";
}
elseif ($page == "recommendation" && $tpl['user']['user_id']) {

			$positive_amount = '';
	if($materialtype == 2)
	$positive_amount = ' shopcoins.amount > 0 and ';

	$sql = "select shopcoins.*, `group`.`name` as gname 
		from `shopcoins`, `group`, `clientselectshopcoins` 
		where ".$positive_amount."shopcoins.`group`=`group`.`group` AND (".($tpl['user']['user_id']==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") and shopcoins.`shopcoins` = clientselectshopcoins.`shopcoins` 
		and clientselectshopcoins.`user` = '{$tpl['user']['user_id']}' 
		and shopcoins.shopcoins not in(".(sizeof($arraycatalpgshopcoins)?implode(",",$arraycatalpgshopcoins):"1").")
		order by shopcoins.".$dateinsert_orderby." desc, shopcoins.price desc limit ".($pagenum-1)*$onpage.", $onpage;";

} else {
	/*$positive_amount = '';
	if($materialtype == 2)	$positive_amount = ' and shopcoins.amount > 0 ';
*/
	$sql = "select shopcoins.*, group.name as gname, group.groupparent ".($CounterSQL?",".$CounterSQL:"")." ".($group>0&&!$page?$groupselect:"")."
	from shopcoins, `group` 
	$where ".$positive_amount."and shopcoins.group=group.group  
	".($group>0&&!$page?($sortname?" order by ".($coinssearch?"shopcoins.shopcoins=".intval($coinssearch)." desc,":"")." groupparent asc,param2,param1,".$dateinsert_orderby." desc":" order by ".($coinssearch?"shopcoins.shopcoins=".intval($coinssearch)." desc,":"")." groupparent asc,".$dateinsert_orderby." desc,price desc, param2,param1"):$orderby)." 
	$limit;";
	echo $sql;
	$data = $shopcoins_class->getItemsByParams($tpl['user']['user_id'],$materialtype);
	var_dump($data );
	
echo $sql;
}


//$result_search = mysql_query($sql);
$ArrayParent = Array();
$MyShowArray = Array();

foreach ($data as $rows){
	$tpl['shop']['ArrayShopcoins'][] = $rows["shopcoins"];
	$tpl['shop']['ArrayParent'][] = $rows["parent"];
	$tpl['shop']['MyShowArray'][] = $rows;
}

if (sizeof($ArrayParent))
{
	$sql_search = "select * from shopcoins where parent in (".implode(",", $tpl['shop']['ArrayParent']).")
	and shopcoins not in (".implode(",", $tpl['shop']['ArrayShopcoins']).")
	and (".($tpl['user']['user_id']==811?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") order by parent;";
	
	$result_search = mysql_query($sql_search);
	while ($rows_search = mysql_fetch_array($result_search))
		$ImageParent[$rows_search["parent"]][] = $rows_search["image_small"];
		
}


if ($materialtype==3 || $materialtype==5)
{
	if ($shopcoinsorder > 1)
	{
		$sql_ourorder = "select * from orderdetails where `order`='$shopcoinsorder' and date > ".(time() - $reservetime).";";
		//echo $sql_ourorder;
		$result_ourorder = mysql_query($sql_ourorder);
		
		$ourcoinsorder = Array();
		$ourcoinsorderamount = Array();
	
		while ($rows_ourorder = mysql_fetch_array($result_ourorder))
		{
			$ourcoinsorder[] = $rows_ourorder["catalog"];
			$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
			//echo "<br>".$rows_ourorder["catalog"]." - ".$rows_ourorder["amount"];
		}
	}
}


$ShopcoinsThemeArray = Array();
$ShopcoinsGroupArray = Array();

unset ($rows);
//$result = mysql_query($sql);

if (sizeof($MyShowArray)==0)
{
	$tpl['shop']['errors'][] = "<br><p class=txt><strong><font color=red>Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</font></strong><br><br>";
} else {
	
	$maxcoefficient=0;
	$sumcoefficient=0;
	$amountsearch = mysql_num_rows($result_search);
	
	if ($materialtype==1 || $materialtype==2 || $materialtype==10 || $materialtype==7 || $materialtype==6 || $materialtype==8 || $materialtype==4 || $materialtype==9 || $materialtype==11 || $materialtype==12 || $searchid || $search || $page == "recommendation")
	{
		
		echo "
		<script language=JavaScript>
		function AddAccessory(shopcoins,materialtype)
		{
			var str;
			str = \"document.mainform.amount\" + shopcoins + \".value\";
			document.mainform.shopcoinsorder.value = shopcoins;
			document.mainform.materialtype.value = materialtype;
			document.mainform.shopcoinsorderamount.value = eval(str);
			//alert (eval(str) + shopcoins);
			if (eval(str) > 0)
			{
				//document.mainform.submit();
				process ('addbascet.php?shopcoinsorder=".$shopcoinsorder."&shopcoins=' + shopcoins + '&amount=' + eval(str));
			}
			else
				alert ('������� ����������');
		}
		</script>
		<div id='ShowShopcoinsm'></div>
		<form action=$script?pageinfo=cookies&typeorder=1&pagenum=$pagenum".($search?"&search=".urlencode($search):"").($searchid?"&searchid=".$searchid:"")."&group=$group".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")." method=post name=mainform>
		<input type=hidden name=shopcoinsorder value=''>
		<input type=hidden name=shopcoinsorderamount value=''>
		<input type=hidden name=materialtype value=''>
		";
		echo "<table border=0 cellpadding=3 cellspacing=0 width=100%>";
		
		$i=1;
		foreach ($MyShowArray as $key=>$rows)
		//while ($rows = mysql_fetch_array($result))
		{
			
			$textoneclick = '';
			
			if (($rows['materialtype']==2 || $rows['materialtype']==4 || $rows['materialtype']==7 || $rows['materialtype']==8 || $rows['materialtype']==6) && $rows['amount']>10) 
				$rows['amount'] = 10;
			
			$mtype = ($rows['materialtype']>0?$rows['materialtype']:$materialtype);
			
			if ($mtype==1)
				$rehref = "������ ";
			elseif ($mtype==8)
				$rehref = "������ ";
			elseif ($mtype==7)
				$rehref = "����� ����� ";
			elseif ($mtype==2)
				$rehref = "�������� ";
			elseif ($mtype==4)
				$rehref = "����� ����� ";
			elseif ($mtype==5)
				$rehref = "����� ";
			elseif ($mtype==9)
				$rehref = "��� ����� ";
			elseif ($mtype==10)
				$rehref = "�������� ";
			elseif ($mtype==11)
				$rehref = "������ ";
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
			    if($rows['year'] == 1990 && $rows['materialtype']==12) $tmp_year_name = '1990 ���';
			    if($rows['year'] == 1991 && $rows['materialtype']==12) $tmp_year_name = '1991 ���';
			    if($rows['year'] == 1992 && $rows['materialtype']==12) $tmp_year_name = '1991 ���';
				$rehref .= " ". $tmp_year_name;
			}
			$namecoins = $rehref;
			$rehrefdubdle = strtolower_ru($rehref)."_c".(($mtype==1 || $mtype==10 || $mtype==12)?$rows['parent']:$rows['shopcoins'])."_pc".($parent>0?$parent:(($mtype==7 || $mtype==8 || $mtype==6 || $mtype==4 || $mtype==2) && $rows["amount"]>1?$rows['shopcoins']:(($mtype==1 || $mtype==10)?$rows['parent']:0)))."_m".$rows['materialtype']."_pp1.html";
			$rehref = strtolower_ru($rehref)."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";
			 
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
					<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].",".$rows["materialtype"].")'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz7.gif border=0 alt='��� ���� � ����� �������'></div></a>";
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
						<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='� �������'></div></a>
						";
						if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." ���.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
						//javascript:AddAccessory(\"$script?pageinfo=cookies&shopcoinsorder=".$rows["shopcoins"]."&typeorder=1&pagenum=$pagenum&pricevalue=$pricevalue&".($search?"search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]."\"
				}
				if (strlen($rows["details"])>250 or $rows["image_big"]) {
//					echo "&nbsp;<a href='index.php?page=show&catalog=".$rows["shopcoins"].(substr_count($addhref,'materialtype')>0?"":"&materialtype=".$rows["materialtype"]).$addhref."' title=\"��������� � ".$rows['name']."\"><img src=".$in."images/corz3.gif border=0 alt='��������� � ".$rows['name']."'></a>";
					echo "&nbsp;<a href='$rehref' title=\"��������� � ".$rows['name']."\"><img src=".$in."images/corz3.gif border=0 alt='��������� � ".$rows['name']."'></a>";
				}
				
				echo $textoneclick;
				
				if ( !$rows["amount"]) 
					$amountall = 1;
				else 
					$amountall = $rows["amount"];
					
				echo "<br>����������:  <strong>".$amountall."</strong>";
						
				echo "<br>������:  <a href=$script?group=".$rows['group']."&materialtype=".$rows['materialtype']." title='���������� ��� ".$rows["gname"]."'><strong><font color=blue>".$rows["gname"]."</font></strong></a>
				<br>��������: <strong>".$rows["name"]."</strong>
				".($rows["number"]?"<br>�����:<strong> ".$rows["number"]."</strong>":"")."
				".($rows["accessoryProducer"]?"<br>�������������:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
				".($rows["accessoryColors"]?"<br>�����:<strong> ".$rows["accessoryColors"]."</strong>":"")."
				".($rows["accessorySize"]?"<br>�������:<strong> ".$rows["accessorySize"]."</strong>":"")."
				
				<br>".($rows["oldprice"]>0?"������ ����: <strong><s>".round($rows["oldprice"],2)." ���.</s></strong>
				<br>����� ����: <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>":
				"����: <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"���� �� ������� ������ ��� ���������� �������� - ��������� �� ����� 3-� ������� �� ��� ��� ����� �������!\")' title='���� �� ������� ������ ��� ���������� �������� - ��������� �� ����� 3-� ������� �� ��� ��� ����� �������!' >����<b><sup><font color=blue>��� ���������� ��������</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." ���.</font></strong></a>":""));
				if ($rows['price1'] && $rows['amount1']) {
				
					$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
					<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>������� ����:
					<td class=tboard>���-��<td class=tboard>".$rows['amount1']; "</tr>";
					$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>����<td class=tboard>".$rows['price1'];
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
					$text = str_replace(" ����� ","<strong> ����� </strong>",$text);
					$text = str_replace(" ������ ","<strong> ������ </strong>",$text);
					$text = str_replace(" ������ ","<strong> ������ </strong>",$text);
					$text = str_replace(" ������� ","<strong> ������� </strong>",$text);
					echo "<br>��������: ".str_replace("\n","<br>",$text);
				}
				echo "</td></tr></table>";
			}
			elseif ($rows['materialtype']==5) {
			
				echo "<table border=0 cellpadding=2 cellspacing=0 width=100%>";
				echo "<tr bgcolor=#99CCFF>
				<td class=tboard width=80% colspan=2><a name=coin".$rows["shopcoins"]." title='".$rows["name"]."'></a><strong>".$namecoins."</strong></td></tr>";
				echo "<tr>
				<td class=tboard>
				<a href='$rehref' title='��������� � ����� ".$rows["name"]."'>
				<img src=images/".$rows["image"]." alt='".$rows["name"]."' border=1 style='border-color:black'>
				</a>
				</td></tr><td class=tboard>";
				
				if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder))
				{
					echo "
					<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows["shopcoins"]]."'> 
					<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].",".$rows["materialtype"].")' title='".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz7.gif border=0 alt='��� � �������'></div></a>";
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
						<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")' title='�������� � ������� ����� ".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='�������� � ������� ����� ".$rows["name"]."'></div></a>
						";
						if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." ���.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
						//javascript:AddAccessory(\"$script?pageinfo=cookies&shopcoinsorder=".$rows["shopcoins"]."&typeorder=1&pagenum=$pagenum&pricevalue=$pricevalue&".($search?"search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]."\"
				}
				echo $textoneclick;
				if (strlen($rows["details"])>250 or $rows["image_big"]) {
//					echo "&nbsp;<a href='index.php?page=show&catalog=".$rows["shopcoins"]."&pagenum=$pagenum&pricevalue=$pricevalue".($search?"&search=".urlencode($search):"").($searchid?"&searchid=".$searchid:"")."&group=$group&materialtype=".$rows['materialtype'].($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"").($theme?"&theme=".$theme:"")."' title='��������� � ����� ".$rows["name"]."'><img src=".$in."images/corz3.gif border=0 alt='��������� � ����� ".$rows["name"]."'></a>";
					echo "&nbsp;<a href='$rehref' title=\"��������� � ".$rows['name']."\"><img src=".$in."images/corz3.gif border=0 alt='��������� � ".$rows['name']."'></a>";
				}
					
				echo "<br>������:  <a href=$script?group=".$rows['group']."&materialtype=".$rows['materialtype']." title='���������� ��� ".$rows["gname"]."'><strong><font color=blue>".$rows["gname"]."</font></strong></a>
				<br>��������: <strong>".$rows["name"]."</strong>
				".($rows["number"]?"<br>�����:<strong> ".$rows["number"]."</strong>":"")."
				".($rows["accessoryProducer"]?"<br>ISBN:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
				".($rows["accessoryColors"]?"<br>��� �������:<strong> ".$rows["accessoryColors"]."</strong>":"")."
				".($rows["accessorySize"]?"<br>���������� �������:<strong> ".$rows["accessorySize"]."</strong>":"")."
				<br>".($rows["oldprice"]>0?"������ ����: <strong><s>".round($rows["oldprice"],2)." ���.</s></strong>
				<br>����� ����: <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>":
				"����: <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"���� �� ������� ������ ��� ���������� �������� - ��������� �� ����� 3-� ������� �� ��� ��� ����� �������!\")' title='���� �� ������� ������ ��� ���������� �������� - ��������� �� ����� 3-� ������� �� ��� ��� ����� �������!' >����<b><sup><font color=blue>��� ���������� ��������</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." ���.</font></strong></a>":""));
				
				if ($rows['price1'] && $rows['amount1']) {
				
					$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
					<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>������� ����:
					<td class=tboard>���-��<td class=tboard>".$rows['amount1']; "</tr>";
					$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>����<td class=tboard>".$rows['price1'];
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
					$text = str_replace(" ����� ","<strong> ����� </strong>",$text);
					$text = str_replace(" ������ ","<strong> ������ </strong>",$text);
					$text = str_replace(" ������ ","<strong> ������ </strong>",$text);
					$text = str_replace(" ������� ","<strong> ������� </strong>",$text);
					$text = str_replace(" �����.","<strong> �����.</strong>",$text);
					$text = str_replace(" ������.","<strong> ������.</strong>",$text);
					$text = str_replace(" ������.","<strong> ������.</strong>",$text);
					$text = str_replace(" �������.","<strong> �������.</strong>",$text);
					echo "<br>��������: ".str_replace("\n","<br>",$text);
				}
				echo "</td></tr>";
				echo "</table>";
			}
			else {
				echo "<table border=0 cellpadding=2 cellspacing=0 width=100%>
				<tr bgcolor=#99CCFF>
				<td class=tboard width=80% colspan=2><a name=coin".$rows["shopcoins"]." title='".($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==1 || $rows["materialtype"]==11?"������":($rows["materialtype"]==2?"��������(����)":"����� �����"))." - ".$rows["gname"]." ".$rows["name"]."'></a><strong>".$namecoins."</strong> </td></tr>
				
				<tr><td class=tboard>";
				//if ($REMOTE_ADDR == $myip)
					
				if ($ImageParent[$rows["parent"]]>0 && ($rows["materialtype"]==1 ||$rows["materialtype"]==10 ||$rows["materialtype"]==12) && !$mycoins)
				{
					echo "<table border=0 cellpadding=1 cellspacing=0 width=294>
					<tr><td valign=top><a href='$rehref' title='������ - ".$rows["gname"]." ".$rows["name"]." - ��������� ����������'>
					".(file_exists("./images/".$rows["image"]."")?"<img src=images/".$rows["image"]." alt='������ - ".$rows["gname"]." ".$rows["name"]." - ��������� ����������' border=1 style='border-color:black'>":"<font color=blue size=+2>��� �����������</font>")."
					</a></td>
					<td valign=top align=center>";
					unset ($tmpsmallimage);
					$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='������ ".$rows["gname"]." | ".$rows["name"]."' width=80  style='border-color:black'>";
					$tmpsmallimage[] = "<img src=smallimages/".$ImageParent[$rows["parent"]][0]." border=1 alt='������ ".$rows["gname"]." | ".$rows["name"]."' width=80  style='border-color:black'>";
					
					echo "<a href='$rehrefdubdle'>".implode("<br>",$tmpsmallimage)."<br><img src=".$in."images/corz13.gif border=0 alt='���������� ������ �������� ����� ".$rows["gname"]." | ".$rows["name"]."'></a></td></tr>
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
					<tr><td valign=top><a href='$rehref' title='".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":"����� �����"))." - ".$rows["gname"]." ".$rows["name"]." - ��������� ����������'>
					".(file_exists("./images/".$rows["image"]."")?"<img src=images/".$rows["image"]." ".($width_image&&$height_image?"width='$width_image' height='$height_image'":"")." alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":"����� �����"))." - ".$rows["gname"]." ".$rows["name"]." - ��������� ����������' border=1 style='border-color:black'>":"<font color=blue size=+2>��� �����������</font>")."
					</a></td>
					<td valign=top align=center>";
					unset ($tmpsmallimage);
					$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==10?"��������":"����� �����")))." - ".$rows["gname"]." ".$rows["name"]."' width=80  style='border-color:black'>";
					$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":"����� �����"))." - ".$rows["gname"]." ".$rows["name"]."' width=80  style='border-color:black'>";
					
					echo "<a href='$rehrefdubdle' title='���������� ������ �������� ".($rows["materialtype"]==8||$rows["materialtype"]==6?"�����":($rows["materialtype"]==2?"�������(���)":($rows["materialtype"]==10?"����������":"������� �����")))." - ".$rows["gname"]." ".$rows["name"]."'>".implode("<br>",$tmpsmallimage)."<br><img src=".$in."images/corz13.gif border=0 alt='���������� ������ �������� ".($rows["materialtype"]==8||$rows["materialtype"]==6?"�����":($rows["materialtype"]==2?"�������(���)":($rows["materialtype"]==10?"����������":"������� �����")))." - ".$rows["gname"]." ".$rows["name"]."'></a></td></tr>
					</table>";
				}
				elseif ($rows["materialtype"]==4 && $rows["amount"] > 1 && !$mycoins) {
				
					echo "<table border=0 cellpadding=1 cellspacing=0 width=294>
					<tr><td valign=top><a href='$rehref' title='".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":"����� �����"))." - ".$rows["gname"]." ".$rows["name"]." - ��������� ����������'>
					".(file_exists("./images/".$rows["image"]."")?"<img ".(($rows["materialtypecross"] & pow(2,1)||$rows["materialtypecross"] & pow(2,8))&&($materialtype==1||$materialtype==8||$materialtype==6)?"width=206 src=images/".$rows["image_big"]."":"src=images/".$rows["image"]."")." alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":"����� �����"))." - ".$rows["gname"]." ".$rows["name"]." - ��������� ����������' border=1 style='border-color:black'>":"<font color=blue size=+2>��� �����������</font>")."
					</a></td>
					<td valign=top align=center>";
					unset ($tmpsmallimage);
					$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==10?"��������":"����� �����")))." - ".$rows["gname"]." ".$rows["name"]."' width=80  style='border-color:black'>";
					$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":"����� �����"))." - ".$rows["gname"]." ".$rows["name"]."' width=80  style='border-color:black'>";
					
					echo "<a href='$rehrefdubdle' title='���������� ������ �������� ".($rows["materialtype"]==8||$rows["materialtype"]==6?"�����":($rows["materialtype"]==2?"�������(���)":($rows["materialtype"]==10?"����������":"������� �����")))." - ".$rows["gname"]." ".$rows["name"]."'>".implode("<br>",$tmpsmallimage)."<br><img src=".$in."images/corz13.gif border=0 alt='���������� ������ �������� ".($rows["materialtype"]==8||$rows["materialtype"]==6?"�����":($rows["materialtype"]==2?"�������(���)":($rows["materialtype"]==10?"����������":"������� �����")))." - ".$rows["gname"]." ".$rows["name"]."'></a></td></tr>
					</table>";
				}
				elseif ($rows["materialtype"]==4) {
					echo "<a href='$rehref' title='".(($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==1 || $rows["materialtype"]==11)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." - ".$rows["gname"]." ".$rows["name"]." - ��������� ����������'>
					".(file_exists("./images/".$rows["image"]."")?"<img ".(($rows["materialtypecross"] & pow(2,1)||$rows["materialtypecross"] & pow(2,8))&&($materialtype==1||$materialtype==8||$materialtype==6)?"width=206 src=images/".$rows["image_big"]."":"src=images/".$rows["image"]."")." alt='".(($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==1 || $rows["materialtype"]==11)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." - ".$rows["gname"]." ".$rows["name"]." - ��������� ����������' border=1 style='border-color:black'>":"<font color=blue size=+2>��� �����������</font>")."
					</a><br>";
				}
				else {
				
					echo "<a href='$rehref' title='".(($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." - ".$rows["gname"]." ".$rows["name"]." - ��������� ����������'>
					".(file_exists("./images/".$rows["image"]."")?"<img src=images/".$rows["image"]." alt='".(($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." - ".$rows["gname"]." ".$rows["name"]." - ��������� ����������' border=1 style='border-color:black'>":"<font color=blue size=+2>��� �����������</font>")."
					</a><br>";
				}
				
				if(!$mycoins) {
					// vbhjckfd start
					
					$reservedForSomeUser = (1>2);
					$reservedForSomeGroup =  $rows['timereserved'] > time() ; // group, lower priority than personal
					$isInRerservedGroup = null;
					
					if($tpl['user']['user_id'] && $reservedForSomeGroup && !$reservedForSomeUser)
					{
						$isInRerservedGroup = isInRerservedGroup($tpl['user']['user_id'], $rows["shopcoins"]);
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
							echo "<img src=".$in."images/corz7.gif border=0 alt='��� � ����� �������'>";
						elseif ( ($reservedForSomeUser || (!$tpl['user']['user_id'] && $reservedForSomeGroup) || (false === $isInRerservedGroup)) && $reserveamount>=$rows["amount"])
							echo "<img src=".$in."images/corz6.gif border=0 alt='�������� ������ ���������� ".($rows["materialtype"]==8 || $rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." ".$rows["gname"]." ".$rows["name"]."'>";
						elseif($statusopt>0) {
						
							if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder))
							{
								echo "
								<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows["shopcoins"]]."'> 
								<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].",".$rows["materialtype"].")' title='".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz7.gif border=0 alt='��� � �������'></div></a>";
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
									<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")' title='�������� � ������� ����� ".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='�������� � ������� ����� ".$rows["name"]."'></div></a>
									";
									if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." ���.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
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
							and ($rows["check"] == 1 || $rows["check"] == 50 || ($tpl['user']['user_id']==811 && $rows["check"] >3))
							)
								echo "<div id=bascetshopcoins".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddBascet('".$rows["shopcoins"]."','1');\" rel=\"nofollow\" title='�������� � ������� ".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==10?"��������":"����� �����")))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz1.gif border=0 alt='�������� � ������� ".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==10?"��������":"����� �����")))." ".$rows["gname"]." ".$rows["name"]."'></a></div>";
								if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." ���.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
						}
						
						if (strlen($rows["details"])>250 or $rows["image_big"])
							echo "&nbsp;<a href='$rehref' title='��������� ���������� � ".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==10?"���������":"������ �����")))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz3.gif border=0 alt='��������� ���������� � ".($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==10?"���������":"������ �����")))." ".$rows["gname"]." ".$rows["name"]."'></a>";
							echo $textoneclick;
						
						if ($reservedForSomeUser && $reserveamount>=$rows["amount"]) {
							echo "<br><font color=gray size=-2>����� �� ".date("H:i", $reserveuser+$reservetime)."</font>";
							
							if (!$statusshopcoins && false === $isInRerservedGroup && $rows['relationcatalog']>0 && $tpl['user']['user_id']>0)
								echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='��� ��������� ��������� ������� ���� ������ � �������� ��� ����� ���������� ����������� �� email...'>�������� ������ ����� �������</a></div>";
						}
						elseif($reservedForSomeGroup && $reserveamount>=$rows["amount"]) {
							if($isInRerservedGroup) {
								echo '<br><font color=#ff0000 size=-2>�� '.($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����")))).' �� �������� ������ ����� <a href=../catalognew target=_blank style="font-size:9px;">�������</a>. ��� �������� ��� ��� �������.</font>';
							}
							else {
								echo '<br><font color=gray size=-2>�� '.($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����")))).' ���� ��������� ������ ����� <a href=../catalognew target=_blank style="font-size:9px;" title=\'������� ����� ������, ��������, ��� � ������ �����\'>�������</a>. '.($rows["materialtype"]==8||$rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����")))).' �� '. date("H:i",$rows['timereserved']) .' ����� ������ ������ �������, ���������� ������.</font>';
								if ( $rows['relationcatalog']>0 && $tpl['user']['user_id']>0)
									echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='��� ��������� ��������� ������� ���� ������ � �������� ��� ����� ���������� ����������� �� email...'>�������� ������ ����� �������</a></div>";
							}			
						}
					}
					else {
						
						$reservinfo = '';
						$reservedForSomeUser = ( $rows["reserve"] > 0 && ( time() - (int) $rows["reserve"] < $reservetime )); //personal
						
						if (time() - (int) $rows["reserve"] < $reservetime  and $rows["reserveorder"] == $shopcoinsorder)
							echo "<img src=".$in."images/corz7.gif border=0 alt='��� � ����� �������'>";
						elseif ( $reservedForSomeUser || (!$tpl['user']['user_id'] && $reservedForSomeGroup) || (false === $isInRerservedGroup) ) {
							
							echo "<img src=".$in."images/corz6.gif border=0 alt='�������� ������ ���������� ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." ".$rows["gname"]." ".$rows["name"]."'>";
							
							if ($rows['doubletimereserve'] > time() && $tpl['user']['user_id']>0 && $tpl['user']['user_id'] == $rows['userreserve']) {
							
								$reservinfo = "<img src=".$in."images/corz77.gif border=0 alt='�� � ������� �� ������� ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"���� �����":($rows["materialtype"]==10?"���������":"������ �����"))))." ".$rows["gname"]." ".$rows["name"]."'>";
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
								and ($rows["check"] == 1 || $rows["check"] == 50 || ($tpl['user']['user_id']==811 && $rows["check"] >3))
								)
									$reservinfo = "<div id=bascetshop".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddNext('".$rows["shopcoins"]."','1');\" rel=\"nofollow\" title='����� � ������� �� ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz11.gif border=0 alt='����� � ������� �� ".($rows["materialtype"]==1?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." ".$rows["gname"]." ".$rows["name"]."'></a></div>";
							}
							elseif ($rows['timereserved']<$rows['reserve'] && $rows['doubletimereserve'] < time() && $tpl['user']['user_id']>0) {
							
								$reservinfo = "<div id=bascetshop".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddNext('".$rows["shopcoins"]."','1');\" rel=\"nofollow\" title='����� � ������� �� ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz11.gif border=0 alt='����� � ������� �� ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." ".$rows["gname"]." ".$rows["name"]."'></a></div>";
							}
						}
						elseif ($rows['doubletimereserve'] > time() && $tpl['user']['user_id'] != $rows['userreserve'])
							echo "<img src=".$in."images/corz6.gif border=0 alt='�������� ������ ���������� ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." ".$rows["gname"]." ".$rows["name"]."'>";
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
							and ($rows["check"] == 1 || $rows["check"] == 50 || ($tpl['user']['user_id']==811 && $rows["check"] >3))
							)
								echo "<div id=bascetshopcoins".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddBascet('".$rows["shopcoins"]."','1');\" rel=\"nofollow\" title='�������� � ������� ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz1.gif border=0 alt='�������� � ������� ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":($rows["materialtype"]==10?"��������":"����� �����"))))." ".$rows["gname"]." ".$rows["name"]."'></a></div>";
								if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." ���.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
						}
						
						if (strlen($rows["details"])>250 or $rows["image_big"])
							echo "&nbsp;<a href='$rehref' title='��������� ���������� � ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":"������ �����"))." ".$rows["gname"]." ".$rows["name"]."'><img src=".$in."images/corz3.gif border=0 alt='��������� ���������� � ".(($rows["materialtype"]==1 || $rows["materialtype"]==11 ||$rows["materialtype"]==12)?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==10?"���������":"������ �����")))." ".$rows["gname"]." ".$rows["name"]."'></a>";
						
						echo $textoneclick;
						
						if ($reservinfo)
							echo "<br>".$reservinfo;
						
						if ($reservedForSomeUser) {
							echo "<br><font color=gray size=-2>����� �� ".date("H:i", $rows["reserve"]+$reservetime)."</font>";
							
							if (time() - (int) $rows["reserve"] >= $reservetime  || $rows["reserveorder"] != $shopcoinsorder  && $rows['relationcatalog']>0 && $tpl['user']['user_id']>0)
								echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='��� ��������� ��������� ������� ���� ������ � �������� ��� ����� ���������� ����������� �� email...'>�������� ������ ����� �������</a></div>";
						}
						elseif($reservedForSomeGroup) {
							if($isInRerservedGroup) {
								echo '<br><font color=#ff0000 size=-2>�� ������ �� �������� ������ ����� <a href=../catalognew target=_blank style="font-size:9px;">�������</a>. ��� �������� ��� ��� �������.</font>';
							}
							else {
								echo '<br><font color=gray size=-2>�� ������ '.$rows["gname"].' '.$rows["name"].' ���� ��������� ������ ����� <a href=../catalognew target=_blank style="font-size:9px;" title=\'������� ����� ������, ��������, ��� � ������ �����\'>�������</a>. ������ �� '. date("H:i",$rows['timereserved']) .' ����� ������ ������ �������, ���������� ������.</font>';
								if ( $rows['relationcatalog']>0 && $tpl['user']['user_id']>0)
									echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='��� ��������� ��������� ������� ���� ������ � �������� ��� ����� ���������� ����������� �� email...'>�������� ������ ����� �������</a></div>";
							}			
						}
						elseif ($rows['doubletimereserve'] > time() && $tpl['user']['user_id'] != $rows['userreserve']) {
							echo '<br><font color=gray size=-2>������ '.$rows["gname"].' '.$rows["name"].' ���� �������������. ������ �� '. date("H:i",$rows['doubletimereserve']) .' ����� ������ ������ ������, ����������� �����.</font>';
							if ( $rows['relationcatalog']>0 && $tpl['user']['user_id']>0)
									echo "<br><div id=mysubscribecatalog".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:WaitSubscribeCatalog(".$rows["shopcoins"].");process('addsubscribecatalog.php?catalog=".$rows["shopcoins"]."');\" title='��� ��������� ��������� ������� ���� ������ � �������� ��� ����� ���������� ����������� �� email...'>�������� ������ ����� �������</a></div>";
						}
						if ($rows['doubletimereserve'] > time() && $tpl['user']['user_id'] == $rows['userreserve'] && $tpl['user']['user_id']>0)
							if ( $reservedForSomeUser || (!$tpl['user']['user_id'] && $reservedForSomeGroup) || (false === $isInRerservedGroup) )
								echo "<font color=red size=-2>�� � ������� �� ������ �� ".date("H:i", $rows["doubletimereserve"])."</font>";
							else
								echo "<br><font color=red size=-2>�� ������ ������ ������. ���� ����� �� ".date("H:i", $rows["doubletimereserve"])."</font>";
					}
		
					// vbhjckfd stop
					
					
					if ($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==7 || $rows["materialtype"]==2 || $rows["materialtype"]==4) {
				
						if ( !$rows["amount"]) $amountall = 1;
						else $amountall = $rows["amount"];
						echo "<br>����������:  <strong>".$amountall."</strong>";
					}
				
				}
				
				if ($rows["gname"])
					echo (!$mycoins?"<br>":"").($rows["materialtype"]==9?"������":"������").":  <a href=$script?group=".$rows['group']."&materialtype=".$rows["materialtype"]." title='���������� ".($rows["materialtype"]==1 || $rows["materialtype"]==8 || $rows["materialtype"]==6?"������":($rows["materialtype"]==2?"��������(����)":($rows["materialtype"]==9?"��� �����":"����� �����")))." ".$rows["gname"]."'><strong><font color=blue>".$rows["gname"]."</font></strong></a>";
				if ($rows["name"])
					echo "<br>��������: <strong>".$rows["name"]."</strong>";
				
				echo "<br>�����: <strong>".$rows["number"]."</strong>
				<br>".($rows["oldprice"]>0?($rows["materialtype"]==8||$rows["materialtype"]==6?"������ ����":"������ ���������").": <strong><s>".round($rows["oldprice"],2)." ���.</s></strong>
				<br>".($rows["materialtype"]==8||$rows["materialtype"]==6?"����� ����":"����� ���������").": <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>":
				($rows["materialtype"]==8||$rows["materialtype"]==6?"����":"���������").": <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"���� �� ������� ������ ��� ���������� �������� - ��������� �� ����� 3-� ������� �� ��� ��� ����� �������!\")' title='���� �� ������� ������ ��� ���������� �������� - ��������� �� ����� 3-� ������� �� ��� ��� ����� �������!' >".($rows["materialtype"]==8||$rows["materialtype"]==6?"����":"���������")."<b><sup><font color=blue>��� ���������� ��������</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." ���.</font></strong></a>":""));
				
				//if ($rows["materialtype"]==12) 
					//echo " �� ������ ������� ������ �� ����������������.";
				
				if ($rows['price1'] && $rows['amount1']) {
				
					$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
					<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>������� ����:
					<td class=tboard>���-��<td class=tboard>".$rows['amount1']; "</tr>";
					$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>����<td class=tboard>".$rows['price1'];
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
				
				if($rows['year'] == 1990 && $materialtype==12) $rows['year'] = '1990 ���';
				if($rows['year'] == 1991 && $materialtype==12) $rows['year'] = '1991 ���';
				if($rows['year'] == 1992 && $materialtype==12) $rows['year'] = '1991 ���';
				
				echo (trim($rows["metal"])?"<br>������: <strong>".$rows["metal"]."</strong>":"")."
				".($rows["width"]&&$rows["height"]?"<br>��������������� ������: <strong>".$rows["width"]."*".$rows["height"]." ��.</strong>":"")."
				".($rows["weight"]>0?"<br>���: <strong>".$rows["weight"]." ��.</strong>":"")."
				".($rows["year"]?"<br>���: <strong>".$rows["year"]."</strong>":"")."
				".(trim($rows["condition"])?"<br>���������: <strong><font color=blue>".$rows["condition"]."</font></strong>":"")."
				".($rows["series"]&&$group?"<br>����� �����: <a href=$script?series=".$rows["series"]."&group=".$group."&materialtype=".$materialtype.">".$series_name[$rows["series"]]."</a>":"")."
				".($rows["materialtype"]==8&&$materialtype==1&&!$mycoins?"<br><font color=red>������ � ������� ������, ��. ������� ������� � �������</font>":"");
				
				
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
					echo "<br>��������: <strong>".implode(", ", $shopcoinstheme)."</strong>";
				
				if (trim($rows["details"]))
				{
					$text = substr($rows["details"], 0, 250);
					$text = strip_tags($text);
					$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
					$text = str_replace($rows['name'],"<strong>".$rows['name']."</strong>",$text);
					$text = str_replace($rows['gname'],"<strong>".$rows['gname']."</strong>",$text);
					$text = str_replace(" ����� ","<strong> ����� </strong>",$text);
					$text = str_replace(" ������ ","<strong> ������ </strong>",$text);
					$text = str_replace(" ������ ","<strong> ������ </strong>",$text);
					$text = str_replace(" ������� ","<strong> ������� </strong>",$text);
					echo "<br>��������: ".str_replace("\n","<br>",$text)."";
				}
				
				if ($rows["dateinsert"]>time()-86400*180 && !$mycoins)
					echo "<br>���������: <strong>".($rows["dateinsert"]>time()-86400*14?"<font color=red>NEW</font> ".date("Y-m-d", $rows["dateinsert"]):date("Y-m-d", $rows["dateinsert"]))."</strong>";
					
					
					if(	$materialtype == 11 AND is_user_has_premissons(intval($_COOKIE['cookiesuser'])) AND is_logged_in(intval($_COOKIE['cookiesuser'])) != FALSE AND !is_already_described($rows['shopcoins']))
					{ 
	echo "<a href=# style='float:right;' onClick=\"window.open('/shopcoins/change_coin.php?modal=1&coin=".$rows['shopcoins']."','Catalog','status=no,menubar=no,scrollbars=yes,resizable=no,width=670,height=650');return false;\">������� (���������� ����� �� �������)</a> ";
				}
					
				$rand = rand(1,2);
				/*if ($rows["realization"]&&!$mycoins)
						echo ($rand==1?"<br>������ �� ����������":"<br>������ �� ��������");*/
				
				echo "</td>";
				if ($mycoins) {
					
					if ($rows['check']=1 && $rows['materialtype']!=1 && $rows['amount']>0) {
					
						echo "<td width=40% valign=top> <br> <a href=#  onclick=\"javascript:ShowShopcoinsStart2();process('showshopcoins.php?shopcoins=".$rows["shopcoins"]."')\" title='���������� �������� ������� � ��������'>���������� �������� ������� � ��������</a>";
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
							
								echo "<td width=40% valign=top> <br><a href=#  onclick=\"javascript:ShowShopcoinsStart2();process('showshopcoins.php?shopcoins=".$rows_m["shopcoins"]."')\" title='���������� �������� ������� � ��������'>���������� �������� ������� � ��������</a>";
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
	}
	elseif ($materialtype==4) //���������� ������
	{
		//while ($rows = mysql_fetch_array($result))
		foreach ($MyShowArray as $key=>$rows)
		{
			
			$textoneclick = '';
			
			$mtype = ($materialtype>0?$materialtype:$rows['materialtype']);
			
			if ($mtype==1)
				$rehref = "������-";
			elseif ($mtype==8)
				$rehref = "������-";
			elseif ($mtype==7)
				$rehref = "�����-�����-";
			elseif ($mtype==2)
				$rehref = "��������-";
			elseif ($mtype==4)
				$rehref = "�����-�����-";
			elseif ($mtype==5)
				$rehref = "�����-";
			elseif ($mtype==9)
				$rehref = "��� ����� ";
			else 
				$rehref = "";
					
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
			
			echo "<table border=0 cellpadding=2 cellspacing=0 width=100%>
			<tr bgcolor=#99CCFF>
			<td class=tboard width=80% colspan=2><a name=coin".$rows["shopcoins"]."></a><strong>".$rows["name"]."</strong></td></tr>
			
			<tr><td class=tboard>";
			if ($ImageParent[$rows["parent"]]>0)
			{
				echo "<table border=0 cellpadding=1 cellspacing=0 width=294>
				<tr><td valign=top><a href='$rehref'>
				<img src=images/".$rows["image"]." alt='".$rows["name"]."' border=1 style='border-color:black'>
				</a></td>
				<td valign=top align=center>";
				unset ($tmpsmallimage);
				$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='".$rows["gname"]." | ".$rows["name"]."' width=80  style='border-color:black'>";
				$tmpsmallimage[] = "<img src=smallimages/".$rows["image_small"]." border=1 alt='".$rows["gname"]." | ".$rows["name"]."' width=80  style='border-color:black'>";
				
				echo "<a href='$rehrefdubdle'>".implode("<br>",$tmpsmallimage)."<br><img src=".$in."images/corz13.gif border=0 alt='���������� ������ �������� �����'></a></td></tr>
				</table>";
			}
			else
				echo "<a href='$rehref'>
				<img src=images/".$rows["image"]." alt='".$rows["name"]."' border=1 style='border-color:black'>
				</a><br>";
			
			
			
			
			/*echo "<table border=0 cellpadding=2 cellspacing=0>";
			echo "<tr bgcolor=#99CCFF>
			<td class=tboard width=600><a name=coin".$rows["shopcoins"]."></a><strong>".$rows["name"]."</strong></td></tr>";
			echo "<tr><td class=tboard>
			<a href='$script?page=show&catalog=".$rows["shopcoins"].$addhref."'>
			<img src=images/".$rows["image"]." alt='".$rows["name"]."' border=1 style='border-color:black'>
			</a>";*/
			
			echo "</td></tr><tr><td class=tboard valign=top>";
			
			$reservedForSomeUser = (1>2);
			
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
			
			if (!$reserveuser && $reservealluser) 
				$reserveuser=$reservealluser;
			
			
			if ($statusshopcoins)
				echo "<img src=".$in."images/corz7.gif border=0 alt='��� � �������'>";
			elseif ($reservedForSomeUser)
				echo "<img src=".$in."images/corz6.gif border=0 alt='�������� ������ ����������'>";
			else
			{
				if ($REMOTE_ADDR!="213.180.194.162" 
				and $REMOTE_ADDR!="213.180.194.133" 
				and $REMOTE_ADDR!="213.180.194.164" 
				and $REMOTE_ADDR!="213.180.210.2" 
				and $REMOTE_ADDR!="83.149.237.18"
				and $REMOTE_ADDR!="83.237.234.171"
				and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"Yandex")
				and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"ia_archiver")
				and !substr_count($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"coona")
				and ($rows["check"] == 1 || $rows["check"] == 50 || ($tpl['user']['user_id']==811 && $rows["check"] >3))
				)
					echo "<div id=bascetshopcoins".$rows["shopcoins"]."><a href='#coin".$rows["shopcoins"]."' onclick=\"javascript:AddBascet('".$rows["shopcoins"]."','1');\" rel=\"nofollow\"><img src=".$in."images/corz1.gif border=0 alt='� �������'></a></div>";
					if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." ���.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
			}
			
			if (strlen($rows["details"])>250 or $rows["image_big"])
				echo "&nbsp;<a href='$rehref'><img src=".$in."images/corz3.gif border=0 alt='���������'></a>";
			echo $textoneclick;
			if ($reservedForSomeUser)
					echo "<br><font color=gray size=-2>����� �� ".date("H:i", $reserveuser+$reservetime)."</font>";
			
			if ( !$rows["amount"]) $amountall = 1;
			else $amountall = $rows["amount"];
			echo "<br><strong>����������:  </strong>".$amountall."";
			
			echo "<br><strong>������:  <font color=blue>".$rows["gname"]."</font></strong>
			<br><strong>��������: </strong>".$rows["name"]."
			<br><strong>�����: </strong>".$rows["number"]."
			<br><strong>����: </strong> <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>";
			if ($rows['price1'] && $rows['amount1']) {
				
					$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
					<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>������� ����:
					<td class=tboard>���-��<td class=tboard>".$rows['amount1']; "</tr>";
					$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>����<td class=tboard>".$rows['price1'];
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
			echo (trim($rows["metal"])?"<br><strong>������: </strong>".$rows["metal"]:"")."
			".($rows["width"]&&$rows["height"]?"<br><strong>��������������� ������: </strong>".$rows["width"]."*".$rows["height"]." ��.":"")."
			".($rows["year"]?"<br><strong>���: </strong>".$rows["year"]:"")."	
			".(trim($rows["condition"])?"<br><strong>���������: <font color=blue>".$rows["condition"]."</font></strong>":"");
			
			unset ($shopcoinstheme);
			$strtheme = decbin($rows["theme"]);
			$strthemelen = strlen($strtheme);
			$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
			for ($k=0; $k<$strthemelen; $k++)
			{
				if ($chars[$k]==1)
					$shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
			}
			//print_r($chars);
			
			if (sizeof($shopcoinstheme))
				echo "<br><strong>��������: </strong>".implode(", ", $shopcoinstheme);
			
			if (trim($rows["details"]))
			{
				$text = substr($rows["details"], 0, 350);
				$text = substr($text, 0, strlen($text) - strpos(strrev($text), '.'));
				echo "<br><strong>��������: </strong>".str_replace("\n","<br>",$text);
			}
			
			if ($rows["dateinsert"]>time()-86400*180)
				echo "<br><strong>���������</strong>: ".($rows["dateinsert"]>time()-86400*14?"<strong><font color=red>NEW</font></strong>":date("Y-m-d", $rows["dateinsert"]));
			
			$rand = rand(1,2);
			if ($rows["realization"])
					echo ($rand==1?"<br>������ �� ����������":"<br>������ �� ��������");
			echo "</td></tr></table>";
		}
		
		echo $page_print."<br>";
	}
	elseif ($materialtype==3) //���������
	{
		
		
		//echo $shopcoinsorder."-".$amount;
		echo "
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
				alert ('������� ����������');
		}
		
		</script>
		<table border=0 cellpadding=3 cellspacing=0 width=100%>
		<form action=$script?pageinfo=cookies&typeorder=1&pagenum=$pagenum".($search?"&search=".urlencode($search):"").($searchid?"&searchid=".$searchid:"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]." method=post name=mainform>
		<input type=hidden name=shopcoinsorder value=''>
		<input type=hidden name=shopcoinsorderamount value=''>
		";
		$i=1;
		//while ($rows = mysql_fetch_array($result))
		foreach ($MyShowArray as $key=>$rows)
		{
			
			$textoneclick = '';
			
			$mtype = ($materialtype>0?$materialtype:$rows['materialtype']);
			
			if ($mtype==1)
				$rehref = "������-";
			elseif ($mtype==8)
				$rehref = "������-";
			elseif ($mtype==7)
				$rehref = "�����-�����-";
			elseif ($mtype==2)
				$rehref = "��������-";
			elseif ($mtype==4)
				$rehref = "�����-�����-";
			elseif ($mtype==5)
				$rehref = "�����-";
			elseif ($mtype==9)
				$rehref = "��� ����� ";
			else 
				$rehref = "";
					
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
			<a href='$rehref' title='".$rows["name"]."'>
			<img src=images/".$rows["image"]." alt='".$rows["name"]."' border=1 style='border-color:black'>
			</a>
			</td></tr><td class=tboard>";
			
			if ($rows['group'] == 1604)
				echo "&nbsp;";
			elseif (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder))
				echo "
				<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows["shopcoins"]]."'> 
				<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz7.gif border=0 alt='��� ���� � ����� �������'></div></a>";
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
					<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='� �������'></div></a>
					";
					if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." ���.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
					//javascript:AddAccessory(\"$script?pageinfo=cookies&shopcoinsorder=".$rows["shopcoins"]."&typeorder=1&pagenum=$pagenum&pricevalue=$pricevalue&".($search?"search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]."\"
			}
			
			if (strlen($rows["details"])>250 or $rows["image_big"])
				echo "&nbsp;<a href='$rehref' title=\"��������� � ".$rows['name']."\"><img src=".$in."images/corz3.gif border=0 alt='��������� � ".$rows['name']."'></a>";
			echo $textoneclick;
			if ($rows['group'] == 1604)
				$amountall = "�� ��������������";
			if ( !$rows["amount"]) 
				$amountall = 1;
			else $amountall = $rows["amount"];
			
			echo "<br>����������:  <strong>".$amountall."</strong>";
					
			echo "<br>������:  <a href=$script?group=".$rows['group']."&materialtype=".$rows['materialtype']." title='���������� ��� ".$rows["gname"]."'><strong><font color=blue>".$rows["gname"]."</font></strong></a>
			<br>��������: <strong>".$rows["name"]."</strong>
			".($rows["number"]?"<br>�����:<strong> ".$rows["number"]."</strong>":"")."
			".($rows["accessoryProducer"]?"<br>�������������:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
			".($rows["accessoryColors"]?"<br>�����:<strong> ".$rows["accessoryColors"]."</strong>":"")."
			".($rows["accessorySize"]?"<br>�������:<strong> ".$rows["accessorySize"]."</strong>":"")."
			
			<br>".($rows["oldprice"]>0?"������ ����: <strong><s>".round($rows["oldprice"],2)." ���.</s></strong>
			<br>����� ����: <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>":
			($rows['group']==1604?"��������������� ����":"����").": <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"���� �� ������� ������ ��� ���������� �������� - ��������� �� ����� 3-� ������� �� ��� ��� ����� �������!\")' title='���� �� ������� ������ ��� ���������� �������� - ��������� �� ����� 3-� ������� �� ��� ��� ����� �������!' >����<b><sup><font color=blue>��� ���������� ��������</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." ���.</font></strong></a>":""));
			
			if ($rows['price1'] && $rows['amount1']) {
				
					$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
					<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>������� ����:
					<td class=tboard>���-��<td class=tboard>".$rows['amount1']; "</tr>";
					$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>����<td class=tboard>".$rows['price1'];
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
				$text = str_replace(" ����� ","<strong> ����� </strong>",$text);
				$text = str_replace(" ������ ","<strong> ������ </strong>",$text);
				$text = str_replace(" ������ ","<strong> ������ </strong>",$text);
				$text = str_replace(" ������� ","<strong> ������� </strong>",$text);
				echo "<br>��������: ".str_replace("\n","<br>",$text);
			}
			echo "</td></tr></table>";
			
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
	}
	//-----------�����
	elseif ($materialtype==5)
	{
		
		//echo $shopcoinsorder."-".$amount;
		echo "
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
				alert ('������� ����������');
		}
		</script>
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
			
			if ($mtype==1)
				$rehref = "������-";
			elseif ($mtype==8)
				$rehref = "������-";
			elseif ($mtype==7)
				$rehref = "�����-�����-";
			elseif ($mtype==2)
				$rehref = "��������-";
			elseif ($mtype==4)
				$rehref = "�����-�����-";
			elseif ($mtype==5)
				$rehref = "�����-";
			elseif ($mtype==9)
				$rehref = "��� ����� ";
			else 
				$rehref = "";
					
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
			<a href='$rehref' title='��������� � ����� ".$rows["name"]."'>
			<img src=images/".$rows["image"]." alt='".$rows["name"]."' border=1 style='border-color:black'>
			</a>
			</td></tr><td class=tboard>";
			
			if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder))
			{
				echo "
				<input type=text name=amount".$rows["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows["shopcoins"]]."'> 
				<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")' title='".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz7.gif border=0 alt='��� � �������'></div></a>";
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
					<a href='#coin".$rows["shopcoins"]."' onclick='javascript:AddAccessory(".$rows["shopcoins"].")' title='�������� � ������� ����� ".$rows["name"]."'><div id=bascetshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz1.gif border=0 alt='�������� � ������� ����� ".$rows["name"]."'></div></a>
					";
					if ($minpriceoneclick<=$rows['price']) $textoneclick = " <a href='#coinone".$rows["shopcoins"]."' onclick=\"ShowOneClick(".$rows["shopcoins"].",'".htmlspecialchars("<img src=".$in."shopcoins/images/".$rows['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows["name"]." ".intval($rows["price"]))." ���.');\"><div  id=oneshopcoins".$rows["shopcoins"]."><img src=".$in."images/corz15.gif border=0></div></a><a name=coinone".$rows["shopcoins"]."></a>";
					//javascript:AddAccessory(\"$script?pageinfo=cookies&shopcoinsorder=".$rows["shopcoins"]."&typeorder=1&pagenum=$pagenum&pricevalue=$pricevalue&".($search?"search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"")."#coin".$rows["shopcoins"]."\"
			}
			
			if (strlen($rows["details"])>250 or $rows["image_big"])
				echo "&nbsp;<a href='$rehref' title='��������� � ����� ".$rows["name"]."'><img src=".$in."images/corz3.gif border=0 alt='��������� � ����� ".$rows["name"]."'></a>";
			echo $textoneclick;	
			echo "<br>������:  <a href=$script?group=".$rows['group']."&materialtype=".$rows['materialtype']." title='���������� ��� ".$rows["gname"]."'><strong><font color=blue>".$rows["gname"]."</font></strong></a>
			<br>��������: <strong>".$rows["name"]."</strong>
			".($rows["number"]?"<br>�����:<strong> ".$rows["number"]."</strong>":"")."
			".($rows["accessoryProducer"]?"<br>ISBN:<strong> ".$rows["accessoryProducer"]."</strong>":"")."
			".($rows["accessoryColors"]?"<br>��� �������:<strong> ".$rows["accessoryColors"]."</strong>":"")."
			".($rows["accessorySize"]?"<br>���������� �������:<strong> ".$rows["accessorySize"]."</strong>":"")."
			<br>".($rows["oldprice"]>0?"������ ����: <strong><s>".round($rows["oldprice"],2)." ���.</s></strong>
			<br>����� ����: <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>":
			"����: <strong><font color=red>".($rows["price"]==0?"���������":round($rows["price"],2)." ���.")."</font></strong>".($rows["clientprice"]>0?"<br><a href=# onclick='javascript:alert(\"���� �� ������� ������ ��� ���������� �������� - ��������� �� ����� 3-� ������� �� ���!\")' title='���� �� ������� ������ ��� ���������� �������� - ��������� �� ����� 3-� ������� �� ���!' >����<b><sup><font color=blue>��� ���������� ��������</font></sup></b>: <strong><font color=red>".round($rows["clientprice"],2)." ���.</font></strong></a>":""));
			
			if ($rows['price1'] && $rows['amount1']) {
				
					$tmpbody1 = "<br><table bgcolor=#000000 cellpadding=2 cellspacing=1 width=100%>
					<tr bgcolor=#fff8e8><td rowspan=2 class=tboard width=25%>������� ����:
					<td class=tboard>���-��<td class=tboard>".$rows['amount1']; "</tr>";
					$tmpbody2 = "<tr bgcolor=#fff8e8><td class=tboard>����<td class=tboard>".$rows['price1'];
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
				$text = str_replace(" ����� ","<strong> ����� </strong>",$text);
				$text = str_replace(" ������ ","<strong> ������ </strong>",$text);
				$text = str_replace(" ������ ","<strong> ������ </strong>",$text);
				$text = str_replace(" ������� ","<strong> ������� </strong>",$text);
				$text = str_replace(" �����.","<strong> �����.</strong>",$text);
				$text = str_replace(" ������.","<strong> ������.</strong>",$text);
				$text = str_replace(" ������.","<strong> ������.</strong>",$text);
				$text = str_replace(" �������.","<strong> �������.</strong>",$text);
				echo "<br>��������: ".str_replace("\n","<br>",$text);
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
	}
	
	if ($search and $submit and $search != 'revaluation' and $search != 'newcoins')
	{
		
		//$amount = 1;
		//���������� ������ � ������
		$sql_key = "select count(*) from keywords where word='".lowstring($search)."' and page='$script';";
		$result_key = mysql_query($sql_key);
		$rows_key = mysql_fetch_array($result_key);
		if ($rows_key[0]==0)
		{
			$sql_key2 = "insert into keywords values (0, '".lowstring(strip_string($search))."', '$script', 1, $amountsearch);";
		} else {
			$sql_key2 = "update keywords set counter=counter+1 where word='".lowstring($search)."' and page='$script';";
		}
		$result_key2 = mysql_query($sql_key2);
//		echo $sql_key2."=sql_key2<br>";
		$sql_tmp = "select * from searchkeywords where keywords='".lowstring($search)."' and page='$script';";
		$result_tmp = mysql_query($sql_tmp);
		$rows_tmp = mysql_fetch_array($result_tmp);
		if ($rows_tmp[0]==0)
		{
			$sql_tmp2 = "insert into searchkeywords values (0, '$maxcoefficient', '$sumcoefficient','".lowstring(strip_string($search))."', '$script', 1, '$amountsearch', '$timenow');";
		} else {
			$sql_tmp2 = "update searchkeywords set counter=counter+1, maxcoefficient='$maxcoefficient', sumcoefficient='$sumcoefficient', amount='$amountsearch' where keywords='".lowstring($search)."' and page='$script';";
		}
		$result_tmp2 = mysql_query($sql_tmp2);
		//echo $sql_tmp2."=sql_tmp2<br>";
			//����� ������
	}
	
	echo "<p class=txt><strong>��������: </strong>".$page_string."</p>";
	if ($materialtype == 3)
		echo "<p class=txt><font color=blue> ��� ���������� ��������(�����������) ��������������� ���������� ��������� �� ����� ���� ������� � ���. </font></p>";
}

if ($GroupDescription)
			echo "<br>".$GroupDescription."<br>";

if ($materialtype==1 && !$mycoins)
{
	echo $AdvertiseText;
}

if ($group)
	include_once "othermaterialid.php";

?>
<script type="text/javascript" src="ajax.php" language="JavaScript"></script>
<? if (!$mycoins) { ?>
<script language="JavaScript">

function WaitSubscribeCatalog(catalog)
{
	
	var str = "mysubscribecatalog"+catalog;
	myDiv = document.getElementById(str);
	myDiv.innerHTML = "<img src=<?echo $in?>images/wait.gif>";
}

function AddSubscribeCatalog ()
{
  error = xmlRoot.getElementsByTagName("error");
  errorvalue = error.item(0).firstChild.data;

  if (errorvalue == 'none')
  {
    var valueid = '';
    var value = '';
    valueid = xmlRoot.getElementsByTagName("valueid").item(0).firstChild.data;
    value = xmlRoot.getElementsByTagName("value").item(0).firstChild.data;

    if (valueid != "")
    {
      myDiv = document.getElementById("mysubscribecatalog" + valueid);
      myDiv.innerHTML = '<b><font color=silver>������ �������</font></b>';
    }
  }
  else if (errorvalue == 'auth')
  {
    myDiv = document.getElementById("mysubscribecatalog" + valueid);
    myDiv.innerHTML = '<b><font color=silver>�� �� ������������</font></b>';
  }
  else
  {
    var valueid = '';
    valueid = xmlRoot.getElementsByTagName("valueid").item(0).firstChild.data;

    myDiv = document.getElementById("mysubscribecatalog" + valueid);
    myDiv.innerHTML = '<font color=red><b>' + errorvalue + '</b></font>';
  }
}

function AddBascet (shopcoins, amount)
{
	//vbhjckfd
	yaCounter31049031.reachGoal('AddBascet');
	process ('addbascet.php?shopcoinsorder=<?echo $shopcoinsorder;?>&shopcoins=' + shopcoins + '&amount=' + amount );
	var str = '';
	str = 'bascetshopcoins' + shopcoins;
	myDiv = document.getElementById(str);
	myDiv.innerHTML = '<img src=<?echo $in;?>images/wait.gif>';
	//alert (shopcoins + " - " + amount);
}

function AddNext (shopcoins, amount)
{
	process ('addnext.php?shopcoins=' + shopcoins + '&amount=');
	var str = '';
	str = 'bascetshop' + shopcoins;
	myDiv = document.getElementById(str);
	myDiv.innerHTML = '<img src=<?echo $in;?>images/wait.gif>';
	//alert (shopcoins + " - " + amount);
}
</script>

<?}?>
<script language="JavaScript">

function ShowNext (xmlRoot)
{

	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	var bascetshopcoins = '';	
	bascetshopcoins = xmlRoot.getElementsByTagName("bascetshopcoins").item(0).firstChild.data;
	datereserve = xmlRoot.getElementsByTagName("datereserve").item(0).firstChild.data;
	
	if (errorvalue == 'none')
	{
		alert ('�� � ������� �� ������� ������, � ������ ������ �� ������� ����������� ���������� ������ ����� ������������� �� ���� � ������� 5 �����. ���� ����� ����� ������������� �� '+datereserve);
		
		var str = '';
		str = 'bascetshop' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz77.gif border=0 alt="�� � ������� �� ������">';
	}
	else if (errorvalue == 'reserved')
	{
		alert ('����� �������������� ������������ � ������ �������������');

		var str = '';
		str = 'bascetshop' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz6.gif border=0 alt="��� � �������">';
		
	}
	else if (errorvalue == 'notavailable')
	{
		
		alert ('����� ��� ������');
		
		var str = '';
		str = 'bascetshop' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz6.gif border=0 alt="��� � �������">';

	}
	else if (errorvalue == 'stopsummax')
	{
		alert ('������������ ����� ������ <? echo $stopsummax;?> ���. \n���� �� �� ��� ������� � �������, ���������� ��������� �������."');
	}
	
}

function ShowBascet2() {

	var divstr = "";
	divstr = "showbascet2";
	myDiv = document.getElementById(divstr);
	if (myDiv.style.display=="none")
		myDiv.style.display="block";
	else
		myDiv.style.display="none";
}

function ShowSmallBascet (xmlRoot)
{
	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	var bascetshopcoins = '';	
	bascetshopcoins = xmlRoot.getElementsByTagName("bascetshopcoins").item(0).firstChild.data;	
	
	if (errorvalue == 'none')
	{
		var shopcoinsorder = '';
		var bascetamount = '';
		var bascetsum = '';
		var bascetweight = '';
		var bascetreservetime = '';
		var bascetpostweightmin = '';
		var bascetpostweightmax = '';
		var bascetinsurance = '';
		var textbascet2 = '';
		
		shopcoinsorder = xmlRoot.getElementsByTagName("shopcoinsorder").item(0).firstChild.data;
		bascetamount = xmlRoot.getElementsByTagName("bascetamount").item(0).firstChild.data;
		bascetsum = xmlRoot.getElementsByTagName("bascetsum").item(0).firstChild.data;
		bascetsumclient = xmlRoot.getElementsByTagName("bascetsumclient").item(0).firstChild.data;
		bascetweight = xmlRoot.getElementsByTagName("bascetweight").item(0).firstChild.data;
		bascetreservetime = xmlRoot.getElementsByTagName("bascetreservetime").item(0).firstChild.data;
		bascetpostweightmin = xmlRoot.getElementsByTagName("bascetpostweightmin").item(0).firstChild.data;
		bascetpostweightmax = xmlRoot.getElementsByTagName("bascetpostweightmax").item(0).firstChild.data;
		bascetinsurance = xmlRoot.getElementsByTagName("bascetinsurance").item(0).firstChild.data;
		textbascet2 = xmlRoot.getElementsByTagName("textbascet2").item(0).firstChild.data;
		//alert(textbascet2);
		
		myDiv = document.getElementById("inorderamount");
		myDiv.innerHTML = "� ����� ������� <a href=<?=$in?>shopcoins/index.php?page=orderdetails> "+bascetamount+" </a> �������, <a href=<?=$in?>shopcoins/index.php?page=orderdetails><img src=<?=$in?>images/basket.gif border=0></a>";
		
		var str = '';
		str = '<table border=0 cellpadding=3 cellspacing=0 style="border:thin solid 1px #000000" id=tableshopcoinsorder width=180>';
		str += '<tr class=tboard bgcolor=#006699><td><strong><font color=white>�������:</font></strong></td></tr>';
		str += '<tr class=tboard bgcolor=#ffcc66><td class=tboard align=top><strong>����� �</strong> ' + shopcoinsorder + ' ';
		str += '<br><strong>�������:</strong> ' + bascetamount + ' <br><strong>�� �����:</strong> ' + bascetsum + ' �.';
		if (bascetsumclient>0) {
			str += '<br><strong>��� ���������� ��������:</strong> ' + bascetsumclient + ' �.';
		}
		str += '<br><strong>��� ~ </strong> ' + bascetweight + ' ��. <br><strong>����� ��:</strong> ' + bascetreservetime + '<br><center><a href=index.php?page=orderdetails><img src=<?echo $in;?>images/basket.gif border=0></a></center></td></tr>';
		str += '<tr class=tboard bgcolor=#006699><td><strong><font color=white>��������:</font></strong></td></tr>';
		str += '<tr class=tboard bgcolor=#ffcc66><td class=tboard align=top><strong>������:</strong><br><strong>��������� �������:</strong> ���������<br><strong>� ����:</strong> �� 170 �.';
		str += '<br><br><strong>����� ������</strong><br><strong>���� �� ����:</strong> �� ' + bascetpostweightmin + ' �� ' + bascetpostweightmax + ' �.';
		str += '<br><strong>��������� 4%:</strong> ' + bascetinsurance + ' �. <br><strong>��������:</strong> 10 �. �� ������� / ����.</td></tr>';
		str +='<tr class=tboard bgcolor=#ffcc66><td><div style="display:none" id=showbascet2>'+textbascet2+'</div></td></tr>'
		str += '<tr class=tboard bgcolor=#EBE4D4><td align=center><img src=../images/windowsmaximize.gif onclick="ShowBascet2();" alt="���������� ����������"/></td></tr></table>';
		
		myDiv = document.getElementById("MainBascet");
		myDiv.innerHTML = str;
		//dHbcnn=document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight
		dWbcnn=document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth
		myDiv.style.position = "absolute";
		myDiv.style.left = dWbcnn - 220;
		myDiv.style.top = document.body.scrollTop;
		
		var str = '';
		str = 'bascetshopcoins' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz7.gif border=0 alt="��� � �������">';
		
	}
	else if (errorvalue == 'reserved')
	{
		alert ('����� �������������� ������������ � ������ �������������');

		var str = '';
		str = 'bascetshopcoins' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz6.gif border=0 alt="��� � �������">';
		
	}
	else if (errorvalue == 'notavailable')
	{
		
		alert ('����� ��� ������');
		
		var str = '';
		str = 'bascetshopcoins' + bascetshopcoins;
		myDiv = document.getElementById(str);
		myDiv.innerHTML = '<img src=<? echo $in; ?>images/corz6.gif border=0 alt="��� � �������">';

	}
	else if (errorvalue == 'stopsummax')
	{
		alert ('������������ ����� ������ <? echo $stopsummax;?> ���. \n���� �� �� ��� ������� � �������, ���������� ��������� �������."');
	}
	else if (errorvalue == 'amount')
	{
		erroramount = xmlRoot.getElementsByTagName("erroramount");
		erroramountvalue = erroramount.item(0).firstChild.data;
		alert ('�� ������ ����� ���� ' + erroramountvalue + ' ����');
	}
		<?

?>
}

function ShowSmallBascet__12 (xmlRoot)
{
	error = xmlRoot.getElementsByTagName("error");
	errorvalue = error.item(0).firstChild.data;
	var bascetshopcoins = '';	
	bascetshopcoins = xmlRoot.getElementsByTagName("bascetshopcoins").item(0).firstChild.data;	
	
	if (errorvalue == 'none')
	{
		var shopcoinsorder = '';
		var bascetamount = '';
		var bascetsum = '';
		var bascetweight = '';
		var bascetreservetime = '';
		var bascetpostweightmin = '';
		var bascetpostweightmax = '';
		var bascetinsurance = '';
		var textbascet2 = '';
		
		shopcoinsorder = xmlRoot.getElementsByTagName("shopcoinsorder").item(0).firstChild.data;
		bascetamount = xmlRoot.getElementsByTagName("bascetamount").item(0).firstChild.data;
		bascetsum = xmlRoot.getElementsByTagName("bascetsum").item(0).firstChild.data;
		bascetsumclient = xmlRoot.getElementsByTagName("bascetsumclient").item(0).firstChild.data;
		bascetweight = xmlRoot.getElementsByTagName("bascetweight").item(0).firstChild.data;
		bascetreservetime = xmlRoot.getElementsByTagName("bascetreservetime").item(0).firstChild.data;
		bascetpostweightmin = xmlRoot.getElementsByTagName("bascetpostweightmin").item(0).firstChild.data;
		bascetpostweightmax = xmlRoot.getElementsByTagName("bascetpostweightmax").item(0).firstChild.data;
		bascetinsurance = xmlRoot.getElementsByTagName("bascetinsurance").item(0).firstChild.data;
		textbascet2 = xmlRoot.getElementsByTagName("textbascet2").item(0).firstChild.data;
		//alert(textbascet2);
		
		myDiv = document.getElementById("inorderamount");
		myDiv.innerHTML = "� ����� ������� <a href=<?=$in?>shopcoins/index.php?page=orderdetails> "+bascetamount+" </a> �������, <a href=<?=$in?>shopcoins/index.php?page=orderdetails><img src=<?=$in?>images/basket.gif border=0></a>";
		
		var str = '';
		str = '<table border=0 cellpadding=3 cellspacing=0 style="border:thin solid 1px #000000" id=tableshopcoinsorder width=180>';
		str += '<tr class=tboard bgcolor=#006699><td><strong><font color=white>�������:</font></strong></td></tr>';
		str += '<tr class=tboard bgcolor=#ffcc66><td class=tboard align=top><strong>����� �</strong> ' + shopcoinsorder + ' ';
		str += '<br><strong>�������:</strong> ' + bascetamount + ' <br><strong>�� �����:</strong> ' + bascetsum + ' �.';
		if (bascetsumclient>0) {
			str += '<br><strong>��� ���������� ��������:</strong> ' + bascetsumclient + ' �.';
		}
		str += '<br><strong>��� ~ </strong> ' + bascetweight + ' ��. <br><strong>����� ��:</strong> ' + bascetreservetime + '<br><center><a href=index.php?page=orderdetails><img src=<?echo $in;?>images/basket.gif border=0></a></center></td></tr>';
		str += '<tr class=tboard bgcolor=#006699><td><strong><font color=white>��������:</font></strong></td></tr>';
		str += '<tr class=tboard bgcolor=#ffcc66><td class=tboard align=top><strong>������:</strong><br><strong>��������� �������:</strong> ���������<br><strong>� ����:</strong> �� 170 �.';
		str += '<br><br><strong>����� ������</strong><br><strong>���� �� ����:</strong> �� ' + bascetpostweightmin + ' �� ' + bascetpostweightmax + ' �.';
		str += '<br><strong>��������� 4%:</strong> ' + bascetinsurance + ' �. <br><strong>��������:</strong> 10 �. �� ������� / ����.</td></tr>';
		str +='<tr class=tboard bgcolor=#ffcc66><td><div style="display:none" id=showbascet2>'+textbascet2+'</div></td></tr>'
		str += '<tr class=tboard bgcolor=#EBE4D4><td align=center><img src=../images/windowsmaximize.gif onclick="ShowBascet2();" alt="���������� ����������"/></td></tr></table>';


		
		myDiv = document.getElementById("MainBascet");
		myDiv.innerHTML = str;
		//dHbcnn=document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight
		dWbcnn=document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth
		myDiv.style.position = "absolute";
		myDiv.style.left = dWbcnn - 220;
		myDiv.style.top = document.body.scrollTop;
		

	}
	else if (errorvalue == 'reserved')
	{
		alert ('����� �������������� ������������ � ������ �������������');

		
	}
	else if (errorvalue == 'notavailable')
	{
		
		alert ('����� ��� ������');
		
	

	}
	else if (errorvalue == 'stopsummax')
	{
		alert ('������������ ����� ������ <? echo $stopsummax;?> ���. \n���� �� �� ��� ������� � �������, ���������� ��������� �������."');
	}
	else if (errorvalue == 'amount')
	{
		erroramount = xmlRoot.getElementsByTagName("erroramount");
		erroramountvalue = erroramount.item(0).firstChild.data;
		alert ('�� ������ ����� ���� ' + erroramountvalue + ' ����');
	}
}
</script>


<?
?>