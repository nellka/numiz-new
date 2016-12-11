<?php
$ArrayResult = array();
$data_result  = array();
$data_result['error'] = null;

$group = (int)request('group');

if (!$tpl['user']['user_id']){	
	$data_result['error'] = "error2";
}

if (!$group){
	$data_result['error'] = "error1";
} else {
	$sql = "select distinct(nominal_id),trim(catalognew.name) as name from catalognew where catalognew.group='$group'";
	
	$catalog_names = $shopcoins_class->getDataSql($sql);
		
	$nominals = array();
	
	
	$i=0;
	foreach ($catalog_names as $rows) {	
		$nominals[]	= $rows['nominal_id'];
		$ArrayResult[$i]['nominal_id'] = $rows['nominal_id'];
		$ArrayResult[$i]['name'] = $rows['name'];
		$i++;
	}
	
	$sql = "select distinct(nominal_id),trim(name) as name from shopcoins where shopcoins.group='$group'";
	
	$catalog_names = $shopcoins_class->getDataSql($sql);
	
	foreach ($catalog_names as $rows) {	
		if(!in_array($rows['nominal_id'],$nominals)){
			$nominals[]	= $rows['nominal_id'];
			$ArrayResult[$i]['nominal_id'] = $rows['nominal_id'];
			$ArrayResult[$i]['name'] = $rows['name'];
			$i++;
		}
	}
		
	if(!$ArrayResult) $data_result['error'] = "error2";
}

if (!$data_result['error']) {	
	//$data_result['arrayresult'] = implode(",",$ArrayResult);
	$data_result['data'] = $ArrayResult;

}

echo json_encode($data_result);
die();