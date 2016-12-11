<?

$childen_data_groups = array();
$childen_data_nominals = array();
$childen_data_years = array();
$childen_data_metals = array();
$childen_data_conditions = array();
$childen_data_thems = array();


$tpl['filter'] = array();
$tpl['filter']['groups'] = array();
$tpl['filter']['nominals'] = array();
$tpl['filter']['years'] = array();
$tpl['filter']['metals'] = array();
$tpl['filter']['conditions'] = array();
$tpl['filter']['thems'] = array();


$tpl['filters']['groups'] = $shopcoinsbyseries_class->getGroupsForFilter($tpl['one_series']["whereselect"]);
foreach ($tpl['filters']['groups'] as $value){
    $childen_data_groups[] = array(
			'filter_id' => $value["group"],
			'name'      => $value['name']);   
}
/*
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
//}*/

//if($groups_filter) $filter_groups[] = $groups_filter;


//фильтр по номиналам
//if(!$tpl['filters']['nominals'] = $cache->load("nominals".implode("_",$group_data)."_$cache_prefix")) { 
$tpl['filters']['nominals'] = $shopcoinsbyseries_class->getNominalsForFilter($groups,$tpl['one_series']["whereselect"]);
   //var_dump($tpl['filters']['nominals']); 
    

$cache_prefix = contentHelper::strtolower_ru("series_$id");

$tpl['filters']['metalls'] = $shopcoinsbyseries_class->getMetallsForFilter($groups,$tpl['one_series']["whereselect"],$nominals);	 
foreach ($tpl['filters']['metalls'] as $value){
    $childen_data_metal[] = array(
			'filter_id' => $value["metal_id"],
			'name'      => $value['name']);   
}

//var_dump($tpl['filters']['metalls']);

$tpl['filters']['conditions'] = $shopcoinsbyseries_class->getConditionsForFilter($groups,$tpl['one_series']["whereselect"],$nominals);
	
foreach ($tpl['filters']['conditions'] as $value){
    $childen_data_conditions[] = array(
			'filter_id' => $value["condition_id"],
			'name'      => $value["name"]);   
}

//var_dump($tpl['filters']['conditions']);

$tpl['filters']['years'] = $shopcoinsbyseries_class->getYearsForFilter($groups,$tpl['one_series']["whereselect"],$nominals);

foreach ($tpl['filters']['years'] as $value){
    $childen_data_years[] = array(
			'filter_id' => $value["year"],
			'name'      => ($value["year"])?$value["year"]:"Без указания года");   
} 

/*var_dump($tpl['filters']['years']);
	
foreach ($ThemeArray as $key=>$value){
	$childen_data_thems[] = array(
			'filter_id' => $key,
			'name'      => $value);   
}*/


foreach ($tpl['filters']['nominals'] as $value){
    $childen_data_nominals[] = array(
			'filter_id' => $value["nominal_id"],
			'name'      => $value["name"]);   
}


if( $childen_data_groups) $filter_groups[] = $tpl['filter']['groups'] = array('name'=>'Страны','filter_group_id'=>'group','filter_group_id_full'=>'groups','filter'=>$childen_data_groups);

if( $childen_data_nominals) $filter_groups[] = $tpl['filter']['nominals'] = array('name'=>'Номиналы','filter_group_id'=>'nominal','filter_group_id_full'=>'nominals','filter'=>$childen_data_nominals);


if($childen_data_years) $filter_groups[]  = $tpl['filter']['years']= array('name'=>'Года','filter_group_id'=>'year','filter_group_id_full'=>'years','filter'=>$childen_data_years);

if($childen_data_metal) $filter_groups[]  = $tpl['filter']['metals']= array('name'=>'Металл','filter_group_id'=>'metal','filter_group_id_full'=>'metals','filter'=>$childen_data_metal);

if($childen_data_conditions) $filter_groups[]  = $tpl['filter']['conditions']= array('name'=>'Состояние','filter_group_id'=>'condition','filter_group_id_full'=>'conditions','filter'=>$childen_data_conditions);

if($childen_data_thems) $filter_groups[] = $tpl['filter']['theme'] = array('name'=>'Тематика','filter_group_id'=>'theme','filter_group_id_full'=>'themes','filter'=>$childen_data_thems);


$filter_groups['group_details'] = array();


if($tpl['user']['user_id']==352480){
	//echo "<br>".time()." f end $i<br>";
}
?>
