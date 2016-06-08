<?
$groups_filter = array();
$childen_data_group = array();
$childen_data_thems = array();

foreach ($tpl['filters']['years'] as $value){
	$childen_data_years[] = array(
			'filter_id' => $value,
			'name'      => $value);
}
foreach ($tpl['filters']['search'] as $key=>$value){

	$childen_data_search[] = array(
			'filter_id' => $key,
			'name'      => $value['title']);
}

$news_by_theme = $news_class->getThemes($WhereParams);
foreach ($news_by_theme as $row){
    $childen_data_thems[] = array(
			'filter_id' => $row['theme'],
			'name'      => $ThemeArray[$row['theme']]);   
}

foreach ($source_type_array as $key=>$value){
	$childen_data_source[] = array(
			'filter_id' => $key,
			'name'      => $value);   
}

$tpl['filters']['All_groups'] = $news_class->getCountries($WhereParams);

//if(!$groups_filter = $cache->load("groups_of_news")) {  
    $Group = array();
	foreach ($tpl['filters']['All_groups'] as $value) {			
		//$Group[] = $rows["group"];
		 $childen_data_group[] = array('filter_id' => $value["group"],
    	                               'name'      => $value["name"]); 
	}
	/*
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
    		
    	    $i++;	 
	    }
	    ksort($childen_data_group);
	   
        	
			 
	}*/
	
	//$cache->save($groups_filter, "groups_of_news");		
//}




$filter_groups[] = $tpl['filter']['search']= array('name'=>'Поиск','filter_group_id'=>'sp','filter_group_id_full'=>'sp_s','filter'=>$childen_data_search);
$filter_groups[] = $tpl['filter']['years']= array('name'=>'Года','filter_group_id'=>'years','filter_group_id_full'=>'years','filter'=>$childen_data_years);
if($childen_data_group) $filter_groups[] = array('name'=>'Страны', 'filter_group_id'=>'group',  'filter_group_id_full'=>'groups', 'filter'=>$childen_data_group);

if($childen_data_thems) $filter_groups[] = $tpl['filter']['theme'] = array('name'=>'Тематика','filter_group_id'=>'theme','filter_group_id_full'=>'themes','filter'=>$childen_data_thems);

if($childen_data_source) $filter_source[] = $tpl['filter']['source'] = array('name'=>'Источник','filter_group_id'=>'source','filter_group_id_full'=>'sources','filter'=>$childen_data_source);


/*
$filter_groups['group_details'] = array();

foreach ((array)$groups as $group){
	$groupData = $shopcoins_class->getGroupItem($group);
	$filter_groups['group_details'][$group] = $groupData["name"];
	
}*/

?>
