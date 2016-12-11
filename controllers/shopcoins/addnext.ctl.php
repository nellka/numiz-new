<?
require $cfg['path'] . '/configs/config_shopcoins.php';
require_once $cfg['path'] . '/models/helpshopcoinsorder.php';

$data_result = array();
$data_result['error'] = null;
if(!$tpl['user']['user_id']) $data_result['error'] = "noauth";

$shopcoins = (integer)request('shopcoins');	

$helpshopcoinsorder_class = new model_helpshopcoinsorder($db_class);

//������ ��� ������� - �� �����������
$rows = $shopcoins_class->getRowByParams(array('shopcoins'=>$shopcoins));

if(!$rows||$rows["check"]==0) $data_result['error'] = "notavailable";
else {    
    $ShopcoinsMaterialtype = $rows["materialtype"];    
    if (in_array($ShopcoinsMaterialtype, array(8,6,7,4,2))) { 	
    	$result_amount = $helpshopcoinsorder_class->getAllByParams(array('shopcoins'=>$shopcoins));   
    	
    	$amountreserve = 0;
    	foreach ($result_amount as $rows_amount) {  	
    		if (time()-$rows_amount["reserve"] < $reservetime ) 
    			$amountreserve++;
    	}    	
    	if ( !$rows["amount"] ) $rows["amount"] = 1;
    	
    	if ($rows["amount"] <= $amountreserve) {    	
    		$data_result['error'] = "reserved"; 
    	}
    } else {    

    	if (time()-$rows["reserve"] > $reservetime || $rows['doubletimereserve']>time()) {
    		//��� ��� �� ������������
    		if ($rows['doubletimereserve']>time() || time()-$rows["reserve"] > $reservetime)
    			$data_result['error'] = "reserved";
    	}
    }
    
    if (($ShopcoinsMaterialtype==3 || $ShopcoinsMaterialtype==5) and $rows["amount"] < $amount) {
    	$data_result['error'] = "amount";
    }
}

if (!$data_result['error']){	
	if ($ShopcoinsMaterialtype == 1) {		
	    $data_update = array('doubletimereserve'=>$rows['reserve']+2*$reservetime, 
		                     'userreserve'=>$tpl['user']['user_id']);  	
    		                     
    	$shopcoins_class->updateRow($data_update,"shopcoins='$shopcoins'");       
	}
}


$data_result['bascetshopcoins'] = $shopcoins;
$data_result['datereserve'] = date('H:i',($rows['reserve']+2*$reservetime));	

echo json_encode($data_result);
die();
?>