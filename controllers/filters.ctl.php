<?
$filter_groups  = array(); 
$childen_data_metal = array();
$childen_data_years = array();
$childen_data_thems = array();
$childen_data_conditions = array();
$childen_data_nominals = array();
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
	    $tpl['filters']['metalls'] = $shopcoins_class->getMetalls($materialtype);	 
	    $cache->save($tpl['filters']['metalls'], "metalls_$materialtype");	 
	} 	
	foreach ($tpl['filters']['metalls'] as $value){
	    $childen_data_metal[] = array(
				'filter_id' => $value["metal"],
				'name'      => $value["metal"]);   
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
	    $tpl['filters']['conditions'] = $shopcoins_class->getConditions($materialtype);
	    $cache->save($tpl['filters']['conditions'], "conditions_$materialtype");	 
	} 	
	
	foreach ($tpl['filters']['conditions'] as $value){
	    $childen_data_conditions[] = array(
				'filter_id' => $value["condition"],
				'name'      => $value["condition"]);   
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

if(!in_array($materialtype,array(5,3))&&($search != 'newcoins') ){
	foreach ($yearsArray  as $key=>$value){
		$childen_data_years[] = array('filter_id' => $key,'name' => $value['name']);
	}
							    
	$tpl['filter']['yearstart'] = 0;
	$tpl['filter']['yearend'] = date("Y",time());
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

$tpl['filter']['price']['min'] = 0;

if(!$tpl['filter']['price']['max'] = $cache->load("price_max_$materialtype")) {  
	$tpl['filter']['price']['max'] = (integer)$shopcoins_class->getMaxPrice($materialtype);
	$cache->save($tpl['filter']['price']['max'], "price_max_$materialtype");	
}


//($
if ($search == 'revaluation') {
    if(!$tpl['filters']['All_groups'] = $cache->load("revaluation_filter_group")) {
        $tpl['filters']['All_groups'] = $shopcoins_class->getGroups(0,1,0);
        $cache->save( $tpl['filters']['All_groups'], "revaluation_filter_group");	
    }
    
    $tpl['filters']['All_groups'] = $shopcoins_class->getGroups(0,1,0);
	$sql = "select distinct `group` 
	from shopcoins 
	where shopcoins.datereprice>0
	and (".($tpl['user']['user_id']==811?(!$nocheck?"shopcoins.check=1 or shopcoins.check>3":"shopcoins.check>3"):"shopcoins.check=1").")
	and shopcoins.dateinsert>0;";
} elseif ($search == 'newcoins') {    
	if(!$tpl['filters']['All_groups'] = $cache->load("newcoins_filter_group")) {
        $tpl['filters']['All_groups'] = $shopcoins_class->getGroups(0,0,1);
        $cache->save( $tpl['filters']['All_groups'], "newcoins_filter_group");	
    }
}	else {
    if(!$tpl['filters']['All_groups'] = $cache->load("all_groups_$materialtype")) {
        $tpl['filters']['All_groups'] = $shopcoins_class->getGroups($materialtype);
        $cache->save( $tpl['filters']['All_groups'], "all_groups_$materialtype");	
    }
}

if(!in_array($materialtype,array(5))){
	
	if(!$groups_filter = $cache->load("groups_$materialtype")) {  
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
		$cache->save($groups_filter, "groups_$materialtype"."_".$group);		
	}
	if($groups_filter) $filter_groups[] = $groups_filter;
}

//фильтр по номиналам
if(!$tpl['filters']['nominals'] = $cache->load("nominals_$materialtype".implode("_",$group_data))) { 
    $tpl['filters']['nominals'] = $shopcoins_class->getNominals($materialtype,$group_data);
    $cache->save($childen_data_nominals, "nominals_$materialtype".implode("_",$group_data));
}

foreach ($tpl['filters']['nominals'] as $value){
    $childen_data_nominals[] = array(
			'filter_id' => $value["name"],
			'name'      => $value["name"]);   
}
    
//}


if( $childen_data_nominals) $filter_groups[] = array('name'=>'Номинал','filter_group_id'=>'nominal','filter_group_id_full'=>'nominals','filter'=>$childen_data_nominals,'materialtype'=>$materialtype);

if($childen_data_years) $filter_groups[] = array('name'=>'Год','filter_group_id'=>'year','filter_group_id_full'=>'years','filter'=>$childen_data_years);
if($childen_data_metal) $filter_groups[] = array('name'=>'Металл','filter_group_id'=>'metal','filter_group_id_full'=>'metals','filter'=>$childen_data_metal);
if($childen_data_conditions) $filter_groups[] = array('name'=>'Состояние','filter_group_id'=>'condition','filter_group_id_full'=>'conditions','filter'=>$childen_data_conditions);
if($childen_data_thems) $filter_groups[] = array('name'=>'Тематика','filter_group_id'=>'theme','filter_group_id_full'=>'themes','filter'=>$childen_data_thems);

?>
