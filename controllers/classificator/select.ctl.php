<?
header("Content-type: text/xml; charset=utf-8");
$xml = "<?xml version=\"1.0\" encoding=\"windows-1251\" standalone=\"yes\"?>
<response>
<scripteval>showClassificator</scripteval>";


//возвращает количество страниц, первых 8 штук для показа, и таги для Main

/*xml
<pagecount>Количество гербов</pagecount>
<TagSelect> - удовлетворящие запросам
<TagId>
<TagName>
...
</TagSelect>

<classificator>
	<ClassificatorID>
	<Image>
	<details>
	<Tags>
	<id>
	<name>
	...
	</Tags>
	<country>
	<id>
	<name>
	..
	</country>
</classificator>
*/

$Tag = request("Tag");
$pagenum = (int)request("pagenum");
$TagArray = explode("*", $Tag);
array_pop($TagArray);

//$pagecount = 8;
$where = "where `check`='1' ";
$orderby = "";

if (sizeof($TagArray)){
	foreach ($TagArray as $key=>$value)
		$whereArray[] = " tag".$value." = '1' ";
	
	if (sizeof($whereArray)>0)
		$where .= "and (".implode(" and ", $whereArray).") ";
}

$sql = "select * from classificatortag order by name;";
$result = $shopcoins_class->getDataSql($sql);
foreach ($result as $rows){
	$tagArray[] = $rows["classificatortag"];
	$tagNameArray[$rows["classificatortag"]] = $rows["name"];
}


//показ всех тагов
$sql = "desc classificator;";
$result = $shopcoins_class->getDataSql($sql);
foreach ($result as $rows){
	if (strpos($rows["Field"], "tag") !== false){
		$sumTag[] = $rows["Field"];
		$TagSumStr[] = "sum(".$rows["Field"].") as Sum".$rows["Field"];
	}
}

if (sizeof($sumTag)>0)
{
	$sql = "select ".implode(",", $TagSumStr)." from classificator $where;";
	$TagF = $shopcoins_class->getRowSql($sql);
	
	$TagFA = array();
	foreach ($sumTag as $key=>$value){		
		if ($TagF["Sum".$value]!=0)	{
			if (!in_array(str_replace("tag","",$value), $TagArray))	{
				$TagFA[] = $TagF["Sum".$value];
				$LastTag[] = str_replace("tag","",$value);
			}
		}
	}
	
	if (sizeof($TagFA))	{
		rsort($TagFA);
		
		$FontTag = Array();
		$i = 0;
		foreach ($TagFA as $key=>$value){
			$FontTag[$value] = $i;
			$i++;
		}
		
		$maxT = $TagFA[0];
		$minT = $TagFA[sizeof($TagFA)-1];
		
		if ($maxT != $minT)
			$step = 150/($maxT-$minT);
		else
			$step = 0;
	}
}

//выборка количества
$sql = "select count(*) from classificator $where ";
$countpubs=$shopcoins_class->getOneSql($sql);

$onpage=16;
$numpages=5;
$pagenum=round($pagenum);
$pages=ceil($countpubs/$onpage);if (!$pages) $pages=1;
if ($pagenum<1) $pagenum=1; if ($pagenum>$pages) $pagenum=$pages;
$i=1;

$frompage=floor(($pagenum-1)/$numpages)*$numpages+1;
$topage=ceil($pagenum/$numpages)*$numpages;if ($topage>$pages) $topage=$pages;

$xmlpagecount .= "
<pagenum>".($pagenum>0?$pagenum:1)."</pagenum>
<onpage>".($onpage>0?$onpage:0)."</onpage>
<numpages>".($numpages>0?$numpages:0)."</numpages>
<frompage>".($frompage>0?$frompage:0)."</frompage>
<topage>".($topage>0?$topage:0)."</topage>
<pages>".($pages>0?$pages:0)."</pages>
";


//выборка гербов, которые остяються

//выборка всех гербов, удовлетворяющие запросу
if (sizeof($sumTag)>0)	$orderby = " order by TGsum ";

$sql = "select * ".(sizeof($sumTag)>0?", (".implode("+",$sumTag).") as TGsum":"")." from classificator 
$where $orderby limit ".($pagenum-1)*$onpage.",$onpage; ";
//echo $sql;
$xmlshopcoinsgroup = Array();
$result = $shopcoins_class->getDataSql($sql);
foreach ($result as $rows){
	$xmlclassificator .= "<classificator>
	<image>".($rows["image_small"]?$rows["image_small"]:"empty")."</image>
	<details>".($rows["details"]?$rows["details"]:"empty")."</details>";
	
	$sql_group = "select `group`.`group`, name from classificatorgroup, `group` 
	where classificator='".$rows["classificator"]."' and classificatorgroup.`group`=`group`.`group`
	order by name;";
	$result_group = $shopcoins_class->getDataSql($sql_group);
	$countryamount = 0;
	if ($result_group){
		$xmlclassificator .= "<country>\n";
		foreach ($result_group as $rows_group){
			$xmlshopcoinsgroup[] = $rows_group["group"];
			$xmlclassificator .= "<countryid>".$rows_group["group"]."</countryid>
			<name>".($rows_group["name"]?$rows_group["name"]:"empty")."</name>\n";
			$countryamount++;
		}
		$xmlclassificator .= "</country>\n";
		$xmlclassificator .= "<countryamount>$countryamount</countryamount>\n";
	}
	
	$tagTxt = Array();
	foreach ($sumTag as $key=>$value) {
		if ($rows[$value]==1){
			$myTag = str_replace("tag","", $value);
			$tagTxt[] = $myTag;		
		}
	}
	
	if (sizeof($tagTxt)>0){
		$k = 0;
		foreach ($tagTxt as $key=>$value) {
			$xmlclassificator .= "<TagId>".$value."</TagId>\n";
			$k++; 
		}
		
		$xmlclassificator .= "<TagsAmount>$k</TagsAmount>\n";
	}
	
	$xmlclassificator .= "</classificator>\n";
	$pagecount++;
}
$xmlpagecount .= "<pagecount>".($pagecount>0?$pagecount:"empty")."</pagecount>\n";


if (sizeof($LastTag))
{
	//нужно еще проделать fontsize
	$sql = "select * from classificatortag where classificatortag in (".implode(",", $LastTag).") order by name;";

	$result = $shopcoins_class->getDataSql($sql);
	foreach ($result AS $rows){
		$xmlTagSelect .= "<TagSelect>".$rows["classificatortag"]."</TagSelect>\n
		<FontSelect>".(100+($TagF["Sumtag".$rows["classificatortag"]]-$minT)*$step)."</FontSelect>";
		$TagSelectAmount++;
	}
} else {
	$xmlTagSelect .= "<TagSelect>0</TagSelect>\n";
}
$xmlTagSelect .= "<TagSelectAmount>".($TagSelectAmount?$TagSelectAmount:0)."</TagSelectAmount>\n";

//показ ссылок на монеты в каталоге и монетной лавке
if (sizeof($xmlshopcoinsgroup)>0){
	$sql = "select distinct `group` from shopcoins where `check`='1' and `group` in (".implode(",", $xmlshopcoinsgroup).") and materialtype=1;";
	$result = $shopcoins_class->getDataSql($sql);
	foreach ($result AS $rows){
		$xmlshopcoinsgroupcheck[$rows["group"]] = 1;
	}
	
	foreach ($xmlshopcoinsgroup as $key=>$value){
		$xmlshopcoinsGroup .= "<shopcoinsgroup>".$value."</shopcoinsgroup>\n
		<shopcoinsgroupcheck>".($xmlshopcoinsgroupcheck[$value]==1?1:0)."</shopcoinsgroupcheck>";
		$shopcoinsgroupAmount++;
	}
	$xmlshopcoinsGroup .= "<shopcoinsgroupamount>".$shopcoinsgroupAmount."</shopcoinsgroupamount>";
} else {
	$xmlshopcoinsGroup .= "<shopcoinsgroupamount>0</shopcoinsgroupamount>";
}

$xml .= $xmlpagecount.$xmlTagSelect.$xmlclassificator.$xmlshopcoinsGroup."</response>";
echo $xml;
die();
?>