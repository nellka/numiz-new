<?
header("Access-Control-Allow-Origin:*");
header('Pragma: no-cache');

$shopcoins = (int)request('shopcoins');
$number = (int)request('number');
$delta = (int)request('delta');

$data_result = array();
$data_result['error'] = null;

if (!$tpl['user']['user_id']){
	$data_result['error'] = "auth";
} elseif (!$number)	$data_result['error'] = "error3";	

if (!$data_result['error']) {
	
	$sql = "select count(*) from a_priceclick where dateinsert>".(time()-30*24*3600)." and `user`='$user' and a_pricecoins = '$number';";
	$rowst = $shopcoins_class->getOneSql($sql);	
	if ($rowst==0) {		
		$sql = "select * from a_pricecoins where a_pricecoins = '$number' and `check`=1;";
		
		$rows = $shopcoins_class->getRowSql($sql);

		if ($rows) {		
			if($rows['price']>0) {
				$deltaprice = ceil($rows['price']*0.01);
					
				$newprice = $rows['price'] + $delta*$deltaprice;
				if ($newprice<1)
					$newprice = 1;
				$minprice = ($newprice<$rows['pricemin']?$newprice:$rows['pricemin']);
				$maxprice = ($newprice>$rows['pricemax']?$newprice:$rows['pricemax']);
				
				if ($tpl['user']['user_id']!=345555) {
					$shopcoins_class->setQuery("update a_pricecoins set `price`='$newprice',pricemin='$minprice', pricemax='$maxprice', amountclick=amountclick+1 where a_pricecoins = '$number'; ");
				}
				
				$data_ins = array('a_pricecoins'=>$number,
								  'pricefrom'=>$rows['price'],
								  'priceto'=>$newprice,
								  'user'=> $tpl['user']['user_id'],
								  'dateinsert'=>time());
				$shopcoins_class->addNewTableRecord('a_priceclick',$data_ins);
			}	else $newprice = 'R';
		}	else $data_result['error'] = "error4";
	} else $data_result['error'] = "error5";
}

//<scripteval>ShowPriceDelta</scripteval>";

if (!$data_result['error']){
    $data_result['number'] = $number;
    $data_result['newprice'] = $newprice;	
}

echo json_encode($data_result);
die();
?>