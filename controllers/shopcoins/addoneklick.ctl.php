<?
require_once $cfg['path'] . '/models/helpshopcoinsorder.php';
require_once $cfg['path'] . '/models/order.php';
require_once $cfg['path'] . '/models/orderdetails.php';
$shopcoins = (integer)request('shopcoins');
$onefio = request('onefio');
$onephone = request('onephone');
$amount = request('amount');

$helpshopcoinsorder_class = new model_helpshopcoinsorder($cfg['db']);
$order_class = new model_order($cfg['db']);
$orderdetails_class = new model_orderdetails($cfg['db']);

$data_result = array();
$data_result['error'] = null;

if (!$shopcoins || !$onefio || !$onephone){
	$data_result['error'] = "noshopcoins";
}

$amount = (integer)$amount?(integer)$amount:1;

$erroramount = "";

$rows = $shopcoins_class->getRowByParams(array('shopcoins'=>$shopcoins));

if(!$rows){
    $data_result['error'] = "noshopcoins";
} else  {
    $oldamount = $rows['amount'];
    $price = $rows['price'];    
    $ShopcoinsMaterialtype = $rows["materialtype"];
    
    if ($price< $minpriceoneclick) 	$data_result['error'] = "stopsummin";
    
    if ($rows['group'] == 1604)	$data_result['error'] = "notavailable";
    elseif ($ShopcoinsMaterialtype==8 || $ShopcoinsMaterialtype==7 || $ShopcoinsMaterialtype==4 || $ShopcoinsMaterialtype==2) {
    	$result_amount = $helpshopcoinsorder_class->getAllByParams(array('shopcoins'=>$shopcoins));    	
    	$amountreserve = 0;
    	foreach ($result_amount as $rows_amount) {    	
    		if (time()-$rows_amount["reserve"] < $reservetime ) 
    			$amountreserve++;
    	}    	
    	if (!$rows["amount"]) $rows["amount"] = 1;
    	
    	if ($rows["amount"] <= $amountreserve || $amount > ($rows["amount"] - $amountreserve)) {    	
    		$data_result['error'] = "reserved"; 
    		$erroramount = $rows["amount"];
    	}
    }  else {    
    	if ($rows["reserve"]>0 || $rows['doubletimereserve']>time()) {
    		if ((time()-$rows["reserve"] < $reservetime and $rows["reserveorder"] != $shopcoinsorder) || ($rows['doubletimereserve']>time() && $rows['userreserve']!=$cookiesuser && $cookiesuser>0))
    		$data_result['error'] = "reserved";
    	}
    }

    if (($ShopcoinsMaterialtype==3 || $ShopcoinsMaterialtype==5) and $rows["amount"] < $amount) {
    	$data_result['error'] = "amount";
    	$erroramount = $rows["amount"];
    }
    
    if ($rows["check"] == 0) $data_result['error'] = "notavailable";    
    
    if (!$data_result['error']) {
        $data_order = array('user'=>811,
                             'date'=>time(),
                             'type'=>'shopcoins',
                             'check'=>1,
                             'ip'=>$user_remote_address,
                             'delivery'=>12,
                             'payment'=>2,
                             'sum'=>$price,
                             'userfio'=>strip_tags($onefio),
                             'phone'=>strip_tags($onephone),
                             'FinalSum'=>$price);  
        $shopcoinsorder = $order_class->addNewRecord($data_order);
        
        $data_orderdetails = array('order'=>$shopcoinsorder,
                             'date'=>time(),
                             'catalog'=>$shopcoins,
                             'amount'=>$amount,
                             'typeorder'=>1);  
       
        $orderdetails_class->addNewRecord($data_orderdetails);
    	if (in_array($ShopcoinsMaterialtype,array(1,10,11,9,12))) {
    		$data_update = array('check'=>0, 
    		                     'reserveorder'=>$shopcoinsorder, 
    		                     'reserve' => 0, 
    		                     'amount' => 0, 
    		                     'doubletimereserve'=>0, 
    		                     'userreserve'=>0, 
    		                     'dateorder'=>time());    		
    	} elseif (in_array($ShopcoinsMaterialtype,array(4,7,8,2))){    	
    		$data_update = array('reserveorder'=>$shopcoinsorder,     		                     
    		                     'amount' => "amount-".$amount,     		                     
    		                     'dateorder'=>time());   
    		if($amount>=$oldamount) $data_update['check'] = 0;
              	
			/*$sql_update = "update shopcoins 
						set  amount=amount-".$amount."
						".($amount>=$oldamount?", `check`=0":"")."
						, reserveorder='".$shopcoinsorder.", dateorder=".time()."'
						where shopcoins='".$shopcoins."';";*/
    		
    	} elseif ($ShopcoinsMaterialtype == 3 || $ShopcoinsMaterialtype == 3 ) {
    	   $data_update = array('amount' => "amount-".$amount,     		                     
    		                     'dateorder'=>time());   
    		if($amount>=$oldamount) $data_update['check'] = 0;
                 	
    	 } 	else {
    	      $data_update = array('reserveorder'=>$shopcoinsorder,   		                     
    		                     'dateorder'=>time(),
    		                     'check' => 0);      		
        }
        $shopcoins_class->updateRow($data_update,"shopcoins='$shopcoins'");
        /*
    	$messageusers = "���� ������ ����� $shopcoinsorder �� ����� $price ���. � ��������� ������� ����� � ���� �������� ��� ���������. Numizmatik.Ru";
    	
    	$sqls = "SHOW TABLE STATUS LIKE 'smssend';";
    	$results = mysql_query($sqls);
    	$rowss = mysql_fetch_array($results);
    	$transactionsms = $rowss['Auto_increment'];
    	
    	include $in."smsconfig.php";
    
    	
    	$testingsms = sendsms2 (0,strip_string($onephone),$messageusers);
    	//echo " nnn=".$testing[0]." === ".$testing[1]." === ".$testing[2];
    	if ($testingsms !== false && sizeof($testingsms)>0 && ($testingsms[0] == 11 || $testingsms[0] == 0)) {
    	
    		$sqlsms = "insert into `smssend` (`smssend`,`user`,`phone`,`text`,`status`,`order`,`type`,`dateinsert`)
    				values (NULL,811,'".$testingsms[1]."','".$testingsms[2]."',1,0,0,".time().");";
    		mysql_query($sqlsms);
    	}*/
    }
    
    if (!$data_result['error']) {  
        $data_result['shopcoinsorder'] = $shopcoinsorder;
        $data_result['bascetshopcoins'] = $shopcoins;
        $data_result['bascetsum'] = $price;
        
    	$xml = "<error>none</error>
    	<shopcoinsorder>".$shopcoinsorder."</shopcoinsorder>
    	<bascetshopcoins>".$shopcoins."</bascetshopcoins>
    	<bascetsum>".$price."</bascetsum>";
    }   else    {
        $data_result['erroramount'] = ($erroramount?$erroramount:"");
        $data_result['bascetshopcoins'] = ($shopcoins?$shopcoins:"none");
    }
}
echo json_encode($data_result);
die();
?>