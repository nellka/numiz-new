<?

$materialtypes = array(1,2,3,4,5,6,7,8,9,10,11, 'newcoins','revaluation');

foreach ($materialtypes as $materialtype){
    if ($materialtype == 'newcoins') {
        $shopcoins_class->setCategoryType(model_shopcoins::NEWCOINS);
    } elseif ($materialtype == 'revaluation') {
    	$shopcoins_class->setCategoryType(model_shopcoins::REVALUATION);
    } else {
        $shopcoins_class->setMaterialtype($materialtype);
        $shopcoins_class->setCategoryType(0);
    }

    $WhereParams = Array();
    
    $dateinsert_orderby = "dateinsert";
    
    $OrderByArray = Array();    
    
    if ($materialtype==5) $OrderByArray[] = " shopcoins.name desc";
    
    $OrderByArray[] ="novelty desc";
	$OrderByArray[] = " if (shopcoins.dateupdate > shopcoins.dateinsert, shopcoins.dateupdate, shopcoins.dateinsert) desc";
	$OrderByArray[] = "shopcoins.dateinsert desc";
	$OrderByArray[] = "shopcoins.price desc";
    
    
    if ($materialtype==1||$materialtype==2||$materialtype==10||$materialtype==4||$materialtype==7||$materialtype==8||$materialtype==6||$materialtype==11||$search=='newcoins'){
    	$OrderByArray[] = "dateinsert desc";
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
    
   // $data = $shopcoins_class->getItemsByParams($WhereParams,1,3,$orderby);
	$data = $shopcoins_class->getNoveltyCoins();
	if(($count = count($data))<3){
		$limit = 3 - $count;
		$data_additional = $shopcoins_class->getFirstCoins($orderby,$limit);
		//var_dump($limit);
		foreach ($data_additional as $r){
			$data[] =  $r;
		}
	}

    $tpl['topmenu']['m'.$materialtype] = Array();

    if($data){
    	$i =0;
    	foreach ($data as $rows){    		
    		$tpl['topmenu']['m'.$materialtype][$i] = $rows;
    		$tpl['topmenu']['m'.$materialtype][$i]['condition'] = $tpl['conditions'][$rows['condition_id']];
	    	 $tpl['topmenu']['m'.$materialtype][$i]['metal'] = $tpl['metalls'][$rows['metal_id']];
	   		$tpl['topmenu']['m'.$materialtype][$i] = array_merge($tpl['topmenu']['m'.$materialtype][$i], contentHelper::getRegHref($tpl['topmenu']['m'.$materialtype][$i],$materialtype));
			
	   		if ($materialtype==5||$materialtype==3){			
				$tpl['topmenu']['m'.$materialtype][$i]['amountall'] = ( !$rows["amount"])?1:$rows["amount"];		
			} else {			
				
				if (in_array($rows["materialtype"],array(2,4,7,8,6)) && $rows['amount']>10) 
					$rows['amount'] = 10;
				
			    $amountall = $rows['amount'];
				if (in_array($rows["materialtype"],array(8,6,7,2,4))) {		
					$amountall = ( !$rows["amount"])?1:$rows["amount"];				
				}			
				$tpl['topmenu']['m'.$materialtype][$i]['amountall'] = $amountall;				
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
			
			$tpl['topmenu']['m'.$materialtype][$i]['shopcoinstheme'] = $shopcoinstheme;	
			 
	   	
    		$i++;
    	}
    }   //var_dump($tpl['topmenu']['m'.$materialtype]);
  //  var_dump('m'.$materialtype,$tpl['topmenu']['m'.$materialtype]);
   /* if ($materialtype==3 || $materialtype==5) {
    	if ($shopcoinsorder > 1) {
    		$result_ourorder = $shopcoins_class->getMyOrderdetails($shopcoinsorder);	
    		foreach ( $result_ourorder as $rows_ourorder){
    			$ourcoinsorder[] = $rows_ourorder["catalog"];
    			$ourcoinsorderamount[$rows_ourorder["catalog"]] = $rows_ourorder["amount"];
    		}
    	}
    }*/
    
    $ShopcoinsThemeArray = Array();
    $ShopcoinsGroupArray = Array();   
    //

//var_dump($tpl['topmenu']['m'.$materialtype]);
/*
	foreach ($tpl['topmenu']['m'.$materialtype] as $i=>$rows) {

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
			
		$i++;	*/
}


//var_dump($tpl['topmenu']);
?>