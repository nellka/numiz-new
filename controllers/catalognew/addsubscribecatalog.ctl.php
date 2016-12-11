<?
header('Pragma: no-cache');

require($cfg['path'].'/models/catalognew.php');

$catalognew_class = new model_catalognew($db_class);

$data_result = array();
$data_result['error'] = null;
$data_result['value'] = false;

$tig=0;

$error = array();

$catalog = (integer)request('catalog');

$amountacsessory = (integer)request('amountacsessory');

if(!$catalog){
    $data_result['error'] = "no item";
} elseif(!$tpl['user']['user_id']) {
    $data_result['error'] = "auth";
} else {    
    $item = $catalognew_class->getMyCatalogshopcoinssubscribeItem($tpl['user']['user_id'],$catalog);
    
	if ($item) {
	    if (!$amountacsessory){
			$data_result['error'] = "же есть такая заявка";
	    } else $tig=1;	    
	}
}


if (!$data_result['error']) {
	
	if (!$amountacsessory) $amountacsessory=1;

		if ($tig==1){
		    $catalognew_class->editMycatalogshopcoinssubscribeItem($tpl['user']['user_id'],$catalog,$amountacsessory);
		} else $catalognew_class->addMycatalogshopcoinssubscribeItem($tpl['user']['user_id'],$catalog,$amountacsessory);
			
		$data_result['value'] = true;
}

$data_result['error'] = $data_result['error'];
$data_result['valueid'] = $catalog;

echo json_encode($data_result);
die();
?>