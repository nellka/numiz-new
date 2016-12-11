<?
$catalog=(integer)request('catalog');
$materialtype=request('materialtype');
$parent=request('parent');

$brightnessvalue = request('brightnessvalue');
$nnnx = request('nnnx');

require $cfg['path'] . '/models/catalogshopcoinsrelation.php';
require_once $cfg['path'] . '/models/helpshopcoinsorder.php';
require $cfg['path'] . '/configs/config_shopcoins.php';
$catalogshopcoinsrelation_class = new model_catalogshopcoinsrelation($cfg['db']);
$helpshopcoinsorder_class = new model_helpshopcoinsorder($cfg['db']);

/*
echo "<a href='".((substr_count($_SERVER['HTTP_REFERER'],"localhost")>0 || substr_count($_SERVER['HTTP_REFERER'],"numizmatik.ru")>0) && substr_count($_SERVER['HTTP_REFERER'],".html")==0?$_SERVER['HTTP_REFERER']:"index.php?pagenum=$pagenum".($search?"&search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"").($theme?"&theme=".$theme:"").($searchid?"&searchid=".$searchid:""))."'><< Назад</a>";

*/

$tpl['show'] = array();
$tpl['show']['rowscicle'] =array();
//если пользователь залогинен то и нет родителя то разрешаем оценивать
if ($catalog && $tpl['user']['user_id'] && !$parent) {
	
    $rows_marktmp = $catalogshopcoinsrelation_class->getRowByParams(array('shopcoins' =>$catalog));
    if(!$rows_marktmp) $rows_marktmp['catalog'] = 0;    
	
	$result_mark = $shopcoins_class->getShopcoinsmark($catalog,$rows_marktmp['catalog']);
	
	$usermarkis = 0;
	$markusers = 0;
	$marksum = 0;
	foreach ($result_mark as $rows_mark) {		
		$markusers ++;
		$marksum += $rows_mark['mark'];
		if ($rows_mark['user'] == $tpl['user']['user_id'])
			$usermarkis++;
	}
	$tpl['show']['can_mark'] = true;
	$tpl['show']['usermarkis'] = $usermarkis;	
    $tpl['show']['markusers'] =$markusers;
    $tpl['show']['marksum'] =$markusers;
	
    $tpl['show']['can_add_comment'] = false;
    
	if(	$materialtype == 11 && is_user_has_premissons(intval($_COOKIE['cookiesuser'])) AND 
		is_logged_in(intval($_COOKIE['cookiesuser'])) != FALSE AND 
		!is_already_described($catalog)){  // if user has 5 orders && !is_locked && item not have description
            $tpl['show']['can_add_comment'] = true;
			$sql = "select * from `group` where type='shopcoins' and `group` not in (667,937,983,997,1014,1015,1062,1063,1097,1106) group by name;";
			$result = mysql_query($sql);
			$i=0;
			while ($rows = mysql_fetch_array($result)) {
				$groupselect_v .= ($i!=0?",\"":"\"").str_replace('"','',$rows["name"])."\"";
				$groupselect_v2 .= ($i!=0?",":"").str_replace('"','',$rows["name"])."";
				$i++;
			}	
	}   
}

//$addhref .= "&page=show".($parent?"&parent=".$parent:"");

//echo "pagenumparent=".$_SERVER['REQUEST_URI'];
$parentinfo = 0;

if ($catalog){	
	$tpl['show']['rows_main'] = $rows_main = $shopcoins_class ->catalogDetails($catalog,1);
	
	if ($rows_main['check']==0 && !$parent) {	
		$parentinfo = 1;	
			
		$data = array('user'=> $tpl['user']['user_id'],
		              'parent' =>$rows_main['parent']);
		$rows_main = $shopcoins_class ->catalogDetails($catalog,$data);
		
		$sql_r = "select s.*, g.name as gname, g.groupparent as ggroup from shopcoins as s, `group` as g where 
		s.parent='".$rows_main['parent']."' and (".($cookiesuser==811?(!$nocheck?"s.check=1 or s.check>3":"s.check>3"):"s.check=1").") and g.`group` = s.`group` limit 1;";
		$result_r = mysql_query($sql_r);
		
		if (mysql_num_rows($result_r)==1) {
			
			$rows_main = mysql_fetch_array($result_r);
			$result_info1 = $sql_r;
			$result_info=mysql_query($result_info1);	
			$parentinfo = 0;
		} else {		
		    $rows_c = $catalogshopcoinsrelation_class->getOneByParams('catalog',array('shopcoins'=>$catalog));
			$sql_c = "select catalog from catalogshopcoinsrelation where ;";
			$result_c = mysql_query($sql_c);
			if (mysql_num_rows($result_c)>0) {
			
				$rows_c = mysql_fetch_array($result_c);
				
				$sql_s = "select s.*, g.name as gname, g.groupparent as ggroup from shopcoins as s, `group` as g, catalogshopcoinsrelation as c where 
				c.catalog='".$rows_c[0]."' and c.shopcoins=s.shopcoins and (".($cookiesuser==811?(!$nocheck?"s.check=1 or s.check>3":"s.check>3"):"s.check=1").") and g.`group` = s.`group` limit 1;";
				$result_s = mysql_query($sql_s);
				if (mysql_num_rows($result_s)==1) {
				
					$rows_main = mysql_fetch_array($result_s);
					$result_info1 = $sql_s;
					$result_info=mysql_query($result_info1);	
					$parentinfo = 0;
				}
			}
		}
	}

	if (!$parent) {	
		$tpl['show']['rowscicle'] = $shopcoins_class->getCoinsrecicle($catalog);		
	}

	$tmp = Array();
	if ($LastCatalog10)	{
		$tmp = explode("#", $LastCatalog10);
		$LastCatalog10_tmp = '';
		for ($i=0; $i < (sizeof($tmp)<10?sizeof($tmp):10); $i++)
		{
			unset ($tmp1);
			$tmp1 = explode("|", $tmp[$i]);
			if ($tmp1[0] != $catalog)
			{
				$LastCatalog10_tmp .= $tmp[$i]."#";
			}
		}
	}
	
	if ($rows_main["shopcoins"]) {
		$LastCatalog10 = $rows_main["shopcoins"]."|".$rows_main["gname"]."|".$rows_main["group"]."|".$rows_main["materialtype"]."|".$rows_main["name"]."|".$rows_main["price"]."|".$rows_main["image_small"]."|".$rows_main["image_big"]."#".$LastCatalog10_tmp;
	}	else $LastCatalog10 = $LastCatalog10_tmp;

	
	setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/shopcoins/", $domain);
	setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/shopcoins/");
	setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/shopcoins/", ".shopcoins.numizmatik.ru");
	setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/");
	$_SESSION['LastCatalog10'] = $LastCatalog10;

	$_DescriptionShopcoins = '';
	$tpl['show']['rows_main']['year'] = $rows_main['year'] = contentHelper::setYearText($rows_main['year'],$materialtype);
		
	if ($materialtype==1 || $materialtype==12) {
		$title = "Монет".($parent?"ы":"а")." ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");
	} elseif ($materialtype==10 ) {
		$title = "Нотгельд".($parent?"ы":"")." ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");
	} elseif ($materialtype==8)	{
		$title = "Дешев".($parent?"ые":"ая")." монет".($parent?"ы":"а")." ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
	}	elseif ($materialtype==7){
		$title = "Набор".($parent?"ы":"")." монет ".$rows_main["gname"]." | ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");
	} elseif ($materialtype==4)	{
		$title = "Подарочны".($parent?"е":"й")." набор".($parent?"ы":"")." монет ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
	}	elseif ($materialtype==2){
		$title = "Банкнот".($parent?"ы":"а")."(бон".($parent?"ы":"а").") ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
		$_DescriptionShopcoins = 'Продажа банкнот и бон со всего мира. Купить банкноты в нашем интернете магазине. Каталог. Цены.';
		
	}	elseif ($materialtype==5) {
		$title = "Книг".($parent?"и":"а")." по нумизматике | ".$rows_main["name"];	
	}	elseif ($materialtype==3)	{
		$title = " Аксессуары для коллекционеров ".$rows_main["gname"]." | ".$rows_main["name"];	
	} elseif ($materialtype==9)	{
		$title = "Лот".($parent?"ы":"")." монет ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
	}	else {
		$title = "Монет".($parent?"ы":"а")." ".$rows_main["gname"]." | ".$rows_main["name"];
	}
	
	$tpl['shopcoins']['_Keywords'] = $rows_main["gname"];
	//$keywords = $_Keywords; //$Meta[0];
	$tpl['shopcoins']['_Description'] = $_DescriptionShopcoins; //$Meta[1];
	$tpl['shopcoins']['_Title'] = $title;
	
	if ($parent) {
		$onpage = 8;
		if (!$pagenumparent)
			$pagenumparent = 1;
		
		if ($materialtype==7 || $materialtype==8 || $materialtype==6 || $materialtype==4 || $materialtype==2) 
			$sql = "select s.*, g.name as gname from shopcoins as s, `group` as g where 
			s.shopcoins='$parent' and g.`group` = s.`group` and (".($cookiesuser==811?(!$nocheck?"s.check=1 or s.check>3":"s.check>3"):"s.check=1").") ";
		else 
			$sql = "select s.*, g.name as gname, if(s.realization=0,0,1) as sr from shopcoins as s, `group` as g where 
			s.parent='$parent' and g.`group` = s.`group` and (".($cookiesuser==811?(!$nocheck?"s.check=1 or s.check>3":"s.check>3"):"s.check=1").") order by s.`check` asc, sr asc, s.".$dateinsert_orderby." desc, s.shopcoins 
			limit ".($pagenumparent-1)*$onpage.", $onpage";
		$result_info = mysql_query($sql);
	}	else	{
		if(!isset($rows_main)) $rows_main = $shopcoins_class->catalogDetails($catalog);		
	}
}
	
//показываем количество страниц
if ($parent) {	
    
	if ($materialtype==7 || $materialtype==8 || $materialtype==6 || $materialtype==4 || $materialtype==2) 
		$sql = "select if (amount>10,10,amount) as amount from shopcoins where (shopcoins='$parent' or parent='$parent') and (shopcoins.`check`='1'".($tpl['user']['user_id']==811?" or shopcoins.`check`>'3'":"").");";
	else 
		$sql = "select count(*) as amount from shopcoins where parent='$parent' and (shopcoins.`check`='1'".($tpl['user']['user_id']==811?" or shopcoins.`check`>'3'":"").");";
	
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$countpubs=$row['amount'];
	
	$onpage=8;
	$numpages=15;$pagenumparent=round($pagenumparent);
	$pages=ceil($countpubs/$onpage);if (!$pages) $pages=1;
	if ($pagenumparent<1) 
		$pagenumparent=1; 
	if ($pagenumparent>$pages) 
		$pagenumparent=$pages;
	$i=1;
	
	$frompage=floor(($pagenumparent-1)/$numpages)*$numpages+1;
	$topage=ceil($pagenumparent/$numpages)*$numpages;if ($topage>$pages) $topage=$pages;
	
	$tempurl = explode('/',$_SERVER['REQUEST_URI']);
	$rehref = $tempurl[sizeof($tempurl)-1];
	$tempurl2 = explode("_",$rehref);
	for ($i=0;$i<sizeof($tempurl2)-1;$i++) 
		$rehrefpage .= ($i>0?"_":"").$tempurl2[$i];
	
	
	if ($pagenumparent>2*$numpages) $page_string .= "<a href='".$rehrefpage."_pp1.html'>[в начало]</a> | ";
	if ($frompage>$numpages) $page_string .= "<a href='".$rehrefpage."_pp".($frompage-1).".html'><<пред</a> | ";
	for ($i=$frompage;$i<=$topage;$i++)
		{
		if ($i==$pagenumparent) $page_string .= "<b>$i</b>";
		else $page_string .= "<a href='".$rehrefpage."_pp$i.html'>$i</a>";
		if ($i<$topage) $page_string .= " | ";
		}      
	if ($pages>$topage) $page_string .= " | <a href='".$rehrefpage."_pp$i.html'>далее>></a>";
	
	echo "<p class=txt><b>Страницы: </b>".$page_string." </p>";
}


//выбираем что он из аксессуаров/книг заказал
if (($materialtype==3 || $materialtype==5) || $rows_main["materialtype"]==3 || $rows_main["materialtype"]==5)
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

//показываем по 8
$onpage = 8;

$countpubs = $shopcoins_class->countallByParams($materialtype);

$rows99 = $rows_main;

if ($materialtype==7 || $materialtype==8 || $materialtype==6 ||$materialtype==4 || $materialtype==2 || $rows_main["materialtype"]==7 || $rows_main["materialtype"]==8 || $rows_main["materialtype"]==4 || $rows_main["materialtype"]==2) {
	
	$amountimages = $countpubs - ($pagenumparent-1)*$onpage; 
	var_dump($amountimages, $countpubs);
	if ($amountimages > $onpage) $amountimages = $onpage;

	if ($rows_main["amount"] >10)
		$rows_main["amount"] = 10;
	
	$reservcount = $helpshopcoinsorder_class->countReserved($rows_main["shopcoins"],time() - $reservetime); 	

	if (!$parent) {	
		if ($rows_main["amount"] <= $reservcount) 
			$result_amount = $helpshopcoinsorder_class->getReserved($rows_main["shopcoins"],time() - $reservetime);
		else $result_amount = $helpshopcoinsorder_class->getReserved($rows_main["shopcoins"],time());
		
		$amountimages = 1;
	}	else {
	    $result_amount = $helpshopcoinsorder_class->countReserved($rows_main["shopcoins"],time() - $reservetime); 
		/*$sql_amount = "SELECT * FROM helpshopcoinsorder WHERE shopcoins='".$rows_main["shopcoins"]."' AND reserve > '".(time() - $reservetime)."' limit ".(($pagenumparent-1)*$onpage).", $onpage;";*/
	}
	
	for ($i=0; $i<$amountimages;$i++) {
		
		$textoneclick = '';
		unset ($details);
		echo "<table border=0 cellpadding=2 cellspacing=0 width=98%>";
		echo "<tr bgcolor=#99CCFF>
		<td class=tboard colspan=2><b>".$rows_main["name"]."</b></td></tr>";
		echo "<tr><td class=tboard>";
		//для яркости - для монет и бон
		if ($materialtype==1||$materialtype==12||$materialtype==2 || $materialtype==4 || $materialtype==8 || $materialtype==6)
		{
		
			if (file_exists("./images/".$rows_main["image_big"])) {
				$showimage = $rows_main["image_big"];
			}
			elseif (file_exists("./images/".$rows_main["image"])) {
				$showimage = $rows_main["image"];
			}
			else {
				$nnnx=1;
				echo "<div id=Image_Big><font color=blue size=+2>Нет изображения</font></div>";
			}
			
			if (!$brightnessvalue && !$nnnx)
			{
				echo "<div id=Image_Big><img src=images/".(trim($rows_main["image_big"])?$rows_main["image_big"]:$rows_main["image"])." alt='".$rows_main["gname"]." - ".$rows_main["name"]."' border=1></div>";
			}
			elseif (!$nnnx)
			{
				echo "<div id=Image_Big><img src=http://www.numizmatik.ru/shopcoins/photoshop7.php?Image=".(trim($rows_main["image_big"])?$rows_main["image_big"]:$rows_main["image"])."&brightnessvalue=$brightnessvalue alt='".$rows_main["gname"]." - ".$rows_main["name"]."' border=1></div>";
			}
						
			echo "<center>
			<table border=0 cellpadding=0 cellspacing=0 align=center>
			".($amountimages <= 1?"<form action='index.php?page=$page&pagenum=$pagenum".($search?"&search=".urlencode($search):"")."&group=$group&materialtype=$materialtype".($pricestart?"&pricestart=".$pricestart:"").($priceend?"&priceend=".$priceend:"").($theme?"&theme=".$theme:"")."' method=post name=mainform onsubmit='Javascript:CheckingValue();'>":"")."
			<input type=hidden name=catalog value='$catalog'>
			".($parent?"<input type=hidden name=parent value='$parent'>":"")."
			".($searchid?"<input type=hidden name=searchid value='$searchid'>":"");
			if ($amountimages <= 1) {
				echo "<tr>
				<td class=tboard>
				<b>Яркость </b>
				<a href=# title='Увеличить яркость изображения ".$rows_main["gname"]." - ".$rows_main["name"]."'><img src=".$cfg['site_dir']."images/addbrightness.gif onclick='javascript:AddBrightness();' border=0 alt='Увеличить яркость изображения ".$rows_main["gname"]." - ".$rows_main["name"]."'></a>&nbsp;&nbsp;
				<input type=text name=brightnessvalue class=formtxt size=3 value='".($brightnessvalue?$brightnessvalue:"0")."'>
				&nbsp;&nbsp;<a href=# title='Уменьшить яркость изображения ".$rows_main["gname"]." - ".$rows_main["name"]."'><img src=".$cfg['site_dir']."images/reducebrightness.gif onclick='javascript:ReduceBrightness();' border=0 alt='Уменьшить яркость изображения ".$rows_main["gname"]." - ".$rows_main["name"]."'></a>
				&nbsp;&nbsp;
				</center>
				</td>
				</tr>";
				
				$imagebig = $rows_main["image_big"];
				$imagest = $rows_main["image"];
				$namest = $rows_main["name"];
			}
			echo "</table>";
		}
		elseif ($materialtype==3 || $materialtype==7 || $materialtype==5)
		{
			echo "<img src=images/".(trim($rows_main["image_big"])?$rows_main["image_big"]:$rows_main["image"])." alt='".$rows_main["gname"]." - ".$rows_main["name"]."' border=1>";
		}
		
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
				$details .= "<br>Серия монет: <a href=".$cfg['site_dir']."/shopcoins?series=".$rows["series"]."&group=".$rows["group"].">".$rows_series["name"]."</a>";
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
			
			if($tpl['user']['user_id'] && $reservedForSomeGroup && !$reservedForSomeUser)
			{
				$isInRerservedGroup = isInRerservedGroup($tpl['user']['user_id'], $rows_main["shopcoins"]);
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
				echo "<img src=".$cfg['site_dir']."images/corz7.gif border=0 alt='Уже в корзине'>";
			elseif ( $reservedForSomeUser || (!$tpl['user']['user_id'] && $reservedForSomeGroup) || (false === $isInRerservedGroup) )
				echo "<img src=".$cfg['site_dir']."images/corz6.gif border=0 alt='Покупает другой посетитель монету ".$rows_main["gname"]." ".$rows_main["name"]."'>";
			elseif($statusopt>0 && $amountimages==1 && !$parent) {
						
				echo "
						<input type=text name=amount".$rows_main["shopcoins"]." size=4 class=formtxt value='0'> 
						<a href='#coin".$rows_main["shopcoins"]."' onclick='javascript:AddAccessory(".$rows_main["shopcoins"].")' title='Положить в корзину монету ".$rows_main["name"]."'><div id=bascetshopcoins".$rows_main["shopcoins"]."><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='Положить в корзину монету ".$rows_main["name"]."'></div></a></form>
						";
				
			}
			else
			{
				if ($tpl['user']['can_see']&& ($rows_main["check"]==1 or ($tpl['user']['user_id']==811 && $rows_main["check"]>3))
				) {
					echo "<div id=bascetshopcoin".($i+1)."><div id=bascetshopcoins".$rows_main["shopcoins"]." ><a href='#coin".$rows_main["shopcoins"]."' onclick=\"javascript:AddBascetTwo('".$rows_main["shopcoins"]."','1','".($i+1)."');\" rel=\"nofollow\" title='Купить монету ".$rows_main["gname"]." ".$rows_main["name"]."'><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='Купить монету ".$rows_main["gname"]." ".$rows_main["name"]."'></a></div></div>";
					if ($minpriceoneclick<=$rows_main['price']) $textoneclick = " <a href=#coinone".($i+1)." onclick=\"ShowOneClick(".$rows_main["shopcoins"].",'".htmlspecialchars("<img src=".$cfg['site_dir']."shopcoins/images/".$rows_main['image']." style=\" border:thin solid 1px #000000\" align=left> ".$rows_main["name"]." ".intval($rows_main["price"]))." руб.',".($i+1).");\"><div  id=oneshopcoins".$rows_main["shopcoins"]."><img src=".$cfg['site_dir']."images/corz15.gif border=0></div></a><div id=oneshopcoin".($i+1)."></div><a name=coinone".($i+1)."></a>";
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
		elseif ($materialtype==3)
		{
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
			if (sizeof($ourcoinsorder) and in_array($rows_main["shopcoins"], $ourcoinsorder))
				$details .= "
				<input type=text name=amount".$rows_main["shopcoins"]." id=amount".$rows_main["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows_main["shopcoins"]]."'> 
				<a href='#'>
				<div id=bascetshopcoins".$rows_main["shopcoins"].">
				<img src=".$cfg['site_dir']."images/corz7.gif border=0 alt='Уже в корзине' onclick='javascript:AddAccessory(".$rows_main["shopcoins"].")'></div>
				</a>";
			else
			{
				if ($tpl['user']['can_see']){
					$details .= "
					<input type=text name=amount".$rows_main["shopcoins"]." id=amount".$rows_main["shopcoins"]." size=4 class=formtxt value='0'> 
					<div id=bascetshopcoins".$rows_main["shopcoins"]."><a href='#'><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='В корзину' onclick='javascript:AddAccessory(".$rows_main["shopcoins"].")'></a></div>
					";
				}				
			}
			
		}
		elseif ($materialtype==5)
		{
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
				<img src=".$cfg['site_dir']."images/corz7.gif border=0 alt='Уже в корзине'></div>
				</a>";
			else
			{
				if ($tpl['user']['can_see']){
					$details .= "
					<input type=text name=amount".$rows_main["shopcoins"]." size=4 class=formtxt value='0'> 
					<div id=bascetshopcoins".$rows_main["shopcoins"]."><a href='#'><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='В корзину' onclick='javascript:AddAccessory(".$rows_main["shopcoins"].")'></a></div>
					";
				}				
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
		echo "</table>";
	}
} else {
	$i = 0;

	if ($rows_main) {	
		$textoneclick = '';
		$rows99 = $rows_main;
		$i++;

		//для наборов монет, цветных, банкнотов, мелочи и наборов надо проверять что зарезервировано
		if (in_array($rows_main["materialtype"],array(1,12,11,10,2,4,7,8,6,9))) {	
				
			$reservedForSomeUser = ( $rows_main["reserve"] > 0 && ( time() - (int) $rows_main["reserve"] < $reservetime ) ); //personal
			$reservedForSomeGroup =  $rows_main['timereserved'] > time() ; // group, lower priority than personal
			$isInRerservedGroup = null;
			if($tpl['user']['user_id'] && $reservedForSomeGroup && !$reservedForSomeUser)
			{
				$isInRerservedGroup = isInRerservedGroup($tpl['user']['user_id'], $rows["shopcoins"]);
			}
	
			if (time() - (int) $rows_main["reserve"] < $reservetime and $rows_main["reserveorder"] == $shopcoinsorder) {
				$tpl['show']['buy_status'] = 2;
			} elseif ( $reservedForSomeUser || (!$tpl['user']['user_id'] && $reservedForSomeGroup) || (false === $isInRerservedGroup) ) {
				$tpl['show']['buy_status'] = 3;
				if ($rows_main['doubletimereserve'] > time() && $tpl['user']['user_id']>0 && $tpl['user']['user_id'] == $rows_main['userreserve']) {
					$tpl['show']['buy_status'] = 4;
				} elseif($rows_main['timereserved']>$rows_main['reserve'] && $isInRerservedGroup && $rows_main['reserve']>0) {					
						if ($tpl['user']['can_see']	&& ($rows_main["check"]==1 or ($tpl['user']['user_id']==811 && $rows_main["check"]>3))){
							$tpl['show']['buy_status'] = 5;
						}
				} elseif ($rows_main['timereserved']<$rows_main['reserve'] && $rows_main['doubletimereserve'] < time() && $tpl['user']['user_id']>0 && $rows_main['doubletimereserve'] < time()) {
					$tpl['show']['buy_status'] = 5;
				}
			} elseif ($rows_main['doubletimereserve'] > time() && $tpl['user']['user_id'] != $rows_main['userreserve']){
				$tpl['show']['buy_status'] = 3;
			} elseif ($rows_main["check"]==1 or ($tpl['user']['user_id']==811 && $rows_main["check"]>3)) {
				if ($tpl['user']['can_see']&&($rows_main["check"]==1 or ($tpl['user']['user_id']==811 && $rows_main["check"]>3))){
					$tpl['shop']['MyShowArray'][$i]['buy_status'] = 7;					
				} elseif ($rows_main["check"] == 0) $tpl['show']['buy_status'] = 9;						
			} elseif ($rows_main["check"] == 0) $tpl['show']['buy_status'] = 9;				
		}	
		
	}
}

//сейчас показываем токо для аксессуаров
if ($materialtype==3) {
	//показ сопутствующих товаров
	$tpl['shop']['related'] = $shopcoins_class->getRelated($catalog);
	$i = 0;
	$oldmaterialtype = 0;
	if ($result){		
		foreach ($result as $rows){
		    $tpl['shop']['related'][$i]['additional_title'] = '';
			if ($oldmaterialtype != $rows["materialtype"]) {
				$tpl['shop']['related'][$i]['additional_title'] = $MaterialTypeArray[$rows["materialtype"]];
				$oldmaterialtype = $rows["materialtype"];
			}		
			$i++;
		}
	}
}

if (!$parent && $catalog) {
    $tpl['shop']['resultp'] = $shopcoins_class->showedWith($catalog, $rows99);
    $tpl['shop']['resultp'][$i]['buy_status']=2;
}
	
if ($parentinfo == 1) { 
	$tpl['shop']['result_show_relation2'] = $shopcoins_class->fromCatalogByGname($rows_main);
	$tpl['shop']['parentinfo'] = 1;
	if(!$result_show_relation2) {	
		$tpl['shop']['parentinfo'] = 0;
	}	
}

if ($parentinfo==0) {
	$tpl['shop']['mycatalog1'] = $catalogshopcoinsrelation_class->getOneByParams('catalog',array('shopcoins'=>$catalog));
    $tpl['shop']['result_show_relation2'] = array();
	if ($tpl['shop']['mycatalog1']) {		
		$tpl['shop']['result_show_relation2'] = $catalogshopcoinsrelation_class->getRelations2($catalog,$tpl['shop']['mycatalog1']);		
		if ($tpl['shop']['result_show_relation2']) {			
		
			$oldmaterialtype = 0;
			foreach ($result_show_relation2 as $rows_show_relation2){				
				if ($k%2==0)
					$RelationText .= "<tr bgcolor=#EBE4D4 valign=top>
				<td class=tboard width=50%><div id=show".$rows_show_relation2['shopcoins']."></div>";
				else
					$RelationText .= "<td class=tboard width=50%><div id=show".$rows_show_relation2['shopcoins']."></div>";
					
				$RelationText .= "<img src=smallimages/".$rows_show_relation2["image_small"]." border=1 alt='".$rows_show_relation2["gname"]." | ".$rows_show_relation2["name"]."' width=80 align=left ".($materialtype==1||$materialtype==2 || $materialtype==4 || $materialtype==8||$materialtype==6?"onMouseover=\"javascript:ShowLot('".$rows_show_relation2['shopcoins']."','".($i-1)."','<img border=1 bordercolor=black src=images/".$rows_show_relation2['image_big'].">');\" onMouseout=\"javascript:NotShowLot('".$rows_show_relation2['shopcoins']."');\"":"").">
				<a href=index.php?catalog=".$rows_show_relation2["shopcoins"]."&page=show&materialtype=".$rows_show_relation2["materialtype"].">".$rows_show_relation2["name"]."</a><br>
				".$rows_show_relation2["gname"]."<br>
				<font color=red><b>";
				if ($rows_show_relation2["price"]==0)
					$RelationText .= "бесплатно";
				else
					$RelationText .= round($rows_show_relation2["price"],2)." руб.";
				$RelationText .= "</b></font><br> 
				";
				
				if ($rows_show_relation2["materialtype"]==3)
				{
					if (sizeof($ourcoinsorder) and in_array($rows_show_relation2["shopcoins"], $ourcoinsorder))
						$RelationText .= "
						<input type=text id=amount".$rows_show_relation2["shopcoins"]." name=amount".$rows_show_relation2["shopcoins"]." size=4 class=formtxt value='".$ourcoinsorderamount[$rows_show_relation2["shopcoins"]]."'> 
						<a href='#' onclick='javascript:AddAccessory(".$rows_show_relation2["shopcoins"].")'><div id=bascetshopcoins".$rows_show_relation2["shopcoins"].">
						<img src=".$cfg['site_dir']."images/corz7.gif border=0 alt='Уже в корзине'></div>
						</a>";
					else
					{
						if ($tpl['user']['can_see'])
						{
							$RelationText .= "
							<input type=text name=amount".$rows_show_relation2["shopcoins"]." id=amount".$rows_show_relation2["shopcoins"]." size=4 class=formtxt value='0'> 
							<a href='#' onclick='javascript:AddAccessory(".$rows_show_relation2["shopcoins"].");'><div id=bascetshopcoins".$rows_show_relation2["shopcoins"]."><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='В корзину'></div></a>
							
							";
						}
					}
				}
				elseif ($rows_show_relation2["materialtype"]==1||$rows_show_relation2["materialtype"]==2|| $rows_show_relation2['materialtype']==8||$rows_show_relation2['materialtype']==6|| $rows_show_relation2['materialtype']==4|| $rows_show_relation2['materialtype']==7|| $rows_show_relation2['materialtype']==9|| $rows_show_relation2['materialtype']==10)
				{
					
					if (sizeof($ourcoinsorder) and in_array($rows_show_relation2["shopcoins"], $ourcoinsorder))
						$RelationText .=  "<img src=".$cfg['site_dir']."images/corz7.gif border=0 alt='Уже в корзине'>";
					elseif (sizeof($CoinsNow) and in_array($rows_show_relation2["shopcoins"], $CoinsNow))
						$RelationText .=  "<img src=".$cfg['site_dir']."images/corz6.gif border=0 alt='Покупает другой посетитель'><br><font color=gray size=-2>Бронь до ".date("H:i", $rows_show_relation2["reserve"]+$reservetime)."</font>";
					else
					{
						if ($tpl['user']['can_see'])
							
							if ($rows_show_relation2["materialtype"]==1|| $rows_show_relation2['materialtype']==9|| $rows_show_relation2['materialtype']==10)
								$RelationText .=  "<div id=bascetshopcoins".$rows_show_relation2["shopcoins"]." ><a href='#coin".$rows_show_relation2["shopcoins"]."' onclick=\"javascript:AddBascet('".$rows_show_relation2["shopcoins"]."','1');\" rel=\"nofollow\"><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='В корзину'></a></div>";
							else
								$RelationText .=  "<div id=bascetshopcoin".($i+1)."><div id=bascetshopcoins".$rows_show_relation2["shopcoins"]." ><a href='#coin".$rows_show_relation2["shopcoins"]."' onclick=\"javascript:AddBascetTwo('".$rows_show_relation2["shopcoins"]."','1','".($i+1)."');\" rel=\"nofollow\"><img src=".$cfg['site_dir']."images/corz1.gif border=0 alt='В корзину'></a></div></div>";
					}
					
				}
				
				if ($k%2==0)
					$RelationText .= "</td>";
				else
					$RelationText .= "</td>
				</tr>";
				$i++;
				$k++;
			}
			if ($k%2)
				$RelationText .= "</td><td>&nbsp;</td>
				</tr>";
			$RelationText .= "</table>";
		
			echo $RelationText;
		}
	}
}




if ($tpl['user']['user_id']){
    $rows = $user_class->getRowByParams(array('user'=>$tpl['user']['user_id']));   
	$user = $tpl['user']['user_id'];
	$email = $rows["email"];
	$fio = $rows["fio"];
}
/*
if ($submit and ($fio and $email and $emailfriend)) {
	//
	$file = fopen($cfg['site_dir']."mail/top.html", "r");
	while (!feof ($file)) 
 		$message .= fgets ($file, 1024);
	fclose($file);
	
	$details = $image_str.$details;
	
	$main_message = "
	<tr bgcolor=\"#006699\"><td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td>
	<td width=498><p><b><font color=white>Монетная лавка Клуба Нумизмат</font></b></td>
	<td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td></tr>
	<tr>
	<td width=498 bgcolor=\"#fff8e8\">
	<b>Добрый день, уважаемый(ая).</b>
	<br>Ваш товарищ (___fio___ ___email___) отправил Вам сообщение с Монетной лавки Клуба Нумизмат. 
	<br>___messageform___
	<br><b>Описание лота:</b>
	___details___
	
	<br><br>Для просмотра данного лота перейдите по ссылке.
	<br>Ссылка лота <a href=___url___>___url___</a>

	<br><br>Данное сообщение не гарантирует, что данный лот не продан другому покупателю.

	<br><br>С уважением, администратор Клуба Нумизмат Мандра Богдан
	<br>Web: <a href=http://www.numizmatik.ru>http://www.numizmatik.ru</a>
	<br>Email: administrator@numizmatik.ru
	<br>Phone: 8-926-236-31-92 (с 10 до 22-00 МСК)
	<br><br>
	</td>
	</tr>";
	
	$main_message = str_replace("___fio___",$fio, $main_message);
	$main_message = str_replace("___email___",$email, $main_message);
	$main_message = str_replace("___messageform___",$messageform, $main_message);
	$main_message = str_replace("___details___",$details, $main_message);
	$main_message = str_replace("___url___","http://www.numizmatik.ru/shopcoins/show.php?catalog=".$catalog, $main_message);
	
	$message = $message.$main_message;
	
	$file = fopen($cfg['site_dir']."mail/bottom.html", "r");
	while (!feof ($file)) 
 		$message .= fgets ($file, 1024);
		
	$recipient = $emailfriend;
	//echo $recipient;
	$subject = "Сообщение из Монетной Лавки Клуба Нумизмат";
	$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
	//mail($recipient, $subject, $message, $headers); //покупателю
	$error = "Сообщение успешно отправлено";
}
elseif ($submit)
	$error = "Заполните поля Ф�?О, email и email Вашего друга";
*/



?>
