<?
require($cfg['path'].'/helpers/Paginator.php');
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/viporder.php';

$id = request('id');

if($tpl['user']['user_id']){
	$shopcoins_class->setUserViporder($id);
	//setcookie('viporder', $id,  time()+ (3600 * 24*7),'/');
	
	$viporder_class = new model_shopcoinsvipclientanswer($db_class);
	
	$GroupNameMain = '';
	$GroupName = ''; 
	$metalTitle = '';
	
	$tpl['shop']['errors'] = array();
	
	
	//сохраняем сортировку элементов на странице в куке
	$tpl['orderby'] = "dateinsertdesc";
	
	
	$checkuser = 0;
	$CounterSQL = "";
	
	$WhereParams = Array();
	
	$page_string = "";
	
	$mycoins = 0;
	$ourcoinsorder = Array();
	$ourcoinsorderamount = Array();
	/*
	$OrderByArray = Array();
	
	//if ($group)	$OrderByArray[] = " ABS(shopcoins.group-".$group.") ";
	
	if (($materialtype==3||$materialtype==5) and $group) $OrderByArray[] = " shopcoins.name desc";
	
	if ($materialtype==5) $OrderByArray[] = " shopcoins.name desc";
	
	$OrderByArray[] ="novelty desc";
	
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
	} 
	
	if ($materialtype==1||$materialtype==2||$materialtype==10||$materialtype==4||$materialtype==7||$materialtype==8||$materialtype==6||$materialtype==11||$search=='newcoins'){
		$OrderByArray[] = $dateinsert_orderby." desc";
		$OrderByArray[] = "shopcoins.price desc";
	}
	
	if ($search === 'revaluation') {
		$OrderByArray[] = "shopcoins.datereprice desc";
		$OrderByArray[] = "shopcoins.price desc";
		$OrderByArray[] = "shopcoins.".$dateinsert_orderby." desc";
	}
	
	if (sizeof($OrderByArray)){
		$orderby = array_merge(array("shopcoins.check ASC"),$OrderByArray);
	}
	*/
	
	$data = $viporder_class->getCoins($id);
	
	//$result_search = mysql_query($sql);
	$ArrayParent = Array();
	$tpl['shop']['MyShowArray'] = Array();
	$tpl['shop']['ArrayParent'] = Array();
	if($data){
		foreach ($data as $rows){
			$tpl['shop']['ArrayShopcoins'][] = $rows["shopcoins"];
			$tpl['shop']['ArrayParent'][] = $rows["parent"];
			$tpl['shop']['MyShowArray'][] = $rows;
		}
	}
	
	if (sizeof($tpl['shop']['ArrayParent'])) {
	    $result_search = $shopcoins_class->getCoinsParents($tpl['shop']['ArrayParent']);
		foreach ($result_search as $rows_search ){		
			$tpl['shop']['ImageParent'][$rows_search["parent"]][] = $rows_search["image_small"];
		}	
	}
	
	if ($materialtype==3 || $materialtype==5) {
		if ($shopcoinsorder > 1) {
			$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);	
			foreach ( $result_ourorder as $rows_ourorder){
				$ourcoinsorder[] = $rows_ourorder["catalog"];
				$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
			}
		}
	}
	
	$ShopcoinsThemeArray = Array();
	$ShopcoinsGroupArray = Array();
	
	
	if (sizeof($tpl['shop']['MyShowArray'])==0){
		$tpl['shop']['errors'][] = "<br><p class=txt><strong><font color=red>Извините, нет результатов, удовлетворяющих поиску. Попробуйте другие варианты.</font></strong><br><br>";
	} else {	
		$amountsearch = count($tpl['shop']['MyShowArray']);	
	
		foreach ($tpl['shop']['MyShowArray'] as $i=>$rows) {
	
		    $tpl['shop']['MyShowArray'][$i]['condition'] = $tpl['conditions'][$rows['condition_id']];
		    $tpl['shop']['MyShowArray'][$i]['metal'] = $tpl['metalls'][$rows['metal_id']];
		   
			//формируем картинки "подобные"
			$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'] = array();
			
			if (($rows["materialtype"] ==1)&&isset($tpl['shop']['ImageParent'][$rows["parent"]])&&$tpl['shop']['ImageParent'][$rows["parent"]]>0 && !$mycoins) {	
				$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'][] =contentHelper::showImage("smallimages/".$rows["image_small"],"Монета ".$rows["gname"]." | ".$rows["name"]);
				$tpl['shop']['MyShowArray'][$i]['tmpsmallimage'][] =contentHelper::showImage("smallimages/".$tpl['shop']['ImageParent'][$rows["parent"]][0],"Монета ".$rows["gname"]." | ".$rows["name"]);
			}
	
	
			$tpl['shop']['MyShowArray'][$i] = array_merge($tpl['shop']['MyShowArray'][$i], contentHelper::getRegHref($tpl['shop']['MyShowArray'][$i],$materialtype,$parent));
			
			 if ($materialtype==5||$materialtype==3){			
				$tpl['shop']['MyShowArray'][$i]['amountall'] = ( !$rows["amount"])?1:$rows["amount"];		
			} else {			
				
				if (in_array($rows["materialtype"],array(2,4,7,8,6)) && $rows['amount']>10) 
					$rows['amount'] = 10;
				
			    $amountall = $rows['amount'];
				if (in_array($rows["materialtype"],array(8,6,7,2,4))) {		
					$amountall = ( !$rows["amount"])?1:$rows["amount"];				
				}			
				$tpl['shop']['MyShowArray'][$i]['amountall'] = $amountall;				
			}
			
			
		    $tpl['shop']['MyShowArray'][$i]['mark'] = $shopcoins_class->getMarks($rows["shopcoins"]);		
			
			$tpl['shop']['MyShowArray'][$i]['buy_status'] = 0 ;		
			$textoneclick='';	
			
			if(!$mycoins) {			
				$statuses = $shopcoins_class->getBuyStatus($rows['shopcoins'],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
				$tpl['shop']['MyShowArray'][$i]['buy_status'] = $statuses['buy_status'];
				$tpl['shop']['MyShowArray'][$i]['reserved_status'] = $statuses['reserved_status'];	
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
				
			$i++;	
		}	
	}
		
	require $cfg['path'] . '/configs/shopcoins_keywords.php';
} else $tpl['shop']['errors'][] = "<br><p class=txt><strong><font color=red>Данный раздел доступен только авторизованным пользователям!</font></strong><br><br>";
?>