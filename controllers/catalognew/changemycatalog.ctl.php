<?
header('Pragma: no-cache');

require($cfg['path'].'/models/catalognew.php');

$catalognew_class = new model_catalognew($db_class);

$data_result = array();
$data_result['error'] = null;
$data_result['value'] = false;

$tig=0;

$error = array();

$catalognewmycatalog = (integer)request('catalognewmycatalog');
$typechange = (integer)request('typechange');
$detailschange = (integer)request('detailschange');

if(!$catalognewmycatalog){
    $data_result['error'] = "no item";
} elseif(!$tpl['user']['user_id']) {
    $data_result['error'] = "auth";
} else {  
    $catalognew_class->changemycatalog($tpl['user']['user_id'],$catalognewmycatalog,$typechange,$detailschange);		
	$data_result['value'] = true;
}

$data_result['error'] = $data_result['error'];
$data_result['valueid'] = $catalognewmycatalog;

echo json_encode($data_result);
die();

?>