<?
$id = (int) request('id');
$tpl['series'] = array();

require($cfg['path'].'/models/shopcoinsbyseries.php');
$filter_groups =  array();
$tpl['one_series'] =  array();


$shopcoinsbyseries_class = new model_shopcoinsbyseries($cfg['db']);

$mycoins = array();

if($id){
    $tpl['one_series'] = $shopcoinsbyseries_class->getSeriesDataById($id);
    
    if($tpl['one_series']){

        $tpl['one_series']['data'] = $shopcoinsbyseries_class->getCoinsBySeries($tpl['one_series']["whereselect"]);    
        $tpl['one_series']['group'] = $shopcoins_class->getGroupItem($tpl['one_series']['countrygroup']);       
        
                
        $tpl['task'] = 'one_serie';
        
        $ourcoinsorder = array();
        $ourcoinsorderamount = array();
        if ($shopcoinsorder) {
        	$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);	
        	foreach ( $result_ourorder as $rows_ourorder){
        		$ourcoinsorder[] = $rows_ourorder["catalog"];
        		$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
        	}
        }
        
        foreach ($tpl['one_series']['data'] as $key=>$rows){	

            $tpl['one_series']['data'][$key]['gname'] = $tpl['one_series']['group']["name"];		   
    	    $tpl['one_series']['data'][$key]['metal'] = $tpl['metalls'][$rows['metal_id']];		   
    	    $tpl['one_series']['data'][$key]['condition'] = $tpl['conditions'][$rows['condition_id']];
    	    $statuses = $shopcoins_class->getBuyStatus($rows["shopcoins"],$tpl['user']['can_see'],$ourcoinsorder,$shopcoinsorder);
    	    
    		$tpl['one_series']['data'][$key]['buy_status'] = $statuses['buy_status'];
    		$tpl['one_series']['data'][$key]['reserved_status'] = $statuses['reserved_status'];	
    		$tpl['one_series']['data'][$key]['mark'] = $shopcoins_class->getMarks($rows["shopcoins"]);
    		
    	    $tpl['one_series']['data'][$key] = array_merge($rows, contentHelper::getRegHref($rows));      	        	    
        }
    }
    
    
}

if(!$tpl['one_series']){
    //получаем все доступные серии
    $tpl['series_groups'] = $shopcoinsbyseries_class->getGroups();
    foreach ($tpl['series_groups'] as $group){       
        $tpl['series'][$group['group']]['group'] = $group;
        $tpl['series'][$group['group']]['data'] = $shopcoinsbyseries_class->getSeriesByCoubtry($group['group']);
    }
}

//var_dump($tpl['series'],$tpl['one_series']);
/*


require($cfg['path'].'/helpers/Paginator.php');
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/viporder.php';



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
require $cfg['path'] . '/configs/shopcoins_keywords.php';*/


?>