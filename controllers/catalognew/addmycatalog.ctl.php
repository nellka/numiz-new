<?
header('Pragma: no-cache');

require($cfg['path'].'/models/catalognew.php');

$catalognew_class = new model_catalognew($db_class);

$data_result = array();
$data_result['error'] = null;
$data_result['value'] = false;

$error = array();

$catalog = (integer)request('catalog');
if(!$catalog){
    $data_result['error'] = "no item";
} elseif(!$tpl['user']['user_id']) {
    $data_result['error'] = "auth";
} else {    
    $item = $catalognew_class->getCatalognewmycatalogItem($tpl['user']['user_id'],$catalog);
    
	if ($item) $data_result['error'] = "Уже в коллекции";
}

if(!$data_result['error']) {

	$catalognew_class->addMycatalogItem($tpl['user']['user_id'],$catalog);				
	$data_result['value'] = true;
}

$data_result['error'] = $data_result['error'];
$data_result['valueid'] = $catalog;

echo json_encode($data_result);
die();

?>