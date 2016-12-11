<?php

include($cfg['path']."/controllers/detailscoins/config.php");

$data_result  = array();
$data_result['error'] = "";

$copycoins = (int)request('copycoins');

if (!$tpl['user']['user_id']){	
	$data_result['error'] = "error2";
}


if (!$copycoins){
	$data_result['error'] = "error1";
} else {
	$sql = "select * from shopcoinswrite where shopcoinswrite='".$copycoins."';";
	$rows = $shopcoins_class->getRowSql($sql);	
	if ($rows) {		
		$RevMetalArray = array_flip($MetalArray);
		$RevConditionArray = array_flip($ConditionArray);

		if ($rows['metal'])
			$metal = $RevMetalArray[$rows['metal']];
		else
			$metal = 0;
			
		if ($rows['condition'])
			$condition = $RevConditionArray[$rows['condition']];
		else
			$condition = 0;
		
		$group_id = 0;	
		$group = $rows['group'];
		if($group){
			$sql = "select `group` from `group` where type='shopcoins' and name='$group'";				
			$group_id = (int) $shopcoins_class->getOneSql($sql);	
		}		
		$year = $rows['year'];
		
		$nominal_id = 0;		
		$name = $rows['name'];
		if($name){
			$sql = "select id from shopcoins_name where name='$name'";    	    
    	    $nominal_id = $shopcoins_class->getOneSql($sql);   
		}
		
		$details = $rows['details'];
		
	} 	else $data_result['error'] = "error2";
}

if (!$data_result['error']){
	$data_result['metal_id'] = $metal;
	$data_result['condition_id'] = $condition;
	$data_result['year'] = $year;
	$data_result['group'] = $group_id;
	$data_result['nominal_id'] = $nominal_id;
	$data_result['details'] = $details;
}


echo json_encode($data_result);
die();