<?
$filter_groups  = array(); 
$childen_data_metal = array();
$childen_data_years = array();
$childen_data_thems = array();
$childen_data_conditions = array();
$childen_data_nominals = array();
$childen_data_series = array();
$tpl['filter'] = array();

$tpl['filter']['metals'] = array();
$tpl['filter']['years'] = array();
$tpl['filter']['thems'] = array();
$tpl['filter']['conditions'] = array();
$tpl['filter']['nominals'] = array();
$tpl['filter']['series'] = array();
/*

монеты
наборы монет
мелочь
цветные
подарочные наборы
барахолка - что это
*/
            


$cache_prefix = contentHelper::strtolower_ru("$materialtype"."_".md5($search));
$cache_prefix .= $nocheck?"_nocheck":"";
$cache_prefix .= $mycoins?"_mycoins":"";


if($tpl['user']['user_id']==352480){    
    var_dump($cache_prefix,$search ,$mycoins);
	//echo time()." f st $i<br>";
}


if($mycoins) $cache_prefix .='mycoins_1_u_'.$tpl['user']['user_id'];
if($nocheck) $cache_prefix .='nocheck_'.$nocheck;

if(in_array($materialtype,array(1,7,8,6,4))){

	//if(!$tpl['filters']['metalls'] = $cache->load("metalls_$cache_prefix")) {	   
	    $tpl['filters']['metalls'] = $shopcoins_class->getMetalls(false,$groups,$nominals);	 
	   // $cache->save($tpl['filters']['metalls'], "metalls_".$cache_prefix);	    	 
	//} 	

	foreach ($tpl['filters']['metalls'] as $value){
	    $childen_data_metal[] = array(
				'filter_id' => $value["metal_id"],
				'name'      => $tpl['metalls'][$value["metal_id"]]);   
	}
}

if($tpl['user']['user_id']==352480){
	//echo time()." f metal $i<br>";
}
/*
монеты
наборы монет
мелочь
цветные
подарочные наборы
*/
if(in_array($materialtype,array(1,7,8,6,4,2))){
	//if(!$tpl['filters']['conditions'] = $cache->load("conditions_$cache_prefix")) {	   
	    $tpl['filters']['conditions'] = $shopcoins_class->getConditions(false,$groups,$nominals);
	    //$cache->save($tpl['filters']['conditions'], "conditions_$cache_prefix");	 
	//} 	
	
	foreach ($tpl['filters']['conditions'] as $value){
	    $childen_data_conditions[] = array(
				'filter_id' => $value["condition_id"],
				'name'      => $value["name"]);   
	}
}
if($tpl['user']['user_id']==352480){
	//echo time()." f condition $i<br>";
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

if(($nominals&&$groups)||($groups&&$materialtype==4)){
   // if(!$tpl['filters']['years'] = $cache->load("years_n_".implode('_',$nominals).'_g_'.implode('_',$groups))) {	   
	    $tpl['filters']['years'] = $shopcoins_class->getYears($nominals,$groups);
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
    
    if(!in_array($materialtype,array(5,3))&&($search != 'newcoins') ){
     
    	foreach ($yearsArray  as $key=>$value){
    		$childen_data_years[] = array('filter_id' => $key,'name' => $value['name']);
    	}
    	
    	$tpl['filter']['yearstart'] = (integer)$shopcoins_class->getMinYear($groups,$nominals);
    	
    	$tpl['filter']['yearend'] = (integer)$shopcoins_class->getMaxYear($groups,$nominals);				    
    	//$tpl['filter']['yearstart'] = 1600;
    	//$tpl['filter']['yearend'] = date("Y",time());
    }
}

if($tpl['user']['user_id']==352480){
	//echo time()." f year $i<br>";
}
/*
фильтр по  тематикам
Монеты
Мелочь
Цветные монеты
Банкноты*/
if(in_array($materialtype,array(1,8,6))){
	
	foreach ($ThemeArray as $key=>$value){
		$childen_data_thems[] = array(
				'filter_id' => $key,
				'name'      => $value);   
	}
}



//if(!$tpl['filter']['price']['max'] = $cache->load("price_max_$materialtype"."_".implode("_",$group_data))) {  
	$tpl['filter']['price']['max'] = (integer)$shopcoins_class->getMaxPrice($groups,$nominals);	
	//$cache->save($tpl['filter']['price']['max'], "price_max_$materialtype"."_".implode("_",$group_data));	
//}

if($tpl['user']['user_id']==352480){
	//echo time()." f maxprice $i<br>";
}
//if(!$tpl['filter']['price']['min'] = $cache->load("price_min_$materialtype".implode("_",$group_data))) {  
	$tpl['filter']['price']['min'] = (integer)$shopcoins_class->getMinPrice($groups,$nominals);	
	//$cache->save($tpl['filter']['price']['min'], "price_min_$materialtype".implode("_",$group_data));	
//}
if($tpl['user']['user_id']==352480){
	//echo time()." f minprice $i<br>";
}

//if(!$tpl['filters']['All_groups'] = $cache->load("all_groups_$cache_prefix")) {
    $tpl['filters']['All_groups'] = $shopcoins_class->getGroups();
    //$cache->save( $tpl['filters']['All_groups'], "all_groups_$cache_prefix");	
//}

if($tpl['user']['user_id']==352480){
    
    //var_dump($tpl['filters']['All_groups']);    
	//echo time()." f allgroups $i<br>";
}

if(!in_array($materialtype,array(5))){
     $gl_prefix = $c_en?'_en':'';
	 if(!$groups_filter = $cache->load("groups_$cache_prefix".$gl_prefix)) {  
	    $Group = array();
		foreach ($tpl['filters']['All_groups'] as $rows) {			
			$Group[] = $rows["group"];
		}
		
		if($Group){
			//информация о группе
			$groups_details = $shopcoins_class->getGroupsDetails($Group,false,$c_en);
			
			$GroupArray = array();
			foreach ($groups_details as $rows){	
				if ($rows["groupparent"]>0) {
					$Group[] = $rows["groupparent"];				
					if (!isset($GroupArray[$rows["groupparent"]])||!is_array($GroupArray[$rows["groupparent"]]))
						$GroupArray[$rows["groupparent"]] = Array();
				
					$GroupArray[$rows["groupparent"]][$rows["group"]] = $rows["name".$gl_prefix];
				}
			}
			
			$parentGroupsInfo = $shopcoins_class->getGroupsDetails($Group,true,$c_en);
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
		
		$cache->save($groups_filter, "groups_$cache_prefix".$gl_prefix);		
	}
	if($groups_filter) $filter_groups[] = $groups_filter;
}

//фильтр по номиналам
//if(!$tpl['filters']['nominals'] = $cache->load("nominals".implode("_",$group_data)."_$cache_prefix")) { 
    $tpl['filters']['nominals'] = $shopcoins_class->getNominals($group_data);
    
   // $cache->save($tpl['filters']['nominals'], "nominals".implode("_",$group_data)."_$cache_prefix");
//}
if($tpl['user']['user_id']==352480){
	//echo time()." f nominals $i<br>";
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

if( $childen_data_nominals) {    
    $filter_groups[] = $tpl['filter']['nominals'] = array('name'=>'Номиналы','filter_group_id'=>'nominal','filter_group_id_full'=>'nominals','filter'=>$childen_data_nominals,'materialtype'=>$materialtype);
}

if( $childen_data_series) $filter_groups[] = array('name'=>'Серии','filter_group_id'=>'series','filter_group_id_full'=>'seriess','filter'=>$childen_data_series,'materialtype'=>$materialtype);

if(($nominals&&$groups)||($groups&&$materialtype==4)){
    if($childen_data_years) $filter_groups[] = $tpl['filter']['years'] = array('name'=>'Года','filter_group_id'=>'years_p','filter_group_id_full'=>'years_p','filter'=>$childen_data_years);
} else {
    if($childen_data_years) $filter_groups[]  = $tpl['filter']['years']= array('name'=>'Года','filter_group_id'=>'years','filter_group_id_full'=>'years','filter'=>$childen_data_years);
}
if($childen_data_metal) $filter_groups[]  = $tpl['filter']['metals']= array('name'=>'Металл','filter_group_id'=>'metal','filter_group_id_full'=>'metals','filter'=>$childen_data_metal);

if($childen_data_conditions) $filter_groups[]  = $tpl['filter']['conditions']= array('name'=>'Состояние','filter_group_id'=>'condition','filter_group_id_full'=>'conditions','filter'=>$childen_data_conditions);

if($childen_data_thems) $filter_groups[] = $tpl['filter']['theme'] = array('name'=>'Тематика','filter_group_id'=>'theme','filter_group_id_full'=>'themes','filter'=>$childen_data_thems);

$filter_groups['group_details'] = array();


foreach ((array)$groups as $group){
	$groupData = $shopcoins_class->getGroupItem($group);
	$filter_groups['group_details'][$group] = $groupData["name".$gl_prefix];
	
}

if($tpl['user']['user_id']==352480){
	//echo "<br>".time()." f end $i<br>";
}
?>
