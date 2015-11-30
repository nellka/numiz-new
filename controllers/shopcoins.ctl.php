<?
require $cfg['path'] . '/configs/config_shopcoins.php';

$catalog = intval($catalog);	
	$sql = "select s.*, g.name as gname, g.groupparent as ggroup from shopcoins as s, `group` as g where 
	s.shopcoins='$catalog' and g.`group` = s.`group` limit 1;";
	$result_info = mysql_query($sql);
	$rows_main = mysql_fetch_array($result_info);
	
	if ($rows_main['check']==0 && !$parent) {
	
		$parentinfo = 1;
		
		$sql_r = "select s.*, g.name as gname, g.groupparent as ggroup from shopcoins as s, `group` as g where 
		s.parent='".$rows_main['parent']."' and (".($cookiesuser==811?(!$nocheck?"s.check=1 or s.check>3":"s.check>3"):"s.check=1").") and g.`group` = s.`group` limit 1;";
		$result_r = mysql_query($sql_r);
		if (mysql_num_rows($result_r)==1) {
			
			$rows_main = mysql_fetch_array($result_r);
			$result_info1 = $sql_r;
			$parentinfo = 0;
		}
		else {
			
			$sql_c = "select catalog from catalogshopcoinsrelation where shopcoins='$catalog';";
			$result_c = mysql_query($sql_c);
			if (mysql_num_rows($result_c)>0) {
			
				$rows_c = mysql_fetch_array($result_c);
				
				$sql_s = "select s.*, g.name as gname, g.groupparent as ggroup from shopcoins as s, `group` as g, catalogshopcoinsrelation as c where 
				c.catalog='".$rows_c[0]."' and c.shopcoins=s.shopcoins and (".($cookiesuser==811?(!$nocheck?"s.check=1 or s.check>3":"s.check>3"):"s.check=1").") and g.`group` = s.`group` limit 1;";
				$result_s = mysql_query($sql_s);
				if (mysql_num_rows($result_s)==1) {
				
					$rows_main = mysql_fetch_array($result_s);
					$result_info1 = $sql_s;
					$parentinfo = 0;
				}
			}
		}
	}
	
	if (!$parent) {
	
		$sqlcicle = "select * from shopcoinsrecicle where shopcoins='$catalog' limit 1;";
		$resultcicle = mysql_query($sqlcicle);
		if (mysql_num_rows($resultcicle)) {
		
			$rowscicle = mysql_fetch_array($resultcicle);
			$ciclelink = "<p class=txt> <strong>Похожие позиции в магазине:</strong><br>
			<a href=./".$rowscicle['reff1']." title='".$rowscicle['title1']."'>".$rowscicle['title1']."</a> <br><a href=./".$rowscicle['reff2']." title='".$rowscicle['title2']."'>".$rowscicle['title2']."</a> <br><a href=./".$rowscicle['reff3']." title='".$rowscicle['title3']."'>".$rowscicle['title3']."</a>   </p>";
		}
		
	}
	
	//if ($user_remote_address  == "89.175.10.10")
	{
		$tmp = Array();
		if ($LastCatalog10)
		{
			$tmp = explode("#", $LastCatalog10);
			unset ($LastCatalog10_tmp);
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
		
		if ($rows_main["shopcoins"])
		{
			$LastCatalog10 = $rows_main["shopcoins"]."|".$rows_main["gname"]."|".$rows_main["group"]."|".$rows_main["materialtype"]."|".$rows_main["name"]."|".$rows_main["price"]."|".$rows_main["image_small"]."|".$rows_main["image_big"]."#".$LastCatalog10_tmp;
		}
		else
			$LastCatalog10 = $LastCatalog10_tmp;

		if(cookiesWork()) {
			setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/shopcoins/", $domain);
			setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/shopcoins/");
			setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/shopcoins/", ".shopcoins.numizmatik.ru");
			setcookie("LastCatalog10", $LastCatalog10, time() + 86400*30, "/");
		}
		else {
			//vbhjckfd
			$_SESSION['LastCatalog10'] = $LastCatalog10;
		}
	}
	
	//$Meta = GetMeta ("shopcoins", "keywords", "details", "shopcoins='$catalog'", "", 0, 0);
	if ($materialtype==1 || $materialtype==12)
	{
		if($rows_main['year'] == 1990 && $materialtype==12) $rows_main['year'] = '1990 ЛМД';
		if($rows_main['year'] == 1991 && $materialtype==12) $rows_main['year'] = '1991 ЛМД';
		if($rows_main['year'] == 1992 && $materialtype==12) $rows_main['year'] = '1991 ММД';
		$tpl['title'] = "Монет".($parent?"ы":"а")." ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");
	}
	elseif ($materialtype==10 )
	{
		$tpl['title'] = "Нотгельд".($parent?"ы":"")." ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");
	}
	elseif ($materialtype==8)
	{
		$tpl['title'] = "Дешев".($parent?"ые":"ая")." монет".($parent?"ы":"а")." ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
	}
	elseif ($materialtype==7)
	{
		$tpl['title'] = "Набор".($parent?"ы":"")." монет ".$rows_main["gname"]." | ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");
	}
	elseif ($materialtype==4)
	{
		$tpl['title'] = "Подарочны".($parent?"е":"й")." набор".($parent?"ы":"")." монет ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["metal"]?" ".$rows_main["metal"]." ":"").($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
	}
	elseif ($materialtype==2)
	{
		$tpl['title'] = "Банкнот".($parent?"ы":"а")."(бон".($parent?"ы":"а").") ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
	}
	elseif ($materialtype==5)
	{
		$tpl['title'] = "Книг".($parent?"и":"а")." по нумизматике | ".$rows_main["name"];	
	}
	elseif ($materialtype==3)
	{
		$tpl['title'] = " Аксессуары для коллекционеров ".$rows_main["gname"]." | ".$rows_main["name"];	
	}
	elseif ($materialtype==9)
	{
		$tpl['title'] = "Лот".($parent?"ы":"")." монет ".($rows_main["ggroup"]==407 && !substr_count($rows_main["gname"],"Россия") && !substr_count($rows_main["gname"],"СССР")?" Россия ":"").$rows_main["gname"]." | ".$rows_main["name"].($rows_main["year"]?" - ".$rows_main["year"]." год":"");	
	}
	else
	{
		$tpl['title'] = "Монет".($parent?"ы":"а")." ".$rows_main["gname"]." | ".$rows_main["name"];
	}
	
	$arraykeyword[] = $rows_main["gname"];
	$keywords = $_Keywords; //$Meta[0];
	$description = $_DescriptionShopcoins; //$Meta[1];
	
	if ($parent)
	{
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
	}
	else
	{
		$sql = "select s.*, g.name as gname from shopcoins as s, `group` as g where 
		s.shopcoins='$catalog' and g.`group` = s.`group` limit 1;";
		$result_info = mysql_query($sql);
		//$rows_main = mysql_fetch_array($result_info);
	}
	
?>