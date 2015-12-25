<?php

$group = trim(request('group'));
$id = trim(request('id'));
if (!$tpl['user']['user_id']){	
	$data_result['error'] = "error2";
}
$ArrayResult = array();
$data_result  = array();
$data_result['error'] = null;
if (!$id)
	$data_result['error'] = "error1";
else {
	//echo $group;
	$catalog_names = $shopcoins_class->getCatalognew($id);	
	
	if(!$catalog_names) $data_result['error'] = "error2";
	foreach ($catalog_names as $rows) {		
		$ArrayResult[] = $rows['name'];
	}
}

if (!$data_result['error']) {	
	$data_result['arrayresult'] = implode(",",$ArrayResult);
}

echo json_encode($data_result);
die();

?>