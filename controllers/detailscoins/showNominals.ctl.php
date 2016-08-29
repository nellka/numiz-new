<?php
$group = trim(request('id'));

$data_result  = array();
$data_result['error'] = null;
$data_result['names'] = array();

$sql = "select distinct(nominal_id),name from catalognew where `group`='$group' order by name;";

$catalog_names = $shopcoins_class->getDataSql($sql);	
	
$i = 0;
foreach ($catalog_names as $rows) {		
	$data_result['names'][$i]['val'] = $rows['nominal_id'];
	$data_result['names'][$i]['text'] = $rows['name'];
	$i++;
}

echo json_encode($data_result);
die();

?>