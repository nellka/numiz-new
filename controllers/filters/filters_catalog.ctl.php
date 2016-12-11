<?php

$filter_groups  = array(); 
$childen_data_metal = array();
$childen_data_conditions = array();
$childen_data_years = array();
$childen_data_thems = array();
$childen_data_nominals = array();

$groups_filter = array();

$tpl['filter'] = array();
$tpl['filter']['metals'] = array();
$tpl['filter']['childen_data_conditions'] = array();
$tpl['filter']['years'] = array();
$tpl['filter']['thems'] = array();
$tpl['filter']['nominals'] = array();

/*

монеты
наборы монет
мелочь
цветные
подарочные наборы
барахолка - что это
*/
            


$cache_prefix = contentHelper::strtolower_ru("catalog_$materialtype"."_".md5($search));


if(in_array($materialtype,array(1,7,8,6,4))){

	//if(!$tpl['filters']['metalls'] = $cache->load("metalls_$cache_prefix")) {	   
	    $tpl['filters']['metalls'] = $catalognew_class->getMetalls(false,$groups,$nominals,$WhereParams);	 
	   // $cache->save($tpl['filters']['metalls'], "metalls_".$cache_prefix);	    	 
	//} 	
	foreach ($tpl['filters']['metalls'] as $value){
	    $childen_data_metal[] = array(
				'filter_id' => $value["metal"],
				'name'      => $value['name']);   
	}
}
/*
if(in_array($materialtype,array(1,7,8,6,4,2))){
	//if(!$tpl['filters']['conditions'] = $cache->load("conditions_$cache_prefix")) {	   
	    $tpl['filters']['conditions'] = $catalognew_class->getConditions(false,$groups,$nominals);
	    //$cache->save($tpl['filters']['conditions'], "conditions_$cache_prefix");	 
	//} 	
	
	foreach ($tpl['filters']['conditions'] as $value){
	    $childen_data_conditions[] = array(
				'filter_id' => $value["condition_id"],
				'name'      => $value["name"]);   
	}
}
*/


$tpl['filter']['yearstart'] = 0;
$tpl['filter']['yearend'] = date("Y",time());

if(($nominals&&$groups)||($groups&&$materialtype==4)){
   // if(!$tpl['filters']['years'] = $cache->load("years_n_".implode('_',$nominals).'_g_'.implode('_',$groups))) {	   
	    $tpl['filters']['years'] = $catalognew_class->getYears($nominals,$groups);
	   // $cache->save($tpl['filters']['years'], "years_n_".implode('_',$nominals).'_g_'.implode('_',$groups));	 
	//} 	
	foreach ($tpl['filters']['years'] as $value){
	    $childen_data_years[] = array(
				'filter_id' => $value["year"],
				'name'      => ($value["year"])?$value["year"]:"Без указания года");   
	} 
	
} else {
    
    if($materialtype==2){        
        
        // unset($yearsArray[3]);
        unset($yearsArray[4]);
        unset($yearsArray[5]);
        unset($yearsArray[6]);       
    }  
    
    if(!in_array($materialtype,array(5,3))){
     
    	foreach ($yearsArray  as $key=>$value){
    		$childen_data_years[] = array('filter_id' => $key,'name' => $value['name']);
    	}
    	
    	$tpl['filter']['yearstart'] = (integer)$catalognew_class->getMinYear($groups,$nominals);    	
    	$tpl['filter']['yearend'] = (integer)$catalognew_class->getMaxYear($groups,$nominals);			    
    
    }
}

	
foreach ($ThemeArray as $key=>$value){
	$childen_data_thems[] = array(
			'filter_id' => $key,
			'name'      => $value);   
}


/*

//if(!$tpl['filter']['price']['max'] = $cache->load("price_max_$materialtype"."_".implode("_",$group_data))) {  
	$tpl['filter']['price']['max'] = (integer)$shopcoins_class->getMaxPrice($groups,$nominals,$bydate);	
	//$cache->save($tpl['filter']['price']['max'], "price_max_$materialtype"."_".implode("_",$group_data));	
//}

if($tpl['user']['user_id']==352480){
	//echo time()." f maxprice $i<br>";
}
//if(!$tpl['filter']['price']['min'] = $cache->load("price_min_$materialtype".implode("_",$group_data))) {  
	$tpl['filter']['price']['min'] = (integer)$shopcoins_class->getMinPrice($groups,$nominals,$bydate);	
	//$cache->save($tpl['filter']['price']['min'], "price_min_$materialtype".implode("_",$group_data));	
//}
if($tpl['user']['user_id']==352480){
	//echo time()." f minprice $i<br>";
}*/

//if(!$tpl['filters']['All_groups'] = $cache->load("all_groups_$cache_prefix")) {
    $tpl['filters']['All_groups'] = $catalognew_class->getGroups($WhereParams);
    
    //$cache->save( $tpl['filters']['All_groups'], "all_groups_$cache_prefix");	
//}


$gl_prefix = $c_en?'_en':'';
//if(!$groups_filter = $cache->load("catalog_groups_$cache_prefix".$gl_prefix)) {  
	$Group_all = array();
	foreach ($tpl['filters']['All_groups'] as $rows) {		    
		$Group_all[] = $rows["group"];
	}
	
	if($Group_all){
		//информация о группе
		$groups_details = $catalognew_class->getGroupsDetails($Group_all,false,$c_en);
		
		$GroupArray = array();
		foreach ($groups_details as $rows){	
			if ($rows["groupparent"]>0) {
				$Group_all[] = $rows["groupparent"];				
				if (!isset($GroupArray[$rows["groupparent"]])||!is_array($GroupArray[$rows["groupparent"]]))
					$GroupArray[$rows["groupparent"]] = Array();
			
				$GroupArray[$rows["groupparent"]][$rows["group"]] = $rows["name".$gl_prefix];
			}
		}
		
		$parentGroupsInfo = $catalognew_class->getGroupsDetails($Group_all,true,$c_en);
		$i=1;
		foreach ($parentGroupsInfo as $value){					
	        $sub_childen_data_group = array();	   
	    	if(isset($GroupArray[$value["group"]])){
	    	    foreach ($GroupArray[$value["group"]] as $k=>$v){
	    	        $sub_childen_data_group[] = array('filter_id' => $k,
	    	                                          'name'      => $v);   
	    	    }
	    	}
	    	//выносим Россию вверх в русском списке	
			$childen_data_group[(($value["group"]==407&&!$c_en)?0:$i)] = array('filter_id' => $value["group"],
		                             'name'      => $value["name".$gl_prefix],
		                             'child'     =>$sub_childen_data_group); 
		    $i++;	 
	    }
	    ksort($childen_data_group);
	    $groups_filter = array('name'=>($materialtype==5||$materialtype==3)?'Группы':'Страны',
	                                                     'filter_group_id'=>'group',
	                                                     'filter_group_id_full'=>'groups',
	                                                     'filter'=>$childen_data_group);
	    	
			 
	}
	
	$cache->save($groups_filter, "catalog_groups_$cache_prefix".$gl_prefix);		
//}
if($groups_filter) $filter_groups[] = $groups_filter;


//фильтр по номиналам
//if(!$tpl['filters']['nominals'] = $cache->load("nominals".implode("_",$group_data)."_$cache_prefix")) { 
    $tpl['filters']['nominals'] = $catalognew_class->getNominals($group_data,$WhereParams);
    
   // $cache->save($tpl['filters']['nominals'], "nominals".implode("_",$group_data)."_$cache_prefix");
//}
if($tpl['user']['user_id']==811){
	//var_dump($group_data,$WhereParams);//echo time()." f nominals $i<br>";
}
foreach ($tpl['filters']['nominals'] as $value){
    $childen_data_nominals[] = array(
			'filter_id' => $value["nominal_id"],
			'name'      => $value["name"]);   
}

 

//фильтр по сериям - пока отключаем

/*
if($materialtype&&$group_data){
	if(!$tpl['filters']['series'] = $cache->load("series_$cache_prefix".implode("_",$group_data))) { 
	    $tpl['filters']['series'] = $shopcoins_class->getFilterSeries($group_data);
	    
	    $cache->save($tpl['filters']['series'], "series_$cache_prefix".implode("_",$group_data));	
	}
	foreach ((array)$tpl['filters']['series'] as $value){  
	    $childen_data_series[] = array(
				'filter_id' => $value["series"],
				'name'      => $value["name"]);   
	}
}*/
if($tpl['user']['user_id']==352480){
	//echo time()." f series $i<br>";
}

//$filter_groups[] = $tpl['filter']['bydate'] = array('name'=>'Поступления по дате','filter_group_id'=>'bydate','filter_group_id_full'=>'bydate','filter'=>$childen_data_bydate);

if( $childen_data_nominals) {    
    $filter_groups[] = $tpl['filter']['nominals'] = array('name'=>'Номиналы','filter_group_id'=>'nominal','filter_group_id_full'=>'nominals','filter'=>$childen_data_nominals,'materialtype'=>$materialtype);
}

if(($nominals&&$groups)||($groups&&$materialtype==4)){
    if($childen_data_years) $filter_groups[] = $tpl['filter']['years'] = array('name'=>'Года','filter_group_id'=>'years','filter_group_id_full'=>'years','filter'=>$childen_data_years);
} else {
    if($childen_data_years) $filter_groups[]  = $tpl['filter']['years']= array('name'=>'Года','filter_group_id'=>'years_p','filter_group_id_full'=>'years_p','filter'=>$childen_data_years);
}
if($childen_data_metal) $filter_groups[]  = $tpl['filter']['metals']= array('name'=>'Металл','filter_group_id'=>'metal','filter_group_id_full'=>'metals','filter'=>$childen_data_metal);

if($childen_data_conditions) $filter_groups[]  = $tpl['filter']['conditions']= array('name'=>'Состояние','filter_group_id'=>'condition','filter_group_id_full'=>'conditions','filter'=>$childen_data_conditions);

if($childen_data_thems) $filter_groups[] = $tpl['filter']['theme'] = array('name'=>'Тематика','filter_group_id'=>'theme','filter_group_id_full'=>'themes','filter'=>$childen_data_thems);


$filter_groups['group_details'] = array();


foreach ((array)$groups as $_group){
	$groupData = $shopcoins_class->getGroupItem($_group);
	$filter_groups['group_details'][$_group] = $groupData["name".$gl_prefix];
	
}

if($tpl['user']['user_id']==352480){
	//echo "<br>".time()." f end $i<br>";
}
?>
