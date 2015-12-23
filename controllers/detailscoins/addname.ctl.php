<?php


$group = trim(request('group'));

if (!$tpl['user']['user_id']){	
	$data_result['error'] = "error2";
}

if (!$group)
	$data_result['error'] = "error1";
else {
	$group=iconv("UTF-8", "windows-1251//TRANSLIT", $group); 
	//echo $group;
	$catalog_names = $shopcoins_class->getCatalognewName($group);
	var_dump($catalog_names);
	die();
	//echo $sql;
	if ($catalog_names) {
	
		foreach ($result as $rows) {		
			$ArrayResult[] = $rows[0];
		}
	} else $data_result['error'] = "error2";
}

if (!$data_result['error']) {	
	$data_result['arrayresult'] = implode(",",$ArrayResult);
}

echo json_encode($data_result);

die();

?>