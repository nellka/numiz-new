<?
require($cfg['path'].'/models/catalogshopcoinsrelation.php');

$catalogshopcoinsrelation_class = new model_catalogshopcoinsrelation($db_class);

$data_result = array();
$data_result['error'] = null;
$data_result['value'] = false;

$error = array();

$catalog = (integer)request('catalog');

if(!$tpl['user']['user_id']) $data_result['error'] = "auth";
else {
    $rows2 = $catalogshopcoinsrelation_class->getRowByParams(array('shopcoins'=>$catalog));

	if ($rows2) {		
		if ($catalogshopcoinsrelation_class->getRow('catalogshopcoinssubscribe', array('catalog'=>$rows2['catalog'],'user'=>$tpl['user']['user_id']))) {			
			$error[] = 'У Вас есть такая заявка';
		}
	} else $error[] = "Монеты еще нет в каталоге";
	    
	if (!sizeof ($error)) {
	    $data_insert = array('user'=>$tpl['user']['user_id'], 
    		                 'catalog' => $rows2['catalog'], 
    		                 'dateinsert'=>time(), 
    		                 'datesend'=>0,
    		                 'amountdatesend'=>0,
    		                 'buy'=>0,
    		                 'amount'=>1);  	
		$shopcoins_class->addNew('catalogshopcoinssubscribe',$data_insert);		
		$data_result['value'] = true;
	} else {
		$data_result['error'] = implode(',',$error);
	}
}

$data_result['error'] = $data_result['error'];
$data_result['valueid'] = $catalog;

echo json_encode($data_result);
die();

?>