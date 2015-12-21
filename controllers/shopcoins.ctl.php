<?
require($cfg['path'].'/helpers/Paginator.php');
require $cfg['path'] . '/configs/config_shopcoins.php';
require($cfg['path'].'/controllers/filters.ctl.php');

$tpl['shop']['errors'] = array();
$search = request('search');

$sortname = request('sortname');

$tpl['pager']['sorts'] = array('dateinsert'=>'новизне',                     
                      'price'=>'по цене',
                      'year'=>'году');
$tpl['pager']['itemsOnpage'] = array(9=>9,16=>16,36=>36,32=>32,'all'=>'Все');

//сохраняем количество элементов на странице в куке
if(request('onpage')){
    $tpl['onpage'] = request('onpage');
} elseif (isset($_COOKIE['onpage'])){
    $tpl['onpage'] =$_COOKIE['onpage'];
}	
if(!isset($tpl['onpage']))	$tpl['onpage'] = 9;
setcookie('onpage', $tpl['onpage'],  time()+ 86400 * 90,'/',$cfg['domain']);

//сохраняем сортировку элементов на странице в куке
if(request('orderby')){
    $tpl['orderby'] = request('orderby');
} elseif (isset($_COOKIE['orderby'])){
    $tpl['orderby'] =$_COOKIE['orderby'];
}	
if(!isset($tpl['orderby']))	$tpl['orderby'] = "dateinsertdesc";
setcookie('orderby', $tpl['orderby'],  time()+ 86400 * 90,'/',$cfg['domain']);

$tpl['pagenum'] = request('pagenum')?request('pagenum'):1;

//array(14) {["condition"]=> string(3) "VF-" ["orderby"]=> string(14) "dateinsertdesc" ["onpage"]=> string(2) "32" ["pagenum"]=> string(1) "1" ["pricestart"]=> string(4) "6709" ["priceend"]=> string(5) "71943" ["conditions"]=> array(2) { [0]=> string(3) "XF-" [1]=> string(3) "VF-" } ["metals"]=> array(1) { [0]=> string(12) "Фарфор" } ["years"]=> array(2) { [0]=> string(1) "1" [1]=> string(1) "2" } ["themes"]=> array(1) { [0]=> string(1) "4" } ["groups"]=> array(1) { [0]=> string(3) "518" } } 



$theme_data = array();
$metal_data = array();
$year_data = array();
$group_data = array();
$condition_data  = array(); 
$materialtype = request('materialtype')?request('materialtype'):1;
$pricestart =request('pricestart');
$priceend =request('priceend');
//если границы цены дефолтные то убираем их из выборки78
if($priceend==$tpl['filter']['price']['max']||$priceend<=0){
	$priceend='';
}

$yearstart  =request('yearstart');
$yearend  =request('yearend');

if($yearend==date('Y',time())){
	$yearend='';
}
	//var_dump($_REQUEST);
//var_dump($pricestart,$priceend);
$searchname = request('searchname');
$coinssearch = request('coinssearch');


$years  =request('years');
//формируем массив интервалов
if($yearstart||$yearend){
	$years_data[] = array($yearstart,$yearend);
}
foreach ($years as $val){
	$years_data[] = $yearsArray[$val]['data'];
}


$theme  =request('theme');
$themes  =request('theme');
$metal  = iconv("cp1251",'utf8',request('metal'));
$metals  =request('metal');
$group = request('group');
$groups = request('group');
//это старые данные в виде строки
$condition = iconv("cp1251",'utf8',request('condition'));
//это новые данные в виде массива
$conditions = request('conditions');
//на случай если парамет передали из прямой ссылки надо поддержать и такой формат
if ($groups) $group_data =$groups;
elseif($group) $group_data =  array($group);

if ($metals) $metal_data =$metals;
elseif($metal) $metal_data =  array($metal);

if ($conditions) $condition_data =$conditions;
elseif($condition) $condition_data =  array($condition);

if ($themes) $theme_data =$themes;
elseif($theme) $theme_data =  array($theme);
if ($themes) $theme_data =$themes;
elseif($theme) $theme_data =  array($theme);

if($yearstart||$yearend||$years){
	echo "<b>c годами надо особо поизвращаться!!</b>";
}
$checkuser = 0;
$CounterSQL = "";

$WhereParams = Array();

$page_string = "";

$mycoins = 0;
$ourcoinsorder = Array();
$ourcoinsorderamount = Array();

$shopcoinsorder = 0;
if (isset($_COOKIE['shopcoinsorder'])&&intval($_COOKIE['shopcoinsorder'])>0)
	$shopcoinsorder = intval($_COOKIE['shopcoinsorder']);
elseif(isset($_SESSION['shopcoinsorder'])&&intval($_SESSION['shopcoinsorder'])>0)
	$shopcoinsorder = intval($_SESSION['shopcoinsorder']);
elseif (intval(request('shopcoinsorder'))>0)
	$shopcoinsorder = intval(request('shopcoinsorder'));


if($pricestart>0) $WhereParams['pricestart'] = $pricestart;
if($priceend>0) $WhereParams['priceend'] = $priceend;
if($theme_data) $WhereParams['theme'] = $theme_data;
if($metal_data) $WhereParams['metal'] = $metal_data;
if($years_data) $WhereParams['year'] = $years_data;
if($condition_data) $WhereParams['condition'] = $condition_data;
if($group_data) $WhereParams['group'] = $group_data;
if($coinssearch) $WhereParams['coinssearch'] = $coinssearch;


if($searchname) {
    //так как ссылки были вида cp1251
    $WhereParams['searchname'] = str_replace("'","",iconv("cp1251",'utf8',$searchname));
}

$dateinsert_orderby = "dateinsert";

//end - потом не забыть подключить
$addhref = //($yearstart?"&yearstart=".$yearstart:"").
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
($nocheck?"&nocheck=".$nocheck:"")
.($searchname?"&searchname=".urlencode($searchname):"");

if ($searchid)
	$MainText .= "<p class=txt><b><font color=red>��������� ������������. �������� ������� ������������ ������.</font></b>
	<br>����� ��������� ����������� ����� - ������� <a href=$script>�����</a>.</p>";



$countpubs = $shopcoins_class->countallByParams($materialtype,$WhereParams,$yearsearch);

if($addhref) $addhref = substr($addhref,1);  
$tpl['paginator'] = new Paginator(array(
        'url'        => $cfg['site_dir']."shopcoins/index.php?".$addhref,
        'count'      => $countpubs,
        'per_page'   => $tpl['onpage'],
        'page'       => $pagenum,
        'border'     =>4));
    
if (!$page){  
	if($materialtype == 12){
		require_once('build_table/build_table.php');
		$MainText .= $table_data;
	}
}

$OrderByArray = Array();

/*if ($coinssearch)
	$OrderByArray[] = " shopcoins.shopcoins=".intval($coinssearch)." desc ";*/

if (isset($CounterSQL)&&$CounterSQL)
	if (sizeof($WhereThemesearch) || sizeof($SearchTempDigit))
		$OrderByArray[] = " (coefficientcoins+counterthemeyear+coefficientgroup) desc, counterthemeyear desc, (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc  ";
	else
		$OrderByArray[] = " (coefficientcoins+coefficientgroup) desc, coefficientgroup desc, coefficientcoins desc ";


if ($search === 'revaluation') {
	$OrderByArray[] = " shopcoins.datereprice desc, shopcoins.price desc, shopcoins.".$dateinsert_orderby." desc";
}

//if ($group)	$OrderByArray[] = " ABS(shopcoins.group-".$group.") ";

if (($materialtype==3||$materialtype==5) and $group) $OrderByArray[] = " shopcoins.name desc";

if ($materialtype==5) $OrderByArray[] = " shopcoins.name desc";

	
if ($tpl['orderby']=="dateinsertdesc"){
	$OrderByArray[] = " if (shopcoins.dateupdate > shopcoins.dateinsert, shopcoins.dateupdate, shopcoins.dateinsert) desc";
	$OrderByArray[] = "shopcoins.dateinsert desc";
	$OrderByArray[] = "shopcoins.price desc";
} elseif ($tpl['orderby']=="dateinsertasc"){
	$OrderByArray[] = " shopcoins.dateinsert asc";
	$OrderByArray[] = "shopcoins.price desc";
} elseif ($tpl['orderby']=="priceasc"){
	$OrderByArray[] = "shopcoins.price asc";
	$OrderByArray[] = "shopcoins.dateinsert desc ";
} elseif ($tpl['orderby']=="pricedesc"){
	$OrderByArray[] = "shopcoins.price desc";
	$OrderByArray[] = "shopcoins.dateinsert desc";
} elseif ($tpl['orderby']=="yearasc"){
	$OrderByArray[] = "shopcoins.year asc";
	$OrderByArray[] ="shopcoins.dateinsert desc";
} elseif ($tpl['orderby']=="yeardesc"){
	$OrderByArray[] = "shopcoins.year desc";
	$OrderByArray[] = "shopcoins.dateinsert desc ";
} elseif($materialtype==12){
	$OrderByArray[] = "shopcoins.year desc";
	$OrderByArray[] = "shopcoins.name desc ";
} elseif ($materialtype==1||$materialtype==2||$materialtype==10||$materialtype==4||$materialtype==7||$materialtype==8||$materialtype==6||$materialtype==11||$search=='newcoins'){
	$OrderByArray[] = $dateinsert_orderby." desc";
	$OrderByArray[] = "shopcoins.price desc";
}

if (sizeof($OrderByArray))
	$orderby = array_merge(array("shopcoins.check ASC"),$OrderByArray);


	
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
} elseif ($page == "recommendation" && $tpl['user']['user_id']) {

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

	$sql = "select shopcoins.*, group.name as gname, group.groupparent ".($CounterSQL?",".$CounterSQL:"")." ".($group>0&&!$page?$groupselect:"")."
	from shopcoins, `group` 
	$where ".$positive_amount."and shopcoins.group=group.group  
	".($group>0&&!$page?($sortname?" order by ".($coinssearch?"shopcoins.shopcoins=".intval($coinssearch)." desc,":"")." groupparent asc,param2,param1,".$dateinsert_orderby." desc":" order by ".($coinssearch?"shopcoins.shopcoins=".intval($coinssearch)." desc,":"")." groupparent asc,".$dateinsert_orderby." desc,price desc, param2,param1"):$orderby)." 
	$limit;";
	echo $sql;*/
	$data = $shopcoins_class->getItemsByParams($materialtype,$WhereParams,$yearsearch,$tpl['pagenum'],$tpl['onpage'],$orderby);
	
}


//$result_search = mysql_query($sql);
$ArrayParent = Array();
$tpl['shop']['MyShowArray'] = Array();

foreach ($data as $rows){
	$tpl['shop']['ArrayShopcoins'][] = $rows["shopcoins"];
	$tpl['shop']['ArrayParent'][] = $rows["parent"];
	$tpl['shop']['MyShowArray'][] = $rows;
}

if (sizeof($tpl['shop']['ArrayParent'])) {
    $result_search = $shopcoins_class->getCoinsParents($tpl['shop']['ArrayParent']);
	foreach ($result_search as $rows_search ){		
		$tpl['shop']['ImageParent'][$rows_search["parent"]][] = $rows_search["image_small"];
	}	
}

if ($materialtype==3 || $materialtype==5) {
	if ($shopcoinsorder > 1) {
		$sql_ourorder = "select * from orderdetails where `order`='$shopcoinsorder' and date > ".(time() - $reservetime).";";
		//echo $sql_ourorder;
		$result_ourorder = mysql_query($sql_ourorder);
		die('$shopcoinsorder');
		
	
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

$tpl['task'] = 'catalog_base';	

if (sizeof($tpl['shop']['MyShowArray'])==0){
	$tpl['shop']['errors'][] = "<br><p class=txt><strong><font color=red>Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</font></strong><br><br>";
} else {	
	$amountsearch = count($tpl['shop']['MyShowArray']);	
	
	foreach ($tpl['shop']['MyShowArray'] as $i=>$rows) {
		//формируем картинки "подобные"
		$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'] = array();
		if (isset($tpl['shop']['ImageParent'][$rows["parent"]])&&$tpl['shop']['ImageParent'][$rows["parent"]]>0 && !$mycoins) {	
			$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'][] =contentHelper::showImage("smallimages/".$rows["image_small"],"Монета ".$rows["gname"]." | ".$rows["name"]);
			$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'][] =contentHelper::showImage("smallimages/".$tpl['shop']['ImageParent'][$rows["parent"]][0],"Монета ".$rows["gname"]." | ".$rows["name"]);
		}

		$mtype = ($materialtype>0?$materialtype:$rows['materialtype']);
		$MyShowArray[$i]['mtype'] = $mtype;	
		$rehref = "";
		if ($materialtype==5||$materialtype==3){
			if ($mtype==1) $rehref = "Монета-";
			if ($mtype==8) $rehref = "Монета-";
			if ($mtype==7) $rehref = "Набор-монет-";
			if ($mtype==2) $rehref = "Банкнота-";
			if ($mtype==4) $rehref = "Набор-монет-";
			if ($mtype==5) $rehref = "книга-";
			if ($mtype==9) $rehref = "Лот монет ";
			
			if ($rows['gname'])
				$rehref .= $rows['gname']."-";
			$rehref .= $rows['name'];
			if ($rows['metal'])
				$rehref .= "-".$rows['metal']; 
			if ($rows['year'])
				$rehref .= "-".$rows['year'];
			$namecoins = $rehref;
			$rehrefdubdle = contentHelper::strtolower_ru($rehref)."_c".($mtype==1?$rows['parent']:$rows['shopcoins'])."_pc".($parent>0?$parent:(($mtype==7 || $mtype==8 || $mtype==6 || $mtype==4 || $mtype==2) && $rows["amount"]>1?$rows['shopcoins']:($mtype==1?$rows['parent']:0)))."_m".$rows['materialtype']."_pp1.html";
			$rehref = contentHelper::strtolower_ru($rehref)."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";	
			$tpl['shop']['MyShowArray'][$i]['amountall'] = ( !$rows["amount"])?1:$rows["amount"];		
		} else {
			if ($mtype==1) $rehref = "Монета ";
			if ($mtype==8) $rehref = "Монета ";
			if ($mtype==7) $rehref = "Набор монет ";
			if ($mtype==2) $rehref = "Банкнота ";
			if ($mtype==4) $rehref = "Набор монет ";
			if ($mtype==5) $rehref = "Книга ";
			if ($mtype==9)	$rehref = "Лот монет ";
			if ($mtype==10)	$rehref = "Нотгельд ";
			if ($mtype==11)	$rehref = "Монета ";	

			
			if (($rows['materialtype']==2 || $rows['materialtype']==4 || $rows['materialtype']==7 || $rows['materialtype']==8 || $rows['materialtype']==6) && $rows['amount']>10) 
				$rows['amount'] = 10;
			
		    $amountall = $rows['amount'];
			if ($rows["materialtype"]==8 || $rows["materialtype"]==6 || $rows["materialtype"]==7 || $rows["materialtype"]==2 || $rows["materialtype"]==4) {		
				$amountall = ( !$rows["amount"])?1:$rows["amount"];				
			}
			
			$tpl['shop']['MyShowArray'][$i]['amountall'] = $amountall;
				
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
			$rehrefdubdle = contentHelper::strtolower_ru($rehref)."_c".(($mtype==1 || $mtype==10 || $mtype==12)?$rows['parent']:$rows['shopcoins'])."_pc".($parent>0?$parent:(($mtype==7 || $mtype==8 || $mtype==6 || $mtype==4 || $mtype==2) && $rows["amount"]>1?$rows['shopcoins']:(($mtype==1 || $mtype==10)?$rows['parent']:0)))."_m".$rows['materialtype']."_pp1.html";
			$rehref = contentHelper::strtolower_ru($rehref)."_c".$rows['shopcoins']."_m".$rows['materialtype'].".html";		
		} 
		
		$tpl['shop']['MyShowArray'][$i]['namecoins'] = $namecoins;
		$tpl['shop']['MyShowArray'][$i]['rehrefdubdle'] = $rehrefdubdle ;
		$tpl['shop']['MyShowArray'][$i]['rehref'] = $rehref ;	
		
		
		$coefficient = 0;
		$sumcoefficient = 0 ;
		$maxcoefficient = 0 ;
		if(!isset($rows['coefficientcoins'])) $rows['coefficientcoins'] = 0;
		if(!isset($rows['coefficientgroup'])) $rows['coefficientgroup'] = 0;
		if(!isset($rows['counterthemeyear'])) $rows['counterthemeyear'] = 0;
		if ($rows['coefficientcoins']) 
			$coefficient = $coefficient+$rows['coefficientcoins'];
		if ($rows['coefficientgroup']) 
			$coefficient = $coefficient+$rows['coefficientgroup']*2;
		if ($rows['counterthemeyear']) 
			$coefficient = $coefficient+$rows['counterthemeyear'];
			
		if ($coefficient>0) {		
			if ($coefficient>$maxcoefficient)
				$maxcoefficient = $coefficient;			
			$sumcoefficient = $sumcoefficient+$coefficient;
		}
				
			
		$tpl['shop']['MyShowArray'][$i]['coefficient'] = 0;
		$tpl['shop']['MyShowArray'][$i]['sumcoefficient'] = 0 ;
		$tpl['shop']['MyShowArray'][$i]['maxcoefficient'] = 0 ;	
		$tpl['shop']['MyShowArray'][$i]['buy_status'] = 0 ;		
		$textoneclick='';	
		
		if(!$mycoins) {			
			$tpl['shop']['MyShowArray'][$i]['reservedForSomeUser'] = $reservedForSomeUser = (1>2);
			$tpl['shop']['MyShowArray'][$i]['reservedForSomeGroup'] = $reservedForSomeGroup =  $rows['timereserved'] > time() ; // group, lower priority than personal
			$tpl['shop']['MyShowArray'][$i]['isInRerservedGroup'] = $isInRerservedGroup = null;
			
			if($tpl['user']['user_id'] && $reservedForSomeGroup && !$reservedForSomeUser)
			{
				$tpl['shop']['MyShowArray'][$i]['isInRerservedGroup'] = $isInRerservedGroup = isInRerservedGroup($tpl['user']['user_id'], $rows["shopcoins"]);
			}
			//ввожу 			
		    //для наборов монет, цветных, банкнотов, мелочи и наборов надо проверять что зарезервировано
			if (in_array($rows["materialtype"],array(7,8,6,2,4))) {
				
				//получаем уже зарезервированные монеты
				$result_amount = $shopcoins_class->getReserved($rows["shopcoins"],$reservetime);				
				$reserveamount = 0;
				$statusshopcoins = 0;
				$reserveuser = 0;
				$reservealluser = 0;
				foreach ($result_amount as $rows_amount){				
					if ( $rows_amount["reserve"] > 0 && ( time() - (int) $rows_amount["reserve"] < $reservetime ) ) { 
						
						$tpl['shop']['reservedForSomeUser'][$i] = $reservedForSomeUser = ( $rows_amount["reserve"] > 0 && ( time() - (int) $rows_amount["reserve"] < $reservetime ) );
						
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

				if ($rows['price1'] && $rows['amount1'] && ($rows['amount'] -$reserveamount)>=$rows['amount1']) {
					$statusopt = 1;		
				} elseif (($rows['amount'] -$reserveamount)>1)	{
					$statusopt = 1;	
				}	
				if (!$reserveuser && $reservealluser) $reserveuser=$reservealluser;
			
				
				if ($statusshopcoins) {
				    $tpl['shop']['MyShowArray'][$i]['buy_status'] = 2;
				} elseif ( ($reservedForSomeUser || (!$tpl['user']['user_id'] && $reservedForSomeGroup) || (false === $isInRerservedGroup)) && $reserveamount>=$rows["amount"]){
				     $tpl['shop']['MyShowArray'][$i]['buy_status'] = 3;
				} elseif($statusopt>0) {				
					if (sizeof($ourcoinsorder) and in_array($rows["shopcoins"], $ourcoinsorder)) {
						 $tpl['shop']['MyShowArray'][$i]['buy_status'] = 8;
					} elseif ($tpl['user']['can_see']) {
					    $tpl['shop']['MyShowArray'][$i]['buy_status'] = 6;
					}						                
				} elseif ($tpl['user']['can_see']&&$rows["check"] == 1 || $rows["check"] == 50 || ($cookiesuser==811 && $rows["check"] >3)){
					//var_dump($tpl['shop']['MyShowArray'][$i]['buy_status']);
					$tpl['shop']['MyShowArray'][$i]['buy_status'] = 7;
				}		
			} else {				
				$reservinfo = '';
				$tpl['shop']['reservedForSomeUser'][$i] = $reservedForSomeUser = ( $rows["reserve"] > 0 && ( time() - (int) $rows["reserve"] < $reservetime )); //personal
				
				if (time() - (int) $rows["reserve"] < $reservetime  and $rows["reserveorder"] == $shopcoinsorder){
					$tpl['shop']['MyShowArray'][$i]['buy_status'] = 2;
				} elseif ( $reservedForSomeUser || (!$tpl['user']['user_id'] && $reservedForSomeGroup) || (false === $isInRerservedGroup) ) {
					$tpl['shop']['MyShowArray'][$i]['buy_status'] = 3;
					
					if ($rows['doubletimereserve'] > time() && $tpl['user']['user_id']>0 && $tpl['user']['user_id'] == $rows['userreserve']) {
						$tpl['shop']['MyShowArray'][$i]['buy_status'] = 4;
					} elseif($rows['timereserved']>$rows['reserve'] && $isInRerservedGroup && $rows['reserve']>0 && $rows['doubletimereserve'] < time()) {
					
						if ($tpl['user']['can_see']	and ($rows["check"] == 1 || $rows["check"] == 50 || ($tpl['user']['user_id']==811 && $rows["check"] >3))){
							$tpl['shop']['MyShowArray'][$i]['buy_status'] = 5;
						}						
					} elseif ($rows['timereserved']<$rows['reserve'] && $rows['doubletimereserve'] < time() && $tpl['user']['user_id']>0) {
						$tpl['shop']['MyShowArray'][$i]['buy_status'] = 5;						
					}
				} elseif ($rows['doubletimereserve'] > time() && $tpl['user']['user_id'] != $rows['userreserve']){
					$tpl['shop']['MyShowArray'][$i]['buy_status'] = 3;
				} elseif ($tpl['user']['can_see']&&$rows["check"] == 1 || $rows["check"] == 50 || ($cookiesuser==811 && $rows["check"] >3)){
				    $tpl['shop']['MyShowArray'][$i]['buy_status'] = 7;
				}			 
			}	
		}	
					
						
						
		$shopcoinstheme = array();
		$strtheme = decbin($rows["theme"]);
		$strthemelen = strlen($strtheme);
		
		$chars = preg_split('//', $strtheme, -1, PREG_SPLIT_NO_EMPTY);
		for ($k=0; $k<$strthemelen; $k++) {
			if ($chars[$k]==1)
			{
				$shopcoinstheme[] = $ThemeArray[($strthemelen-1-$k)];
				if (!in_array(($strthemelen-1-$k), $ShopcoinsThemeArray))
					$ShopcoinsThemeArray[] = ($strthemelen-1-$k);
			}
		}
		
		if (!in_array($rows["group"], $ShopcoinsGroupArray))
			$ShopcoinsGroupArray[] = $rows["group"];
		
		$tpl['shop']['MyShowArray'][$i]['shopcoinstheme'] = $shopcoinstheme;
	}
	
}

//записываем статистике по тому, что искали
if ($search && $submit && $search != 'revaluation' && $search != 'newcoins'){		
    $shopcoins_class->addSearchStatistic();
	
}		

/*
if ($GroupDescription)
			echo "<br>".$GroupDescription."<br>";

if ($materialtype==1 && !$mycoins)
{
	echo $AdvertiseText;
}*/

//if ($group)	include_once "othermaterialid.php";

?>