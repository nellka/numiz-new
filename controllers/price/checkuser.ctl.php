<?
header('Pragma: no-cache');

require_once($cfg['path'].'/models/price.php');
$price_class = new model_priceshopcoins($db_class);

$number = (int)request('number');
$data_result['number'] = $number;

$error = array();

if (!$number) $data_result['error'] = "error3";	

if(!$tpl['user']['user_id']) $data_result['error'] = "error1";	

if (!$data_result['error']) {
	
	$sql = "select * from priceshopcoins where priceshopcoins = '$number' and checkuser=0;";
	
	$result = $price_class->getOneSql($sql);
	
	if ($result) {
	    $price_class->updateStatus($number,$tpl['user']['user_id']);
		$rows = mysql_fetch_array($result);
		mysql_query("update priceshopcoins set checkuser='$user' where priceshopcoins = '$number'; ");
	}	else $data_result['error'] = "error4";
}
echo json_encode($data_result);
die();  
?>