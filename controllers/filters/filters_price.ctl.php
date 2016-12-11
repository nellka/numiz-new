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

if($WhereParams['group']){
	$tpl['filters']['metalls'] = $price_class->getMetalls($WhereParams);	 
	
	foreach ($tpl['filters']['metalls'] as $value){	
		$wp_['group'] = $WhereParams['group'];	
		$wp_['metal'] = array($value["metal"]);	
		
		$nominals = $price_class->getNominals($wp_);
					
	    $NameArray = array();
	    
		foreach ($nominals as $rows){
			$NameArray[] = array('id'=>$rows["nominal_id"],'name'=>$rows["name"]);
		}
		$childen_data_metal[] = array(
			'filter_id' => $value["metal"],
			'name'      => $value['name'],
			'nominals'=> $NameArray);	   
	}

}

if($WhereParams['group']&&$WhereParams['nominal']){
	$wp_y = array();	
	$wp_y['group'] = $WhereParams['group'];
	$wp_y['nominal'] = $WhereParams['nominal'];	
	$wp_y['metal'] = $WhereParams['metal'];
	$tpl['filters']['years'] = $price_class->getYears($wp_y);
	
	foreach ($tpl['filters']['years'] as $value){        
   
		$wp_y['year'] = array($value["year"]);

		$childen_data_years[] = array(
                            'filter_id' => $value["year"],
                            'name'      => ($value["year"])?$value["year"]:"Без указания года",
                            'simbols' => $price_class->getSimbols($wp_y));   

	}
}
/*
//if(!$tpl['filters']['conditions'] = $cache->load("conditions_$cache_prefix")) {	   
$tpl['filters']['conditions'] = $price_class->getConditions($WhereParams);
foreach ($tpl['filters']['conditions'] as $value){
    $childen_data_conditions[] = array(
			'filter_id' => $value["condition_id"],
			'name'      => $value["name"]);   
}

$tpl['filter']['yearstart'] = 0;
$tpl['filter']['yearend'] = date("Y",time());

if($groups){
   $tpl['filters']['years'] = $price_class->getYears($WhereParams);
	
    foreach ($tpl['filters']['years'] as $value){
        $childen_data_years[] = array(
                            'filter_id' => $value["year"],
                            'name'      => ($value["year"])?$value["year"]:"Без указания года");   
    } 	
} else {     
    foreach ($yearsArray  as $key=>$value){
            $childen_data_years[] = array('filter_id' => $key,'name' => $value['name']);
    }

    $tpl['filter']['yearstart'] = (integer)$price_class->getMinYear($WhereParams);    	
    $tpl['filter']['yearend'] = (integer)$price_class->getMaxYear($WhereParams);			    

}

$tpl['filters']['simbol'] = $price_class->getSimbols($WhereParams);
foreach ($tpl['filters']['simbol'] as $value){
    $childen_data_simbols[] = array(
			'filter_id' => $value["simbol"],
			'name'      => $value["name"]);   
}

//$tpl['filter']['price']['max'] = (integer)$price_class->getMaxPrice($WhereParams);	

//$tpl['filter']['price']['min'] = (integer)$price_class->getMinPrice($WhereParams);	

if($tpl['user']['user_id']==352480){

}
*/
$tpl['filters']['All_groups'] = $price_class->getGroups($WhereParams);

$gl_prefix = $c_en?'_en':'';
$Group_all = array();
foreach ($tpl['filters']['All_groups'] as $rows) {		    
	$Group_all[] = $rows["group"];
}

if($Group_all){
	//информация о группе
	
	$GroupArray = array();
	
	$parentGroupsInfo = $shopcoins_class->getGroupsDetails($Group_all,false,$c_en);
	
	foreach ($parentGroupsInfo as $value){	       

		$childen_data_group[] = array('filter_id' => $value["group"],
	                             'name'      => $value["name".$gl_prefix]); 
    }

    $groups_filter = array('name'=>'Страны',
                                     'filter_group_id'=>'group',
                                     'filter_group_id_full'=>'groups',
                                     'filter'=>$childen_data_group);
    	
		 
}

//$cache->save($groups_filter, "catalog_groups_$cache_prefix".$gl_prefix);		

if($groups_filter) $filter_groups[] = $groups_filter;
/*
if($WhereParams['group']){

    $tpl['filters']['nominals'] = $price_class->getNominals($WhereParams);

    foreach ($tpl['filters']['nominals'] as $value){
        $childen_data_nominals[] = array(
                            'filter_id' => $value["nominal_id"],
                            'name'      => $value["name"]);   
    }
}*/


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

if($childen_data_simbols) $filter_groups[] = $tpl['filter']['simbols'] = array('name'=>'Символы','filter_group_id'=>'simbol','filter_group_id_full'=>'simbols','filter'=>$childen_data_simbols);


$filter_groups['group_details'] = array();


foreach ((array)$groups as $_group){
	$groupData = $shopcoins_class->getGroupItem($_group);
	$filter_groups['group_details'][$_group] = $groupData["name".$gl_prefix];
	
}

if($tpl['user']['user_id']==352480){
	//echo "<br>".time()." f end $i<br>";
}