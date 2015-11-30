<?
$page_string = "";
if ($searchid) {

	$where = " where 
	(".($cookiesuser==811||$cookiesuser==309236?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") 
	".(sizeof($WhereArray)?" and ".implode(" and ", $WhereArray):"");
} elseif ($search) {

	$where = " where 
	((".($cookiesuser==811||$cookiesuser==309236?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") ".($show50?"or shopcoins.check=50":"").") and ((shopcoins.materialtype in (2,4,7,8,3,5,9)) or (shopcoins.materialtype in(1,10) and shopcoins.amountparent>0) or shopcoins.number='$search' or shopcoins.number2='$search') 
	".(sizeof($WhereArray)?" and ".implode(" and ", $WhereArray):"");
} else {
	$where = " where 
	(".($cookiesuser==811||$cookiesuser==309236?(!$nocheck?"shopcoins.check=1 or (shopcoins.check>3 and shopcoins.check<20)":"(shopcoins.check>3 and shopcoins.check<20)"):"shopcoins.check=1").") 
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

//названия монет в группе



if ($searchid)
	$MainText .= "<p class=txt><b><font color=red>Уважаемый пользователь. Включена система расширенного поиска.</font></b>
	<br>Чтобы отключить расширенный поиск - нажмите <a href=$script>здесь</a>.</p>";

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
	//счетчик
	
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
	
	if ($pagenum>2*$numpages) $page_string .= "<a href='$script?pagenum=1".$addhref.($sortname?"&sortname=1":"").($yearsearch?"&yearsearch=$yearsearch":"")."'>[в начало]</a> | ";
	if ($frompage>$numpages) $page_string .= "<a href='$script?pagenum=".($frompage-1).$addhref.($sortname?"&sortname=1":"").($yearsearch?"&yearsearch=$yearsearch":"")."'><<пред</a> | ";
	for ($i=$frompage;$i<=$topage;$i++)
		{
		if ($i==$pagenum) $page_string .= "<b>$i</b>";
		else $page_string .= "<a href='$script?pagenum=$i".$addhref.($sortname?"&sortname=1":"").($yearsearch?"&yearsearch=$yearsearch":"")."'>$i</a>";
		if ($i<$topage) $page_string .= " | ";
		}      
	if ($pages>$topage) $page_string .= " | <a href='$script?pagenum=$i".$addhref.($sortname?"&sortname=1":"").($yearsearch?"&yearsearch=$yearsearch":"")."'>далее>></a>";
	if($materialtype == 12){
			require_once('build_table/build_table.php');
			$MainText .= $table_data;
	}
	
	$MainText .= "<p class=txt><b>Страницы: </b>".$page_string."</p>";
}

$addhref .= ($pagenum?"&pagenum=".$pagenum:"");

//сортировка
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

?>