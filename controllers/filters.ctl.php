<?
$filter_groups  = array(); 
$childen_data_metal = array();
$childen_data_years = array();
$childen_data_thems = array();
$childen_data_conditions = array();
$childen_data_nominals = array();
$childen_data_series = array();
/*
монеты
наборы монет
мелочь
цветные
подарочные наборы
барахолка - что это
*/
if(in_array($materialtype,array(1,7,8,6,4))){
	if(!$tpl['filters']['metalls'] = $cache->load("metalls_$materialtype")) {	   
	    $tpl['filters']['metalls'] = $shopcoins_class->getMetalls();	 
	    $cache->save($tpl['filters']['metalls'], "metalls_$materialtype"."_$search");
	    	 
	} 	

	foreach ($tpl['filters']['metalls'] as $value){
	    $childen_data_metal[] = array(
				'filter_id' => $value["metal_id"],
				'name'      => $value["name"]);   
	}
}
/*
монеты
наборы монет
мелочь
цветные
подарочные наборы
*/
if(in_array($materialtype,array(1,7,8,6,4))){
	if(!$tpl['filters']['conditions'] = $cache->load("conditions_$materialtype")) {	   
	    $tpl['filters']['conditions'] = $shopcoins_class->getConditions();
	    $cache->save($tpl['filters']['conditions'], "conditions_$materialtype"."_$search");	 
	} 	
	
	foreach ($tpl['filters']['conditions'] as $value){
	    $childen_data_conditions[] = array(
				'filter_id' => $value["condition_id"],
				'name'      => $value["name"]);   
	}
}

/*
фильтры по годам делаем пока статическими
$tpl['filters']['years'] = $shopcoins_class->getYears($materialtype);

foreach ($tpl['filters']['years'] as $value){
    $childen_data_years[] = array(
			'filter_id' => $value["year"],
			'name'      => $value["year"]);   
}*/

$tpl['filter']['yearstart'] = 0;
$tpl['filter']['yearend'] = date("Y",time());

if($nominals&&$groups){
    if(!$tpl['filters']['years'] = $cache->load("years_n_".implode('_',$nominals).'_g_'.implode('_',$groups))) {	   
	    $tpl['filters']['years'] = $shopcoins_class->getYears($nominals,$groups);
	    $cache->save($tpl['filters']['years'], "years_n_".implode('_',$nominals).'_g_'.implode('_',$groups));	 
	} 	
	foreach ($tpl['filters']['years'] as $value){
	    $childen_data_years[] = array(
				'filter_id' => $value["year"],
				'name'      => ($value["year"])?$value["year"]:"Без указания года");   
	} 
	
} else {
    if(!in_array($materialtype,array(5,3))&&($search != 'newcoins') ){
     
    	foreach ($yearsArray  as $key=>$value){
    	      // var_dump($yearsArray);
    		$childen_data_years[] = array('filter_id' => $key,'name' => $value['name']);
    	}
    							    
    	$tpl['filter']['yearstart'] = 1600;
    	$tpl['filter']['yearend'] = date("Y",time());
    }
}

/*
фильтр по  тематикам
Монеты
Мелочь
Цветные монеты
Банкноты*/
if(in_array($materialtype,array(1,8,6,2))){
	
	foreach ($ThemeArray as $key=>$value){
		$childen_data_thems[] = array(
				'filter_id' => $key,
				'name'      => $value);   
	}
}



if(!$tpl['filter']['price']['max'] = $cache->load("price_max_$materialtype")) {  
	$tpl['filter']['price']['max'] = (integer)$shopcoins_class->getMaxPrice($group_data);	
	$cache->save($tpl['filter']['price']['max'], "price_max_$materialtype"."_$search"."_g".implode("_",$group_data));	
}
if(!$tpl['filter']['price']['min'] = $cache->load("price_min_$materialtype")) {  
	$tpl['filter']['price']['min'] = (integer)$shopcoins_class->getMinPrice($group_data);	
	$cache->save($tpl['filter']['price']['min'], "price_min_$materialtype"."_$search".implode("_",$group_data));	
}

//($
if(!$tpl['filters']['All_groups'] = $cache->load("all_groups_$materialtype"."_$search")) {
    $tpl['filters']['All_groups'] = $shopcoins_class->getGroups();
    $cache->save( $tpl['filters']['All_groups'], "all_groups_$materialtype"."_$search");	
}


if(!in_array($materialtype,array(5))){
	
	if(!$groups_filter = $cache->load("groups_$materialtype"."_$search")) {  
	    $Group = array();
		foreach ($tpl['filters']['All_groups'] as $rows) {			
			$Group[] = $rows["group"];
		}
		
		if($Group){
			//информация о группе
			$groups_details = $shopcoins_class->getGroupsDetails($Group);
			$GroupArray = array();
			foreach ($groups_details as $rows){		
				if ($rows["groupparent"]>0) {
					$Group[] = $rows["groupparent"];				
					if (!isset($GroupArray[$rows["groupparent"]])||!is_array($GroupArray[$rows["groupparent"]]))
						$GroupArray[$rows["groupparent"]] = Array();
				
					$GroupArray[$rows["groupparent"]][$rows["group"]] = $rows["name"];
				}
			}
			
			$parentGroupsInfo = $shopcoins_class->getGroupsDetails($Group,true);
			$i=1;
			foreach ($parentGroupsInfo as $value){					
		        $sub_childen_data_group = array();	   
		    	if(isset($GroupArray[$value["group"]])){
		    	    foreach ($GroupArray[$value["group"]] as $k=>$v){
		    	        $sub_childen_data_group[] = array('filter_id' => $k,
		    	                                          'name'      => $v);   
		    	    }
		    	}
		    	//выносим Россию вверх	
	    		$childen_data_group[($value["group"]==407?0:$i)] = array('filter_id' => $value["group"],
	    	                             'name'      => $value["name"],
	    	                             'child'     =>$sub_childen_data_group); 
	    	    $i++;	 
		    }
		    ksort($childen_data_group);
		    $groups_filter = array('name'=>($materialtype==5||$materialtype==3)?'Группа':'Страна',
		                                                     'filter_group_id'=>'group',
		                                                     'filter_group_id_full'=>'groups',
		                                                     'filter'=>$childen_data_group);
		   
	        	
				 
		}
		$cache->save($groups_filter, "groups_$materialtype"."_$search");		
	}
	if($groups_filter) $filter_groups[] = $groups_filter;
}

//фильтр по номиналам
if(!$tpl['filters']['nominals'] = $cache->load("nominals_$materialtype".implode("_",$group_data)."_$search")) { 
    $tpl['filters']['nominals'] = $shopcoins_class->getNominals($group_data);
    
    $cache->save($tpl['filters']['nominals'], "nominals_$materialtype".implode("_",$group_data)."_$search");
}
foreach ($tpl['filters']['nominals'] as $value){
    $childen_data_nominals[] = array(
			'filter_id' => $value["nominal_id"],
			'name'      => $value["name"]);   
}
    

//фильтр по сериям
if($materialtype&&$group_data){
	if(!$tpl['filters']['series'] = $cache->load("series_$materialtype".implode("_",$group_data))) { 
	    $tpl['filters']['series'] = $shopcoins_class->getFilterSeries($group_data);
	    
	    $cache->save($tpl['filters']['series'], "series_$materialtype".implode("_",$group_data));	
	}
	foreach ((array)$tpl['filters']['series'] as $value){  
	    $childen_data_series[] = array(
				'filter_id' => $value["series"],
				'name'      => $value["name"]);   
	}
}


if( $childen_data_nominals) $filter_groups[] = array('name'=>'Номинал','filter_group_id'=>'nominal','filter_group_id_full'=>'nominals','filter'=>$childen_data_nominals,'materialtype'=>$materialtype);

if( $childen_data_series) $filter_groups[] = array('name'=>'Серии','filter_group_id'=>'series','filter_group_id_full'=>'seriess','filter'=>$childen_data_series,'materialtype'=>$materialtype);

if($nominals&&$groups){
    if($childen_data_years) $filter_groups[] = array('name'=>'Год','filter_group_id'=>'years_p','filter_group_id_full'=>'years_p','filter'=>$childen_data_years);
} else {
    if($childen_data_years) $filter_groups[] = array('name'=>'Год','filter_group_id'=>'years','filter_group_id_full'=>'years','filter'=>$childen_data_years);
}
if($childen_data_metal) $filter_groups[] = array('name'=>'Металл','filter_group_id'=>'metal','filter_group_id_full'=>'metals','filter'=>$childen_data_metal);
if($childen_data_conditions) $filter_groups[] = array('name'=>'Состояние','filter_group_id'=>'condition','filter_group_id_full'=>'conditions','filter'=>$childen_data_conditions);
if($childen_data_thems) $filter_groups[] = array('name'=>'Тематика','filter_group_id'=>'theme','filter_group_id_full'=>'themes','filter'=>$childen_data_thems);

$filter_groups['group_details'] = array();


foreach ((array)$groups as $group){
	$groupData = $shopcoins_class->getGroupItem($group);
	$filter_groups['group_details'][$group] = $groupData["name"];
	
}

?>
